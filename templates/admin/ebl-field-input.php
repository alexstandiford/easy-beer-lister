<?php
/**
 * Default Input template for Metabox Fields
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


use ebl\admin\field;

if(!defined('ABSPATH')) exit;
if(!$this->field instanceof field) return; //Bail early if we aren't working with a field object
?>
<input <?= $this->field->inputArgs() ?>name="<?= $this->field->id ?>" id="<?= $this->field->id; ?>" value="<?= $this->field->metaValue; ?>"/>
