<?php
class ControllerExtensionModuleOneCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/onecheckout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_onecheckout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/onecheckout', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/onecheckout', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_onecheckout_document_id'])) {
			$data['module_onecheckout_document_id'] = $this->request->post['module_onecheckout_document_id'];
		} else {
			$data['module_onecheckout_document_id'] = $this->config->get('module_onecheckout_document_id');
		}

		if (isset($this->request->post['module_onecheckout_number_id'])) {
			$data['module_onecheckout_number_id'] = $this->request->post['module_onecheckout_number_id'];
		} else {
			$data['module_onecheckout_number_id'] = $this->config->get('module_onecheckout_number_id');
		}

		if (isset($this->request->post['module_onecheckout_register'])) {
			$data['module_onecheckout_register'] = $this->request->post['module_onecheckout_register'];
		} else {
			$data['module_onecheckout_register'] = $this->config->get('module_onecheckout_register');
		}

		if (isset($this->request->post['module_onecheckout_status'])) {
			$data['module_onecheckout_status'] = $this->request->post['module_onecheckout_status'];
		} else {
			$data['module_onecheckout_status'] = $this->config->get('module_onecheckout_status');
		}

        $this->load->model('customer/custom_field');

        $data['custom_fields'] = $this->model_customer_custom_field->getCustomFields();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/onecheckout', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/onecheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}