<?php
class ModelAuctionAuction extends Model {
    
    public function openAuction($auction_id){
        $auctionId = $this->db->escape($auction_id);

        $query = $this->db->query("SELECT CONCAT(c.firstname,' ',c.lastname) AS fullname, c.email AS email_address, ad.title AS title 
        FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad
        ON (a.auction_id = ad.auction_id) 
        LEFT JOIN " . DB_PREFIX . "customer c
        ON (a.customer_id = c.customer_id)
        WHERE a.auction_id = '" . (int)$auctionId . "'");
        $sellerInfo = $query->row;
        $sellerInfo['type'] = 'opening';

        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '2' 
        WHERE a.auction_id = '" . (int)$auctionId . "'");

        return $sellerInfo;
    }

    public function closeAuction($auction_id){
        $auctionId = $this->db->escape($auction_id);

        $query = $this->db->query("SELECT CONCAT(c.firstname,' ',c.lastname) AS fullname, c.email AS email_address
        FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad
        ON (a.auction_id = ad.auction_id) 
        LEFT JOIN " . DB_PREFIX . "customer c
        ON (a.customer_id = c.customer_id)
        WHERE a.auction_id = '" . (int)$auctionId . "'");
        $sellerInfo = $query->row;

        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '3' 
        WHERE a.auction_id = '" . (int)$auctionId . "'");

        return $sellerInfo;
    }

    public function hasExpired($auction_id) {
        $auctionId = $this->db->escape($auction_id);

        $query = $this->db->query("SELECT auction_id FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . (int)$auctionId . "' AND NOW() - end_date >= 0");
        $results = $query->row;
        if(isset($results['auction_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isRelistable($auction_id) {
        $auctionId = $this->db->escape($auction_id);
        $query = $this->db->query("SELECT a.relist, ao.auto_relist FROM " . DB_PREFIX . "auctions a 
        LEFT JOIN " . DB_PREFIX . "auction_options ao ON(ao.auction_id = a.auction_id) 
        WHERE a.auction_id = '" . (int)$auctionId . "' AND a.status = '2'");
        $results = $query->row;
        if ($results['auto_relist'] == '1' && $results['relist'] > '0') {
            return true;
        } else {
            return false;
        }
    }

    public function getReserveBid($auction_id){
        $auctionId = $this->db->escape($auction_id);

        $query = $this->db->query("SELECT reserve_price FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . $auctionId . "'");
        $result = $query->row;
        return $result['reserve_price'];
    }

    public function relistAuction($auction_id) {
        $originalAuctionId = $this->db->escape($auction_id);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auctions WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $original = $query->row;

        $newRelist = $original['relist'] - 1;
        $thisRelisting = $original['num_relist'] - $newRelist;
        $currentDate = $this->db->query("SELECT NOW() AS currenttime")->row;
        $this->db->query("INSERT INTO " . DB_PREFIX . "auctions 
        SET 
            customer_id = '" . $original['customer_id'] . "', 
            auction_type = '" . $original['auction_type'] . "', 
            date_created = '" . $currentDate['currenttime'] . "', 
            status = '1', 
            num_relist = '" . $original['num_relist'] . "', 
            relist = '" . $newRelist . "'");

        $newAuctionId = $this->db->getLastId();
        // copy photos and update main_image
        $this->db->query("UPDATE " . DB_PREFIX . "auctions 
        SET 
        main_image = '" . $original['main_image'] . "' 
        WHERE auction_id = '" . (int)$newAuctionId . "'");
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_photos WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $photos = $query->rows;
        foreach($photos as $photo) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "auction_photos 
            SET 
            auction_id = '" . $newAuctionId . "', 
            sort_order = '" . $photo['sort_order'] . "', 
            image = '" . $photo['image'] . "'");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_description WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $results = $query->rows;

        foreach ($results as $language_id => $value) {
            $languageId = (int)$language_id + 1;
            if(substr($value['name'],-8) == 'Relisted') {
                $name = substr($this->db->escape($value['name']), 0, -13) . ' - ' . strval($thisRelisting) . 'XRelisted';
                $metaTitle = substr($this->db->escape($value['meta_title']), 0, -13) . ' - ' . strval($thisRelisting) . 'XRelisted';
            } else {
                $name = $this->db->escape($value['name']) . ' - ' . strval($thisRelisting) . 'XRelisted';
                $metaTitle = $this->db->escape($value['meta_title']) . ' - ' . strval($thisRelisting) . 'XRelisted';
            }

			$this->db->query("INSERT INTO " . DB_PREFIX . "auction_description
							 SET
							 auction_id = '" . (int)$newAuctionId . "',
							 language_id = '" . (int)$languageId . "',
							 name = '" . $name . "',
							 subname = '" . $this->db->escape($value['subname']) . "',
							 description = '" . $this->db->escape($value['description']) . "',
							 tag = '" . $this->db->escape($value['tag']) . "',
							 meta_title = '" . $metaTitle . "',
							 meta_description = '" . $this->db->escape($value['meta_description']) . "',
							 meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'
							 ");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $result = $query->row;

        $auctionInfo['seller_id'] = $original['customer_id'];
        $auctionInfo['title'] = $result['title'];
        $auctionInfo['auction_id'] = $newAuctionId;
        
        $grace_period = '1';
        $endlength = strval(($result['duration'] * 24) + $grace_period);

        $newStartDate = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $grace_period . " HOUR) AS newStartDate")->row;
        $newEndDate = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL " . $endlength . " HOUR) AS newEndDate")->row;
        $auctionInfo['start_date'] = $newStartDate['newStartDate'];

        $this->db->query("INSERT INTO " . DB_PREFIX . "auction_details 
        SET 
            auction_id = '" . (int)$newAuctionId . "',
            title = '" . $result['title'] . "-Relist', 
            subtitle = '" . $result['subtitle'] . "', 
            min_bid = '" . $result['min_bid'] . "', 
            shipping_cost = '" . $result['shipping_cost'] . "', 
            additional_shipping = '" . $result['additional_shipping'] . "', 
            reserve_price = '" . $result['reserve_price'] . "', 
            duration = '" . $result['duration'] . "', 
            increment = '" . $result['increment'] . "', 
            shipping = '" . $result['shipping'] . "', 
            payment = '" . $result['payment'] . "', 
            international_shipping = '" . $result['international_shipping'] . "', 
            initial_quantity = '" . $result['initial_quantity'] . "', 
            quantity = '" . $result['quantity'] . "', 
            current_fee = '" . $result['current_fee'] . "', 
            buy_now_price = '" . $result['buy_now_price'] . "', 
            start_date = '" . $newStartDate['newStartDate'] . "', 
            end_date = '" . $newEndDate['newEndDate'] . "'");


        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_options WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $result = $query->row;
        $auctionInfo['options'] = $result;

        $this->db->query("INSERT INTO " . DB_PREFIX . "auction_options 
        SET 
            auction_id = '" . $newAuctionId . "',  
            custom_bid_increments = '" . $result['custom_bid_increments'] . "', 
            bolded_item = '" . $result['bolded_item'] . "', 
            on_carousel = '" . $result['on_carousel'] . "', 
            buy_now_only = '" . $result['buy_now_only'] . "', 
            featured = '" . $result['featured'] . "', 
            highlighted = '" . $result['highlighted'] . "', 
            slideshow = '" . $result['slideshow'] . "', 
            social_media = '" . $result['social_media'] . "', 
            auto_relist = '" . $result['auto_relist'] . "'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_category WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $results = $query->rows;

        foreach($results as $category) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_category 
            SET 
            auction_id = '" . $newAuctionId . "', 
            category_id = '" . $category['category_id'] . "'");
        }

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_layout WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $results = $query->rows;

        foreach($results as $layout) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_layout 
            SET 
            auction_id = '" . $newAuctionId . "', 
            store_id = '" . $layout['store_id'] . "', 
            layout_id = '" . $layout['layout_id'] . "'");
        }
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_to_store WHERE auction_id = '" . (int)$originalAuctionId . "'");
        $results = $query->rows;

        foreach($results as $result) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "auction_to_store 
            SET 
            auction_id = '" . $newAuctionId . "', 
            store_id = '" . $result['store_id'] . "'");
        }
        
        return $auctionInfo;
    }
    
    public function getBuyNowPrice($auction_id){
        $sql = "SELECT buy_now_price FROM " . DB_PREFIX . "auction_details 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function closeWonAuction($auction_id){
        $query = "SELECT  
            ad.title AS title, 
            s.customer_id AS seller, 
            bh.bidder_id AS bidder, 
            bh.bid_amount AS bid_amount, 
            CONCAT(s.firstname, ' ', s.lastname) AS seller_name, 
            s.email AS seller_email, 
            CONCAT(b.firstname, ' ', b.lastname) AS bidder_name, 
            b.email AS bidder_email 
            FROM " . DB_PREFIX . "auctions a 
            LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id) 
            LEFT JOIN " . DB_PREFIX . "bid_history bh ON (a.auction_id = bh.auction_id) 
            LEFT JOIN " . DB_PREFIX . "customer s ON (a.customer_id = s.customer_id) 
            LEFT JOIN " . DB_PREFIX . "customer b ON (bh.bidder_id = b.customer_id) 
            WHERE a.auction_id = '" . $this->db->escape($auction_id) . "' 
            AND bh.winner = '1'";

        $results = $this->db->query($query);
        $auctionInfo = $results->row;

        $sql = "UPDATE " . DB_PREFIX . "auctions 
        SET status = '3', 
        winning_bid = '" . (float)$auctionInfo['bid_amount'] . "' 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $this->db->query($sql);

        // write a general review record with auctionId, sellersId, biddersId.  The other columns will be default.
        $sql = "INSERT INTO " . DB_PREFIX . "reviews 
        SET 
        auction_id = '" . $this->db->escape($auction_id) . "', 
        seller_id = '" . $auctionInfo['seller'] . "', 
        bidder_id = '" . $auctionInfo['bidder'] . "'";
        $this->db->query($sql);
        return $auctionInfo;
    }

    public function getAuctionDurations(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "durations ORDER BY duration");
        return $query->rows;
    }
    
    public function getAuctionTypes(){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "auction_types");
        return $query->rows;
    }
    
    public function getAuctionStatus($auctionId) {
        $results = $this->db->query("SELECT status FROM " . DB_PREFIX . "auctions WHERE auction_id = '" . $this->db->escape($auctionId) . "'");
        return $results->row['status'];
    }

    public function getAuctionStatusByOrder($order_id) {
        $orderId = $this->db->escape($order_id);
        $sql = "SELECT oa.auction_id, a.status FROM " . DB_PREFIX . "order_auction oa 
        LEFT JOIN " . DB_PREFIX . "auctions a ON (oa.auction_id = a.auction_id) 
        WHERE oa.order_id = '" . (int)$orderId . "'";
        debuglog($sql);
        $query = $this->db->query($sql);
        return $query->rows;
    }
    
    public function getAuctionInfoOfWinner($auction_id) {
        $auctionId = $this->db->escape($auction_id);

        $query = "SELECT a.customer_id AS customer_id, ad.title AS title FROM " . DB_PREFIX . "auctions a 
        LEFT JOIN " . DB_PREFIX . "auction_details ad ON (a.auction_id = ad.auction_id) 
        WHERE a.auction_id = '" . $auctionId . "'";
        return $this->db->query($query)->row;
    }

    public function extendAuction($auction_id) {
        $auctionId = $this->db->escape($auction_id);
        $extendTime = $this->config->get('config_auction_extension_for') * 60;
        $sql = "UPDATE " . DB_PREFIX . "auction_details 
        SET 
        end_date = ADDTIME(end_date, '" . $extendTime . "') 
        WHERE auction_id = '" . (int)$auctionId . "'";
        $this->db->query($sql);
        $query = $this->db->query("SELECT end_date FROM " . DB_PREFIX . "auction_details WHERE auction_id = '" . (int)$auctionId . "'");
        return $query->row['end_date'];
    }

    // End of Model
}