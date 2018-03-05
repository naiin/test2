<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://hraza.de
 * @since      1.0.0
 *
 * @package    League_Stats
 * @subpackage League_Stats/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    League_Stats
 * @subpackage League_Stats/admin
 * @author     Hussnain Raza <han_raza@hotmail.com>
 */
class League_Stats_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
        global $wpdb;
        $this->wpdb = $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->table_prefix = $wpdb->prefix;
        $this->table_league_results = $wpdb->prefix. 'myleague_results';

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/league-stats-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'script-name', plugin_dir_url( __FILE__ ) . 'css/datatables.min.css', array(), $this->version, true );



	}

	/**
	 * Register the JavaScript for the admin area.
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


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/league-stats-admin.js', array( 'jquery' ), $this->version, false );
//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name.'script-name', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array(), $this->version, true );


    }

    /**
     *
     * admin/class-wp-cbf-admin.php - Don't add this
     *
     **/

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page( 'League Stats Settings', 'League Stats Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links( $links ) {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge(  $settings_link, $links );

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page() {
        include_once( 'partials/league-stats-admin-display.php' );
        echo $this->duels_answered();
        echo $this->wpb_recently_registered_users();
        echo '</div></div><!-- page-wrapper END-->';

    }


    public function validate($input) {
        // All checkboxes inputs
        $valid = array();

        //Cleanup
        $valid['cleanup'] = (isset($input['cleanup']) && !empty($input['cleanup'])) ? 1 : 0;
        $valid['comments_css_cleanup'] = (isset($input['comments_css_cleanup']) && !empty($input['comments_css_cleanup'])) ? 1: 0;
        $valid['gallery_css_cleanup'] = (isset($input['gallery_css_cleanup']) && !empty($input['gallery_css_cleanup'])) ? 1 : 0;
        $valid['body_class_slug'] = (isset($input['body_class_slug']) && !empty($input['body_class_slug'])) ? 1 : 0;
        $valid['jquery_cdn'] = (isset($input['jquery_cdn']) && !empty($input['jquery_cdn'])) ? 1 : 0;
        $valid['cdn_provider'] = esc_url($input['cdn_provider']);

        return $valid;
    }

    public function options_update() {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }
    public function league_shortcodes_admin(){
        add_shortcode( 'league-recent-users', array( $this, 'wpb_recently_registered_users') );

    }
    public function wpb_recently_registered_users() {



        $recentusers = '<div class="wrap"><h1 class="wp-heading-inline">Recent Users</h1><br><table id="league-recent-users" class="widefat fixed">';
        $recentusers.='<thead><tr><th>User Name</th><th>Email</th><th>Register Date</th></tr></thead><tbody>';

        $usernames = $this->wpdb->get_results("SELECT * FROM ".$this->wpdb->users." ORDER BY ID DESC LIMIT 20");

        foreach ($usernames as $username) {
            $recentusers .= '<tr class="alternate">';
            $recentusers .= '<td class="column-columnname">'.$username->user_nicename.'</td>';
            $recentusers .= '<td class="column-columnname">'.$username->user_email.'</td>';
            $recentusers .= '<td class="column-columnname">'.$username->user_registered.'</td>';

//            if (!$username->user_url) :
//
//                $recentusers .= '<td class="column-columnname">' .get_avatar($username->user_email, 45)."</a></td>";
//
//            else :
//
//                $recentusers .= '<td class="column-columnname">' .get_avatar($username->user_email, 45)."</td>";
//
//            endif;
            $recentusers .= '</tr>';

        }
        $recentusers .= '</tbody></table></div>';

        return $recentusers;
    }

    public function duels_answered(){

        $result = $this->wpdb->get_results('SELECT * FROM '.$this->table_league_results);
        $html = '<h3 class="wp-heading-inline" >Answered Duels Results</h3><br><table id="league-duels-answered" class="widefat fixed" cellspacing="0"><thead>';
        $html.= '<th>User Name</th><th>Home Team</th><th>Away Team</th><th>Schedule</th><th>Selected Option</th><th>Results</th></thead><tbody>';
        foreach ($result as $row){
            $user = get_user_by( 'ID', $row->user_id );
            $html.='<tr class="alternate"><td>'.$user->user_nicename.'</td><td class="column-columnname">'.get_post_meta( $row->post_id, 'home_team' )[0].'</td>';
            $html.='<td class="column-columnname">'.get_post_meta( $row->post_id, 'away_team' )[0].'</td>';
            $html.='<td class="column-columnname">'.get_post_meta( $row->post_id, 'match_date' )[0].' - '.getMetaValue('match_time', $row->post_id).'</td>';
            $html.='<td class="column-columnname">'.$row->selected_option.'</td>';
            $html.='<td class="column-columnname">'.getMetaValue("results", $row->post_id).'</td>';
            $html.='</tr>';

        }
        $html.='</tbody></table>';
        return $html;

    }

    function wpb_user_count() {
        $usercount = count_users();
        $result = $usercount['total_users'];
        return $result;
    }

    function league_duels_answered_count(){
        $result = $this->wpdb->get_results('SELECT * FROM '.$this->table_league_results);
        return sizeof($result);

    }
    function league_duels_count(){
        return wp_count_posts( 'my_league' )->publish;
    }

}
