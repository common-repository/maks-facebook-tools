<?php

/*
Author:        Maksuel Boni
Author URI:    https://maks.com.br
Creation Date: 26/12/2017
Description:   Insert Like and share Button

Version:       0.0.1
Last Modified: 26/12/2017
*/

namespace MAKS\facebook_tools\CORE;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( ! class_exists('\MAKS\facebook_tools\CORE\like_button') )
{
    require_once( __DIR__ . '/common-services.php' );

    class like_button extends common_services
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
         * LIKE BUTTON
         */
        private static $menu = array(
            'page_title' => 'Like Button',
            'menu_title' => 'Like Button',
            'capability' => 'manage_options',
            'menu_slug'  => 'like_button',
        );

        private $defaults = array(

        );

        private function __construct()
        {
            // add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            add_filter( 'the_content', array( $this, 'after_content' ) );

            // TODO:
            // options to config like and share button
            // add button for all post or all pages or both
            // add after or before text (post and page separed)
            // or add only with shortcode
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
            echo 'MAKS FACEBOOK TOOLS/LIKE BUTTON v0.0.1';
        }

        public function after_content( $content )
        {
            $url = wp_get_shortlink();

            $content .= "<p><div class=\"fb-like\" data-href=\"{$url}\" data-layout=\"standard\" data-action=\"like\" data-size=\"large\" data-show-faces=\"false\" data-share=\"true\"></div></p>";

            return $content;
        }
    }
}
