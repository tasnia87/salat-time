<?php
namespace  SalatTimes;
class API
{
    function __construct() {
        add_action('rest_api_init', function ()
        {
            register_rest_route('salat/v1', '/time', array(
                'methods' => 'GET',
                'callback' => array( 'SalatTimes\API', 'get_salat_times' )
            ));
            register_rest_route('salat/v1', '/time', array(
                'methods' => 'POST',
                'callback' => array( 'SalatTimes\API', 'save_salat_times' )
            ));
        });
    }


    function IsAuthorized()
    {
        //return true;

        $nonce = '';
        if (isset($_REQUEST['_wpnonce'])) {
            $nonce = $_REQUEST['_wpnonce'];
        } elseif (isset($_SERVER['HTTP_X_WP_NONCE'])) {
            $nonce = $_SERVER['HTTP_X_WP_NONCE'];
        }
        return wp_verify_nonce($nonce, 'wp_rest');

    }

    function get_salat_times()
    {
         if(self::IsAuthorized()) {
                $salat_times = get_posts(array('orderby' => 'menu_order', 'order' => 'ASC', 'post_type' => 'salat_times', 'posts_per_page' => 10));
                return rest_ensure_response($salat_times);
          }
         else{
             return rest_ensure_response('You are not authorized to call this service!');
         }
    }

    public static function save_salat_times()
    {
        //

        if (self::IsAuthorized()) {


            $postdata = file_get_contents('php://input');
            $eventData = json_decode($postdata, true);


            foreach ($eventData as $value) {
                $iqamah = $value['post_title'];
                $salat = $value['post_content'];
                self::update_time($iqamah, $salat);
            }

            return self::get_salat_times();
        }

    }

    function update_time($title, $time)
    {
        $salat_time = get_page_by_title($title, OBJECT, 'salat_times');
        $salat_time->post_content = $time;
        wp_update_post($salat_time);
    }

}
new API();
