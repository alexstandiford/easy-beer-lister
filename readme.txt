=== BrewBuddy ===
Contributors: tstandiford
Donate link: http://www.brewbuddy.io/recommends/donate
Tags: beer, beers, brewery, untappd, beer menu
Requires at least: 3.0.1
Tested up to: 4.4.2
Stable tag: 1.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Brew Buddy makes it easier for brewers, and bars to manage their beer information. Sort beers on your website, and create print-friendly menus.

== Description ==

## Features ##
* Special Beer Post Type separates your beers from blog posts, and pages.
* Sort your beers by availaiblity, food pairings, custom tags, and beer style.
* Special fields for ABV, OG, IBU, untappd URL, and video.
* Interface matches existing WordPress interface
* Custom Shortcode `[beer]` will allow users to reference a beer in a blog or page.
* Bulk-edit sorting methods, such as availability, food pairings, and what's on tap.
* Special Menus Post Type allows you to create auto-populating custom menus for quick printing, or display on a TV screen.

## Shortcodes ##
* `[beer name="#NAME OF BEER" text="#URL TEXT FOR BEER"]` - Create a URL to a specified beer.  This link will also show a preview of the beer when you hover over it with your mouse.
    * `name` - The name of the beer you want to link to.  Case insensitive
    * `text` - (Optional) The text you want in the URL.  Defaults to the name of the beer.
* `[beer_list wrapper="div" show_description="true" sort="desc" style="BEER STYLE" on-tap="IS ON TAP" pairings="PAIRINGS TO SHOW" tags="TAGS TO SHOW" availability="AVAILABILITY TO SHOW" show_price="FALSE"]`
    * 'wrapper' - (Optional) The HTML tag that wraps the list.  Defaults to div.
    * 'show_description' - (Optional) Show the beer excerpt (description) after the name of the beer.  Defaults to false.
    * 'show_price' - (Optional) Show the beer price after the description.  Defaults to false. **it is generally illegal to show your price publically.  This only exists for menu printing**
    * 'on-tap - (Optional) Only show beers that are on-tap.  defaults to false.
    * 'sort' - (Optional) Sorting method of the beer list.  Can be desc (descending), asc (ascending), or rand (random)
    * 'style' - (Optional) Name of the beer style(s) you want to show.  Separate each value by a comma (example: stout,lambic,pilsner,ipa)
    * 'pairings' - (Optional) Name of the beer food pairings(s) you want to show.  Separate each value by a comma (example: chocolate,cheesecake,steak)
    * 'tags' - (Optional) Name of the beer tags(s) you want to show.  Separate each value by a comma (example: featured,favorite,seasonal)
    * 'availability' - (Optional) Beer availability time(s) you want to show.  Separate each value by a comma (example: winter,summer,spring)

## Getting Started ##

### Add a Beer ###
To add a beer, follow these steps:

1. Visit your WordPress Dashboard
2. On the left-hand column, hover over "Beers"
3. Click "Add New"
4. Fill in all of the information on the page
5. Click "Publish"

### View Your Beers ###
Brew Buddy automatically builds the relevant pages for you, based on what information you add to your beers.  To view these pages, follow these steps:

#### View All Beers ####
To view all beers, you usually need to visit `YOURSITE.COM/beers/`.  If that doesn't work, follow these steps to figure out your beers URL:

1. Visit your WordPress Dashboard
2. On the left-hand side, hover over "Appearance"
3. Click "Menus"
4. On the left-hand column of the menu interface, click "Beers"
5. Click "View All"
6. Check "All Beers" and click "Add to Menu"
7. Click Save Menu.  This will add the All Beers link to your menu.
8. Click on the All Beers link in your menu to visit the page.
9. Optional - delete the All Beers link from your menu.

#### View Beer By Information ####

1. Visit your WordPress Dashboard
2. On the left-hand column, hover over "Beers"
3. Click on either "Beer Styles", "Pairings", "Availability", or "Tags", depending on which page you would like to view.
4. In the right-side of the interface, hover over the term you would like to sort by and click "view".

### Batch-edit Beers ###

One of the most powerful features of Brew Buddy is the ability to batch-edit your beer information.  To do this, follow these steps:

1. Visit your WordPress Dashboard
2. On the left-hand column, hover over "Beers"
3. Click on "All Beers"
4. Check all of the beers that you want to edit
5. Click on the "Bulk Actions" dropdown menu, and click "edit"
6. Click "Apply"
7. Apply the changes you want to make, and click "update"

## Creating Beer Menus ##
**NOTE** Menus are only visible to the user if they're logged in, so you don't have to worry about displaying your price.

### Add a Menu ###
To add a menu, follow these steps:
1. Visit your WordPress Dashboard
2. On the left-hand column, hover over "Menus"
3. Click "Add New"
4. Fill in all of the information on the page
5. Click "Publish"

### Print a Menu ###
To print a menu, follow these steps:

1. Navigate to the menu that you wish to print
	1. Visit your WordPress Dashboard
	2. On the left-hand column, hover over "Menus"
	3. Click "All Menus"
	4. Hover over the menu and click "View"
2. Print the menu
	1. click file>>>print (or press CONTROL+P)
	2. For best results, make sure the margins are set to none. (in Chromse this is found after pressing the "more settings" button)
	3. click Print

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Start adding your beers under Beers->Add New

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

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Initial Launch.  Hooray!

== Upgrade Notice ==
