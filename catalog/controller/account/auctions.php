<?php
class ControllerAccountAuctions extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/auctions', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/auctions');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/auctions', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_auction_id'] = $this->language->get('column_auction_id');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_start_date'] = $this->language->get('column_start_date');
		$data['column_end_date'] = $this->language->get('column_end_date');
		$data['column_views'] = $this->language->get('column_views');
		$data['column_bids'] = $this->language->get('column_bids');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_reserve_bid'] = $this->language->get('column_reserve_bid');
		$data['column_buy_now_price'] = $this->language->get('column_buy_now_price');
		$data['column_top_bid'] = $this->language->get('column_top_bid');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['auctions'] = array();

		$this->load->model('account/auction');

		$auction_total = $this->model_account_auction->getTotalAuctions();

		$results = $this->model_account_auction->getAuctions(($page - 1) * 10, 10);
		//debuglog($results);
		foreach ($results as $result) {
			// ok lets figure out the action on each first
			// if the auction is in created state you can edit it
			// if the auction is in open state you can only view it like a normal auction
			// if the auction is in closed state you can only view it as a closed auction
			// if the auction is in moderation state you can only view it.

			switch($result['status']) {
				case '0':
					// Under Moderation
					$view['link'] = $this->url->link('account/auctions/view', 'auction_id=' . $result['auction_id'], true);
					$view['button_colour'] = 'danger';
					$view['button_pic'] = 'stop';
					break;
				case '1':
					// Created
					$view['link'] = $this->url->link('auction/edit', 'auction_id=' . $result['auction_id'], true);
					$view['button_colour'] = 'warning';
					$view['button_pic'] = 'edit';
					break;
				case '2':
					// Open
					$view['link'] = $this->url->link('account/auctions/info', 'auction_id=' . $result['auction_id'], true);
					$view['button_colour'] = 'success';
					$view['button_pic'] = 'gavel';
					break;
				case '3':
					// Closed
					$view['link'] = $this->url->link('account/auctions/info', 'auction_id=' . $result['auction_id'], true);
					$view['button_colour'] = 'info';
					$view['button_pic'] = 'eye';
					break;
				case '4':
					// Suspended
					$view['link'] = $this->url->link('account/auctions/suspended', 'auction_id=' . $result['auction_id'], true);
					$view['button_colour'] = 'danger';
					$view['button_pic'] = 'gavel';
					break;
			}

			$data['auctions'][] = array(
				'auction_id'   => $result['auction_id'],
				'title'       => $result['title'],
				'status'     => $this->model_account_auction->getAuctionStatusByType($result['status']),
				'views'					=> $result['viewed'],
				'date_start'		=> date($this->language->get('datetime_format'), strtotime($result['start_date'])),
				'date_ended'		=> date($this->language->get('datetime_format'), strtotime($result['end_date'])),
				'bids'   => $result['num_bids'],
				'type'   => $result['buy_now_only']?'Buy Now Only':'Regular',
				'reserve_bid'			=> $result['reserve_price'],
				'buy_now_price'			=> $result['buy_now_price'],
				'top_bid'			=> $this->model_account_auction->getHighestBid($result['auction_id']),
				'view'       => $view
			);
		}

		$pagination = new Pagination();
		$pagination->total = $auction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/auctions', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($auction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($auction_total - 10)) ? $auction_total : ((($page - 1) * 10) + 10), $auction_total, ceil($auction_total / 10));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/auction_list', $data));
	}

	public function info() {
		$this->load->language('account/auctions');

		if (isset($this->request->get['auction_id'])) {
			$auction_id = $this->request->get['auction_id'];
		} else {
			$auction_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/auctions/info', 'auction_id=' . $auction_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		
		$this->load->model('auction/auction');
		$durations = $this->model_auction_auction->getAuctionDurations();

		$this->load->model('catalog/auction');
		$auction_info = $this->model_catalog_auction->getAuction($auction_id);

		$this->load->model('extension/module');
		$data['allow_subtitles'] = $this->config->get('config_auction_subtitles');
		$data['allow_extra_images'] = $this->config->get('config_auction_picture_gallery');

		// hmm options how to show them.  I want to show an option if the module is used and have a yes or check box beside it if the user has chosen it.
		$data['options'] = array();

    $data['auto_relist_used'] = $this->config->get('config_auction_auto_relist');
    $data['max_relists'] = $this->config->get('config_auction_max_relists');

		if ($auction_info) {
			//debuglog($auction_info);
			$data['auction_id'] = $auction_id;
			$data['auction_title'] = $auction_info['name'];
			$data['auction_subtitle'] = $auction_info['subname'];
			$data['auction_description'] = $auction_info['description'];
			$data['auction_tags'] = $auction_info['tag'];
			$data['auction_type'] = $auction_info['buy_now_only']?'Buy Now Only':'Regular';
			$data['auction_views'] = $auction_info['viewed'];
			

			if ($this->model_extension_module->isModuleUsed('featured')) {
				array_push($data['options'], array('name' => 'Item Featured', 'toggle' => $auction_info['featured']?'Yes':'No'));
			}
			if ($this->model_extension_module->isModuleUsed('carousel')) {
				array_push($data['options'], array('name' => 'Item on Carousel', 'toggle' => $auction_info['on_carousel']?'Yes':'No'));
			}
			if ($this->model_extension_module->isModuleUsed('slideshow')) {
				array_push($data['options'], array('name' => 'Item in Slideshow', 'toggle' => $auction_info['slideshow']?'Yes':'No'));
			}

			array_push($data['options'], array('name' => 'Item Bolded', 'toggle' => $auction_info['bolded']?'Yes':'No'));
			array_push($data['options'], array('name' => 'Item Highlighted', 'toggle' => $auction_info['highlighted']?'Yes':'No'));
			array_push($data['options'], array('name' => 'Advertised on Social Media', 'toggle' => $auction_info['social_media']?'Yes':'No'));

			$data['starting_bid'] = $this->currency->format($auction_info['min_bid'],$this->session->data['currency']);
			$data['reserve_bid'] = $this->currency->format($auction_info['reserve_price'],$this->session->data['currency']);
			$data['buy_now_price'] = $this->currency->format($auction_info['buy_now_price'],$this->session->data['currency']);
			$this->load->model('account/bid');
			$topBid = $this->model_account_bid->getTopBid($auction_id);
			$data['top_bid'] = $topBid?$this->currency->format($topBid,$this->session->data['currency']):'No Bids Received';
			foreach($durations as $duration) {
				if($auction_info['duration'] == $duration['duration']) {
					$data['auction_duration'] = $duration['description'];
				}
			}



			$this->load->model('tool/image');

			if ($auction_info['main_image']) {
				$data['popup'] = $this->model_tool_image->resize($auction_info['main_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}

			if ($auction_info['main_image']) {
				$data['thumb'] = $this->model_tool_image->resize($auction_info['main_image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}

			$data['images'] = array();

			$results = $this->model_catalog_auction->getAuctionImages($this->request->get['auction_id']);
			if (!$results) {
				for($x=0; $x < $this->config->get('config_auction_max_gallery_pictures'); $x++) {
					$data['images'][$x] = array(
						'popup' => $this->model_tool_image->resize('no_image.png', $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
						'thumb' => $this->model_tool_image->resize('no_image.png', $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
					);
				}
			}
				

			foreach ($results as $result) {
				$data['images'][$result['sort_order']] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
				);
			}
			$this->document->setTitle(sprintf($this->language->get('text_auction'), $auction_id));

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/auctions', $url, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => sprintf($this->language->get('text_auction'),$auction_id),
				'href' => $this->url->link('account/auctions/info', 'auction_id=' . $this->request->get['auction_id'] . $url, true)
			);

			$data['heading_title'] = sprintf($this->language->get('text_auction'), $auction_id);

			$data['text_auction_title'] = $this->language->get('text_auction_title');
			$data['text_auction_subtitle'] = $this->language->get('text_auction_subtitle');
			$data['text_auction_id'] = $this->language->get('text_auction_id');
			$data['text_auction_description'] = $this->language->get('text_auction_description');
			$data['text_auction_tags'] = $this->language->get('text_auction_tags');
			$data['text_auction_type'] = $this->language->get('text_auction_type');
			$data['text_auction_price_settings'] = $this->language->get('text_auction_price_settings');
			$data['text_auction_starting_bid'] = $this->language->get('text_auction_starting_bid');
			$data['text_auction_reserve_bid'] = $this->language->get('text_auction_reserve_bid');
			$data['text_auction_buy_now_price'] = $this->language->get('text_auction_buy_now_price');
			$data['text_auction_duration'] = $this->language->get('text_auction_duration');
			$data['text_auction_views'] = $this->language->get('text_auction_views');
			$data['text_auction_other_pictures'] = $this->language->get('text_auction_other_pictures');
			$data['text_auction_main_picture'] = $this->language->get('text_auction_main_picture');
			$data['text_auction_top_bid'] = $this->language->get('text_auction_top_bid');
			$data['text_auction_options_available'] = $this->language->get('text_auction_options_available');
			$data['text_empty'] = $this->language->get('text_empty');

			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['continue'] = $this->url->link('account/auctions', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/auction_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_auction'));

			$data['heading_title'] = $this->language->get('text_auction');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/auction', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_auction'),
				'href' => $this->url->link('account/auction/info', 'auction_id=' . $auction_id, true)
			);

			$data['continue'] = $this->url->link('account/auctions', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
	}
}