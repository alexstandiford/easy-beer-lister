<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 10/23/17
 */

namespace ebl\app\widget;

use ebl\app\beerList\tapList;
use ebl\app\templateLoader;

if(!defined('ABSPATH')) exit;

class onTapWidget extends \WP_Widget{
  public function __construct(){
    parent::__construct(
      'ebl_on_tap', // Base ID
      __('On Tap', 'ebl_on_tap_domain'), // Widget name will appear in UI
      ['description' => __('Display a list of what beers are on-tap', 'ebl_on_tap_domain')] // Widget description
    );
  }

  /*--- WIDGET FRONT END ---*/
  public function widget($args, $instance){
    $title = apply_filters('widget_title', $instance['title']);
    // before and after widget arguments are defined by themes
    echo $args['before_widget'];
    if(!empty($title)) echo $args['before_title'].$title.$args['after_title'];
    new tapList();
    $template = new templateLoader('widget','on-tap');
    $template->loadTemplate();
    echo $args['after_widget'];
    wp_reset_query();
  }

  // Widget Backend
  public function form($instance){
    if(isset($instance['title'])){
      $title = $instance['title'];
    }
    else{
      $title = __("On Tap", 'ebl_on_tap_domain');
    }
    // Widget admin form
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
    </p>
    <?php
  }

  // Updating widget replacing old instances with new
  public function update($new_instance, $old_instance){
    $instance = [];
    $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

    return $instance;
  }
}