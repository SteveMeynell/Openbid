<?php
class ControllerAccountWishList extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/wishlist');

		$this->load->model('account/wishlist');

		$this->load->model('catalog/auction');

		$this->load->model('auction/bidding');

		$this->load->model('tool/image');

		if (isset($this->request->get['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->get['remove']);

			$this->session->data['success'] = $this->language->get('text_remove');

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

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
			'href' => $this->url->link('account/wishlist')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_start_date'] = $this->language->get('column_start_date');
		$data['column_end_date'] = $this->language->get('column_end_date');
		$data['column_reserve_price'] = $this->language->get('column_reserve_price');
		$data['column_buy_now_price'] = $this->language->get('column_buy_now_price');
		$data['column_current_bid'] = $this->language->get('column_current_bid');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['auctions'] = array();

		$results = $this->model_account_wishlist->getWishlist();

		foreach ($results as $result) {
			$auction_info = $this->model_catalog_auction->getAuction($result['auction_id']);

			if ($auction_info) {
				if ($auction_info['main_image']) {
					$image = $this->model_tool_image->resize($auction_info['main_image'], $this->config->get($this->config->get('config_theme') . '_image_wishlist_width'), $this->config->get($this->config->get('config_theme') . '_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($auction_info['status'] == '1') {
					// Auction not open yet
					// don't show any prices or bids
					$reserve_price = 'Not Open Yet';
					$buy_now_price = 'Not Open Yet';
					$currentBid = 'Not Open Yet';
					$href = $this->url->link('account/wishlist');
				} elseif ($auction_info['status'] == '3') {
					// Auction is closed
					// Only show the winning bid
					$reserve_price = 'Closed';
					$buy_now_price = 'Closed';
					$currentBid = $this->currency->format($auction_info['winning_bid'], $this->session->data['currency']);
					$href = $this->url->link('auction/closed_auctions', 'auction_id=' . $auction_info['auction_id']);
				} else {
					// Auction is open
					// show all the prices
					$reserve_price = $this->currency->format($auction_info['reserve_price'], $this->session->data['currency']);
					$buy_now_price = $this->currency->format($auction_info['buy_now_price'], $this->session->data['currency']);
					$current_bid = $this->model_auction_bidding->getCurrentBid($result['auction_id']);
					$currentBid = $this->currency->format($current_bid['bid_amount'], $this->session->data['currency']);
					$href = $this->url->link('auction/auction', 'auction_id=' . $auction_info['auction_id']);
				}

				$data['auctions'][] = array(
					'auction_id'				=> $auction_info['auction_id'],
					'thumb'							=> $image,
					'name'							=> $auction_info['name'],
					'start_date'				=> $auction_info['start_date'],
					'end_date'					=> $auction_info['end_date'],
					'reserve_price'			=> $reserve_price,
					'buy_now_price'			=> $buy_now_price,
					'current_bid'				=> $currentBid,
					'href'							=> $href,
					'remove'						=> $this->url->link('account/wishlist', 'remove=' . $auction_info['auction_id'])
				);
			} else {
				$this->model_account_wishlist->deleteWishlist($result['auction_id']);
			}
		}

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/wishlist', $data));
	}

	public function add() {
		$this->load->language('account/wishlist');

		$json = array();

		if (isset($this->request->post['auction_id'])) {
			$auction_id = $this->request->post['auction_id'];
		} else {
			$auction_id = 0;
		}

		$this->load->model('catalog/auction');

		$auction_info = $this->model_catalog_auction->getAuction($auction_id);

		if ($auction_info) {
			if ($this->customer->isLogged()) {
				// Edit customers cart
				$this->load->model('account/wishlist');

				$this->model_account_wishlist->addWishlist($this->request->post['auction_id']);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('auction/auction', 'auction_id=' . (int)$this->request->post['auction_id']), $auction_info['name'], $this->url->link('account/wishlist'));

				$json['total'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			} else {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				$this->session->data['wishlist'][] = $this->request->post['auction_id'];

				$this->session->data['wishlist'] = array_unique($this->session->data['wishlist']);

				$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('auction/auction', 'auction_id=' . (int)$this->request->post['auction_id']), $auction_info['name'], $this->url->link('account/wishlist'));

				$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
