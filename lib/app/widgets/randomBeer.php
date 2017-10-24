<?php
/**
 * Random Beer Widget
 * @author: Alex Standiford
 * @date  : 10/23/17
 */

namespace ebl\app\widget;

use ebl\app\beerList;
use ebl\app\templateLoader;

if(!defined('ABSPATH')) exit;

class randomBeer extends \WP_Widget{
  function __construct(){
    $args = ['description' => __('Features a random beer on page load', EBL_PREFIX.'_random_beer_domain')];
    parent::__construct(EBL_PREFIX.'_random_beer', __('Random Beer', EBL_PREFIX.'_random_beer_domain'), $args);
  }

  // Widget front-end
  public function widget($args, $instance){
    $title = apply_filters('widget_title', $instance['title']);
    echo $args['before_widget'];
    new beerList(["posts_per_page" => 1, 'orderby' => 'rand']);
    if(!empty($title)) echo $args['before_title'].$title.$args['after_title'];
    $template = new templateLoader('widget', 'random-beer');
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
      $title = __("Featured Beer", 'ebl_random_beer_domain');
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
    $instance = array();
    $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

    return $instance;
  }
}
