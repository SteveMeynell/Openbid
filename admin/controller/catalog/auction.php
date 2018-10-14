<?php
class ControllerCatalogAuction extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/auction');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/auction');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_auction->addAuction($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

// fill out auction stuff			
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_auction_status'])) {
				$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
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

			$this->response->redirect($this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/auction');

		
/* POSTING HERE
 *
 *
*/
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			// testing
			$testdata = $this->request->post;
			$allow_custom_start_date = $this->config->get('config_auction_custom_start_date');
			$allow_custom_end_date = $this->config->get('config_auction_custom_end_date');
			
			if(!$allow_custom_start_date && !isset($testdata['custom_start_date'])){
					$testdata['custom_start_date'] = date("Y-m-d H:i:s");
			}
			if(!$allow_custom_end_date) {
				$myStartDate = date_create($testdata['custom_start_date']);
				date_add($myStartDate, date_interval_create_from_date_string(($testdata['duration'] * 24) . ' hours'));
				$myEndDate = get_object_vars($myStartDate);
				$testdata['custom_end_date'] = $myEndDate['date'];
			}
			
			if($allow_custom_end_date && !isset($testdata['duration'])){
				$myStartDate = date_create($testdata['custom_start_date']);
				$myEndDate = date_create($testdata['custom_end_date']);
				$myDuration = date_diff($myStartDate, $myEndDate);
				$testdata['duration'] = $myDuration->days;
			}
			
			
			
			$this->model_catalog_auction->editAuction($this->request->get['auction_id'], $testdata);
			//$this->model_catalog_auction->editAuction($this->request->get['auction_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

// Fill out auction stuff here			
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_auction_status'])) {
				$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
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

			$this->response->redirect($this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true));
		}
// End Posting here
		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/auction');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $auction_id) {
				$this->model_catalog_auction->deleteAuction($auction_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

// Fill out auction stuff here			
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_auction_status'])) {
				$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
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

			$this->response->redirect($this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/auction');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $auction_id) {
				$this->model_catalog_auction->copyAuction($auction_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

// Fill out auction stuff here			
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_auction_status'])) {
				$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
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

			$this->response->redirect($this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_auction_id'])) {
			$filter_auction_id = $this->request->get['filter_auction_id'];
		} else {
			$filter_auction_id = null;
		}

		if (isset($this->request->get['filter_seller'])) {
			$filter_seller = $this->request->get['filter_seller'];
		} else {
			$filter_seller = null;
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$filter_auction_status = $this->request->get['filter_auction_status'];
		} else {
			$filter_auction_status = null;
		}

		if (isset($this->request->get['filter_auction_type'])) {
			$filter_auction_type = $this->request->get['filter_auction_type'];
		} else {
			$filter_auction_type = null;
		}
		
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
		}

		if (isset($this->request->get['filter_date_created'])) {
			$filter_date_created = $this->request->get['filter_date_created'];
		} else {
			$filter_date_created = null;
		}

		if (isset($this->request->get['filter_start_date'])) {
			$filter_start_date = $this->request->get['filter_start_date'];
		} else {
			$filter_start_date = null;
		}
		
		if (isset($this->request->get['filter_end_date'])) {
			$filter_end_date = $this->request->get['filter_end_date'];
		} else {
			$filter_end_date = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			debuglog($sort);
		} else {
			$sort = 'a.auction_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_auction_id'])) {
			$url .= '&filter_auction_id=' . $this->request->get['filter_auction_id'];
		}

		if (isset($this->request->get['filter_seller'])) {
			$url .= '&filter_seller=' . urlencode(html_entity_decode($this->request->get['filter_seller'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_auction_type'])) {
			$url .= '&filter_auction_type=' . $this->request->get['filter_auction_type'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['auction'])) {
			$url .= '&auction=' . $this->request->get['auction'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true)
		);

		//$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], true);
		//$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('catalog/auction/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('catalog/auction/delete', 'token=' . $this->session->data['token'], true);

		$data['auctions'] = array();

		$filter_data = array(
			'filter_auction_id'      => $filter_auction_id,
			'filter_seller'	   => $filter_seller,
			'filter_auction_status'  => $filter_auction_status,
			'filter_auction_type'         => $filter_auction_type,
			'filter_date_created'    => $filter_date_created,
			'filter_start_date' => $filter_start_date,
			'filter_end_date' => $filter_end_date,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		

		$results = $this->model_catalog_auction->getAuctions($filter_data);
		foreach ($results as $result) {
			$data['auctions'][] = array(
				'auction_id'        => $result['auction_id'],
				'image'				=>HTTP_CATALOG . 'image/no_image.png',
				'type'				=> $result['auction_type'] ? 'Dutch':'Regular',
				'seller'            => ucwords($result['firstname'] . ' ' . $result['lastname']),
				'auction_status'    => $result['status_name'] ? $result['status_name'] : $this->language->get('text_missing'),
				'title'       		=> $result['title'],
				'date_created'      => date($this->language->get('date_format_short'), strtotime($result['date_created'])),
				'start_date'        => date($this->language->get('datetime_format'), strtotime($result['start_date'])),
                'end_date'          => date($this->language->get('datetime_format'), strtotime($result['end_date'])),
				'view'              => $this->url->link('catalog/auction/info', 'token=' . $this->session->data['token'] . '&auction_id=' . $result['auction_id'] . $url, true),
				'edit'              => $this->url->link('catalog/auction/edit', 'token=' . $this->session->data['token'] . '&auction_id=' . $result['auction_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_regular'] = $this->language->get('text_regular');
		$data['text_dutch'] = $this->language->get('text_dutch');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_seller'] = $this->language->get('column_seller');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_createdate'] = $this->language->get('column_createdate');
		$data['column_startdate'] = $this->language->get('column_startdate');
		$data['column_enddate'] = $this->language->get('column_enddate');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_seller'] = $this->language->get('entry_seller');
		$data['entry_auction_status'] = $this->language->get('entry_auction_status');
		$data['entry_date_created'] = $this->language->get('entry_date_created');
		$data['entry_start_date'] = $this->language->get('entry_start_date');
		$data['entry_end_date'] = $this->language->get('entry_end_date');

		//$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		//$data['button_shipping_print'] = $this->language->get('button_shipping_print');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_auction_id'])) {
			$url .= '&filter_auction_id=' . $this->request->get['filter_auction_id'];
		}

		if (isset($this->request->get['filter_seller'])) {
			$url .= '&filter_seller=' . urlencode(html_entity_decode($this->request->get['filter_seller'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_auction'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=a.auction_id' . $url, true);
		$data['sort_type'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=a.auction_type' . $url, true);
		$data['sort_seller'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=seller' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=a.status' . $url, true);
		$data['sort_title'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=ad.title' . $url, true);
		$data['sort_createdate'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=a.date_created' . $url, true);
		$data['sort_startdate'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=ad.start_date' . $url, true);
		$data['sort_enddate'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . '&sort=ad.end_date' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_auction_id'])) {
			$url .= '&filter_auction_id=' . $this->request->get['filter_auction_id'];
		}

		if (isset($this->request->get['filter_seller'])) {
			$url .= '&filter_seller=' . urlencode(html_entity_decode($this->request->get['filter_seller'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}
		
		if (isset($this->request->get['filter_end_date'])) {
			$url .= '&filter_end_date=' . $this->request->get['filter_end_date'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$auction_total = $this->model_catalog_auction->getTotalAuctions($filter_data);

		$pagination = new Pagination();
		$pagination->total = $auction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($auction_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($auction_total - $this->config->get('config_limit_admin'))) ? $auction_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $auction_total, ceil($auction_total / $this->config->get('config_limit_admin')));

		$data['filter_auction_id'] = $filter_auction_id;
		$data['filter_seller'] = $filter_seller;
		$data['filter_auction_status'] = $filter_auction_status;
		$data['filter_type'] = $filter_type;
		$data['filter_date_created'] = $filter_date_created;
		$data['filter_start_date'] = $filter_start_date;
		$data['filter_end_date'] = $filter_end_date;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('localisation/auction_status');

		$data['auction_statuses'] = $this->model_localisation_auction_status->getAuctionStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/auction_list', $data));
	}

/*
 *
 *
 *		Start Here
 *
 *
 *
 */
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['auction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		// Tabs
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_seller'] = $this->language->get('tab_seller');
		$data['tab_bidding'] = $this->language->get('tab_bidding');
		$data['tab_reviews'] = $this->language->get('tab_reviews');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_fees'] = $this->language->get('tab_fees');
		
		
		// Tab General ****************************************************
		// Entry items on General Tab
		$data['entry_name'] = $this->language->get('entry_name');
		// if admin allows
		$data['entry_subname'] = $this->language->get('entry_subname');
		//
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_tag'] = $this->language->get('entry_tag');
		
		// Help items on General Tab
		$data['help_tag'] = $this->language->get('help_tag');
		$data['help_keyword'] = $this->language->get('help_keyword');
		
		// End of General Tab ********************************************
		
		// Tab Data ******************************************************
		// Entry items on Data Tab
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_auction_status'] = $this->language->get('entry_auction_status');
		$data['entry_min_bid'] = $this->language->get('entry_min_bid');
		$data['entry_reserve_price'] = $this->language->get('entry_reserve_price');
		// If admin allows
		$data['entry_auto_relist'] = $this->language->get('entry_auto_relist');
		$data['entry_auto_relist_times'] = $this->language->get('entry_auto_relist_times');
		//
		// these only available if custom start and end date is allowed by admin
		$data['entry_start_date'] = $this->language->get('entry_start_date');
		$data['entry_end_date'] = $this->language->get('entry_end_date');
		// else set durations available
		$data['entry_duration'] = $this->language->get('entry_duration');
		//
		// If admin allows
		$data['entry_increments'] = $this->language->get('entry_increments');
		$data['entry_bid_from'] = $this->language->get('entry_bid_from');
		$data['entry_bid_to'] = $this->language->get('entry_bid_to');
		$data['entry_bid_increment'] = $this->language->get('entry_bid_increment');
		//
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_international_shipping'] = $this->language->get('entry_international_shipping');
		$data['entry_shipping_amount'] = $this->language->get('entry_shipping_amount');
		$data['entry_additional_shipping'] = $this->language->get('entry_addtional_shipping');
		// Help items on Data
		$data['help_type'] = $this->language->get('help_type');
		$data['help_min_bid'] = $this->language->get('help_min_bid');
		$data['help_price'] = $this->language->get('help_price');
		$data['help_reserve_price'] = $this->language->get('help_reserve_price');
		$data['help_auto_relist'] = $this->language->get('help_auto_relist');
		$data['help_auto_relist_times'] = $this->language->get('help_auto_relist_times');
		$data['help_custom_dates'] = $this->language->get('help_custom_dates');
		$data['help_increments'] = $this->language->get('help_increments');
		
		
		// End of Tab Data ************************************************
		
		// Tab Links
		// Entry items for tab Links
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_buy_now'] = $this->language->get('entry_buy_now');
		// if buy now selected
		$data['entry_buy_now_price'] = $this->language->get('entry_buy_now_price');
		//
		$data['entry_bolded'] = $this->language->get('entry_bolded');
		$data['entry_on_carousel'] = $this->language->get('entry_on_carousel');
		$data['entry_featured'] = $this->language->get('entry_featured');
		$data['entry_highlighted'] = $this->language->get('entry_highlighted');
		$data['entry_slideshow'] = $this->language->get('entry_slideshow');
		$data['entry_social_media'] = $this->language->get('entry_social_media');
		
		// Help items for tab Links
		$data['help_category'] = $this->language->get('help_category');
		$data['help_buy_now'] = $this->language->get('help_buy_now');
		$data['help_bolded'] = $this->language->get('help_bolded');
		$data['help_on_carousel'] = $this->language->get('help_on_carousel');
		$data['help_featured'] = $this->language->get('help_featured');
		$data['help_highlighted'] = $this->language->get('help_highlighted');
		$data['help_slideshow'] = $this->language->get('help_slideshow');
		$data['help_social_media'] = $this->language->get('help_social_media');
		
		
		// End of Tab Links ***********************************************
		
		// Tab Images
		// Entry items for tab Image
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_additional_image'] = $this->language->get('entry_additional_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		
		// End of Images Tab **********************************************
		
		
		// Sellers Info Tab
		// Entry items for tab Seller
		$data['entry_sellers_firstname'] = $this->language->get('entry_sellers_firstname');
		$data['entry_sellers_lastname'] = $this->language->get('entry_sellers_lastname');
		// Text items for tab Seller
		$data['text_sales_history'] = $this->language->get('text_sales_history');
		$data['text_bidding_history'] = $this->language->get('text_bidding_history');
		// Sale History columns
		$data['column_item'] = $this->language->get('column_item');
		$data['column_date_sold'] = $this->language->get('column_date_sold');
		$data['column_amount_sold'] = $this->language->get('column_amount_sold');
		$data['column_num_relisted'] = $this->language->get('column_num_relisted');
		$data['column_winning_bidder'] = $this->language->get('column_winning_bidder');
		$data['column_highest_bid'] = $this->language->get('column_highest_bid');
		$data['column_date_placed'] = $this->language->get('column_date_placed');
		$data['column_won_lost'] = $this->language->get('column_won_lost');
		
		
		// End of Seller Info Tab ******************************************
		
		
		// Bidding History Tab
		// Entry items for tab bidding
		// Text items for tab bidding
		// uses the bidding history text item from seller tab
		$data['text_closing_date'] = $this->language->get('text_closing_date');
		$data['text_reserved_bid'] = $this->language->get('text_reserved_bid');
		// Bid History Columns
		$data['column_bid_name'] = $this->language->get('column_bid_name');
		$data['column_bid_date'] = $this->language->get('column_bid_date');
		$data['column_bid_amount'] = $this->language->get('column_bid_amount');
		$data['column_bid_proxy'] = $this->language->get('column_bid_proxy');
		
		
		// End of Bid History Tab
		
		// Reviews and Complaints Tab
		// Entry items for reviews tab
		
		// text items for reviews tab
		
		// help items for reviews tab
		
		
		// End of Reviews and Complaints Tab
		
		
		// Design Tab
		// Entry items for design tab
		$data['entry_layout'] = $this->language->get('entry_layout');
		// already have this $data['entry_store'] = $this->language->get('entry_store');
		
		// text items for design tab
		
		// help items for design tab
		
		
		// End of Design Tab
		
		// Fees Tab
		// Columns
		$data['column_fee_name'] = $this->language->get('column_fee_name');
		$data['column_fee_amount'] = $this->language->get('column_fee_amount');
		$data['column_fee_date'] = $this->language->get('column_fee_date');
		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['auction_id'])) {
			$data['action'] = $this->url->link('catalog/auction/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/auction/edit', 'token=' . $this->session->data['token'] . '&auction_id=' . $this->request->get['auction_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/auction', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['auction_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$auction_info = $this->model_catalog_auction->getAuction($this->request->get['auction_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['auction_description'])) {
			$data['auction_description'] = $this->request->post['auction_description'];
		} elseif (isset($this->request->get['auction_id'])) {
			$data['auction_description'] = $this->model_catalog_auction->getAuctionDescriptions($this->request->get['auction_id']);
		} else {
			$data['auction_description'] = array();
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($auction_info)) {
			$data['keyword'] = $auction_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
		
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['auction_store'])) {
			$data['auction_store'] = $this->request->post['auction_store'];
		} elseif (isset($this->request->get['auction_id'])) {
			$data['auction_store'] = $this->model_catalog_auction->getAuctionStores($this->request->get['auction_id']);
		} else {
			$data['auction_store'] = array(0);
		}
		
		$this->load->model('auction/auction_setting');
		$data['types'] = $this->model_auction_auction_setting->getAuctionTypes();
		$data['statuses'] = $this->model_auction_auction_setting->getAuctionStatuses();

	
		if(isset($this->request->post['auction_type'])) {
			$data['auction_type'] = $this->request->post['auction_type'];
		} elseif (isset($this->request->get['auction_id'])) {
			$data['auction_type'] = $this->model_catalog_auction->getAuctionTypes($this->request->get['auction_id']);
		} else {
			$data['auction_type'] = array(0);
		}

		 
		if (isset($this->request->post['auction_status'])) {
			$data['auction_status'] = $this->request->post['auction_status'];
		} elseif (!empty($auction_info)) {
			$data['auction_status'] = $auction_info['status'];
		} else {
			$data['auction_status'] = '0';
		}
		
		if (isset($this->request->post['min_bid'])) {
			$data['min_bid'] = $this->request->post['min_bid'];
		} elseif (!empty($auction_info)) {
			$data['min_bid'] = $auction_info['min_bid'];
		} else {
			$data['min_bid'] = '';
		}
		
		if (isset($this->request->post['reserve_price'])) {
			$data['reserve_price'] = $this->request->post['reserve_price'];
		} elseif (!empty($auction_info)) {
			$data['reserve_price'] = $auction_info['reserve_price'];
		} else {
			$data['reserve_price'] = '';
		}
		
		$data['allow_auto_relist'] = $this->config->get('config_auction_auto_relist');
		if (isset($this->request->post['auto_relist'])) {
			$data['auto_relist'] = $this->request->post['auto_relist'];
		} elseif (!empty($auction_info)) {
			$data['auto_relist'] = $auction_info['relisted'];
		} else {
			$data['auto_relist'] = '';
		}
		
		if (isset($this->request->post['num_relist'])) {
			$data['num_relist'] = $this->request->post['num_relist'];
		} elseif (!empty($auction_info)) {
			$data['num_relist'] = $auction_info['relist'];
		} else {
			$data['num_relist'] = '';
		}

		$data['allow_custom_start_date'] = $this->config->get('config_auction_custom_start_date');
		
		if (isset($this->request->post['custom_start_date'])) {
			$data['custom_start_date'] = $this->request->post['custom_start_date'];
		} elseif ($auction_info['start_date']) {
			$data['custom_start_date'] = $auction_info['start_date'];
		} else {
			$data['custom_start_date'] = '';
		}
		
		$data['allow_custom_end_date'] = $this->config->get('config_auction_custom_end_date');
		if (isset($this->request->post['custom_end_date'])) {
			$data['custom_end_date'] = $this->request->post['custom_end_date'];
		} elseif ($auction_info['end_date']) {
			$data['custom_end_date'] = $auction_info['end_date'];
		} else {
			$data['custom_end_date'] = '';
		}
		
		// $data['custom_end_date'] = date_add(date_create($data['custom_start_date']),$this->request->post['duration']);
		
		if(!$data['allow_custom_end_date']) {
			$this->load->model('auction/auction_duration');
			$filter = '';
			$data['durations'] = $this->model_auction_auction_duration->getAllDurations($filter);
		}
		
		if (isset($this->request->post['duration'])) {
			$data['duration'] = $this->request->post['duration'];
		} elseif ($auction_info['duration']) {
			$data['duration'] = $auction_info['duration'];
		} else {
			$data['duration'] = '';
		}
		
		
		
		if (isset($this->request->post['shipping'])) {
			$data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($auction_info)) {
			$data['shipping'] = $auction_info['shipping'];
		} else {
			$data['shipping'] = 1;
		}

		if (isset($this->request->post['start_date'])) {
			$data['start_date'] = $this->request->post['start_date'];
		} elseif (!empty($auction_info)) {
			$data['start_date'] = ($auction_info['start_date'] != '0000-00-00') ? $auction_info['start_date'] : '';
		} else {
			$data['start_date'] = date('Y-m-d H:i:s');
		}


		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}


		


		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['auction_category'])) {
			$categories = $this->request->post['auction_category'];
		} elseif (isset($this->request->get['auction_id'])) {
			$categories = $this->model_catalog_auction->getAuctionCategories($this->request->get['auction_id']);
		} else {
			$categories = array();
		}

		$data['auction_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['auction_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}


		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		
		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($auction_info)) {
			$data['image'] = $auction_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($auction_info) && is_file(DIR_IMAGE . $auction_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($auction_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		// Images
		if (isset($this->request->post['auction_image'])) {
			$auction_images = $this->request->post['auction_image'];
		} elseif (isset($this->request->get['auction_id'])) {
			$auction_images = $this->model_catalog_auction->getAuctionImages($this->request->get['auction_id']);
		} else {
			$auction_images = array();
		}

		$data['auction_images'] = array();

		foreach ($auction_images as $auction_image) {
			if (is_file(DIR_IMAGE . $auction_image['image'])) {
				$image = $auction_image['image'];
				$thumb = $auction_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['auction_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $auction_image['sort_order']
			);
		}


/*		if (isset($this->request->post['auction_related'])) {
			$auctions = $this->request->post['auction_related'];
		} elseif (isset($this->request->get['auction_id'])) {
			$auctions = $this->model_catalog_auction->getAuctionRelated($this->request->get['auction_id']);
		} else {
			$auctions = array();
		}

		$data['auction_relateds'] = array();

		foreach ($auctions as $auction_id) {
			$related_info = $this->model_catalog_auction->getAuction($auction_id);

			if ($related_info) {
				$data['auction_relateds'][] = array(
					'auction_id' => $related_info['auction_id'],
					'name'       => $related_info['name']
				);
			}
		}

		if (isset($this->request->post['points'])) {
			$data['points'] = $this->request->post['points'];
		} elseif (!empty($auction_info)) {
			$data['points'] = $auction_info['points'];
		} else {
			$data['points'] = '';
		}

		if (isset($this->request->post['auction_reward'])) {
			$data['auction_reward'] = $this->request->post['auction_reward'];
		} elseif (isset($this->request->get['auction_id'])) {
			$data['auction_reward'] = $this->model_catalog_auction->getAuctionRewards($this->request->get['auction_id']);
		} else {
			$data['auction_reward'] = array();
		}
*/
		if (isset($this->request->post['auction_layout'])) {
			$data['auction_layout'] = $this->request->post['auction_layout'];
		} elseif (isset($this->request->get['auction_id'])) {
			$data['auction_layout'] = $this->model_catalog_auction->getAuctionLayouts($this->request->get['auction_id']);
		} else {
			$data['auction_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
//debuglog($data);
		$this->response->setOutput($this->load->view('catalog/auction_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/auction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['auction_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}
		
		// *************************************************************************************************************************************
		// check that the end date is later than the start date.  if custom start dates are not allowed then the start date is NOW,
		// end date must be after this.  If custom end dates are not allowed then duration must exist so that it can be added to the start date.
		// *************************************************************************************************************************************

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['auction_id']) && $url_alias_info['query'] != 'auction_id=' . $this->request->get['auction_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['auction_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/auction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/auction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_seller'])) {
			$this->load->model('catalog/auction');
			//$this->load->model('catalog/option');

			if (isset($this->request->get['filter_seller'])) {
				$filter_seller = $this->request->get['filter_seller'];
			} else {
				$filter_seller = '';
			}
/*
			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}
*/
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_seller'  => $filter_seller,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_auction->getAuctions($filter_data);

			foreach ($results as $result) {
/*				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}
*/
				$json[] = array(
					'auction_id' => $result['auction_id'],
					//'seller'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'auction_id'        => $result['auction_id'],
					'image'				=>HTTP_CATALOG . 'image/no_image.png',
					'type'				=> $result['auction_type'] ? 'Dutch':'Regular',
					'seller'            => ucwords($result['firstname'] . ' ' . $result['lastname']),
					'auction_status'    => $result['status_name'] ? $result['status_name'] : $this->language->get('text_missing'),
					'title'       		=> $result['title'],
					'date_created'      => date($this->language->get('date_format_short'), strtotime($result['date_created'])),
					'start_date'        => date($this->language->get('date_format_short'), strtotime($result['start_date'])),
					'end_date'          => date($this->language->get('date_format_short'), strtotime($result['end_date'])),
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
