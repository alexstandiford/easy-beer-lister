## Intro ##
Beer Buddy will make it easier for breweries to feature their beer on their website.

## Features ##
* Add custom fields to a page, and will set the template of the page to a beer buddy template to display the content.  It should run on most themes, but will run better on themes that are optimized for the plugin (obviously all of mine would support it out of the box.)
* Creates custom taxonomies to organize beer easily
* Support a dynamic default layout to accommodate the data that is given to it about the beer.
* Support WordPress theme structure, allowing developers to design for the plugin.
* Custom Shortcode `[beer name="Old Leghumper"]` will allow users to reference a beer in a blog page.
---
## How it will work ##

### Back-end Top-level menu: ###
Beer
* Add New Beer
* Manage Beers
* Settings

### The Beer page Creation Process: ###
* "Add new beer" Window pops up with form:
    * Beer Name
    * Beer Type
    * Beer Photo
    * Beer Description
    * Beer Video
    * ABV
    * Availability
    * O.G.
    * IBU
    * Pair With
    * Untappd URL
    * On Tap (Checkbox)
    * Tags
    * Beer Group (Custom Taxonomy)
* Click "Submit beer"

### Plugin: ###
* Creates page, makes beer name the title
* Checks for On Tap Checkbox.  Creates category if checked, and category doesn't exist
* Adds taxonomy to beer based on beer style.  Creates taxonomy if it doesn't exist
* Adds taxonomy to beer based on IBUs.  Creates taxonomy if it doesn't exist
* Adds taxonomy to beer based on O.G.  Creates taxonomy if it doesn't exist
* Adds taxonomy to beer based on ABV.  Creates taxonomy if it doesn't exist
* Checks Theme for compatible template, and set the template
* If theme doesn't exist, use the plugin-supplied fallback(s) instead
* Publishes page
* Add page to menu

### The Beer Edit Process: ###
* "Manage Beers" -> "Click on Beer to Edit" -> Window pops up with pre-populated form values
* Make Changes
* Click "Submit Changes"
* page Reloads with confirmation and CTA to view page

### The Beer Removal Process: ###
* "Manage Beers" > "Click on Beer to Delete" > Window pops up with pre-populated form values
* Click "Delete Beer" > Confirmation Window Pops Up
* Click "Delete Beer" again

### The Beer Hide Process: ###
* "Manage Beers" > "Click on Beer to Delete" > Window pops up with pre-populated form values
* Check "Hide Beer"
* Click "Submit Changes"
* page reloads with confirmation and CTA to view page

### Beer Shortcode: ###
* Creates a link that can be clicked on to go to that specific beer page
* Creates a script that enables hovering to learn more about that beer

## Options: ##
* Force fallback theme
    * Forces the plugin to use the fallback theme when loading a beer page
* Debug info
    * Shows debug information for support
* Disable JavaScript
 * Manually Disables JavaScript in settings pages
* Disable Beer Shortcode Popup
    * Disables the beer shortcode popup on-hover (Some themes won't play nice with this)
* Disable Default Beer Type Population
    * Disables the automatic population of beer styles on page reload

### Future Options (Perhaps in a paid version): ###
* Multiple Layouts
* Store Finder
* Import Beers
    * Imports CSV of beers
* Export Beers
    * Exports CSV of beers on website.  Useful when switching to a new website
* WYSIWYG support for `[beer]` shortcode
* Sidebar functionality