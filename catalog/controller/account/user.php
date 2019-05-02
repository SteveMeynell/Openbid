<?php
class ControllerAccountUser extends Controller {
	public function index() {

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/user', 'user=' . $this->request->get['user'], true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
	
		$this->load->language('account/user');
		$this->load->model('account/user');
		$this->load->model('tool/image');
		$this->load->model('auction/bidding');
		$setting = array(
			'width'	=> '150',
			'height'	=> '150'
		);
		$userId = $this->db->escape($this->request->get['user']);

		$firstName = $this->model_account_user->getFirstName($userId);
		$memberSince = $this->model_account_user->getMemberSince($userId);
		//debuglog($firstName . " has been a member since " . $memberSince);
		$allAuctions = $this->model_account_user->getAuctions($userId);
		//debuglog($allAuctions['bidder']);
		foreach ($allAuctions['seller']['auctions'] as $type => $auctions) {
			foreach($auctions as $auction_info) {
				if ($auction_info) {
					if ($auction_info['main_image']) {
						$image = $this->model_tool_image->resize($auction_info['main_image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
		
					if (is_null($auction_info['winning_bid'])) {
						$overlayImage = $this->model_tool_image->resize('closed.png', $setting['width'], $setting['height']);
					} else {
						$overlayImage = $this->model_tool_image->resize('won_auction.png', $setting['width'], $setting['height']);
					}

					$price = $this->currency->format($auction_info['winning_bid'], $this->session->data['currency']);


					//$rating = $auction_info['seller_id']['star_rating']['communication'];
					$currentBid = $this->model_auction_bidding->getCurrentBid($auction_info['auction_id']);
					if($auction_info['status'] == '2') {
						$href = $this->url->link('auction/auction', 'auction_id=' . $auction_info['auction_id']);
					} elseif ($auction_info['status'] == '3') {
						$href = $this->url->link('auction/closed_auctions', 'auction_id=' . $auction_info['auction_id']);
					} else {
						$href = '#';
					}

					$data['auctions'][$type][] = array(
						'auction_id'  => $auction_info['auction_id'],
						'thumb'       => $image,
						'overlay_image'	=> $overlayImage,
						'title'        => $auction_info['name'],
						'subtitle'        => $auction_info['subname'],
						'description' => utf8_substr(strip_tags(html_entity_decode($auction_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'seller'			=> $firstName,
						'seller_link'	=> $this->url->link('account/user', 'user=' . $userId, true), 
						'buy_now'			=> $this->currency->format($auction_info['buy_now_price'], $this->session->data['currency']), 
						'want_buy_now'	=> ($auction_info['buy_now_price'])?'1':'0', 
						'price'       => $price, 
						'current_bid'		=> $this->currency->format($currentBid['bid_amount'], $this->session->data['currency']), 
						'buy_now_only'	=> $auction_info['buy_now_only'],
						//'rating'      => $rating,
						'href'        => $href
						);
				}
			}
		}

		//debuglog($allAuctions['seller']['closed']);
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => sprintf($this->language->get('text_account'),$firstName),
			'href' => $this->url->link('account/user', 'user='. $userId, true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 

		$data['heading_title'] = $this->language->get('heading_title');

		$data['heading_user'] = sprintf($this->language->get('text_account'),$firstName);
		$data['heading_user_info']	= $this->language->get('heading_user_info');
		$data['heading_seller']	= $this->language->get('heading_seller');
		$data['heading_bidder']	= $this->language->get('heading_bidder');
		$data['heading_featured']	= $this->language->get('heading_featured');
		$data['heading_created']	= $this->language->get('heading_created');
		$data['heading_closed']	= $this->language->get('heading_closed');
		$data['heading_open']	= $this->language->get('heading_open');

		$data['text_username'] = $this->language->get('text_username');
		$data['text_membership'] = $this->language->get('text_membership');
		$data['text_auctions_rank'] = $this->language->get('text_auctions_rank');
		$data['text_total_auctions'] = $this->language->get('text_total_auctions');
		$data['text_total_views'] = $this->language->get('text_total_views');
		$data['text_total_items_sold'] = $this->language->get('text_total_items_sold');
		$data['text_highest_winning_bid'] = $this->language->get('text_highest_winning_bid');
		$data['text_most_bids_received'] = $this->language->get('text_most_bids_received');
		$data['text_highest_bid_received'] = $this->language->get('text_highest_bid_received');
		$data['text_seller_ranking'] = $this->language->get('text_seller_ranking');
		$data['text_bidder_ranking'] = $this->language->get('text_bidder_ranking');
		$data['text_please_login'] = $this->language->get('text_please_login');
		$data['text_current_bid'] = $this->language->get('text_current_bid');
		$data['text_total_bids_placed'] = $this->language->get('text_total_bids_placed');
		$data['text_total_bids_won'] = $this->language->get('text_total_bids_won');
		$data['text_max_bid_placed'] = $this->language->get('text_max_bid_placed');
		$data['text_num_auctions_bid'] = $this->language->get('text_num_auctions_bid');

		$data['button_wishlist']		= $this->language->get('button_wishlist');

		$data['userId'] = $userId;
		$data['firstname'] = $firstName;
		$data['membersince'] = $memberSince;
		$data['seller_info'] = $allAuctions['seller'];
		$data['bidder_info'] = $allAuctions['bidder'];
		//$data['stats'] = $allAuctions['stats'];



		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/user', $data));
	}

	public function review() {
		$json = array();

		if (isset($this->request->get['review_type'])) {
			$type = $this->request->get['review_type'];
			$userId = $this->request->get['user_id'];
			$this->load->model('account/review');
			if($type == 'seller') {
				$reviews['seller'] = $this->model_account_review->getReviewsBySellerId($userId);
			} else {
				$reviews['bidder'] = $this->model_account_review->getReviewsByBidderId($userId);
			}

			$json['reviews'] = $reviews;
			//$json['success'] = $type . " ok got it";
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// end of Controller
}
