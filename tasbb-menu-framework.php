<?php
$tasbb_menu_templates = [];
//Class to call for menu query
class tasbb_menu{
  public function __construct($column_default = 10){
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
		$this->columnDefault = $column_default;
		$this->beerColumnOverride = get_post_meta(get_the_ID(),'tasbb_beers_per_column',true);
		if($this->beerColumnOverride != null || $this->beerColumnOverride > 0){
			$this->beersPerColumn = $this->beerColumnOverride;
		}
		else{
			$this->beersPerColumn = $this->columnDefault;
		}
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

//Constructor for new menu template
class tasbb_menu_template{
	public function register($args){
		$this->directory = $args['directory'];
		$this->file_name = $args['file_name'];
		$this->name = $args['template_name'];
		$this->register_template();
		$this->slug = strtolower(str_replace(" ","-",sanitize_text_field($this->name)));
	}
	private function register_template(){
		global $tasbb_menu_templates;
		$tasbb_menu_templates[] = $this;
	}
}

function tasbb_get_menu_template($slug){
	global $tasbb_menu_templates;
	foreach($tasbb_menu_templates as $template){
		if($template->slug == $slug){
			$result = $template;
			return $result;
		}
		else{
			$result = false;
		}
	}
	return $result;
}

//Constructs the default menu
$tasbb_default_print_menu = new tasbb_menu_template;
$tasbb_default_print_menu->register([
	'directory' => plugin_dir_path(__FILE__),
	'file_name' => 'tasbb-menu-template.php',
	'template_name' => 'Default Print Template'
]);

//Constructs the default TV menu template
$tasbb_default_tv_menu = new tasbb_menu_template;
$tasbb_default_tv_menu->register([
	'directory' => plugin_dir_path(__FILE__),
	'file_name' => 'tasbb-tv-menu-template.php',
	'template_name' => 'Default TV Template'
]);

//Setup for a menu
function tasbb_menu_head(){
		if(!is_user_logged_in()){
        do_action('tasbb_menu_not_logged_in');
		  	$error_message = '<h1>Please log in to view this content</h1>';
        echo $error_message;
		  die;
		}
      else{?>
        <!DOCTYPE HTML>
        <head><?php
          do_action('tasbb_menu_head_scripts');?>
          <?php }; ?>
          <style>
            <?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_css',true); ?>
          </style>
        </head>
<?php   do_action('tasbb_menu_head');
}

//Checks if menu template exists
function tasbb_locate_menu_template($template){
	if(file_exists(get_stylesheet_directory().'/'.'menu-'.$template.'.php')){
		return get_stylesheet_directory().'/'.'menu-'.$template.'.php';
	}
	else{
		return false;
	}
}