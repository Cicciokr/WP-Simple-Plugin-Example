<?php if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
/** * Create a new table class that will extend the WP_List_Table */
class Sportrace_Table extends WP_List_Table
{
    public function __construct()
    {

        parent::__construct(array(
            'singular' => 'singular_form',
            'plural' => 'plural_form',
            'ajax' => true
        ));
    }

    public function prepare_items()
    {
        // $this->_column_headers = $this->get_column_info();
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );
        /** Process bulk action */
        $this->process_bulk_action();
        $per_page = $this->get_items_per_page('records_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $data = self::get_records($per_page, $current_page);
        $this->set_pagination_args(
            [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page' => $per_page // WE have to determine how many items to show on a page
            ]
        );
        $this->items = $data;
    }

    public static function get_records($per_page = 10, $page_number = 1)
    {
        global $wpdb;
        $sql = "select * from " . $wpdb->prefix . "sportrace";
        if (isset($_REQUEST['s'])) {
            $sql .= ' where name_race LIKE "%' . $_REQUEST['s'] . '%"';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    function get_columns()
    {
        $columns = [
            'id' => '<input type="checkbox" />',
            'photo' => 'Photo',
            'name_race' => 'Name Race'
        ];
        return $columns;
    }

    public function get_hidden_columns()
    {
        // Setup Hidden columns and return them
        return array();
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name_race' => array('name_race', true)
        );
        return $sortable_columns;
    }

    function column_id($item)
    {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'photo':
                return "<img src='" . $item['photo'] . "' style='width:100px;'>";
                break;
            case 'name_race':
                return "<a href='?page=sportrace%2Fmembers_sportrace_page.php&id=" . $item['id'] . "'>" . $item[$column_name] . "</a>";
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    public function get_bulk_actions()
    {
        $actions = ['bulk-delete' => 'Delete'];
        return $actions;
    }
    public function process_bulk_action()
    {
        // Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (!wp_verify_nonce($nonce, 'bx_delete_records')) {
                die('Errore esecuzione delete');
            } else {
                self::delete_records(absint($_GET['record']));
                exit;
            }
        }
        // If the delete bulk action is triggered
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'bulk-delete' || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')) {
                $delete_ids = esc_sql($_POST['bulk-delete']);
                foreach ($delete_ids as $id) {
                    self::delete_records($id);
                }
                exit;
                exit;
            }
        }
    }
    public static function delete_records($id)
    {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . "sportrace", ['id' => $id], ['%d']);
    }

    public function no_items()
    {
        _e('No record found in the database.', 'bx');
    }

    public static function record_count()
    {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "sportrace";
        return $wpdb->get_var($sql);
    }
}