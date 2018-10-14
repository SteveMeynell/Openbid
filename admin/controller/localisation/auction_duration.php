<?php
class ControllerLocalisationAuctionDuration extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/auction_duration');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/auction_duration');

		$this->getList();
	}
    
    protected function getList() {
        
        if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'duration_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
        
        if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['duration'])) {
			$url .= '&duration_id=' . $this->request->get['duration'];
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
			'href' => $this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true)
		);

		
		$data['add'] = $this->url->link('localisation/auction_duration/add', 'token=' . $this->session->data['token'], true);
		$data['delete'] = $this->url->link('localisation/auction_duration/delete', 'token=' . $this->session->data['token'], true);

		$data['durations'] = array();
        
        $filter_data = array(
            'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
        
        $results = $this->model_localisation_auction_duration->getAllDurations($filter_data);
        
        foreach($results as $result){
            $data['durations'][] = array(
                'duration_id'   => $result['duration_id'],
                'duration'          => $result['duration'],
                'description'   => $result['description'],
                'edit'       => $this->url->link('localisation/auction_duration/edit', 'token=' . $this->session->data['token'] . '&duration_id=' . $result['duration_id'] . $url, true)
            );
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        $data['column_duration'] = $this->language->get('column_duration');
        $data['column_description'] = $this->language->get('column_description');
        $data['column_action'] = $this->language->get('column_action');
        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        
        $data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
        
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
        
        if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        
        $duration_total = $this->model_localisation_auction_duration->getTotalDurations();

		$pagination = new Pagination();
		$pagination->total = $duration_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($duration_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($duration_total - $this->config->get('config_limit_admin'))) ? $duration_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $duration_total, ceil($duration_total / $this->config->get('config_limit_admin')));
        
        $data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/auction_duration_list', $data));

        
        
    }
    
    protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
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
        
        if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}
        
        $url = '';
        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['duration_id'])) {
			$data['action'] = $this->url->link('localisation/auction_duration/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/auction_duration/edit', 'token=' . $this->session->data['token'] . '&duration_id=' . $this->request->get['duration_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['duration_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$duration_info = $this->model_localisation_auction_duration->getDuration($this->request->get['duration_id']);
		}

		$data['token'] = $this->session->data['token'];
        
        if (isset($this->request->post['duration'])) {
			$data['duration'] = $this->request->post['duration'];
		} elseif (!empty($duration_info)) {
			$data['duration'] = $duration_info['duration'];
		} else {
			$data['duration'] = '';
		}
        
        if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($duration_info)) {
			$data['description'] = $duration_info['description'];
		} else {
			$data['description'] = '';
		}
        
        
        
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/auction_duration_form', $data));    

    }
    
    public function add() {
		$this->load->language('localisation/auction_duration');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/auction_duration');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_auction_duration->addDuration($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/auction_duration');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/auction_duration');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            
			$this->model_localisation_auction_duration->editDuration($this->request->get['duration_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {

		$this->load->language('localisation/auction_duration');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/auction_duration');

		if (isset($this->request->post['selected'])) {
            
			foreach ($this->request->post['selected'] as $duration_id) {
				$this->model_localisation_auction_duration->deleteDuration($duration_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('localisation/auction_duration', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
    
    
    protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/auction_duration')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
    
    
} // End of Controller