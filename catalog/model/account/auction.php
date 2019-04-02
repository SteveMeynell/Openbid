<?php
class ModelAccountAuction extends Model {


  public function getTotalAuctions(){
    $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "auctions` a 
    LEFT JOIN `" . DB_PREFIX . "auction_to_store` a2s ON (a.auction_id = a2s.auction_id) 
    WHERE customer_id = '" . (int)$this->customer->getId() . "' 
    AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row['total'];
  }

	public function getAuction($auction_id) {
		$auctionId = $this->db->escape($auction_id);
		// main auction details
		$sql = "SELECT DISTINCT * FROM " . DB_PREFIX . "auctions a 
		LEFT JOIN " . DB_PREFIX . "auction_details ad1 ON(ad1.auction_id = a.auction_id) 
		LEFT JOIN " . DB_PREFIX . "auction_options ao ON(ao.auction_id = a.auction_id) 
		LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON(a2s.auction_id = a.auction_id) 
		WHERE a.auction_id = '" . $auctionId . "' 
		AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		$auction_info = $this->db->query($sql)->row;

		// description
		$sql = "SELECT * FROM " . DB_PREFIX . "auction_description 
		WHERE auction_id = '" . $auctionId . "'";
		$auctionDescriptions = $this->db->query($sql)->rows;

		foreach($auctionDescriptions as $description) {
			$auction_info['description'][$description['language_id']] = array(
				'name'					=> $description['name'],
				'subname'				=> $description['subname'],
				'description'		=> $description['description'],
				'tag'						=> $description['tag']
			);
		}

		// photos
		$sql = "SELECT * FROM " . DB_PREFIX . "auction_photos 
		WHERE auction_id = '" . $auctionId . "'";
		$auctionPhotos = $this->db->query($sql)->rows;

		foreach($auctionPhotos as $photo) {
			$auction_info['photos'][$photo['sort_order']] = $photo['image'];
		}

		// categories
		$sql = "SELECT * FROM " . DB_PREFIX . "auction_to_category 
		WHERE auction_id = '" . $auctionId . "'";
		$auctionCategories = $this->db->query($sql)->rows;

		foreach($auctionCategories as $category) {
			$auction_info['category'][] = $category['category_id'];
		}

		
		return $auction_info;
	}
  public function getAuctions($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
    }
    
    $sql = "SELECT 
    a.auction_id, 
    a.status, 
    a.date_created, 
    a.current_fee, 
    a.viewed, 
    a.winning_bid, 
    a.num_bids, 
		ao.buy_now_only,
    ad.title, 
    ad.start_date, 
    ad.end_date, 
    ad.reserve_price, 
    ad.buy_now_price 
    FROM `" . DB_PREFIX . "auctions` a 
		LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id) 
		LEFT JOIN " . DB_PREFIX . "auction_options ao ON (a.auction_id = ao.auction_id) 
    LEFT JOIN " . DB_PREFIX . "auction_to_store a2s ON (a.auction_id = a2s.auction_id) 
    WHERE a.customer_id = '" . (int)$this->customer->getId() . "' 
    AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
    ORDER BY a.auction_id DESC LIMIT " . (int)$start . "," . (int)$limit;

		$query = $this->db->query($sql);

		return $query->rows;
	}

  public function getAuctionStatusByType($status_code){
    $query = "SELECT name FROM " . DB_PREFIX . "auction_status WHERE auction_status_id = '" . $this->db->escape($status_code) . "'";
    $result = $this->db->query($query);
    return $result->row['name'];
  }

	public function addAuction($data) {
		//debuglog("Model Account Auction addAuction");
		//debuglog($data);
		// add in the actual auction table
		if(isset($data['relist'])) {
			$mainRelistNumber = $this->db->escape($data['relist']);
		} else {
			$mainRelistNumber = $this->db->escape($data['num_relist']);
		}

		$sellerId = $this->db->escape($data['seller_id']);
		$this->db->query("INSERT INTO " . DB_PREFIX . "auctions
						 SET
						 customer_id = '" . $sellerId . "',
						 auction_type = '" . $this->db->escape($data['auction_type']) . "',
						 status = '" . $this->db->escape($data['auction_status']) . "',
						 num_relist = '" . $this->db->escape($data['num_relist']) . "', 
						 relist = '" . $mainRelistNumber . "', 
						 date_created = NOW() 
						 ");

		$auction_id = $this->db->getLastId();

		$this->load->model('tool/upload');
		if (isset($data['main_image'])) {
			if (is_dir(DIR_IMAGE . 'catalog/auctions/' . $sellerId) || mkdir(DIR_IMAGE . 'catalog/auctions/' . $sellerId)) {
				if (!is_file(DIR_IMAGE . $data['main_image'])) {
					$fileInfo = $this->model_tool_upload->getUploadByCode($data['main_image']);
					if (rename(DIR_UPLOAD . $fileInfo['filename'], DIR_IMAGE . 'catalog/auctions/' . $sellerId . '/' . $fileInfo['name'])) {
						$this->model_tool_upload->deleteUploadById($fileInfo['upload_id']);
						$this->db->query("UPDATE " . DB_PREFIX . "auctions
							 SET
							 main_image = 'catalog/auctions/" . $sellerId . '/' . $fileInfo['name'] . "'
							 WHERE auction_id = '" . (int)$auction_id . "'");
					}
				} else {
					$this->db->query("UPDATE " . DB_PREFIX . "auctions
							 SET
							 main_image = '" . $data['main_image'] . "' 
							 WHERE
							 auction_id = '" . $auction_id . "'");
				}
			} else {
			$this->db->query("UPDATE " . DB_PREFIX . "auctions
							 SET
							 main_image = 'catalog/Folder.jpg'
							 WHERE
							 auction_id = '" . $auction_id . "'");
			}
		}

		if (isset($data['auction_image'])) {
			if (is_dir(DIR_IMAGE . 'catalog/auctions/' . $sellerId)) {
				foreach ($data['auction_image'] as $auction_image) {
					if (!is_file(DIR_IMAGE . $auction_image['image'])) {
						$fileInfo = $this->model_tool_upload->getUploadByCode($auction_image['image']);
						if (rename(DIR_UPLOAD . $fileInfo['filename'], DIR_IMAGE . 'catalog/auctions/' . $sellerId . '/' . $fileInfo['name'])) {
							$this->model_tool_upload->deleteUploadById($fileInfo['upload_id']);

							$this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos
										SET auction_id = '" . (int)$auction_id . "',
										image = 'catalog/auctions/" . $sellerId . '/' . $fileInfo['name'] . "', 
										sort_order = '" . (int)$auction_image['sort_order'] . "'");
						}
					} else {
						$this->db->query("UPDATE " . DB_PREFIX . "auction_photos
										SET auction_id = '" . (int)$auction_id . "',
										image = 'catalog/auctions/" . $sellerId . '/' . $auction_image['image'] . "', 
										sort_order = '" . (int)$auction_image['sort_order'] . "' 
										WHERE auction_id = '" . (int)$auction_id . "'");
					}
				}
			}
		}

	
		foreach ($data['auction_description'] as $language_id => $value) {
			$title = $value['name'];
			$subtitle = $value['subname'];
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
							 meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'
							 ");
		}

		
		// details
		$this->db->query("INSERT INTO " . DB_PREFIX . "auction_details
						 SET
						 auction_id = '" . (int)$auction_id . "',
						 title = '" . $this->db->escape($title) . "',
						 subtitle = '" . $this->db->escape($subtitle) . "',
						 start_date = '" . $this->db->escape($data['custom_start_date']) ."',
						 end_date = '" . $this->db->escape($data['custom_end_date']) ."',
						 min_bid = '" . $this->db->escape((float)$data['min_bid']) . "',
						 shipping_cost = '" . $this->db->escape((float)$data['shipping_cost']) . "',
						 additional_shipping = '" . $this->db->escape((float)$data['additional_shipping']) . "',
						 reserve_price = '" . $this->db->escape((float)$data['reserve_price']) . "',
						 duration = '" . $this->db->escape($data['duration']) . "',
						 increment = '" . $this->db->escape($data['increment']) . "',
						 shipping = '" . $this->db->escape((int)$data['shipping']) . "',
						 payment = '0',
						 international_shipping = '" . $this->db->escape((int)$data['international_shipping']) . "',
						 initial_quantity = '" . $this->db->escape($data['initial_quantity']) . "',
						 buy_now_price = '" . $this->db->escape((float)$data['buy_now_price']) . "'
						 ");
		
		// options
		$this->db->query("INSERT INTO " . DB_PREFIX . "auction_options
						 SET
						 auction_id = '" . (int)$auction_id . "',
						 bolded_item = '" . $this->db->escape($data['bolded_item']) . "',
						 on_carousel = '" . $this->db->escape($data['on_carousel']) . "',
						 buy_now_only = '" . $this->db->escape($data['buy_now_only']) . "',
						 featured = '" . $this->db->escape($data['featured']) . "',
						 highlighted = '" . $this->db->escape($data['highlighted']) . "',
						 slideshow = '" . $this->db->escape($data['slideshow']) . "',
						 social_media = '" . $this->db->escape($data['social_media']) . "',
						 auto_relist = '" . $this->db->escape($data['auto_relist']) . "'
						 ");
		
		
		if (isset($data['auction_store'])) {
			foreach ($data['auction_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_store
								 SET
								 auction_id = '" . (int)$auction_id . "',
								 store_id = '" . (int)$store_id . "'");
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

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'auction_id=" . (int)$auction_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('auction');

		return $auction_id;
		
	}


	public function editAuction($data) {
		// add in the actual auction table
		//debuglog("Model Account Auction Edit");
		
		$auction_id = $this->db->escape($data['auction_id']);
		$sellerId = $this->customer->getId();
		$this->db->query("UPDATE " . DB_PREFIX . "auctions
						 SET
						 num_relist = '" . $this->db->escape($data['num_relist']) . "', 
						 modified_by = '" . $sellerId . "', 
						 date_modified = NOW() 
						 WHERE auction_id = '" . $auction_id . "' 
						 ");

		$this->load->model('tool/upload');
		if (!is_dir(DIR_IMAGE . 'catalog/auctions/' . $sellerId)) {
			mkdir(DIR_IMAGE . 'catalog/auctions/' . $sellerId);
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "auction_photos WHERE auction_id = '" . (int)$auction_id . "'");

			foreach($data['uploaded_images'] as $key => $imageData) {
				if($imageData <> '') {
					if(file_exists(DIR_IMAGE . $imageData)) {
						// file just need to be added
						if ($key === 'main_image') {
							$this->db->query("UPDATE " . DB_PREFIX . "auctions
									SET
									main_image = '" . $imageData . "' 
									WHERE auction_id = '" . (int)$auction_id . "'");
						} else {
							$this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos
										SET 
										image = '" . $imageData . "', 
										sort_order = '" . (int)$key . "', 
										auction_id = '" . (int)$auction_id . "'");
						}
					} else {
						// must be the code
						$fileInfo = $this->model_tool_upload->getUploadByCode($imageData);
						// move the file
						rename(DIR_UPLOAD . $fileInfo['filename'], DIR_IMAGE . 'catalog/auctions/' . $sellerId . '/' . $fileInfo['name']);
						$this->model_tool_upload->deleteUploadById($fileInfo['upload_id']);
						// could be either the main image or an additional one
						//debuglog($key);
						if ($key === 'main_image') {
							//debuglog("hmm shouldn't be here");
							$this->db->query("UPDATE " . DB_PREFIX . "auctions
									SET
									main_image = 'catalog/auctions/" . $sellerId . '/' . $fileInfo['name'] . "'
									WHERE auction_id = '" . (int)$auction_id . "'");
						} else {
							//debuglog("at the correct insert");
							$this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos
										SET 
										image = 'catalog/auctions/" . $sellerId . '/' . $fileInfo['name'] . "', 
										sort_order = '" . (int)$key . "', 
										auction_id = '" . (int)$auction_id . "'");
						}
					} 
				} else {
					debuglog("empty");
					debuglog($imageData);
				}
			}
			
	
	
		foreach ($data['auction_description'] as $language_id => $value) {
			$title = $value['name'];
			$subtitle = $value['subname'];
			
			// auction keywords
      $seader = $title . ' ' . (null !== $subtitle ? $subtitle .' ': '') . $value['description'];
			$keywords = make_keywords($seader);
						
			$addon_keywords = 'For sale ' . $title . ', Auctioning ' . $title .', ';
			$tag_limit = array('limit_keywords_to' => 5);

			if ($value['tag'] == '') {
        $value['tag'] = make_keywords($seader,$tag_limit);
      }


			$this->db->query("UPDATE " . DB_PREFIX . "auction_description
							 SET
							 language_id = '" . (int)$language_id . "',
							 name = '" . $this->db->escape($value['name']) . "',
							 subname = '" . $this->db->escape($value['subname']) . "',
							 description = '" . $this->db->escape($value['description']) . "',
							 tag = '" . $this->db->escape($value['tag']) . "',
							 meta_title = 'Auctioning " . $title . "',
							 meta_description = '" . strip_tags($value['description']) . "',
							 meta_keyword = '" . $addon_keywords . $keywords . ', ' . $value['tag'] . "' 
							 WHERE auction_id = '" . (int)$auction_id . "'
							 ");
		}

		
		// details

		$this->db->query("UPDATE " . DB_PREFIX . "auction_details
						 SET
						 title = '" . $this->db->escape($title) . "',
						 subtitle = '" . $this->db->escape($subtitle) . "',
						 min_bid = '" . $this->db->escape((float)$data['min_bid']) . "',
						 shipping_cost = '" . $this->db->escape((float)$data['shipping_cost']) . "',
						 additional_shipping = '" . $this->db->escape((float)$data['international_shipping_cost']) . "',
						 reserve_price = '" . $this->db->escape((float)$data['reserve_bid']) . "',
						 duration = '" . $this->db->escape($data['auction_duration']) . "',
						 shipping = '" . $this->db->escape((int)$data['shipping']) . "',
						 international_shipping = '" . $this->db->escape((int)$data['international_shipping']) . "',
						 buy_now_price = '" . $this->db->escape((float)$data['buy_now_price']) . "' 
						 WHERE auction_id = '" . (int)$auction_id . "'
						 ");
		
		// options
		$sql = "UPDATE " . DB_PREFIX . "auction_options
		SET
		bolded_item = '" . $this->db->escape($data['bolded_option']) . "', ";
		
		$sql .= isset($data['carousel_option'])?" on_carousel = '" . $this->db->escape($data['carousel_option']) . "', ":'';
		$sql .= isset($data['featured_option'])?" featured = '" . $this->db->escape($data['featured_option']) . "', ":'';
		$sql .= isset($data['slideshow_option'])?" slideshow = '" . $this->db->escape($data['slideshow_option']) . "', ":'';

		$sql .= "buy_now_only = '" . $this->db->escape($data['buy_now_only']) . "', 
		highlighted = '" . $this->db->escape($data['highlighted_option']) . "', 
		social_media = '" . $this->db->escape($data['social_option']) . "', 
		auto_relist = '" . $this->db->escape($data['auto_relist']) . "' 
		WHERE auction_id = '" . (int)$auction_id . "'";

		$this->db->query($sql);
		

		if (isset($data['auction_category'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "auction_to_category WHERE auction_id = '" . (int)$auction_id . "'");
			foreach ($data['auction_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_category
								 SET 
								 category_id = '" . (int)$category_id . "', 
								 auction_id = '" . (int)$auction_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'auction_id=" . (int)$auction_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		return $auction_id;

		
	}

	public function getAuctionTitle ($auction_id) {
		$auctionId = $this->db->escape($auction_id);
		$query = "SELECT title FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . $auctionId . "'";
		return $this->db->query($query)->row['title'];
	}

	public function getHighestBid($auction_id) {
		$auctionId = $this->db->escape($auction_id);
		$query = "SELECT bid_amount FROM " . DB_PREFIX . "bid_history 
		WHERE auction_id = '" . $auction_id . "' ORDER BY bid_amount DESC";
		$result = $this->db->query($query);
		$highestBid = $result->row;
		if (!isset($highestBid['bid_amount'])) {
			$query = "SELECT bid_amount FROM " . DB_PREFIX . "current_bids 
		WHERE auction_id = '" . $auction_id . "' ORDER BY bid_amount DESC";
		$result = $this->db->query($query);
		$highestBid = $result->row;
		}

		if(!isset($highestBid['bid_amount'])) {
			return '0';
		} else {
			return $highestBid['bid_amount'];
		}
	}

  // End of Model
}