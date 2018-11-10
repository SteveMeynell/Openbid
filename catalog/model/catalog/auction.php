<?php
class ModelCatalogAuction extends Model {
	public function updateViewed($auction_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "auctions SET viewed = (viewed + 1) WHERE auction_id = '" . (int)$auction_id . "'");
	}

	public function getAuction($auction_id) {
		$query = $this->db->query("
								  SELECT DISTINCT *,
								  ad1.start_date AS start_date,
								  ad1.end_date AS end_date,
								  ad1.min_bid AS min_bid,
								  ad1.shipping_cost AS shipping_cost,
								  ad1.additional_shipping AS additional_shipping,
								  ad1.reserve_price AS reserve_price,
								  ad1.buy_now_price AS buy_now_price,
								  ad1.quantity AS quantity,
								  ad1.shipping AS shipping_allowed,
								  ad1.international_shipping AS international_allowed,
								  ad2.name AS name,
								  ad2.subname AS subname,
								  ad2.description AS description,
								  ad2.tag AS tag,
								  ao.buy_now_only AS buy_now_only,
								  ao.bolded_item AS bolded_item,
								  ao.highlighted AS highlighted
								  FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_description ad2
								  ON (a.auction_id = ad2.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
								  ON (a.auction_id = a2s.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_details ad1
								  ON (a.auction_id = ad1.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_options ao
								  ON (a.auction_id = ao.auction_id) 
								  WHERE a.auction_id = '" . (int)$auction_id . "'
								  AND ad2.language_id = '" . (int)$this->config->get('config_language_id') . "'
								  AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'auction_id'       => $query->row['auction_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'quantity'         => $query->row['quantity'],
				'image'            => $query->row['image'],
				'start_date'		=> $query->row['start_date'],
				'reserve_price'          => $query->row['reserve_price'],
				'end_date'       => $query->row['end_date'],
				'buy_now_price'		=> $query->row['buy_now_price'],
				'buy_now_only'		=> $query->row['buy_now_only'],
				'bolded'		=> $query->row['bolded_item'],
				'highlighted'		=> $query->row['highlighted'],
				'rating'			=> '4',
				'reviews'			=> '',
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getAuctions($data = array()) {
		$sql = "
		SELECT a.auction_id, a.customer_id, a.auction_type, a.status, a.image, a.viewed, 
		ad1.start_date AS start_date,
		ad1.end_date AS end_date,
		ad1.min_bid AS min_bid,
		ad1.shipping_cost AS shipping_cost,
		ad1.additional_shipping AS additional_shipping,
		ad1.reserve_price AS reserve_price,
		ad1.buy_now_price AS buy_now_price,
		ad1.quantity AS quantity,
		ad1.shipping AS shipping_allowed,
		ad1.international_shipping AS international_allowed,
		ad2.name AS name,
		ad2.subname AS subname,
		ad2.description AS description,
		ad2.tag AS tag,
		ao.buy_now_only AS buy_now_only, 
		ao.bolded_item AS bolded, 
		ao.highlighted AS highlighted 
								FROM " . DB_PREFIX . "auctions a
								  LEFT JOIN " . DB_PREFIX . "auction_description ad2
								  ON (a.auction_id = ad2.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
								  ON (a.auction_id = a2s.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_details ad1
								  ON (a.auction_id = ad1.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_options ao
								  ON (a.auction_id = ao.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_to_category a2c
								  ON (a.auction_id = a2c.auction_id) 
								  WHERE ad2.language_id = '" . (int)$this->config->get('config_language_id') . "'
								  AND ad1.start_date <= NOW() 
								  AND a.status = '2' 
								  AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
								  
		if (!empty($data['filter_category_id'])) {
			$sql .= " AND a2c.category_id = '" . $data['filter_category_id'] . "' ";
		}


		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "ad2.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR ad2.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "ad2.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			$sql .= ")";
		}
		
		if (isset($data['filter_featured'])) {
			$sql .= " AND ao.featured = '1' ";
		}
		if (isset($data['filter_no_featured'])) {
			$sql .= " AND ao.featured = '0' ";
		}
		
		if (isset($data['filter_bolded'])) {
			$sql .= " AND ao.bolded_item = '1' ";
		}
		if (isset($data['filter_no_bolded'])) {
			$sql .= " AND ao.bolded_item = '0' ";
		}
		
		if (isset($data['filter_highlighted'])) {
			$sql .= " AND ao.highlighted = '1' ";
		}
		if (isset($data['filter_no_highlighted'])) {
			$sql .= " AND ao.highlighted = '0' ";
		}
		

		$sql .= " GROUP BY a.auction_id";

		$sort_data = array(
			'ad2.name',
			'ad1.min_bid',
			'ad1.start_date'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'ad2.name') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'ad1.min_bid') {
				$sql .= " ORDER BY ad1.min_bid";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY ad1.start_date";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(ad2.name) DESC";
		} else {
			$sql .= " ASC, LCASE(ad2.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$auction_data = array();

		$auction_data = $this->db->query($sql)->rows;
		
		
		return $auction_data;
	}


	public function getLatestAuctions($limit) {
		//$auction_data = $this->cache->get('auction.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		$auction_data = array();
			$query = $this->db->query("SELECT a.auction_id FROM " . DB_PREFIX . "auctions a
									  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id)
									  LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id) 
									  WHERE a.status = '2'
									  AND ad.start_date <= NOW()
									  AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
									  ORDER BY ad.start_date DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			//$this->cache->set('auction.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		

		return $auction_data;
	}

	public function getTopSellers($limit){
		//$auction_data = $this->cache->get('auction.top_sellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
		

			$auction_data = array();
			
			$query = $this->db->query("SELECT COUNT(a.auction_id) AS count, a.customer_id AS customer 
									  FROM " . DB_PREFIX . "auctions a
									  LEFT JOIN ". DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id) 
									  GROUP BY a.customer_id
									  DESC 
									  LIMIT " . (int)$limit);
			
			foreach ($query->rows as $result) {
				$auction_data[$result['customer']] = $result['count'];
			}
			
			//$this->cache->set('auction.top_sellers.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		
		
		return $auction_data;
	}
	
	
	public function getMostViewedAuctions($limit) {
		//$auction_data = $this->cache->get('auction.most_viewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);


			$auction_data = array();

			$query = $this->db->query("SELECT a.auction_id, a.viewed AS viewed
									  FROM " . DB_PREFIX . "auctions a
									  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id)
									  WHERE a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
									  AND a.viewed > '0'
									  AND a.status = '2' 
									  GROUP BY a.auction_id
									  ORDER BY viewed
									  DESC
									  LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			//$this->cache->set('auction.most_viewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		
		

		return $auction_data;
	}
	
	public function getStartingSoonAuctions($settings) {
		$limit = $settings['limit'];
		$starting_when = $settings['starting_when'];
		$length = $settings['length'];
		
		//$auction_data = $this->cache->get('auction.starting_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$starting_when . (int)$length . (int)$limit);
		
			$auction_data = array();
			
			$sql = "SELECT a.auction_id, ad1.start_date 
									  FROM " . DB_PREFIX . "auctions a
									  LEFT JOIN " . DB_PREFIX . "auction_details ad1 ON (a.auction_id = ad1.auction_id) 
									  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id) ";
									  
			$others = "GROUP BY a.auction_id
									  ORDER BY ad1.start_date
									  ASC
									  LIMIT " . (int)$limit;
									  
			$where = "WHERE a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '1' ";
			
			if(isset($settings['carousel'])) {
				$sql .= "LEFT JOIN " . DB_PREFIX . "auction_options ao ON (a.auction_id = ao.auction_id) ";
				$where .= " AND ao.on_carousel = '1' ";
			}
			
			if(!$length) {
				$timeframe = '';
			} elseif ($starting_when) {
				$currentdatetime = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $length . " DAY) AS current")->row;
				$timeframe = "AND DATE(ad1.start_date) <= '" . $currentdatetime['current'] . "'";
			} else {
				$currentdatetime = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $length . " HOUR) AS current")->row;
				$timeframe = "AND DATE(ad1.start_date) <= '" . $currentdatetime['current'] . "'";
			}
			
			
			$sql .= $where;
			$sql .= $timeframe;
			$sql .= $others;
			

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			//$this->cache->set('auction.starting_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$starting_when . (int)$length . (int)$limit, $auction_data);
		
		
//debuglog($auction_data);
		return $auction_data;
		
	}
	
	public function getEndingSoonAuctions($settings) {
		$limit = $settings['limit'];
		$ending_when = $settings['ending_when'];
		$length = $settings['length'];
		
		//$auction_data = $this->cache->get('auction.ending_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$ending_when . (int)$length . (int)$limit);
		
			$auction_data = array();
			
			$sql = "SELECT a.auction_id, ad1.end_date 
									  FROM " . DB_PREFIX . "auctions a
									  LEFT JOIN " . DB_PREFIX . "auction_details ad1 ON (a.auction_id = ad1.auction_id) 
									  LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id) ";
									  
			$others = "GROUP BY a.auction_id
									  ORDER BY ad1.end_date
									  ASC
									  LIMIT " . (int)$limit;
									  
			$where = "WHERE a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND a.status = '2' ";
			
			if(!$length) {
				$timeframe = '';
			} elseif ($ending_when) {
				$currentdatetime = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $length . " DAY) AS current")->row;
				$timeframe = "AND DATE(ad1.end_date) <= '" . $currentdatetime['current'] . "'";
			} else {
				$currentdatetime = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $length . " HOUR) AS current")->row;
				$timeframe = "AND DATE(ad1.end_date) <= '" . $currentdatetime['current'] . "'";
			}
			
			
			$sql .= $where;
			$sql .= $timeframe;
			$sql .= $others;
			
//debuglog($sql);
			$query = $this->db->query($sql);
			
			

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			//$this->cache->set('auction.starting_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$starting_when . (int)$length . (int)$limit, $auction_data);
		
		
//debuglog($auction_data);
		return $auction_data;
		
	}

	public function getPopularAuctions($limit) {
		$auction_data = $this->cache->get('auction.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
	
		if (!$auction_data) {
			$query = $this->db->query("SELECT p.auction_id FROM " . DB_PREFIX . "auction p LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
	
			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}
			
			$this->cache->set('auction.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		}
		
		return $auction_data;
	}
	

	public function getAuctionImages($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_photos WHERE auction_id = '" . (int)$auction_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}


	public function getAuctionLayoutId($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_layout WHERE auction_id = '" . (int)$auction_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_category WHERE auction_id = '" . (int)$auction_id . "'");

		return $query->rows;
	}

	public function getTotalAuctions($data = array()) {
		$sql = "SELECT COUNT(DISTINCT a.auction_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "auction_to_category a2c ON (cp.category_id = a2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "auction_to_category a2c";
			}

				$sql .= " LEFT JOIN " . DB_PREFIX . "auctions a ON (a2c.auction_id = a.auction_id)";
		} else {
			$sql .= " FROM " . DB_PREFIX . "auctions a";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "auction_description ad
		ON (a.auction_id = ad.auction_id)
		LEFT JOIN " . DB_PREFIX . "auction_details ads
		ON (a.auction_id = ads.auction_id) 
		LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
		ON (a.auction_id = a2s.auction_id)
		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND a.status = '2'
		AND ads.start_date <= NOW()
		AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND a2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "ad.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}
			
			$sql .= ")";
		}
		//debuglog($sql);
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	
	
	public function getCarouselAuctions($data = array()) {
		if (isset($data['num_auctions']) && $data['num_auctions']>0){
			$limit = $data['num_auctions'];
		} else {
			$limit = 100;
		}
		switch ($data['type']) {
			// Anywhere
			case '0':
			case '1':
			case '2':
			case '3':
			case '4':
			default:
				$sql = "SELECT a.auction_id AS auction_id, a.image AS image, ad2.name AS title, ad2.subname AS subtitle, ad2.description AS description
				FROM " . DB_PREFIX . "auctions a
				LEFT JOIN " . DB_PREFIX . "auction_details ad1
				ON (a.auction_id = ad1.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_description ad2
				ON (a.auction_id = ad2.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
				ON (a.auction_id = ad2.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_options ao
				ON (a.auction_id = ao.auction_id) ";
				
				$where = " WHERE ad2.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND a.status = '2'
				AND ao.on_carousel = '1' 
				AND ad1.start_date <= NOW()
				AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
				$other = "GROUP BY a.auction_id
											  ORDER BY ad1.start_date
											  ASC";
		
				$query = $sql . $where . $other;
				$auctions = $this->db->query($query)->rows;
				
				shuffle($auctions);
				return array_slice($auctions,0,min($limit,count($auctions)));
				break;
		
		}

	}

		public function getFeaturedAuctions($settings){
		$auctions = array();
		
		$sql = "SELECT a.auction_id AS auction_id, a.image AS image, ad2.name AS title, ad2.subname AS subtitle, ad2.description AS description, ad1.reserve_price AS reserve_price 
				FROM " . DB_PREFIX . "auctions a
				LEFT JOIN " . DB_PREFIX . "auction_details ad1
				ON (a.auction_id = ad1.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_description ad2
				ON (a.auction_id = ad2.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
				ON (a.auction_id = ad2.auction_id)
				LEFT JOIN " . DB_PREFIX . "auction_options ao
				ON (a.auction_id = ao.auction_id) ";
				
				$where = " WHERE ad2.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND a.status = '2'
				AND ao.featured = '1' 
				AND ad1.start_date <= NOW()
				AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
				
				$other = "GROUP BY a.auction_id
											  ORDER BY ad1.start_date
											  ASC";
		
				$query = $sql . $where . $other;
				$auctions = $this->db->query($query)->rows;
				
		return $auctions;
	}
	
	// End of Model
}
