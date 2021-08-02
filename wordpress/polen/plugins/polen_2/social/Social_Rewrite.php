<?php
namespace Polen\Social;

use Polen\Includes\Debug;

defined( 'ABSPATH' ) || die;

class Social_Rewrite
{
    const BASE_URL = 'social';

    const QUERY_VARS_SOCIAL_APP     = 'social_app';
    const QUERY_VARS_SOCIAL_IS_HOME = 'social_is_home';
    
    /**
     * 
     */
    public function __construct( $static = false )
    {
        if( $static ) {
            add_action( 'init',             array( $this, 'rewrites' ) );
            add_filter( 'query_vars',       array( $this, 'query_vars' ), 10, 1 );
            add_action( 'template_include', array( $this, 'template_include' ) );
        }
    }


    /**
     * 
     */
    public function rewrites()
    {
        add_rewrite_rule( self::BASE_URL . '/crianca-esperanca', 'index.php?'.self::QUERY_VARS_SOCIAL_APP.'=1&'.self::QUERY_VARS_SOCIAL_IS_HOME.'=1', 'top' );
    }


    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = self::QUERY_VARS_SOCIAL_APP;
        $query_vars[] = self::QUERY_VARS_SOCIAL_IS_HOME;
        return $query_vars;
    }


    /**
     * 
     */
    public function template_include( $template )
    {
        $social_app = get_query_var( self::QUERY_VARS_SOCIAL_APP );
        if( empty( $social_app ) || $social_app !== '1' ) {
            return $template;
        }

        $GLOBALS[ self::QUERY_VARS_SOCIAL_APP ]     = '1';
        $GLOBALS[ self::QUERY_VARS_SOCIAL_IS_HOME ] = '1';
        
        if( $GLOBALS[ self::QUERY_VARS_SOCIAL_IS_HOME ] == '1' ) {
            return get_template_directory() . '/social/index.php';
        }
    }
}