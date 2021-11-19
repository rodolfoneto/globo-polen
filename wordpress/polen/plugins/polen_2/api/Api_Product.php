<?php

namespace Polen\Api;

use WC_Product_Query;
use WP_Query;

class Api_Product
{
    /**
     * Retornar talentos de acordo com a campanha
     *
     * @param array $params
     * @param string $campaingn
     * @return array
     */
    public function polen_get_products_by_campagins(array $params, string $campaingn = ''): array
    {
        $per_page = $params['per_page'] ?? get_option('posts_per_page');
        $paged = $params['paged'] ?? 1;
        $orderby = $params['orderby'] ?? 'popularity';
        $orderby = explode('-', $orderby);

        $order = $orderby[1] ?? 'DESC';

        $args = array(
            'limit' => $per_page,
            'page' => $paged,
        );

        if (!empty($campaingn)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'campaigns',
                'field' => 'slug',
                'terms' => $campaingn,
            );
        }

        if (isset($params['s'])) {
            $args['s'] = $params['s'];
        }

        $query = new WC_Product_Query($args);

        return wc_products_array_orderby($query->get_products(), $orderby[0], $order);
    }

    /**
     * Retornar quantidade de posts encontrados de acordo com os filtros passados
     *
     * @param array $args
     * @param string $campaingn
     * @return int
     */
    public function get_products_count(array $args, string $campaingn = ''): int
    {
        $query_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'meta_query' => array(),
        );

        if (!empty($campaingn)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'campaigns',
                'field' => 'slug',
                'terms' => $campaingn,
            );
        }

        if (!empty($args['product_cat'])) {
            $query_args['product_cat'] = $args['category'];
        }

        $query = new WP_Query( $query_args );

        return $query->found_posts;
    }
}