<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$data['heading_title'] = $setting['name'];

		$data['text_buy_now'] = $this->language->get('text_buy_now');
		$data['text_buy_now_only'] = $this->language->get('text_buy_now_only');
		$data['text_current_bid'] = $this->language->get('text_current_bid');
		$data['text_viewed'] = $this->language->get('text_viewed');
		$data['text_please_login']	= $this->language->get('text_please_login');
		$data['text_ending_in'] = $this->language->get('text_ending_in');
		$data['text_days'] = $this->language->get('text_days');
		$data['text_hours'] = $this->language->get('text_hours');
		$data['text_minutes'] = $this->language->get('text_minutes');

		$data['button_bid'] = $this->language->get('button_bid');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		

		$this->load->model('tool/image');

		$data['auctions'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 5;
		}

		
		$this->load->model('catalog/auction');
		$this->load->model('auction/bidding');

		$auctions = $this->model_catalog_auction->getFeaturedAuctions($setting);

		foreach ($auctions as $auction_info) {
			if ($auction_info) {
				if ($auction_info['image']) {
					$image = $this->model_tool_image->resize($auction_info['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$tempcurrent_bid = $this->model_auction_bidding->getCurrentBid($auction_info['auction_id']);
					$current_bid = $this->currency->format($tempcurrent_bid['bid_amount'],$this->session->data['currency']);
					$buy_now = $this->currency->format($auction_info['buy_now_price'],$this->session->data['currency']);
					$want_buy_now = $auction_info['buy_now_price'];
				} else {
					$current_bid = false;
					$buy_now = false;
					$want_buy_now = false;
				}
	
				//$price = $this->currency->format($auction_info['reserve_price'], $this->session->data['currency']);
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
					'current_bid'       => $current_bid,
					'buy_now_only'	=> $auction_info['buy_now_only'],
					'buy_now'     => $buy_now,
					'want_buy_now'	=> $want_buy_now,
					'rating'		=> $rating,
					'end_date'		=> $auction_info['end_date'],
					'views'      => $auction_info['viewed'],
					'href'        => $this->url->link('auction/auction', 'auction_id=' . $auction_info['auction_id'])
					);
			}
		}

		if ($data['auctions']) {
			return $this->load->view('extension/module/featured', $data);
		}
		
	}
}