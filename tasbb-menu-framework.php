<?php

class tasbb_menu{
  public function __construct(){
    $this->filter = [
      'sort'             => get_post_meta(get_the_ID(),'tasbb_export_sort_order',true),
      'sortby'           => get_post_meta(get_the_ID(),'tasbb_export_sortby',true),
      'on-tap'           => get_post_meta(get_the_ID(),'tasbb_export_ontap',true),
      'pairings'         => get_post_meta(get_the_ID(),'tasbb_export_pairings',true),
      'tags'             => tasbb_parse_taxonomy_checkbox('tags'),
      'style'            => tasbb_parse_taxonomy_checkbox('style'),
      'availability'     => tasbb_parse_taxonomy_checkbox('availability'),
      'show_description' => get_post_meta(get_the_ID(),'tasbb_export_show_description',true),
      'show_price'       => get_post_meta(get_the_ID(),'tasbb_export_show_price',true),
      'show_image'       => get_post_meta(get_the_ID(),'tasbb_export_show_img',true),
      'show_ibu'         => get_post_meta(get_the_ID(),'tasbb_export_show_ibu',true),
      'show_abv'         => get_post_meta(get_the_ID(),'tasbb_export_show_abv',true),
      'show_og'          => get_post_meta(get_the_ID(),'tasbb_export_show_og',true),
      'show_style'       => get_post_meta(get_the_ID(),'tasbb_export_show_style',true),
    ];
    $this->heading = get_post_meta(get_the_ID(),'tasbb_export_menu_heading',true);
    $this->subheading = get_post_meta(get_the_ID(),'tasbb_export_menu_subheading',true);
    $this->beforeMenu = get_post_meta(get_the_ID(),'tasbb_export_menu_before_menu',true);
    $this->afterMenu = get_post_meta(get_the_ID(),'tasbb_export_menu_after_menu',true);
	}
	
	//Imports beers into WordPress DB
	public function args(){
       $args = [
        'post_type' => 'beers',
        'order'     => $this->filter['sort'],
          'orderby' => 'meta_value_num',
          'meta_key' => $this->filter['sortby'],
        "tax_query" => [],
          'posts_per_page' => -1
        ];

       if($this->filter['sortby'] == 'name'){
          $args['orderby'] = 'name';
          unset($args['meta_key']);
       }
        //--- ON TAP ---//
      if($this->filter['on-tap'] != null){
        array_push($args['tax_query'], [
            "taxonomy"  => "availability",
            "field" => "slug",
            "terms" => "on-tap"
          ]);
      };
        //--- Pairings ---//
      if($this->filter['pairings'] != null){
        $this->filter['pairings'] = str_replace(' ','-',$this->filter['pairings']);
        $this->filter['pairings'] = strtolower($this->filter['pairings']);
        $this->filter['pairings'] = str_getcsv($this->filter['pairings']);
        array_push($args['tax_query'],[
          'taxonomy' => 'pairing',
          'field' => 'slug',
          'terms' => $this->filter['pairings']
        ]);
      };
        //--- Tags ---//
      if($this->filter['tags'] != null){
        array_push($args['tax_query'],[
          'taxonomy' => 'tags',
          'field' => 'slug',
          'terms' => $this->filter['tags']
        ]);
      };
        //--- Availability ---//
      if($this->filter['availability'] != null){
        array_push($args['tax_query'],[
          'taxonomy' => 'availability',
          'field' => 'slug',
          'terms' => $this->filter['availability']
        ]);
      };
        //--- type ---//
      if($this->filter['style'] != null){
        array_push($args['tax_query'],[
          'taxonomy' => 'style',
          'field' => 'slug',
          'terms' => $this->filter['style']
        ]);
      };
     return $args;
	}
};

//Setup for a menu
function tasbb_menu_head(){
		if(!is_user_logged_in()){
        do_action('tasbb_menu_not_logged_in');
		  $error_message = '<h1>Please log in to view this content</h1>';
        echo $error_message;
		  die;
		}
      else{
        $scripts = [
            plugin_dir_url(__FILE__).'style/tasbb-print.css'
          ]?>
        <!DOCTYPE HTML>
        <head><?php
          do_action('tasbb_menu_head_scripts');
          foreach($scripts as $script){?>
          <link rel="stylesheet" href="<?php echo $script; ?>">
          <?php }; ?>
          <style>
            <?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_css',true); ?>
          </style>
        </head>
<?php   do_action('tasbb_menu_head');
      }
}