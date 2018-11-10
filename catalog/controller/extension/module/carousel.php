<?php
class ControllerExtensionModuleCarousel extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('catalog/auction');
		$this->load->model('tool/image');
		//$this->load->language('extension/module/carousel');

		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/animate.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
		
		// Set Up Filters

//		$filter['limit'] = $setting['num_auctions'];
		$data['heading_text'] = $setting['heading_text'];
		$data['footer_text'] = $setting['footer_text'];
		
		$data['transition'] = ($setting['transition_id'])?'fadeOut':'';

		$data['auctions'] = array();

		$results = $this->model_catalog_auction->getCarouselAuctions($setting);
		//debuglog($results);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['auctions'][] = array(
					'title' => $result['title'],
					'subtitle' => $result['subtitle'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'link'  => $this->url->link('auction/auction', 'auction_id=' . $result['auction_id']),
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/carousel', $data);
	}
}