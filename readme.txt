=== Posterize ===
Contributors: Yan Sarazin
Tags: post
Requires at least: 3.1
Tested up to: 3.1
Stable tag: 0.1

WordPress to Posterous plugin.

== Description ==

Posterize will cross-post your Wordpress blog post to any of your Posterous sites. Post methods include a link back to your WordPress blog, or complete content. Posterous


== Installation ==

1. If you are upgrading, it is recommended that you deactivate the plugin from the Plugins page, and delete the posterize folder from your server.
2. Upload the unzipped contents to your /wp-content/plugins/ directory.
3. Active the plugin from the Plugins page.
4. Go to settings page to configure Posterize

== Configuration == 

1. Enter your Posterous email and password (used to log into http://posterous.com)
2. Click "Refresh site list" and select the Posterous site you want to publish to
3. Select Post Type
4. Save Settings

== Changelog ==

= 2.2 =
* Complete plugin rewrite.. again.
* Updating a published WordPress post will now update the Posterous post
* Pages no longer being published (Feature coming soon)
* Improved admin panel layout and design
* Selection of Posterous site changed to radio buttons

= 2.1.1 =
* Fixed missing line break issues.

= 2.1.0 =
* Including WordPress post tags if available
* Passing Posterize as the source in the Posterous API call

= 2.0.2 =
* Fixed issues where an error was thrown when “get sites” would return a single Posterous site.

= 2.0.1 =
* Complete rewrite from the ground up
* No longer uses email. Utilizes the Posterous API to interact with Posterous
* Ability to select which Posterous site to post to
* Administrator settings panel for easy configuration

= 1.0.1 =
* Changed Posterous email from post@posterous.com to posterous@posterous.com to avoid potential cross-post loop.

= 1.0.0 =
* Initial Posterize version.

