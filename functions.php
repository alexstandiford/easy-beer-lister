<?php
$bbcount = 1;

/*--- GET BEER FIELD ---*/
function tasbb_get_field($taxonomy,$post_id = null,$format_value = null){
	if($post_id == null){
		$post_id = get_the_id();
	}
	return get_post_meta( $post_id, $taxonomy, true);
}

/*--- RETURNS BEER INFORMATION LOOP ---*/
function tasbb_get_beer_info($taxonomy,$info = 'name',$post_id = null){
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
    $beer_info = tasbb_get_field($taxonomy);
  }
  return $beer_info;
}

/*--- SPITS OUT BEER INFORMATION ---*/
function tasbb_beer_info($taxonomy,$tag = 'li',$post_id = null, $single = false){
  if(taxonomy_exists($taxonomy)){
  $names = tasbb_get_beer_info($taxonomy,'name',$post_id);
  $ids = tasbb_get_beer_info($taxonomy,'term_id',$post_id);
  $i = 0;
  if($single == true){
    echo $names[0];
    return;
  }
  foreach($names as $name){
    if($name != 'On-Tap'){
      $result .= do_action('tasbb_beer_info_before_tag');
      $result .= '<'.$tag.' class="'.$taxonomy.' ';
      $result .= do_action( 'tasbb_beer_info_class' );
      $result .= '">';
      $result .=  '<a href="'.get_term_link($ids[$i],$taxonomy).'">';
      $result .=    $name;
      $result .=  '</a>';
      $result .= '</'.$tag.'>';
      $result .= do_action('tasbb_beer_info_after_tag');
      $i++;
    }
  }
  echo $result;
  }
  elseif(tasbb_beer_info_exists($taxonomy)){
    $result = tasbb_get_field($taxonomy);

    if($taxonomy == 'abv'){
      $result .= '%';
    };
    echo $result;
  };
}

/*--- SPITS OUT BEER INFORMATION URL ---*/
function tasbb_beer_info_url($taxonomy,$post_id = null, $single = false){
  if(taxonomy_exists($taxonomy)){
  $names = tasbb_get_beer_info($taxonomy,'name',$post_id);
  $ids = tasbb_get_beer_info($taxonomy,'term_id',$post_id);
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
function tasbb_beer_video(){
	do_action('tasbb_before_video');
  $result = do_shortcode('[video src="'.tasbb_get_field('tasbb_video').'"]');
	do_action('tasbb_after_video');
  echo $result;
}

/*--- CREATES GALLERY FROM BEER INFO ---*/
function tasbb_beer_gallery(){
	do_action('tasbb_before_gallery');
	$result = do_shortcode('[gallery ids="'.tasbb_get_field('tasbb_gallery').'"]');
	do_action('tasbb_after_gallery');
	echo $result;
}

/*--- CHECK IF BEER IS ON-TAP ---*/
function tasbb_beer_is_on_tap($post_id = null){
  if($post_id == null){
    $post_id = get_the_id();
  };
  if(in_array('On-Tap',tasbb_get_beer_info('availability','name',$post_id))){
    return true;
  }
  else{
    return false;
  }
}

/*--- CHECK IF A VALUE EXISTS FOR A FIELD ---*/
function tasbb_beer_info_exists($taxonomy,$info = 'name', $post_id = null){
  if(tasbb_get_beer_info($taxonomy,$info,$post_id) == null){
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
$post_availability = tasbb_get_beer_info('availability','name',$post_id);
unset($post_availability[array_search('On-Tap',$post_availability)]);
$post_availability = implode(', ',$post_availability);
if($a['text'] == ''){
  $a['text'] = $post_title;
};
global $bbcount;

?>
<?php

$on_tap_msg = apply_filters( 'tasbb_on_tap_msg', 'On Tap Now!' );
$r .='<a id="beer-'.$bbcount.'" class="'.do_action('tasbb_add_beer_shortcode_class').' beer-url" href="'.get_permalink($post_id).'">'.$a['text'].'</a>';
if(get_option('tasbb_js_hover') == FALSE){
  $e .='<figure id="beer-'.$bbcount.'-popup" class="beer-popup hidden">';
  $e .=  '<h2>'.$post_title.'</h2>';
  if(get_option('tasbb_hide_ontap_msg') != 1 && tasbb_beer_is_on_tap($post_id)){
  $e .=  '<h3>'.$on_tap_msg.'</h3>';
  };
	if($post_img != null){
  	$e .=  '<img src="'.$post_img.'">';
	};
  $e .=  '<figcaption>'.$post_excerpt.'</figcaption>';
  $e .=  '<dl>';
	if(get_option('tasbb_hide_og') != 1 && tasbb_get_field('tasbb_og',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>O.G.</dt>';
		$e .=      '<dd>'.tasbb_get_field('tasbb_og',$post_id).'</dd>';
		$e .=    '</div>';
	};
	if(get_option('tasbb_hide_ibu') != 1 && tasbb_get_field('tasbb_ibu',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>IBUs</dt>';
		$e .=      '<dd>'.tasbb_get_field('tasbb_ibu',$post_id).'</dd>';
		$e .=    '</div>';
	};
	if(get_option('tasbb_hide_abv') != 1 && tasbb_get_field('tasbb_abv',$post_id) != null){
		$e .=    '<div>';
		$e .=      '<dt>ABV</dt>';
		$e .=      '<dd>'.tasbb_get_field('tasbb_abv',$post_id).'%</dd>';
		$e .=    '</div>';
	};
  $e .=  '</dl>';
};
if($post_availability == 'Year-Round'){
$e .=apply_filters('tasbb_year_round_msg','<aside>available year-round</aside>');
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

/*--- BEER LOOP SHORTCODE ---*/
function tasbb_beer_list_shortcode($atts){
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
  $r .= '<dd class="tasbb-shortcode-beer-description">';
  $r .= get_the_excerpt();
  };
	if($a['show_price'] == TRUE){
	$r .= '<span class="price">';
	$r .= '$'.tasbb_get_beer_info('tasbb_price');
	$r .= '</span>';
	}
	if($a['show_price'] == TRUE || $a['show_description'] == TRUE){
  $r .= '</dd>';
	}
  $r .= '</'.$a['wrapper'].'>';
  endwhile; endif;
  return $r;
}
add_shortcode( 'beer_list', 'tasbb_beer_list_shortcode' );

function tasbb_email_sidebar(){?>
	<div class="tasbb-admin-sidebar">
		<div class="tasbb-sidebar-item">
			<h2>BrewBuddy was proudly made by Alex Standiford</h2>
			<p>I am here to help breweries manage their online presence faster. I do that by providing breweries with tools, tips, and tricks that make their lives easier.</p>
			<p>If you ever have <em>any</em> questions about WordPress, or need customizations to your website don't hesitate to send me a message.  I'll be happy to help you out in any way I can.</p>
			<ul>
				<li>Email: <a href="mailto:a@alexstandiford.com">a@alexstandiford.com</a></li>
				<li><a href="http://www.twitter.com/alexstandiford" target="blank">Follow me on Twitter</a></li>
				<li><a href="http://www.alexstandiford.com" target="blank">Visit my website</a></li>
			</ul>
		</div>
		<div class="signup-form">	
		<div id="mc_embed_signup">
		<form action="//alexstandiford.us2.list-manage.com/subscribe/post?u=f39d9629a4dd9dd976f09f6e5&amp;id=b6a3d349e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
			<h2>Spend Less Time Updating Your Website</h2>
			<h3>Fill out the form below, and I'll send you</h3>
					<ul>
						<li>A list of my 3 must-have free plugins for brewers and bars</li>
						<li>Learn about the free tool that I use to spend less time managing social media</li>
						<li>A complete workflow of how to quickly promote events on Facebook, Instagram, and Twitter</li>
						<li>PDF to-do checklists that walk you through the process quickly</li>
						<li>Ongoing WordPress tips and tricks for breweries</li>
					</ul>
		<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
		<div class="mc-field-group">
			<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
		</label>
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>
		<div class="mc-field-group">
			<label for="mce-FNAME">First Name </label>
			<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
		</div>
		<div class="mc-field-group">
			<label for="mce-LNAME">Last Name </label>
			<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
		</div>
		<div class="mc-field-group input-group hidden">
				<ul><li><input checked type="checkbox" value="1" name="group[18977][1]" id="mce-group[18977]-18977-0"><label for="mce-group[18977]-18977-0">BrewBuddy User</label></li>
		<li><input checked type="checkbox" value="2" name="group[18977][2]" id="mce-group[18977]-18977-1"><label for="mce-group[18977]-18977-1">Website Efficency Workflow</label></li>
		</ul>
		</div>
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none"></div>
				<div class="response" id="mce-success-response" style="display:none"></div>
			</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f39d9629a4dd9dd976f09f6e5_b6a3d349e7" tabindex="-1" value=""></div>
				<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
		</form>
		</div>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
		<!--End mc_embed_signup-->
		</div>
							</div>
<?php }

?>