<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/auction');

		$this->load->model('tool/image');

		$data['auctions'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['auction'])) {
			$auctions = array_slice($setting['auction'], 0, (int)$setting['limit']);

			foreach ($auctions as $auction_id) {
				$auction_info = $this->model_catalog_auction->getAuction($auction_id);

				if ($auction_info) {
					if ($auction_info['image']) {
						$image = $this->model_tool_image->resize($auction_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($auction_info['price'], $auction_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$auction_info['special']) {
						$special = $this->currency->format($this->tax->calculate($auction_info['special'], $auction_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$auction_info['special'] ? $auction_info['special'] : $auction_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $auction_info['rating'];
					} else {
						$rating = false;
					}

					$data['auctions'][] = array(
						'auction_id'  => $auction_info['auction_id'],
						'thumb'       => $image,
						'name'        => $auction_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($auction_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_auction_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('auction/auction', 'auction_id=' . $auction_info['auction_id'])
					);
				}
			}
		}

		if ($data['auctions']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}