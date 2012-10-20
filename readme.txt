=== Email CoAuthor On Post ===
Contributors: mrdenny
Donate Link: http://mrdenny.com/go/EmailCoAuthorOnPost
Tags: email
Requires at least: 3.0.1
Tested up to: 3.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Emails other people when you publish a blog post.

== Description ==

This plugin solves a missing feature in WordPress.  This missing feature is 
the ability to email the subject or co-author of the blog post when the post
which they assisted with is published.  This plugin solves this problem by 
allowing each blog post to have the ability to email a different email address
for each post as it is published.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `EmailCoAuthorOnPost.php` to the `/wp-content/plugins/EmailCoAuthorOnPost` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the global settings through the settings page.


== Frequently Asked Questions ==

= How do I change the set the email address to email? =

By adding a custom field to the blog post called "EmailTo", then putting the email address or 
addresses into the field.  Multiple email addresses can be used by placing them into a comma 
seperated list or as seperate custom fields.  If a comma seperated list is used a single
email will be sent.  If multple custom fields are used one email will be sent per custom field.

= How do I change the default subject and body of the message? =

The default subject and message are configured via the settings page.

= How do I change the subject and body for one blog post? =

The subject can be changed by adding a custom field to the blog post named "EmailSubject".  The
body of the message can be changed by adding a custom field to the blog post named "EmailBody".

= How do I personalize the email being sent out? =

By adding a custom field to the blog post named "EmailToName" and putting the name of the person 
being emailed in that field the email which is sent will be personalized.

= Can I personalize emails on a single blog post to multiple email addresses? =

No.  Only the first EmailToName value which the script finds will be used, and it will be used for 
all the emails which are sent out for that blog post.

= Can I customize the from email address? =

Not at this time.  I've you'd like to see this feature added please <a href="http://mrdenny.com/go/EmailCoAuthorOnPost">let me know</a>.

== Screenshots ==

1. This screenshot shows the setting field in use.
2. The settings screen with values which have been filled out.
3. The custom fields overriding the default subject and body.

== Changelog ==
= 0.1.1 =
* Moved the donation link on the settings page to a more appropriate location.
* Updated the FAQ

= 0.1 =
* Added the settings screen.
* Added the ability to override the subject and body on a post by post basis.
* Added the ability to personalize the emails.
* Added support for multiple EmailTo fields (see FAQ).

= 0.0.1 =
* Birth of the plugin.

== Upgrade Notice ==


