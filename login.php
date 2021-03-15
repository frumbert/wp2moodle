<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @author Tim St.Clair - timst.clair@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package local/wp2moodle
 * @version 1.0
 *
 * Moodle-end component of the wpMoodle Wordpress plugin.
 * Accepts user details passed across from Wordpress, creates a user in Moodle, authenticates them, and enrols them in the specified Cohort(s) or Group(s)
 *
 * 2012-05  Created
 * 2014-04  Added option to bypass updating user record for existing users
 *          Added option to enrol user into multiple cohorts or groups by specifying comma-separated list of identifiers
**/


global $CFG, $USER, $SESSION, $DB;

require('../../config.php');
require_once('locallib.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot."/lib/enrollib.php");

$SESSION->wantsurl = $CFG->wwwroot.'/';

$PASSTHROUGH_KEY = get_config('auth_wp2moodle', 'sharedsecret');
if (!isset($PASSTHROUGH_KEY)) {
	echo "Sorry, this plugin has not yet been configured. Please contact the Moodle administrator for details.";
}

$rawdata = $_GET['data'];
if (!empty($_GET)) {

	$auth 				= 'wp2moodle';

	// get the data that was passed in
	$userdata 			= decrypt_string($rawdata, $PASSTHROUGH_KEY);

	// time (in minutes) before incoming link is considered invalid
	$timeout 			= (integer) get_config('auth_wp2moodle', 'timeout');
	$matchfield 		= get_config('auth_wp2moodle', 'matchfield') ?: "idnumber";
	$matchvalue 		= "";
	$courseId 			= 0;

	// default field values for fields required by moodle
	$default_firstname 	= get_config('auth_wp2moodle', 'firstname') ?: "no-firstname";
	$default_lastname 	= get_config('auth_wp2moodle', 'lastname') ?: "no-lastname";

	// set a default idnumber prefix to help prevent index clashes on mdlw8_user_mneuse_uix
	$idnumber_prefix 	= get_config('auth_wp2moodle', 'idprefix') ?: "wp2m";
	$redirectnoenrol	= get_config('auth_wp2moodle', 'redirectnoenrol');

	// if userdata didn't decrypt, then timestamp will = 0, so following code will be bypassed anyway (e.g. bad data)
	$timestamp 			= (integer) get_key_value($userdata, "stamp"); // remote site should have set this to new DateTime("now").getTimestamp(); which is a unix timestamp (utc)
	$theirs				= new DateTime("@$timestamp"); // @ format here: http://www.gnu.org/software/tar/manual/html_node/Seconds-since-the-Epoch.html#SEC127
	$diff 				= floatval(date_diff(date_create("now"), $theirs)->format("%i")); // http://www.php.net/manual/en/dateinterval.format.php

	// check the timestamp to make sure that the request is still within a few minutes of this servers time
	if ($timestamp > 0 && ($timeout == 0 || $diff <= $timeout)) { // less than N minutes passed since this link was created or timeout=0, so it's still ok

		$username 			= trim(strtolower(get_key_value($userdata, "username"))); // php's tolower, not moodle's
		$hashedpassword 	= get_key_value($userdata, "passwordhash");
		$firstname 			= get_key_value($userdata, "firstname") ?: $default_firstname;
		$lastname 			= get_key_value($userdata, "lastname") ?: $default_lastname;
		$email 				= get_key_value($userdata, "email");
		$idnumber 			= $idnumber_prefix . get_key_value($userdata, "idnumber"); // the users id in the wordpress database, stored here for possible user-matching,  prefixed to avoid clashes
		$cohort_idnumbers 	= get_key_value($userdata, "cohort"); // the cohort to map the user user; these can be set as enrolment options on one or more courses, if it doesn't exist then skip this step
		$group_idnumbers 	= get_key_value($userdata, "group");
		$course_idnumbers 	= get_key_value($userdata, "course");

		// specifify either the activity order (topmost being 1, counting down the page);
		// or specify the activity id number - the XXX in moodle when your url is /mod/activitymame/view.php?id=XXX
		// specify one, not both.
		$activity 			= (integer) get_key_value($userdata, "activity"); // activity number to start at, > 0
		$cmid 				= (integer) get_key_value($userdata, "cmid"); // activity cmid

		// $updatefields = (get_key_value($userdata, "updatable") != "false"); // if true or not set, update fields like email, username, etc.
		$updatefields 		= (get_config('auth_wp2moodle', 'updateuser') === '1');
		$wantsurl 			= get_key_value($userdata, "url");

		// set DB record lookup match value; default = idnumber
		switch ($matchfield) {
			case "username": $matchvalue = $username; break;
			case "email":    $matchvalue = $email;    break;
			default:		 $matchvalue = $idnumber; $matchfield = "idnumber";
		}

		// find the user record (if it exists) and update if required
		if ($DB->record_exists('user', [$matchfield => $matchvalue])) {
			$updateuser = get_complete_user_data($matchfield, $matchvalue);
			if ($updatefields) {
				check_user_email($email);
				$updateuser->username 		= $username;
				$updateuser->email 			= $email;
				$updateuser->idnumber 		= $idnumber;
				$updateuser->firstname 		= $firstname;
				$updateuser->lastname 		= $lastname;
				$updateuser 				= truncate_user($updateuser); // typecast obj to array, works just as well
				$updateuser->timemodified 	= time(); // record that we changed the record

				$DB->update_record('user', $updateuser);
				\core\event\user_updated::create_from_userid($updateuser->id)->trigger();
			}
			$user = get_complete_user_data('id', $updateuser->id);
		} else {
			$authplugin = get_auth_plugin($auth);
			check_user_email($email);
			$updateuser = new stdClass();
			if ($newinfo = $authplugin->get_userinfo($username)) {
				$newinfo = truncate_user($newinfo);
				foreach ($newinfo as $key => $value){
					$updateuser->$key = $value;
				}
			}
			$updateuser->city 			= '';
			$updateuser->auth 			= $auth;
			$updateuser->policyagreed 	= 1;
			$updateuser->idnumber 		= $idnumber;
			$updateuser->username 		= $username;
			$updateuser->password 		= md5($hashedpassword); // manual auth checks password validity, so we need to set a valid password
			$updateuser->firstname 		= $firstname;
			$updateuser->lastname 		= $lastname;
			$updateuser->email 			= $email;
			$updateuser->lang 			= $CFG->lang;
			$updateuser->confirmed 		= 1; // don't want an email going out about this user
			$updateuser->lastip 		= getremoteaddr();
			$updateuser->timecreated 	= time();
			$updateuser->timemodified 	= $updateuser->timecreated;
			$updateuser->mnethostid 	= $CFG->mnet_localhost_id;
			$updateuser = truncate_user($updateuser);
			$updateuser->id = $DB->insert_record('user', $updateuser);
			\core\event\user_created::create_from_userid($user->id)->trigger();
			$user = get_complete_user_data('id', $updateuser->id);
		}

		// Entrol users to matched COHORTS
		if (!empty($cohort_idnumbers)) {
			$ids = explode(',',$cohort_idnumbers);
			foreach ($ids as $cohort) {
				if ($DB->record_exists('cohort', array('idnumber'=>$cohort))) {
					$cohortrow = $DB->get_record('cohort', array('idnumber'=>$cohort));
					if (!$DB->record_exists('cohort_members', array('cohortid'=>$cohortrow->id, 'userid'=>$user->id))) {
						// internally triggers cohort_member_added event
						cohort_add_member($cohortrow->id, $user->id);
					}

					// if the plugin auto-opens the course, then find the course this cohort enrols for and set it as the opener link
					if (get_config('auth_wp2moodle', 'autoopen') === '1')  {
						if ($enrolrow = $DB->get_record('enrol', array('enrol'=>'cohort','customint1'=>$cohortrow->id,'status'=>0))) {
							$courseId = $enrolrow->courseid;
						}
					}
				}
			}
		}

		// Enrol users to matched GROUPS
		if (!empty($group_idnumbers) && $redirectnoenrol === '0') {
			$ids = explode(',',$group_idnumbers);
			foreach ($ids as $group) {
				if ($DB->record_exists('groups', array('idnumber'=>$group))) {
					$grouprow = $DB->get_record('groups', array('idnumber'=>$group));
					$courseId = $grouprow->courseid;
					enrol_into_course($courseId, $user->id);
					if (!$DB->record_exists('groups_members', array('groupid'=>$grouprow->id, 'userid'=>$user->id))) {
						// internally triggers groups_member_added event
						groups_add_member($grouprow->id, $user->id);
					}
				}
			}
		}

		// Enrol users to matched COURSES
		if (!empty($course_idnumbers)) {
			$studentrow = $DB->get_record('role', array('shortname'=>'student'));
			$ids = explode(',', $course_idnumbers);
			foreach ($ids as $course) {
				if ($DB->record_exists('course', array('idnumber'=>$course))) {
					$courserow = $DB->get_record('course', array('idnumber'=>$course));
					if ($redirectnoenrol === '0') { // 0 = try enrol; 1 = skip enrol
						if (!enrol_try_internal_enrol($courserow->id, $user->id, $studentrow->id)) {
							continue;
						}
					}
					$courseId = $courserow->id;
				}
			}
		}

		// Work out STARTING URL
		if (get_config('auth_wp2moodle', 'autoopen') === '1')  {
			if ($courseId > 0) {
				$SESSION->wantsurl = new moodle_url('/course/view.php', array('id'=>$courseId));
			}
			// if an activity is specified, or a cmid has been specified, then work out its url.
			if ($activity > 0 || $cmid > 0) {
				$course = get_course($courseId);
				$modinfo = get_fast_modinfo($course);
				$index = 0;
				foreach ($modinfo->get_cms() as $cmindex => $cm) {
					if ($cm->uservisible && $cm->available) {
						// TODO fix this loop to account for contextual permissions to cm objects .. one day
						if (($index === $activity && $cmid === 0) || ($activity === 0 && $cmid === $cmindex)) {
							$SESSION->wantsurl = new moodle_url("/mod/" . $cm->modname . "/view.php", array("id" => $cmindex));
							break;
						}
						$index += 1;
					}
				}
			}
		}

		// Do we need to override the STARTING URL ?
		if (!empty($wantsurl)) {
			$SESSION->wantsurl = new moodle_url(rawurldecode($wantsurl));
		}

		// LOG IN
		$authplugin = get_auth_plugin($auth);
		if ($authplugin->user_login($user->username, $user->password)) {
			$user->loggedin = true;
			$user->site     = $CFG->wwwroot;
			complete_user_login($user);
		}
	}
}

// Finished - REDIRECT
redirect($SESSION->wantsurl);
