<?php 

include("wp-load.php");
include("simple_html_dom.php");

global $wpdb;


if(isset($_GET['json'])){
    query_posts(array( 
        'post_type' => 'my_league',
        'post_status' => 'publish',
        'showposts' => 50000

    ) );
        $han = array();
        while ( have_posts() ) : the_post();
        $han[] = get_post();
        
    endwhile;

    echo json_encode($han);
  }

if(isset($_GET['cron'])){

	query_posts(array( 
        'post_type' => 'my_league',
    	'post_status' => 'publish',
    	'showposts' => 50000

    ) );





    ?>

    <?php while ( have_posts() ) : the_post();
    	$post = get_post();
    	if(getMetaValue("home_team_logo", $post->ID) == ""){
    		$soccer=new XMLSoccer("KLUJFUFVYCMWEEIGOQLPVNKWDOAKIKJFXMCPEINUKFWRUFPIVP");

    		$players=$soccer->GetTeam(array("teamName"=>getMetaValue("home_team", $post->ID))) or die("sdahfkjsa");
    		print_r($players);
    		foreach($players as $key=>$value){
    			$url = (string) $value->WikiPageUrl;

    			$html = file_get_html($url);
    			if(!empty($html) && !empty($url)){
    				$img = "";
	    			foreach($html->find('.infobox tbody tr td a img') as $element) 
	       				$img = $element->src;
			        if($img != ""){
				        wp_update_post(array(
				        'ID'    =>  $post->ID,
				        'post_status'   =>  'draft'
				        ));
				        update_field("home_team_logo", $img, $post->ID);
			        }
	        	}
    			exit();
    		}
    		
    	}
     ?>

			<h1><?php echo getMetaValue("home_team_logo", $post->ID); ?></h1>

			<img src="<?php the_field('hero_image'); ?>" />


		<?php endwhile; // end of the loop. 
}


if (isset($_GET['publish'])){
 
	while ( have_posts() ) : the_post();
    	$post = get_post();    			        
        wp_update_post(array(
        'ID'    =>  $post->ID,
        'post_status'   =>  'publish'
        ));   
        print_r($post); 		
	endwhile;
$change_status_from = 'draft';
$change_status_to = 'publish';
$update_query = new WP_Query(array('post_status'=>$change_status_from, 'post_type'=>"my_league", 'posts_per_page'=>-1));
print "ajsf";
if($update_query->have_posts()){

    while($update_query->have_posts()){

        $update_query->the_post();
        wp_update_post(array('ID'=>$post->ID, 'post_status'=>$change_status_to));

    }

}

}
 
print_r($_GET);
?>