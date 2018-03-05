<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://hraza.de
 * @since      1.0.0
 *
 * @package    League_Stats
 * @subpackage League_Stats/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    League_Stats
 * @subpackage League_Stats/public
 * @author     Hussnain Raza <han_raza@hotmail.com>
 */
class League_Stats_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $user_id;
	private $wpdb;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
	    global $wpdb;
	    $this->wpdb = $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $user = get_current_user_id();
        $this->user_id = $user->ID;
        $this->table_prefix = $wpdb->prefix;
        $this->table_league_results = $wpdb->prefix. 'myleague_results';


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in League_Stats_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The League_Stats_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/league-stats-public.css', array(), $this->version, 'all' );

	}
    public function get_current_user_id(){
        return $this->user_id;
    }
    public function get_wpdb(){
        global $wpdb;
        return $wpdb;
    }
    public function get_table_prefix(){
        return $this->table_prefix;
    }

    public function get_table_league_results(){
        return $this->table_league_results;
    }
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in League_Stats_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The League_Stats_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/league-stats-public.js', array( 'jquery' ), $this->version, false );

	}
    public function league_shortcodes(){
	    add_shortcode( 'league-stats-display', array( $this, 'league_stats_display_callback') );

    }
    public function league_stats_display_callback($atts ){
        //do your plugin stuff
        $result = $this->wpdb->get_results('SELECT * FROM '.$this->table_league_results .' WHERE user_id="'.get_current_user_id().'"');
        $html = '<h3 style="text-align:center">Answered Duels Results</h3><br><table><thead>';
        $html.= '<th>Home Team</th><th>Away Team</th><th>Schedule</th><th>Selected Option</th><th>Results</th></thead><tbody>';
        foreach ($result as $row){
            $html.='<tr><td>'.get_post_meta( $row->post_id, 'home_team' )[0].'</td>';
            $html.='<td>'.get_post_meta( $row->post_id, 'away_team' )[0].'</td>';
            $html.='<td>'.get_post_meta( $row->post_id, 'match_date' )[0].'</td>';
            $html.='<td>'.$row->selected_option.'</td>';
            $html.='<td>'.getMetaValue("results", $row->post_id).'</td>';
            $html.='</tr>';

        }
        $html.='</tbody></table>';
        return $html;
    }

}
