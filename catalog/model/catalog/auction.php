<?php
class ModelCatalogAuction extends Model {
	public function updateViewed($auction_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "auction SET viewed = (viewed + 1) WHERE auction_id = '" . (int)$auction_id . "'");
	}

	public function getAuction($auction_id) {
		$query = $this->db->query("
								  SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer,
								  (SELECT price FROM " . DB_PREFIX . "auction_discount pd2
								  WHERE pd2.auction_id = p.auction_id
								  AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
								  AND pd2.quantity = '1'
								  AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
								  AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
								  ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)
								  AS discount,
								  (SELECT price
								  FROM " . DB_PREFIX . "auction_special ps
								  WHERE ps.auction_id = p.auction_id
								  AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
								  AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
								  AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
								  ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)
								  AS special,
								  (SELECT points
								  FROM " . DB_PREFIX . "auction_reward pr
								  WHERE pr.auction_id = p.auction_id
								  AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "')
								  AS reward,
								  (SELECT ss.name
								  FROM " . DB_PREFIX . "stock_status ss
								  WHERE ss.stock_status_id = p.stock_status_id
								  AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "')
								  AS stock_status,
								  (SELECT wcd.unit
								  FROM " . DB_PREFIX . "weight_class_description wcd
								  WHERE p.weight_class_id = wcd.weight_class_id
								  AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "')
								  AS weight_class,
								  (SELECT lcd.unit
								  FROM " . DB_PREFIX . "length_class_description lcd
								  WHERE p.length_class_id = lcd.length_class_id
								  AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "')
								  AS length_class,
								  (SELECT AVG(rating) AS total
								  FROM " . DB_PREFIX . "review r1
								  WHERE r1.auction_id = p.auction_id
								  AND r1.status = '1'
								  GROUP BY r1.auction_id)
								  AS rating,
								  (SELECT COUNT(*) AS total
								  FROM " . DB_PREFIX . "review r2
								  WHERE r2.auction_id = p.auction_id
								  AND r2.status = '1'
								  GROUP BY r2.auction_id)
								  AS reviews, p.sort_order
								  FROM " . DB_PREFIX . "auction p
								  LEFT JOIN " . DB_PREFIX . "auction_description pd
								  ON (p.auction_id = pd.auction_id)
								  LEFT JOIN " . DB_PREFIX . "auction_to_store p2s
								  ON (p.auction_id = p2s.auction_id)
								  LEFT JOIN " . DB_PREFIX . "manufacturer m
								  ON (p.manufacturer_id = m.manufacturer_id)
								  WHERE p.auction_id = '" . (int)$auction_id . "'
								  AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
								  AND p.status = '1'
								  AND p.date_available <= NOW()
								  AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'auction_id'       => $query->row['auction_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getAuctions($data = array()) {
		$sql = "
		SELECT p.auction_id,
		(SELECT AVG(rating) AS total
		FROM " . DB_PREFIX . "review r1
		WHERE r1.auction_id = p.auction_id
		AND r1.status = '1'
		GROUP BY r1.auction_id)
		AS rating,
		(SELECT price
		FROM " . DB_PREFIX . "auction_discount pd2
		WHERE pd2.auction_id = p.auction_id
		AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
		AND pd2.quantity = '1'
		AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW())
		AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW()))
		ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)
		AS discount,
		(SELECT price
		FROM " . DB_PREFIX . "auction_special ps
		WHERE ps.auction_id = p.auction_id
		AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
		AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW())
		AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
		ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)
		AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "auction_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "auction_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "auction_filter pf ON (p2c.auction_id = pf.auction_id) LEFT JOIN " . DB_PREFIX . "auction p ON (pf.auction_id = p.auction_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "auction p ON (p2c.auction_id = p.auction_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "auction p";
		}

		$sql .= "
		LEFT JOIN " . DB_PREFIX . "auction_description pd
		ON (p.auction_id = pd.auction_id)
		LEFT JOIN " . DB_PREFIX . "auction_to_store p2s
		ON (p.auction_id = p2s.auction_id)
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND p.status = '1'
		AND p.date_available <= NOW()
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.auction_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
		}

		return $auction_data;
	}

	public function getAuctionSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.auction_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.auction_id = ps.auction_id AND r1.status = '1' GROUP BY r1.auction_id) AS rating FROM " . DB_PREFIX . "auction_special ps LEFT JOIN " . DB_PREFIX . "auction p ON (ps.auction_id = p.auction_id) LEFT JOIN " . DB_PREFIX . "auction_description pd ON (p.auction_id = pd.auction_id) LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.auction_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
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

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
		}

		return $auction_data;
	}

	public function getLatestAuctions($limit) {
		$auction_data = $this->cache->get('auction.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$auction_data) {
			$query = $this->db->query("SELECT p.auction_id FROM " . DB_PREFIX . "auction p LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			$this->cache->set('auction.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		}

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

	public function getBestSellerAuctions($limit) {
		$auction_data = $this->cache->get('auction.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$auction_data) {
			$auction_data = array();

			$query = $this->db->query("SELECT op.auction_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_auction op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "auctions` p ON (op.auction_id = p.auction_id) LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.auction_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$auction_data[$result['auction_id']] = $this->getAuction($result['auction_id']);
			}

			$this->cache->set('auction.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $auction_data);
		}

		return $auction_data;
	}

	public function getAuctionAttributes($auction_id) {
		$auction_attribute_group_data = array();

		$auction_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "auction_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.auction_id = '" . (int)$auction_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($auction_attribute_group_query->rows as $auction_attribute_group) {
			$auction_attribute_data = array();

			$auction_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "auction_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.auction_id = '" . (int)$auction_id . "' AND a.attribute_group_id = '" . (int)$auction_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($auction_attribute_query->rows as $auction_attribute) {
				$auction_attribute_data[] = array(
					'attribute_id' => $auction_attribute['attribute_id'],
					'name'         => $auction_attribute['name'],
					'text'         => $auction_attribute['text']
				);
			}

			$auction_attribute_group_data[] = array(
				'attribute_group_id' => $auction_attribute_group['attribute_group_id'],
				'name'               => $auction_attribute_group['name'],
				'attribute'          => $auction_attribute_data
			);
		}

		return $auction_attribute_group_data;
	}

	public function getAuctionOptions($auction_id) {
		$auction_option_data = array();

		$auction_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.auction_id = '" . (int)$auction_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($auction_option_query->rows as $auction_option) {
			$auction_option_value_data = array();

			$auction_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.auction_id = '" . (int)$auction_id . "' AND pov.auction_option_id = '" . (int)$auction_option['auction_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($auction_option_value_query->rows as $auction_option_value) {
				$auction_option_value_data[] = array(
					'auction_option_value_id' => $auction_option_value['auction_option_value_id'],
					'option_value_id'         => $auction_option_value['option_value_id'],
					'name'                    => $auction_option_value['name'],
					'image'                   => $auction_option_value['image'],
					'quantity'                => $auction_option_value['quantity'],
					'subtract'                => $auction_option_value['subtract'],
					'price'                   => $auction_option_value['price'],
					'price_prefix'            => $auction_option_value['price_prefix'],
					'weight'                  => $auction_option_value['weight'],
					'weight_prefix'           => $auction_option_value['weight_prefix']
				);
			}

			$auction_option_data[] = array(
				'auction_option_id'    => $auction_option['auction_option_id'],
				'auction_option_value' => $auction_option_value_data,
				'option_id'            => $auction_option['option_id'],
				'name'                 => $auction_option['name'],
				'type'                 => $auction_option['type'],
				'value'                => $auction_option['value'],
				'required'             => $auction_option['required']
			);
		}

		return $auction_option_data;
	}

	public function getAuctionDiscounts($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_discount WHERE auction_id = '" . (int)$auction_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getAuctionImages($auction_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_image WHERE auction_id = '" . (int)$auction_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getAuctionRelated($auction_id) {
		$auction_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_related pr LEFT JOIN " . DB_PREFIX . "auction p ON (pr.related_id = p.auction_id) LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE pr.auction_id = '" . (int)$auction_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$auction_data[$result['related_id']] = $this->getAuction($result['related_id']);
		}

		return $auction_data;
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
		$sql = "SELECT COUNT(DISTINCT p.auction_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "auction_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "auction_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "auction_filter pf ON (p2c.auction_id = pf.auction_id) LEFT JOIN " . DB_PREFIX . "auctions p ON (pf.auction_id = p.auction_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "auctions p ON (p2c.auction_id = p.auction_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "auctions p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "auction_description pd ON (p.auction_id = pd.auction_id) LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($auction_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "auction_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.auction_id = '" . (int)$auction_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}

	public function getProfiles($auction_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "auction_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.auction_id = " . (int)$auction_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalAuctionSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.auction_id) AS total FROM " . DB_PREFIX . "auction_special ps LEFT JOIN " . DB_PREFIX . "auction p ON (ps.auction_id = p.auction_id) LEFT JOIN " . DB_PREFIX . "auction_to_store p2s ON (p.auction_id = p2s.auction_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
