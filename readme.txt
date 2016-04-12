=== BrewBuddy ===
Contributors: tstandiford
Donate link: http://www.brewbuddy.io/recommends/donate
Tags: beer, beers, brewery, untappd, beer menu, bar, bartender, bars, restaurant, brewer, craft beer, craft bar, beer import
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: 1.02
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Brew Buddy makes it easier for brewers, and bars to manage their beer information. Sort beers on your website, and create print-friendly menus.

== Description ==

A quick overview:
https://vimeo.com/162600046
For more information, check out [http://www.brewbuddy.io](brewbuddy.io)

## Features ##
* Special Beer Post Type separates your beers from blog posts, and pages.
* Sort your beers by availaiblity, food pairings, custom tags, and beer style.
* Special fields for ABV, OG, IBU, untappd URL, video, and image gallery.
* Interface matches existing WordPress interface
* Custom Shortcode `[beer]` will allow users to reference a beer in a blog or page.
* Bulk-edit sorting methods, such as availability, food pairings, and what's on tap.
* Special Menus Post Type allows you to create auto-populating custom menus for quick printing, or display on a TV screen.

## Add-Ons ##
BrewBuddy has many optional add-ons that can make it even faster to manage your beers on your website.  [http://www.brewbuddy.io/downloads/untappd-importer/](Import your beers directly from Untappd), and more!

## Shortcodes ##
* `[beer]` - Create a URL to a specified beer.  This link will also show a preview of the beer when you hover over it with your mouse.
* `[beer_list]` - Create a list of beers based on specified parameters such as style, or pairings.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Start adding your beers under Beers->Add New
4. View the [https://github.com/tstandiford/brewbuddy/wiki/Getting-Started](Getting started guide) for more.

== Frequently Asked Questions ==

= How do I display an image of my beer =

When creating your Beer, set the "featured image" option in the sidebar to the beer image you wish to use.  Most themes automatically use this option out of the box.


= How do I display a beer on a page or post? =

Use the `[beer name=# text=#]` shortcode inside your page editor to add a beer to any page. Replace the "#" with the name of the beer, as well as the text that you want to show in the link.


= How do I display a list of all my beers? =

Use the `[beer_list]` shortcode inside your page editor to add a list of all your beers to any page.  See the getting started section to learn more about this shortcode.

= My printed menu looks funny =

Be sure to remove the default margins when you print your menu!

= When I hover over a beer, the preview popup is way off! =

Since BrewBuddy is designed to work with as many themes as possible, I created a way to adjust this hover.  Go to settings->BrewBuddy settings and set some offset values until you get it right.  Use trial and error until it looks good

== Screenshots ==
1.Beers get their own separate UI
2.Beer page works regardless of theme
3.Create multiple custom menus
4.Familiar interface makes adding beers easy
5.Batch edit what's on tap
6.Powerful filtering functions for print menus
7.Shortcodes display beers in blog posts and pages
8.Custom print menus can be created for extended functionality
== Changelog ==

= 1.00 =
* Initial Launch.  Hooray!

= 1.01 =
* Improved menu functionality
* Added sidebar to options page
* Menu template improvements

= 1.02 =
* Added a ton of hooks and actions so developers can modify the plugin without changing the core
* Added menu template dropdown option so custom templates can be more-easily made
* Built menu theme framework for developers
* Added option to add image galleries to beer.  This will allow developers to use photos of the beer for menus, and the website
* Overhauled the default print and TV menu templates, dramatically improving their functionality
* Added an option to override the number of beers shown per-column on a menu
* Added an option to override the CSS of a beer menu template

= 1.10 =
* Added hooks to beer info function
* Added ability to remove specific beers from a menu by name or ID
* Added extensions page
* General code clean-up

== Upgrade Notice ==

= 1.01 =
Improved menu functionality

= 1.02 =
Huge functionality upgrade. You must upgrade in-order to be supported by new themes

= 1.10 =
This update improves menu filtering functionality.