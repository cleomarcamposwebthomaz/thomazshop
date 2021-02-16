<?php
class ControllerExtensionModuleBycategory extends Controller {
public function index($setting) {
	$this->load->model('catalog/product');
	$this->load->model('catalog/category');
	$this->load->model('tool/image');

		$data['products'] = array();

		$data['category_info'] = $this->model_catalog_category->getCategory($setting['category_id']);

		$data['heading_title'] = $setting['name'];

		$data['carousel_id'] = $setting['category_id'];

		if($data['category_info']){
			$data['catimag'] = $this->model_tool_image->resize($data['category_info']['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
		}else{
			$data['catimag'] = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height'));
		}


		$data['category_link'] = $this->url->link('product/category', 'path=' . $setting['category_id'],'SSL');

		$results = $this->model_catalog_product->getProductsByCategory($setting['category_id'],$setting['limit']);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
			} else {
				$image = false;
			}
			$imagens = $this->model_catalog_product->getProductImages($result['product_id']);
			if($imagens){
				$image2 = $this->model_tool_image->resize($imagens[0]['image'], $setting['width'], $setting['height']);
			}else{
				$image2 = $image;
			}


			if ((float)$result['price']) {
				$mrp_percentage = round(100 - (($result['price']*100) / $result['price']));
			} else {
				$mrp_percentage = false;
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
			} else {
				$special = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = $result['rating'];
			} else {
				$rating = false;
			}

			if ((float)$result['special']) {
				$special_percentage = round(100 - (($result['special']*100) / $result['price']));
			} else {
				$special_percentage = false;
			}

			$data['products'][] = array(
				'name' 			=> utf8_substr(strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')), 0, 50),
				'product_id' 	=> $result['product_id'],
				'model' 		=> $result['model'],
				'quantity' 		=> $result['quantity'],
				'description' 	=> html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
				'thumb'   	 	=> $image,
				'thumb2'   	 	=> $image2,
				'price'   	 	=> $price,
				'rating'     	=> $rating,
				'special' 	 	=> $special,
				'special_percentage' =>$special_percentage,
				'href'     	 	=> $this->url->link('product/product', 'product_id=' . $result['product_id'],'SSL')
			);

			$this->load->model('setting/setting');
			$data['config_semjuros'] = $this->model_setting_setting->getSettingValue('config_semjuros', '0'); $data['directory'] = $this->model_setting_setting->getSettingValue('theme_default_directory', '0'); 
		}
			return $this->load->view('extension/module/bycategory', $data);
	}
}
?>
