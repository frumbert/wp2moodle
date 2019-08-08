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
 * Admin settings and defaults
 *
 * @package auth_wp2moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $wp2myesno = array(
        new lang_string('no'),
        new lang_string('yes'),
    );

    $settings->add(new admin_setting_heading('auth_wp2moodle/pluginname',
            '',
            new lang_string('settings_description', 'auth_wp2moodle')));

    $settings->add(new admin_setting_configtext('auth_wp2moodle/sharedsecret',
        new lang_string('settings_sharedsecret','auth_wp2moodle'),
    	new lang_string('settings_sharedsecret_desc', 'auth_wp2moodle'),
    	'this is not a secure key, change it'
    ));

    $settings->add(new admin_setting_configtext_with_maxlength('auth_wp2moodle/timeout',
        new lang_string('settings_timeout','auth_wp2moodle'),
    	new lang_string('settings_timeout_desc', 'auth_wp2moodle'),
    	'5',
    	PARAM_RAW, 4, 3
    ));

    $settings->add(new admin_setting_configtext('auth_wp2moodle/logoffurl',
        new lang_string('settings_logoffurl','auth_wp2moodle'),
    	new lang_string('settings_logoffurl_desc', 'auth_wp2moodle'),
    	''
    ));

    $settings->add(new admin_setting_configselect('auth_wp2moodle/autoopen',
        new lang_string('settings_autoopen', 'auth_wp2moodle'),
        new lang_string('settings_autoopen_desc', 'auth_wp2moodle'),
        1,
        $wp2myesno
    ));

    $settings->add(new admin_setting_configselect('auth_wp2moodle/updateuser',
        new lang_string('settings_updateuser', 'auth_wp2moodle'),
        new lang_string('settings_updateuser_desc', 'auth_wp2moodle'),
        1,
        $wp2myesno
    ));

    $settings->add(new admin_setting_configselect('auth_wp2moodle/redirectnoenrol',
        new lang_string('settings_redirectnoenrol', 'auth_wp2moodle'),
        new lang_string('settings_redirectnoenrol_desc', 'auth_wp2moodle'),
        0,
        $wp2myesno
    ));

    $settings->add(new admin_setting_configtext('auth_wp2moodle/firstname',
        new lang_string('settings_firstname','auth_wp2moodle'),
    	new lang_string('settings_firstname_desc', 'auth_wp2moodle'),
    	'empty-firstname'
    ));

    $settings->add(new admin_setting_configtext('auth_wp2moodle/lastname',
        new lang_string('settings_lastname','auth_wp2moodle'),
    	new lang_string('settings_lastname_desc', 'auth_wp2moodle'),
    	'empty-lastname'
    ));

    $settings->add(new admin_setting_configtext('auth_wp2moodle/idprefix',
        new lang_string('settings_idprefix','auth_wp2moodle'),
    	new lang_string('settings_idprefix_desc', 'auth_wp2moodle'),
    	'wp2m'
    ));

    // Display locking / mapping of profile fields.
    // $authplugin = get_auth_plugin('wp2moodle');
    // display_auth_lock_options($settings, $authplugin->authtype,
    //     $authplugin->userfields, get_string('auth_fieldlocks_help', 'auth'), false, false);

}