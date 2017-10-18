<?php
/**
 * Meta Box Handler for Easy Beer Lister
 * @author: Alex Standiford
 * @date  : 10/16/17
 */

namespace ebl\admin;

use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class metaBox extends ebl{

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
         'step' => 0.01,
       ],
      ],
      ['name'           => 'On Tap',
       'description'    => 'Is this beer currently on-tap?',
       'type'           => 'select',
       'select_options' => ['No', 'Yes']],
      ['name'           => 'Availability Start Date',
       'description'    => 'Enter the first month this delicious brew is available. This is reflected on the availability calendar.',
       'type'           => 'select',
       'select_options' => [
         'Year-Round', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
       ],
      ],
      ['name'           => 'Availability End Date',
       'description'    => 'Enter the last month this delicious brew is available. This is reflected on the availability calendar.',
       'type'           => 'select',
       'class'          => 'ebl-field mod--hidden',
       'select_options' => [
         'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
       ],
      ],
      ['name'        => 'SRM Value',
       'description' => 'Enter the SRM Value (color value) of the beer here.',
       'type'        => 'srmpicker',
       'input_args'  => [
         'type' => 'text',
       ],
      ],
      ['name'        => 'Glass Shape',
       'description' => 'select which glass shape you prefer this beer to be displayed with',
       'type'        => 'glassshape',
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
      ['name'        => 'Image Gallery',
       'description' => 'Select Images of this beer',
       'type'        => 'gallery',
      ],
    ],
  ];
  public $field;
  public $id;
  public $title;
  public $postType;
  public $priority;
  public $context;
  public $metaKey;
  public $metaValue;
  public $meta;
  public $inLoop = false;
  public $jsArgs = [];
  public $postID;
  const EBL_METABOX_TEMPLATE_DIR = 'admin/metabox/';

  public function __construct($meta_box_name, $post_type, $context = 'normal', $priority = 'default'){
    $this->fields = $this->fields[$post_type];
    $this->id = sanitize_title(EBL_PREFIX.'-'.$meta_box_name);
    $this->metaKey = str_replace('-', '_', $this->id);
    $this->title = esc_html__($meta_box_name);
    $this->postType = $post_type;
    $this->context = $context;
    $this->priority = $priority;
    $this->field = new metaBoxField($this->fields[0]);
    parent::__construct();
  }

  /**
   * Check for errors before moving forward
   * @return null|\WP_Error
   */
  function checkForErrors(){
    if(!is_array($this->fields)){
      return $this->throwError('eblmeta01', 'An invalid Post Type was specified in the post meta object.');
    }

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
   * Loads the metabox fields template
   * @return null|\WP_Error
   */
  public function loadTemplate($object){
    $this->postID = $object->ID;
    if(!$this->meta) $this->meta = get_post_meta($this->postID, $this->metaKey, true);
    $template_name = apply_filters('ebl_metabox_'.$this->id.'_template_location', EBL_TEMPLATE_DIRECTORY.self::EBL_METABOX_TEMPLATE_DIR.$this->id.'.php');
    if(!file_exists($template_name)) return $this->throwError('eblmeta03', 'The template located at '.$template_name.' Could not be found.');
    wp_nonce_field(basename(__FILE__), 'ebl_beer_nonce');
    if($this->fileExists($template_name)) include($template_name);

    return null;
  }

  /**
   * Check to see if there are any meta fields to display
   * @return bool
   */
  public function haveFields(){

    //If we haven't done the loop yet, set it up and return true.
    if($this->inLoop == false){

      return true;
    }

    //If we have fields left, return true
    if(count($this->fields) > 1){
      return true;
    };

    //Otherwise, enqueue scripts and return false
    $this->enqueue();

    return false;
  }

  /**
   * Creates the next meta box field object, and shifts the array
   */
  public function theField(){
    if($this->inLoop == false){
      wp_nonce_field(EBL_PATH, EBL_PREFIX.'_beer_nonce');
      $this->inLoop = true;
    }
    else{
      $this->field = new metaBoxField($this->fields[1]);
      if(!empty($this->field->jsArgs)) $this->jsArgs[$this->field->fieldID()] = $this->field->jsArgs; //Capture the js args to pass to the script afterward
      array_shift($this->fields);
    }
  }

  /**
   * Get a single field input, based on the type
   * Template files are located in plugin_dir/templates/admin/metabox
   * @return string|\WP_Error
   */
  public function input(){
    if($this->inLoop == false) return $this->throwError('eblmeta04', 'metaBox->input() cannot be used outside of the loop');
    $input_template_dir = EBL_TEMPLATE_DIRECTORY.self::EBL_METABOX_TEMPLATE_DIR;
    $this->metaValue = $this->getMetaValue() ? esc_attr($this->getMetaValue()) : '';
    $file = apply_filters('ebl_field_'.$this->field->type.'_template_location', $input_template_dir.'ebl-field-'.$this->field->type.'.php');
    if($this->fileExists($file)) include($file);

    return $this->field->type;
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
    $meta_values = [];
    if($this->haveFields()){
      while($this->haveFields()){
        $this->theField();
        $meta_values[str_replace('-', '_', $this->field->metaValue)] = $_POST[$this->field->metaValue];
      }
    }
    update_post_meta($post_id, $this->metaKey, $meta_values);

    return $post_id;
  }

  private function enqueue(){
    if(!$this->inLoop) return $this->throwError('meta05', 'The private method enqueue() can only run inside the loop.');
    wp_enqueue_media();
    wp_dequeue_script('admin-media-beer');
    wp_register_script('admin-media-beer', EBL_ASSETS_URL.'js/admin-media-beer.js', ['jquery'], EBL_VERSION);
    wp_localize_script('admin-media-beer', 'eblAdmin', $this->jsArgs);
    wp_enqueue_script('admin-media-beer');

    return false;
  }

  /**
   * Gets the current meta value
   * @return mixed
   */
  public function getMetaValue(){
    if(!$this->inLoop) $this->throwError('meta06', 'The method getMetaValue only works inside the loop');

    return isset($this->meta[str_replace('-', '_', $this->field->metaValue)]) ? $this->meta[str_replace('-', '_', $this->field->metaValue)] : '';
  }

}