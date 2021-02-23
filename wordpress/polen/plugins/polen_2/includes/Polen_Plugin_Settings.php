<?php

namespace Polen\Includes;

class Polen_Plugin_Settings
{

    public function __construct($static=false)
    {
//        if( $static ) {
//            $this->init();
            add_action('redux/options/Polen_Plugin_Settings/saved', array($this, 'save'), 10, 2);
            add_action('redux/options/Polen_Plugin_Settings/settings/change', array($this, 'save'), 10, 2);
//        }
    }

    public static function init() {
        if ( ! class_exists( 'Redux' ) ) {
            return;
        }

        // This is your option name where all the Redux data is stored.
        $opt_name = "Polen_Plugin_Settings";

        $theme = wp_get_theme(); // For use with some settings. Not necessary.

        $args = array(
            // TYPICAL -> Change these values as you need/desire
            'opt_name'             => $opt_name,
            // This is where your data is stored in the database and also becomes your global variable name.
            'display_name'         => 'Configurações do Site',
            // Name that appears at the top of your panel
            'display_version'      => '1.0.0',
            // Version that appears at the top of your panel
            'menu_type'            => 'menu',
            //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
            'allow_sub_menu'       => true,
            // Show the sections below the admin menu item or not
            'menu_title'           => esc_html__( 'Config. do Site', 'polen' ),
            'page_title'           => esc_html__( 'Configurações do Site', 'polen' ),
            // You will need to generate a Google API key to use this feature.
            // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
            'google_api_key'       => '',
            // Set it you want google fonts to update weekly. A google_api_key value is required.
            'google_update_weekly' => true,
            // Must be defined to add google fonts to the typography module
            'async_typography'     => false,
            // Use a asynchronous font on the front end or font string
            'disable_google_fonts_link' => true,
            // Disable this in case you want to create your own google fonts loader
            'admin_bar'            => true,
            // Show the panel pages on the admin bar
            'admin_bar_icon'       => 'dashicons-admin-generic',
            // Choose an icon for the admin bar menu
            'admin_bar_priority'   => 55,
            // Choose an priority for the admin bar menu
            'global_variable'      => 'Polen_Plugin_Settings',
            // Set a different name for your global variable other than the opt_name
            'dev_mode'             => false,
            // Show the time the page took to load, etc
            'update_notice'        => true,
            // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
            'customizer'           => true,
            // Enable basic customizer support
            //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
            //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

            // OPTIONAL -> Give you extra features
            'page_priority'        => null,
            // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
            'page_parent'          => 'options.php',
            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
            'page_permissions'     => 'manage_options',
            // Permissions needed to access the options panel.
            'menu_icon'            => '',
            // Specify a custom URL to an icon
            'last_tab'             => '',
            // Force your panel to always open to a specific tab (by id)
            'page_icon'            => 'icon-themes',
            // Icon displayed in the admin panel next to your menu_title
            'page_slug'            => 'polen-site-settings',
            // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
            'save_defaults'        => true,
            // On load save the defaults to DB before user clicks save or not
            'default_show'         => false,
            // If true, shows the default value next to each field that is not the default value.
            'default_mark'         => '',
            // What to print by the field's title if the value shown is default. Suggested: *
            'show_import_export'   => true,
            // Shows the Import/Export panel when not used as a field.

            // CAREFUL -> These options are for advanced use only
            'transient_time'       => '3600',
            'output'               => true,
            // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
            'output_tag'           => true,
            // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
            'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
            // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
            'use_cdn'              => true,
            // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

            // HINTS
            'hints'                => array(
                'icon'          => 'el el-question-sign',
                'icon_position' => 'right',
                'icon_color'    => 'lightgray',
                'icon_size'     => 'normal',
                'tip_style'     => array(
                    'color'   => 'red',
                    'shadow'  => true,
                    'rounded' => false,
                    'style'   => '',
                ),
                'tip_position'  => array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                ),
                'tip_effect'    => array(
                    'show' => array(
                        'effect'   => 'slide',
                        'duration' => '500',
                        'event'    => 'mouseover',
                    ),
                    'hide' => array(
                        'effect'   => 'slide',
                        'duration' => '500',
                        'event'    => 'click mouseleave',
                    ),
                ),
            )
        );

        Redux::set_args( $opt_name, $args );

        // Section: Geral
        Redux::set_section( $opt_name, array(
            'title'            => esc_html__( 'Geral', 'polen' ),
            'id'               => 'general',
            'icon'             => 'el el-align-justify',
            'subsection'       => false,
            'fields'           => array(
                array(
                    'id'       => 'admin_bar',
                    'type'     => 'switch',
                    'title'    => esc_html__('Desativar a barra administrativa do Wordpress', 'polen'),
                    'desc'     => 'Desativa a barra administrativa do Wordpress (adminbar).',
                    'default'  => true,
                ),
            ),
        ) );
        
        add_action( 'redux/loaded', 'remove_demo' );
        if ( ! function_exists( 'remove_demo' ) ) {
            function remove_demo() {
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );
                    remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
                }
            }
        }
    }

    public function save( $args ) {
        // Ações a serem executadas após o salvamento das configurações.
    }

}

//function polen_Plugin_Settings_Init() {
//    $Polen_Plugin_Settings = new Polen_Plugin_Settings( true );
//}
//
//polen_Plugin_Settings_Init();