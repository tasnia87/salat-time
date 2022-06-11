<?php

function samadhan_authorized(){
    //return true;

    $nonce ='';
    if ( isset( $_REQUEST['_wpnonce'] ) ) {
        $nonce = $_REQUEST['_wpnonce'];
    } elseif ( isset( $_SERVER['HTTP_X_WP_NONCE'] ) ) {
        $nonce = $_SERVER['HTTP_X_WP_NONCE'];
    }
    return wp_verify_nonce( $nonce, 'wp_rest' );

}

function samadhan_get_salat_times()
{
   // if(samadhan_authorized()) {
        $salat_times = get_posts(array('orderby' => 'menu_order', 'order' => 'ASC','post_type'=>'salat_times','posts_per_page' => 10));
        return rest_ensure_response($salat_times);
  //  }
}
function samadhan_save_salat_times()
{
    //

    if(samadhan_authorized()) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'salat_time_table';
        $postdata = file_get_contents('php://input');

        $eventData = json_decode($postdata, true);


        foreach ($eventData as $value) {
            $iqamah = $value['post_title'];
            $salat = $value['post_content'];
             samadhan_update_time($iqamah, $salat);
        }

        return samadhan_get_salat_times();
    }

}

function samadhan_update_time($title, $time){
    $salat_time = get_page_by_title( $title,OBJECT ,'salat_times');
    $salat_time->post_content = $time;
    wp_update_post($salat_time);
}

add_action('rest_api_init', function () {

    register_rest_route('salat/v1', '/time', array(
        'methods' => 'GET',
        'callback' => 'samadhan_get_salat_times',
    ));
    register_rest_route('salat/v1', '/time', array(
        'methods' => 'POST',
        'callback' => 'samadhan_save_salat_times',
    ));
});
