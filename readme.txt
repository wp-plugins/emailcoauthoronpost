=== Email CoAuthor On Post ===
Contributors: mrdenny
Donate Link: http://dcac.co/go/EmailCoAuthorOnPost
Tags: email
Requires at least: 3.0.1
Tested up to: 3.9
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

Fill out the "Email CoAuthor On Post" widget within the New Post editor.  Using the plugin only 
requires that the Name and email addresses are filled out.  Customizing the subject and body
on a per post basis is optional.

= Can multiple email addresses be emailed at once? =

Yes.  There are two options for doing this.  The first option is to use the "Email CoAuthor On Post" widget on
the new post editor and simply put a comma between each email address.  The second option is to add one "EmailTo" 
custom fields each one with a different email address.

= How do I change the default subject and body of the message? =

The default subject and message are configured via the settings page.

= How do I change the subject and body for one blog post? =

Fill out the "Email CoAuthor On Post" widget within the New Post editor.

= Can I personalize emails on a single blog post to multiple email addresses? =

No.  Only the first EmailToName value which the script finds will be used, and it will be used for 
all the emails which are sent out for that blog post.

= Can I customize the from email address? =

Not at this time.  I've you'd like to see this feature added please <a href="http://mrdenny.com/go/EmailCoAuthorOnPost">let me know</a>.

= What HTML is supported? =

Just about anything that you want to put into the email and that's supported by email clients can 
be used.  The plugin doesn't scrub or process the email in any way.  It simply passes the HTML 
that you supply and adds in the HTML headers to the email so that the email client that the user 
is using processes the HTML tags.

= The emails aren't showing up in HTML, what should I do? =

First check the Settings page to make sure that the Send as HTML checkbox is checked.  If it is
check that your email client supports HTML email.

== Screenshots ==

1. This screenshot shows the setting field in use.
2. The settings screen with values which have been filled out.
3. The custom fields overriding the default subject and body.
4. "Email CoAuthor On Post" widget on the new post editor.

== Supported Variables ==

* $domain - Replaced with the URL of the website
* $post_url - Replaced with the URL of the post which was published
* $title - Replaced with the title of the post which was published

== Changelog ==

= 2.3 =
* Addes option to have default subject and body shown for each post and page.
* Fixed Undefined variable errors from post/page meta-box.

= 2.2.2 =
* Fixed bug where people without the manage_options right could not log into the admin screen at all.


= 2.2.1 =
* Fixed the support and donate URLs.

= 2.2 =
* Added support for emailing someone when a page is published.

= 2.1 =
* Bug fixes related to sending emails to admins.

= 2.0 =
* Added the widget on the new post and new page editors.

= 1.6 =
* Added support for variables within the subject and body of the message.  Supported variables are $domain, $post_url and $title.  When processed the emails will be sent with the values of these variables replaced with the URL or the site, the URL of the post and the title of the post respectively.

= 1.5.2 =
* Tested for Wordpress 1.5.1

= 1.5.1 =
* Made the text box for the message body larger.

= 1.5 =
* Changed all the code to be wrapped within a class to minimize object name problems and to shorten function names.
* Added a banner message on the admin pages about the settings needing to be set after initial installation.
* Plugin only sends an email when the status is changed.  This fixes the problem of sending an email every time an already published post is updated.
* Added a security check on the settings page to prevent manual navigation to the settings page.
* Removed the settings link from the Installed Plugins page if the user doesn't have the right to edit the settings.


= 1.1 =
* Fixed the donation link on the settings page.

= 1.0 =
* Added support for HTML in the email body.
* Added the ability to email a list of addresses on every post being published.
* Optimized the PHP code a little.

= 0.1.1 =
* Moved the donation link on the settings page to a more appropriate location.
* Updated the FAQ.

= 0.1 =
* Added the settings screen.
* Added the ability to override the subject and body on a post by post basis.
* Added the ability to personalize the emails.
* Added support for multiple EmailTo fields (see FAQ).

= 0.0.1 =
* Birth of the plugin.

== Upgrade Notice ==
