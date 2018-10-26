<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_auction'] = $this->language->get('text_auction');
		$data['text_created_auction_status'] = $this->language->get('text_created_auction_status');
		$data['text_open_auction_status'] = $this->language->get('text_open_auction_status');
		$data['text_closed_auction_status'] = $this->language->get('text_closed_auction_status');
		$data['text_moderation_status'] = $this->language->get('text_moderation_status');
		$data['text_suspended_status'] = $this->language->get('text_suspended_status');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_bidders_only'] = $this->language->get('text_bidders_only');
		$data['text_sellers_only'] = $this->language->get('text_sellers_only');
		$data['text_bidders_sellers'] = $this->language->get('text_bidders_sellers');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_front'] = $this->language->get('text_front');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_homepage'] = $this->language->get('text_homepage');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_logout'] = $this->language->get('text_logout');

		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/dashboard', '', true);
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);

			// Auctions
			$this->load->model('catalog/auction');
			
			// Open Auctions
			$auction_totals = $this->model_catalog_auction->getTotalAuctions(array('dashboard' => 'true'));
			
			$data['moderation_status_total'] = $auction_totals['0'];
			$data['moderation_status'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['created_auction_status_total'] = $auction_totals['1'];
			$data['created_auction_status'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['open_auction_status_total'] = $auction_totals['2'];
			$data['open_auction_status'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['closed_auction_status_total'] = $auction_totals['3'];
			$data['closed_auction_status'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['suspended_status_total'] = $auction_totals['4'];
			$data['suspended_status'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['auction'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			$data['auction_total'] = '0';
//debuglog("Need to fix this... header controller");
			// Customers
			$this->load->model('report/customer');

			$data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

			$data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
			
			$data['bidders_only_total'] = $this->model_report_customer->getTotalCustomersByGroup(array('filter_group' => '1'));
			
			$data['bidders_only'] = $this->url->link('report/customer_bidders', 'token=' . $this->session->data['token'], true);
			
			$data['sellers_only_total'] = $this->model_report_customer->getTotalCustomersByGroup(array('filter_group' => '2'));
																								 
			$data['sellers_only'] = $this->url->link('report/customer_sellers', 'token=' . $this->session->data['token'], true);
			
			$data['bidders_sellers_total'] = $this->model_report_customer->getTotalCustomersByGroup();
																								 
			$data['bidders_sellers'] = $this->url->link('report/customer_both', 'token=' . $this->session->data['token'], true);

			$this->load->model('customer/customer');

			$customer_total = $this->model_customer_customer->getTotalCustomers(array('filter_approved' => false));

			$data['customer_total'] = $customer_total;
			$data['customer_approval'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', true);

			// Auctions
			$this->load->model('catalog/auction');

			$auction_total = $this->model_catalog_auction->getTotalAuctions(array('filter_auction_status' => 0));

			$data['auction_total'] = $auction_total;

			$data['auction'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&filter_quantity=0', true);

			// Reviews
			$this->load->model('catalog/review');

			$review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => 0));

			$data['review_total'] = $review_total;

			$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', true);

			// Affliate
			$this->load->model('marketing/affiliate');

			$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates(array('filter_approved' => false));

			$data['affiliate_total'] = $affiliate_total;
			$data['affiliate_approval'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=1', true);

			$data['alerts'] = $customer_total + $auction_total + $review_total + $affiliate_total;

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}

		return $this->load->view('common/header', $data);
	}
}
