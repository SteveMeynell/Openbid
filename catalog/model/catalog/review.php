<?php
class ModelCatalogReview extends Model {

	public function getReviewsByProductId($product_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByProductId($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}

	public function getReviewsBySellerId($seller_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		//debuglog("SELECT r.bidder_review_id, r.review_id, r.bidder_id, bc.firstname, r.bidder_suggestion, r.date_added FROM " . DB_PREFIX . "bidder_reviews r LEFT JOIN " . DB_PREFIX . "customer bc ON (bc.customer_id = r.bidder_id) WHERE r.seller_id = '" . (int)$seller_id . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		$sql = "SELECT r.bidder_review_id, r.review_id, r.bidder_id,	bc.firstname,	r.bidder_suggestion, DATE_FORMAT(r.date_added,'%a %b %e, %Y') FROM " . DB_PREFIX . "bidder_reviews r LEFT JOIN " . DB_PREFIX . "customer bc ON (bc.customer_id = r.bidder_id) WHERE r.seller_id = '" . (int)$seller_id . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit;
		//debuglog($sql);
		$query = $this->db->query("SELECT 
		r.bidder_review_id, 
		r.review_id, 
		r.bidder_id, 
		bc.firstname,	
		r.bidder_suggestion, 
		DATE_FORMAT(r.date_added,'%a %b %e, %Y') as date_added
		FROM " . DB_PREFIX . "bidder_reviews r 
		LEFT JOIN " . DB_PREFIX . "customer bc 
		ON (bc.customer_id = r.bidder_id) 
		WHERE r.seller_id = '" . (int)$seller_id . "' 
		ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		$reviews = $query->rows;
		foreach($reviews as $index => $review) {
			$questions = $this->db->query("SELECT 
			ROUND(AVG(bidder_question1)) as communications, 
			ROUND(AVG(bidder_question2)) as shipping, 
			ROUND(AVG(bidder_question3)) as quality 
			FROM " . DB_PREFIX . "bidder_reviews 
			WHERE seller_id = '" . $seller_id . "' AND bidder_id = '" . $review['bidder_id'] . "'");
			$reviews[$index]['ratings'] = $questions->row;
		}

		return $reviews;
	}

	public function getTotalReviewsBySellerId($seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "bidder_reviews 
		WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row['total'];
	}

	public function getTotalRateBySellerId($seller_id) {
		$query = $this->db->query("SELECT AVG(bidder_question1) AS rating FROM " . DB_PREFIX . "bidder_reviews 
		WHERE seller_id = '" . (int)$seller_id . "'");
		$rating = $query->row['rating'];
		$query = $this->db->query("SELECT AVG(bidder_question2) AS rating FROM " . DB_PREFIX . "bidder_reviews 
		WHERE seller_id = '" . (int)$seller_id . "'");
		$rating += $query->row['rating'];
		$query = $this->db->query("SELECT AVG(bidder_question3) AS rating FROM " . DB_PREFIX . "bidder_reviews 
		WHERE seller_id = '" . (int)$seller_id . "'");
		$rating += $query->row['rating'];

		return round($rating/3, 0, PHP_ROUND_HALF_UP);
	}

	public function getTotalReviewsByBidderId($bidder_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_reviews 
		WHERE bidder_id = '" . (int)$bidder_id . "'");

		return $query->row['total'];
	}

	public function getTotalRateByBidderId($bidder_id) {
		$query = $this->db->query("SELECT AVG(seller_question1) AS rating FROM " . DB_PREFIX . "seller_reviews 
		WHERE bidder_id = '" . (int)$bidder_id . "'");
		$rating = $query->row['rating'];
		$query = $this->db->query("SELECT AVG(seller_question2) AS rating FROM " . DB_PREFIX . "seller_reviews 
		WHERE bidder_id = '" . (int)$bidder_id . "'");
		$rating += $query->row['rating'];
		$query = $this->db->query("SELECT AVG(seller_question3) AS rating FROM " . DB_PREFIX . "seller_reviews 
		WHERE bidder_id = '" . (int)$bidder_id . "'");
		$rating += $query->row['rating'];

		return round($rating/3, 0, PHP_ROUND_HALF_UP);
	}

	

	// end of model
}