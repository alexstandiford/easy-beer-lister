<?php
$bbcount = 1;

/*--- RETURNS BEER INFORMATION LOOP ---*/
function get_beer_info($taxonomy,$info = 'name',$post_id = null){
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
    $beer_info = get_field($taxonomy);
  }
  return $beer_info;
}

/*--- SPITS OUT BEER INFORMATION ---*/
function beer_info($taxonomy,$tag = 'li',$post_id = null, $single = false){
  if(taxonomy_exists($taxonomy)){
  $names = get_beer_info($taxonomy,'name',$post_id);
  $ids = get_beer_info($taxonomy,'term_id',$post_id);
  $i = 0;
  if($single == true){
    echo $names[0];
    return;
  }
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
  elseif(beer_info_exists($taxonomy)){
    $result = get_field($taxonomy);

    if($taxonomy == 'abv'){
      $result .= '%';
    }
    echo $result;
  };
}

/*--- SPITS OUT BEER INFORMATION ---*/
function beer_info_url($taxonomy,$post_id = null, $single = false){
  if(taxonomy_exists($taxonomy)){
  $names = get_beer_info($taxonomy,'name',$post_id);
  $ids = get_beer_info($taxonomy,'term_id',$post_id);
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

/*--- SPITS OUT BEER PHOTOS AS GALLERY ---*/
function beer_photos($columns = '3'){
  if(beer_info_exists('additional_photos')){
  $images = get_field('additional_photos');
  $img_ids = [];
  foreach($images as $image){
    array_push($img_ids,$image['id']);
  }
  $img_ids = implode(',',$img_ids);
  $result .= do_shortcode('[gallery ids="'.$img_ids.'" columns="'.$columns.'"]');
  echo $result;
  }
  else{
    return;
  }
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

/*--- CHECK IF A VALUE EXISTS FOR A FIELD ---*/
function beer_info_exists($taxonomy,$info = 'name', $post_id = null){
  if(get_beer_info($taxonomy,$info,$post_id) == null){
    $r = false;
  }
  else{
    $r = true;
  }
  return $r;
}
/*--- BEER SHORTCODE ---*/
function tasbb_beer_shortcode($atts){
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
wp_reset_postdata();
echo $e;
return $r;
}
add_shortcode( 'beer', 'tasbb_beer_shortcode' );

?>