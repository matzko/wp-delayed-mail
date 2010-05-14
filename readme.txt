=== Delayed Email ===
Contributors: filosofo
Donate link: http://austinmatzko.com/wordpress-plugins/wp-delayed-mail/
Tags: comments, email, wp_mail
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 1.0

Send an email in the future.

== Description ==

Delay the sending of an email. `wp_delayed_mail` is the same as `wp_mail` except that the first argument passed to <code>wp_delayed_mail</code> is the UNIX epoch timestamp at which to send the email.

== Installation ==

1. Upload the `wp-delayed-email` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Send an email calling `wp_delayed_mail` just as you would `wp_mail`, except that the first argument is the UNIX epoch timestamp for the time at which you want the email to send.

== Frequently Asked Questions ==

= Why would I want to send an email in the future? =

That's up to you, but I wrote this plugin to act as part of a delayed auto-responder to a form submission.

== Changelog ==

= 1.0 =
* Introduced the plugin.
