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
 * @package   auth_wp2moodle
 * @copyright 2012 onwards Tim St.Clair
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Wordpress 2 Moodle';

$string['settings_heading'] = 'Wordpress 2 Moodle';
$string['settings_description'] = 'Uses Wordpress user details to create user & log onto Moodle (one way Single Sign On)';

$string['settings_sharedsecret'] = 'Shared secret';
$string['settings_sharedsecret_desc'] = 'Encryption key which matches Wordpress';

$string['settings_timeout'] = 'Link timeout';
$string['settings_timeout_desc'] = 'Minutes before incoming link is considered invalid (use 0 for no expiry)';

$string['settings_logoffurl'] = 'Logoff Url';
$string['settings_logoffurl_desc'] = 'Url to redirect to if the user presses Logoff (optional)';

$string['settings_autoopen'] = 'Auto open course';
$string['settings_autoopen_desc'] = 'Automatically open the course after successful auth (uses first match in cohort or group)';

$string['settings_updateuser'] = 'Update user profile fields using Wordpress values?';
$string['settings_updateuser_desc'] = 'If set, user profile fields such as first and last name will be overwritten each time the SSO occurs. Turn this off if you want to let the user manage their profile fields independantly.';

$string['settings_redirectnoenrol'] = 'Only redirect user to course?';
$string['settings_redirectnoenrol_desc'] = 'If set, the user is being redirected to the course. Otherwise the user is enrolled into the course, if that has not been done already.';

$string['settings_firstname'] = 'First name (if empty)';
$string['settings_firstname_desc'] = 'If no first name is specified by Wordpress, use this value';

$string['settings_lastname'] = 'Last name (if empty)';
$string['settings_lastname_desc'] = 'If no last name is specified by Wordpress, use this value';

$string['settings_idprefix'] = 'Prefix for user idnumber';
$string['settings_idprefix_desc'] = 'Optional string value to store ahead of the idnumber to avoid clashes (default: wp2m). Warning: changing this after user have enrolled may disassociate their existing user records; apply with caution.';
