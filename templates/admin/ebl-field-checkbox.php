<?php
/**
 * Checkbox Field for Admin Pages
 * @author: Alex Standiford
 * @date  : 11/12/17
 */


if(!defined('ABSPATH')) exit;
if(!$this->field instanceof \ebl\admin\field) return; //Bail early if we aren't in a meta box object
$this->field->inputArgs['type'] = 'checkbox';
?>
<input <?= $this->field->inputArgs() ?> name="<?= $this->field->id ?>" id="<?= $this->field->id; ?>" <?= checked($this->field->metaValue,"on"); ?>/>
