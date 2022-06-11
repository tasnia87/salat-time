<?php
/**
 * Plugin Name: Samadhan Salat Wall
 * Description: A plug to create a Satal Wall
 * Version : 1.0.2
 *
 */

include_once ('includes/salat-time-post.php');
include_once ('includes/functions.php');
include_once ('includes/api-endpoints.php');
include_once('includes/view.php');
include_once('includes/view-full-screen.php');



/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/



// TEST:
// samadhan_prepare_salat_time_table();

function func_load_vue_scripts_axios_rest_call() {
    wp_register_script( 'wp_vue_js_rest_call', plugins_url('client/vendor/vue.js',__FILE__ ));
    wp_register_script( 'wp_axios_rest_call', plugins_url('client/vendor/axios.min.js',__FILE__ ));

    wp_register_script('samadhan_salat_app', plugin_dir_url( __FILE__ ).'client/js/main.js', ['jquery','wp_vue_js_rest_call','wp_axios_rest_call'], true );
    wp_register_style('samadhan_salat_css', plugins_url('client/css/main.css',__FILE__ ));
}
add_action('wp_enqueue_scripts', 'func_load_vue_scripts_axios_rest_call');
function func_wp_vue_axios_rest_call(){

    wp_enqueue_script('wp_vue_js_rest_call');
    wp_enqueue_script('wp_axios_rest_call');
    wp_enqueue_style('samadhan_salat_css');

    wp_localize_script('samadhan_salat_app','settingObject',array(
        'root'=>esc_url_raw(rest_url()),
        'nonce'=>wp_create_nonce('wp_rest')
    ));
    wp_enqueue_script('samadhan_salat_app');


    //Return
} // end function
function func_salat_time(){
    func_create_salat_time();
    func_wp_vue_axios_rest_call();
    return  salat_time_view();
    }

function func_salat_time_full(){
    func_wp_vue_axios_rest_call();
    return  salat_time_view_full_screen();
}
add_shortcode('salat_time', 'func_salat_time');
add_shortcode('salat_time_full_screen', 'func_salat_time_full');

function func_create_salat_time(){
    func_update_time('Shuruq', '5:30 am', 1);
    func_update_time('Dhuhr',  '1:25 pm',2);
    func_update_time('Asr',  '5:40 pm',3);
    func_update_time('Magrib',  '7:10 pm',4);
    func_update_time('Eisha',  '9:30 pm',5);
}


