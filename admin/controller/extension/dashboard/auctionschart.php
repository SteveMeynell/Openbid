<?php
class ControllerExtensionDashboardAuctionschart extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/auctionschart');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_auctionschart', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/auctionschart', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/auctionschart', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_auctionschart_width'])) {
			$data['dashboard_auctionschart_width'] = $this->request->post['dashboard_auctionschart_width'];
		} else {
			$data['dashboard_auctionschart_width'] = $this->config->get('dashboard_auctionschart_width');
		}
	
		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_auctionschart_status'])) {
			$data['dashboard_auctionschart_status'] = $this->request->post['dashboard_auctionschart_status'];
		} else {
			$data['dashboard_auctionschart_status'] = $this->config->get('dashboard_auctionschart_status');
		}

		if (isset($this->request->post['dashboard_auctionschart_sort_order'])) {
			$data['dashboard_auctionschart_sort_order'] = $this->request->post['dashboard_auctionschart_sort_order'];
		} else {
			$data['dashboard_auctionschart_sort_order'] = $this->config->get('dashboard_auctionschart_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/auctionschart_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/auctionschart')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	
	
	public function dashboard() {
		$this->load->language('extension/dashboard/auctionschart');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_day'] = $this->language->get('text_day');
		$data['text_week'] = $this->language->get('text_week');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		return $this->load->view('extension/dashboard/auctionschart_info', $data);
	}

	public function chart() {
		$this->load->language('extension/dashboard/auctionschart');

		$json = array();

		$this->load->model('report/auction');

		$json['openauction'] = array();
		$json['closedauction'] = array();
		$json['xaxis'] = array();

		$json['openauction']['label'] = $this->language->get('text_openauction');
		$json['closedauction']['label'] = $this->language->get('text_closedauction');
		$json['openauction']['data'] = array();
		$json['closedauction']['data'] = array();

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_report_auction->getTotalClosedAuctionsByDay();

				foreach ($results as $key => $value) {
					$json['closedauction']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_auction->getTotalOpenAuctionsByDay();

				foreach ($results as $key => $value) {
					$json['openauction']['data'][] = array($key, $value['total']);
				}


				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = array($i, $i);
				}
				break;
			case 'week':
				$results = $this->model_report_auction->getTotalClosedAuctionsByWeek();

				foreach ($results as $key => $value) {
					$json['closedauction']['data'][] = array($key, $value['total']);
				}
				
				$results = $this->model_report_auction->getTotalOpenAuctionsByWeek();

				foreach ($results as $key => $value) {
					$json['openauction']['data'][] = array($key, $value['total']);
				}



				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$json['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}
				break;
			case 'month':
				$results = $this->model_report_auction->getTotalClosedAuctionsByMonth();

				foreach ($results as $key => $value) {
					$json['closedauction']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_auction->getTotalOpenAuctionsByMonth();

				foreach ($results as $key => $value) {
					$json['openauction']['data'][] = array($key, $value['total']);
				}
				

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$json['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
				}
				break;
			case 'year':
				$results = $this->model_report_auction->getTotalClosedAuctionsByYear();

				foreach ($results as $key => $value) {
					$json['closedauction']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_auction->getTotalOpenAuctionsByYear();

				foreach ($results as $key => $value) {
					$json['openauction']['data'][] = array($key, $value['total']);
				}
				

				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}