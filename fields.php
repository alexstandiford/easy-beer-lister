<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function ebl_parse_taxonomy_checkbox($taxonomy){
	$terms = get_terms($taxonomy,['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	$result = [];
	
	foreach($terms as $term){
		$tax_meta = get_post_meta(get_the_ID(),$term->slug,true);
		if($tax_meta == 1){
			array_push($result, $term->slug);
		}
		
	};
		return $result;
	}

//---THE BEER PAGE META BOX FIELDS---//
function ebl_beer_meta_box($object, $box) {
	add_action('admin_footer', 'ebl_meta_scripts');?>
	<script>var set_to_post_id = <?php echo get_option( 'media_selector_attachment_id', 0 ); ?>;</script>
  <?php wp_nonce_field( basename( __FILE__ ), 'ebl_beer_nonce' ); ?>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">ABV</label><br>
    <?php _e( "Enter the ABV of the beer here. (do not include the % sign)"); ?>
    </p>
    <input class="widefat" type="number" step="0.01" name="ebl-abv" id="ebl-abv" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_abv', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">IBU</label><br>
    <?php _e( "Enter the IBU of the beer here."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="ebl-ibu" id="ebl-ibu" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_ibu', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">OG</label><br>
    <?php _e( "Enter the gravity of the beer here."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="ebl-og" id="ebl-og" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_og', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Price</label><br>
    <?php _e( "Enter the price of the beer here.  This is for menu exporting only, and should not show up on your website."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="ebl-price" id="ebl-price" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_price', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Untappd URL</label><br>
    <?php _e( "Enter the Untappd URL of the beer here."); ?>
    </p>
		<input class="widefat" type="text" name="ebl-untappd-url" id="ebl-untappd-url" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_untappd-url', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Video URL</label><br>
    <?php _e( "Enter the Video URL of the beer here"); ?>
    </p>
		<input class="widefat" type="text" step="0.01" name="ebl-video" id="ebl-video" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_video', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Image Gallery</label><br>
    <?php _e( "Select Images of this beer"); wp_enqueue_media();?>
    </p>
		<input class="hidden" type="text" name="ebl-gallery" id="image_attachment_id" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_gallery', true)); ?>" />
		<input type="button" class="button" name="ebl_gallery_button" id="upload_image_button" value="<?php _e( 'Upload/Select images' ); ?>" />
  </div>
<?php  }

//---THE BEER PAGE BREWER INFO META BOX FIELDS---//
function ebl_beer_brewer_info_meta_box($object, $box){?>
 <em>Not required, but useful for craft bars who serve beer from different locations</em>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Brewery Name</label><br>
    <?php _e( "Name of the Brewery this beer comes from."); ?>
    </p>
		<input class="widefat" type="text" name="ebl-brewer-name" id="ebl-brewer-name" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_brewer_name', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Brewery City</label><br>
    <?php _e( "City of the Brewery this beer comes from."); ?>
    </p>
		<input class="widefat" type="text" name="ebl-brewer-city" id="ebl-brewer-city" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_brewer_city', true)); ?>" />
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Brewery State</label><br>
    <?php _e( "State of the Brewery this beer comes from."); ?>
    </p>
		<input class="widefat" type="text" name="ebl-brewer-state" id="ebl-brewer-state" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_brewer_state', true)); ?>" />
  </div>
<?php }

//---THE MENU PAGE META BOX FIELDS---//
function ebl_menu_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'ebl_beer_nonce' ); ?>

  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">Menu Subheading</label><br>
    <?php _e( "Enter the subheading of the menu here"); ?>
    </p>
		<input class="widefat" type="text" id="ebl_export_menu_subheading" name="ebl_export_menu_subheading" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_export_menu_subheading', true)); ?>" />
  </div>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">Before Menu Text</label><br>
    <?php _e( "Enter the Text to enter right before the menu begins here"); ?>
    </p>
		<textarea class="widefat" id="ebl_export_menu_before_menu" name="ebl_export_menu_before_menu"><?php echo esc_attr(get_post_meta( $object->ID, 'ebl_export_menu_before_menu', true)); ?></textarea>
  </div>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">After Menu Text</label><br>
    <?php _e( "Enter the Text to enter right after the menu ends here"); ?>
    </p>
		<textarea class="widefat" id="ebl_export_menu_after_menu" name="ebl_export_menu_after_menu"><?php echo esc_attr(get_post_meta( $object->ID, 'ebl_export_menu_after_menu', true)); ?></textarea>
  </div>

<?php  }

//---THE MENU FILTER META BOX FIELDS---//
function ebl_menu_filters_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'ebl_beer_nonce' ); ?>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Only Show On-Tap Beers</label><br>
    <?php _e( "Only show beers that are marked as on-tap in the beers menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_ontap" name="ebl_export_ontap" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_ontap', true));  ?>/>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-style">Filter by Style</label><br>
    <?php _e( "Select the Beer Style you want to filter.  If all are unchecked, all beer styles will be used."); ?>
    </p>
<?php
	$ebl_terms = get_terms('style',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($ebl_terms as $ebl_term){?>
	<input type="checkbox" id="<?php echo $ebl_term->slug; ?>" name="<?php echo $ebl_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $ebl_term->slug, true));  ?>/>
	<label for="<?php echo $ebl_term->slug;?>"><?php echo $ebl_term->name; ?></label><br/>
<?php }; ?>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-availability">Filter by Availability</label><br>
    <?php _e( "Select the Beer Availability you want to filter.  If all are unchecked, all beer availabilities will be used."); ?>
    </p>
<?php
	$ebl_terms = get_terms('availability',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($ebl_terms as $ebl_term){
	if($ebl_term->slug != 'on-tap'){?>
	<input type="checkbox" id="<?php echo $ebl_term->slug; ?>" name="<?php echo $ebl_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $ebl_term->slug, true));  ?>/>
	<label for="<?php echo $ebl_term->slug;?>"><?php echo $ebl_term->name; ?></label><br/>
<?php };}; ?>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-tag">Filter by Beer Tag</label><br>
    <?php _e( "Select the Beer Tag you want to filter.  If all are unchecked, all beer tags will be used."); ?>
    </p>
<?php
	$ebl_terms = get_terms('tags',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($ebl_terms as $ebl_term){?>
	<input type="checkbox" id="<?php echo $ebl_term->slug; ?>" name="<?php echo $ebl_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $ebl_term->slug, true));  ?>/>
	<label for="<?php echo $ebl_term->slug;?>"><?php echo $ebl_term->name; ?></label><br/>
<?php }; ?>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Beers to Exclude</label><br>
    <?php _e( "List the name, or ID of the beers that you want to remove from your menu. This is useful when you have no other way to filter the beer out. One beer per line."); ?>
    </p>
		<textarea class="widefat" id="ebl_beers_to_filter" name="ebl_beers_to_filter"><?php echo esc_attr(get_post_meta( $object->ID, 'ebl_beers_to_filter', true)); ?></textarea>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Beers to Include</label><br>
    <?php _e( "List the name, or ID of the beers that you want to add from your menu. This is useful when you want to add a specific beer that doesn't fit any grouping otherwise. One beer per line."); ?>
    </p>
		<textarea class="widefat" id="ebl_beers_to_include" name="ebl_beers_to_include"><?php echo esc_attr(get_post_meta( $object->ID, 'ebl_beers_to_include', true)); ?></textarea>
  </div>
<?php  }

//---THE MENU SETTINGS META BOX FIELDS---//
function ebl_menu_settings_meta_box($object, $box){?>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">Override Beers Per Column</label><br>
    <?php _e( "Specify the number of beers per column (Uses template default if blank)"); ?>
    </p>
		<input type="number" id="ebl_beers_per_column" name="ebl_beers_per_column" value="<?php echo esc_attr(get_post_meta( $object->ID, 'ebl_beers_per_column', true)); ?>" />
  </div>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">CSS Overrides</label><br>
    <?php _e( "Add any custom CSS for this menu here."); ?>
    </p>
		<textarea class="widefat" id="ebl_export_menu_css" name="ebl_export_menu_css"><?php echo esc_attr(get_post_meta( $object->ID, 'ebl_export_menu_css', true)); ?></textarea>
  </div>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">Sort By</label><br>
    <?php _e( "Specify the Beer Value you want to sort by."); ?>
    </p>
        <select id="ebl_export_sortby" name="ebl_export_sortby">
					<option value="ebl_abv" <?php selected( get_post_meta( $object->ID,'ebl_export_sortby',true), 'ebl_abv');?>>ABV</option>
					<option value="ebl_ibu" <?php selected( get_post_meta( $object->ID,'ebl_export_sortby',true), 'ebl_ibu');?>>IBU</option>
					<option value="ebl_og" <?php selected( get_post_meta( $object->ID,'ebl_export_sortby',true), 'ebl_og');?>>OG</option>
					<option value="ebl_price" <?php selected( get_post_meta( $object->ID,'ebl_export_sortby',true), 'ebl_price');?>>Price</option>
					<option value="name" <?php selected( get_post_meta( $object->ID,'ebl_export_sortby',true), 'name');?>>Name</option>
				</select>
  </div>
  <div class="ebl-field ebl-full-width">
    <p class="label">
    <label for="ebl-abv">Sort Order</label><br>
    <?php _e( "Specify the order of the menu.  Ascending (A-Z), or Descending (Z-A)"); ?>
    </p>
        <select id="ebl_export_sort_order" name="ebl_export_sort_order">
					<option value="asc" <?php selected( get_post_meta( $object->ID, 'ebl_export_sort_order', true), 'asc');?>>Ascending</option>
					<option value="desc" <?php selected( get_post_meta( $object->ID, 'ebl_export_sort_order', true), 'desc');?>>Descending</option>
				</select>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer Image</label><br>
    <?php _e( "Show image of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_img" name="ebl_export_show_img" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_img', true));  ?>/>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer Description</label><br>
    <?php _e( "Show the description (also known as the excerpt) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_description" name="ebl_export_show_description" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_description', true));  ?>/>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer Style</label><br>
    <?php _e( "Show the style of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_style" name="ebl_export_show_style" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_style', true));  ?>/>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer OG</label><br>
    <?php _e( "Show the Original Gravity (OG) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_og" name="ebl_export_show_og" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_og', true));  ?>/>
  </div>
  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer IBU</label><br>
    <?php _e( "Show the International Bittering Units (IBU) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_ibu" name="ebl_export_show_ibu" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_ibu', true));  ?>/>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Brewer Name</label><br>
    <?php _e( "Show the name of the brewery that each beer came from."); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_brewer_name" name="ebl_export_show_brewer_name" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_brewer_name', true));  ?>/>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Brewer City</label><br>
    <?php _e( "Show the city that each beer came from."); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_brewer_city" name="ebl_export_show_brewer_city" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_brewer_city', true));  ?>/>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Brewer State</label><br>
    <?php _e( "Show the state that each beer came from."); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_brewer_state" name="ebl_export_show_brewer_state" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_brewer_state', true));  ?>/>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer ABV</label><br>
    <?php _e( "Show the Alcohol by Volume (ABV) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_abv" name="ebl_export_show_abv" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_abv', true));  ?>/>
  </div>

  <div class="ebl-field">
    <p class="label">
    <label for="ebl-abv">Show Beer Price</label><br>
    <?php _e( "Show the price of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="ebl_export_show_price" name="ebl_export_show_price" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'ebl_export_show_price', true));  ?>/>
  </div>
<?php
}

//---THE MENU TEMPLATE META BOX FIELDS---//
function ebl_menu_template_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'ebl_beer_nonce' ); ?>
  <div class="ebl-field ebl-full-width">
        <select id="ebl_menu_template" name="ebl_menu_template">
					<?php global $ebl_menu_templates;
					foreach($ebl_menu_templates as $template){ ?>
					<option value="<?php echo $template->slug; ?>" <?php selected( get_post_meta( $object->ID, 'ebl_menu_template', true), $template->slug);?>><?php echo $template->name; ?></option>
					<?php }; ?>
				</select>
  </div>
<?php  }

//---THE MENU VISIBILITY META BOX FIELDS---//
function ebl_menu_visibility_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'ebl_beer_nonce' ); ?>
  <div class="ebl-field ebl-full-width">
    <p>Leave this set to "private" if you don't want the general public to see the menu.</p>
    <p>Warning: Know your local law - it is usually not a good idea to show pricing of alcohol to the general public.</p>
    <select id="ebl_menu_public" name="ebl_menu_public">
     <option value="ebl_private" <?php selected( get_post_meta( $object->ID, 'ebl_menu_public', true), "ebl_private");?>>Private</option>
     <option value="ebl_public" <?php selected( get_post_meta( $object->ID, 'ebl_menu_public', true), "ebl_public");?>>Public</option>
    </select>
  </div>
<?php  }

//---THE BEER META BOX SUBMISSION AND VALIDATION---//
function ebl_save_beer_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['ebl_beer_nonce']) || !wp_verify_nonce($_POST['ebl_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('ebl_meta_item')){
  class ebl_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new ebl_meta_item('ebl_abv','ebl-abv'),
	new ebl_meta_item('ebl_ibu','ebl-ibu'),
	new ebl_meta_item('ebl_og','ebl-og'),
	new ebl_meta_item('ebl_price','ebl-price'),
	new ebl_meta_item('ebl_untappd-url','ebl-untappd-url'),
	new ebl_meta_item('ebl_video','ebl-video'),
	new ebl_meta_item('ebl_gallery','ebl-gallery'),
	new ebl_meta_item('ebl_brewer_name','ebl-brewer-name'),
	new ebl_meta_item('ebl_brewer_city','ebl-brewer-city'),
	new ebl_meta_item('ebl_brewer_state','ebl-brewer-state'),
	];
	foreach($metas as $meta){
	$meta->oldValue = get_post_meta( $post_id, $meta->theKey, true );
  /* If a new meta value was added and there was no previous value, add it. */
  if ( $meta->newValue && '' == $meta->oldValue )
    add_post_meta( $post_id, $meta->theKey, $meta->newValue, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $meta->newValue && $meta->newValue != $meta->oldValue )
    update_post_meta( $post_id, $meta->theKey, $meta->newValue, $meta->oldValue );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $meta->newValue && $meta->oldValue )
    delete_post_meta( $post_id, $meta->theKey, $meta->oldValue );
	}
}

//---THE MENU META BOX SUBMISSION AND VALIDATION---//
function ebl_save_menu_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['ebl_beer_nonce']) || !wp_verify_nonce($_POST['ebl_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('ebl_menu_meta_item')){
  class ebl_menu_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new ebl_menu_meta_item('ebl_export_menu_subheading','ebl_export_menu_subheading'),
	new ebl_menu_meta_item('ebl_export_menu_before_menu','ebl_export_menu_before_menu'),
	new ebl_menu_meta_item('ebl_export_menu_after_menu','ebl_export_menu_after_menu'),
	new ebl_menu_meta_item('ebl_export_menu_css','ebl_export_menu_css'),
	new ebl_menu_meta_item('ebl_export_ontap','ebl_export_ontap'),
	new ebl_menu_meta_item('ebl_export_show_style','ebl_export_show_style'),
	new ebl_menu_meta_item('ebl_export_show_og','ebl_export_show_og'),
	new ebl_menu_meta_item('ebl_export_show_ibu','ebl_export_show_ibu'),
	new ebl_menu_meta_item('ebl_export_show_abv','ebl_export_show_abv'),
	new ebl_menu_meta_item('ebl_export_show_price','ebl_export_show_price'),
	new ebl_menu_meta_item('ebl_export_show_img','ebl_export_show_img'),
	new ebl_menu_meta_item('ebl_export_show_description','ebl_export_show_description'),
	new ebl_menu_meta_item('ebl_export_sort_order','ebl_export_sort_order'),
	new ebl_menu_meta_item('ebl_export_sortby','ebl_export_sortby'),
	new ebl_menu_meta_item('ebl_beers_per_column','ebl_beers_per_column'),
	new ebl_menu_meta_item('ebl_beers_to_filter','ebl_beers_to_filter'),
	new ebl_menu_meta_item('ebl_beers_to_include','ebl_beers_to_include'),
	new ebl_menu_meta_item('ebl_menu_public','ebl_menu_public'),
	new ebl_menu_meta_item('ebl_export_show_brewer_name','ebl_export_show_brewer_name'),
	new ebl_menu_meta_item('ebl_export_show_brewer_city','ebl_export_show_brewer_city'),
	new ebl_menu_meta_item('ebl_export_show_brewer_state','ebl_export_show_brewer_state'),
	];

	//--- PUSHES STYLE TAXONOMY TO ARRAY ---//
	$ebl_terms = get_terms('style',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('ebl_menu_meta_item')){
		foreach($ebl_terms as $ebl_term){
			array_push($metas, new ebl_menu_meta_item($ebl_term->slug,$ebl_term->slug));
		};
	};
	//--- PUSHES AVAILABILITY TAXONOMY TO ARRAY ---//
	$ebl_terms = get_terms('availability',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('ebl_menu_meta_item')){
		foreach($ebl_terms as $ebl_term){
			array_push($metas, new ebl_menu_meta_item($ebl_term->slug,$ebl_term->slug));
		};
	};
	//--- PUSHES TAG TAXONOMY TO ARRAY ---//
	$ebl_terms = get_terms('tags',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('ebl_menu_meta_item')){
		foreach($ebl_terms as $ebl_term){
			array_push($metas, new ebl_menu_meta_item($ebl_term->slug,$ebl_term->slug));
		};
	};
	

	foreach($metas as $meta){
	$meta->oldValue = get_post_meta( $post_id, $meta->theKey, true );
  /* If a new meta value was added and there was no previous value, add it. */
  if ( $meta->newValue && '' == $meta->oldValue )
    add_post_meta( $post_id, $meta->theKey, $meta->newValue, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $meta->newValue && $meta->newValue != $meta->oldValue )
    update_post_meta( $post_id, $meta->theKey, $meta->newValue, $meta->oldValue );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $meta->newValue && $meta->oldValue )
    delete_post_meta( $post_id, $meta->theKey, $meta->oldValue );
	}
}

//---THE MENU TEMPLATE META BOX SUBMISSION AND VALIDATION---//
function ebl_save_menu_template_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['ebl_beer_nonce']) || !wp_verify_nonce($_POST['ebl_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('ebl_menu_template_meta_item')){
  class ebl_menu_template_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new ebl_menu_template_meta_item('ebl_menu_template','ebl_menu_template'),
	];

	foreach($metas as $meta){
	$meta->oldValue = get_post_meta( $post_id, $meta->theKey, true );
  /* If a new meta value was added and there was no previous value, add it. */
  if ( $meta->newValue && '' == $meta->oldValue )
    add_post_meta( $post_id, $meta->theKey, $meta->newValue, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $meta->newValue && $meta->newValue != $meta->oldValue )
    update_post_meta( $post_id, $meta->theKey, $meta->newValue, $meta->oldValue );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $meta->newValue && $meta->oldValue )
    delete_post_meta( $post_id, $meta->theKey, $meta->oldValue );
	}
}

function ebl_add_post_meta_boxes() {
  add_meta_box(
    'ebl-beer-info',                          // Unique ID
    esc_html__( 'Beer Info' ),                // Title
    'ebl_beer_meta_box',                      // Callback function
    'beers',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
  
    add_meta_box(
    'ebl-beer-brewer-info',                   // Unique ID
    esc_html__( 'Brewery Info' ),             // Title
    'ebl_beer_brewer_info_meta_box',          // Callback function
    'beers',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );

  add_meta_box(
    'ebl-menu-info',                          // Unique ID
    esc_html__( 'Menu Info' ),                // Title
    'ebl_menu_meta_box',                      // Callback function
    'menus',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
    
  add_meta_box(
    'ebl-menu-filters',                       // Unique ID
    esc_html__( 'Beer Filter Options' ),      // Title
    'ebl_menu_filters_meta_box',              // Callback function
    'menus',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
     
  add_meta_box(
    'ebl-menu-settings',                      // Unique ID
    esc_html__( 'Beer Menu Settings' ),       // Title
    'ebl_menu_settings_meta_box',             // Callback function
    'menus',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
  
  add_meta_box(
    'ebl-menu-template',                      // Unique ID
    esc_html__( 'Menu Template' ),            // Title
    'ebl_menu_template_meta_box',             // Callback function
    'menus',                                  // Admin page (or post type)
    'side',                                   // Context
    'default'                                 // Priority
  );
  
  add_meta_box(
    'ebl-menu-visibility',                    // Unique ID
    esc_html__( 'Menu Visibility' ),          // Title
    'ebl_menu_visibility_meta_box',           // Callback function
    'menus',                                  // Admin page (or post type)
    'side',                                   // Context
    'default'                                 // Priority
  );

}

function ebl_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'ebl_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'ebl_save_beer_meta', 10, 2);
	add_action( 'save_post', 'ebl_save_menu_meta', 10, 2);
	add_action( 'save_post', 'ebl_save_menu_template_meta', 10, 2);
}

add_action( 'load-post.php', 'ebl_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'ebl_post_meta_boxes_setup' );
?>
