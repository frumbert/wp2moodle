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
 * CAS authentication plugin upgrade code
 *
 * @package    auth_cas
 * @subpackage wp2moodle
 * @copyright  2021 Tim St.Clair (tim.stclair@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade auth_cas.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_wp2moodle_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2020111500) {
        set_config('settings_matchfield', 'idnumber', 'auth_wp2moodle');
        upgrade_plugin_savepoint(true, 2020111500, 'auth', 'wp2moodle');
    }

    return true;
}

