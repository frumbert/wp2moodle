wp2moodle
=========

WordPress to Moodle (wp2moodle) is a plugin that allows users in WordPress to open Moodle courses without getting an icky logon box in between. It will also (optionally) enrol the user into cohorts or courses.

It uses an encrypted link and doesn’t rely on SSL (though it’s recommended you use SSL where possible). Your WordPress and Moodle servers might be on the same host, or can be on different networks or server technologies. Since it only uses hyperlinks to communicate, there’s no special setup.

The plugin has these limitations by design:

- The users that created through this plugin can’t sign in to Moodle using their WordPress username – they must sign in from the link this plugin generates.
- You can’t go in reverse; i.e. log onto Moodle and be signed back into WordPress (using those users – other auth plugins still work)
- WordPress is not notified of any course results
- WordPress is not notified of any changes to the user profile done by Moodle (though the plugin normally disables the password)
- WordPress has no way of knowing if the values being linked to exist within Moodle (e.g. it doesn't check your work)

Data is encrypted (using aes-256-cbc via openssl) at the Wordpress end and handed over a standard http GET request. Only the minimum required information is sent in order to create a Moodle user record. The user is automatically created if not present at the Moodle end, and then authenticated, and (optionally) enrolled in a Cohort, a Group, or both.

Requirements
------------
Moodle 3.1 or above (Reccomended: 3.6.4 or higher)
Wordpress 4 or above (Reccomended: 5.2.2 or higher)
openssl extension on your php (you probably have this)

Demo
-----
http://wordpress.frumbert.org/

How to install this plugin
---------------------

1. download the plugin into a zip file named wp2moodle.zip
2. in wordpress choose `Plugins > Add New > Upload Plugin` and upload and active the plugin in the normal way
3. in moodle choose `Site Administration > Plugins > Install plugins` and upload and activate thie plugin in the normal way


Usage:
------
You can not use this plugin directly; it is launched by wp2moodle from within Wordpress.

Note, when linking to things by their `id` make sure you use the moodle field `id number` - ids are text, not numbers.

Problems?
---------
If you are having problems, try these first. If you raise an issue, let me know ALL the version numbers of your installations, what server platform they are running on, and any relevent error messages, otherwise I won't be able to help.

1. Confirm that you have the requirement met to run the plugin (e.g. openssl must be installed and show up in phpinfo)
2. Confirm that your course has the appropriate enrolment providers set up already (e.g. cohort based enrolment or manual enrolment)
3. Confirm that your shortcode is working in Wordpress
4. Confirm that you are using the text/string version of an identifier and NOT the numerical id of a course or cohort. the Id Number field is NOT set by default in moodle- you have to add something.
5. Look in your sites php error log to see if you can see if the plugin is silently throwing an error that you are not seeing on the page.
6. If you're trying one lookup type (e.g. group) then try switching to a different type (e.g. cohort). This may help me narrow down if it's a particular lookup type that is affected.

Licence:
--------
GPL3, as per Moodle.

