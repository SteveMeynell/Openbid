<?php
class ModelAccountReview extends Model {

	public function addReview($data) {
		//debuglog($data);
		// insert a record into the review table with the site question answers
		if($this->db->escape($data['group']) == 'seller') {
			$group = '1';
		} elseif($this->db->escape($data['group']) == 'bidder') {
			$group = '2';
		} else {
			$group = 0;
		}
		$reviewId = $this->db->escape($data['review_id']);

		$this->db->query("INSERT INTO " . DB_PREFIX . "review 
		SET 
		customer_id = '" . (int)$this->db->escape($data['customer_id']) . "', 
		review_group = '" . $group . "', 
		question1 = '" . $this->db->escape($data['question1']) . "', 
		suggestion1 = '" . $this->db->escape($data['suggestion1']) . "', 
		question2 = '" . $this->db->escape($data['question2']) . "', 
		suggestion2 = '" . $this->db->escape($data['suggestion2']) . "', 
		question3 = '" . $this->db->escape($data['question3']) . "', 
		suggestion3 = '" . $this->db->escape($data['suggestion3']) . "', 
		question4 = '" . $this->db->escape($data['question4']) . "', 
		suggestion4 = '" . $this->db->escape($data['suggestion4']) . "', 
		question5 = '" . $this->db->escape($data['question5']) . "', 
		suggestion5 = '" . $this->db->escape($data['suggestion5']) . "', 
		question6 = '" . $this->db->escape($data['question6']) . "', 
		suggestion6 = '" . $this->db->escape($data['suggestion6']) . "', 
		final_suggestion = '" . $this->db->escape($data['final_suggestion']) . "'");



		if($this->db->escape($data['group']) == 'seller') {
			// update the reviews table with the seller reviewed
			$this->db->query("UPDATE " . DB_PREFIX . "reviews 
			SET seller_reviewed = 1 
			WHERE review_id = '" . $reviewId . "'");
			// need to get the bidders_id from the reviews table
			$bidderId = $this->db->query("SELECT bidder_id FROM " . DB_PREFIX . "reviews WHERE review_id = '" . $reviewId . "'")->row['bidder_id'];
			// insert into the sellers_reviews
			$this->db->query("INSERT INTO " . DB_PREFIX . "seller_reviews 
			SET 
			review_id = '" . $reviewId . "', 
			seller_id = '" . $this->db->escape($data['customer_id']) . "', 
			bidder_id = '" . $bidderId . "', 
			seller_question1 = '" . $this->db->escape($data['question1']) . "', 
			question1_suggestion = '" . $this->db->escape($data['suggestion1']) . "', 
			seller_question2 = '" . $this->db->escape($data['question2']) . "', 
			question2_suggestion = '" . $this->db->escape($data['suggestion2']) . "', 
			seller_question3 = '" . $this->db->escape($data['question3']) . "', 
			question3_suggestion = '" . $this->db->escape($data['suggestion3']) . "', 
			seller_suggestion = '" . $this->db->escape($data['final_suggestion']) . "'");
		} elseif($this->db->escape($data['group']) == 'bidder') {
			// update the reviews table with the bidder reviewed
			$this->db->query("UPDATE " . DB_PREFIX . "reviews 
			SET bidder_reviewed = 1 
			WHERE review_id = '" . $reviewId . "'");
			// need to get the sellers_id from the reviews table
			$sellerId = $this->db->query("SELECT seller_id FROM " . DB_PREFIX . "reviews WHERE review_id = '" . $reviewId . "'")->row['seller_id'];
			// insert into the bidders_reviews
			$this->db->query("INSERT INTO " . DB_PREFIX . "bidder_reviews 
			SET 
			review_id = '" . $reviewId . "', 
			bidder_id = '" . $this->db->escape($data['customer_id']) . "', 
			seller_id = '" . $sellerId . "', 
			bidder_question1 = '" . $this->db->escape($data['question1']) . "', 
			question1_suggestion = '" . $this->db->escape($data['suggestion1']) . "', 
			bidder_question2 = '" . $this->db->escape($data['question2']) . "', 
			question2_suggestion = '" . $this->db->escape($data['suggestion2']) . "', 
			bidder_question3 = '" . $this->db->escape($data['question3']) . "', 
			question3_suggestion = '" . $this->db->escape($data['suggestion3']) . "', 
			bidder_suggestion = '" . $this->db->escape($data['final_suggestion']) . "'");
		} else {
			// guest review
		}
		
		

		if (in_array('review', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/review');
			$overallRatings = $this->getTotalSiteReviews();

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$message  = $this->language->get('text_waiting') . "\n";
			$message .= sprintf($this->language->get('text_reviewer'), html_entity_decode($data['name'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= "Current Rating: \n";
			$message .= $this->language->get('text_question1') . "\n";
			$message .= sprintf($this->language->get('text_rating1'), html_entity_decode($data['question4'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_suggestion1'), html_entity_decode($data['suggestion4'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_question2') . "\n";
			$message .= sprintf($this->language->get('text_rating2'), html_entity_decode($data['question5'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_suggestion2'), html_entity_decode($data['suggestion5'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_question3') . "\n";
			$message .= sprintf($this->language->get('text_rating3'), html_entity_decode($data['question6'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_suggestion3'), html_entity_decode($data['suggestion6'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_overall_rating') . "\n";
			$message .= sprintf($this->language->get('text_overall_design_rating'), html_entity_decode($overallRatings['designRate'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_overall_navigation_rating'), html_entity_decode($overallRatings['navRate'], ENT_QUOTES, 'UTF-8')) . "\n";
			$message .= sprintf($this->language->get('text_overall_value_rating'), html_entity_decode($overallRatings['valueRate'], ENT_QUOTES, 'UTF-8')) . "\n\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();

			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_alert_email'));

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		// send email to the other user to remind them to fill out a review or to view reviews made about them.

	}


	public function getReviewsByCustomerId($customer_id, $start = 0, $limit = 20) {

		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT 
		r.*, ad.title AS auction_title, CONCAT(s.firstname, ' ', s.lastname) AS sellername, CONCAT(b.firstname, ' ', b.lastname) AS biddername 
		FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "auction_details ad 
		ON (r.auction_id = ad.auction_id) 
		LEFT JOIN " . DB_PREFIX . "customer s 
		ON (r.seller_id = s.customer_id) 
		LEFT JOIN " . DB_PREFIX . "customer b 
		ON (r.bidder_id = b.customer_id) 
		WHERE r.seller_id = '" . (int)$customer_id . "' 
		ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		$reviews['seller'] = $query->rows;
		// now add the link if they haven't reviewed yet.
		foreach($reviews['seller'] as $index => $review) {
			if(!$review['seller_reviewed']) {
				$reviews['seller'][$index]['state'] = 'review';
				$reviews['seller'][$index]['link'] = $this->url->link('account/review/write', 'review_id=' . $review['review_id'] . '&group=seller', true);
				$reviews['seller'][$index]['view'] = 'Please Review';
			} else {
				if($review['bidder_reviewed']) {
					$reviews['seller'][$index]['state'] = 'view';
					//$reviews['seller'][$index]['link'] = $this->url->link('account/review/view', 'review_id=' . $review['review_id'], true);
					$reviews['seller'][$index]['view'] = 'Thank you for Reviewing';
				} else {
					$reviews['seller'][$index]['state'] = 'remind';
					//$reviews['seller'][$index]['link'] = $this->url->link('account/review/sendReminder', 'review_id=' . $review['review_id'], true);
					$reviews['seller'][$index]['view'] = 'Bidder Not Reviewed';
				}
			}
		}

		$query = $this->db->query("SELECT 
		r.*, ad.title AS auction_title, CONCAT(s.firstname, ' ', s.lastname) AS sellername, CONCAT(b.firstname, ' ', b.lastname) AS biddername 
		FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "auction_details ad 
		ON (r.auction_id = ad.auction_id) 
		LEFT JOIN " . DB_PREFIX . "customer s 
		ON (r.seller_id = s.customer_id) 
		LEFT JOIN " . DB_PREFIX . "customer b 
		ON (r.bidder_id = b.customer_id) 
		WHERE r.bidder_id = '" . (int)$customer_id . "' 
		ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		$reviews['bidder'] = $query->rows;
		foreach($reviews['bidder'] as $index => $review) {
			if(!$review['bidder_reviewed']) {
				$reviews['bidder'][$index]['state'] = 'review';
				$reviews['bidder'][$index]['link'] = $this->url->link('account/review/write', 'review_id=' . $review['review_id'] . '&group=bidder', true);
				$reviews['bidder'][$index]['view'] = 'Please Review';
			} else {
				if($review['seller_reviewed']) {
					$reviews['bidder'][$index]['state'] = 'view';
					//$reviews['bidder'][$index]['link'] = $this->url->link('account/review/view', 'review_id=' . $review['review_id'], true);
					$reviews['bidder'][$index]['view'] = 'Thank you for Reviewing';
				} else {
					$reviews['bidder'][$index]['state'] = 'remind';
					$reviews['bidder'][$index]['link'] = $this->url->link('account/review/sendReminder', 'review_id=' . $review['review_id'], true);
					$reviews['bidder'][$index]['view'] = 'Seller Not Reviewed';
				}
			}
		}

		return $reviews;
	}

	public function getTotalSiteReviews() {
		$query = $this->db->query("SELECT ROUND(AVG(question4)) AS designRate, ROUND(AVG(question5)) AS navRate, ROUND(AVG(question6)) AS valueRate FROM " . DB_PREFIX . "review");
		return $query->row;
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

	public function getReviewersName($review_id, $filter) {
		$reviewId = $this->db->escape($review_id);
		$seller_where = " (SELECT seller_id FROM " . DB_PREFIX . "reviews r WHERE r.review_id='" . (int)$reviewId . "')";
		$bidder_where = " (SELECT bidder_id FROM " . DB_PREFIX . "reviews r WHERE r.review_id='" . (int)$reviewId . "')";
		$sql = "SELECT c.firstname AS firstname FROM " . DB_PREFIX . "customer c 
		WHERE customer_id = ";
		
		if($filter == 'seller') {
			$sql .= $seller_where;
		} else {
			$sql .= $bidder_where;
		}

		$result = $this->db->query($sql);
		return $result->row['firstname'];
	}

	public function getReview($review_id) {
		$reviewId = $this->db->escape($review_id);
		$sql = "SELECT * FROM " . DB_PREFIX . "bidder_reviews WHERE review_id='" . (int)$reviewId . "'";
		$bidder = $this->db->query($sql);
		$reviews['bidder'] = $bidder->row;
		$sql = "SELECT * FROM " . DB_PREFIX . "seller_reviews WHERE review_id='" . (int)$reviewId . "'";
		$seller = $this->db->query($sql);
		$reviews['seller'] = $seller->row;

		$reviews['auction_title'] = $this->getAuctionTitle($review_id);

		if(isset($reviews['bidder'])) {
			$reviews['bidder']['firstname'] = $this->getReviewersName($review_id, 'bidder');
		}
		if(isset($reviews['seller'])) {
			$reviews['seller']['firstname'] = $this->getReviewersName($review_id, 'seller');
		}

		return $reviews;

	}

	public function getAuctionTitle($review_id) {
		$reviewId = $this->db->escape($review_id);
		$sql = "SELECT title FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "auction_details ad ON(ad.auction_id = r.auction_id) 
		WHERE review_id = '" . (int)$reviewId . "'";
		$result = $this->db->query($sql);
		return $result->row['title'];
	}

	public function getWhoToRemind($review_id) {
		$reviewId = $this->db->escape($review_id);
		$sellers = $this->db->query("SELECT email, firstname FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "customer c ON(c.customer_id = r.seller_id) 
		WHERE review_id = '" . (int)$reviewId . "' AND seller_reviewed = '0'");
		$bidders = $this->db->query("SELECT email, firstname FROM " . DB_PREFIX . "reviews r 
		LEFT JOIN " . DB_PREFIX . "customer c ON(c.customer_id = r.seller_id) 
		WHERE review_id = '" . (int)$reviewId . "' AND bidder_reviewed = '0'");

		$reminders = array();
		if($sellers->num_rows) {
			$reminders['sellers']	= $sellers->row;
		}
		if($bidders->num_rows) {
			$reminders['bidders']	= $bidders->row;
		}

		return $reminders;
	}
	
	// end of model
}