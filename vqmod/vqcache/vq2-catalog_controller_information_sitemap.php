<?php
class ControllerInformationSitemap extends Controller {
	public function index() {

					
			// Weblog - Sitemap  
					
						$this->load->model('weblog/weblog');
					
						$weblog_sitemap_categories = array();
						

						
						if ( $this->config->get('weblog_status') ) {
	
					// Weblog main page	
					
							$weblog_texts = $this->config->get('weblog_texts');
							$data['weblog_text_link_name'] = !empty($weblog_texts[$this->config->get('config_language_id')]['weblog_text_link_name'])? $weblog_texts[$this->config->get('config_language_id')]['weblog_text_link_name'] : 'Blog';

							$weblog_sitemap_categories[] = array(
								'name'     => $data['weblog_text_link_name'],
								'children' => $weblog_sitemap_categories,
								'href'     => $this->url->link('weblog/article', 'weblog=' . $this->config->get('config_store_id'), 'SSL') // For the parameter weblog=..., see file Seo_url.php
							);	
							

					// Weblog Categories
					
							$category_total = $this->model_weblog_weblog->getTotalCategories();
							
							if ($category_total) {
								
								$weblog_categories = $this->model_weblog_weblog->getCategories();

								foreach ($weblog_categories as $category) {
										
									// Level 2
									$children_data = array();
									
									$filter_data = array(
										'parent_category_id' 	=> $category['weblog_category_id']
									);

									$children = $this->model_weblog_weblog->getCategories($filter_data);
									
									foreach ($children as $child) {
									
										$child_name = $child['name'];

										$children_data[] = array(
											'name'  => $child_name,
											'href'  => $this->url->link('weblog/category', 'weblog_category_path=' . $category['weblog_category_id'] . '_' . $child['weblog_category_id'])
										);					
									}
									
									// Level 1
									$weblog_sitemap_categories[] = array(
										'name'     => $category['name'],
										'children' => $children_data,
										'href'     => $this->url->link('weblog/category', 'weblog_category_path=' . $category['weblog_category_id'])
									);			
								}
							}	
						}
						
						$data['weblog_sitemap_categories'] = $weblog_sitemap_categories;
						
			// Weblog - Sitemap  

					
		$this->load->language('information/sitemap');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/sitemap')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_history'] = $this->language->get('text_history');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_search'] = $this->language->get('text_search');
		$data['text_information'] = $this->language->get('text_information');
		$data['text_contact'] = $this->language->get('text_contact');

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories_1 = $this->model_catalog_category->getCategories(0);

		foreach ($categories_1 as $category_1) {
			$level_2_data = array();

			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);

			foreach ($categories_2 as $category_2) {
				$level_3_data = array();

				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);

				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'name' => $category_3['name'],
						'href' => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'] . '_' . $category_3['category_id'])
					);
				}

				$level_2_data[] = array(
					'name'     => $category_2['name'],
					'children' => $level_3_data,
					'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'])
				);
			}

			$data['categories'][] = array(
				'name'     => $category_1['name'],
				'children' => $level_2_data,
				'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'])
			);
		}

		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		$data['history'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['search'] = $this->url->link('product/search');
		$data['contact'] = $this->url->link('information/contact');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			$data['informations'][] = array(
				'title' => $result['title'],
				'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('information/sitemap', $data));
	}
}