<?php
class ControllerStartupSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

 
	
					if (isset($parts[0]) && $parts[0] == $this->config->get('weblog_seo_path')) {

						array_shift($parts); // remove the element $parts[0] by shifting the array (do not use unset($parts[0]) because the index 0 would get lost)		
					}
					
					if (isset($parts[0]) && $parts[0] == 'author') { // After shifting the array, $parts[1] (if it's set) becomes $parts[0]
						array_shift($parts);
					}
					
					$weblog_search_path = ($this->config->get('weblog_seo_path')?  $this->config->get('weblog_seo_path') . '/wlsearch' : 'wlsearch');
				
					if ($this->request->get['_route_'] == $weblog_search_path){
						$this->request->get['route'] = 'weblog/search';
					}
					
					if ($this->request->get['_route_'] != $weblog_search_path) // No need of braces

					
			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);

					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}

					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}

					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}

					if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id') {
						$this->request->get['route'] = $query->row['query'];
					}
 
				

					if ($url[0] == 'weblog_article_id') {
					
						$this->request->get['route'] = 'weblog/article/view';
						$this->request->get['weblog_article_id'] = $url[1];						
					}
					
					if ($url[0] == 'weblog_category_id') {
					
						if (!isset($this->request->get['weblog_category_path'])) {
							
							$this->request->get['route'] = 'weblog/category';
							$this->request->get['weblog_category_path'] = $url[1];
						} else {
						
							$this->request->get['route'] = 'weblog/category';
							$this->request->get['weblog_category_path'] .= '_' . $url[1];
						}
					}
					 
					if ($url[0] == 'weblog_author_id') { 
					
						$this->request->get['route'] = 'weblog/author';
						$this->request->get['weblog_author_id'] = $url[1];
					}
					
					// Set up the route for the article list page 
					
					if ($url[0] == 'weblog') { 
					
						$this->request->get['route'] = 'weblog/article';
						$this->request->get['weblog'] = 1;
					}				
				
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}

			if (!isset($this->request->get['route'])) {
				if (isset($this->request->get['product_id'])) {
					$this->request->get['route'] = 'product/product';
				} elseif (isset($this->request->get['path'])) {
					$this->request->get['route'] = 'product/category';
				} elseif (isset($this->request->get['manufacturer_id'])) {
					$this->request->get['route'] = 'product/manufacturer/info';
				} elseif (isset($this->request->get['information_id'])) {
					$this->request->get['route'] = 'information/information';
				}
			}
		}
	}

	public function rewrite($link) {
 														
						$weblog_path = $this->config->get('weblog_seo_path')? '/' . $this->config->get('weblog_seo_path') : '';
					
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				

					} else if($data['route'] == 'weblog/article/view' && $key == 'weblog_article_id') {
					
						// Prepend to the article name the current category path
						$cat_path = '';
						if( isset($data['weblog_category_path'])) {
						
							$weblog_categories = explode("_", $data['weblog_category_path']);

							foreach ($weblog_categories as $weblog_category) {
								$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'weblog_category_id=" . (int)$weblog_category . "'");
								
								if ($query->num_rows && $query->row['keyword']) {
								
									$cat_path .= '/' . $query->row['keyword']; // APPEND CHILD CATEGORY NAMES TO THEIR PARENTS, 	
								}
							}
							unset($data['weblog_category_path']);
						}
					

						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
						
						if ($query->num_rows && $query->row['keyword']) {
							$url .= $weblog_path .  $cat_path . '/' .  $query->row['keyword'];
							$url .= isset($url_info['fragment'])? '#' . $url_info['fragment'] : ''; // string after the hashmark #
						} 
						unset($data[$key]);
 
					} else if($data['route'] == 'weblog/author' && $key == 'weblog_author_id') {
					
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
						
						if ($query->num_rows && $query->row['keyword']) {
						
							$url .= $weblog_path . '/author/' . $query->row['keyword'];
							$url .= isset($url_info['fragment'])? '#' . $url_info['fragment'] : ''; // string after the hashmark #
						} 
						unset($data[$key]);
						
					} else if($data['route'] == 'weblog/category' && $key == 'weblog_category_path') {
					
						$weblog_categories = explode("_", $value);

						$tmp = '';
						foreach ($weblog_categories as $weblog_category) {
							$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'weblog_category_id=" . (int)$weblog_category . "'");
							
							if ($query->num_rows && $query->row['keyword']) {
							
								$tmp .= '/' . $query->row['keyword']; // APPEND CHILD CATEGORY NAMES TO THEIR PARENTS, 	
							}
						}
						$url .= !empty($tmp)? ($weblog_path . $tmp . '/') : ''; // Add a trailing slash to the last category name
						
						$url .= isset($url_info['fragment'])? '#' . $url_info['fragment'] : ''; // string after the hashmark #
						
						unset($data[$key]);
											
					} else if($data['route'] == 'weblog/search') {
						
						$url .= $weblog_path . '/wlsearch';
						
						$url .= isset($url_info['fragment'])? '#' . $url_info['fragment'] : ''; // string after the hashmark #
						
						unset($data[$key]);

					} else if ( isset($data['route']) && $data['route'] == 'weblog/article' && $key == 'weblog' ) {
						
						$url .= $weblog_path;
						
						if ($this->config->get('weblog_seo_keyword')) {
							$url .= '/' . $this->config->get('weblog_seo_keyword');			
						} else {
							$url =  '';
						}
						$url .= isset($url_info['fragment'])? '#' . $url_info['fragment'] : ''; // string after the hashmark #
						
						unset($data['weblog']); // "dummy" parameter passed from the route weblog/article to identify itself. Remove it so it will not be appended to the url.
					} 

					elseif ($key == 'path') {			
					
				
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}
}
