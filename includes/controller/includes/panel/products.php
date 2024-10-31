<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class PRDCT_PLLR_List_Table extends WP_List_Table {

    var $args;
    /** Class constructor */
    public function __construct($args) {

        $this->args = $args;
        parent::__construct( [
            'singular' => __( $args['singular'], 'sp' ), //singular name of the listed records
            'plural'   => __( $args['plural'], 'sp' ), //plural name of the listed records
            'ajax'     => false //should this table support ajax?

        ] );

    }

    public function get_cache($per_page = 50, $page_number = 1 , $search_term=""){
        global $wpdb;

        $arg = $this->args;
        $types = explode(',',$arg['type']);
        $arr = array();
        foreach ($types as $t){
            $arr[] = 'type="'.trim($t).'"';
        }
        $typo = '('.implode(' OR ',$arr).')';
        if($search_term == "") {
            $sql = 'SELECT * FROM '.$wpdb->prefix.'product_puller WHERE '.$typo.' ORDER by id DESC';

//            if (!empty($_REQUEST['orderby'])) {
//                $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
//                $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
//            }


            $sql .= " LIMIT $per_page";

            $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


            $result = $wpdb->get_results($sql);
            $data = array();
            foreach ($result as $k => $r) {
                $data[$k]['id'] = $r->id;
                $data[$k]['image'] = $r->image!="" ? '<img src='.$r->image.' style="width:50px">' : "";
                $data[$k]['type'] = $r->type;
                $data[$k]['price'] = $r->price;
                $data[$k]['product_id'] = $r->product_id;
                $data[$k]['title'] = '<a href="'.$arg['link'].$r->id.'">'.$this->text_cleaner($r->title).'</a>';
            }
        }else{
            $sql = 'SELECT * FROM '.$wpdb->prefix.'product_puller WHERE (product_id="'.esc_sql($search_term).'" OR title LIKE "%'.esc_sql($search_term).'%") AND '.$typo.' ORDER by id DESC';
            $result = $wpdb->get_results($sql);
            $data = array();
            foreach ($result as $k => $r) {
                $data[$k]['id'] = $r->id;
                $data[$k]['image'] = $r->image!="" ? '<img src='.$r->image.' style="width:50px">' : "";
                $data[$k]['type'] = $r->type;
                $data[$k]['price'] = $r->price;
                $data[$k]['product_id'] = $r->product_id;
                $data[$k]['title'] = '<a href="'.$arg['link'].$r->id.'">'.$this->text_cleaner($r->title).'</a>';
            }
        }

        return $data;
    }

    public function get_columns(){
        $arg = $this->args;
        $columns = array(
            'cb'      => '<input type="checkbox" />',
            'id'      => __('<b>ID</b>','sp'),
            'image' => __('<b>Image</b>','sp'),
            'title' => __('<b>'.$arg['singular'].'</b>','sp'),
            'type' => __('<b>Type</b>','sp'),
            'price' => __('<b>Price</b>','sp'),
            'product_id'    => __('<b>Product ID</b>','sp')
        );
        return $columns;
    }

    public function prepare_items() {
        global $wpdb;
        $args = $this->args;
        $search_terms = isset($_POST['s']) ? sanitize_text_field(trim($_POST['s'])) : "";
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $current_page = $this->get_pagenum();
        $sql = "SELECT COUNT(id) FROM {$wpdb->prefix}product_puller WHERE type='".$args['type']."'";
        $total_items = $wpdb->get_var($sql);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => 50
        ));
        $this->items = $this->get_cache(50,$current_page, $search_terms);
    }
    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
            case 'image':
            case 'title':
            case 'type':
            case 'price':
            case 'product_id':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }



    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    public function get_bulk_actions() {
        $actions = array(
            'bulk-delete' => 'Delete'
        );

        return $actions;
    }

    public function column_name( $item ) {

        // create a nonce
        $delete_nonce = wp_create_nonce( 'sp_delete_customer' );

        $title = '<strong>' . $item['title'] . '</strong>';

        $actions = array(
            'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
        );

        return $title . $this->row_actions( $actions );
    }

    public function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_customer( absint( $_GET['customer'] ) );

                wp_redirect( esc_url( add_query_arg() ) );
                exit;
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {

            $delete_ids = esc_sql( $_POST['bulk-delete'] );

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_customer( $id );

            }

            wp_redirect( esc_url( add_query_arg() ) );
            exit;
        }
    }

    public function delete_customer( $id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}product_puller",
            array( 'id' => $id ),
            array( '%d' )
        );

    }
    function text_cleaner($text){
        $text = str_replace("\\'","'", $text);
        $text = str_replace("\'","'",$text);
        $text = str_replace("\&#039;","'",$text);
        $text = str_replace('\\"','"',$text);
        $text = str_replace('\"','"',$text);
        return $text;
    }

}
