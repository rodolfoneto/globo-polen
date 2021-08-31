<?php

namespace Polen\Master_class;

class Master_Class_Controller{

    public function send_form_request()
    {
        try{
            $email = sanitize_text_field($_POST['email']);

            if( !filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL ) ) {
                wp_send_json_error(array('Error' => 'Email incorreto', 422));
            }

            $url = 'https://hooks.zapier.com/hooks/catch/10583855/b483m4i/';
            $response = wp_remote_post($url, array(
                    'method' => 'POST',
                    'timeout' => 45,
                    'headers' => array(),
                    'body' => array(
                        'email' => $email,
                    ),
                )
            );

            if (is_wp_error($response)) {
                wp_send_json_error(array('error' => 'Sistema indisponível. Por favor entre em contato com o suporte'), 503);
                wp_die();
            }

            wp_send_json_success( 'ok', 200 );

        } catch (\Exception $e) {
            wp_send_json_error(array('Error' => $e->getMessage()), 422);
            wp_die();
        }
    }

}