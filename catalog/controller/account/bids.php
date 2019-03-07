<?php
class ControllerAccountBids extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/bids', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->addStyle("bids.css");
		$this->document->addScript("bids.js");
		$this->load->language('account/bids');

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
			'href' => $this->url->link('account/bids', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

    $data['column_bid_id'] = $this->language->get('column_bid_id');
		$data['column_auction_id'] = $this->language->get('column_auction_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_status'] = $this->language->get('column_status');
    $data['column_bid_date'] = $this->language->get('column_bid_date');
    $data['column_winner'] = $this->language->get('column_winner');
    $data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['bids'] = array();

    $this->load->model('account/bid');
    $this->load->model('account/auction');

		$bid_totals = $this->model_account_bid->getTotalBids();
		$bid_total = $bid_totals['currentTotal'] + $bid_totals['historyTotal'];

		$results = $this->model_account_bid->getBids(($page - 1) * 10, 10);
		//debuglog("Results:");
		//debuglog($results);
		foreach ($results['history'] as $auctions) {
			foreach ($auctions as $bid) {
			
				if($bid['status'] == '2') {
					$view = $this->url->link('auction/auction', 'auction_id=' . $bid['auction_id'], true);
				} elseif($bid['status'] == '3'){
					$view = $this->url->link('auction/closed_auctions', 'auction_id=' . $bid['auction_id'], true);
				} else {
					$view = $this->url->link('account/bids', '', true);
				}
			/*
      $data['bids'][] = array(
        'bid_id' => $result['bid_id'],
        'auction_id' => $result['auction_id'],
        'title' => $result['title'],
        'status'  => $this->model_account_auction->getAuctionStatusByType($result['status']),
        'bid_amount'  => $result['bid_amount'],
        'bid_date'    => $result['bid_date'],
        'winner' => ($result['winner'])?($result['bidder_id']==$result['proxy_bidder_id'])?'You':$result['proxy_bidder_id']:'Did Not Reach Reserve Bid',
        'view'        => $view
			);
			*/
				$data['bids']['history'][] = array(
					'bid_id'				=> $bid['bid_id'],
					'auction_id'		=> $bid['auction_id'],
					'title'					=> $bid['title'],
					'bid_amount'		=> $bid['bid_amount'],
					'bid_date'			=> $bid['bid_date'],
					'winner'				=> $bid['winner'],
					'view'					=> $view
				);
			}
		}
//debuglog($data['bids']);


    $pagination = new Pagination();
		$pagination->total = $bid_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/bids', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($bid_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($bid_total - 10)) ? $bid_total : ((($page - 1) * 10) + 10), $bid_total, ceil($bid_total / 10));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/bid_list', $data));
	
  }

	


  // End of Controller
}