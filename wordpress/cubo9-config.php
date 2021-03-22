<?php
if( ! defined( 'ABSPATH' ) ) {
        headear( 'location: /' );
}

/**
 * Cubo9: wp-content is now polen
 */
define( 'WP_CONTENT_DIR', ABSPATH . 'polen' );
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
define( 'PLUGINDIR', WP_PLUGIN_DIR );
if ( defined( 'WP_CLI' ) ) { $_SERVER['HTTP_HOST'] = 'polen.globo'; }
define( 'WP_CONTENT_URL', '//'. $_SERVER['HTTP_HOST'] . '/polen' );
define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );

/**
 * JWT
 */
define( 'JWT_AUTH_SECRET_KEY', '90424b7a87b2b4243a0312df61d3e5722b4e87c' );
define( 'JWT_AUTH_CORS_ENABLE', true );

/**
 * Ambiente de desenvolvimento?
 * 
 * ATENÇÃO: Deixe como falso no ambiente de produção.
 */
define( 'DEV_ENV', true );

/**
 * Caso o WP_DEBUG esteja ativo
 */
if( 'WP_DEBUG' ) {
        // Enable Debug logging to the /wp-content/debug.log file
        define( 'WP_DEBUG_LOG', true );
        // Disable display of errors and warnings
        define( 'WP_DEBUG_DISPLAY', false );
        @ini_set( 'display_errors', 0 );
        // Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
        define( 'SCRIPT_DEBUG', false );
}
