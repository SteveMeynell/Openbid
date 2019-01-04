<?php
class ModelAuctionAuction extends Model {
    
	public function relistClosingAuctions(){
    
        /* First for auctions that have winners:
         *      Winners:    Have placed a bid that is higher than the reserved bid or if that is zero,
         *                  than higher that the min_bid. And of course is the highest bid.
         *      Winners should be notified of their win.
         *      Sellers should be notified of the winning bid.
         *      Auction closed
         *      Bids moved to bid_history
         *      Bids removed from current_bids
         *      Depending on how some options are done, removed from their lists too.
        */      
         
        /* Next check for auctions that have no winners but have remaining relistings.
         * Auction should be set to created with a new start date = current date + grace period(not long-set option)
         * Decrease the num_relists by one and notify the seller of the relisting.  Maybe send some tips.
         * Notify Seller
        */
         
         /* Finally, check for auctions that have no winner and no more relistings.
         * These should be closed or put in some sort of limbo 
         * and suggestions.
         * Email sent to the seller with options and/or tips
         */

        $effected_customers = array();
        
        $query = $this->db->query("SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS fullname, c.email as email_address FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad
        ON (a.auction_id = ad.auction_id)
        LEFT JOIN " . DB_PREFIX . "auction_options ao
        ON (a.auction_id = ao.auction_id)
        LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
        ON (a.auction_id = a2s.auction_id)
        LEFT JOIN " . DB_PREFIX . "customer c
        ON (a.customer_id = c.customer_id) 
        WHERE a.status = '2'
        AND ad.end_date <= NOW()
        AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
        
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS fullname, c.email as email_address FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad
        ON (a.auction_id = ad.auction_id)
        LEFT JOIN " . DB_PREFIX . "auction_options ao
        ON (a.auction_id = ao.auction_id)
        LEFT JOIN " . DB_PREFIX . "auction_to_store a2s
        ON (a.auction_id = a2s.auction_id)
        LEFT JOIN " . DB_PREFIX . "customer c
        ON (a.customer_id = c.customer_id) 
        WHERE a.status = '2'
        AND ad.end_date <= NOW()
        AND a2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
        
        //debuglog($sql);
        
        if ($query->num_rows) {
            $effected_auctions = $query->rows;
			$effected_customers['relist'] = $effected_auctions;
        } else {
            $effected_auctions = false;
			$effected_customers['relist'] = false;
        }
		
		$grace_period = '1';  // grace period before reopening auctions relisted.  This will be a setting later.
		
		$this->load->model('auction/bidding');
		if($effected_auctions) {
			foreach($effected_auctions as $auction) {
				$current_bid = $this->model_auction_bidding->getCurrentBid($auction['auction_id']);
				if($auction['reserve_price'] > $current_bid['bid_amount'] && $auction['num_relist'] > 0){
					$sql = "UPDATE " . DB_PREFIX . "auctions
					SET num_relist = (num_relist - 1),
					date_modified = NOW(),
					status = '1',
					modified_by = '1'
					WHERE auction_id = '" . $auction['auction_id'] . "'";
					//debuglog($sql);
					$this->db->query($sql);
					$new_start = (int)$auction['duration']*24 + $grace_period;
					$sql = "UPDATE " . DB_PREFIX . "auction_details
					SET start_date = DATE_ADD(NOW(), INTERVAL " . $grace_period . " HOUR),
					end_date = DATE_ADD(NOW(), INTERVAL " . $new_start . " HOUR),
					min_bid = " . max($auction['min_bid'], $current_bid['bid_amount']) . "
					WHERE auction_id = '" . $auction['auction_id'] . "'";
					
					$this->db->query($sql);
					
					$this->model_auction_bidding->moveBids2History($auction['auction_id']);
					debuglog("relisting: " . $auction['auction_id']);
					$message = html_entity_decode('Auction: ' . $auction['auction_id'] . ' Relisted.', ENT_QUOTES, 'UTF-8');
					$mail = new Mail();
					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender("Closing Day");
					$mail->setSubject(html_entity_decode("Auction Relisted", ENT_QUOTES, 'UTF-8'));
					$mail->setText($message, ENT_QUOTES, 'UTF-8');
					$mail->send();
				} elseif($auction['reserve_price'] <= $current_bid['bid_amount']) {
                    debuglog("We have a winner");
                    /* What has to happen now is: 
                    The auction has to close.
                    The winning bid has to be flagged as the winner.
                    The bids moved to history.
                    The seller has to be notified with the details.
                    The winning bidder has to be notified with the details.
                    */
                    $this->db->query("UPDATE " . DB_PREFIX . "auctions 
                    SET status = '3', 
                    winning_bid = '" . $current_bid['bid_amount'] . "' 
                    WHERE auction_id = '" . $auction['auction_id'] . "'");
                    $this->db->query("UPDATE " . DB_PREFIX . "current_bids SET winner = '1' WHERE bid_id = '" . $current_bid['bid_id'] . "'");
                    $this->model_auction_bidding->moveBids2History($auction['auction_id']);
                }
			}
		}
		
		return $effected_customers;
	}
		
	public function closeOpenAuctions() {
        //must check if latest bid exceeds the reserved bid if set and if not then if it is relisted
        // need a cool off period before relist maybe - setting - then reset the start time, end time,
        // decrease the relist count send email informing the seller that it didn't sell and give them the cool
        // off period to edit settings maybe.
        
        // maybe put all closing none winners with no relist option in a limbo state and send emails to the sellers
        // offering relisting before closing for good.
        
        // if latest bid exceeds the reserved bid then we have a winner!  Close the auction, send an email to
        // the seller informing them that the item has sold and with the buyers details.
        // Send the winning bidder and email informing them that they have won and that the seller will contact
        // them with details.
        
        // We take no responsibility for the payment and delivery of items.  We only give the seller the platform
        // to offer the item for sale.
        $this->load->model('auction/bidding');
        $closing_sql = "SELECT a.auction_id FROM " . DB_PREFIX . "auctions a 
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id) 
        WHERE a.status = '2' AND ad1.end_date <= NOW()";
        $closing_auctions = $this->db->query($closing_sql)->rows;
        foreach($closing_auctions as $closing_auction){
            $this->model_auction_bidding->moveBids2History($closing_auction['auction_id']);
            $this->db->query("UPDATE " . DB_PREFIX . "auctions 
            SET status = '3' 
            WHERE auction_id = '" . $closing_auction['auction_id'] . "'");
        }
        /*
        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '3' 
        WHERE a.status = '2' AND ad1.end_date <= NOW()");
        */
        //debuglog($closing_auctions);
        $results = count($closing_auctions);
        //debuglog($results);
		if($results){
			$effected_customers['closed'] = $results;
		} else {
			$effected_customers['closed'] = false;
        }
        
        //$this->load->model('auction/bidding');
        //$this->model_auction_bidding->moveBids2History($auction['auction_id']);

       /* $message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>Closing Auction</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($results . ' Auctions Closed.', ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";
        */
        $message = html_entity_decode($results . ' Auctions Closed.', ENT_QUOTES, 'UTF-8');
        if($results) {
            $mail = new Mail();
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender("Closing Day");
            $mail->setSubject(html_entity_decode("Closing Auctions", ENT_QUOTES, 'UTF-8'));
            $mail->setText($message, ENT_QUOTES, 'UTF-8');
            $mail->send();
        }
        
        return $effected_customers;
        //$this->log->write($results . 'Auctions Closed.');
    }
    
    public function openCreatedAuctions() {
        
        $query = $this->db->query("SELECT CONCAT(c.firstname,' ',c.lastname) AS fullname, c.email AS email_address
        FROM " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad
        ON (a.auction_id = ad.auction_id) 
        LEFT JOIN " . DB_PREFIX . "customer c
        ON (a.customer_id = c.customer_id)
        WHERE a.status = '1' AND ad.start_date < NOW()");

        if ($query->num_rows) {
            $customers_effected = $query->rows;
        } else {
            $customers_effected = false;
        }
        
        
        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '2' 
        WHERE a.status = '1' AND ad1.start_date < NOW()");
        
        $results = $this->db->countAffected();
        //$this->load->model("extension/module");
        //$auctionOpenMsg = $this->model_extension_module->getModulebyName('Auction Open','html');
        if ($results) {
            $mail = new Mail();
            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender("Opening Day");
            $mail->setSubject(html_entity_decode("Opening Auctions", ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($results . " Auctions Opened.", ENT_QUOTES, 'UTF-8'));
            $mail->send();
            //debuglog($auctionOpenMsg);
            /*foreach($customers_effected as $customer_effected){
                //debuglog($customer_effected);
                $mail = new Mail();
                $mail->setTo($customer_effected['email_address']);
                $mail->setFrom($this->config->get('config_email'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject(html_entity_decode($auctionOpenMsg['module_description'][1]['title'], ENT_QUOTES, 'UTF-8'));
                $mail->setHtml('<!DOCTYPE <html><body>' . $auctionOpenMsg['module_description'][1]['description'] . '</body></html>', ENT_QUOTES, 'UTF-8');
                //$mail->send();
                debuglog($mail);
            }*/
        }
        
        return $customers_effected;
        //$this->log->write($results . ' Auctions Opened.');
    }
    
    public function getBuyNowPrice($auction_id){
        $sql = "SELECT buy_now_price FROM " . DB_PREFIX . "auction_details 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $result = $this->db->query($sql);
        return $result->row;
    }

    public function closeWonAuction($auction_id){
        $sql = "UPDATE " . DB_PREFIX . "auctions 
        SET status = '3'  
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $this->db->query($sql);
        debuglog($sql);

    }

    public function declareWinners(){
        /* To declare winners follow these steps:
        1. Get list of closing auctions
            Already got list during the relist closing auctions section, maybe pass it in here instead
        2. Get current Bid for each of these auctions
        3. Check the closing bid compared to the reserve amount and if the closing bid
            is greater than or equal to the reserve bid then it has a winner.
        4. Close auction and set the winning bid to winner
        5. Move bids to history
        6. Send notifications out.
        */

    }
    // End of Model
}