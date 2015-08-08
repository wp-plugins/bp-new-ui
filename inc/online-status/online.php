<?php

/*******************************************
since 1.0
*******************************************/

$bp_ui = get_option( 'bpui_settings' );

if(!defined('bpui_PLUGIN_DIR'))
	define('bpui_PLUGIN_DIR', dirname(__FILE__));

add_action('wp', 'bpui_update_online_users_status');
function bpui_update_online_users_status() {
    if (is_user_logged_in()) {
        if (($logged_in_users = get_transient('users_online')) === false) $logged_in_users = array();
        $current_user = wp_get_current_user();
        $current_user = $current_user->ID;
        $current_time = current_time('timestamp');
        if (!isset($logged_in_users[$current_user]) || ($logged_in_users[$current_user] < ($current_time - (5 * 60)))) {
            $logged_in_users[$current_user] = $current_time;
            set_transient('users_online', $logged_in_users, 30 * 60);
        }
    }
}

function bpui_is_user_online($user_id) {
    $logged_in_users = get_transient('users_online');
    return isset($logged_in_users[$user_id]) && ($logged_in_users[$user_id] > (current_time('timestamp') - (15 * 60)));
}

add_action('bp_before_member_header', 'bpui_bp_user_online_status');
function bpui_bp_user_online_status(){
    global $bp_ui;
    echo '<ul class="bp_online_user">';
    $user_id = bp_displayed_user_id();
        echo '<li class="bp_online_user">';
        if (bpui_is_user_online($user_id)) {
            echo '<div class="bp_status_online bp_is_online">'.__('Online', 'bp-new-ui').'</div>';
        } else {
            echo '<div class="bp_status_online bp_is_not_online">'.__('Offline', 'bp-new-ui').'</div>';
        }
        echo '</li></ul>';
}

add_action('wp_logout', 'bpui_set_user_logged_out');
function bpui_set_user_logged_out() {
    global $current_user;
    get_currentuserinfo();
    $logged_in_users = get_transient('users_online');
    unset($logged_in_users[$current_user->ID]);
}

add_action('wp_login', 'bpui_set_user_logged_in');
function bpui_set_user_logged_in() {
    if (($logged_in_users = get_transient('users_online')) === false) $logged_in_users = array();
    $current_user = wp_get_current_user();
    $current_user = $current_user->ID;
    $current_time = current_time('timestamp');
    if (!isset($logged_in_users[$current_user]) || ($logged_in_users[$current_user] < ($current_time - (5 * 60)))) {
        $logged_in_users[$current_user] = $current_time;
        set_transient('users_online', $logged_in_users, 30 * 60);
    }
}