<?php
namespace Polen\Api;

use WP_REST_Server;

class Api_Routers{

    public function __construct()
    {
        add_action('rest_api_init', [ $this, 'init_routers' ]);
    }

    function init_routers()
    {
        $controller = new Api_Controller();
        /**
         * ROTA: Listar Talentos
         *
         * @param s Filtrar por string (opcional)
         * @param paged Exibir a página atual (opcional)
         * @param per_page Número de post por página (opcional)
         * @param campaign Filtrar pela a  campanha - ID (opcional)
         * @param campaign_category Filtrar por categoria - slug (opcional)
         */
        register_rest_route('/v3', '/talents', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$controller, 'talents'],
            'args' => [
                's',
                'per_page',
                'paged',
                'campaign',
                'campaign_category,',
            ],
            'validate_callback' => '__return_null',
        ));

        /**
         * ROTA: Descrição do talento
         */
        register_rest_route('/v3', '/talent', array(
            'methods' => 'GET',
            'args' => array(
                'slug',
                ),
            'callback' => [$controller, 'talent'],
            'validate_callback' => '__return_null',
        ));
    }
}
