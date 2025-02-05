<?php

namespace CRPlugins\MPGatewayCheckout\Gateway;

use CRPlugins\MPGatewayCheckout\Api\MPApi;
use CRPlugins\MPGatewayCheckout\Helper\Helper;

/**
 * Class in charge of processing MercadoPago's notifications
 */
class IPNProcessor
{
    public const PAYMENTS_META_KEY = 'wc_gateway_payments';

    /**
     * Default constructor, also creates a webhook endpoint
     */
    public function __construct()
    {
        add_action('woocommerce_api_mp-gateway-ipn', [$this, 'check_ipn']);
    }

    /**
     * Receives the webhook and check if it's valid to proceed
     *
     * @return void
     */
    public function check_ipn()
    {
        $input = file_get_contents('php://input');
        $input = json_decode($input, true);
        if (empty($input) || !$this->validate_input($input)) {
            wp_die('WooCommerce MercadoPago Gateway invalid IPN request', 'MP Gateway IPN', ['response' => 500]);
        }
    }

    /**
     * Validates the incoming webhook
     *
     * @param array $data
     * @return bool
     */
    private function validate_input(array $data)
    {
        $data = wp_unslash($data);
        if (empty($data['type']) || $data['type'] !== 'payment' || empty($data['data']['id'])) {
            return false;
        }
        $payment_id = wc_sanitize_order_id($data['data']['id']);

        $mp_api = new MPApi(Helper::get_option('access_token', ''));
        $payment = $mp_api->get('/payments/' . $payment_id);

        $this->handle_payment($payment);
        return true;
    }

    /**
     * Checks the payment id in MP
     *
     * @param array $payment
     * @return void
     */
    private function handle_payment(array $payment)
    {
        $reference = (empty($payment['external_reference']) ? null : filter_var($payment['external_reference'], FILTER_SANITIZE_STRING));
        if (empty($reference)) {
            return;
        }
        
        $prefix = Helper::get_option('external_reference', 'WC-');
        if (substr($reference, 0, 3) !== $prefix) {
            return;
        }
        
        $order_id = (int) str_replace($prefix, '', $reference);
        $order = wc_get_order($order_id);
        if (empty($order)) {
            Helper::log_error('IPN notified for: ' . $reference . '. But such order_id: ' . $order_id . ' doesn\'t exist');
            return;
        }

        $previous_payments = $order->get_meta(self::PAYMENTS_META_KEY);
        if(!empty($previous_payments)){
            $previous_payments = explode(',', $previous_payments);
            if(in_array($payment['id'], $previous_payments)){
                // Payment already processed;
                return;
            }
        }

        $mp_data = [
            'status' => $payment['status'],
            'status_detail' => $payment['status_detail'],
            'payment_id' => $payment['id']
        ];
        $this->handle_order_status($order, $mp_data);
    }

    /**
     * Handles the order status from the IPN
     *
     * @param \WC_Order $order
     * @param array $mp_data
     * @return void
     */
    private function handle_order_status(\WC_Order $order, array $mp_data)
    {
        $previous_payments = $order->get_meta(self::PAYMENTS_META_KEY);
        if(!empty($previous_payments)){
            $previous_payments = explode(',', $previous_payments);
        }else{
            $previous_payments = [];
        }
        $previous_payments[] = $mp_data['payment_id'];

        if ($mp_data['status'] === 'approved') {
            $status = Helper::get_option('status_payment_approved', 'wc-processing');
            $order->update_status(
                $status,
                sprintf(__('Mercadopago Gateway - Payment: %s was approved.', 'wc-mp-gateway-checkout'), $mp_data['payment_id'])
            );
            $order->update_meta_data(self::PAYMENTS_META_KEY, implode(',', $previous_payments));
            Helper::log_info('IPN - Notification checked, order: ' . $order->get_id() . ' checked and updated to approved (' . $status . ')');
        } elseif ($mp_data['status'] === 'in_process') {
            $status = Helper::get_option('status_payment_in_process', 'wc-pending');
            $order->update_status(
                $status,
                sprintf(__('Mercadopago Gateway - Payment: %s is pending.', 'wc-mp-gateway-checkout'), $mp_data['payment_id'])
            );
            $order->update_meta_data(self::PAYMENTS_META_KEY, implode(',', $previous_payments));
            Helper::log_info('IPN - Notification checked, order: ' . $order->get_id() . ' checked and updated to in process (' . $status . ')');
        } elseif ($mp_data['status'] === 'rejected') {
            $status = Helper::get_option('status_payment_rejected', 'wc-failed');
            $reason = WC_MP_Gateway::handle_rejected_payment($mp_data['status_detail']);
            $order->update_status(
                $status,
                sprintf(
                    __('Mercadopago Gateway - Payment: %s was rejected. Reason: %s.', 'wc-mp-gateway-checkout'),
                    $mp_data['payment_id'],
                    $reason
                )
            );
            $order->update_meta_data(self::PAYMENTS_META_KEY, implode(',', $previous_payments));
            Helper::log_info('IPN - Notification checked, order: ' . $order->get_id() . ' checked and updated to rejected (' . $status . '). Reason: ' . $reason);
        } else {
            Helper::log_error('Tried to updated order from IPN with unknown status: ' . $mp_data['status']);
        }
        $order->save();
        return;
    }
}
