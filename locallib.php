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
 * decode a string encrypted with openssl
 */
function wp2m_base64_decode($b64) {
	return base64_decode(str_replace(array('-','_'),array('+','/'),$b64));
}
function wp2m_is_base64($string) {
    $decoded = base64_decode($string, true);
    // Check if there is no invalid character in string
    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;
    // Decode the string in strict mode and send the response
    if (!base64_decode($string, true)) return false;
    // Encode and compare it to original one
    if (base64_encode($decoded) != $string) return false;
    return true;
}

function decrypt_string($data, $key) {
	if ( wp2m_is_base64($key)) {
		$encryption_key = base64_decode($key);
	} else {
		$encryption_key = $key;
	}
	list($encrypted_data, $iv) = explode('::', wp2m_base64_decode($data), 2);
	return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

/**
 * querystring helper, returns the value of a key in a string formatted in key=value&key=value&key=value pairs, e.g. saved querystrings
 */
function get_key_value($string, $key) {
	$list = explode( '&', str_replace( '&amp;', '&', $string));
	foreach ($list as $pair) {
		$item = explode( '=', $pair);
		if (strtolower($key) == strtolower($item[0])) {
			return urldecode($item[1]); // not for use in $_GET etc, which is already decoded, however our encoder uses http_build_query() before encrypting
		}
	}
	return "";
}

// truncate_userinfo requires and returns an array
// but we want to send in and return a user object
function truncate_user($userobj) {
	$user_array = truncate_userinfo((array) $userobj);
	$obj = new stdClass();
	foreach($user_array as $key=>$value) {
		$obj->{$key} = $value;
	}
	return $obj;
}


/*
Issue: https://github.com/frumbert/wp2moodle--wordpress-/issues/10
Author: catasoft
Purpose, enrols everyone as student using the manual enrolment plugin
Todo:  do we trigger \core\event\user_enrolment_created::create() ??
*/
function enrol_into_course($courseid, $userid, $roleid = 5) {
	global $DB;
	$manualenrol = enrol_get_plugin('manual'); // get the enrolment plugin
	$enrolinstance = $DB->get_record('enrol',
		array('courseid'=>$courseid,
			'status'=>ENROL_INSTANCE_ENABLED,
			'enrol'=>'manual'
		),
		'*',
		MUST_EXIST
	);
	// retrieve enrolment instance associated with your course
	return $manualenrol->enrol_user($enrolinstance, $userid, $roleid); // enrol the user
}

function check_user_email($email) {
	global $SESSION;
    if (email_is_not_allowed($email)) {

        $failurereason = AUTH_LOGIN_FAILED;
        $event = \core\event\user_login_failed::create(['other' => ['username' => $username,
                                                                    'reason' => $failurereason]]);
        $event->trigger();
        // The username exists but the emails don't match. Refuse to continue.
        $reason = get_string('loginerror_invaliddomain', 'auth_wp2moodle');
        $errormsg = get_string('notloggedindebug', 'auth_wp2moodle', $reason);
        $SESSION->loginerrormsg = $errormsg;
        redirect(new moodle_url('/login/index.php'));
	}
}
