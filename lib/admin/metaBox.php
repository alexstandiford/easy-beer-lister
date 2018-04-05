<?php
/**
 * Meta Box Handler for Easy Beer Lister
 * @author: Alex Standiford
 * @date  : 10/16/17
 */

namespace ebl\admin;

if(!defined('ABSPATH')) exit;

class metaBox extends fieldLoop{

  public $fields = [
    'beers' => [
      ['name'        => 'ABV',
       'description' => 'Enter the ABV of the beer here. (Do not include the % sign)',
       'input_args'  => [
         'type' => 'number',
         'step' => 0.01,
       ],
      ],
      ['name'        => 'IBU',
       'description' => 'Enter the IBU of the beer here.',
       'input_args'  => [
         'type' => 'number',
         'step' => 0.01,
       ],
      ],
      ['name'        => 'OG',
       'description' => 'Enter the gravity of the beer here.',
       'input_args'  => [
         'type' => 'number',
         'step' => 0.001,
       ],
      ],
      ['name'           => 'On Tap',
       'description'    => 'Is this beer currently on-tap?',
       'type'           => 'select',
       'select_options' => [0 => 'No', 1 => 'Yes']],
      ['name'           => 'Availability Start Date',
       'description'    => 'Enter the first month this delicious brew is available. This is reflected on the availability calendar.',
       'type'           => 'select',
       'select_options' => [
         0 => 'Year-Round', -1 => 'Unavailable', 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
       ],
      ],
      ['name'           => 'Availability End Date',
       'description'    => 'Enter the last month this delicious brew is available. This is reflected on the availability calendar.',
       'type'           => 'select',
       'class'          => 'ebl-field mod--hidden',
       'select_options' => [
         1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
       ],
      ],
      ['name'        => 'SRM Value',
       'description' => 'Enter the SRM Value (color value) of the beer here.',
       'type'        => 'srmpicker',
       'input_args'  => [
         'type' => 'text',
       ],
      ],
      ['name'              => 'Glass Layout',
       'description'       => 'select which glass layout you prefer this beer to be displayed with. Note that beer bottles will <strong>not</strong> display unless you add a top and bottom beer label.',
       'type'              => 'glasslayout',
       'fallback_value'    => ['shape' => 'shaker', 'layout' => 'bottle'],
       'sanitize_callback' => 'sanitizeGlassLayout',
       'input_args'        => [
         'type' => 'text',
       ],
      ],
      ['name'        => 'Price',
       'description' => 'Enter the price of the beer here. Do not include your currency symbol. This will only display if you have the price configured to display in the options.',
       'input_args'  => [
         'type' => 'number',
         'step' => 0.01,
       ],
      ],
      ['name'        => 'Untappd URL',
       'description' => 'Enter the Untappd URL here.',
      ],
      ['name'        => 'Video URL',
       'key'         => 'video',
       'description' => 'Enter the Video URL here.',
      ],
      ['name'           => 'Label',
       'description'    => 'Upload the label for this beer here',
       'type'           => 'imageupload',
       'preview_target' => 'bottom-label-target',
      ],
      ['name'           => 'Top Label',
       'description'    => 'Upload the top label for the beer bottle here',
       'type'           => 'imageupload',
       'preview_target' => 'top-label-target',
      ],
      ['name'        => 'Gallery',
       'description' => 'Select Images of this beer',
       'type'        => 'gallery',
      ],
    ],
  ];
  public $title;
  public $postType;
  public $priority;
  public $context;
  public $metaKey;
  public $metaValue;
  public $meta;

  public function __construct($meta_box_name, $post_type, $context = 'normal', $priority = 'default'){
    if(isset($_GET['post'])) self::$postID = $_GET['post'];
    $this->title = esc_html__($meta_box_name);
    $this->postType = $post_type;
    $this->context = $context;
    $this->priority = $priority;
    parent::__construct(apply_filters(EBL_PREFIX.'_beer_meta_fields', $this->fields[$post_type], $this->fields[$post_type]), $meta_box_name);
  }

  /**
   * Check for errors before moving forward
   * @return null|\WP_Error
   */
  function checkForErrors(){
    if(!is_array($this->fields)){
      return $this->throwError('eblmeta01', 'An invalid Post Type was specified in the post meta object.');
    }
    parent::checkForErrors();

    return null;
  }

  /**
   * Adds the meta box. This is the function that should be called back via the 'add_meta_boxes' action
   */
  public function addMetaBox(){
    add_meta_box(
      $this->id,
      $this->title,
      [$this, 'loadTemplate'],
      $this->postType,
      $this->context,
      $this->priority
    );
  }

  /**
   * Updates the Post Metadata
   *
   * @param $post_id
   *
   * @return mixed
   */
  public function saveMetaData($post_id){
    if(!isset($_POST['ebl_beer_nonce']) || !wp_verify_nonce($_POST['ebl_beer_nonce'], EBL_PATH)) return $post_id;
    if($this->haveFields()){
      while($this->haveFields()){
        $this->theField();
        if($_POST[$this->field->id] != get_post_meta($post_id, $this->field->metaKey, true)){
          if(sanitizeCheck::sanitize($this->field, $_POST[$this->field->id], $post_id)){
            update_post_meta($post_id, $this->field->metaKey, $_POST[$this->field->id]);
          }
        }
      }
    }

    return $post_id;
  }

}