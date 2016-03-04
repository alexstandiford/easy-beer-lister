<?php

//---THE BEER PAGE META BOX FIELDS---//
function tasbb_beer_meta_box($object, $box) { ?>
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
<?php  }

//---THE MENU PAGE META BOX FIELDS---//
function tasbb_menu_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'tasbb_beer_nonce' ); ?>

  <div class="tasbb-field tasbb-full-width">
    <p class="label">
    <label for="tasbb-abv">Menu Subheading</label><br>
    <?php _e( "Enter the Video URL of the beer here"); ?>
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
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Only Show On-Tap Beers</label><br>
    <?php _e( "Only show beers that are marked as on-tap in the beers menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_ontap" name="tasbb_export_ontap" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_ontap', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Image</label><br>
    <?php _e( "Show image of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_img" name="tasbb_export_show_img" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_img', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Description</label><br>
    <?php _e( "Show the description (also known as the excerpt) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_description" name="tasbb_export_show_description" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_description', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Style</label><br>
    <?php _e( "Show the style of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_style" name="tasbb_export_show_style" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_style', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer OG</label><br>
    <?php _e( "Show the Original Gravity (OG) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_og" name="tasbb_export_show_og" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_og', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer IBU</label><br>
    <?php _e( "Show the International Bittering Units (IBU) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_ibu" name="tasbb_export_show_ibu" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_ibu', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer ABV</label><br>
    <?php _e( "Show the Alcohol by Volume (ABV) of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_abv" name="tasbb_export_show_abv" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_abv', false));  ?>/>
  </div>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">Show Beer Price</label><br>
    <?php _e( "Show the price of beer in beer menu"); ?>
    </p>
    <input type="checkbox" id="tasbb_export_show_price" name="tasbb_export_show_price" value="1" <?php echo checked(1, get_post_meta( $object->ID, 'tasbb_export_show_price', false));  ?>/>
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
	new tasbb_menu_meta_item('tasbb_export_ontap','tasbb_export_ontap'),
	new tasbb_menu_meta_item('tasbb_export_show_style','tasbb_export_show_style'),
	new tasbb_menu_meta_item('tasbb_export_show_og','tasbb_export_show_og'),
	new tasbb_menu_meta_item('tasbb_export_show_ibu','tasbb_export_show_ibu'),
	new tasbb_menu_meta_item('tasbb_export_show_abv','tasbb_export_show_abv'),
	new tasbb_menu_meta_item('tasbb_export_show_price','tasbb_export_show_price'),
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
}

function tasbb_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'tasbb_add_post_meta_boxes' );
	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'tasbb_save_beer_meta', 10, 2);
	add_action( 'save_post', 'tasbb_save_menu_meta', 10, 2);
}

add_action( 'load-post.php', 'tasbb_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'tasbb_post_meta_boxes_setup' );
?>