<?php
/**
 * Creates a WordPress Options Page
 * Handles all options page fields, tabs, and admin menu additions
 * @author: Alex Standiford
 * @date  : 11/11/17
 */


namespace ebl\admin;


if(!defined('ABSPATH')) exit;

class optionsPage extends fieldLoop{

  public static $options_pages = [
    'beer_options' => [
      'args' => [
        'page_title' => 'Easy Beer Lister Settings',
        'menu_title' => 'Easy Beer Lister',
      ],
      'tabs' => [
        'default' => [
          'fields' => [
            [
              'name'           => 'Default Top Beer Label',
              'description'    => 'When no top label is specified, this label will be used.',
              'type'           => 'imageupload',
              'preview_target' => 'top-label-target',
            ],
            [
              'name'           => 'Default Bottom Beer Label',
              'description'    => 'When no bottom label is specified, this label will be used.',
              'type'           => 'imageupload',
              'preview_target' => 'bottom-label-target',
            ],
            [
              'name'        => 'Currency Symbol',
              'description' => 'Specify your currency symbol here. (defaults to $)',
            ],
            [
              'name'        => 'Disable Individual Beer Pages?',
              'description' => 'Check this box if you wish to disable single beer pages on your site. Any existing link to a single beer page will automatically redirect to the beer listing page, instead.',
              'type'        => 'checkbox',
            ],
            [
              'name'           => 'Default behavior for unavailable beers',
              'description'    => 'Select how you would prefer beers set to "Unavailable" to be displayed',
              'type'           => 'select',
              'select_options' => [
                0 => 'By default, show unavailable beers on beer listings',
                1 => 'By default, hide unavailable beers from beer listings',
              ],
            ],
          ],
        ],
      ],
    ],
  ];
  public $args;
  public $name;
  public $template;
  public $tabs;
  public $currentTab;
  public $inTabLoop = false;
  public $pageSlug;

  public function __construct($page_to_load, $tab = null){
    $this->name = $page_to_load;
    $this->loadMenuItem();

    //Load in the current options page fields if, and only if, we're on the correct admin page.
    if($this->isCurrentOptionsPage()){
      parent::__construct($this->getFields(), $this->name);
    }
  }

  /**
   * Checks to see if the current page is the options page loaded in
   * This allows us to skip loading unnecessary fields when we don't have the current options page open
   * @return bool
   */
  private function isCurrentOptionsPage(){
    return isset($_GET['page']) && $_GET['page'] == $this->args['menu_slug'];
  }

  /**
   * Adds the current page to the settings menu
   */
  private function loadMenuItem(){
    $this->args = $this->parseArgs(apply_filters('ebl_'.$this->name.'_page_args', self::$options_pages[$this->name]['args'], self::$options_pages[$this->name]['args']));
    add_action('admin_menu', [$this, 'addOptionsPage']);
  }

  /**
   * Grabs the options fields of the current tab for the parent constructor
   * @return array
   */
  private function getFields(){
    $this->tabs = $this->getTabs();
    $this->currentTab = isset($_GET['tab']) ? $_GET['tab'] : key(self::$options_pages[$this->name]['tabs']);
    if(!array_key_exists($this->currentTab, self::$options_pages[$this->name]['tabs'])) $this->throwError('options02', 'The current tab '.$this->currentTab.' does not exist in the list of tabs for '.$this->name);

    return apply_filters('ebl_'.$this->name.'_page_fields', self::$options_pages[$this->name]['tabs'][$this->currentTab]['fields'], self::$options_pages[$this->name]['tabs'][$this->currentTab]['fields']);
  }

  /**
   * Loads in the options page
   * @return bool
   */
  public function addOptionsPage(){
    if($this->hasErrors()) return false;
    add_options_page(
      $this->args['page_title'],
      $this->args['menu_title'],
      $this->args['capability'],
      $this->args['menu_slug'],
      [$this, 'loadTemplate']
    );

    return true;
  }

  /**
   * Parses the args and loads in defaults
   *
   * @param $args
   *
   * @return array|bool
   */
  private function parseArgs($args){
    $defaults = [
      'capability' => 'manage_options',
      'menu_slug'  => str_replace(' ', '_', strtolower($args['page_title'])),
    ];
    $args = wp_parse_args($args, $defaults);

    return $args;
  }

  /**
   * Gets the tabs
   * @return mixed
   */
  private function getTabs(){
    $tabs = [];
    if(isset(self::$options_pages[$this->name]['tabs'])){
      $tabs = self::$options_pages[$this->name]['tabs'];
    }
    $tabs = apply_filters('ebl_'.$this->name.'_page_tabs', $tabs, $tabs);

    return $tabs;
  }

  /**
   * Checks to see if the current options page has tabs
   * @return bool
   */
  public function hasTabs(){
    if(!$this->inTabLoop){
      $this->inTabLoop = true;

      return count($this->tabs) == 1 ? false : true;
    }

    return !empty($this->tabs);
  }

  /**
   * Loads the next tab in the loop
   */
  public function theTab(){
    $this->currentTab = key($this->tabs);
    $title = isset($this->tabs[$this->currentTab]['title']) ? $this->tabs[$this->currentTab]['title'] : false;
    $this->tab = new optionsTab($this->currentTab, $title);
    array_shift($this->tabs);
  }

  /**
   * Loads all of the options pages in the array.
   */
  public static function loadAll(){
    foreach(self::$options_pages as $page_name => $page_args){
      new self($page_name);
    }
  }

  /**
   * Checks for errors before loading in pages
   * @return mixed
   */
  protected function checkForErrors(){
    if(!isset($this->args['page_title'])) $this->throwError('options01', 'The "page_title" value is not set for this field type. Please set this in the options array.');
    if(!isset($this->args['menu_title'])) $this->throwError('options02', 'The "menu_title" value is not set for this field type. Please set this in the options array.');
    if(!isset($this->args['menu_slug'])) $this->throwError('options04', 'The "menu_slug" value is not set for this field type. Please set this in the options array.');

    return false;
  }

  /**
   * Standard input handler, with extra options update handler
   * Solely because I hate the options API WITH A FIERY PASSION.
   * @return string|\WP_Error
   */
  public function input(){
    if(isset($_POST[$this->field->id])){
      $this->updateOption($this->field->id, $_POST[$this->field->id]);
      $this->field->metaValue = $this->getOption($this->field->id);
    }
    elseif(!empty($_POST)){
      $this->deleteOption($this->field->id);
      $this->field->metaValue = $this->getOption($this->field->id);
    }

    return parent::input();
  }

  /**
   * Runs just like the default theField() funciton, but hooks in nonce and security for options updates
   */
  public function theField(){
    $the_field = parent::theField();
    if($this->inLoop == false){
      if(!current_user_can('manage_options')){
        wp_die('Unauthorized user');
      }
      if(!wp_verify_nonce(EBL_PREFIX.'_beer_nonce')){
        wp_die('Nonce verification failed');
      }
    }

    return $the_field;
  }
}