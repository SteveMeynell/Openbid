<?php
class ModelAuctionAuction extends Model {
    
    public function getAuctions($data = array(), $store_id = 0) {
        $sql = "SELECT a.auction_id AS auction_id, a.date_created AS date_created,
        aus.name AS status_name,
        CONCAT(c.firstname, ' ', c.lastname) AS seller,
        ad.current_bid AS current_bid, ad.start_date AS start_date, ad.end_date AS end_date  
        FROM " . DB_PREFIX . "auctions a  
         LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = a.customer_id) 
         LEFT JOIN " . DB_PREFIX . "auction_status aus ON (aus.auction_status_id = a.status AND aus.language_id = '" . (int)$this->config->get('config_language_id') . "') 
         LEFT JOIN " . DB_PREFIX . "auction_details ad ON (ad.auction_id = a.auction_id)";
        
        $auction_data = array();
        
        if (isset($data['filter_auction_status'])) {
			$implode = array();
            
			$auction_statuses = explode(',', $data['filter_auction_status']);

			foreach ($auction_statuses as $auction_status_id) {
				$implode[] = "a.status = '" . (int)$auction_status_id . "'";
			}

			if ($implode) {
				$sql .= " WHERE (" . implode(" OR ", $implode) . ")";
			}
		} else {
			$sql .= " WHERE a.status > '0'";
		}
        
        if (!empty($data['filter_auction_id'])) {
			$sql .= " AND a.auction_id = '" . (int)$data['filter_auction_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_created'])) {
			$sql .= " AND DATE(a.date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}

		$sort_data = array(
			'auction_id',
			'seller',
			'status',
			'date_created'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.auction_id";
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
//debuglog($sql);
		$query = $this->db->query($sql);

		return $query->rows;
	}
    
    public function getAuction($auction_id, $store_id = 0) {
        $auction_data = array();
        $sql = "SELECT ad.*, a.date_created, a.num_bids, a.bn_only, a.bold, a.highlighted, a.featured, a.bn_sale, a.store_id, 
		 ap.photo_url, acs.name AS status, c.firstname, c.lastname, c.email, c.telephone, c.address_id 
		FROM " . DB_PREFIX . "auctions a 
		 LEFT JOIN " . DB_PREFIX . "auction_details ad ON (ad.auction_id = a.auction_id)
		 LEFT JOIN " . DB_PREFIX . "auction_photos ap ON (ap.photo_id = ad.photo_id)
		  LEFT JOIN " . DB_PREFIX . "auction_status acs ON (acs.auction_status_id = ad.status AND acs.language_id = ad.language_id)
		  LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = a.customer_id)
		   WHERE a.auction_id = '" . (int)$auction_id . "' AND a.store_id = '" . (int)$store_id . "'";

		$sort_data = array(
			'a.auction_id',
			'ad.customer_id',
			'a.status',
			'a.date_created'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.auction_id";
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
        
        $sql = "SELECT * FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id)
        WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        
        $implode = array();
        
        if (!empty($data['filter_auction_id'])) {
            $implode[] = "a.auction_id = '" . (int)$data['filter_auction_id'] . "'";
        }
        if (!empty($data['filter_customer'])) {
            $implode[] = "ad.customer_id = '" . (int)$data['filter_customer'] . "'";
        }
        if (!empty($data['filter_auction_status'])) {
            $implode[] = "a.status = '" . (int)$data['filter_auction_status'] . "'";
        }
        if (!empty($data['filter_date_created'])) {
			$implode[] = "DATE(a.date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}
        if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'a.auction_id',
			'ad.customer_id',
			'a.status',
			'a.date_created'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY a.auction_id";
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
    public function editAuction($auction_id, $store_id = 0) {
        
        
    }
    
    public function deleteAuction($auction_id, $store_id = 0) {
        
    }
    
    
    
}