<?php
/**
 * Glass Shape Field Picker
 * @author: Alex Standiford
 * @date  : 10/17/17
 */

use \ebl\app\glass;

if(!defined('ABSPATH')) exit;
if(!$this->field instanceof \ebl\admin\field) return; //Bail early if we aren't in a meta box object
$field_id = $this->field;
$field_id = $field_id::$fieldID;
$post_id = isset($this::$postID) ? (int)$this::$postID : null;
$meta_value = explode(',',$this->field->metaValue);
$maybe_default_glass_shape = $this::$postID == null ? explode(',',$this->getDefaultGlassData())[0] : null;
?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value="<?= $this->field->metaValue; ?>"/>
<div class="ebl-glass-options-wrapper" data-wrapper-id="<?= $field_id; ?>">
  <div class="ebl-glass-shape-wrapper ebl-glass-item-wrapper">
    <strong>Glass Shape</strong>
    <?php
    foreach($this->getGlassShapes() as $glass_shape):
      if($glass_shape === 'bottle') continue;
      $glass = new glass($post_id, $glass_shape);
      $class = $meta_value[0] == $glass_shape ? 'glass-shape mod--selected' : 'glass-shape'; ?>

      <div class="<?= $class; ?>" data-glass-shape="<?= $glass_shape ?>">
        <?= $glass->glass(); ?>
        <span><?= ucfirst($glass_shape); ?></span>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="ebl-glass-layout-wrapper ebl-glass-item-wrapper">
    <strong>Glass Layout</strong>
    <?php

    foreach($this->getGlassLayouts() as $glass_layout):
      $glass = new glass($post_id, $maybe_default_glass_shape);
      $bottle = new glass($post_id, 'bottle');
      $class = 'glass-layout';
      if($glass_layout == $this->getSelectedLayout()) $class .= ' mod--selected';
      ?>

      <div class="<?= 'glass-shape glass-shape-'.$glass_layout.' '.$class; ?>" data-glass-layout="<?= $glass_layout ?>">
        <?php if($glass_layout !== 'bottle') echo $glass->glass(); ?>
        <?php if($glass_layout !== 'glass') echo $bottle->glass(); ?>
        <span><?= ucfirst($glass_layout); ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</div>
