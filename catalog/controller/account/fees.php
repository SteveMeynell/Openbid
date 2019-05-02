<?php
class ControllerAccountFees extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/fees', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/fees');

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
			'text' => $this->language->get('text_transaction'),
			'href' => $this->url->link('account/fees', '', true)
		);

		$this->load->model('account/fees');
		$this->load->model('bookkeeping/accounting');
		$this->load->model('account/auction');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_fee_title'] = $this->language->get('column_fee_title');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_auction_title'] = $this->language->get('column_auction_title');
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['transactions'] = array();

		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$transaction_total = $this->model_account_fees->getMyTotalFees();
		/*
		I have to fix this.  What I am looking for is fees that are in the current cart to be displayed as unpaid and historic 
		fees displayed as paid.
		*/

		$results = $this->model_account_fees->getMyFees($filter_data);

		foreach ($results as $result) {
			$data['transactions'][] = array(
				'auction_id'	=> $this->model_account_auction->getAuctionTitle($result['auction_id']),
				'amount'      		=> $this->currency->format($this->model_account_fees->getTotalAmountByAuction($result['auction_id']), $this->config->get('config_currency')),
				'description' 		=> $this->model_bookkeeping_accounting->getAccountDescriptionByAccount(substr($result['fee_code'], 0, 2) . '10'),
				'date_added'  		=> date($this->language->get('date_format_long'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/fees', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_total - 10)) ? $transaction_total : ((($page - 1) * 10) + 10), $transaction_total, ceil($transaction_total / 10));

		$data['total'] = $this->currency->format($this->customer->getBalance(), $this->session->data['currency']);

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/fees', $data));
	}
}