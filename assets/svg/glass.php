<?php
/**
 * Grabs a single beer glass
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\glass) return; //Bail early if we aren't in a beer glass object

?>
<svg class="ebl-glass ebl-glass-<?= $this->glassShape ?>" data-srm-value="<?= $this->srm; ?>" style="color:<?= $this->srmHex ?>;" viewBox="<?= $this->getViewbox(); ?>">
  <use xlink:href="#<?= $this->glassShape; ?>"></use>
</svg>