<?php
/**
 * Template for a select box field
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object
?>
<select <?= $this->field->inputArgs() ?> name="<?= $this->field->id ?>" id="<?= $this->field->id; ?>">
  <?php foreach($this->field->selectOptions as $select_option): ?>
    <option <?= selected(sanitize_title($select_option),$this->getMetaValue()); ?> value="<?= sanitize_title($select_option); ?>"><?= __($select_option); ?></option>
  <?php endforeach; ?>
</select>
