<?php

/*
Author:        Maksuel Boni
Author URI:    https://maks.com.br
Creation Date: 26/12/2017
Description:   Replace default comments

Version:       0.0.1
Last Modified: 26/12/2017
*/

namespace MAKS\facebook_tools\CORE;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( ! class_exists('\MAKS\facebook_tools\CORE\comments_plugin') )
{
    require_once( __DIR__ . '/common-services.php' );

    class comments_plugin extends common_services
    {
        /**
         * SINGLETON
         */
        private static $instance = null;

        public static function get_instance() : self
        {
            if( self::$instance === null )
            {
                self::$instance = new self();
            }

            return self::$instance;            
        }

        public static function init( string $menu_parent_slug ) : self
        {
            self::$menu['parent_slug'] = $menu_parent_slug;
            self::$menu['menu_slug'] = self::sub_menu_slug( $menu_parent_slug, self::$menu['menu_slug'] );

            return self::get_instance();
        }

        /**
         * COMMENTS PLUGIN
         */
        private static $menu = array(
            'page_title' => 'Comments Plugin',
            'menu_title' => 'Comments Plugin',
            'capability' => 'manage_options',
            'menu_slug'  => 'comments_plugin',
        );

        private $defaults = array(
            'colorscheme' => 'light',  // or 'dark' (data-colorscheme)
            'num_posts'   => 10,       // minimum value is 1 (data-numposts)
            'order_by'    => 'social', // or 'reverse_time' or 'time' (data-order-by)
            'width'       => '100%'    // minimum value is 320px (data-width)
        );
        
        private function __construct()
        {
            // add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            add_filter( 'pings_open', array( $this, 'set_pings_to_close' ), 20, 2 );

            add_filter( 'comments_array', array( $this, 'hide_existing_comments' ), 10, 2 );

            add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

            // TODO:
            // button to disable comments
        }

        /**
         * PUBLIC METHODS
         */
        public function admin_menu()
        {
            add_submenu_page(
                self::$menu['parent_slug'],
                self::$menu['page_title'],
                self::$menu['menu_title'],
                self::$menu['capability'],
                self::$menu['menu_slug'],
                array( $this, 'page' )
            );  
        }

        public function page()
        {
            echo 'MAKS FACEBOOK TOOLS/COMMENTS PLUGIN v0.0.1';
        }

        public function set_pings_to_close()
        {
            return false;
        }

        public function hide_existing_comments( $comments )
        {
            return array();
        }

        public function wp_enqueue_scripts()
        {
            if( comments_open() )
            {
                wp_enqueue_script(
                    'maks-facebook-tools-comments-plugin',
                    plugins_url( '/comments-plugin.js', __FILE__ ),
                    array('jquery'),
                    '0.0.3',
                    true
                );
            }
        }
    }
}