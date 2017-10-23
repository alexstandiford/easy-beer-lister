<?php
/**
 * Image Upload template for metaboxes
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object

?>
<input class="hidden <?= $this->field->inputTarget ?>" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value="<?= $this->field->metaValue; ?>"/>

<div class="<?= $this->field->previewTarget; ?>">
    <img height="100" src="<?= wp_get_attachment_image_url($this->field->metaValue, 'medium'); ?>">
</div>

<input type="button" class="button upload_single_image_button" name="ebl_image_button" value="<?= __('Upload/Select image'); ?>"/>