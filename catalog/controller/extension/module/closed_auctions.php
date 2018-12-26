<?php
class ControllerExtensionModuleClosedAuctions extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/closed_auctions');

		$data['heading_title'] = $setting['name'];

		/*$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
*/
		

		$this->load->model('tool/image');

		$data['auctions'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 5;
		}

		
		$this->load->model('catalog/auction');
		$filter = array(
			'limit'	=> $setting['limit'],
			'winners'	=> true
		);

		$auctions = $this->model_catalog_auction->getClosedAuctions($filter);

		foreach ($auctions as $auction_info) {
			if ($auction_info) {
				if ($auction_info['main_image']) {
					$image = $this->model_tool_image->resize($auction_info['main_image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}
	
				$price = $this->currency->format($auction_info['winning_bid'], $this->session->data['currency']);
/*					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($auction_info['price'], $auction_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}
*/

				$rating = '4';

				$data['auctions'][] = array(
					'auction_id'  => $auction_info['auction_id'],
					'thumb'       => $image,
					'title'        => $auction_info['title'],
					'subtitle'        => $auction_info['subtitle'],
					'description' => utf8_substr(strip_tags(html_entity_decode($auction_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'seller'			=> $auction_info['seller'],
					'price'       => $price,
					'rating'      => $rating,
					'href'        => $this->url->link('auction/closed_auctions', 'auction_id=' . $auction_info['auction_id'])
					);
			}
		}

		if ($data['auctions']) {
			return $this->load->view('extension/module/closed_auctions_mini', $data);
		}
		
	}
}