<?php
/*
Plugin Name: My League for Wordpress
Plugin URI: http://hraza.de
Version: 1.0
Author: Hussnain Raza
Description: Extract data from xmlsoccer.com and create quizes in wordpress
*/
require(plugin_dir_path(__FILE__) . 'XMLSoccer.php');
require(plugin_dir_path(__FILE__) . 'shortcodes.php');
require(plugin_dir_path(__FILE__) . 'vendor/autoload.php');


use Sportmonks\SoccerAPI\SoccerAPIClient;

//Database Tables
function create_plugin_database_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'myleague_results';
    $sql = "CREATE TABLE $table_name (
	 id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
	 user_id int(128) unsigned NOT NULL,
	 post_id int(128) unsigned NOT NULL,
	 match_id int(128) unsigned NOT NULL,
	 team_id int(128) unsigned NOT NULL,
	 status int(3) unsigned NOT NULL,
	 selected_option varchar(25) unsigned NOT NULL,
	 PRIMARY KEY  (id)
	 );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_plugin_database_table');


//Check User already clicked the result

function check_post_user_myleague($user_id, $post_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'myleague_results';
    $sql = "SELECT * FROM $table_name WHERE user_id = %s AND post_id = %s;";
    return $wpdb->get_results($wpdb->prepare($sql, array($user_id, $post_id)));


}

function check_post_date($post_id)
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'posts';
    $table_name1 = $wpdb->prefix . 'postmeta';
    $sql = "SELECT * FROM `martin_wppostmeta` as pm JOIN martin_wpposts as pp ON pm.post_id=pp.ID where pm.meta_key='match_date' and pm.meta_value < CURDATE() and pp.ID=%s";
    $result = $wpdb->get_results($wpdb->prepare($sql, array($post_id)));


    if (sizeof($result) == 0) {
        return 0;
    } else {
        return 1;
    }
}

// function check_post_time($post_id){

// 	global $wpdb;
// 	$table_name = $wpdb->prefix . 'posts';
// 	$table_name1 = $wpdb->prefix . 'postmeta';
// 	$sql = "SELECT * FROM `martin_wppostmeta` as pm JOIN martin_wpposts as pp ON pm.post_id=pp.ID where pm.meta_key='match_time' and pm.meta_value < CURTIME() and pp.ID=%s";
// 	$result = $wpdb->get_results($wpdb->prepare($sql, array($post_id)));


// 	if(sizeof($result)== 0){
// 		return 0;
// 	}else{
// 		return 1;
// 	}
// }
function check_premiere_league($post_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'posts';
    $table_name1 = $wpdb->prefix . 'postmeta';
    $sql2 = "SELECT * FROM `martin_wppostmeta` as pm JOIN martin_wpposts as pp ON pm.post_id=pp.ID where pm.meta_key='league_id' and pm.meta_value = '8' and pp.ID=%s";
    $result2 = $wpdb->get_results($wpdb->prepare($sql2, array($post_id)));

    if (sizeof($result2) == 0) {
        return 1;
    } else {
        return 0;
    }

}

// register jquery and style on initialization
add_action('init', 'register_script');
function register_script()
{
    wp_register_script('myleague_script', plugins_url('/js/myleague.js', __FILE__), array('jquery'), '2.5.1');
    wp_register_script('myleague_script_date_as', plugins_url('/js/date.js', __FILE__), array('jquery'), '2.5');
    wp_register_style('myleague', plugins_url('/css/myleague.css', __FILE__), false, '1.0.0', 'all');
    wp_register_style('fonticons', plugins_url('/font/flaticon.css', __FILE__), false, '1.0.0', 'all');

}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_style');

function enqueue_style()
{
    wp_enqueue_script('myleague_script');
    wp_enqueue_script('myleague_script_date_as');
    wp_enqueue_style('myleague');
    wp_enqueue_style('fonticons');

}

add_action('init', 'my_league_wp');

function my_league_wp()
{

    //hook into the init action and call create_topics_nonhierarchical_taxonomy when it fires

    add_action( 'init', 'create_topics_nonhierarchical_taxonomy', 0 );

    register_post_type('my_league',
        array(
            'labels' => array(
                'name' => 'Prediction Duels',
                'singular_name' => 'Prediction Duels',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Duels',
                'edit' => 'Edit',
                'edit_item' => 'Edit Duel',
                'new_item' => 'New Duel',
                'view' => 'View',
                'view_item' => 'View Duel',
                'search_items' => 'Search Duel',
                'not_found' => 'No Duel found',
                'not_found_in_trash' => 'No Duel found in Trash',
                'parent' => 'Parent Duel'
            ),

            'public' => true,
            'menu_position' => 14,
            'supports' => array('title', 'editor', 'comments', 'thumbnail', 'custom-fields'),
            'taxonomies' => array(''),
            'menu_icon' => plugins_url('images/image.png', __FILE__),
            'has_archive' => true
        )
    );



    $labels = array(
        'name' => _x( 'League', 'taxonomy general name' ),
        'singular_name' => _x( 'League', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search League' ),
        'all_items' => __( 'All Leagues' ),
        'parent_item' => __( 'Parent League' ),
        'parent_item_colon' => __( 'Parent League:' ),
        'edit_item' => __( 'Edit League' ),
        'update_item' => __( 'Update League' ),
        'add_new_item' => __( 'Add New League' ),
        'new_item_name' => __( 'New League Name' ),
        'menu_name' => __( 'Leagues' ),
    );

// Now register the taxonomy

    register_taxonomy('league',array('my_league'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'topic' ),
    ));




}




function my_league_setting_menu()
{
    add_menu_page('Prediction Duels Settings', 'Prediction Duel Settings', 'manage_options',
        'my_league_settings', 'my_league_settings_page', '', 16);
}

// This tells WordPress to call the function named "my_league_setting_menu"
// when it's time to create the menu pages.
add_action("admin_menu", "my_league_setting_menu");
function my_league_settings_page()
{
    ?>
    <div class="wrap">
        <?php screen_icon('themes'); ?> <h2>Front page elements</h2>

        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="num_elements">
                            Get all New Matches for next 7 days
                        </label>
                    </th>

                </tr>
                <tr>
                    <td>
                        <p>
                            <input type="submit" name="update_settings" value="Save settings" class="button-primary"/>
                        </p>
                    </td>
                </tr>
            </table>
        </form>


        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="num_elements">
                            Update Current Matches with score results and give credits to the user
                        </label>
                    </th>

                </tr>
                <tr>
                    <td>
                        <p>
                            <input type="submit" name="update_results_matches" value="Save settings"
                                   class="button-primary"/>
                        </p>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <?php


    $date = date('Y-m-d', strtotime("+7 days"));
    if (isset($_POST["update_settings"])) {
        print_r($_POST);
        try {

            $soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
            $soccerAPI->setPage(1);
            $soccerAPI->setInclude(["localTeam", "visitorTeam", "league", "round", "venue", "season"]);
            $matches = $soccerAPI->fixturesBetweenDates(date("Y-m-d"), $date);
            $pages = $matches->meta->pagination->total_pages;
            print_r($matches->meta);
            $i = 1;
            while ($i <= $pages) {
                $soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
                $soccerAPI->setPage($i);
                $soccerAPI->setInclude(["localTeam", "visitorTeam", "league","league.country", "round", "venue", "season"]);
                $matches = $soccerAPI->fixturesBetweenDates(date("Y-m-d"), $date);

                foreach ($matches->data as $key => $value) {
                    $localTeam = $value->localTeam->data;
                    $visitorTeam = $value->visitorTeam->data;
                    $round = $value->round->data;
                    $location = $value->venue->data;
                    $league = $value->league->data;
                    $league_country = $league->country->data->name;
                    $season = $value->season->data;
                    if (checkMatchIdExists($value->id)) {
                        continue;
                    }

                    echo $localTeam->name . " VS " . $visitorTeam->name;
                    echo "<br> Match ID= " . $value->id . " ID=" . $value->league_id . " Name=" . $league->name . "<br><br>";
                    print_r($league);
                    echo "--------------------------------------------------------------<br>";
                    $post_id = wp_insert_post(array(
                        'post_type' => 'my_league',
                        'post_title' => $localTeam->name . " VS " . $visitorTeam->name,
                        'post_content' => "Match is between " . $localTeam->name . " VS " . $visitorTeam->name,
                        'post_status' => 'publish',
                        'comment_status' => 'closed',   // if you prefer
                    ));

                    update_field("match_id", $value->id, $post_id);
                    update_field("match_date", $value->time->starting_at->date, $post_id);
                    update_field("match_time", $value->time->starting_at->time, $post_id);
                    update_field("round", $round->name, $post_id);
                    update_field("home_team", $localTeam->name, $post_id);
                    update_field("home_team_id", $value->localteam_id, $post_id);
                    update_field("home_team_logo", $localTeam->logo_path, $post_id);

                    update_field("away_team", $visitorTeam->name, $post_id);
                    update_field("away_team_id", $value->visitorteam_id, $post_id);
                    update_field("away_team_logo", $visitorTeam->logo_path, $post_id);

                    update_field("location_team", $location->name, $post_id);
                    update_field("league_name", $league->name, $post_id);
                    update_field("league_id", $value->league_id, $post_id);
                    update_field("season_id", $value->season_id, $post_id);
                    update_field("season_name", $season->name, $post_id);
                    update_field("league_country", $league_country, $post_id);

                    $taxonomy = 'league';
                    $cat_name = $league->name;
                    //get the category to check if exists
                    $cat  = get_term_by('name', $cat_name , $taxonomy);
                    $cat_id = 0;
                    //check existence
                    if($cat == false){
                        //cateogry not exist create it
                        $cat = wp_insert_term($cat_name, $taxonomy);
                        //category id of inserted cat
                        $cat_id = $cat['term_id'] ;
                    }else{
                        //category already exists let's get it's id
                        $cat_id = $cat->term_id ;
                    }
                    $res = wp_set_post_terms($post_id, array($cat_id),$taxonomy ,true);


                }//end of foreach

                $i = $i + 1;
            }//end of while

        } catch (XMLSoccerException $e) {
            echo "XMLSoccerException: " . $e->getMessage();
            print_r($e);
            print "bye bye";
        }
    }


    if (isset($_POST['update_results_matches'])) {
        global $wpdb;
        $query = new WP_Query(array(
            'post_type' => 'my_league',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        $table_name = $wpdb->prefix . 'myleague_results';

        $sql = "select * FROM $table_name WHERE status=1";
        $result = $wpdb->get_results($sql);
        foreach ($result as $key => $value) {
            $post_id = $value->post_id;
            $alreadyExist = 0;
            $metaResult = getMetaValue("results", $post_id);
            if( $metaResult!= ""){
                $alreadyExist = 1;
            }
            if($alreadyExist ==1){

                if ($value->selected_option == $metaResult) {
                    $soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
                    $soccerAPI->setInclude(["localTeam", "visitorTeam"]);
                    $matches = $soccerAPI->oddsByMatchAndBookmakerId(getMetaValue("match_id", $post_id), 2)->data;
                    $oddValue = 1;
                    if ($metaResult == "home") {
                        $oddValue = $matches[0]->bookmaker->data[0]->odds->data[0]->value;

                    } elseif ($metaResult == "draw") {
                        $oddValue = $matches[0]->bookmaker->data[0]->odds->data[1]->value;
                    } elseif ($metaResult == "away") {
                        $oddValue = $matches[0]->bookmaker->data[0]->odds->data[2]->value;
                    } else {

                        echo "Can't get the odds for " . get_the_title($post_id);
                    }
                    $newOddValue = $oddValue * 100;
                    echo $newOddValue;

                    mycred_add('duels_answered', $value->user_id, $newOddValue, 'Points for Correct Duel Answer for ' . getMetaValue("home_team", $post_id). ' VS ' . getMetaValue("away_team", $post_id));
                }

                $sql = "UPDATE " . $table_name . " SET status = 0 WHERE post_id = $post_id";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
                echo "<p>Updated Results from Already saved result for ".get_the_title($post_id)."in user ID=".$value->user_id."</p><br>";



            }
            else {
                $now = time();
                $date = getMetaValue("match_date", $post_id); #could be (almost) any string date
                $match_time = getMetaValue("match_time", $post_id);
                $match_date = strtotime($date . " " . $match_time);
                if ($match_date < $now) {

                    //Call the API
                    $soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
                    $soccerAPI->setInclude(["localTeam", "visitorTeam"]);
                    $matches = $soccerAPI->oddsByMatchAndBookmakerId(getMetaValue("match_id", $post_id), 2)->data;
                    $TeamData = $soccerAPI->fixturesByMatchId(getMetaValue("match_id", $post_id))->data;
                    $localTeamScore = $TeamData->scores->localteam_score;
                    $visitorTeamScore = $TeamData->scores->visitorteam_score;
                    //get Results
                    $matchResult = "";
                    if ($localTeamScore == $visitorTeamScore ) {
                        $matchResult = 'draw';
                    } elseif ($localTeamScore > $visitorTeamScore) {
                        $matchResult = 'home';

                    } elseif ($localTeamScore < $visitorTeamScore) {
                        $matchResult = 'away';
                    }
                    // Update database results according to API
                    update_post_meta( $post_id, "results", "$matchResult" );
                    $sql = "UPDATE " . $table_name . " SET status = 0 WHERE post_id = $post_id";
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);
                    //Get ODDS and check for correct answer for each
                    if ($value->selected_option == $matchResult) {
                        $oddValue = 1;

                        if ($matchResult == "home") {
                            $oddValue = $matches[0]->bookmaker->data[0]->odds->data[0]->value;

                        } elseif ("draw" == $matchResult) {
                            $oddValue = $matches[0]->bookmaker->data[0]->odds->data[1]->value;

                        } elseif ("away" == $matchResult) {
                            $oddValue = $matches[0]->bookmaker->data[0]->odds->data[2]->value;
                        }else{
                            echo "Default 100 points given!!! Can't calculate odds for the match ". $TeamData->localTeam->data->name . ' VS ' . $TeamData->visitorTeam->data->name;
                        }

                        mycred_add('duels_answered', $value->user_id, ($oddValue * 100), 'Points for Correct Duel Answer for ' . $TeamData->localTeam->data->name . ' VS ' . $TeamData->visitorTeam->data->name);
                    }
                    $metaResult = getMetaValue("results", $post_id);
                    echo "<p>Updated Results from API and saved result for ".get_the_title($post_id)." in user ID=".$value->user_id." and result is ".$matchResult."</p><br>";


                }//($match_date < $now)

            }//else

        }//

    }//end of settings function

}




function getAllLeagues()
{
    query_posts(array(
        'post_type' => 'my_league',
        'post_status' => 'publish',
        'showposts' => 50000

    ));
    $posts = array();
    while (have_posts()) : the_post();
        $post = get_post();
        //echo the_field("home_team", $post->ID);
        $posts[] = $post;
    endwhile;
    return $posts;
}

function getMetaValue($key, $postID)
{
    return get_post_meta($postID, $key, true);
}

function checkMatchIdExists($matchid)
{


    global $wpdb;
    $table_name = $wpdb->prefix . 'posts';
    $table_name1 = $wpdb->prefix . 'postmeta';
    $sql = "SELECT * FROM `martin_wppostmeta` as pm JOIN martin_wpposts as pp ON pm.post_id=pp.ID where pm.meta_key='match_id' and pm.meta_value=%s";
    $result = $wpdb->get_results($wpdb->prepare($sql, array($matchid)));

    if (sizeof($result) == 0) {
        return false;
    } else {
        return true;
    }
    return true;
}

?>