<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$bbcount = 1;

/*--- GET BEER FIELD ---*/
function ebl_get_field($taxonomy,$post_id = null,$format_value = null){
	if($post_id == null){
		$post_id = get_the_id();
	}
	return get_post_meta( $post_id, $taxonomy, true);
}

/*--- RETURNS BEER INFORMATION LOOP ---*/
function ebl_get_beer_info($taxonomy,$info = 'name',$post_id = null){
  if(taxonomy_exists($taxonomy)){
      if($post_id == null){
        $post_id = get_the_id();
      };
      $beer_info_process = wp_get_post_terms($post_id,$taxonomy);
      $beer_info = [];
      foreach($beer_info_process as $beer_info_item){
        array_push($beer_info,$beer_info_item->$info);
      }
    }
  else{
    $beer_info = ebl_get_field($taxonomy);
  }
  return $beer_info;
}

/*--- SPITS OUT BEER INFORMATION ---*/
function ebl_beer_info($taxonomy,$tag = 'li',$post_id = null, $single = false, $link = true){
  if(taxonomy_exists($taxonomy)){
  $names = ebl_get_beer_info($taxonomy,'name',$post_id);
  $ids = ebl_get_beer_info($taxonomy,'term_id',$post_id);
  $i = 0;
  if($single == true){
    echo $names[0];
    return;
  }
  foreach($names as $name){
    if($name != 'On-Tap'){
      $result .= do_action('ebl_beer_info_before_tag');
      $result .= '<'.$tag.' class="'.$taxonomy.' ';
      $result .= do_action( 'ebl_beer_info_class' );
      $result .= '">';
      $result .=  $link == true ? '<a href="'.get_term_link($ids[$i],$taxonomy).'">' : '';
      $result .=    $name;
      $result .=  $link == true ? '</a>' : '';
      $result .= '</'.$tag.'>';
      $result .= do_action('ebl_beer_info_after_tag');
      $i++;
    }
  }
  echo $result;
  }
  elseif(ebl_beer_info_exists($taxonomy)){
    $result = ebl_get_field($taxonomy);

    if($taxonomy == 'abv'){
      $result .= '%';
    };
    echo $result;
  };
}

/*--- SPITS OUT BEER INFORMATION URL ---*/
function ebl_beer_info_url($taxonomy,$post_id = null, $single = false){
  if(taxonomy_exists($taxonomy)){
  $names = ebl_get_beer_info($taxonomy,'name',$post_id);
  $ids = ebl_get_beer_info($taxonomy,'term_id',$post_id);
  $i = 0;
  if($single == true){
    echo get_term_link($ids[$i],$taxonomy);
    return;
  }
  foreach($names as $name){
      $result .= get_term_link($ids[$i],$taxonomy);
      $i++;
  }
  echo $result;
}
}

/*--- SPITS OUT BEER VIDEO ---*/
function ebl_beer_video(){
	do_action('ebl_before_video');
  $result = do_shortcode('[video src="'.ebl_get_field('ebl_video').'"]');
	do_action('ebl_after_video');
  echo $result;
}

/*--- CREATES GALLERY FROM BEER INFO ---*/
function ebl_beer_gallery(){
	do_action('ebl_before_gallery');
	$result = do_shortcode('[gallery ids="'.ebl_get_field('ebl_gallery').'"]');
	do_action('ebl_after_gallery');
	echo $result;
}

/*--- CHECK IF BEER IS ON-TAP ---*/
function ebl_beer_is_on_tap($post_id = null){
  if($post_id == null){
    $post_id = get_the_id();
  };
  if(in_array('On-Tap',ebl_get_beer_info('availability','name',$post_id))){
    return true;
  }
  else{
    return false;
  }
}

/*--- CHECK IF A VALUE EXISTS FOR A FIELD ---*/
function ebl_beer_info_exists($taxonomy,$info = 'name', $post_id = null){
  if(ebl_get_beer_info($taxonomy,$info,$post_id) == null){
    $r = false;
  }
  else{
    $r = true;
  }
  return $r;
}

/*--- CHECK IF ANY BEER MEASUREMENTS EXIST ---*/
function ebl_beer_measurements_exist($post_id = null){
	if($post_id == null){
		$post_id = get_the_id();
	};
  if(ebl_beer_info_exists('ebl_abv','name',$post_id) || ebl_beer_info_exists('ebl_ibu','name',$post_id) || ebl_beer_info_exists('ebl_og','name',$post_id)){
    return true;
  }
  else{
    return false;
  }
}

/*--- BEER SHORTCODE ---*/
function ebl_beer_shortcode($atts){
  $a = shortcode_atts( array(
    'name' => '',
    'text'  => ''
  ), $atts );
$a['name'] = str_replace('-',' ',$a['name']);
$a['name'] = strtolower($a['name']);
$args=array(
  'name' => $a['name'],
  'post_type' => 'beers',
  'post_status' => 'publish',
  'showposts' => 1,
);
$my_posts = new WP_Query($args);
$post_id = $my_posts->posts[0]->ID;
$post_img = wp_get_attachment_url( get_post_thumbnail_id($post_id));
$post_title = get_the_title($post_id);
$post_excerpt = $my_posts->posts[0]->post_excerpt;
$post_availability = ebl_get_beer_info('availability','name',$post_id);
unset($post_availability[array_search('On-Tap',$post_availability)]);
$post_availability = implode(', ',$post_availability);
if($a['text'] == ''){
  $a['text'] = $post_title;
};
global $bbcount;

?>
<?php

$on_tap_msg = apply_filters( 'ebl_on_tap_msg', 'On Tap Now!' );
$r .='<a id="beer-'.$bbcount.'" class="'.do_action('ebl_add_beer_shortcode_class').' beer-url" href="'.get_permalink($post_id).'">'.$a['text'].'</a>';
if(get_option('ebl_js_hover') == FALSE){
	if(get_option('ebl_js_hover_x') == 0){
		$x = 0;
	}
	else
	{
		$x = get_option('ebl_js_hover_x');
	}
	if(get_option('ebl_js_hover_y') == 0){
		$y = 0;
	}
	else
	{
		$y = get_option('ebl_js_hover_y',10);
	}
  $e .='<figure id="beer-'.$bbcount.'-popup" class="beer-popup hidden" style="transform:translate('.$x.'px,'.$y.'px);">';
  $e .=  '<h2>'.$post_title.'</h2>';
  if(get_option('ebl_hide_ontap_msg') != 1 && ebl_beer_is_on_tap($post_id)){
  $e .=  '<h3>'.$on_tap_msg.'</h3>';
  };
	if($post_img != null){
  	$e .=  '<img src="'.$post_img.'">';
	};
  $e .=  '<figcaption>'.$post_excerpt.'</figcaption>';
  $e .=  '<dl>';
	if(get_option('ebl_hide_og') != 1 && ebl_get_field('ebl_og',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>O.G.</dt>';
		$e .=      '<dd>'.ebl_get_field('ebl_og',$post_id).'</dd>';
		$e .=    '</div>';
	};
	if(get_option('ebl_hide_ibu') != 1 && ebl_get_field('ebl_ibu',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>IBUs</dt>';
		$e .=      '<dd>'.ebl_get_field('ebl_ibu',$post_id).'</dd>';
		$e .=    '</div>';
	};
	if(get_option('ebl_hide_abv') != 1 && ebl_get_field('ebl_abv',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>ABV</dt>';
		$e .=      '<dd>'.ebl_get_field('ebl_abv',$post_id).'%</dd>';
		$e .=    '</div>';
	};
  $e .=  '</dl>';
};
if($post_availability == 'Year-Round'){
$e .=apply_filters('ebl_year_round_msg','<aside>available year-round</aside>');
}
else{
$e .= '<aside>available during '.$post_availability.'</aside>';
};
$e .='</figure>';
$bbcount++;
wp_reset_postdata();
echo $e;
return $r;
}
add_shortcode( 'beer', 'ebl_beer_shortcode' );

/*--- BEER LOOP SHORTCODE ---*/
function ebl_beer_list_shortcode($atts){
  $a = shortcode_atts( array(
    'wrapper' => 'div',
    'sort' => 'desc',
    'style' => null,
    'on-tap'  => null,
    'pairings' => null,
    'tags' => null,
    'availability' => null,
    'show_description' => false,
		'show_price' => false
  ), $atts );
  $args = [
    'post_type' => 'beers',
    'orderby' => $a['sort'],
    "tax_query" => []
    ];
  
    //--- ON TAP ---//
  if($a['on-tap'] != null){
    array_push($args['tax_query'], [
        "taxonomy"  => "availability",
        "field" => "slug",
        "terms" => "on-tap"
      ]);
  };
    //--- Pairings ---//
  if($a['pairings'] != null){
    $a['pairings'] = str_replace(' ','-',$a['pairings']);
    $a['pairings'] = strtolower($a['pairings']);
    $a['pairings'] = str_getcsv($a['pairings']);
    array_push($args['tax_query'],[
      'taxonomy' => 'pairing',
      'field' => 'slug',
      'terms' => $a['pairings']
    ]);
  };
    //--- Tags ---//
  if($a['tags'] != null){
    $a['tags'] = str_replace(' ','-',$a['tags']);
    $a['tags'] = strtolower($a['tags']);
    $a['tags'] = str_getcsv($a['tags']);
    array_push($args['tax_query'],[
      'taxonomy' => 'tags',
      'field' => 'slug',
      'terms' => $a['tags']
    ]);
  };
    //--- Availability ---//
  if($a['availability'] != null){
    $a['availability'] = str_replace(' ','-',$a['availability']);
    $a['availability'] = strtolower($a['availability']);
    $a['availability'] = str_getcsv($a['availability']);
    array_push($args['tax_query'],[
      'taxonomy' => 'availability',
      'field' => 'slug',
      'terms' => $a['availability']
    ]);
  };
    //--- type ---//
  if($a['style'] != null){
    $a['style'] = str_replace(' ','-',$a['style']);
    $a['style'] = strtolower($a['style']);
    $a['style'] = str_getcsv($a['style']);
    array_push($args['tax_query'],[
      'taxonomy' => 'style',
      'field' => 'slug',
      'terms' => $a['style']
    ]);
  };
  $beers = new WP_Query($args);
  if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post();
  $r .= '<'.$a['wrapper'].'>';
  $r .= '<dl><a href="'.get_post_permalink().'">';
  $r .=  get_the_title();
  $r .= '</a></dl>';
  //--- DESCRIPTION ---//
  if($a['show_description'] == TRUE){
  $r .= '<dd class="ebl-shortcode-beer-description">';
  $r .= get_the_excerpt();
  };
	if($a['show_price'] == TRUE){
	$r .= '<span class="price">';
	$r .= '$'.ebl_get_beer_info('ebl_price');
	$r .= '</span>';
	}
	if($a['show_price'] == TRUE || $a['show_description'] == TRUE){
  $r .= '</dd>';
	}
  $r .= '</'.$a['wrapper'].'>';
  endwhile; endif;
  return $r;
}
add_shortcode( 'beer_list', 'ebl_beer_list_shortcode' );

?>
