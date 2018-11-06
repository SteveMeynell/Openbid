<?php
class ModelAuctionAuction extends Model {
    
    public function closeOpenAuctions() {
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
            $effected_customers = $query->rows;
        } else {
            $effected_customers = false;
        }
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
        
        $this->db->query("UPDATE " . DB_PREFIX . "auctions a
        LEFT JOIN " . DB_PREFIX . "auction_details ad1
        ON (a.auction_id = ad1.auction_id)
        SET a.status = '3' 
        WHERE a.status = '2' AND ad1.end_date <= NOW()");
        
        $results = $this->db->countAffected();
        
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
    
    
    // End of Model
}