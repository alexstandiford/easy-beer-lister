<?php

//---THE META BOX FIELDS---//
function tasbb_post_class_meta_box($object, $box) { ?>
  <?php wp_nonce_field( basename( __FILE__ ), 'tasbb_post_class_nonce' ); ?>
  <div class="tasbb-field">
    <p class="label">
    <label for="tasbb-abv">ABV</label><br>
    <?php _e( "Enter the ABV of the beer here. (do not include the % sign)"); ?>
    </p>
    <input class="widefat" type="number" name="tasbb-abv" id="tasbb-abv" value="<?php echo esc_attr(get_post_meta( $object->ID, 'tasbb_abv', true)); ?>" size="30" />
  </div>
<?php }

//---THE META BOX SUBMISSION AND VALIDATION---//
function tasbb_save_post_class_meta($post_id, $post) {

  /* Verify the nonce before proceeding. */
  if (!isset($_POST['tasbb_post_class_nonce']) || !wp_verify_nonce($_POST['tasbb_post_class_nonce'], basename(__FILE__)))
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );
  
  /* Check if the current user has permission to edit the post. */
  if (!current_user_can($post_type->cap->edit_post, $post_id))
    return $post_id;

  class tasbb_meta{
    function __construct(){
      $this->meta_key = null;
      $this->meta_value = null;
    }
  }
  //------ START HERE ------//
  ( isset( $_POST['tasbb-abv'] ) ? $_POST['tasbb-abv'] : '' )
  $meta_key = 'tasbb_post_class';
  
  /* Get the posted data and sanitize it for use as an HTML class. */
  $tasbb_metas = [
    ];

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

function tasbb_add_post_meta_boxes() {
  add_meta_box(
    'tasbb-beer-info',                        // Unique ID
    esc_html__( 'Beer Info' ),                // Title
    'tasbb_post_class_meta_box',              // Callback function
    'beers',                                  // Admin page (or post type)
    'normal',                                   // Context
    'default'                                 // Priority
  );
}

function tasbb_post_meta_boxes_setup() {
  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'tasbb_save_post_class_meta', 10, 2 );
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'tasbb_add_post_meta_boxes' );
}

add_action( 'load-post.php', 'tasbb_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'tasbb_post_meta_boxes_setup' );



///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
//////////////                                /////////////////////
//////////////     THE FIELD OF BULLSHIT      /////////////////////
//////////////                                /////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////



// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');
 
function my_acf_settings_path($path) {
    $path = dirname(__FILE__ ).'/acf/';
    return $path;
}
// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
    $dir = dirname(__FILE__ ).'/acf/';
    return $dir;
}

// 3. Hide ACF field group menu item
function remove_acf_menu() {
		remove_menu_page('edit.php?post_type=acf');
	}
	add_action( 'admin_menu', 'remove_acf_menu', 999);

// 4. Include ACF
include_once( dirname(__FILE__ ).'/acf/acf.php' );
include_once( dirname(__FILE__ ).'/acf-gallery/acf-gallery.php' );

// 5. Register fields

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_beer',
		'title' => 'Other Beer Information',
		'fields' => array (
			array (
				'key' => 'field_56c86b3d1ec5c',
				'label' => 'ABV',
				'name' => 'abv',
				'type' => 'number',
				'instructions' => 'Enter the ABV of the beer here (do not include the % sign)',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 100,
				'step' => '',
			),
			array (
				'key' => 'field_56c86b3d1ec5c1231',
				'label' => 'Price',
				'name' => 'price',
				'type' => 'number',
				'instructions' => 'Enter the price of the beer (Does not show up on website, only used for menu export)',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 100,
				'step' => '',
			),
			array (
				'key' => 'field_56c86a359ad75',
				'label' => 'IBU',
				'name' => 'ibu',
				'type' => 'number',
				'instructions' => 'Enter the IBU value of the beer here',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_56c87f5d60a32',
				'label' => 'O.G.',
				'name' => 'og',
				'type' => 'number',
				'instructions' => 'Enter the original gravity of the beer',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_56c86b811ec5d',
				'label' => 'Beer Video',
				'name' => 'video',
				'type' => 'text',
				'instructions' => 'Enter the URL of the related beer video.',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_56c87cccd20e5',
				'label' => 'Additional Photos',
				'name' => 'additional_photos',
				'type' => 'gallery',
				'instructions' => 'Add extra photos of the beer in-addition to the featured image',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_56c87f9360a33',
				'label' => 'Untappd URL',
				'name' => 'untappd_url',
				'type' => 'text',
				'instructions' => 'Enter the Untappd URL for this beer',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'beers',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

?>