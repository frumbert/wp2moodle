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

$string['settings_invalidloginurl'] = 'Invalid Login Url';
$string['settings_invalidloginurl_desc'] = 'Url to redirect to if the user is suspended or deleted';

$string['settings_autoopen'] = 'Auto open course';
$string['settings_autoopen_desc'] = 'Automatically open the course after successful authentication';

$string['settings_updateuser'] = 'Update user profile fields using Wordpress values?';
$string['settings_updateuser_desc'] = 'When YES, user profile fields (firstname, lastname, email, idnumber) are updated to use the supplied values. Turn this off if you want to let the user manage their profile fields independantly.';

$string['settings_redirectnoenrol'] = 'Only redirect user to course?';
$string['settings_redirectnoenrol_desc'] = 'When YES, course enrolment is bypassed. The user will still be redirected to the course homepage (if not otherwise overridden).';

$string['settings_firstname'] = 'First name (if empty)';
$string['settings_firstname_desc'] = 'If no first name is specified by Wordpress, use this value';

$string['settings_lastname'] = 'Last name (if empty)';
$string['settings_lastname_desc'] = 'If no last name is specified by Wordpress, use this value';

$string['settings_matchfield'] = 'Field used to match';
$string['settings_matchfield_desc'] = 'When creating or matching users, use this database field to match records (default: idnumber).';

$string['settings_idprefix'] = 'Prefix for user idnumber';
$string['settings_idprefix_desc'] = 'Optional string value to store in front of of the idnumber to avoid clashes (default: wp2m).';

$string['notloggedindebug'] = 'The login attempt failed. Reason: {$a}';
$string['loginerror_invaliddomain'] = 'The email address is not allowed at this site.';

$string['settings_usernameemail'] = 'Set username to email';
$string['settings_usernameemail_desc'] = 'Prefer email to username in username field when creating or updating records.';