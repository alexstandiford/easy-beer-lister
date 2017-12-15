<?php
/**
 * Beer image component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-info-basic.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID);
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <div class="beer-glass-group">
    <?php if($this->parent['location'] == 'archive' && $this->getOption('disable_individual_beer_pages') != 'on'): ?><a href="<?= get_post_permalink($beer->post->ID); ?>"> <?php endif; ?>
      <?php $beer->getGlassLayout(); ?>
    <?php if($this->parent['location'] == 'archive' && $this->getOption('disable_individual_beer_pages') != 'on'): ?></a> <?php endif; ?>
  </div>
</div>