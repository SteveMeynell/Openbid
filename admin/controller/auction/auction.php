<?php
class ControllerAuctionAuction extends Controller {
    
    public function index() {
		$this->load->language('auction/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
        
        $this->load->model('auction/auction');
        $this->getList();
    }
        
    public function add() {
		$this->load->language('auction/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('auction/auction');

		$this->getForm();
	}

	public function edit() {
		$this->load->language('auction/auction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('auction/auction');

		$this->getForm();
	}
        
        
    
    
    protected function getList() {
		if (isset($this->request->get['filter_auction_id'])) {
			$filter_auction_id = $this->request->get['filter_auction_id'];
		} else {
			$filter_auction_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$filter_auction_status = $this->request->get['filter_auction_status'];
		} else {
			$filter_auction_status = null;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
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

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			'href' => $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], true);
		$data['shipping'] = $this->url->link('sale/order/shipping', 'token=' . $this->session->data['token'], true);
		$data['add'] = $this->url->link('auction/auction/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('auction/auction/delete', 'token=' . $this->session->data['token'], true);

		$data['auctions'] = array();

		$filter_data = array(
			'filter_auction_id'      => $filter_auction_id,
			'filter_customer'	   => $filter_customer,
			'filter_auction_status'  => $filter_auction_status,
			'filter_total'         => $filter_total,
			'filter_date_created'    => $filter_date_created,
			'filter_start_date' => $filter_start_date,
			'filter_end_date' => $filter_end_date,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$auction_total = $this->model_auction_auction->getTotalAuctions($filter_data);

		$results = $this->model_auction_auction->getAuctions($filter_data);
//debuglog($results);
		foreach ($results as $result) {
			$data['auctions'][] = array(
				'auction_id'        => $result['auction_id'],
				'seller'            => $result['seller'],
				'auction_status'    => $result['status_name'] ? $result['status_name'] : $this->language->get('text_missing'),
				'current_bid'       => $this->currency->format($result['current_bid'], $this->config->get('config_currency'), '1'),
				'date_created'      => date($this->language->get('date_format_short'), strtotime($result['date_created'])),
				'start_date'        => date($this->language->get('date_format_short'), strtotime($result['start_date'])),
                'end_date'          => date($this->language->get('date_format_short'), strtotime($result['end_date'])),
				//'shipping_code' => $result['shipping_code'],
				'view'              => $this->url->link('auction/auction/info', 'token=' . $this->session->data['token'] . '&auction_id=' . $result['auction_id'] . $url, true),
				'edit'              => $this->url->link('auction/auction/edit', 'token=' . $this->session->data['token'] . '&auction_id=' . $result['auction_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_auction_id'] = $this->language->get('column_auction_id');
		$data['column_seller'] = $this->language->get('column_seller');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_current_bid'] = $this->language->get('column_current_bid');
		$data['column_date_created'] = $this->language->get('column_date_created');
		$data['column_start_date'] = $this->language->get('column_start_date');
        $data['column_end_date'] = $this->language->get('column_end_date');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_auction_id'] = $this->language->get('entry_auction_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_auction_status'] = $this->language->get('entry_auction_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_created'] = $this->language->get('entry_date_created');
		$data['entry_start_date'] = $this->language->get('entry_start_date');
		$data['entry_end_date'] = $this->language->get('entry_end_date');

		$data['button_invoice_print'] = $this->language->get('button_invoice_print');
		$data['button_shipping_print'] = $this->language->get('button_shipping_print');
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

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_start_date'])) {
			$url .= '&filter_start_date=' . $this->request->get['filter_start_date'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=a.auction_id' . $url, true);
		$data['sort_customer'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=auction_status' . $url, true);
		$data['sort_total'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=a.total' . $url, true);
		$data['sort_date_created'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=a.date_created' . $url, true);
		$data['sort_start_date'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=ad.start_date' . $url, true);
		$data['sort_end_date'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . '&sort=ad.end_date' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_auction_id'])) {
			$url .= '&filter_auction_id=' . $this->request->get['filter_auction_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
$auction_total = 1;
		$pagination = new Pagination();
		$pagination->total = $auction_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($auction_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($auction_total - $this->config->get('config_limit_admin'))) ? $auction_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $auction_total, ceil($auction_total / $this->config->get('config_limit_admin')));

		$data['filter_auction_id'] = $filter_auction_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_auction_status'] = $filter_auction_status;
		$data['filter_total'] = $filter_total;
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

		$this->response->setOutput($this->load->view('auction/auction_list', $data));
	}
    
    public function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['auction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_ip_add'] = sprintf($this->language->get('text_ip_add'), $this->request->server['REMOTE_ADDR']);
		$data['text_item'] = $this->language->get('text_item');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_auction_detail'] = $this->language->get('text_auction_detail');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_order_status'] = $this->language->get('entry_order_status');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['tab_auction'] = $this->language->get('tab_auction');
		$data['tab_customer'] = $this->language->get('tab_customer');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_item'] = $this->language->get('tab_item');
		$data['tab_voucher'] = $this->language->get('tab_voucher');
		$data['tab_total'] = $this->language->get('tab_total');

		$url = '';

		if (isset($this->request->get['filter_auction_id'])) {
			$url .= '&filter_auction_id=' . $this->request->get['filter_auction_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_auction_status'])) {
			$url .= '&filter_auction_status=' . $this->request->get['filter_auction_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
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
			'href' => $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['cancel'] = $this->url->link('auction/auction', 'token=' . $this->session->data['token'] . $url, true);

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['auction_id'])) {
			$auction_info = $this->model_auction_auction->getAuction($this->request->get['auction_id']);
		}
debuglog($auction_info);
		if (!empty($auction_info)) {
			$data['auction_id'] = $this->request->get['auction_id'];
			$data['store_id'] = $auction_info['store_id'];
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

			$data['customer'] = $auction_info['customer'];
			$data['customer_id'] = $auction_info['customer_id'];
			$data['customer_group_id'] = $auction_info['customer_group_id'];
			$data['firstname'] = $auction_info['firstname'];
			$data['lastname'] = $auction_info['lastname'];
			$data['email'] = $auction_info['email'];
			$data['telephone'] = $auction_info['telephone'];
			$data['fax'] = $auction_info['fax'];
			$data['account_custom_field'] = $auction_info['custom_field'];

			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($auction_info['customer_id']);

			$data['payment_firstname'] = $auction_info['payment_firstname'];
			$data['payment_lastname'] = $auction_info['payment_lastname'];
			$data['payment_company'] = $auction_info['payment_company'];
			$data['payment_address_1'] = $auction_info['payment_address_1'];
			$data['payment_address_2'] = $auction_info['payment_address_2'];
			$data['payment_city'] = $auction_info['payment_city'];
			$data['payment_postcode'] = $auction_info['payment_postcode'];
			$data['payment_country_id'] = $auction_info['payment_country_id'];
			$data['payment_zone_id'] = $auction_info['payment_zone_id'];
			$data['payment_custom_field'] = $auction_info['payment_custom_field'];
			$data['payment_method'] = $auction_info['payment_method'];
			$data['payment_code'] = $auction_info['payment_code'];

			$data['shipping_firstname'] = $auction_info['shipping_firstname'];
			$data['shipping_lastname'] = $auction_info['shipping_lastname'];
			$data['shipping_company'] = $auction_info['shipping_company'];
			$data['shipping_address_1'] = $auction_info['shipping_address_1'];
			$data['shipping_address_2'] = $auction_info['shipping_address_2'];
			$data['shipping_city'] = $auction_info['shipping_city'];
			$data['shipping_postcode'] = $auction_info['shipping_postcode'];
			$data['shipping_country_id'] = $auction_info['shipping_country_id'];
			$data['shipping_zone_id'] = $auction_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $auction_info['shipping_custom_field'];
			$data['shipping_method'] = $auction_info['shipping_method'];
			$data['shipping_code'] = $auction_info['shipping_code'];

/*			// Products
			$data['order_products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$data['order_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['order_totals'] = array();

			$order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}
*/
			$data['auction_status_id'] = $auction_info['auction_status_id'];
			$data['comment'] = $order_info['comment'];
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate'] = $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'];
			$data['currency_code'] = $order_info['currency_code'];
		} else {
			$data['auction_id'] = 0;
			$data['store_id'] = 0;
			$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
			
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();

			$data['auction_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		$this->load->model('localisation/auction_status');

		$data['auction_statuses'] = $this->model_localisation_auction_status->getAuctionStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
/*
		$data['voucher_min'] = $this->config->get('config_voucher_min');

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
*/
		// API login
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
		
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('auction/auction_form', $data));
	}
    
        
}