<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		if($this->config->get('jumbotron_status')) {
			$data['jumbotron'] = $this->load->controller('extension/jumbotron/jumbotron');
		}
		$this->load->model('extension/module');
		$homepage_info = $this->model_extension_module->getModuleByCode('closed_auctions');
		debuglog($homepage_info);
		if($homepage_info) {
			$setting_info = json_decode($homepage_info['setting'], true);
			$data['closed_auction_data'] = $this->load->controller('extension/module/closed_auctions', $setting_info);
		} else {
			$data['closed_auction_data'] = false;
		}


		$this->load->model('catalog/information');
		$information_info = $this->model_catalog_information->getInformation('8');

		$data['information'] = html_entity_decode($information_info['description']);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
