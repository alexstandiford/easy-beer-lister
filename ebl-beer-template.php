<?php get_header();?>
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
<?php
$page_wrapper = get_option('ebl_beer_page_wrapper_class') == null ? 'ebl-page-wrapper' : get_option('ebl_beer_page_wrapper_class');
$content_heading_wrapper = get_option('ebl_beer_page_heading_wrapper_class') == null ? 'content-heading-wrapper' : get_option('ebl_beer_page_heading_wrapper_class');
$content_wrapper = get_option('ebl_beer_page_content_wrapper') == null ? 'ebl-primary-content-wrapper' : get_option('ebl_beer_page_content_wrapper');
$sidebar_wrapper = get_option('ebl_beer_page_sidebar_wrapper') == null ? 'ebl-primary-sidebar-wrapper' : get_option('ebl_beer_page_sidebar_wrapper');
do_action('ebl_before_beer_wrapper');
?>
<div class="<?php echo apply_filters('ebl_beer_page_wrapper',$page_wrapper) ?>">
	<div id="primary" class="<?php echo apply_filters('ebl_beer_heading_wrapper_class', $content_heading_wrapper); ?>">
     <?php do_action('ebl_before_beer_overlay'); ?>
        <div class="<?php echo apply_filters('ebl_beer_heading_overlay_class', 'content-heading-overlay'); ?>">
          <?php do_action('ebl_before_beer_heading'); ?>
            <div class="<?php echo apply_filters('ebl_beer_heading_class', 'content-heading'); ?>">
              <?php do_action('ebl_before_beer_title'); ?>
                <h2><?php the_title();?></h2>
                <?php if(function_exists('ebl_beer_info')){ebl_beer_info('style','h2',null,true);};?>
                <?php if(function_exists('ebl_beer_is_on_tap')){
                if(ebl_beer_is_on_tap()){ ?>
                <h4 class="on-tap"><a href="<?php ebl_beer_info_url('availability',null,true) ?>"><?php echo apply_filters('ebl_beer_ontap_msg','On Tap Now!'); ?></a></h4>
                <?php };};?>
                <hr>
                <p class="<?php echo apply_filters('ebl_beer_availability_class', 'availability'); ?>"><?php echo apply_filters('ebl_beer_availability_title','Availability:'); ?><?php if(function_exists('ebl_beer_info')){ebl_beer_info('availability','span');};?></p>
            </div>
        </div>
    </div>
    <div id="primary" class="<?php echo apply_filters('ebl_beer_content_wrapper', $content_wrapper); ?>">
      <?php do_action('ebl_before_beer_content'); ?>
        <div class="<?php echo apply_filters('ebl_beer_content', 'ebl-primary-content'); ?>">
          <?php do_action('ebl_before_beer_excerpt'); ?>
        <blockquote>
        <?php the_excerpt();?>
        </blockquote>
          <?php do_action('ebl_before_beer_info'); ?>
        <?php if(function_exists('ebl_beer_info')){?>
          <?php if(ebl_beer_info_exists('ebl_untappd_url')){?>
          <a class="untappd-url btn" href="<?php ebl_beer_info('ebl_untappd_url');?>" target="blank">View on Untappd</a>
          <?php }; ?>
          <?php if(ebl_beer_info_exists('ebl_abv') || ebl_beer_info_exists('ebl_ibu') || ebl_beer_info_exists('ebl_og')){?>
					<h3><?php echo apply_filters('ebl_beer_info_title','Beer Info'); ?></h3>
				<div class="ebl-beer-info-wrapper">
				 <?php the_post_thumbnail();?>
         <dl class="ebl-beer-info">
           <?php if(ebl_beer_info_exists('ebl_abv')){?>
           <div>
           <dt><?php echo apply_filters('ebl_beer_abv_title','ABV:'); ?></dt>
             <dd><?php ebl_beer_info('ebl_abv'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_abv');
            if(ebl_beer_info_exists('ebl_ibu')){?>
           <div>
           <dt><?php echo apply_filters('ebl_beer_ibu_title','IBU:'); ?></dt>
             <dd><?php ebl_beer_info('ebl_ibu'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_ibu');
            if(ebl_beer_info_exists('ebl_og')){?>
           <div>
           <dt><?php echo apply_filters('ebl_beer_og_title','Original Gravity:'); ?></dt>
             <dd><?php ebl_beer_info('ebl_og'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_og');
            if(ebl_beer_info_exists('pairing')){?>
           <div>
             
           <dt><?php echo apply_filters('ebl_beer_pairing_title','Pairs With:'); ?></dt>
         <?php ebl_beer_info('pairing','dd'); ?>
           </div>
           <?php  }; do_action('ebl_after_beer_pairing'); ?>
         </dl>
					</div>
					<span class="ebl-row"></span>
          <?php };
         };
		   the_content();
         if(function_exists('ebl_beer_info')){
          ebl_beer_video();
					ebl_beer_gallery();
        }
        ?>
        </div>
      <?php do_action('ebl_after_beer_info'); ?>
    </div>
</div>
 <?php
endwhile;
?>
<?php endif; 
do_action('ebl_before_beer_sidebar');
if(get_option('ebl_beer_page_sidebar') == 'default'){?>
  <div class="<?php echo apply_filters('ebl_beer_sidebar_wrapper',$sidebar_wrapper); ?>"><?php
    get_sidebar(); ?>
  </div> <?php
}
elseif(get_option('ebl_beer_page_sidebar') != 'ebl-no-widget' && get_option('ebl_beer_page_sidebar') != null){?>
  <div class="<?php echo apply_filters('ebl_beer_sidebar_wrapper',$sidebar_wrapper); ?>"><?php
    dynamic_sidebar(get_option('ebl_beer_page_sidebar')); ?>
  </div> <?php
}
?>
<?php do_action('ebl_before_beer_footer'); ?>
<?php get_footer();?>