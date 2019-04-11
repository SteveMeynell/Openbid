<?php
class ControllerAuctionClosedAuctions extends Controller {
	private $error = array();

	public function index() {
		
		$this->load->language('auction/auction');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['auction_id'])) {
			$auction_id = (int)$this->request->get['auction_id'];
		} else {
			$auction_id = 0;
		}

		

		$this->load->model('catalog/auction');
		$this->load->model('auction/bidding');

		$auction_info = $this->model_catalog_auction->getClosedAuction($auction_id);
		$winning_bid = $this->model_auction_bidding->getWinningBid($auction_id);

		if ($auction_info) {
			$url = '';
			
			$data['breadcrumbs'][] = array(
				'text' => $auction_info['title'],
				'href' => $this->url->link('auction/closed_auctions', $url . '&auction_id=' . $this->request->get['auction_id'])
			);

			$this->document->setTitle($auction_info['meta_title']);
			$this->document->setDescription($auction_info['meta_description']);
			$this->document->setKeywords($auction_info['meta_keyword']);
			$this->document->addLink($this->url->link('auction/auction', 'auction_id=' . $this->request->get['auction_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			
			$data['heading_title'] = $auction_info['title'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_viewed'] = $this->language->get('text_viewed');
			$data['text_seller'] = $this->language->get('text_seller');
			$data['text_num_bids'] = $this->language->get('text_num_bids');
			$data['text_sold_for'] = $this->language->get('text_sold_for');

			/*$this->load->language('auction/review');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
			

			if($this->customer->isLogged() && $this->customer->getId() == $auction_info['seller_id']) {
				// Ok its the seller
				$data['entry_question1'] = $this->language->get('seller_question1');
				$data['entry_question2'] = $this->language->get('seller_question2');
				$data['entry_question3'] = $this->language->get('seller_question3');
			} elseif ($this->customer->isLogged() && $this->customer->getId() == $winning_bid['bidder_id']) {
				// Ok its the bidder
				$data['entry_question1'] = $this->language->get('bidder_question1');
				$data['entry_question2'] = $this->language->get('bidder_question2');
				$data['entry_question3'] = $this->language->get('bidder_question3');	
			} else {
				// guest 
				$data['entry_question1'] = $this->language->get('guest_question1');
				$data['entry_question2'] = $this->language->get('guest_question2');
				$data['entry_question3'] = $this->language->get('guest_question3');
			}
			
			$data['entry_question4'] = $this->language->get('site_question1');
			$data['entry_question5'] = $this->language->get('site_question2');
			$data['entry_question6'] = $this->language->get('site_question3');
			
			$data['button_continue'] = $this->language->get('button_continue');
*/
			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');

			
			$auction_info['reviews'] = $this->model_catalog_review->getTotalReviewsBySellerId($auction_info['seller_id']);
			$auction_info['seller_rating'] = $this->model_catalog_review->getTotalRateBySellerId($auction_info['seller_id']);
				//$data['all_seller_reviews'] = $this->model_catalog_review->getReviewsBySellerId($auction_info['seller_id']);
			
	//debuglog($auction_info['all_seller_reviews']);
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $auction_info['reviews']);

			$data['auction_id'] = (int)$this->request->get['auction_id'];
			
			$data['description'] = html_entity_decode($auction_info['description'], ENT_QUOTES, 'UTF-8');
			$data['seller_id'] = $auction_info['seller_id'];
			$data['seller'] = $auction_info['seller'];
			$data['num_bids'] = $auction_info['num_bids'];
			$data['sold_for'] = (isset($winning_bid['bid_amount']) && $winning_bid['winner'])?$winning_bid['bid_amount']:'Did Not Sell!';
			$data['sold'] = $winning_bid['winner'];


			$this->load->model('tool/image');

			if ($auction_info['main_image']) {
				$data['popup'] = $this->model_tool_image->resize($auction_info['main_image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}

			if ($auction_info['main_image']) {
				$data['thumb'] = $this->model_tool_image->resize($auction_info['main_image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['closed_image'] = $this->model_tool_image->resize('closed.png', $this->config->get($this->config->get('config_theme') . '_image_thumb_width')*2, $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			$data['winner_image'] = $this->model_tool_image->resize('thewinner.png', $this->config->get($this->config->get('config_theme') . '_image_thumb_width')*2, $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			$data['won_image'] = $this->model_tool_image->resize('won_auction.png', $this->config->get($this->config->get('config_theme') . '_image_thumb_width')*2, $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));

			$data['images'] = array();

			$results = $this->model_catalog_auction->getAuctionImages($this->request->get['auction_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
				);
			}

			
			$data['review_status'] = $this->config->get('config_review_status');

			if($this->customer->isLogged() && isset($winning_bid['bidder_id']) && $this->customer->getId() == $winning_bid['bidder_id']) {
				$data['winning_bidder'] = true;
			} else {
				$data['winning_bidder'] = false;
			}

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$auction_info['reviews']);
			
			
			$data['rating'] = (int)$auction_info['seller_rating'];
			$data['views'] = $auction_info['views'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('auction/auction', 'auction_id=' . (int)$this->request->get['auction_id']);

			$data['tags'] = array();

			if ($auction_info['tag']) {
				$tags = explode(',', $auction_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('auction/search', 'tag=' . trim($tag))
					);
				}
			}

			//$this->model_catalog_auction->updateViewed($this->request->get['auction_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/module/closed_auctions', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('auction/auction', $url . '&auction_id=' . $auction_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function review() {
		$this->load->language('auction/auction');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsBySellerId($this->request->get['seller_id']);

		$results = $this->model_catalog_review->getReviewsBySellerId($this->request->get['seller_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('auction/auction/review', 'seller_id=' . $this->request->get['seller_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('auction/review', $data));
	}

	


	public function getBidHistory(){
		$this->load->model('auction/bidding');
		$auction_id = $this->request->get['auction_id'];
		$min_bid = $this->request->get['min_bid'];
		$bidList = $this->model_auction_bidding->getAllBids($auction_id);
		$json = array();
		$json['bids'] = array();
		$json['isUsersBid'] = array();

		//debuglog("bidList:");
		//debuglog($bidList);
		foreach($bidList as $bidAmount){
			array_push($json['bids'],$bidAmount['bid_amount']);
			if($this->customer->isLogged() && $bidAmount['bidder_id'] === $this->customer->getId()){
				array_push($json['isUsersBid'],'1');
			} else {
				array_push($json['isUsersBid'],'0');
			}
		}
		$json['currentBid'] = array_pop($json['bids']);
		array_push($json['bids'], $json['currentBid']);
		if($json['currentBid']){
			$nextBid = $this->model_auction_bidding->getNextBid($json['currentBid']);
		} else {
			$nextBid = $this->model_auction_bidding->getNextBid($min_bid);
		}
		
		$json['nextBid'] = $nextBid;
		//debuglog("Json:");
		//debuglog($json);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// end of controller
}
