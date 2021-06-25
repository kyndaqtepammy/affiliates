<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Referals_List extends WP_List_Table {
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }



    public function get_columns() {
        $columns = array(
            'id'        => 'Entry ID',
            'fullname'  => 'Full Name',
            'email'     => 'Email',
            //'clicks'    => 'Clicks'
            'site_from' => 'Link Clicked From',
            'ip_address'=> 'IP Address',
            'dated'     => 'Date'
        );

        return $columns;
    }
    



    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array();
    }



    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return array('fullname' => array('fullname', false));
    }



         /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        $data = array();
        global $wpdb;
        $tablename = $wpdb->prefix.'sdds_affiliates';
        $data = $wpdb->get_results("SELECT * FROM $tablename", ARRAY_A);
        return $data;
        //print_r($data) ;
    }



    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'fullname':
            case 'email':
            //case 'clicks':
            case 'site_from':
            case 'ip_address':
            case 'dated':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }



    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'fullname';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }

} //class