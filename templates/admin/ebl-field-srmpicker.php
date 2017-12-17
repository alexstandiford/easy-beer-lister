<?php
/**
 * SRM Picker template for metaboxes
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this->field instanceof \ebl\admin\field) return; //Bail early if we aren't in a meta box object
$field_id = $this->field;
$field_id = $field_id::$fieldID;

?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value=" <?= $this->field->metaValue; ?>"/>
<div class="ebl-srm-wrapper" data-wrapper-id="<?= $field_id; ?>">
  <?php foreach($this::$srm_values as $key => $srm_value): $key++; ?>
    <?php $class = $this->field->metaValue == $key ? 'ebl-srm-value mod--selected' : 'ebl-srm-value'; ?>
    <div class="<?= $class; ?>" data-srm-hex="<?= $srm_value ?>" data-srm-value="<?= $key; ?>" style="background-color:<?= $srm_value; ?>"><?= $key; ?></div>
  <?php endforeach; ?>
</div>