<?php 
class Sportrace_Rest extends WP_REST_Controller
{
    /**
	 * Constructor.
	 */
    public function __construct()
    {
        $this->namespace = 'rest-sportrace/v1';
        $this->rest_base = 'sportrace';
    }
    /**
	 * Register the component routes.
	 */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check')
            )
        ));
    }

    public function get_items($request)
    {
        global $wpdb;
        $sql = "select * from " . $wpdb->prefix . "sportrace ORDER BY id";
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        for ($i = 0; $i < count($result); $i++) {
            $result[$i]["members"] = [];

            $sql_p = "select * from " . $wpdb->prefix . "sportrace_members where id_sportrace = '" . $result[$i]['id'] . "' ORDER BY position";
            $result_p = $wpdb->get_results($sql_p, 'ARRAY_A');
            $result[$i]["members"] = $result_p;
        }
        $data = rest_ensure_response($result);
        return $data;
    }

    public function get_items_permissions_check($request)
    {
        return true;
    }
}