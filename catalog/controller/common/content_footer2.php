<?php
class ControllerCommonContentFooter2 extends Controller {
	public function index() {
		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('setting/module');

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_footer2');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			if (isset($part[0]) && $this->config->get('module_' . $part[0] . '_status')) {
				$module_data = $this->load->controller('extension/module/' . $part[0]);

				if ($module_data) {
					$data['modules'][] = $module_data;
				}
			}


			if (isset($part[1])) {
				$setting_info = $this->model_setting_module->getModule($part[1]);

				if ($setting_info && $setting_info['status']) {
					$output = $this->load->controller('extension/module/' . $part[0], $setting_info);

					if ($output) {
						$data['modules'][] = $output;
					}
				}
			}
		}

		$data['telephone'] = $this->config->get('config_telephone');
		$data['address'] = $this->config->get('config_address');
		$data['email'] = $this->config->get('config_email');
		$data['redes_sociais'] = $this->load->controller('common/redes_sociais');
		$data['site'] = $_SERVER['SERVER_NAME'];
		return $this->load->view('common/content_footer2', $data);
	}
}
