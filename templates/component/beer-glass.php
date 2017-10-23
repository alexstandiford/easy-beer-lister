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
    <?php if($this->parent['location'] == 'archive'): ?><a href="<?= get_post_permalink($beer->post->ID); ?>"> <?php endif; ?>
      <?= $beer->getGlass(); ?>
      <?= $beer->getBottle(); ?>
    <?php if($this->parent['location'] == 'archive'): ?></a> <?php endif; ?>
  </div>
</div>