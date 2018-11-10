<?php
class ControllerExtensionDashboardRecent extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/recent');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_recent', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/recent', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/recent', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_recent_width'])) {
			$data['dashboard_recent_width'] = $this->request->post['dashboard_recent_width'];
		} else {
			$data['dashboard_recent_width'] = $this->config->get('dashboard_recent_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_recent_status'])) {
			$data['dashboard_recent_status'] = $this->request->post['dashboard_recent_status'];
		} else {
			$data['dashboard_recent_status'] = $this->config->get('dashboard_recent_status');
		}

		if (isset($this->request->post['dashboard_recent_sort_order'])) {
			$data['dashboard_recent_sort_order'] = $this->request->post['dashboard_recent_sort_order'];
		} else {
			$data['dashboard_recent_sort_order'] = $this->config->get('dashboard_recent_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/recent_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/analytics/google_analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/recent');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_auction_id'] = $this->language->get('column_auction_id');
		$data['column_seller'] = $this->language->get('column_seller');
		$data['column_reserve'] = $this->language->get('column_reserve');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_buy_now'] = $this->language->get('column_buy_now');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['auctions'] = array();

		$filter_data = array(
			'sort'  => 'a.date_created',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		$this->load->model('catalog/auction');
		
		$results = $this->model_catalog_auction->getAuctions($filter_data);

		foreach ($results as $result) {
			$data['auctions'][] = array(
				'auction_id'   => $result['auction_id'],
				'seller'   => $result['seller'],
				'reserve_bid'     => $this->currency->format($result['reserve_price'],$this->config->get('config_currency')),
				'date_created' => date($this->language->get('datetime_format'), strtotime($result['date_created'])),
				'buy_now'      => $this->currency->format($result['buy_now_price'], $this->config->get('config_currency')),
				'view'       => $this->url->link('catalog/auction/edit', 'token=' . $this->session->data['token'] . '&auction_id=' . $result['auction_id'], true),
			);
		}

		return $this->load->view('extension/dashboard/recent_info', $data);
	}
}