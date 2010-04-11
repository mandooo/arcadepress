=== Plugin Name ===
Contributors: skybox3d.com
Donate link: http://www.skybox3d.com/store/products/arcadepress-open-source-wordpress-plugin-php-arcade-script/
Tags: arcade, games, gaming, cms, flash, flashgames
Requires at least: 2.8.0
Tested up to: 2.9.2
Stable tag: 0.5.2

== Description ==

**THIS PLUGIN IS IN HEAVY DEVELOPMENT!  NOT ALL FEATURES ARE FUNCTIONAL**

ArcadePress is an open source arcade plugin for Wordpress that allows you to turn any Wordpress site into a full arcade site, including flash game uploads, categories, highscores, game feeds & more.

For full documentation or more gaming/webmaster related tools and projects, visit [Skybox3d.com](http://www.skybox3d.com/ "Skybox3d.com")

We think Wordpress has one of the richest sets of themes and plugins, allowing webmasters to accomplish literally 
anything they can dream of.  To that end, we have started work on ArcadePress, a plugin which extends Wordpress so that
you can literally setup a full flash arcade website with just a few clicks of the mouse.  Combining the powers of a 
modern PHP arcade script with extreme versatility of the Wordpress platform and it's massive developer base.

Here's what we have done in version 0.5.2:
*	Add games from the admin panel
*	Edit games from the admin panel
*	Basic set of options including alternate content, game display options, etc.
*	Shortcode for adding a specific game: [arcadepress display="game" primkey="15"]
*	Shortcode for recent games: [arcadepress display="recentgames"]
*	Shortcode for recent games with specific number of games to pull: [arcadepress display="recentgames" quantity="25"]
*	Very basic admin dashboard widget
*	Very useless but present sidebar widget

ArcadePress is a *GPL licensed* arcade plugin for Wordpress which is still in very early development.  Our intended
feature set is as follows:
*	Add/edit flash games through the Wordpress admin panel.
*	Each game creates it's own Wordpress page, allowing it to co-exist with other useful plugins (like sitemap plugins) as well as friendly URLs & comments.
*	Each game has a name, thumbnail(s), small description, & full description
*	Provide multiple sidebar and dashboard widgets
*	Automatically import games from leading game feeds
*	Record highscores from compatible games, using the Wordpress user system
*	Provide markup to allow you to include games, descriptions, thumbnails, and more in any post or page.
*	Eventually, we would like to also include optional full ArcadePress installations (Wordpress plus ArcadePress pre-installed) along with several custom designed Wordpress themes designed exxclusively for use with ArcadePress

== Installation ==

**THIS PLUGIN IS IN HEAVY DEVELOPMENT!  NOT ALL FEATURES ARE FUNCTIONAL**

The recommended way to install ArcadePress is to go into the Wordpress admin panel, and click on Add New under the 
Plugins menu.  Search for ArcadePress, and then click on Install, then click Install Now.  Once the installation 
completes, Activate the plugin

Or, if you want to install manually:

1. Download the ArcadePress.zip file
1. Extract the zip file to your hard drive, using a 7-zip or your archiver of choice.
1. Upload the `/arcadepress/` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new page, call it something like Arcade
1. Visit the ArcadePress admin page and select a "mainpage" for ArcadePress to use, like the Arcade page we told you to create in the last step

== Frequently Asked Questions ==

= Is this plugin stable or finished yet? =

Not at this time.  This plugin is in it's infancy and is still in the Alpha state as of this writing.

= What is ArcadePress intended to do? =

Allow Wordpress to have a full open source arcade script extension.  We have high ambitions for 
ArcadePress including highscore capabilities, dashboard and sidebar widgets, automatic game import 
from feeds, full game management, and much more. 

== Screenshots ==
 
 1. Add or edit a game
 2. General ArcadePress options

== Changelog ==

= 0.52 = 
* First public release

= 0.51 =
* Fixed a few minor bugs

= 0.5 =
* First draft of ArcadePress.

== Upgrade Notice ==

= 0.52 = 
* First public release

= 0.51 =
* Fixed a few minor bugs

= 0.5 =
Initial release
