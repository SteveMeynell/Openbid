<?php
class ControllerCatalogReview extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->addReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = null;
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'href' => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/review/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/review/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['reviews'] = array();

		$filter_data = array(
			'filter_product'    => $filter_product,
			'filter_author'     => $filter_author,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$review_total = $this->model_catalog_review->getTotalReviews($filter_data);

		$results = $this->model_catalog_review->getReviews($filter_data);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'review_id'  => $result['review_id'],
				'seller'       => $result['seller_name'],
				'bidder'     => $result['bidder_name'],
				'seller_reviewed'     => $result['seller_reviewed']?$this->language->get('text_yes'):$this->language->get('text_no'),
				'bidder_reviewed'     => $result['bidder_reviewed']?$this->language->get('text_yes'):$this->language->get('text_no'),
				'auction'     => $result['name'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('catalog/review/edit', 'token=' . $this->session->data['token'] . '&review_id=' . $result['review_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_auction'] = $this->language->get('column_auction');
		$data['column_seller'] = $this->language->get('column_seller');
		$data['column_seller_reviewed'] = $this->language->get('column_seller_reviewed');
		$data['column_bidder'] = $this->language->get('column_bidder');
		$data['column_bidder_reviewed'] = $this->language->get('column_bidder_reviewed');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		

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

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_product'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, true);
		$data['sort_author'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.author' . $url, true);
		$data['sort_rating'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.rating' . $url, true);
		$data['sort_date_added'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));

		$data['filter_product'] = $filter_product;
		$data['filter_author'] = $filter_author;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_auction'] = $this->language->get('entry_auction');
		$data['entry_seller'] = $this->language->get('entry_seller');
		$data['entry_seller_reviewed'] = $this->language->get('entry_seller_reviewed');
		$data['entry_seller_question1'] = $this->language->get('entry_seller_question1');
		$data['entry_seller_question2'] = $this->language->get('entry_seller_question2');
		$data['entry_seller_question3'] = $this->language->get('entry_seller_question3');
		$data['entry_seller_suggestion'] = $this->language->get('entry_seller_suggestion');
		$data['entry_seller_date_added'] = $this->language->get('entry_seller_date_added');

		$data['entry_bidder'] = $this->language->get('entry_bidder');
		$data['entry_bidder_reviewed'] = $this->language->get('entry_bidder_reviewed');
		$data['entry_bidder_question1'] = $this->language->get('entry_bidder_question1');
		$data['entry_bidder_question2'] = $this->language->get('entry_bidder_question2');
		$data['entry_bidder_question3'] = $this->language->get('entry_bidder_question3');
		$data['entry_bidder_suggestion'] = $this->language->get('entry_bidder_suggestion');
		$data['entry_bidder_date_added'] = $this->language->get('entry_bidder_date_added');

		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_review_date'] = $this->language->get('entry_review_date');
		$data['entry_text'] = $this->language->get('entry_text');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_seller_reminder'] = $this->language->get('button_seller_reminder');
		$data['button_bidder_reminder'] = $this->language->get('button_bidder_reminder');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['auction'])) {
			$data['error_auction'] = $this->error['auction'];
		} else {
			$data['error_auction'] = '';
		}

		if (isset($this->error['seller'])) {
			$data['error_seller'] = $this->error['seller'];
		} else {
			$data['error_seller'] = '';
		}

		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}

		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'href' => $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['review_id'])) {
			$data['action'] = $this->url->link('catalog/review/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/review/edit', 'token=' . $this->session->data['token'] . '&review_id=' . $this->request->get['review_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$review_info = $this->model_catalog_review->getReview($this->request->get['review_id']);
		}

		//debuglog($review_info);
		$data['token'] = $this->session->data['token'];

		//$this->load->model('catalog/product');

		$data['review_id'] = $review_info['review_id'];

		if (isset($this->request->post['auction_id'])) {
			$data['auction_id'] = $this->request->post['auction_id'];
		} elseif (!empty($review_info)) {
			$data['auction_id'] = $review_info['auction_id'];
		} else {
			$data['auction_id'] = '';
		}

		if (isset($this->request->post['auction'])) {
			$data['auction'] = $this->request->post['auction'];
		} elseif (!empty($review_info)) {
			$data['auction'] = $review_info['name'];
		} else {
			$data['auction'] = '';
		}

		if (isset($this->request->post['seller'])) {
			$data['seller'] = $this->request->post['seller'];
		} elseif (!empty($review_info)) {
			$data['seller'] = $review_info['seller_name'];
			$data['seller_id']	= $review_info['seller_id'];
		} else {
			$data['seller'] = '';
			$data['seller_id'] = '';
		}

		if (isset($this->request->post['seller_reviewed'])) {
			$data['seller_reviewed'] = $this->request->post['seller_reviewed'];
		} elseif (!empty($review_info)) {
			$data['seller_reviewed'] = $review_info['seller_reviewed'];
		} else {
			$data['seller_reviewed'] = '';
		}

		if (isset($this->request->post['seller_suggestion'])) {
			$data['seller_suggestion'] = $this->request->post['seller_suggestion'];
		} elseif (!empty($review_info)) {
			$data['seller_suggestion'] = $review_info['seller_suggestion'];
		} else {
			$data['seller_suggestion'] = '';
		}

		if (isset($this->request->post['seller_question1'])) {
			$data['seller_question1'] = $this->request->post['seller_question1'];
		} elseif (!empty($review_info)) {
			$data['seller_question1'] = $review_info['seller_question1'];
		} else {
			$data['seller_question1'] = '';
		}

		if (isset($this->request->post['seller_question2'])) {
			$data['seller_question1'] = $this->request->post['seller_question2'];
		} elseif (!empty($review_info)) {
			$data['seller_question2'] = $review_info['seller_question2'];
		} else {
			$data['seller_question2'] = '';
		}
		if (isset($this->request->post['seller_question3'])) {
			$data['seller_question3'] = $this->request->post['seller_question3'];
		} elseif (!empty($review_info)) {
			$data['seller_question3'] = $review_info['seller_question3'];
		} else {
			$data['seller_question3'] = '';
		}

		if (isset($this->request->post['bidder'])) {
			$data['bidder'] = $this->request->post['bidder'];
		} elseif (!empty($review_info)) {
			$data['bidder'] = $review_info['bidder_name'];
			$data['bidder_id'] = $review_info['bidder_id'];
		} else {
			$data['bidder'] = '';
			$data['bidder_id'] = '';
		}

		if (isset($this->request->post['bidder_reviewed'])) {
			$data['bidder_reviewed'] = $this->request->post['bidder_reviewed'];
		} elseif (!empty($review_info)) {
			$data['bidder_reviewed'] = $review_info['bidder_reviewed'];
		} else {
			$data['bidder_reviewed'] = '';
		}

		if (isset($this->request->post['bidder_suggestion'])) {
			$data['bidder_suggestion'] = $this->request->post['bidder_suggestion'];
		} elseif (!empty($review_info)) {
			$data['bidder_suggestion'] = $review_info['bidder_suggestion'];
		} else {
			$data['bidder_suggestion'] = '';
		}

		if (isset($this->request->post['bidder_question1'])) {
			$data['bidder_question1'] = $this->request->post['bidder_question1'];
		} elseif (!empty($review_info)) {
			$data['bidder_question1'] = $review_info['bidder_question1'];
		} else {
			$data['bidder_question1'] = '';
		}

		if (isset($this->request->post['bidder_question2'])) {
			$data['bidder_question1'] = $this->request->post['bidder_question2'];
		} elseif (!empty($review_info)) {
			$data['bidder_question2'] = $review_info['bidder_question2'];
		} else {
			$data['bidder_question2'] = '';
		}
		if (isset($this->request->post['bidder_question3'])) {
			$data['bidder_question3'] = $this->request->post['bidder_question3'];
		} elseif (!empty($review_info)) {
			$data['bidder_question3'] = $review_info['bidder_question3'];
		} else {
			$data['bidder_question3'] = '';
		}

		if (isset($this->request->post['review_date'])) {
			$data['review_date'] = $this->request->post['review_date'];
		} elseif (!empty($review_info)) {
			$data['review_date'] = ($review_info['review_date'] != '0000-00-00 00:00' ? $review_info['review_date'] : '');
		} else {
			$data['review_date'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!isset($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
			$this->error['rating'] = $this->language->get('error_rating');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function sendReminder(){
		$json = array();

		$mailInfo = array();
		$this->load->model('customer/customer');
		$customer = $this->model_customer_customer->getCustomer($this->request->post['target_id']);
		$mailInfo['email'] = $customer['email'];
		if($this->request->post['target'] == 'bidder_reminder') {
			$mailInfo['message'] = 'Congratulations, please take the time to write a review of your experience with both the seller and the site.';
			$mailInfo['subject'] = 'Please write a review';
		} else {
			$mailInfo['message'] = 'Congratulations, please take the time to write a review of your experience with both the bidder and the site.';
			$mailInfo['subject'] = 'Please write a review';
		}
		
		$this->sendMail($mailInfo);
		$json['success'] = true;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function sendMail($mailInfo) {

		$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
											
						$mail->setTo($mailInfo['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender('The Review Department');
						$mail->setSubject($mailInfo['subject']);
						$mail->setText($mailInfo['message']);
						$mail->send();

	}

	// end of controller
}