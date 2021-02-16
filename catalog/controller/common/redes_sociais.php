<?php
class ControllerCommonRedesSociais extends Controller {
	public function index() {

		$this->load->model('setting/extension');

		$data['facebook'] = $this->config->get('config_social_facebook');
		$data['instagram'] = $this->config->get('config_social_instagram');
		$data['whatsapp'] = $this->config->get('config_social_whatsapp');
		$data['youtube'] = $this->config->get('config_social_youtube');
		$data['twitter'] = $this->config->get('config_social_twitter');

		return $this->load->view('common/redes_sociais', $data);
	}
}
