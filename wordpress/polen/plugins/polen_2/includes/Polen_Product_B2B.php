<?php
namespace Polen\Includes;

use Polen\Admin\Polen_Admin_B2B_Product_Fields;

class Polen_Product_B2B
{

    /**
     * 
     */
    public static function get_all_product_ids( $qtd = 20 )
    {
        $args = array(
            'fields' => 'ids',
            'post_status' => array( 'publish' ),
            'numberposts' => $qtd,
            'orderby'     => 'menu_order',
            'post_type'   => 'product',
            'meta_query'  => array( 
                array(
                    'key' => Polen_Admin_B2B_Product_Fields::FIELD_NAME_IS_B2B,
                    'value'    => 'yes',
                    'compare'  => '=',
                )
            )
        );
        $products = get_posts( $args );
        return $products;
    }
}
