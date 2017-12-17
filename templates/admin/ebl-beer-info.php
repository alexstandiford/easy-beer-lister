<?php
/**
 * Single Beer Metabox template
 * @author: Alex Standiford
 * @date  : 10/16/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\metaBox) return; //Bail early if we aren't in a meta box object

if($this->haveFields()):
  while($this->haveFields()): $this->theField();
    ?>
    <div id="<?= $this->field->id.'-wrapper' ?>" data-field-id="<?= $this->field->fieldID() ?>" class="<?= $this->field->class ?>">
      <p class="label">
        <label for="<?= $this->field->id ?>"><?= $this->field->name ?></label><br>
        <?= $this->field->description; ?>
      </p>
      <?php $this->input(); ?>
    </div>
  <?php endwhile; endif; ?>