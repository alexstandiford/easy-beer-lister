<?php

class tasbb_ut{
  public $ID = '107E2F6BA2ADE6A462D97976000782C1EC7A797D';
  public $key = '1206E9C1093C50ADF259943FB1298B6C5E8F904E';
  public $breweryID = 2663;
  public $apiVersion = 'v4';
  public function __construct(){
    $this->breweryUrl = 'https://api.untappd.com/'.$this->apiVersion.'/brewery/info/'.$this->breweryID.'?client_id='.$this->ID.'&client_secret='.$this->key;
  }
};
$tasbb_ut = new tasbb_ut;

function tasbb_get_brewery_info(){
  //global $tasbb_ut;
  //$json = file_get_contents($tasbb_ut->breweryUrl);
  $json = file_get_contents(plugin_dir_url(__FILE__).'test.json');
  $obj = json_decode($json);
  foreach($obj->response->brewery->beer_list->items as $beer){
  var_dump($beer->beer);  
  }
}

tasbb_get_brewery_info();