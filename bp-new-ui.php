<?php
/*
Plugin Name: BuddyPress New UI
Description: A great plugin completely changes the entire design BuddyPress in light or dark color
Version: 1.0
Author: Daniel 4000
Author URI: http://dk4000.com
Contributors: daniluk4000
Text Domain: bp-new-ui
Domain Path: /languages
*/
//----------------------------------------
// Constructor
include "inc/online-status/online.php";
class BP_NEW_UI {
function __construct() {
add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
} 
public function register_plugin_styles() {
$val = get_option('bp_new_ui_option');
$val = $val['1'];
if ( $val == '1'){
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/dark.css';
wp_enqueue_style( 'bp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/dark.css', filemtime( $css_path ) );
} 
else {
$css_path = plugin_dir_path( __FILE__ ) . '/inc/css/light.css';
wp_enqueue_style( 'bp_new_ui', plugin_dir_url( __FILE__ ) . '/inc/css/light.css', filemtime( $css_path ) );
}
}
} // end class

if ( $val == '1'){
// Load
//----------------------------------------
function bp_new_ui_load_plugin_textdomain() {
    load_plugin_textdomain( 'bp-new-ui', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'bp_new_ui_load_plugin_textdomain' );

//----------------------------------------
// Create plugin settings page
add_action('admin_menu', 'bpui_add_plugin_page');

function bpui_add_plugin_page(){
add_options_page( ''.__('Settings').' bpress New UI', ' bpress New UI', 'manage_options', 'bp_new_ui', 'bp_new_ui_options_page_output' );
}

function bp_new_ui_options_page_output(){
?>
<div class="wrap bpress-new-ui-wrap" id="bpui">
<div class="imgclass"></div>
<div class="noimgclass">
<style>
#bpui .updated {
    display: none;
}
#bpui img {
    width: 50%;
    border-radius: 3px;
    float: right;
    position: absolute;
    right: 10px;
}
.noimgclass {
    float: left;
    width: 50%;
}
status {
    float: left;
    width: 100%;
    white-space: nowrap;
}
status {
    background: #BC3530;
    width: auto;
    padding: 0 5px;
    border: 1px solid #BC3530 !important;
    color: #fff;
    margin-top: 2px;
}
.desc code {
    background: rgba(139, 0, 0, 0.5);
    color: #fff;
}
.noimgclass .submit input {
    box-shadow: 0px 0px;
    border-radius: 0px;
    border: 0px none;
    background: #BC3530;
}
.desc {
    margin-top: 5px;
    background: #BC3530;
    padding: 5px;
    color: #fff;
}
.light {
    background: #ddd;
    color: #000;
}
eng {
    background: #ddd;
    font-size: 20px;
    width: 100%;
    float: left;
    padding: 5px;
    text-align: center;
    box-sizing: border-box;
}
</style>
<h2><?php echo get_admin_page_title() ?></h2>
<form action="options.php" method="POST">
<?php settings_fields( 'bp_new_ui_group' ); ?>
<?php do_settings_sections( 'bp_new_ui_page' ); ?>
<?php submit_button(); ?>
</form>
</div>
<?php
}

// Register Settings
//----------------------------------------
function bpui_plugin_settings(){ 
$pluginname = __( 'BuddyPress New UI' );
$settingsname = __( 'Settings' );
$changestylename = __( ''.__('Change').' '.__('Style').'', 'bp-new-ui' );

register_setting( 'bp_new_ui_group', 'bp_new_ui_option' );
add_settings_section( 'bp_new_ui_id', '', '', 'bp_new_ui_page' ); 
add_settings_field('bp_new_ui_field', $changestylename, 'fill_bp_new_ui_field', 'bp_new_ui_page', 'bp_new_ui_id' );
}
add_action('admin_init', 'bpui_plugin_settings');

function fill_bp_new_ui_field(){
$val = get_option('bp_new_ui_option');
$locale = get_locale();
$val = $val['1'];
$posts = get_posts();
?>
<label>
<input type="checkbox" name="bp_new_ui_option[1]" value="1" <?php checked( 1, $val ) ?>  /> <?php _e( 'Change style to Dark color', 'bp-new-ui'); ?></label> <br>
<?php
if ( $val == '1') {
echo'<status class="dark">';_e( 'Now active Dark Theme', 'bp-new-ui' );echo'</status>';
} 
else {
echo'<status class="light">';_e( 'Now active Light Theme', 'bp-new-ui' );echo'</status>';
}
}
}
// instantiate our plugin's class
$GLOBALS['bp_new_ui'] = new bp_NEW_UI();
?>