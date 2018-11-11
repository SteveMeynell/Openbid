<?php
class ControllerExtensionJumbotronJumbotron extends Controller {
	public function index() {
		static $module = 0;

		$this->load->model('catalog/auction');
		$this->load->model('tool/image');

		$option = $this->config->get('jumbotron_option');
		$data['heading'] = $this->config->get('jumbotron_heading');
		
		if($option) {
			$most_viewed = $this->model_catalog_auction->getMostViewedAuctions('1');
			$data['auction'] = array_pop($most_viewed);
		} else {
			$auction_total = $this->model_catalog_auction->getTotalAuctions();
			$auctions = $this->model_catalog_auction->getAuctions();
			$data['auction'] = $auctions[rand(0,$auction_total-1)];
		}
		
		$bgimage = $this->model_tool_image->resize($data['auction']['image'], 1000,1000);
		
		$data['start_div'] = '<div class="jumbotron jumbotron-fluid text-center" style="background-image: url(' . $bgimage . '); background-size: cover; background-repeat: no-repeat no-repeat; background-position: top; background-color: #ffffff;">';

/*		

		foreach ($most_vieweds as $most_viewed) {
			if (is_file(DIR_IMAGE . $most_viewed['image'])) {
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
*/


		return $this->load->view('extension/jumbotron/jumbotron', $data);
	}
}