<?php
// On-Tap Widget
class ebl_on_tap extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'ebl_on_tap', 

// Widget name will appear in UI
__('On Tap', 'ebl_on_tap_domain'), 

// Widget description
array( 'description' => __( 'Display a list of what beers are on-tap', 'ebl_on_tap_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output

$ebl_on_tap = new WP_Query(["post_type" => "beers","tax_query" => [["taxonomy"  => "availability","field" => "slug","terms" => "on-tap",],],]);
do_action('ebl_before_on_tap_widget');?>
<ul class="ebl-on-tap-widget">
<?php if($ebl_on_tap->have_posts()) : while($ebl_on_tap->have_posts()) : $ebl_on_tap->the_post();?>
  <li><a href="<?php echo get_post_permalink();?>"><?php the_title(); ?></a></li>
<?php endwhile; endif;?>
</ul>
<?php do_action('ebl_after_on_tap_widget');
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( "On Tap", 'ebl_on_tap_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
function ebl_on_tap_function() {
	register_widget( 'ebl_on_tap' );
}

// Random Beer
class ebl_random_beer extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'ebl_random_beer', 

// Widget name will appear in UI
__('Random Beer', 'ebl_random_beer_domain'), 

// Widget description
array( 'description' => __( 'Features a random beer on page load', 'ebl_random_beer_domain' ), ) 
);
}
// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output

$ebl_random_beer = new WP_Query(["posts_per_page" => 1, "post_type" => "beers", 'orderby' => 'rand']);
if($ebl_random_beer->have_posts()) : while($ebl_random_beer->have_posts()) : $ebl_random_beer->the_post();?>
<div class="ebl-random-beer">
	<?php do_action('ebl_before_random_beer_shortcode'); ?>
  <h3><a href="<?php echo get_post_permalink();?>"><?php the_title(); ?></a></h3>
  <p><?php the_excerpt();?></p>
  <?php echo wp_get_attachment_image( get_post_thumbnail_id(),'small' );?>
  <?php if(ebl_beer_info_exists('ebl_untappd_url')){?>
  <?php };
	do_action('ebl_after_random_beer_shortcode'); ?>
</div>
<?php
	endwhile; endif;
echo $args['after_widget'];
}	
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( "Featured Beer", 'ebl_random_beer_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
function ebl_random_beer_function() {
	register_widget( 'ebl_random_beer' );
}


add_action( 'widgets_init', 'ebl_random_beer_function' );
add_action( 'widgets_init', 'ebl_on_tap_function' );

?>