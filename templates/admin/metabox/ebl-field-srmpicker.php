<?php
/**
 * SRM Picker template for metaboxes
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object
$field_id = $this->field;
$field_id = $field_id::$fieldID;

?>
<input class="hidden" <?= $this->field->inputArgs(); ?> name="<?= $this->field->id; ?>" id="<?= $this->field->inputTarget; ?>" value=" <?= $this->metaValue; ?>"/>
<div class="ebl-srm-wrapper" data-wrapper-id="<?= $field_id; ?>">
  <?php foreach($this::SRM_VALUES as $key => $srm_value): $key++; ?>
    <?php $class = $this->getMetaValue() == $key ? 'ebl-srm-value mod--selected' : 'ebl-srm-value'; ?>
    <div class="<?= $class; ?>" data-srm-hex="<?= $srm_value ?>" data-srm-value="<?= $key; ?>" style="background-color:<?= $srm_value; ?>"><?= $key; ?></div>
  <?php endforeach; ?>
</div>