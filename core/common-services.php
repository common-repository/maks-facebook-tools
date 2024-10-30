<?php

/*
Author:        Maksuel Boni
Author URI:    https://maks.com.br
Creation Date: 26/12/2017
Description:   Common services

Version:       0.0.1
Last Modified: 26/12/2017
*/

namespace MAKS\facebook_tools\CORE;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( ! class_exists('\MAKS\facebook_tools\CORE\common_services') )
{
    class common_services
    {
        protected static function sub_menu_slug( string $menu_name, string $sub_menu_name )
        {
            return $menu_name . '_' . $sub_menu_name;
        }
    }
}