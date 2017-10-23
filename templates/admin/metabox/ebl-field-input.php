<?php
/**
 * Default Input template for Metabox Fields
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object
?>

<input <?= $this->field->inputArgs() ?> name="<?= $this->field->id ?>" id="<?= $this->field->id; ?>" value="<?= $this->field->metaValue; ?>"/>
