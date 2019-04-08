<?php


class Scatter_Payment_Gateway extends WC_Payment_Gateway {


    public function __construct(){

        //DEFINE DETAILS
        $this->id = "Scatter_Payment_Gateway";
        $this->icon = "";
        $this->has_fields = true;
        $this->method_title = "Scatter Payment Gateway";
        $this->method_description = "Scatter Payment Gateway";
        $this->title = 'Scatter Payment Gateway';

        //INIT METHODS
        $this->init_form_fields();
        $this->init_settings();

        //OPTIONS UPDATE HOOK
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

    }

    function init_form_fields(){

        $this->form_fields = array(
            //WC DEFAULTS
            'enabled' => array(
                'title' => __( 'Enable/Disable', 'woocommerce' ),
                'type' => 'checkbox',
                'label' => __( 'Enable Scatter Payment', 'woocommerce' ),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __( 'Title', 'woocommerce' ),
                'type' => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                'default' => __( 'Scatter Payment', 'woocommerce' ),
                'desc_tip'      => true,
            ),
            'description' => array(
                'title' => __( 'Customer Message', 'woocommerce' ),
                'type' => 'textarea',
                'default' => ''
            ),
            //WC CUSTOMS
            'host' => array(
                'title' => 'Host',
                'description' => 'Your host',
                'type' => 'text',
                'default' => '',
                'class' => '',
            ),
            'port' => array(
                'title' => 'port',
                'description' => 'Your port',
                'type' => 'text',
                'default' => '',
                'class' => '',
            ),
            'protocol' => array(
                'title' => 'Protocol',
                'description' => 'Your protocol',
                'type' => 'text',
                'default' => '',
                'class' => '',
            ),
            'chainId' => array(
                'title' => 'Chain Id',
                'description' => 'Your chainId',
                'type' => 'text',
                'default' => '',
                'class' => '',
            ),
            'account' => array(
                'title' => 'Payment Account',
                'description' => 'Your Payment Account',
                'type' => 'text',
                'default' => '',
                'class' => '',
            ),
            'blockchain' => array(
                'title' => 'Blockchain Type',
                'description' => 'Choose Blockchain Type',
                'type' => 'select',
                'default' => 'EOS',
                'class' => '',
                'options' => array(
                    'EOS' => 'EOS',
                    'Ethereum' => 'ETH',
                    'Tron' => 'Tron',
                )
            ),
            'tokens' => array(
                'title' => 'Acceptable Tokens',
                'description' => 'Choose Acceptable Tokens',
                'type' => 'multiselect',
                'default' => 'EOS',
                'class' => '',
                'options' => array(
                    'EOS' => 'EOS',
                    'ETH' => 'ETH',
                    'TRX' => 'TRX',
                    'CUSD' => 'CUSD',
                    'DAI' => 'DAI',
                    'USDC' => 'USDC'
                )
            )
        );
    }

    function admin_options() {
        ?>
        <h2><?php _e('Scatter Payment Gateway','woocommerce'); ?></h2>
        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table> <?php
    }

    function process_payment( $order_id ) {
        global $woocommerce;
        $order = new WC_Order( $order_id );

        // Mark as on-hold (we're awaiting the cheque)
        $order->update_status('on-hold', __( 'Awaiting Scatter payment', 'woocommerce' ));

        // Reduce stock levels
        $order->reduce_order_stock();

        // Remove cart
        $woocommerce->cart->empty_cart();

        // Return thankyou redirect
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url( $order )
        );
    }


}