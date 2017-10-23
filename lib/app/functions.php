<?php
/**
 * Helper Functions
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


use ebl\app\beer;
use ebl\app\beerList;

if(!defined('ABSPATH')) exit;

/**
 * I've always wanted to make a function called this.
 * Gets the current beer object
 */
function ebl_get_beer($id = null){
  if($id) return new beer($id);
  global $ebl_beer;

  return $ebl_beer;
}

/**
 * Loads the current query into the file.
 * @return beerList
 */
function ebl_get_beer_list(){
  global $ebl_beer_list;


  return $ebl_beer_list;
}