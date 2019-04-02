<?php
class ModelAuctionBidding extends Model {
    
    public function updateBidCount($auction_id, $howmany = '1'){
        $this->db->query("UPDATE " . DB_PREFIX . "auctions SET num_bids = (num_bids + '" . (int)$howmany . "') WHERE auction_id = '" . (int)$auction_id . "'");
    }
    
    public function placeBid($bid) {

        $leadingBid = $this->getCurrentBid($this->db->escape($bid['auction_id']));
        
        $bidNewAmount = $this->db->escape($bid['bid_amount']);
        $bidNewProxyAmount = $this->db->escape($bid['proxy_bid_amount']);
        $auction_id = $this->db->escape($bid['auction_id']);
        $howmany = 0;
        $this->load->model('account/customer');
        $this->load->model('account/activity');
        $bidderInfo = $this->model_account_customer->getCustomer($this->db->escape($bid['bidder_id']));

        if($leadingBid['bid_amount'] == 0 || isset($bid['winner'])) {

            // First Bid or Buy Now Bid
            if(isset($bid['winner'])){
                $sql = "UPDATE " . DB_PREFIX . "auctions SET winning_bid = '" . $bidNewProxyAmount . "' 
                WHERE auction_id = '" . $auction_id . "'";
                $this->db->query($sql);
                
                // Add to activity log
                if ($this->config->get('config_customer_activity')) {

                    $activity_data = array(
                        'customer_id' => $this->db->escape($bid['bidder_id']),
                        'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                    );

                    $this->model_account_activity->addActivity('winning_bidder', $activity_data);
                }

            }
            $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
            SET
            auction_id = '" . $auction_id . "',
            bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            bid_date =  NOW(),
            bid_amount = '" . $bidNewProxyAmount . "',
            quantity = '1',
            proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            proxy_bid_amount = '" . $bidNewProxyAmount . "'");
            $result = $this->db->getLastId();
            $howmany +=1;
        } else {
            $proxyInfo = $this->model_account_customer->getCustomer($leadingBid['proxy_bidder_id']);
            // Proxy bids are equal.  Place first bid then rebid with the same bid amount but from the current leader.
            if($bidNewProxyAmount == $leadingBid['proxy_bid_amount']) {
                // works debuglog("proxy bids are equal");
                // Place New Bid 
                $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $auction_id . "',
                bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                $result = $this->db->getLastId();
                // Add to activity log
                if ($this->config->get('config_customer_activity')) {

                    $activity_data = array(
                        'customer_id' => $this->db->escape($bid['bidder_id']),
                        'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                    );

                    $this->model_account_activity->addActivity('placing_bidder', $activity_data);
                }
                // Place Proxy Bid
                $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $auction_id . "',
                bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                $result = $this->db->getLastId();
                $howmany +=2;
                // Add to activity log
                if ($this->config->get('config_customer_activity')) {

                    $activity_data = array(
                        'customer_id' => $leadingBid['proxy_bidder_id'],
                        'name'        => $proxyInfo['firstname'] . ' ' . $proxyInfo['lastname']
                    );

                    $this->model_account_activity->addActivity('proxy_bidder', $activity_data);
                }
            }

            // New Proxy bid does not meet the current leading Proxy bid
            if($bidNewProxyAmount < $leadingBid['proxy_bid_amount']) {
                // works debuglog("does not meet proxy bid");
                // Place New Bid 
                $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $auction_id . "',
                bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $leadingBid['proxy_bid_amount'] . "'");
                $result = $this->db->getLastId();
                // Add to activity log
                if ($this->config->get('config_customer_activity')) {

                    $activity_data = array(
                        'customer_id' => $this->db->escape($bid['bidder_id']),
                        'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                    );

                    $this->model_account_activity->addActivity('placing_bidder', $activity_data);
                }
                // Place Proxy Bid
                $newBid = $this->model_auction_bidding->getNextBid($bidNewProxyAmount);
                $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $auction_id . "',
                bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                bid_date =  NOW(),
                bid_amount = '" . min($newBid, $leadingBid['proxy_bid_amount']) . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $leadingBid['proxy_bid_amount'] . "'");
                $result = $this->db->getLastId();
                $howmany +=2;
                // Add to activity log
                if ($this->config->get('config_customer_activity')) {

                    $activity_data = array(
                        'customer_id' => $leadingBid['proxy_bidder_id'],
                        'name'        => $proxyInfo['firstname'] . ' ' . $proxyInfo['lastname']
                    );

                    $this->model_account_activity->addActivity('proxy_bidder', $activity_data);
                }
            }

            // New proxy bid is greater than the current leading proxy bid
            if($bidNewProxyAmount > $leadingBid['proxy_bid_amount']) {
                // works debuglog("new proxy is bigger than the proceeding one");
                if($bidNewAmount == $bidNewProxyAmount || $leadingBid['bid_amount'] == $leadingBid['proxy_bid_amount']) {
                    // Just a normal bid
                    //Place new bid
                    $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $auction_id . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $bidNewAmount . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    $result = $this->db->getLastId();
                    $howmany +=1;
                    // Add to activity log
                    if ($this->config->get('config_customer_activity')) {

                        $activity_data = array(
                            'customer_id' => $this->db->escape($bid['bidder_id']),
                            'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                        );

                        $this->model_account_activity->addActivity('placing_bidder', $activity_data);
                    }
                } else {
                    //Place new bid
                    $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $auction_id . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $bidNewAmount . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    $result = $this->db->getLastId();
                    // Add to activity log
                    if ($this->config->get('config_customer_activity')) {

                        $activity_data = array(
                            'customer_id' => $this->db->escape($bid['bidder_id']),
                            'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                        );

                        $this->model_account_activity->addActivity('placing_bidder', $activity_data);
                    }
                    // Place proxy bid
                    $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $auction_id . "',
                    bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $leadingBid['proxy_bid_amount'] . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    $result = $this->db->getLastId();
                    // Add to activity log
                    if ($this->config->get('config_customer_activity')) {

                        $activity_data = array(
                            'customer_id' => $leadingBid['proxy_bidder_id'],
                            'name'        => $proxyInfo['firstname'] . ' ' . $proxyInfo['lastname']
                        );

                        $this->model_account_activity->addActivity('proxy_bidder', $activity_data);
                    }
                    // Place new proxy bid
                    $newBid = $this->model_auction_bidding->getNextBid($leadingBid['proxy_bid_amount']);
                    $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $auction_id . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $newBid . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    $result = $this->db->getLastId();
                    $howmany+=3;
                    // Add to activity log
                    if ($this->config->get('config_customer_activity')) {

                        $activity_data = array(
                            'customer_id' => $this->db->escape($bid['bidder_id']),
                            'name'        => $bidderInfo['firstname'] . ' ' . $bidderInfo['lastname']
                        );

                        $this->model_account_activity->addActivity('placing_bidder', $activity_data);
                    }
                }
            }
        }


        
            
        
        if(isset($bid['winner'])){
            $sql = "UPDATE " . DB_PREFIX . "current_bids SET winner = '1' WHERE bid_id = '" . $result . "'";
            $winner = $this->db->query($sql);
        }
        $this->updateBidCount($auction_id, $howmany);
        $howmany = 0;
        
        return $result;
    }
    

    public function getCurrentBid($data) {
        $sql = "SELECT * FROM " . DB_PREFIX . "current_bids 
                         WHERE auction_id = '" . $data . "'
                         ORDER BY bid_id DESC";
        $query = $this->db->query($sql);
        $results = $query->row;
        
        if (isset($results['bid_amount'])) {
            return $results;
        } else {
            return array('bid_amount'=>'0');
        }
    }
    
    public function getNextBid($data) {
        $this->load->model('auction/bid_increments');
        
        $amount = $this->model_auction_bid_increments->getNextIncrement($data);

        return $data + $amount['increment'];
    }
    
    public function moveBids2History($auction_id) {
        $sql = "INSERT INTO " . DB_PREFIX . "bid_history
        (bid_id, auction_id, bidder_id, bid_date, bid_amount, quantity, proxy_bidder_id, proxy_bid_amount, winner)
        SELECT bid_id, auction_id, bidder_id, bid_date, bid_amount, quantity, proxy_bidder_id, proxy_bid_amount, winner FROM
        " . DB_PREFIX . "current_bids
        WHERE auction_id = '" . $auction_id . "'";
        //debuglog($sql);

        $this->db->query($sql);
        $sql = "DELETE FROM " . DB_PREFIX . "current_bids WHERE auction_id = '" . $auction_id . "'";
        $this->db->query($sql);
    }

    public function getNumBids($auction_id) {
        $sql = "SELECT count(auction_id) as num_bids FROM " . DB_PREFIX . "current_bids 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $bids = $this->db->query($sql);

        return $bids->row;
    }
    public function getAllBids($auction_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "current_bids 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "'";
        $bids = $this->db->query($sql);

        return $bids->rows;
    }

    public function getLastBid($auction_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "current_bids 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "' 
        ORDER BY bid_date DESC";
        
        $lastBid = $this->db->query($sql);
        
        return $lastBid->row;
    }

    public function getWinningBid($auction_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "bid_history 
        WHERE auction_id = '" . $this->db->escape($auction_id) . "' 
        AND winner = '1'";
        $winningBid = $this->db->query($sql);
        return $winningBid->row;
    }

    public function markBidWon($bid_id){
        $sql = $this->db->query("UPDATE " . DB_PREFIX . "current_bids 
        SET winner = '1' 
        WHERE bid_id = '" . $this->db->escape($bid_id) . "'");
    }

    public function shouldExtendAuction($bid_id) {
        $bidId = $this->db->escape($bid_id);
        $sql = "SELECT TIME_TO_SEC(TIMEDIFF(ad.end_date, cb.bid_date)) diff FROM " . DB_PREFIX . "current_bids cb 
        LEFT JOIN " . DB_PREFIX . "auction_details ad ON (cb.auction_id = ad.auction_id) 
        WHERE bid_id = '" . (int)$bidId . "'";
        debuglog($sql);
        if($this->db->query($sql)->row['diff'] <= ($this->config->get('config_auction_extension_for') * 60)) {
            return true;
        } else {
            return false;
        }
    }
} // End of Model