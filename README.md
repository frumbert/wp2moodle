wp2moodle
=========

WordPress to Moodle (wp2moodle) is a plugin that allows users in WordPress to open Moodle courses without getting a logon box in between. It will also (optionally) enrol the user into cohorts, courses and groups.

It uses an encrypted link and **doesn’t rely on SSL / https** (though it’s recommended you use SSL where possible). Your WordPress and Moodle servers might be on the same host, or can be on different networks or server technologies. Since it only uses hyperlinks to communicate, there’s no special setup.

The plugin has these limitations by design:

- The users that created through this plugin can’t sign in to Moodle using their WordPress username – they must sign in from the link this plugin generates.
- You can’t go in reverse; i.e. log onto Moodle and be signed back into WordPress (using those users – other auth plugins still work)
- WordPress is not notified of any course results
- WordPress is not notified of any changes to the user profile done by Moodle (though the plugin normally disables the password)
- WordPress has no way of knowing if the values being linked to exist within Moodle (e.g. it doesn't check your work)

Data is encrypted (using aes-256-cbc via openssl) at the Wordpress end and handed over a standard http GET request. Only the minimum required information is sent in order to create a Moodle user record. The user is automatically created if not present at the Moodle end, and then authenticated, and (optionally) enrolled in a Cohort, a Group, or both.

How it works
------------

This plugin allows you to place a shortcode in a post that passes encrypted logon information to Moodle (requires this plugin to be also installed into Moodle). The user will be added to Moodle and optionally enrolled in the specified Cohort(s), Course(s) and/or Group(s).

Use the Moodle button on the rich editor to insert the shortcode, or enter the details manually using the examples below as a guide.

Example: `[wp2moodle class='css-classname' group='group1' cohort='class1' target='_blank' authtext='Please log on']launch the course[/wp2moodle]`


| Attribute | Kind | Purpose | Example |
| --- | --- | --- | --- |
| `class` | optional | defaults to 'wp2moodle'; CSS class attribute of link | `[wp2moodle course='abc1' class='wp2m-link']Open[/wp2moodle]` |
| `cohort` | optional, csv | idnumber of one or more cohorts in which to enrol a user | `[wp2moodle cohort='business_cert3']enrol in Cert 3 Business[/wp2moodle]` |
| `group` | optional, csv | idnumber of one or more groups in which to enrol | `[wp2moodle group='eng14_a,math14_b,hist13_c']Math, English & History[/wp2moodle]` |
| `course` | optional, csv | idnumber of one or more courses in which to enrol | `[wp2moodle course='abc1,abc2,def1']Enrol in 3 courses[/wp2moodle]` |
| `target` | optional | defaults to '_self'; href target attribute of link | `[wp2moodle course='abc1' target='_blank']Open[/wp2moodle]` |
| `authtext` | optional | string to display if not yet logged on, can be a shortcode | `[wp2moodle authtext='Please log on first' course='abc1']Open the course[/wp2moodle]` |
| `activity` | optional, depreciated | 1-based index (count) of visible activites | `[wp2moodle course='abc1' activity='2']Open course page[/wp2moodle]` |
| `cmid` |optional | Activity ID to open (e.g. /mod/plugin/view.php?id=XXX) | `[wp2moodle course='abc1' cmid='4683']Open course blog[/wp2moodle]` |
| `url` | optional | Url to open after logon (overrides everything else) | `/mod/customplugin/index.php?id=123` |


Requirements
------------
PHP 5.6+ (Reccomended: 7.3 or higher)
Moodle 3.1 or above (Reccomended: 3.6.4 or higher, last checked in 3.10.1+)
Wordpress 4 or above (Reccomended: 5.2.2 or higher, last checked in 5.7)
openssl extension on your php (you probably have this)

How to install this plugin
---------------------

1. download the plugin into a zip file named wp2moodle.zip
2. in wordpress choose `Plugins > Add New > Upload Plugin` and upload and active the plugin in the normal way
3. in moodle choose `Site Administration > Plugins > Install plugins` and upload and activate thie plugin in the normal way


Usage:
------
You can not use this plugin directly; it is launched by wp2moodle from within Wordpress.

*IMPORTANT*: when linking to things by their `id` make sure you use the moodle field `id number`. This is often blank by default - you need to set it.



Problems?
---------
If you are having problems, try these first. If you raise an issue, let me know ALL the version numbers of your installations, what server platform they are running on, and any relevent error messages, otherwise I won't be able to help.

1. Confirm that you have the requirement met to run the plugin (e.g. openssl must be installed and show up in phpinfo)
2. Confirm that your course has the appropriate enrolment providers set up already (e.g. cohort based enrolment or manual enrolment)
3. Confirm that your shortcode is working in Wordpress
4. Confirm that you are using the text/string version of an identifier and NOT the numerical id of a course or cohort. the Id Number field is NOT set by default in moodle- you have to add something.
5. Look in your sites php error log to see if you can see if the plugin is silently throwing an error that you are not seeing on the page. In Moodle you can turn on DEVELOPER DEBUGGING to reveal crashes or error messages.
6. If you're trying one lookup type (e.g. group) then try switching to a different type (e.g. cohort). This may help me narrow down if it's a particular lookup type that is affected.
7. If you are using IDNUMBER matching on the user, ensure you have a prefix set and the value isn't clashing with existing user records (e.g. mdlw8_user_mneuse_uix error)

Licence:
--------
GPL3, as per Moodle.