<?php
/**
 * Glass Shape Field Picker
 * @author: Alex Standiford
 * @date  : 10/17/17
 */

use \ebl\app\glass;
use ebl\app\glassLayout;

if(!defined('ABSPATH')) exit;
if(!$this->field instanceof \ebl\admin\field) return; //Bail early if we aren't in a meta box object
$field_id = $this->field;
$field_id = $field_id::$fieldID;
$post_id = isset($this::$postID) ? (int)$this::$postID : null;
$meta_value = explode(',', $this->field->metaValue);
?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value="<?= $this->field->metaValue; ?>"/>
<div class="ebl-glass-options-wrapper" data-wrapper-id="<?= $field_id; ?>">
  <div class="ebl-glass-shape-wrapper ebl-glass-item-wrapper">
    <strong>Glass Shape</strong>
    <?php
    foreach($this->getGlassShapes() as $glass_shape):
      if($glass_shape === 'bottle') continue;
      $glass_layout_args = isset($post_id) ? [['shape' => $glass_shape]] : [['shape' => $glass_shape, 'srm' => 9, 'layout' => 'glass']];
      $glass = new glassLayout($post_id, $glass_layout_args);
      $class = $meta_value[0] == $glass_shape ? 'glass-shape mod--selected' : 'glass-shape'; ?>

      <div class="<?= $class; ?>" data-glass-shape="<?= $glass_shape ?>">
        <?php $glass->getGlassLayout(); ?>
        <span><?= ucfirst($glass_shape); ?></span>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="ebl-glass-layout-wrapper ebl-glass-item-wrapper">
    <strong>Glass Layout</strong>
    <?php
    foreach($this->getGlassLayouts() as $glass_layout):
      $glass_layout_args = [];
      $i = 0;
      foreach($glass_layout as $layout){
        if($layout == 'glass'){
          $glass_layout_args[$i]['shape'] = $meta_value[0] ? $meta_value[0] : 'shaker';
        }
        else{
          $glass_layout_args[$i]['shape'] = 'bottle';
        }

        if(!isset($post_id)){
          $glass_layout_args[$i]['layout'] = implode('-',$glass_layout);
          $glass_layout_args[$i]['srm'] = 9;
        }
        $i++;
      }
      $glass = new glassLayout($post_id, $glass_layout_args);
      $class = 'glass-layout';
      $glass_layout_string = implode('-', (array)$glass_layout);
      if($glass_layout_string == $this->getSelectedLayout()) $class .= ' mod--selected';
      ?>

      <div class="<?= 'glass-shape glass-shape-'.$glass_layout_string.' '.$class; ?>" data-glass-layout="<?= $glass_layout_string ?>">
        <?php $glass->getGlassLayout(); ?>
        <!--        <span>--><? //= $shape;
        ?><!--</span>-->
      </div>
    <?php endforeach; ?>
  </div>
</div>
