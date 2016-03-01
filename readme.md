## Intro ##
Brew Buddy makes it easier for brewers, and bars to feature their beer on their website.  Sort beers by what is on tap, and feature beers on your blog posts with the `[beer]` shortcode.

## Features ##
* Special Beer tab separates your beers from blog posts, and pages.
* Sort your beers by availaiblity, food pairings, custom tags, and beer style.
* Special fields for ABV, OG, IBU, untappd URL, and a photo gallery
* Interface matches existing WordPress interface
* Custom Shortcode `[beer]` will allow users to reference a beer in a blog or page.
* Bulk-edit sorting methods, such as availability, food pairings, and what's on tap.
* Generate print-able menus quickly and easily

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

### Print a menu ###
To print your menu, follow these steps:

1. Visit your WordPress Dashboard
2. On the left-hand side, hover over "Beers"
3. Click "Export Menu"
4. Click on the "View/Print Menu", located at the top of the page.
5. Print the file using your web browser settings (Usually located under file>>>print)
		* Tip: Set all margins to 0, and turn off header and footer settings when printing for best results

### Menu Configuration ###
There are many different options that can be set to configure what does (and does not) display on your menu.  These options are very similar to the configuration options that you can do with the `[beer_list]` shortcode.  To get to these options, follow these steps:

1. Visit your WordPress Dashboard
2. On the left-hand side, hover over "Beers"
3. Click "Export Menu"

Once there, you can configure the options shown therein.

#### Filters ####
Filters allow you to specify criteria that determines what displays on your menu.  These filters can be combined with other styles.

_Example: `style: Stout` and `pairings: Chocolate` will show all stouts that pair well with chocolate_

 * Pairings
 	* (multiple values, separated by commas) Only show beers with food pairings you specify
	* _example: chocolate,burger,bleu cheese_
 * Styles
 	* (multiple values, separated by commas) Only show beers with beer styles you specify
	* _example: stout,ipa,porter_
 * Tags
 	* (multiple values, separated by commas) Only show beers with tags you specify
	* _example: favorite,seasonal,christmas favorite_
 * Availability
 	* (multiple values, separated by commas) Only show beers with availability you specify
	* _example: winter,summer,fall_
	
#### Show/Hide Options ####
You can specify which items you want to show/hide from you menu.  Here's a list of what can be shown/hidden from the Export Menu options page (mentioned above):

  * Images
  * Description
  * Style
  * IBU
  * ABV
  * OG
  * Price

#### Customize CSS of Menu Page ####
Don't like the default CSS of the menu page?  You can add any style overrides inside of the `custom CSS overrides` field under the Export Menu.  This is added as an inline style to the head of the menu, so anything that you put in this document will override your defaults.