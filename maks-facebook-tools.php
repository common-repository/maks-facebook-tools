<?php
/*
Plugin Name:  MAKS Facebook Tools
Plugin URI:   https://maks.com.br/wordpress/facebook-tools/
Description:  Simple Embed Facebook Tools
Version:      0.0.5
Author:       MAKS.com.br
Author URI:   https://maks.com.br/
License:      GPLv3
License URI:  https://www.gnu.org/licenses/gpl.html
*/

namespace MAKS;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !class_exists('\MAKS\facebook_tools') )
{
    require_once( __DIR__ . '/core/like-button.php' );
    require_once( __DIR__ . '/core/comments-plugin.php' );

    class facebook_tools
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

        public static function init() : self
        {
            return self::get_instance();
        }

        /*
        * FACEBOOK TOOLS
        */
        const option_name = 'maks_facebook_tools';

        const menu = array(
            'page_title'    => 'MAKS Facebook Tools',
            'menu_title'    => 'Facebook Tools',
            'menu_subtitle' => 'Settings',
            'capability'    => 'manage_options',
            'menu_slug'     => self::option_name,
            'icon_url'      => 'dashicons-facebook',
            'position'      => null
        );

        private static $options = null;

        // array(
        //     'settings' => array(
        //         'enable_like_button' = false,
        //         'enable_comments_plugin' = false
        //     )
        // )

        private function __construct()
        {
            $settings = $this->get_options('settings');

            // add_action( 'admin_menu', array( $this, 'admin_menu' ) );

            $enable_like_button     = $settings['enable_like_button']     ?? true; // temporary true
            $enable_comments_plugin = $settings['enable_comments_plugin'] ?? true; // temporary true

            if( $enable_like_button )
            {
                \MAKS\facebook_tools\CORE\like_button::init( self::menu['menu_slug'] ); 
            }

            if( $enable_comments_plugin )
            {
                \MAKS\facebook_tools\CORE\comments_plugin::init( self::menu['menu_slug'] );
            }

            if( $enable_like_button || $enable_comments_plugin )
            {
                add_action( 'wp_head', array( $this, 'share_meta' ) );

                add_action( 'wp_footer', array( $this, 'fb_root' ) );
            }

            // TODO: create active and desactive register wp
            // add option manter as configuracoes quando desinstalar
            // add version options
        }

        public static function get_options( string $key = '' ) : array
        {
            if( self::$options === null )
            {
                self::$options = get_option( self::option_name, array() );
            }
            
            if( ! empty( $key ) )
            {
                return self::$options[$key] ?? array();

            } else {
                
                return self::$options;
            }
        }

        public static function set_options( string $key, mixed $new_options )
        {
        }

        public static function save_options()
        {
            update_option( self::option_name, self::$options );
        }

        /*
        * ADMIN METHODS
        */
        public function admin_menu()
        {
            add_menu_page(
                self::menu['page_title'],
                self::menu['menu_title'],
                self::menu['capability'],
                self::menu['menu_slug'],
                '',
                self::menu['icon_url'],
                self::menu['position']
            );

            add_submenu_page(
                self::menu['menu_slug'],
                self::menu['page_title'],
                self::menu['menu_subtitle'],
                self::menu['capability'],
                self::menu['menu_slug'],
                array( $this, 'page' )
            );
        }

        public function page()
        {
            // $this->check_POST();

            echo 'MAKS Facebook Tools/Settings<br>';

            // var_dump( $this->get_options() );
        }

        private function check_POST()
        {

        }

        /**
         * PUBLIC METHODS
         */
        public function share_meta()
        {
            global $post;

            $url = wp_get_shortlink();
            $title = get_the_title();
            $description = wp_trim_words( $post->post_content, 30 );
            $image_url = get_the_post_thumbnail_url( null, 'large' );

            echo "<meta property=\"og:url\"         content=\"{$url}\" />";
            echo "<meta property=\"og:type\"        content=\"website\" />";
            echo "<meta property=\"og:title\"       content=\"{$title}\" />";
            echo "<meta property=\"og:description\" content=\"{$description}\" />";
            echo "<meta property=\"og:image\"       content=\"{$image_url}\" />";
        }

        public function fb_root()
        {
            $lang = get_locale();

            echo
            '<div id="fb-root"></div>' .
            "<script>(function(d, s, id) {" .
            "  var js, fjs = d.getElementsByTagName(s)[0];" .
            "  if (d.getElementById(id)) return;" .
            "  js = d.createElement(s); js.id = id;" .
            "  js.src = 'https://connect.facebook.net/{$lang}/sdk.js#xfbml=1&version=v2.11';" .
            "  fjs.parentNode.insertBefore(js, fjs);" .
            "}(document, 'script', 'facebook-jssdk'));</script>" .
            '<style>div#comments div#respond form { display: none; }</style>';
        }
    }
}

// error_reporting( E_ALL );
// ini_set( 'display_errors', '1' );

\MAKS\facebook_tools::init();