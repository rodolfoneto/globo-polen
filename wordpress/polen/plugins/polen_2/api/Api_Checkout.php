<?php

namespace Polen\Api;

use Automattic\WooCommerce\Client;
use DateTime;
use Exception;
use Polen\Includes\Debug;
use Polen\Includes\Emails\Polen_WC_Customer_New_Account;
use Polen\Includes\Polen_Campaign;
use Polen\Includes\Polen_Checkout_Create_User;
use Polen\Includes\Polen_Order;
use WC_Cart;
use WC_Coupon;
use WC_Customer;
use WC_Session_Handler;
use WP_REST_Request;

class Api_Checkout
{

    private $woocommerce;
    const ORDER_METAKEY = 'hotsite';
    const USER_METAKEY  = 'hotsite';

    public function __construct()
    {
        $this->auth();
    }

    public function auth()
    {
        global $Polen_Plugin_Settings;
        $this->woocommerce = new Client(
            site_url(),
            $Polen_Plugin_Settings['polen_api_rest_cosumer_key'],
            $Polen_Plugin_Settings['polen_api_rest_cosumer_secret'],
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );
    }

    /**
     * Criação de uma order completa, seguindo os passos:
     *
     * 1- Verificar se os campos obrigatorios foram passados
     * 2- Verificar se o CPF é valido
     * 3- Criar um novo usuario caso cliete esteja deslogado
     * 4- Verificar status do cupom
     * 5- Registrar order no woocommerce
     * 6- Adicionar meta dados de acordo com o sistema
     * 7- Fazer requisição para o TUNA
     * 8- Atualizar status de acordo com o response do TUNA
     *
     * @param WP_REST_Request $request
     * @return array|void
     */
    public function create_order( WP_REST_Request $request )
    {
        try {
            $nonce = $request->get_param( 'security' );
            if( !wp_verify_nonce( $nonce, $_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'] ) ) {
                throw new Exception( 'Falha na verificação de segurança', 401 );
            }
            $tuna = new Api_Gateway_Tuna();
            $fields = $request->get_params();
            $required_fields = $this->required_fields();
            $errors = array();

            foreach ($required_fields as $key => $field) {
                if (!isset($fields[$key]) && !empty($field)) {
                    $errors[] = "O campo {$field} é obrigatório";
                }
                $data[$key] = sanitize_text_field($fields[$key]);
            }

            if (!empty($errors)) {
                return api_response( $errors, 422 );
            }

            if (!$this->CPF_validate($fields['cpf'])) {
                throw new Exception( 'CPF Inválido', 422 );
            }

            $product = wc_get_product( $fields['product_id'] );
            if( empty( $product ) ) {
                throw new Exception( 'Produto inválido', 422 );
            }
            if (!$product->is_in_stock()) {
                throw new Exception( 'Produto sem estoque', 422 );
            }

            //TODO: Pegar o slug da campanha pelo produto add o metodo
            // na classe Polen_Campaign::
            $campaign = 'galo_idolos';
            $user = $this->create_new_user( $data, $campaign );
            
            WC()->cart->empty_cart();
            
            $add_product_cart = WC()->cart->add_to_cart( $product->get_id(), 1 );
            if( !$add_product_cart ) {
                throw new Exception( 'Esse produto não pode ser comprado', 422 );
            }

            $coupon = null;
            if (isset($fields['coupon'])) {
                $this->check_cupom($fields['coupon']);
                $coupon = sanitize_text_field($fields['coupon']);
            }

            $order_woo = $this->order_payment_woocommerce($user['user_object']->data, $fields['product_id'], $coupon);
            $this->add_meta_to_order($order_woo, $data);
            $payment = $tuna->process_payment($order_woo->get_id(), $user, $fields);

            // if ( $payment['order_status'] != 200 ) {
            //     throw new Exception($payment['message']);
            // }
            return api_response( $payment, 201 );

        } catch (\Exception $e) {
            return api_response( $e->getMessage(), $e->getCode() );
        }
    }

    /**
     * Criar usuario
     *
     * @param array $data
     * @return \WP_User
     */
    private function create_new_user( array $data, $campaign = '' )
    {
        $userdata = array(
            'user_login' => $data['email'],
            'user_email' => $data['email'],
            'user_pass' => wp_generate_password(6, false),
            'first_name' => $data['name'],
            'nickname' => $data['name'],
            'role' => 'customer',
        );

        $user['new_account'] = false;
        $user_wp = get_user_by( 'email', $userdata['user_email'] );
        if( false === $user_wp ) {

            $args = [];
            if( !empty( $campaign ) ) {
                $args[ 'campaign' ] = $campaign;
            }
            $args[ Polen_Checkout_Create_User::META_KEY_CREATED_BY ] = 'checkout';
            
            $api_user = new Api_User();
            $user_id = $api_user->create_user_custumer(
                $userdata['user_email'],
                $userdata['first_name'],
                $userdata['user_pass'],
                $args,
                true
            );
            $user['new_account'] = true;
            $user_wp = get_user_by( 'id', $user_id );
        }

        unset( $user_wp->user_pass );
        $user['user_object'] = $user_wp;

        $address = array(
            'billing_email' => $data['email'],
            'billing_cpf' => preg_replace('/[^0-9]/', '', $data['cpf']),
            'billing_country' => 'BR',
            'billing_phone' => preg_replace('/[^0-9]/', '', $data['phone']),
            'billing_cellphone' => preg_replace('/[^0-9]/', '', $data['phone']),
        );

        foreach ( $address as $key => $value ) {
            update_user_meta( $user['user_object']->ID, $key, $value );
        }
        return $user;
    }

    /**
     * Criar uma order no woocommerce
     *
     * @param WP_User $user
     * @param int $product_id
     * @param $coupon
     */
    public function order_payment_woocommerce($user, $product_id, $coupon = '')
    {
        $args = [
            // 'status'        => null,
            'customer_id'   => $user->ID,
            'customer_note' => 'created by api rest',
            'created_via'   => 'checkout_rest_api',
            'parent'        => null,
            'cart_hash'     => null,
        ];

        $order = wc_create_order($args);
        $product = wc_get_product($product_id);

        $address = array(
            'first_name' => $user->display_name,
            'last_name'  => '',
            'email'      => $user->user_email,
            'phone'      => get_user_meta($user->ID, 'billing_cellphone', true),
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => 'BR'
        );

        $order->add_product($product, 1);
        $order->set_address($address, 'billing');
        $order->apply_coupon($coupon);
        $order->calculate_totals();
        $order->save();

        return $order;
    }

    /**
     * Verificar se o cupom está válido para a criação da order
     *
     * @param $code_id
     */
    public function coupon_rules($code_id)
    {
        try {

            return api_response( $this->check_cupom( $code_id ), 200 );

        } catch (\Exception $e) {
            return api_response($e->getMessage(), 422);
        }

    }

    protected function check_cupom( $coupom_code )
    {
        $return = WC()->cart->apply_coupon( $coupom_code );
        if( !$return ) {
            // WC()->cart->empty_cart();
            throw new Exception( 'Cupom inválido' . __LINE__, 422 );
        }

        if( empty( WC()->cart->get_applied_coupons() ) ) {
            // WC()->cart->empty_cart();
            throw new Exception( 'Cupom inválido' . __LINE__, 422 );
        }
        return true;
    }

    /**
     * Retorna todos os campos do formulário que são obrigatórios
     */
    private function required_fields(): array
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'phone' => 'Celular/Telefone',
            'cpf' => 'CPF',
            'instruction' => 'Instrução',
            'video_to' =>  'Endereçamento do vídeo',
            'video_category' => 'Categoria do vídeo',
            'name_to_video' => 'Nome de quem receberá o vídeo',
            'allow_video_on_page' => 'Configuração de exibição',
            'product_id' => 'ID do Produto',
        ];
    }

    /**
     * Adicionar metas na order
     *
     * @param \WC_Order $order
     * @param array $data
     * @throws Exception
     */
    private function add_meta_to_order($order, array $data)
    {
        $email = $data['email'];
        $status = $data['allow_video_on_page'] ? 'on' : 'off';
        // $product = wc_get_product($data['product_id']);

        $order->update_meta_data('_polen_customer_email', $email);
        $order->add_meta_data( self::ORDER_METAKEY, 'polen_galo', true);

        $items = $order->get_items();
        $item = array_pop($items);
        $order_item_id = $item->get_id();
        $quantity = 1;

        wc_add_order_item_meta($order_item_id, '_line_subtotal', $order->get_subtotal(), true );
        wc_add_order_item_meta($order_item_id, '_line_total', $order->get_total(), true );

        wc_add_order_item_meta($order_item_id, '_qty'                 , $quantity, true);
        wc_add_order_item_meta($order_item_id, 'offered_by'           , $data['name'], true);
        wc_add_order_item_meta($order_item_id, 'video_to'             , $data['video_to'], true);
        wc_add_order_item_meta($order_item_id, 'name_to_video'        , $data['name_to_video'], true);
        wc_add_order_item_meta($order_item_id, 'email_to_video'       , $email, true);
        wc_add_order_item_meta($order_item_id, 'video_category'       , $data['video_category'], true);
        wc_add_order_item_meta($order_item_id, 'instructions_to_video', $data['instruction'], true);
        wc_add_order_item_meta($order_item_id, 'allow_video_on_page'  , $status, true);

        $interval  = Polen_Order::get_interval_order_basic();
        $timestamp = Polen_Order::get_deadline_timestamp($order, $interval);
        Polen_Order::save_deadline_timestamp_in_order($order, $timestamp);
        $order->add_meta_data(Polen_Order::META_KEY_DEADLINE, $timestamp, true);

        $order->save();
    }

    /**
     * Verifica se um CPF é válido
     *
     * @param string $cpf
     * @return bool
     */
    private function CPF_validate(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
