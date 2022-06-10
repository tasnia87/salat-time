<?php

function samadhan_authorized(){
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
    if(samadhan_authorized()) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'salat_time_table';
        $retrieve_data = $wpdb->get_results("SELECT * FROM $table_name");
        return rest_ensure_response($retrieve_data);
    }
}
function samadhan_save_salat_times()
{
    if(samadhan_authorized()) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'salat_time_table';
        $postdata = file_get_contents('php://input');

        $eventData = json_decode($postdata, true);
        foreach ($eventData as $value) {
            $iqamah = $value['Iqamah'];
            $salat = $value['Salat'];
            $data_update = array('Iqamah' => $iqamah);
            $data_where = array('Salat' => $salat);
            $wpdb->update($table_name, $data_update, $data_where);

        }

        return samadhan_get_salat_times();
    }

}
add_action('rest_api_init', function () {

    register_rest_route('salat/v1', '/time', array(
        'methods' => 'GET',  /* END point B */
        'callback' => 'samadhan_get_salat_times',
    ));
    register_rest_route('salat/v1', '/time', array(
        'methods' => 'POST',
        'callback' => 'samadhan_save_salat_times', /* END point D */
    ));
});