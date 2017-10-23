<?php
/**
 * Beer info component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-info-basic.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID);
?>
<div <?= $this->wrapperClasses(); ?> <?= $this->wrapperArgs(); ?>>
  <?php if($this->parent['location'] == 'archive'): ?>
  <a href="<?= get_post_permalink($beer->post->ID); ?>"> <?php endif; ?>
    <h2><?= get_the_title(); ?></h2>
    <?php if($this->parent['location'] == 'archive'): ?></a> <?php endif; ?>
  <?php if($this->parent['location'] == 'archive'): ?>
    <a href="<?= get_term_link($beer->getStyle('slug'), 'style'); ?>">
      <h3><?= $beer->getStyle(); ?></h3>
    </a>
  <?php else: ?>
    <h3><?= $beer->getStyle(); ?></h3>
  <?php endif; ?>
  <?php if(is_singular('beers')): ?>
    <?php the_content($beer->post->ID); ?>
  <?php else: ?>
    <p><?= get_the_excerpt($beer->post->ID); ?></p>
  <?php endif; ?>
</div>
