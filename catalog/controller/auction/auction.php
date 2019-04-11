<?php
class ControllerAuctionAuction extends Controller {
	private $error = array();

	public function index() {
		if(!$this->isThisAuctionOpen()) {
			$this->response->redirect($this->url->link('auction/closed_auctions', 'auction_id=' . $this->request->get['auction_id']));
		}
		$customerGroupId = $this->customer->getGroupId();
		$customerOnline = $this->customer->isLogged();
		$seePrices = $this->config->get('config_customer_price');
		$guestsBid = $this->config->get('config_checkout_guest');
	
		$this->load->language('auction/auction');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('catalog/category');

		if (isset($this->request->get['path'])) {
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('auction/category', 'path=' . $path)
					);
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

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
					'text' => $category_info['name'],
					'href' => $this->url->link('auction/category', 'path=' . $this->request->get['path'] . $url)
				);
			}
		}

		
			$url = '';

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


		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

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
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('auction/search', $url)
			);
		}

		if (isset($this->request->get['auction_id'])) {
			$auction_id = (int)$this->request->get['auction_id'];
		} else {
			$auction_id = 0;
		}

		$this->load->model('catalog/auction');

		$auction_info = $this->model_catalog_auction->getAuction($auction_id);

		if ($auction_info) {
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
				'text' => $auction_info['name'],
				'href' => $this->url->link('auction/auction', $url . '&auction_id=' . $this->request->get['auction_id'])
			);

			$this->document->setTitle($auction_info['meta_title']);
			$this->document->setDescription($auction_info['meta_description']);
			$this->document->setKeywords($auction_info['meta_keyword']);
			$this->document->addLink($this->url->link('auction/auction', 'auction_id=' . $this->request->get['auction_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/auction/bidding.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $auction_info['name'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_buy_now'] = $this->language->get('text_buy_now');
			$data['text_buy_now_only'] = $this->language->get('text_buy_now_only');
			$data['text_current_bid'] = $this->language->get('text_current_bid');
			$data['text_viewed'] = $this->language->get('text_viewed');
			$data['text_watching'] = $this->language->get('text_watching');
			$data['text_please_login']	= $this->language->get('text_please_login');
			$data['text_ending_in'] = $this->language->get('text_ending_in');
			$data['text_reserved_bid'] = $this->language->get('text_reserved_bid');
			$data['text_reserved_bid_met'] = $this->language->get('text_reserved_bid_met');
			$data['text_no_reserved_bid'] = $this->language->get('text_no_reserved_bid');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			$data['button_bid'] = $this->language->get('button_bid');
			$data['button_buynow'] = $this->language->get('button_buynow');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			
			$data['button_continue'] = $this->language->get('button_continue');

			$data['seller_id'] = $auction_info['customer_id'];
			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');

			$auction_info['reviews'] = $this->model_catalog_review->getTotalReviewsBySellerId($auction_info['customer_id']);
				
			//$data['all_seller_reviews'] = $this->model_catalog_review->getReviewsBySellerId($auction_info['customer_id']);
			
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $auction_info['reviews']);

			$data['auction_id'] = (int)$this->request->get['auction_id'];
			
			$data['description'] = html_entity_decode($auction_info['description'], ENT_QUOTES, 'UTF-8');
			
			$data['end_date']	= $auction_info['end_date'];

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

			$data['images'] = array();

			$results = $this->model_catalog_auction->getAuctionImages($this->request->get['auction_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
				);
			}

			$data['buy_now_only'] = $auction_info['buy_now_only'];
			$this->load->model('auction/bidding');
			$data['isLoggedIn'] = $customerOnline;
			if ($this->customer->isLogged() && $this->customer->getGroupId()!='2' && $this->customer->getId() != $auction_info['customer_id']) {
				$data['can_bid'] = 'yes';
			} else {
				$data['can_bid'] = 'no';
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$current_bid = $this->model_auction_bidding->getCurrentBid($this->request->get['auction_id']);
				$theNextBid = $this->currency->format($this->model_auction_bidding->getNextBid($current_bid['bid_amount']),$this->session->data['currency']);
				$minimumBid = $this->currency->format($auction_info['min_bid'],$this->session->data['currency']);
				$data['min_bid'] = $auction_info['min_bid'];
				$data['current_bid_amount'] = $current_bid['bid_amount'];
				$data['reserve_bid_amount'] = $auction_info['reserve_price'];
				$data['reserve_bid'] = $this->currency->format($auction_info['reserve_price'],$this->session->data['currency']);
				$data['next_bid_text'] = (floatval($current_bid['bid_amount'])>0)? $theNextBid : $minimumBid;
				$data['next_bid'] = (floatval($current_bid['bid_amount'])>0)? $this->model_auction_bidding->getNextBid($current_bid['bid_amount']) : $auction_info['min_bid'];
				//$data['button_bid'] .= $this->currency->format($data['next_bid'],$this->session->data['currency']);
				$data['buy_now'] = $this->currency->format($auction_info['buy_now_price'],$this->session->data['currency']);
				$data['want_buy_now'] = $auction_info['buy_now_price'];
				$data['button_buynow'] .= ' For Only ' . $this->currency->format($auction_info['buy_now_price'],$this->session->data['currency']);
				$data['current_bid'] = $this->currency->format($current_bid['bid_amount'],$this->session->data['currency']);
			} else {
				$data['min_bid'] = '0';
				$data['buy_now'] = false;
				$data['want_buy_now'] = false;
				$data['current_bid'] = false;
				$data['reserve_bid'] = false;
			}


			$data['review_status'] = $this->config->get('config_review_status');

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
			$auction_info['seller_rating'] = $this->model_catalog_review->getTotalRateBySellerId($auction_info['customer_id']);
			$data['rating'] = (int)$auction_info['seller_rating'];
			$data['views'] = $auction_info['viewed'];
			$this->load->model("auction/wishlist");
			$data['watches'] = $this->model_auction_wishlist->getTotalWatching($this->request->get['auction_id']);

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

			$this->model_catalog_auction->updateViewed($this->request->get['auction_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('auction/auction', $data));
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
				'bidder'     => $result['firstname'],
				'text'       => nl2br($result['bidder_suggestion']),
				'ratings'     => $result['ratings'],
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
		$this->load->model('catalog/review');
		$auction_id = $this->request->get['auction_id'];
		$min_bid = $this->request->get['min_bid'];
		$bidList = $this->model_auction_bidding->getAllBids($auction_id);
		$json = array();
		$json['bids'] = array();
		//$json['isUsersBid'] = array();

		foreach($bidList as $index => $bidAmount){
			$bidderRating = $this->model_catalog_review->getTotalRateByBidderId($bidAmount['bidder_id']);
			$json['bids'][$index]['bid_amount'] = $bidAmount['bid_amount'];
			//array_push($json['bids']['bid_amount'],$bidAmount['bid_amount']);
			if($this->customer->isLogged() && $bidAmount['bidder_id'] === $this->customer->getId()){
				$json['bids'][$index]['isUsersBid'] = '1';
				$json['currentWinner'] = '1';
			} else {
				$json['bids'][$index]['isUsersBid'] = '0';
				$json['currentWinner'] = '0';
			}
			$json['bids'][$index]['rating'] = $bidderRating;
			//array_push($json['bids']['rating'], $bidderRating);
			$json['currentBid'] = $bidAmount['bid_amount'];
		}
		//$json['currentBid'] = array_pop($json['bids']);
		//array_push($json['bids'], $json['currentBid']);
		if(isset($json['currentBid'])){
			debuglog("in getBidHistory the current bid is - " . $json['currentBid']);
			$nextBid = $this->model_auction_bidding->getNextBid($json['currentBid']);
		} else {
			$nextBid = $min_bid; 
		}
		
		$json['nextBid'] = $nextBid;
		debuglog("all of json in getBidHistory");
		debuglog($json);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function BuyRightNow(){
		$auction_id = $this->request->post['auction_id'];
		$json = array();
		$this->load->language('mail/auction');
		// things to do here
		

		// place final winning bid of the Buy Now Price
		$this->load->model('auction/bidding');
		$this->load->model('auction/auction');
		$buyNowPrice = $this->model_auction_auction->getBuyNowPrice($auction_id);
		$thisWinningBid = array(
			'auction_id'	=> $this->db->escape($auction_id),
			'bidder_id'	=>	$this->customer->getId(),
			'bid_amount'	=>	$buyNowPrice['buy_now_price'],
			'proxy_bid_amount'	=> $buyNowPrice['buy_now_price'],
			'winner'			=> '1'
		);
		$result = $this->model_auction_bidding->placeBid($thisWinningBid);

		// move all bids to history
		$this->model_auction_bidding->moveBids2History($auction_id);
		
		// mark auction as closed
		
		$winningInfo = $this->model_auction_auction->closeWonAuction($auction_id);
		//debuglog($winningInfo);
		$winningInfo['type'] = 'buynow';
		//$PostOffice = $this->load->controller('common/postoffice');
		$well = $this->sendMail($winningInfo);
		if($well){
			debuglog("ok responded was sent");
		}

		$json['url'] = "index.php?route=auction/closed_auctions&auction_id=" . $auction_id;

		//$this->redirect($url);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function PlaceBid(){
		$this->load->model('auction/bidding');
		$auction_id = $this->request->post['auction_id'];
		$bidAmount = $this->request->post['bid_amount'];
		$min_bid = $this->request->post['min_bid'];
		$last_bid = $this->model_auction_bidding->getLastBid($auction_id);
		$next_bid = (empty($last_bid))?$min_bid:$this->model_auction_bidding->getNextBid($last_bid['bid_amount']);
		debuglog("auction controller - PlaceBid - next_bid " . $next_bid);
		if(!$bidAmount){
			$bidAmount=$next_bid;
		}
		$json = array();

		$thisBid = array(
			'auction_id'	=> $this->db->escape($auction_id),
			'bidder_id'	=>	$this->customer->getId(),
			'bid_amount'	=>	$next_bid,
			'proxy_bid_amount'	=> $bidAmount
		);
		
		$result = $this->model_auction_bidding->placeBid($thisBid);
		if($this->config->get('config_auction_extension')) {
			if($this->model_auction_bidding->shouldExtendAuction($result)) {
				$this->load->model('auction/auction');
				$json['extend'] = $this->model_auction_auction->extendAuction($auction_id);
			} else {
				$json['extend'] = false;
			}
		} else {
			$json['extend'] = false;
		}
		//debuglog($json);
		// Add to activity log
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('place_bid', $activity_data);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function Limbo(){
		$json = array();

		$auctionId = $this->request->post['auction_id'];
		$this->load->language('mail/auction');
		
		$this->load->model('auction/auction');
		$this->load->model("account/customer");
		$this->load->model('auction/bidding');

		$currentStatus = $this->model_auction_auction->getAuctionStatus($auctionId);
		switch ($currentStatus) {
			case '1':
			$sellerInfo = $this->model_auction_auction->openAuction($auctionId);
			$well = $this->sendMail($sellerInfo);
			if($well){
				debuglog("ok responded was sent about opening");
			}
			break;

			case '2':
			//debuglog("open now what");
			//	if time has run out
			$winningBidder = '';
			if ($this->model_auction_auction->hasExpired($auctionId)) {
				$current_bid = $this->model_auction_bidding->getCurrentBid($auctionId);
				$reserve_bid = $this->model_auction_auction->getReserveBid($auctionId);
				
				$testamount = $current_bid['bid_amount'] - $reserve_bid;
				
				if ($current_bid['bid_amount'] > 0 && $testamount >= 0)  {
					// we have a winner
					$auctionInfo = $this->declareWinner($current_bid);
					$this->model_auction_bidding->moveBids2History($auctionId);
					$winningInfo = $this->model_auction_auction->closeWonAuction($auctionId);
					$auctionInfo['type'] = 'winner';
					$well = $this->sendMail($auctionInfo);
				} elseif ($this->model_auction_auction->isRelistable($auctionId)) {
					$auctionInfo = $this->model_auction_auction->relistAuction($auctionId);
					$seller_info = $this->model_auction_auction->closeAuction($auctionId);
					$this->model_auction_bidding->moveBids2History($auctionId);
					//$seller_info = $this->model_account_customer->getCustomerInfoById($auctionInfo['seller_id']);
					$seller_info['type'] = 'relist';
					$seller_info['link'] = html_entity_decode($this->url->link('auction/edit', 'auction_id=' . $auctionInfo['auction_id'], true) . "\n\n", ENT_QUOTES, 'UTF-8');
					$sellerInfo = array_merge($seller_info, $auctionInfo);
					$auctionInfo['highest_bid'] = $current_bid['bid_amount'];
					$sellerInfo['message'] = $this->getRelistMessage($auctionInfo);
					$this->load->model('fees/fees');
					$this->load->model('bookkeeping/accounting');
					$feeInfo = array(
						'auction_id'		=> $auctionInfo['auction_id'],
						'customer_id'		=> $auctionInfo['seller_id'],
						'fee_code'			=> $this->model_bookkeeping_accounting->getAccountCodeByShortCode('A-relist'),
						'amount'				=> strval($this->config->get('fees_relist_fee'))
					);
					$relistFee = $this->model_fees_fees->addFee($feeInfo);
					$this->model_fees_fees->addFeeToCart($feeInfo);
					//$this->cart->add($auctionInfo['auction_id'], $feeInfo['amount']);
					$well = $this->sendMail($sellerInfo);
				} else {
					// not relistable just close it and send emails
					$sellerInfo = $this->model_auction_auction->closeAuction($auctionId);
					$this->model_auction_bidding->moveBids2History($auctionId);
				}

				//$sellerInfo = $this->model_auction_auction->closeAuction($auctionId);
				//$this->model_auction_bidding->moveBids2History($auctionId);
			}
			
			break;
			
			default:
			debuglog("hmmm this shouldn't happen");
			debuglog($currentStatus);
			$json['redirect'] = "index.php?route=auction/closed_auctions&auction_id=" . $auctionId;
		}

		$json['success'] = 'Yaaaaay it works';
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function declareWinner($bid_data) {
		$this->model_auction_bidding->markBidWon($this->db->escape($bid_data['bid_id']));
		$bidderInfo = $this->model_account_customer->getCustomerInfoById($this->db->escape($bid_data['bidder_id']));
		$auction_data = $this->model_auction_auction->getAuctionInfoOfWinner($this->db->escape($bid_data['auction_id']));
		$sellerInfo = $this->model_account_customer->getCustomerInfoById($auction_data['customer_id']);
		$auction_info['seller_name'] = $sellerInfo['fullname'];
		$auction_info['seller_email'] = $sellerInfo['email_address'];
		$auction_info['bidder_name'] = $bidderInfo['fullname'];
		$auction_info['bidder_email'] = $bidderInfo['email_address'];
		$auction_info['title'] = $auction_data['title'];
		$auction_info['bid_amount'] = $this->db->escape($bid_data['bid_amount']);

		return $auction_info;
	}

	public function getRelistMessage($auction) {
		$relistMessage = '';

		$followingSuggestions = array();
  	if (!$auction['options']['buy_now_only']){
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_1'));
		} else {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_8'));
		}
		if(!$auction['options']['featured']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_2'));
		}
		if(!$auction['options']['bolded_item']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_3'));
		}
		if(!$auction['options']['on_carousel']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_4'));
		}
		if(!$auction['options']['highlighted']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_5'));
		}
		if(!$auction['options']['slideshow']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_6'));
		}
		if(!$auction['options']['social_media']) {
			array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_7'));
		}
		
		array_push($followingSuggestions,'-' . $this->language->get('text_suggestion_10'));

		$relistMessage = sprintf($this->language->get('text_auction_relisted'), $auction['title']) . "\n";
		$relistMessage .= sprintf($this->language->get('text_relist_highest'), $auction['highest_bid']) . "\n";
		$relistMessage .= sprintf($this->language->get('text_relist_timelimit'), $auction['start_date']) . "\n";
		$relistMessage .= sprintf($this->language->get('text_relist_suggestions')) . "\n";
		foreach($followingSuggestions as $followingSuggestion) {
			$relistMessage .= $followingSuggestion . "\n";
		}
		$relistMessage .= $this->language->get('text_thank_you');


		return $relistMessage;
	}

	private function sendMail($mailInfo) {
		if (!isset($mailInfo['type'])) {
			$sendmsg = 'admin';
		} else {
			switch ($mailInfo['type']) {
				case 'opening':
				// mail seller that the auction is open
				$sellerSubject = html_entity_decode($this->language->get('text_subject_opened'), ENT_QUOTES, 'UTF-8');
				$sellerMessage = html_entity_decode(sprintf($this->language->get('text_auction_opened'),$mailInfo['fullname'],$mailInfo['title']), ENT_QUOTES, 'UTF-8');
				$sendmsg = 'seller';
				break;

				case 'buynow':
				// mail to them
				$sellerSubject = html_entity_decode($this->language->get('text_subject_bn_seller'), ENT_QUOTES, 'UTF-8');
				$bidderSubject = html_entity_decode($this->language->get('text_subject_bn_bidder'), ENT_QUOTES, 'UTF-8');
				$sellerMessage = html_entity_decode(sprintf($this->language->get('text_message_bn_seller'),$mailInfo['seller_name'],$mailInfo['title'],$mailInfo['bidder_email']), ENT_QUOTES, 'UTF-8');
				$bidderMessage = html_entity_decode(sprintf($this->language->get('text_message_bn_bidder'),$mailInfo['bidder_name'],$mailInfo['title'],$mailInfo['seller_email']), ENT_QUOTES, 'UTF-8');
				$sendmsg = 'all';
				break;

				case 'relist':
				$sellerSubject = html_entity_decode($this->language->get('text_subject_relisted'), ENT_QUOTES, 'UTF-8');
				$sellerMessage = html_entity_decode($mailInfo['message']/*sprintf($this->language->get('text_message_relist_seller'),$mailInfo['fullname'],$mailInfo['title'],$mailInfo['link'])*/, ENT_QUOTES, 'UTF-8');
				$sendmsg = 'seller';
				break;

				case 'winner':
				$sellerSubject = html_entity_decode($this->language->get('text_subject_auction_won'), ENT_QUOTES, 'UTF-8');
				$bidderSubject = html_entity_decode($this->language->get('text_subject_auction_won'), ENT_QUOTES, 'UTF-8');

				$testSellMessage = $this->language->get('text_auction_won_smsg1') . "\n";
				$testSellMessage .= $this->language->get('text_auction_won_smsg2') . "\n\n";
				$testSellMessage .= $this->language->get('text_auction_won_contact1') . "\n";
				$testSellMessage .= $this->language->get('text_auction_won_contact2');
				$sellerMessage = sprintf($testSellMessage, $mailInfo['seller_name'], $mailInfo['title'], $mailInfo['bid_amount'], $mailInfo['bidder_name'], $mailInfo['bidder_name'], $mailInfo['bidder_name'], $mailInfo['bidder_email']);
				
				$testBidMessage = $this->language->get('text_auction_won_bmsg1') . "\n";
				$testBidMessage .= $this->language->get('text_auction_won_bmsg2') . "\n\n";
				$testBidMessage = $this->language->get('text_auction_won_contact1') . "\n";
				$testBidMessage = $this->language->get('text_auction_won_contact2') . "\n";
				$bidderMessage = sprintf($testBidMessage, $mailInfo['bidder_name'], $mailInfo['title'], $mailInfo['bid_amount'], $mailInfo['seller_name'], $mailInfo['seller_name'], $mailInfo['seller_email']);
				
				$sendmsg = 'all';
				break;

				default:
				$sendmsg = 'admin';
				break;
			}
		}

		if ($sendmsg == 'all') {
			// Send to Seller
			$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
											
						$mail->setTo($mailInfo['seller_email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->language->get('text_sender'));
						$mail->setSubject($sellerSubject);
						$mail->setText($sellerMessage);
						$mail->send();
			// Send to Bidder
			$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
											
						$mail->setTo($mailInfo['bidder_email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->language->get('text_sender'));
						$mail->setSubject($bidderSubject);
						$mail->setText($bidderMessage);
						$mail->send();
						$sent = true;
		} elseif($sendmsg == 'seller') {
			// Send to Seller
			$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
											
						$mail->setTo($mailInfo['email_address']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->language->get('text_sender'));
						$mail->setSubject($sellerSubject);
						$mail->setText($sellerMessage);
						$mail->send();
						$sent = true;
			} else {
				// send to admin
				$mail = new Mail();
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
												
							$mail->setTo($this->config->get('config_email'));
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender($this->language->get('text_sender'));
							$mail->setSubject('Something went wrong');
							$mail->setText("I don't know what but something went wrong");
							$mail->send();
							$sent = true;
			}
		return $sent;
	}

	public function checkForNewBids() {
		$json = array();
		$json['newBids'] = false;
		$auctionId = $this->request->get['auction_id'];
		$currentNumberOfBids = $this->request->get['num_bids'];
		$sql = "SELECT COUNT(*) AS bidCount FROM " . DB_PREFIX . "current_bids WHERE auction_id = '" . $auctionId . "'";
		$query = $this->db->query($sql)->row;
		debuglog($query['bidCount']);
		debuglog("current num bids");
		debuglog($currentNumberOfBids);
		if($query['bidCount'] > $currentNumberOfBids) {
			$json['newBids'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function isThisAuctionOpen(){
		$this->load->model('auction/auction');
		$status = $this->model_auction_auction->getAuctionStatus($this->request->get['auction_id']);
		if ($status == '2') {
			return true;
		} else {
			return false;
		}
	}
	// end of controller
}
