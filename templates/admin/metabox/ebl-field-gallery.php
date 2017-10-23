<?php
/**
 * Gallery Input template for metaboxes
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object
$images = explode(',', $this->field->metaValue);

?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value="<?= $this->field->metaValue; ?>"/>

<div id="<?= $this->field->previewTarget; ?>">
  <?php foreach($images as $image): ?>
    <img height="100" src="<?= wp_get_attachment_image_url($image, 'medium'); ?>">
  <?php endforeach; ?>
</div>

<input type="button" class="button" name="ebl_gallery_button" id="upload_image_button" value="<?= __('Upload/Select images'); ?>"/>