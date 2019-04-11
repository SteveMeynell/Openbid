<?php
class ModelCatalogReview extends Model {
	public function addReview($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "'");

		$review_id = $this->db->getLastId();

		$this->cache->delete('product');

		return $review_id;
	}

	public function editReview($review_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');
	}

	public function deleteReview($review_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');
	}

	public function getReview($review_id) {

		$sql = "SELECT 
		r.review_id, 
		r.seller_id, 
		r.seller_reviewed, 
		r.bidder_id, 
		r.bidder_reviewed, 
		r.date_added AS review_date, 
		r.auction_id, 
		ad.name, 
		sr.seller_review_id, 
		sr.seller_question1, 
		sr.seller_question2, 
		sr.seller_question3, 
		sr.seller_suggestion, 
		CONCAT(sc.firstname, ' ', sc.lastname) AS seller_name,
		sr.date_added AS seller_review_date, 
		br.bidder_review_id, 
		br.bidder_question1, 
		br.bidder_question2, 
		br.bidder_question3, 
		br.bidder_suggestion, 
		CONCAT(bc.firstname, ' ', bc.lastname) AS bidder_name,
		br.date_added AS bidder_review_date 
		FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "auction_description ad ON(ad.auction_id = r.auction_id) 
		LEFT JOIN " . DB_PREFIX . "seller_reviews sr ON(sr.seller_id = r.seller_id) 
		LEFT JOIN " . DB_PREFIX . "bidder_reviews br ON(br.bidder_id = r.bidder_id) 
		LEFT JOIN " . DB_PREFIX . "customer sc ON(sc.customer_id = r.seller_id) 
		LEFT JOIN " . DB_PREFIX . "customer bc ON(bc.customer_id = r.bidder_id) 
		WHERE r.review_id = '" . (int)$review_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		//debuglog($sql);

		$query = $this->db->query($sql);

		return $query->row;
	}

	public function getReviews($data = array()) {
		$sql = "SELECT 
		r.review_id, 
		r.seller_id, 
		r.bidder_id, 
		r.seller_reviewed, 
		r.bidder_reviewed, 
		r.date_added, 
		r.auction_id, 
		ad.name, 
		CONCAT(cs.firstname, ' ', cs.lastname) AS seller_name, 
		CONCAT(cb.firstname, ' ', cb.lastname) AS bidder_name 
		FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "auction_description ad 
		ON (r.auction_id = ad.auction_id) 
		LEFT JOIN " . DB_PREFIX . "customer cs 
		ON (r.seller_id = cs.customer_id) 
		LEFT JOIN " . DB_PREFIX . "customer cb 
		ON (r.bidder_id = cb.customer_id) 
		WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";


		if (!empty($data['filter_product'])) {
			$where .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$where .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$where .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if(isset($where)) {
			$sql += $where;
		}

		$sort_data = array(
			'seller_name',
			'bidder_name',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
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

	public function getTotalReviews($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "reviews";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalReviewsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");

		return $query->row['total'];
	}
}