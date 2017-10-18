<?php
/**
 * Glass Shape Field Picker
 * @author: Alex Standiford
 * @date  : 10/17/17
 */

use \ebl\app\glass;
if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object
$field_id = $this->field;
$field_id = $field_id::$fieldID;

?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value=" <?= $this->metaValue; ?>"/>
<div class="ebl-glass-shape-wrapper" data-wrapper-id="<?= $field_id; ?>">
  <?php
  foreach($this->getGlassShapes() as $glass_shape):
    $glass = new glass((int)$this->postID, $glass_shape);
    $class = $this->getMetaValue() == $glass_shape ? 'glass-shape mod--selected' : 'glass-shape'; ?>

    <div class="<?= $class; ?>" data-glass-shape="<?= $glass_shape ?>">
      <?= $glass->glass(); ?>
      <span><?= ucfirst($glass_shape); ?> <?php if($glass_shape == 'bottle'): ?> Only <?php endif; ?></span>
    </div>
  <?php endforeach; ?>
</div>
