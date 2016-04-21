<?php get_header();?>
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
<?php
do_action('ebl_before_beer_wrapper');
?>
	<div id="primary" class="<?php echo apply_filters('ebl_beer_body_class', 'content-heading-wrapper'); ?>">
     <?php do_action('ebl_before_beer_overlay'); ?>
        <div class="<?php echo apply_filters('ebl_beer_heading_overlay_class', 'content-heading-overlay'); ?>">
          <?php do_action('ebl_before_beer_heading'); ?>
            <div class="<?php echo apply_filters('ebl_beer_heading_class', 'content-heading'); ?>">
              <?php do_action('ebl_before_beer_title'); ?>
                <h2><?php the_title();?></h2>
                <?php if(function_exists('ebl_beer_info')){ebl_beer_info('style','h2',null,true);};?>
                <?php if(function_exists('ebl_beer_is_on_tap')){
                if(ebl_beer_is_on_tap()){ ?>
                <h4 class="on-tap"><a href="<?php ebl_beer_info_url('availability',null,true) ?>">On Tap Now!</a></h4>
                <?php };};?>
                <hr>
                <p class="<?php echo apply_filters('ebl_beer_availability_class', 'availability'); ?>">Availability:<?php if(function_exists('ebl_beer_info')){ebl_beer_info('availability','span');};?></p>
            </div>
        </div>
    </div>
    <div id="primary" class="<?php echo apply_filters('ebl_beer_content_wrapper', 'ebl-primary-content-wrapper'); ?>">
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
					<h3>Beer Info</h3>
				<div class="ebl-beer-info-wrapper">
				 <?php the_post_thumbnail();?>
         <dl class="ebl-beer-info">
           <?php if(ebl_beer_info_exists('ebl_abv')){?>
           <div>
           <dt>ABV:</dt>
             <dd><?php ebl_beer_info('ebl_abv'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_abv');
            if(ebl_beer_info_exists('ebl_ibu')){?>
           <div>
           <dt>IBU:</dt>
             <dd><?php ebl_beer_info('ebl_ibu'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_ibu');
            if(ebl_beer_info_exists('ebl_og')){?>
           <div>
           <dt>Original Gravity:</dt>
             <dd><?php ebl_beer_info('ebl_og'); ?></dd>
           </div>
           <?php };
            do_action('ebl_after_beer_og');
            if(ebl_beer_info_exists('pairing')){?>
           <div>
             
           <dt>Pairs With:</dt>
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
 <?php
endwhile;
?>
<?php endif; ?>
<?php do_action('ebl_before_beer_footer'); ?>
<?php get_footer();?>