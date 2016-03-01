<?php

class dbDelta_db_schema {

    /**
     * Stuff we do when starting.
     */
    function __construct() {
        $this->index_test_001();
        $this->index_test_002();
        $this->index_test_003();
        $this->index_test_004();
        $this->index_test_005();
        $this->index_test_006();
    }

    /**
     * Initialize a singleton of this object.
     *
     * @return dbDelta_db_schema
     */
    public static function init() {
        static $instance = false;
        if (!$instance) {
            load_plugin_textdomain('db-delta-test', false, DBDELTA_TEST_REL_DIR . '/languages/');
            $instance = new dbDelta_db_schema();
        }
        return $instance;
    }

    /**
     * This is the approved WP 4.0 format.
     *
     * Double space after PRIMARY KEY
     *
     * Other indexes have KEY <keyname> (<key field>)
     *
     * Does NOT generate duplicate indexes.
     * Does NOT generate database errors (see test 004 below)
     */
    private function index_test_001() {
        $this->build_table( 'dbdelta_test_001',
            '
            id mediumint(8) unsigned NOT NULL auto_increment ,
            first varchar(255) NULL,
            PRIMARY KEY  (id),
            KEY first (first)
            '
        );
    }

    /**
     * Skips index name on secondary index.
     *
     * AS OF WP 4.4.2 this produces TWO indexes named "first"
     */
    private function index_test_002() {
        $this->build_table( 'dbdelta_test_002',
            '
            id mediumint(8) unsigned NOT NULL auto_increment ,
            first varchar(255) NULL,
            PRIMARY KEY  (id),
            INDEX (first)
            '
        );

    }

    /**
     * Skips index name on secondary index.
     *
     * AS OF WP 4.4.2 this produces TWO indexes named "first"
     */
    private function index_test_003() {
        $this->build_table( 'dbdelta_test_003' ,
            '
            id mediumint(8) unsigned NOT NULL auto_increment ,
            first varchar(255) NULL,
            PRIMARY KEY  (id),
            KEY (first)
            '
        );
    }


    /**
     * Uses INDEX instead of KEY on second index.
     *
     * AS OF WP 4.4.2 this generates and error in dbDelta
     * DOES NOT create a duplicate index
     * WordPress database error Duplicate key name 'first' for query ALTER TABLE wp_dbdelta_test_004 ADD INDEX first (first)
     */
    private function index_test_004() {
        $this->build_table( 'dbdelta_test_004',
            '
            id mediumint(8) unsigned NOT NULL auto_increment ,
            first varchar(255) NULL,
            PRIMARY KEY  (id),
            INDEX first (first)
            '
        );
    }


    /**
     * Private Test
     *
     */
    private function index_test_005() {
        $this->build_table( 'dbdelta_test_005',
            '
                    slp_repq_id         bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_time       timestamp NOT NULL default current_timestamp,
                    slp_repq_query      varchar(255) NOT NULL,
                    slp_repq_tags       varchar(255),
                    slp_repq_address    varchar(255),
                    slp_repq_radius     varchar(5),
                    meta_value          longtext,
                    PRIMARY KEY  (slp_repq_id),
                    KEY slp_repq_time (slp_repq_time)
            '
        );
    }


    /**
     * Private Test
     *
     */
    private function index_test_006() {
        $this->build_table( 'dbdelta_test_006',
            '
                    slp_repqr_id    bigint(20) unsigned NOT NULL auto_increment,
                    slp_repq_id     bigint(20) unsigned NOT NULL,
                    sl_id           mediumint(8) unsigned NOT NULL,
                    PRIMARY KEY  (slp_repqr_id),
                    KEY slp_repq_id (slp_repq_id)
            '
        );
    }

    /**
     * Build the table and indexes.
     *
     * @param string $table
     * @param string $fields
     */
    private function build_table( $table , $fields ) {
        global $wpdb;

        $charcoal = '';
        if ( ! empty( $wpdb->charset ) )
            $charcoal = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty( $wpdb->collate ) )
            $charcoal .= " COLLATE $wpdb->collate";

        $table_name = $wpdb->prefix . $table;
        $sql = "CREATE TABLE $table_name ( $fields ) $charcoal";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
        dbDelta( $sql );
    }
}