<?php
/**
 * Custom Post Type Registration
 * @author: Alex Standiford
 * @date  : 10/14/17
 */

namespace ebl\core;

if(!defined('ABSPATH')) exit;

class cpt{

  private static $instance = null;

  private function __construct(){
  }

  /**
   * Initialize the Custom Post Type(s) in Easy Beer Lister
   * All Custom Post Types, and Taxonomies are created from a single filter-able array.
   * @return array
   */
  private function getOverrideArgs(){
    $post_types = [
      'beers' => [
        'name'          => 'Beers',
        'singular_name' => 'Beer',
        'menu_icon'     => EBL_ASSETS_URL.'icons/beer-icon.png',
        'supports'      => ['title', 'editor', 'thumbnail'],
        'taxonomies'    => [
          'style'   => [
            'hierarchical' => true,
            'meta_box_cb' => 'metaBoxAsSelect',
            'rewrite'     => ['slug' => 'style'],
            'labels'       => [
              'label'       => __(' Style'),
            ],
          ],
          'pairing' => [
            'label'         => __('Pairings'),
          ],
          'tags'    => [
            'name'          => __('Tags'),
            'singular_name' => __('tag'),
            'label'         => __('Tags'),
          ],
        ],
      ],
    ];

    return apply_filters('ebl_post_type_register', $post_types);
  }

  public static function register(){
    self::$instance = new self;
    self::$instance->getPostTypes();
  }

  /**
   * Registers the post types from the post_type array
   */
  private function getPostTypes(){
    foreach($this->getOverrideArgs() as $post_type => $args){
      $default_args = $this->getDefaultArgs($this->getNames($post_type, $args));
      $merged_args = $this->getArgsWithDefaults($default_args, $args);
      $this->registerPostTypeWithTaxonomies($post_type, $merged_args);
    }
  }

  /**
   * Gets the singular and plural form of the given name
   */
  public function getNames($post_type, $args){
    $name = isset($args['singular_name']) ? $args['singular_name'] : false;
    $name = $name == false ? $post_type : $name;
    if(!isset($args['name'])) $args['name'] = '';
    $plural_name = isset($args['singular_name']) ? $args['name'] : $name.'s';

    return ['singular' => $name, 'plural' => $plural_name];
  }

  /**
   * Registers the post type and taxonomies (if any exist)
   *
   * @param $post_type
   * @param $merged_args
   */
  public function registerPostTypeWithTaxonomies($post_type, $merged_args){
    if(!empty($merged_args['taxonomies'])){
      $taxonomies_to_push = [];
      foreach($merged_args['taxonomies'] as $taxonomy_name => $args){
        $names = $this->getNames($taxonomy_name, $args);
        if(isset($args['meta_box_cb'])) $args['meta_box_cb'] = [$this, $args['meta_box_cb']];
        $default_args = $this->getDefaultArgs($names, 'taxonomy');
        register_taxonomy(strtolower($taxonomy_name), $post_type, $this->getArgsWithDefaults($default_args, $args));
        $taxonomies_to_push[] = $taxonomy_name;
      }
      unset($merged_args['taxonomies']);
      $merged_args['taxonomies'] = $taxonomies_to_push;
    }
    register_post_type($post_type, $merged_args);
  }

  /**
   * Creates the merged arguments of the given arguments and defaults
   *
   * @param        $default_args
   * @param        $args
   * @param string $type
   *
   * @return array
   */
  private function getArgsWithDefaults($default_args, $args){
    unset($args['name']);
    unset($args['singular_name']);
    $merged_args = is_array($args) ? array_merge_recursive($default_args, $args) : $default_args;
    if(!isset($args['labels'])) $args['labels'] = null;

    return $merged_args;
  }

  /**
   * Gets the default args for the given item type
   *
   * @param        $name
   * @param string $type - can be taxonomy or post
   *
   * @return array
   */
  private function getDefaultArgs($name, $type = 'post'){
    $plural_name = $name['plural'];
    $name = $name['singular'];

    if($type == 'post'){
      $args = [
        'public'            => true,
        'has_archive'       => true,
        'capability_type'   => 'post',
        'show_in_menu'      => true,
        'show_ui'           => true,
        'show_in_admin_bar' => true,
        'can_export'        => true,
        'menu_position'     => 5,
        'show_in_rest'      => true,
        'taxonomies'        => [],
        'supports'          => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail'],
        'labels'            => [
          'name'               => __(ucfirst($plural_name)),
          'singular_name'      => __(ucfirst($name)),
          'label'              => __(ucfirst($name)),
          'add_new'            => _x('Add New', $name),
          'add_new_item'       => __('Add New '.$name),
          'new_item'           => __('New '.$name),
          'edit_item'          => __('Edit '.$name),
          'view_item'          => __('View '.$name),
          'all_items'          => __('All '.$plural_name),
          'search_items'       => __('Search '.$plural_name),
          'not_found'          => __('No '.$plural_name.' found.'),
          'not_found_in_trash' => __('No '.$plural_name.' found in Trash.'),
        ],
      ];
    }
    else{
      $args = [
        'labels' => [
          'name'          => __(ucfirst($plural_name)),
          'singular_name' => __(ucfirst($name)),
          'menu_name'     => __(ucfirst($plural_name)),
        ],
      ];
    }

    return $args;
  }

  /**
   * Converts a taxonomy metabox to a select box. Useful for situations where only one type of meta should be select-able
   *
   * @param $post
   * @param $box
   */
  public function metaBoxAsSelect($post, $box){
    $taxonomy = $box['args']['taxonomy'];
    $name = 'tax_input['.$taxonomy.']';
    $term_obj = wp_get_object_terms($post->ID, $taxonomy);
    wp_dropdown_categories(array('taxonomy' => $taxonomy, 'name' => "{$name}[]", 'selected' => $term_obj[0]->term_id, 'orderby' => 'name', 'hierarchical' => 0, 'show_option_none' => '&mdash;'));
    echo "<p>Didn't find the style you're looking for? <a href='".admin_url().'edit-tags.php?taxonomy=style&post_type=beers'."'>add it here</a></p>";
  }
}