<?php
/**
 * Beer video component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-video.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID);
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <?php foreach($beer->getGalleryItems() as $gallery_item): ?>
  <?= wp_get_attachment_image($gallery_item,'medium'); ?>
  <?php endforeach; ?>
</div>