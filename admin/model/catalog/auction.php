<?php
class ModelCatalogAuction extends Model {
	
	public function getTotalAuctions($data = array()) {
        
        if(!empty($data['dashboard'])) {
            $auctions = array();
            $query = $this->db->query("SELECT COUNT(auction_id) AS other FROM "  . DB_PREFIX . "auctions WHERE status = 0");
            $result = $query->row;
            array_push($auctions, $result['other']);
            $query = $this->db->query("SELECT COUNT(auction_id) AS open FROM "  . DB_PREFIX . "auctions WHERE status = 1");
            $result = $query->row;
            array_push($auctions, $result['open']);
            $query = $this->db->query("SELECT COUNT(auction_id) AS closed FROM "  . DB_PREFIX . "auctions WHERE status = 2");
            $result = $query->row;
            array_push($auctions, $result['closed']);
            $query = $this->db->query("SELECT COUNT(auction_id) AS suspended FROM "  . DB_PREFIX . "auctions WHERE status = 3");
            $result = $query->row;
            array_push($auctions, $result['suspended']);
            return $auctions;
        }
        
        $sql = "SELECT COUNT(DISTINCT a.auction_id) AS total FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id)
        ";
        
        $implode = array();
        
        if (!empty($data['filter_auction_id'])) {
            $implode[] = "a.auction_id = '" . (int)$data['filter_auction_id'] . "'";
        }
        if (!empty($data['filter_seller'])) {
            $implode[] = "a.customer_id = '" . (int)$data['filter_seller'] . "'";
        }
		if (!empty($data['filter_auction_type'])) {
            $implode[] = "a.auction_type = '" . (int)$data['filter_auction_type'] . "'";
        }
        if (!empty($data['filter_auction_status'])) {
            $implode[] = "a.status = '" . (int)$data['filter_auction_status'] . "'";
        }
        		
		if (!empty($data['filter_date_created'])) {
			$implode[] = "a.date_created >= '" . $data['filter_date_created'] . "'";
		}
		
		if (!empty($data['filter_start_date'])) {
			$implode[] = "ad.start_date >= '" . $this->db->escape($data['filter_start_date']) . "'";
		}
		if (!empty($data['filter_end_date'])) {
			$implode[] = "ad.end_date <= '" . $this->db->escape($data['filter_end_date']) . " 23:59:59'";
		}
		if (!empty($data['filter_title'])) {
			$implode[] = "ad.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}
		if (!empty($data['filter_reserve_price'])) {
			$implode[] = "ad.reserve_price >= '" . (float)$data['filter_reserve_price'] . "'";
		}
		if (!empty($data['filter_winning_bid'])) {
			$implode[] = "a.winning_bid >= '" . (float)$data['filter_winning_bid'] . "'";
		}
		
        if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
    }
	
	
	public function getAuctions($data = array()) {
		$sql = "SELECT *, aus.name AS status_name, CONCAT(c.lastname, ' ', c.firstname) AS seller  FROM " . DB_PREFIX . "auctions a
		LEFT JOIN " . DB_PREFIX . "auction_details ad
		ON (a.auction_id = ad.auction_id) 
		LEFT JOIN " . DB_PREFIX . "auction_description ades
		ON (a.auction_id = ades.auction_id)
		LEFT JOIN " . DB_PREFIX . "customer c
		ON (a.customer_id = c.customer_id)
		LEFT JOIN " . DB_PREFIX . "auction_status aus
		ON (a.status = aus.auction_status_id) 
		WHERE ades.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_auction_id'])) {
			$sql .= " AND a.auction_id = '" . $this->db->escape($data['filter_auction_id']) . "'";
		}

		if (!empty($data['filter_title'])) {
			$sql .= " AND ad.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
		}

		if (!empty($data['filter_seller'])) {
			$sql .= " AND CONCAT(c.firstname, ' ', c.lastname)  = '" . $this->db->escape(strtolower($data['filter_seller'])) . "'";
		}

		if (isset($data['filter_auction_status']) && !is_null($data['filter_auction_status'])) {
			$sql .= " AND a.status = '" . $this->db->escape($data['filter_auction_status']) . "'";
		}

		if (isset($data['filter_auction_type']) && !is_null($data['filter_auction_type'])) {
			$sql .= " AND a.auction_type = '" . (int)$data['filter_auction_type'] . "'";
		}

		if (isset($data['filter_date_created']) && !is_null($data['filter_date_created'])) {
			$sql .= " AND a.date_created >= '" . $data['filter_date_created'] . "'";
		}
		
		if (isset($data['filter_start_date']) && !is_null($data['filter_start_date'])) {
			$sql .= " AND ad.start_date >= '" . $data['filter_start_date'] . "'";
		}
		
		if (isset($data['filter_end_date']) && !is_null($data['filter_end_date'])) {
			$sql .= " AND ad.end_date <= '" . $data['filter_end_date'] . " 23:59:59'";
		}

		$sql .= " GROUP BY a.auction_id";

		$sort_data = array(
			'a.auction_id',
			'ad.title',
			'a.status',
			'a.customer_id',
			'a.date_created',
			'ad.start_date',
			'ad.end_date',
			'seller',
			'a.auction_type'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ad.title";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	
	public function addAuction($data) {
		
		// add in the actual auction table
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "auctions
						 SET
						 store_id = '" . $this->db->escape($data['store_id']) . "',
						 customer_id = '" . $this->db->escape($data['customer_id']) . "',
						 auction_type = '" . $this->db->escape($data['auction_type']) . "',
						 status = '" . $this->db->escape($data['status']) . "',
						 relisted = '" . $this->db->escape($data['relisted']) . "',
						 num_bids = '" . $this->db->escape($data['num_bids']) . "',
						 current_fee = '" . $this->db->escape($data['current_fee']) . "',
						 date_modified = NOW(),
						 date_created = NOW()");

		$auction_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "auction
							 SET
							 image = '" . $this->db->escape($data['image']) . "'
							 WHERE auction_id = '" . (int)$auction_id . "'");
		}

		// details
		$this->db->query("INSERT INTO " . DB_PREFIX . "auction_details
						 SET
						 auction_id = '" . (int)$auction_id . "',
						 title = '" . $this->db->escape($data['title']) . "',
						 subtitle = '" . $this->db->escape($data['subtitle']) . "',
						 start_date = '" . $this->db->escape($data['start_date']) . "',
						 end_date = '" . $this->db->escape($data['end_date']) . "',
						 min_bid = '" . $this->db->escape((float)$data['min_bid']) . "',
						 shipping_cost = '" . $this->db->escape((float)$data['shipping_cost']) . "',
						 additional_shipping = '" . $this->db->escape((float)$data['additional_shipping']) . "',
						 reserve_price = '" . $this->db->escape((float)$data['reserve_price']) . "',
						 duration = '" . $this->db->escape((float)$data['duration']) . "',
						 increment = '" . $this->db->escape((float)$data['increment']) . "',
						 shipping = '" . $this->db->escape((int)$data['shipping']) . "',
						 payment = '" . $this->db->escape((int)$data['payment']) . "',
						 international_shipping = '" . $this->db->escape((int)$data['international_shipping']) . "',
						 quantity = '" . $this->db->escape((int)$data['quantity']) . "',
						 relist = '" . $this->db->escape((int)$data['relist']) . "',
						 current_fee = '" . $this->db->escape((float)$data['current_fee']) . "',
						 tax = '" . $this->db->escape((int)$data['tax']) . "',
						 taxinc = '" . $this->db->escape((int)$data['taxinc']) . "',
						 buy_now_price = '" . $this->db->escape((float)$data['buy_now_price']) . "'
						 ");
		
		
		foreach ($data['auction_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "auction_description
							 SET
							 auction_id = '" . (int)$auction_id . "',
							 language_id = '" . (int)$language_id . "',
							 name = '" . $this->db->escape($value['name']) . "',
							 subname = '" . $this->db->escape($value['subname']) . "',
							 description = '" . $this->db->escape($value['description']) . "',
							 tag = '" . $this->db->escape($value['tag']) . "',
							 meta_title = '" . $this->db->escape($value['meta_title']) . "',
							 meta_description = '" . $this->db->escape($value['meta_description']) . "',
							 meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['auction_store'])) {
			foreach ($data['auction_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_store
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['auction_image'])) {
			foreach ($data['auction_image'] as $auction_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos
								 SET auction_id = '" . (int)$auction_id . "',
								 image = '" . $this->db->escape($auction_image['image']) . "',
								 sort_order = '" . (int)$auction_image['sort_order'] . "'");
			}
		}

		if (isset($data['auction_category'])) {
			foreach ($data['auction_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_category
								 SET auction_id = '" . (int)$auction_id . "',
								 category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['auction_layout'])) {
			foreach ($data['auction_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_layout
								 SET auction_id = '" . (int)$auction_id . "',
								 store_id = '" . (int)$store_id . "',
								 layout_id = '" . (int)$layout_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'auction_id=" . (int)$auction_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('auction');

		return $auction_id;
	}

	public function editAuction($auction_id, $data) {
		debuglog($data);
		
		
		/*
		$this->db->query("UPDATE " . DB_PREFIX . "auctions
						 SET
						 store_id = '" . $this->db->escape($data['store_id']) . "',
						 customer_id = '" . $this->db->escape($data['customer_id']) . "',
						 auction_type = '" . $this->db->escape($data['auction_type']) . "',
						 status = '" . $this->db->escape($data['status']) . "',
						 relisted = '" . $this->db->escape($data['relisted']) . "',
						 num_bids = '" . $this->db->escape($data['num_bids']) . "',
						 current_fee = '" . $this->db->escape($data['current_fee']) . "',
						 date_modified = NOW()
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "auctions
							 SET
							 image = '" . $this->db->escape($data['image']) . "'
							 WHERE
							 auction_id = '" . (int)$auction_id . "'");
		}

		// details here
		$this->db->query("UPDATE " . DB_PREFIX . "auction_details
						 SET
						 title = '" . $this->db->escape($data['title']) . "',
						 subtitle = '" . $this->db->escape($data['subtitle']) . "',
						 start_date = '" . $this->db->escape($data['start_date']) . "',
						 end_date = '" . $this->db->escape($data['end_date']) . "',
						 min_bid = '" . $this->db->escape((float)$data['min_bid']) . "',
						 shipping_cost = '" . $this->db->escape((float)$data['shipping_cost']) . "',
						 additional_shipping = '" . $this->db->escape((float)$data['additional_shipping']) . "',
						 reserve_price = '" . $this->db->escape((float)$data['reserve_price']) . "',
						 duration = '" . $this->db->escape((float)$data['duration']) . "',
						 increment = '" . $this->db->escape((float)$data['increment']) . "',
						 shipping = '" . $this->db->escape((int)$data['shipping']) . "',
						 payment = '" . $this->db->escape((int)$data['payment']) . "',
						 international_shipping = '" . $this->db->escape((int)$data['international_shipping']) . "',
						 quantity = '" . $this->db->escape((int)$data['quantity']) . "',
						 relist = '" . $this->db->escape((int)$data['relist']) . "',
						 current_fee = '" . $this->db->escape((float)$data['current_fee']) . "',
						 tax = '" . $this->db->escape((int)$data['tax']) . "',
						 taxinc = '" . $this->db->escape((int)$data['taxinc']) . "',
						 buy_now_price = '" . $this->db->escape((float)$data['buy_now_price']) . "'
						 WHERE
						 auction_id = '" . (int)$auction_id . "'
						 ");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_description
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		foreach ($data['auction_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "auction_description
							 SET
							 auction_id = '" . (int)$auction_id . "',
							 language_id = '" . (int)$language_id . "',
							 name = '" . $this->db->escape($value['name']) . "',
							 description = '" . $this->db->escape($value['description']) . "',
							 tag = '" . $this->db->escape($value['tag']) . "',
							 meta_title = '" . $this->db->escape($value['meta_title']) . "',
							 meta_description = '" . $this->db->escape($value['meta_description']) . "',
							 meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_store
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		if (isset($data['auction_store'])) {
			foreach ($data['auction_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_store
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_photos
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		if (isset($data['auction_image'])) {
			foreach ($data['auction_image'] as $auction_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 image = '" . $this->db->escape($auction_image['image']) . "',
								 sort_order = '" . (int)$auction_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_category
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		if (isset($data['auction_category'])) {
			foreach ($data['auction_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_category
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_layout
						 WHERE
						 auction_id = '" . (int)$auction_id . "'");

		if (isset($data['auction_layout'])) {
			foreach ($data['auction_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_layout
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 store_id = '" . (int)$store_id . "',
								 layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias
						 WHERE
						 query = 'auction_id=" . (int)$auction_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
							 SET
							 query = 'auction_id=" . (int)$auction_id . "',
							 keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('auction');
		*/
	}

	
	
	public function copyAuction($auction_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "auction p WHERE p.auction_id = '" . (int)$auction_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['product_attribute'] = $this->getProductAttributes($product_id);
			$data['auction_description'] = $this->getAuctionDescriptions($auction_id);
			$data['product_discount'] = $this->getProductDiscounts($product_id);
			$data['product_filter'] = $this->getProductFilters($product_id);
			$data['auction_image'] = $this->getAuctionImages($auction_id);
			$data['product_option'] = $this->getProductOptions($product_id);
			$data['product_related'] = $this->getProductRelated($product_id);
			$data['product_reward'] = $this->getProductRewards($product_id);
			$data['product_special'] = $this->getProductSpecials($product_id);
			$data['auction_category'] = $this->getAuctionCategories($auction_id);
			$data['product_download'] = $this->getProductDownloads($product_id);
			$data['auction_layout'] = $this->getAuctionLayouts($auction_id);
			$data['auction_store'] = $this->getAuctionStores($auction_id);
			$data['product_recurrings'] = $this->getRecurrings($product_id);

			$this->addAuction($data);
		}
	}

	public function deleteAuction($auction_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "auctions WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_description WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_options WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_photos WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_category WHERE auction_id = '" . (int)$auction_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_layout WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_store WHERE auction_id = '" . (int)$auction_id . "'");
		
		//$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE auction_id = '" . (int)$auction_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'auction_id=" . (int)$auction_id . "'");
		

		$this->cache->delete('auction');
	}

	public function getAuctionDetails($auction_id) {
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "auction_details ad WHERE ad.auction_id = '" . (int)$auction_id . "'";							
								  
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getAuction($auction_id) {
		$sql = "SELECT DISTINCT *, (SELECT keyword
								  FROM " . DB_PREFIX . "url_alias
								  WHERE query = 'auction_id=" . (int)$auction_id . "')
								  AS keyword FROM " . DB_PREFIX . "auctions p
								  LEFT JOIN " . DB_PREFIX . "auction_details pd
								  ON (p.auction_id = pd.auction_id)
								  WHERE p.auction_id = '" . (int)$auction_id . "'";
								  
		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getAuctionOptions($auction_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "auction_options
		WHERE auction_id = '" . $auction_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}


	public function getAuctionsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auctions p
								  LEFT JOIN " . DB_PREFIX . "auction_description pd
								  ON (p.auction_id = pd.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_to_category p2c
								  ON (p.auction_id = p2c.auction_id)
								  WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
								  AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getAuctionDescriptions($auction_id) {
		$auction_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_description
								  WHERE auction_id = '" . (int)$auction_id . "'");

// fill out auctions stuff here								  
		foreach ($query->rows as $result) {
			$auction_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'subname'             => $result['subname'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $auction_description_data;
	}

	public function getAuctionCategories($auction_id) {
		$auction_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_category
								  WHERE auction_id = '" . (int)$auction_id . "'");

		foreach ($query->rows as $result) {
			$auction_category_data[] = $result['category_id'];
		}

		return $auction_category_data;
	}

	public function getAuctionImages($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_photos WHERE auction_id = '" . (int)$auction_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getAuctionStores($auction_id) {
		$auction_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_store
								  WHERE auction_id = '" . (int)$auction_id . "'");

		foreach ($query->rows as $result) {
			$auction_store_data[] = $result['store_id'];
		}

		return $auction_store_data;
	}

	public function getAuctionLayouts($auction_id) {
		$auction_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_layout
								  WHERE auction_id = '" . (int)$auction_id . "'");

		foreach ($query->rows as $result) {
			$auction_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $auction_layout_data;
	}

	public function getTotalAuctionsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "auction_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	public function getAuctionTypes($auction_id) {
		//$auction_type_data = array();

		$query = $this->db->query("SELECT auction_type AS type FROM " . DB_PREFIX . "auctions
								  WHERE auction_id = '" . (int)$auction_id . "'");

		return $query->row;
	}
	
	
	
	
	
} // End of Model