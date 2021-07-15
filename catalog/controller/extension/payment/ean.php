<?php
class ControllerExtensionPaymentean extends Controller {
	public function index() {
		return $this->load->view('extension/payment/ean');
	}

	public function confirm() {
        $this->load->language('extension/payment/ean');
        
		$json = array();
		
        if (empty($this->session->data['ean'])) {
            $json['error'] = $this->language->get('error_ean');
            
            $json['redirect'] = $this->url->link('checkout/checkout');
        } else {
    		if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'ean') {
    			$this->load->model('checkout/order');

    			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_ean_order_status_id'));
    		
    			$json['redirect'] = $this->url->link('checkout/success');
    		}
        }
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
