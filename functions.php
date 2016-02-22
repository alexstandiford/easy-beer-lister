<?php
$bbcount = 1;

function get_beer_info($taxonomy,$info = 'name',$post_id = null){
  if($post_id == null){
    $post_id = get_the_id();
  };
  $beer_info_process = wp_get_post_terms($post_id,$taxonomy);
  $beer_info = [];
  foreach($beer_info_process as $beer_info_item){
    array_push($beer_info,$beer_info_item->$info);
  }
  return $beer_info;
}

/*--- SPITS OUT BEER INFORMATION ---*/
function beer_info($taxonomy,$tag = 'li',$post_id = null){
  if(taxonomy_exists($taxonomy)){
  $names = get_beer_info($taxonomy,'name',$post_id);
  $ids = get_beer_info($taxonomy,'term_id',$post_id);
  $i = 0;
  foreach($names as $name){
    if($name != 'On-Tap'){
      $result .= '<'.$tag.'>';
      $result .=  '<a href="'.get_term_link($ids[$i],$taxonomy).'">';
      $result .=    $name;
      $result .=  '</a>';
      $result .= '</'.$tag.'>';
      $i++;
    }
  }
  echo $result;
  }
  else{
    $result = get_field($taxonomy);

    if($taxonomy == 'abv'){
      $result .= '%';
    }
    echo $result;
  };
}

/*--- SPITS OUT BEER PHOTOS AS GALLERY ---*/
function beer_photos(){
  $images = get_field('additional_photos');
  $img_ids = [];
  foreach($images as $image){
    array_push($img_ids,$image['id']);
  }
  $img_ids = implode(',',$img_ids);
  $result .= '<h3>Additional Images</h3>';
  $result .= do_shortcode('[gallery ids="'.$img_ids.'"]');
  echo $result;
}

/*--- SPITS OUT BEER VIDEO ---*/
function beer_video(){
  $result = do_shortcode('[video src="'.get_field('video').'"]');
  echo $result;
}

/*--- CHECK IF BEER IS ON-TAP ---*/
function beer_is_on_tap($post_id = null){
  if($post_id == null){
    $post_id = get_the_id();
  };
  if(in_array('On-Tap',get_beer_info('availability','name',$post_id))){
    return true;
  }
  else{
    return false;
  }
}

/*------BEER SHORTCODE------*/
function tasbb_beer_shortcode($atts){
  $a = shortcode_atts( array(
    'beer' => 'name',
    'text'  => ''
  ), $atts );
$args=array(
  'name' => $name,
  'post_type' => 'beers',
  'post_status' => 'publish',
  'showposts' => 1,
);
$my_posts = new WP_Query($args);
$post_id = $my_posts->posts[0]->ID;
$post_img = wp_get_attachment_url( get_post_thumbnail_id($post_id));
$post_title = get_the_title($post_id);
$post_excerpt = $my_posts->posts[0]->post_excerpt;
$post_availability = get_beer_info('availability','name',$post_id);
unset($post_availability[array_search('On-Tap',$post_availability)]);
$post_availability = implode(', ',$post_availability);
if($a['text'] == ''){
  $a['text'] = $post_title;
};
global $bbcount;
?>
<?php
$r .='<a id="beer-'.$bbcount.'" class="beer-url" href="'.get_permalink($post_id).'">'.$a['text'].'</a>';
$e .='<figure id="beer-'.$bbcount.'-popup" class="beer-popup hidden">';
$e .=  '<h2>'.$post_title.'</h2>';
if(beer_is_on_tap($post_id)){
$e .=  '<h3>On Tap Now!</h3>';
};
$e .=  '<img src="'.$post_img.'">';
$e .=  '<figcaption>'.$post_excerpt.'</figcaption>';
$e .=  '<dl>';
$e .=    '<div>';
$e .=      '<dt>O.G.</dt>';
$e .=      '<dd>'.get_field('og',$post_id).'</dd>';
$e .=    '</div>';
$e .=    '<div>';
$e .=      '<dt>IBUs</dt>';
$e .=      '<dd>'.get_field('ibu',$post_id).'</dd>';
$e .=    '</div>';
$e .=    '<div>';
$e .=      '<dt>ABV</dt>';
$e .=      '<dd>'.get_field('abv',$post_id).'%</dd>';
$e .=    '</div>';
$e .=  '</dl>';

if($post_availability == 'Year-Round'){
$e .= '<aside>available year-round</aside>';
}
else{
$e .= '<aside>available during '.$post_availability.'</aside>';
};
$e .='</figure>';
$bbcount++;
echo $e;
return $r;
}
add_shortcode( 'beer', 'tasbb_beer_shortcode' );

function tasbb_add_fields(){
  if(!file_exists(get_template_directory(). '/single-beers.php')){
    if(get_field('og') || get_field('ibu') || get_field('abv') || get_field('untappd_url')){
    $e .='<h3>Beer Info</h3>';
    $e .='<dl>';
    
    if(get_field('og')){
    $e .=  '<div>';
    $e .=    '<dt>Original Gravity</dt>';
    $e .=    '<dd>'.get_field('og').'</dd>';
    $e .=  '</div>';
    };
      
    if(get_field('ibu')){
    $e .=  '<div>';
    $e .=    '<dt>IBUs</dt>';
    $e .=    '<dd>'.get_field('ibu').'</dd>';
    $e .=  '</div>';
    };
      
    if(get_field('abv')){
    $e .=  '<div>';
    $e .=    '<dt>ABV</dt>';
    $e .=    '<dd>'.get_field('abv').'%</dd>';
    $e .=  '</div>';
    };
      
    if(get_field('untappd_url')){
    $e .=  '<div>';
    $e .=    '<dt>View on Untapped</dt>';
    $e .=    '<dd><a href="'.get_field('untappd_url').'" target="blank"><img src="'.plugin_dir_url(__FILE__).'media/untappd.png"></a></dd>';
    $e .=  '</div>';
    }
    
    $e .='</dl>';
    }
    
    if(get_beer_info('pairing')){
    $e .= '<p>Pairs with: ';
    $e .= implode(', ',get_beer_info('pairing'));
    $e .= "</p>";
    }
    
    if(get_field('video')){
    $e .= '<h3>Video</h3>';
    $e .= do_shortcode('[video src="'.get_field('video').'"]');
    };

    if(get_field('additional_photos')){
      $images = get_field('additional_photos');
      $img_ids = [];
        foreach($images as $image){
          array_push($img_ids,$image['id']);
        }
      $img_ids = implode(',',$img_ids);
    $e .= '<h3>Additional Images</h3>';
    $e .= do_shortcode('[gallery ids="'.$img_ids.'"]');
    };
    
    if(get_beer_info('tags')){
    $e .= '<p>Tags: ';
    $e .= implode(', ',get_beer_info('tags'));
    $e .= "</p>";
    }

    echo $e;
  }
}
add_action('loop_end','tasbb_add_fields');
?>