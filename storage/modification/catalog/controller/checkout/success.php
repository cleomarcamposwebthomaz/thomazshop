<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {

              // one_checkout.ocmod.xml | Cleomar Campos
              // Cadastra o cliente automaticamente caso ele esteja realizando a compra como visitante
              if ($this->config->get('module_onecheckout_register')) {
                if (!$this->customer->getId() && !empty($this->session->data['guest']) && isset($this->session->data['order_id'])) {
                  $this->load->model('account/customer');
                  $this->load->model('account/address');

                  if (isset($this->session->data['guest']['custom_field']['account'])) {
                    unset($this->session->data['guest']['custom_field']['account']);
                  }

                  if (isset($this->session->data['payment_address']['custom_field']['address'])) {
                    unset($this->session->data['payment_address']['custom_field']['address']);
                  }

                  $this->session->data['guest']['custom_field']['account'] = $this->session->data['guest']['custom_field'];
                  $this->session->data['payment_address']['custom_field']['address'] = $this->session->data['payment_address']['custom_field'];

                  // Cadastra o cliente caso o visitante não possua um cadastro na loja
                  $query = $this->db->query(" SELECT COUNT(email) as total FROM ".DB_PREFIX."customer WHERE email='".$this->db->escape($this->session->data['guest']['email'])."'");

                  if ($query->row['total'] <= 0) {
                        $customer_id = $this->model_account_customer->addCustomer($this->session->data['guest']);
                        $address_id = $this->model_account_address->addAddress($customer_id, $this->session->data['payment_address']);

                        $this->db->query("UPDATE ".DB_PREFIX."order SET customer_id=".(int) $customer_id." WHERE order_id=". (int) $this->session->data['order_id']);

                      $login = $this->customer->login(
                        $this->session->data['guest']['email'], 
                        html_entity_decode($this->session->data['guest']['password'], ENT_QUOTES, 'UTF-8')
                      );
                    }
                }
              }
          
		$this->load->language('checkout/success');

		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}
