=== Plugin Name ===
Contributors: skybox3d.com
Donate link: http://www.skybox3d.com/store/products/arcadepress-open-source-wordpress-plugin-php-arcade-script/
Tags: arcade, game, games, gaming, cms, flash, flashgames, arcade script, arcade-script, arcadepress
Requires at least: 2.8.0
Tested up to: 2.9.2
Stable tag: 0.65

== Description ==

ArcadePress is an open source arcade plugin for Wordpress that allows you to turn any Wordpress site into a full arcade site, including flash game uploads, categories, highscores, game feeds & more.

For full documentation or more gaming/webmaster related tools and projects, visit [Skybox3d.com](http://www.skybox3d.com/ "Skybox3d.com")

We think Wordpress has one of the richest sets of themes and plugins, allowing webmasters to accomplish literally 
anything they can dream of.  To that end, we have started work on ArcadePress, a plugin which extends Wordpress so that
you can literally setup a full flash arcade website with just a few clicks of the mouse.  Combining the powers of a 
modern PHP arcade script with extreme versatility of the Wordpress platform and it's massive developer base.

Here's what we have done in version 0.65:
* Add games from the admin panel
* Edit games from the admin panel
* Basic set of options including alternate content, game display options, etc.
* Shortcode for adding a specific game: [arcadepress display="game" primkey="15"]
* Shortcode for recent games: [arcadepress display="recentgames"]
* Shortcode for recent games with specific number of games to pull: [arcadepress display="recentgames" quantity="25"]
* Very basic admin dashboard widget
* Recent games sidebar widget
* Top games sidebar widget
* Each time a game plays it is counted

ArcadePress is a *GPL licensed* arcade plugin for Wordpress which is still in very early development.  Our intended
feature set is as follows:
* Add/edit flash games through the Wordpress admin panel.
* Each game creates it's own Wordpress page, allowing it to co-exist with other useful plugins (like sitemap plugins) as well as friendly URLs & comments.
* Each game has a name, thumbnail(s), small description, & full description
* Provide multiple sidebar and dashboard widgets
* Automatically import games from leading game feeds
* Record highscores from compatible games, using the Wordpress user system
* Provide markup to allow you to include games, descriptions, thumbnails, and more in any post or page.
* Eventually, we would like to also include optional full ArcadePress installations (Wordpress plus ArcadePress pre-installed) along with several custom designed Wordpress themes designed exxclusively for use with ArcadePress

== Installation ==

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

= Why is my ArcadePress "mainpage" empty, & what should I put there? = 
You have to create and/or select a Wordpress page to act as the base or "mainpage" of ArcadePress. 
All games that you add will be created as child pages of this page.  It is up to what you place 
on the mainpage, but we recommend using the ArcadePress shortcodes to show the game categories, 
the newest games, the most played games, and more.

= How do I add Recent Games and Top games to my posts and pages? =
Use shortcodes in your posts or pages.  For example, to display recent games added to your site, 
use this shortcode:
[arcadepress display="recentgames"]

To display the top played games, use:
[arcadepress display="topgames"]

The rest of the examples below apply to both recentgames and topgames shortcodes:

By default, these shortcodes list 10 games.  To show more or less, add this to the shortcode:
[arcadepress display="recentgames" quantity="20"]

In that example I increased the quantity to 20.

Now let's say you want to display the thumbnail image with each game, you would use this shortcode:
[arcadepress display="topgames" usepictures="true"]

Or let's say you want to display just thumbnails with no text:
[arcadepress display="recentgames" usepictures="true" usetext="false"]

In this final example, we'll use all the options to display just the single most played game:
[arcadepress display="topgames" quantity="1" usepictures="true" usetext="false"]

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

= 0.65 =
* "Use categories?" option was added to ArcadePress General Options
* Added shortcode: [arcadepress display="categories"] which displays a link to each game category in a list.
* Added category list widget
* Added shortcode: [arcadepress category="Action"] which will list games from a specific category, in the example, games from the Action category are listed.  You can also use usepictures, usetext, and quantity, just like you can with the other shortcodes.  For example, to display upto 20 games from the Adventure category using only thumbnails and no text, your shortcode would look like this: [arcadepress category="Adeventure" quantity="20" useimages="true" usetext="false"]

= 0.62 =
* Fixed issue with admin pages being unnaccessible

= 0.61 =
* No changes

= 0.6.0 =
* Had used 0.52 instead of 0.5.2, meaning I had to go up to 0.6.0 in order for the update to show as needed in the admin panel

= 0.5.3 = 
* Added usepictures and usetext options to the recent games, and top games shortcodes.
* Added [arcadepress display="topgames"] shortcode
* Added game play counting each time a game is displayed.
* Added total number of game plays to admin Dashboard widget
* Fixed and created most of the functionality for the Top Games Wordpress widget
* Added Recent Games Wordpress widget
* Fixed problem where admin may not validate when uploading a file, causing the file upload to fail
* Reorganized the main options page in a table to fix some layout issues

= 0.5.2 = 
* First public release

= 0.5.1 =
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
