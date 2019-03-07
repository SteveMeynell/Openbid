<?php
class ControllerExtensionModuleEndingSoon extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/ending_soon');

		$data['heading_title'] = $setting['name']; //$this->language->get('heading_title');

		$data['text_buy_now'] = $this->language->get('text_buy_now');
		$data['text_buy_now_only'] = $this->language->get('text_buy_now_only');
		$data['text_current_bid'] = $this->language->get('text_current_bid');
		$data['text_viewed'] = $this->language->get('text_viewed');
		$data['text_please_login']	= $this->language->get('text_please_login');
		$data['text_ending_in'] = $this->language->get('text_ending_in');
		$data['text_days'] = $this->language->get('text_days');
		$data['text_hours'] = $this->language->get('text_hours');
		$data['text_minutes'] = $this->language->get('text_minutes');
		
		//$data['text_ending_when'] = ($setting['ending_when']) ? $this->language->get('text_days'):$this->language->get('text_hours');

		$data['button_bid'] = $this->language->get('button_bid');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/auction');

		$this->load->model('tool/image');

		$data['auctions'] = array();


		$results = $this->model_catalog_auction->getEndingSoonAuctions($setting);

		if ($results) {
			$this->load->model('auction/bidding');
			
			foreach ($results as $result) {
				
				if ($result['main_image']) {
					$image = $this->model_tool_image->resize($result['main_image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$tempcurrent_bid = $this->model_auction_bidding->getCurrentBid($result['auction_id']);
					$current_bid = $this->currency->format($tempcurrent_bid['bid_amount'],$this->session->data['currency']);
					$buy_now = $this->currency->format($result['buy_now_price'],$this->session->data['currency']);
					$want_buy_now = $result['buy_now_price'];
				} else {
					$current_bid = false;
					$buy_now = false;
					$want_buy_now = false;
				}
				

				//$ending_in = date_diff(date_create(), date_create($result['end_date']));

				
				
				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else { 
					$rating = false;
				}

				$data['auctions'][] = array(
					'auction_id'  => $result['auction_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'current_bid'       => $current_bid,
					'buy_now_only'	=> $result['buy_now_only'],
					'buy_now'     => $buy_now,
					'want_buy_now'	=> $want_buy_now,
					'rating'		=> $rating,
					'end_date'		=> $result['end_date'],
					'views'      => $result['viewed'],
					'href'        => $this->url->link('auction/auction', 'auction_id=' . $result['auction_id'])
				);
			}

			
		}
		return $this->load->view('extension/module/ending_soon', $data);
	}
}
