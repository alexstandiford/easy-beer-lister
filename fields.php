<?php
function tasbb_parse_taxonomy_checkbox($taxonomy){
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
function tasbb_beer_meta_box($object, $box) {
	add_action('admin_footer', 'tasbb_meta_scripts');?>
	<script>var set_to_post_id = <?php echo get_option( 'media_selector_attachment_id', 0 ); ?>;</script>
  <?php wp_nonce_field( basename( __FILE__ ), 'tasbb_beer_nonce' ); ?>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">ABV</label><br>
    <?php _e( "Enter the ABV of the beer here. (do not include the % sign)"); ?>
    </p>
    <input class="widefat" type="number" step="0.01" name="tasbb-abv" id="tasbb-abv" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_abv', true)); ?>" />
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">IBU</label><br>
    <?php _e( "Enter the IBU of the beer here."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="tasbb-ibu" id="tasbb-ibu" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_ibu', true)); ?>" />
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">OG</label><br>
    <?php _e( "Enter the gravity of the beer here."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="tasbb-og" id="tasbb-og" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_og', true)); ?>" />
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Price</label><br>
    <?php _e( "Enter the price of the beer here.  This is for menu exporting only, and should not show up on your website."); ?>
    </p>
		<input class="widefat" type="number" step="0.01" name="tasbb-price" id="tasbb-price" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_price', true)); ?>" />
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Untappd URL</label><br>
    <?php _e( "Enter the Untappd URL of the beer here."); ?>
    </p>
		<input class="widefat" type="text" name="tasbb-untappd-url" id="tasbb-untappd-url" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_untappd-url', true)); ?>" />
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Video URL</label><br>
    <?php _e( "Enter the Video URL of the beer here"); ?>
    </p>
		<input class="widefat" type="text" step="0.01" name="tasbb-video" id="tasbb-video" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_video', true)); ?>" />
  </div>
	<div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Image Gallery</label><br>
    <?php _e( "Select Images of this beer"); wp_enqueue_media();?>
    </p>
		<input class="hidden" type="text" name="tasbb-gallery" id="image_attachment_id" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_gallery', true)); ?>" />
		<input type="button" class="button" name="tasbb_gallery_button" id="upload_image_button" value="<?php _e( 'Upload/Select images' ); ?>" />
  </div>
<?php  }

//---THE MENU PAGE META BOX FIELDS---//
function tasbb_menu_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'tasbb_beer_nonce' ); ?>

  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Menu Subheading</label><br>
    <?php _e( "Enter the subheading of the menu here"); ?>
    </p>
		<input class="widefat" type="text" id="tasbb_export_menu_subheading" name="tasbb_export_menu_subheading" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_export_menu_subheading', true)); ?>" />
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Before Menu Text</label><br>
    <?php _e( "Enter the Text to enter right before the menu begins here"); ?>
    </p>
		<textarea class="widefat" id="tasbb_export_menu_before_menu" name="tasbb_export_menu_before_menu"><?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_export_menu_before_menu', true)); ?></textarea>
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">After Menu Text</label><br>
    <?php _e( "Enter the Text to enter right after the menu ends here"); ?>
    </p>
		<textarea class="widefat" id="tasbb_export_menu_after_menu" name="tasbb_export_menu_after_menu"><?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_export_menu_after_menu', true)); ?></textarea>
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Override Beers Per Column</label><br>
    <?php _e( "Specify the number of beers per column (Uses template default if blank)"); ?>
    </p>
		<input type="number" id="tasbb_beers_per_column" name="tasbb_beers_per_column" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_beers_per_column', true)); ?>" />
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">CSS Overrides</label><br>
    <?php _e( "Add any custom CSS for this menu here."); ?>
    </p>
		<textarea class="widefat" id="tasbb_export_menu_css" name="tasbb_export_menu_css"><?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_export_menu_css', true)); ?></textarea>
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Sort By</label><br>
    <?php _e( "Specify the Beer Value you want to sort by."); ?>
    </p>
        <select id="tasbb_export_sortby" name="tasbb_export_sortby">
					<option value="tasbb_abv" <?php selected( get_post_meta( $object->ID,'tasbb_export_sortby',true), 'tasbb_abv');?>>ABV</option>
					<option value="tasbb_ibu" <?php selected( get_post_meta( $object->ID,'tasbb_export_sortby',true), 'tasbb_ibu');?>>IBU</option>
					<option value="tasbb_og" <?php selected( get_post_meta( $object->ID,'tasbb_export_sortby',true), 'tasbb_og');?>>OG</option>
					<option value="tasbb_price" <?php selected( get_post_meta( $object->ID,'tasbb_export_sortby',true), 'tasbb_price');?>>Price</option>
					<option value="name" <?php selected( get_post_meta( $object->ID,'tasbb_export_sortby',true), 'name');?>>Name</option>
				</select>
  </div>
  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Sort Order</label><br>
    <?php _e( "Specify the order of the menu.  Ascending (A-Z), or Descending (Z-A)"); ?>
    </p>
        <select id="tasbb_export_sort_order" name="tasbb_export_sort_order">
					<option value="asc" <?php selected( get_post_meta( $object->ID, 'tasbb_export_sort_order', true), 'asc');?>>Ascending</option>
					<option value="desc" <?php selected( get_post_meta( $object->ID, 'tasbb_export_sort_order', true), 'desc');?>>Descending</option>
				</select>
  </div>

  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Only Show On-Tap Beers</label><br>
    <?php _e( "Only show beers that are marked as on-tap in the beers menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_ontap" name="tasbb_export_ontap" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_ontap', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Image</label><br>
    <?php _e( "Show image of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_img" name="tasbb_export_show_img" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_img', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Description</label><br>
    <?php _e( "Show the description (also known as the excerpt) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_description" name="tasbb_export_show_description" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_description', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Style</label><br>
    <?php _e( "Show the style of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_style" name="tasbb_export_show_style" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_style', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer OG</label><br>
    <?php _e( "Show the Original Gravity (OG) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_og" name="tasbb_export_show_og" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_og', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer IBU</label><br>
    <?php _e( "Show the International Bittering Units (IBU) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_ibu" name="tasbb_export_show_ibu" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_ibu', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer ABV</label><br>
    <?php _e( "Show the Alcohol by Volume (ABV) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_abv" name="tasbb_export_show_abv" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_abv', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Price</label><br>
    <?php _e( "Show the price of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_price" name="tasbb_export_show_price" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_price', true));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-style">Filter by Style</label><br>
    <?php _e( "Select the Beer Style you want to filter.  If all are unchecked, all beer styles will be used."); ?>
    </p>
<?php
	$tasbb_terms = get_terms('style',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($tasbb_terms as $tasbb_term){?>
	<input type="checkbox" id="<?php echo $tasbb_term->slug; ?>" name="<?php echo $tasbb_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $tasbb_term->slug, true));  ?>/>
	<label for="<?php echo $tasbb_term->slug;?>"><?php echo $tasbb_term->name; ?></label><br/>
<?php }; ?>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-availability">Filter by Availability</label><br>
    <?php _e( "Select the Beer Availability you want to filter.  If all are unchecked, all beer availabilities will be used."); ?>
    </p>
<?php
	$tasbb_terms = get_terms('availability',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($tasbb_terms as $tasbb_term){
	if($tasbb_term->slug != 'on-tap'){?>
	<input type="checkbox" id="<?php echo $tasbb_term->slug; ?>" name="<?php echo $tasbb_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $tasbb_term->slug, true));  ?>/>
	<label for="<?php echo $tasbb_term->slug;?>"><?php echo $tasbb_term->name; ?></label><br/>
<?php };}; ?>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-tag">Filter by Beer Tag</label><br>
    <?php _e( "Select the Beer Tag you want to filter.  If all are unchecked, all beer tags will be used."); ?>
    </p>
<?php
	$tasbb_terms = get_terms('tags',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);
	
	foreach($tasbb_terms as $tasbb_term){?>
	<input type="checkbox" id="<?php echo $tasbb_term->slug; ?>" name="<?php echo $tasbb_term->slug; ?>" value="1" <?php echo checked(1, get_post_meta( $object->ID, $tasbb_term->slug, true));  ?>/>
	<label for="<?php echo $tasbb_term->slug;?>"><?php echo $tasbb_term->name; ?></label><br/>
<?php }; ?>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Beers to Exclude</label><br>
    <?php _e( "List the name, or ID of the beers that you want to remove from your menu. This is useful when you have no other way to filter the beer out. One beer per line."); ?>
    </p>
		<textarea class="widefat" id="tasbb_beers_to_filter" name="tasbb_beers_to_filter"><?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_beers_to_filter', true)); ?></textarea>
  </div>
<?php  }

//---THE MENU TEMPLATE META BOX FIELDS---//
function tasbb_menu_template_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'tasbb_beer_nonce' ); ?>
  <div class="tasbb-field tasbb-full-width">
        <select id="tasbb_menu_template" name="tasbb_menu_template">
					<?php global $tasbb_menu_templates;
					foreach($tasbb_menu_templates as $template){ ?>
					<option value="<?php echo $template->slug; ?>" <?php selected( get_post_meta( $object->ID, 'tasbb_menu_template', true), $template->slug);?>><?php echo $template->name; ?></option>
					<?php }; ?>
				</select>
  </div>
<?php  }

//---THE BEER META BOX SUBMISSION AND VALIDATION---//
function tasbb_save_beer_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['tasbb_beer_nonce']) || !wp_verify_nonce($_POST['tasbb_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('tasbb_meta_item')){
  class tasbb_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new tasbb_meta_item('tasbb_abv','tasbb-abv'),
	new tasbb_meta_item('tasbb_ibu','tasbb-ibu'),
	new tasbb_meta_item('tasbb_og','tasbb-og'),
	new tasbb_meta_item('tasbb_price','tasbb-price'),
	new tasbb_meta_item('tasbb_untappd-url','tasbb-untappd-url'),
	new tasbb_meta_item('tasbb_video','tasbb-video'),
	new tasbb_meta_item('tasbb_gallery','tasbb-gallery'),
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
function tasbb_save_menu_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['tasbb_beer_nonce']) || !wp_verify_nonce($_POST['tasbb_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('tasbb_menu_meta_item')){
  class tasbb_menu_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new tasbb_menu_meta_item('tasbb_export_menu_subheading','tasbb_export_menu_subheading'),
	new tasbb_menu_meta_item('tasbb_export_menu_before_menu','tasbb_export_menu_before_menu'),
	new tasbb_menu_meta_item('tasbb_export_menu_after_menu','tasbb_export_menu_after_menu'),
	new tasbb_menu_meta_item('tasbb_export_menu_css','tasbb_export_menu_css'),
	new tasbb_menu_meta_item('tasbb_export_ontap','tasbb_export_ontap'),
	new tasbb_menu_meta_item('tasbb_export_show_style','tasbb_export_show_style'),
	new tasbb_menu_meta_item('tasbb_export_show_og','tasbb_export_show_og'),
	new tasbb_menu_meta_item('tasbb_export_show_ibu','tasbb_export_show_ibu'),
	new tasbb_menu_meta_item('tasbb_export_show_abv','tasbb_export_show_abv'),
	new tasbb_menu_meta_item('tasbb_export_show_price','tasbb_export_show_price'),
	new tasbb_menu_meta_item('tasbb_export_show_img','tasbb_export_show_img'),
	new tasbb_menu_meta_item('tasbb_export_show_description','tasbb_export_show_description'),
	new tasbb_menu_meta_item('tasbb_export_sort_order','tasbb_export_sort_order'),
	new tasbb_menu_meta_item('tasbb_export_sortby','tasbb_export_sortby'),
	new tasbb_menu_meta_item('tasbb_beers_per_column','tasbb_beers_per_column'),
	new tasbb_menu_meta_item('tasbb_beers_to_filter','tasbb_beers_to_filter'),
	];

	//--- PUSHES STYLE TAXONOMY TO ARRAY ---//
	$tasbb_terms = get_terms('style',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('tasbb_menu_meta_item')){
		foreach($tasbb_terms as $tasbb_term){
			array_push($metas, new tasbb_menu_meta_item($tasbb_term->slug,$tasbb_term->slug));
		};
	};
	//--- PUSHES AVAILABILITY TAXONOMY TO ARRAY ---//
	$tasbb_terms = get_terms('availability',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('tasbb_menu_meta_item')){
		foreach($tasbb_terms as $tasbb_term){
			array_push($metas, new tasbb_menu_meta_item($tasbb_term->slug,$tasbb_term->slug));
		};
	};
	//--- PUSHES TAG TAXONOMY TO ARRAY ---//
	$tasbb_terms = get_terms('tags',['orderby' => 'name', 'order' => 'asc', 'hide_empty' => true]);	
	if(class_exists('tasbb_menu_meta_item')){
		foreach($tasbb_terms as $tasbb_term){
			array_push($metas, new tasbb_menu_meta_item($tasbb_term->slug,$tasbb_term->slug));
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
function tasbb_save_menu_template_meta($post_id, $post) {
	
  /* Verify the nonce before proceeding. */
  if (!isset($_POST['tasbb_beer_nonce']) || !wp_verify_nonce($_POST['tasbb_beer_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;
	if(!class_exists('tasbb_menu_template_meta_item')){
  class tasbb_menu_template_meta_item{
    function __construct($key, $value){
      $this->theKey = $key;
      $this->newValue = ( isset( $_POST[$value] ) ? $_POST[$value] : '' );
    }
  }
	};
	$metas = [
	new tasbb_menu_template_meta_item('tasbb_menu_template','tasbb_menu_template'),
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

function tasbb_add_post_meta_boxes() {
  add_meta_box(
    'tasbb-beer-info',                        // Unique ID
    esc_html__( 'Beer Info' ),                // Title
    'tasbb_beer_meta_box',                    // Callback function
    'beers',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
  
  add_meta_box(
    'tasbb-menu-info',                        // Unique ID
    esc_html__( 'Menu Info' ),                // Title
    'tasbb_menu_meta_box',                    // Callback function
    'menus',                                  // Admin page (or post type)
    'normal',                                 // Context
    'default'                                 // Priority
  );
  
  add_meta_box(
    'tasbb-menu-template',                    // Unique ID
    esc_html__( 'Menu Template' ),            // Title
    'tasbb_menu_template_meta_box',           // Callback function
    'menus',                                  // Admin page (or post type)
    'side',                                   // Context
    'default'                                 // Priority
  );

}

function tasbb_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'tasbb_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'tasbb_save_beer_meta', 10, 2);
	add_action( 'save_post', 'tasbb_save_menu_meta', 10, 2);
	add_action( 'save_post', 'tasbb_save_menu_template_meta', 10, 2);
}

add_action( 'load-post.php', 'tasbb_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'tasbb_post_meta_boxes_setup' );
?>