## Intro ##
Brew Buddy makes it easier for brewers, and bars to feature their beer on their website.  Sort beers by what is on tap, and feature beers on your blog posts with the `[beer]` shortcode.

## Features ##
* Special Beer tab separates your beers from blog posts, and pages.
* Sort your beers by availaiblity, food pairings, custom tags, and beer style.
* Special fields for ABV, OG, IBU, untappd URL, and a photo gallery
* Interface matches existing WordPress interface
* Custom Shortcode `[beer]` will allow users to reference a beer in a blog or page.
* Bulk-edit sorting methods, such as availability, food pairings, and what's on tap.

## Shortcodes ##
* `[beer name="#NAME OF BEER" text="#URL TEXT FOR BEER"]` - Create a URL to a specified beer.  This link will also show a preview of the beer when you hover over it with your mouse.
    * `name` - The name of the beer you want to link to.  Case insensitive
    * `text` - (Optional) The text you want in the URL.  Defaults to the name of the beer.
* `[beer_list wrapper="div" show_description="true" sort="desc" style="BEER STYLE" on-tap="IS ON TAP" pairings="PAIRINGS TO SHOW" tags="TAGS TO SHOW" availability="AVAILABILITY TO SHOW"]`
    * 'wrapper' - (Optional) The HTML tag that wraps the list.  Defaults to div.
    * 'show_description' - (Optional) Show the beer excerpt (description) after the name of the beer.  Defaults to false.
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