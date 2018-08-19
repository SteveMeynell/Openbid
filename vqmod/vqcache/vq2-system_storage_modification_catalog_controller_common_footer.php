<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

			
			/* mps */
		$data['xmas_snow_flake_color'] = $this->config->get('xmas_snow_flake_color');
		if(empty($data['xmas_snow_flake_color'])){
			$data['xmas_snow_flake_color'] = '71C7D8';
		}
		$data['xmas_status'] = $this->config->get('xmas_snow_flake_status');
		/* mpe */
			
			

		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_feedback'] = $this->language->get('text_feedback');
		$data['text_quote'] = $this->language->get('text_quote');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['feedback'] = $this->url->link('information/feedback');
		$data['quote'] = $this->url->link('information/quote');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/account', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

					
						$data['weblog_status'] = $this->config->get('weblog_status');
						$data['weblog_footer_link'] = $this->config->get('weblog_footer_link');

						if ( $this->config->get('weblog_status') && $this->config->get('weblog_footer_link') ) {
						
							$weblog_texts = $this->config->get('weblog_texts');

							$data['weblog_text_link_name'] = !empty($weblog_texts[$this->config->get('config_language_id')]['weblog_text_link_name'])? $weblog_texts[$this->config->get('config_language_id')]['weblog_text_link_name'] : 'Blog';

							$data['weblog']	= $this->url->link('weblog/article', 'weblog=' . $this->config->get('config_store_id'), 'SSL'); // For the parameter weblog=store_id, see file Seo_url.php
						}
					

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		return $this->load->view('common/footer', $data);
	}
}
