<?php
/**
 * Beer Options Wrapper template
 * @author: Alex Standiford
 * @date  : 11/11/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\admin\optionsPage) return; //Bail early if we aren't in an options page object
?>
<div class="ebl-options-wrapper wrap">
  <?php if($this->hasTabs()): ?>
    <nav class="ebl-tabs nav-tab-wrapper">
      <?php while($this->hasTabs()): $this->theTab(); ?>
        <a href="?page=<?= $this->args['menu_slug'] ?>&tab=<?= $this->tab->slug ?>" class="<?= $this->tab->tabClass(); ?>"><?= $this->tab->title; ?></a>
      <?php endwhile; ?>
    </nav>
  <?php endif; ?>
  <h1><?= $this->args['page_title']; ?></h1>
  <?php if($this->haveFields()): ?>
    <form method="post">
      <?php while($this->haveFields()): $this->theField();
        ?>
        <div id="<?= $this->field->id.'-wrapper' ?>" data-field-id="<?= $this->field->fieldID() ?>" class="<?= $this->field->class ?>">
          <p class="label">
            <strong><label for="<?= $this->field->id ?>"><?= $this->field->name ?></label></strong><br>
            <?= $this->field->description; ?>
          </p>
          <?php $this->input(); ?>
        </div>
      <?php endwhile; ?>
      <?php submit_button(); ?>
    </form>
  <?php endif; ?>
</div>