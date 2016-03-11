<?php

class tasbb_ut{
  public function __construct(){
  	$ID = '107E2F6BA2ADE6A462D97976000782C1EC7A797D';
  	$key = '1206E9C1093C50ADF259943FB1298B6C5E8F904E';
  	$apiVersion = 'v4';
  	$breweryID = 2663;
    $this->breweryUrl = 'https://api.untappd.com/'.$apiVersion.'/brewery/info/'.$breweryID.'?client_id='.$ID.'&client_secret='.$key;
		//$json = file_get_contents($this->breweryUrl);
		$json = file_get_contents(plugin_dir_url(__FILE__).'test.json');
		$this->obj = json_decode($json);
		$this->beers = $this->obj->response->brewery->beer_list->items;
	}
	
	//Returns a specific beer as an object
	public function get_beer_info($value, $type = "bid"){
		$type = strtolower($type);
		$beer_array = $this->beers;
		foreach($beer_array as $struct) {
			if ($value == $struct->beer->$type) {
					$result = $struct;
					return $result;
			}
		}
	}
	
	//Imports beers into WordPress DB
	public function import_beers(){
		foreach($this->beers as $obj){
			$postarr = [
				'post_title'   => $obj->beer->beer_name,
				'post_content' => $obj->beer->beer_description,
				'post_excerpt' => $obj->beer->beer_description,
				'guid'         => $obj->beer->bid,
				'post_type'    => 'beers',
				'post_status'  => 'publish'
			];
			if( null == get_page_by_title($obj->beer->beer_name,null,"beers")){
				echo '<p>'.$obj->beer->beer_name." was sucessfully imported</p>";
			$post_id = wp_insert_post($postarr);
			tasbb_generate_featured_image($obj->beer->beer_label,$post_id);
			wp_set_object_terms($post_id,$obj->beer->beer_style,'style');
			$obj->beer->beer_abv > 0 ? update_post_meta($post_id,'tasbb_abv',$obj->beer->beer_abv) : '';
			$obj->beer->beer_ibu > 0 ? update_post_meta($post_id,'tasbb_ibu',$obj->beer->beer_ibu) : '';
			update_post_meta($post_id,'tasbb_untappd-url','https://untappd.com/b/'.$obj->brewery->brewery_slug.'/'.$obj->beer->bid);
			}
			else{
				echo '<p>'.$obj->beer->beer_name." was sucessfully updated</p>";
			$obj->beer->beer_abv > 0 ? update_post_meta(get_page_by_title($obj->beer->beer_name,null,"beers"),'tasbb_abv',$obj->beer->beer_abv) : '';
			$obj->beer->beer_ibu > 0 ? update_post_meta(get_page_by_title($obj->beer->beer_name,null,"beers"),'tasbb_ibu',$obj->beer->beer_ibu) : '';
			update_post_meta(get_page_by_title($obj->beer->beer_name,null,"beers"),'tasbb_untappd-url','https://untappd.com/b/'.$obj->brewery->brewery_slug.'/'.$obj->beer->bid);
			}
		}
	}
};

function tasbb_generate_featured_image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}
