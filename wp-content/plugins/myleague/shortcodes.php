<?php 
use Sportmonks\SoccerAPI\SoccerAPIClient;

function get_meta_values( $meta_key,  $post_type = 'post' ) {

    $posts = get_posts(
        array(
            'post_type' => $post_type,
            'meta_key' => $meta_key,
            'posts_per_page' => -1,
        )
    );

    $meta_values = array();
    foreach( $posts as $post ) {
        $meta_values[] = get_post_meta( $post->ID, $meta_key, true );
    }

    return $meta_values;

}

$meta_values = get_meta_values( $meta_key, $post_type );



add_shortcode( 'my_league_display_all', 'display_all_my_leagues' ); 
function display_all_my_leagues() {
    // if ( isset( $_POST['gg'] ) ) {
    //     $post = array(
    //         'post_content' => $_POST['content'], 
    //         'post_title'   => $_POST['title']
    //     );
    //     $id = wp_insert_post( $post, $wp_error );
    // }

	$current_user = wp_get_current_user();
	$query = new WP_Query(array(
    'post_type' => 'my_league',
    'post_status' => 'publish',
    'posts_per_page' => -1,
		));

    $terms = get_terms([
        'taxonomy' => 'league',
        'hide_empty' => false,
    ]);
    $html_options = '';
    foreach ($terms as $term) {
        $post_id = get_the_ID();
        $html_options .="<option id='".$terms->term_id."'>". $term->name."</option>";
    }

	$html ="<link rel='stylesheet' id='myleague-css-datepick'  href='https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.3/datepicker.min.css' type='text/css' media='all' />";
	$html .="<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.3/datepicker.js'></script>";
    $html .= "<div class='et_pb_row et_pb_row_0 glyph' >";
    $html .= "<label>Select League:</label>";
    $html .="<select class='et_pb_contact_select input' id='duels_filter_leagues'  name='league_filter'><option id='reset'>Select All Leagues</option>".$html_options."</select>";

//Countries Filter Starts here
    $countries = get_meta_values( 'league_country', my_league );
    $countries = array_unique($countries);
    $html_options = '';
    foreach ($countries as $key => $term) {
        $post_id = get_the_ID();
        $html_options .="<option id='".$key."'>". $term."</option>";
    }

    $html .= "<label>Select Country:</label>";
    $html .="<select class='et_pb_contact_select input' id='duels_filter_countries' name='league_filter'><option id='reset'>Select All Countries</option>".$html_options."</select>";
    $html .= "<label>Start Date:</label>";
    $html .="<input class='et_pb_contact_select input'  id='duels_filter_date_from' placeholder='DD-MM-YYYY' name='league_filter_date_from' />";
    $html .= "<label>End Date:</label>";
    $html .="<input class='et_pb_contact_select input'  id='duels_filter_date_to' placeholder='DD-MM-YYYY' name='league_filter_date_to' />";
    $html.="<br><br><br><div class=\"et_contact_bottom_container\"><button type=\"button\" id='duels_filter_date_apply_filter' class=\"et_pb_contact_submit et_pb_button\">Apply Filter</button></div>";
    $html .="</div>";


	while ($query->have_posts()) {
	    $query->the_post();
	    $post = get_post();
	    $post_id = get_the_ID();
	    // check_post_date($post_id) == 1  ||
//	    if(sizeof(check_post_user_myleague($current_user->ID, $post_id))>0 ||  check_premiere_league($post_id) == 1)
//	    	continue;

	    $html .= "<div class='my-league-row et_pb_row et_pb_row_0 glyph' date=".date("d-m-Y",strtotime(getMetaValue("match_date", $post->ID)))." country=".getMetaValue('league_country', $post->ID)." league='".getMetaValue('league_name', $post->ID)."'>";
	    $html .= "<h2>".$post->post_title."</h2>";
	    $html .= "<p> <strong>Date:</strong> ".date("d-m-Y",strtotime(getMetaValue("match_date", $post->ID)))." ".getMetaValue("match_time", $post->ID)."</p>";
	    $html .= "<h3> League: ".getMetaValue('league_name', $post->ID) ."</h3>";
	    $html .= "<h3> Country: ".getMetaValue('league_country', $post->ID) ."</h3>";
	    $html .= "<h3> Who will win the Match ?</h3>";

	    $html .= "<div class='wrapper-for-answers'><div class='et_pb_column_0 et_pb_column_1_3'>";
	    $hometeamid = getMetaValue('home_team_id', $post->ID);


	    $html .= "<a href='#' class='my-league-answer my-league-selected-answer'  postid='".$post->ID."' matchid='".getMetaValue("match_id", $post->ID)."' teamid='".getMetaValue("home_team_id", $post->ID)."' option='home'><img src='".getMetaValue("home_team_logo", $post->ID)."' />";
	    $html .= "<p>".getMetaValue("home_team", $post->ID)."</p></a>";
	    $html .= "</div>";

	    $html .="<div style='padding-left: 70px;' class='et_pb_column_1_3 result-section my-league-selected-answer' postid='".$post->ID."' matchid='".getMetaValue("match_id", $post->ID)."' teamid='".getMetaValue("home_team_id", $post->ID)."' option='draw'>
	    <img src='".plugins_url( "images/equalsto.png", __FILE__ )."'/><br><span class=''>Draw </span> </div>";

	    $html .= "<div class='et_pb_column_1 et_pb_column_1_3'>";
	    $awayteamid = getMetaValue('away_team_id', $post->ID);
	    $html .= "<a href='#' class='my-league-answer my-league-selected-answer'  postid='".$post->ID."' matchid='".getMetaValue("match_id", $post->ID)."' teamid='".getMetaValue("away_team_id", $post->ID)."' option='away'><img src='".getMetaValue("away_team_logo", $post->ID)."' />";
	    $html .= "<p>".getMetaValue("away_team", $post->ID)."</p></a>";	    
	    $html .= "</div>";
	    $html .= "</div>";
	    $html .= "</div>";


	    //print_r($post);
	    }
    return $html;

}

add_shortcode( 'my_league_display_odds_match', 'display_odds_match' ); 
function display_odds_match() {




	if(isset($_GET['matchid']) && isset($_GET['team'])){
		$soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
	    $odds = $soccerAPI->oddsByMatchId($_GET['matchid']);
	    $soccerAPI->setInclude(["localTeam", "visitorTeam"]);
	    $match = $soccerAPI->fixturesByMatchId($_GET['matchid'])->data;
	    $html ="<h2 align='center'>".$match->localTeam->data->name." VS ". $match->visitorTeam->data->name."</h2>";
	    $html .= "<img style='margin-left:100px;' src='".$match->localTeam->data->logo_path."' />"; 
	    $html .= "<img style='float:right;margin-right:100px;' src='".$match->visitorTeam->data->logo_path."' />"; 
	    $html .= "<br><br><table><thead><tr><th>Betting Site</th><th>Home Team</th><th>Draw</th><th>Away Team</th></tr></thead><tbody>";
		foreach ($odds->data[0]->bookmaker->data as $key => $value) {
			 // print_r($value);
			 // echo "<br><br><br>--------------------------------------------------<br><br><br>";
				$html .="<tr>";
				$html .="<td>".$value->name."</td>";
				if($value->odds->data[0]->label == "Home" || $value->odds->data[0]->label == 1){
					$home = $value->odds->data[0]->value;
				}elseif($value->odds->data[0]->label == "Away" || $value->odds->data[0]->label == 2){
					$away =$value->odds->data[0]->value;
				}elseif($value->odds->data[0]->label == "Draw" || $value->odds->data[0]->label == "X"){
					$draw = $value->odds->data[0]->value;
				}
				if($value->odds->data[1]->label == "Home" || $value->odds->data[1]->label == 1){
					$home = $value->odds->data[1]->value;
				}elseif($value->odds->data[1]->label == "Away" || $value->odds->data[1]->label == 2){
					$away =$value->odds->data[1]->value;
				}elseif($value->odds->data[1]->label == "Draw" || $value->odds->data[1]->label == "X"){
					$draw = $value->odds->data[1]->value;
				}
				if($value->odds->data[2]->label == "Home" || $value->odds->data[2]->label == 1){
					$home = $value->odds->data[2]->value;
				}elseif($value->odds->data[2]->label == "Away" || $value->odds->data[2]->label == 2){
					$away =$value->odds->data[1]->value;
				}elseif($value->odds->data[2]->label == "Draw" || $value->odds->data[2]->label == "X"){
					$draw = $value->odds->data[2]->value;
				}
				$html .="<td>".$home."</td>";
				$html .="<td>".$draw."</td>";
				$html .="<td>".$away."</td>";
			}
		$html .="</tbody></table>";
		return $html;

	}else{
		return "Sorry Match Id Not defined";
	}

}


add_action('init','do_stuff');
function do_stuff(){
	 if(isset($_POST['shortcodeAnswer'])){

		global $wpdb;
		 $table_name = $wpdb->prefix . 'myleague_results';
		 $postid = $_POST['postid'];
		 $matchid = $_POST['matchid'];
		 $teamid = $_POST['teamid'];
		 $option = $_POST['option'];
		 $current_user = wp_get_current_user();
		 $sql = "INSERT INTO ".$table_name. " (`user_id`, `post_id`, `match_id`, `team_id`, `selected_option`, status) VALUES(".$current_user->ID.",".$postid.",". $matchid.",$teamid, '$option', 1);";
		 require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		 dbDelta( $sql );
		 //echo "success";
		 $soccerAPI = new SoccerAPIClient("6c1QxQqgvBGCpc7RQVqNl0P8yUKDiDlYwm3SFgFeUkF9VaaakXLniPXxtyMv");
	     $soccerAPI->setInclude(["localTeam", "visitorTeam"]);
	     $match = $soccerAPI->fixturesByMatchId($matchid)->data;
		 mycred_add( 'duels_answered', $current_user->ID, 10, 'Points Answering Duel for '. $match->localTeam->data->name.' VS '. $match->visitorTeam->data->name );
		 echo json_encode(getPercentageByPostId($postid));



		exit();
	}
}

function getPercentageByPostId($postid){
	global $wpdb;
	$table_name = $wpdb->prefix . 'myleague_results';
	$sql = "select team_id,selected_option, count(*) AS total FROM $table_name WHERE post_id=$postid GROUP BY selected_option, team_id";
	$result = $wpdb->get_results ($sql);
	$grandTotal = 0;
	foreach ($result as $key => $value) {
		$grandTotal = $grandTotal + $value->total;
	}
	$homeTeam = 0;
	$awayTeam = 0;
	$draw = 0;
	foreach ($result as $key => $value) {
		$teamid = $value->team_id;
		$selected_option = $value->selected_option;
		$total = $value->total;	
		if($selected_option == 'draw'){
			$draw = round(($value->total / $grandTotal) * 100 ,2);
		}elseif($selected_option == 'home'){

			$homeTeam = round(($value->total / $grandTotal) * 100, 2);
		}elseif($selected_option == 'away'){

			$awayTeam = round(($value->total / $grandTotal) * 100, 2);
		}
	}

return array("home" => $homeTeam, 
			 "away" => $awayTeam,
			 "draw" => $draw);

}