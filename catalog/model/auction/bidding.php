<?php
class ModelAuctionBidding extends Model {
    
    
    
    public function placeBid($bid) {

        $leadingBid = $this->getCurrentBid($this->db->escape($bid['auction_id']));
        
        $bidNewAmount = $this->db->escape($bid['bid_amount']);
        $bidNewProxyAmount = $this->db->escape($bid['proxy_bid_amount']);

        if($leadingBid['bid_amount'] == 0) {
            // First Bid or Buy Now Bid
            $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
            SET
            auction_id = '" . $this->db->escape($bid['auction_id']) . "',
            bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            bid_date =  NOW(),
            bid_amount = '" . $bidNewProxyAmount . "',
            quantity = '1',
            proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
            proxy_bid_amount = '" . $bidNewProxyAmount . "'");
        } else {
            // Proxy bids are equal.  Place first bid then rebid with the same bid amount but from the current leader.
            if($bidNewProxyAmount == $leadingBid['proxy_bid_amount']) {
                // works debuglog("proxy bids are equal");
                // Place New Bid 
                $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                // Place Proxy Bid
                $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $bidNewProxyAmount . "'");
            }

            // New Proxy bid does not meet the current leading Proxy bid
            if($bidNewProxyAmount < $leadingBid['proxy_bid_amount']) {
                // works debuglog("does not meet proxy bid");
                // Place New Bid 
                $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                bid_date =  NOW(),
                bid_amount = '" . $bidNewProxyAmount . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $leadingBid['proxy_bid_amount'] . "'");
                // Place Proxy Bid
                $newBid = $this->model_auction_bidding->getNextBid($bidNewProxyAmount);
                $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                SET
                auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                bid_date =  NOW(),
                bid_amount = '" . min($newBid, $leadingBid['proxy_bid_amount']) . "',
                quantity = '1',
                proxy_bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                proxy_bid_amount = '" . $leadingBid['proxy_bid_amount'] . "'");
            }

            // New proxy bid is greater than the current leading proxy bid
            if($bidNewProxyAmount > $leadingBid['proxy_bid_amount']) {
                // works debuglog("new proxy is bigger than the proceeding one");
                if($bidNewAmount == $bidNewProxyAmount || $leadingBid['bid_amount'] == $leadingBid['proxy_bid_amount']) {
                    // Just a normal bid
                    //Place new bid
                    $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $bidNewAmount . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                } else {
                    //Place new bid
                    $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $bidNewAmount . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    // Place proxy bid
                    $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                    bidder_id = '" . $leadingBid['proxy_bidder_id'] . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $leadingBid['proxy_bid_amount'] . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                    // Place new proxy bid
                    $newBid = $this->model_auction_bidding->getNextBid($leadingBid['proxy_bid_amount']);
                    $sql = $this->db->query("INSERT INTO " . DB_PREFIX . "current_bids
                    SET
                    auction_id = '" . $this->db->escape($bid['auction_id']) . "',
                    bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    bid_date =  NOW(),
                    bid_amount = '" . $newBid . "',
                    quantity = '1',
                    proxy_bidder_id = '" . $this->db->escape($bid['bidder_id']) . "',
                    proxy_bid_amount = '" . $bidNewProxyAmount . "'");
                }
            }
        }


        
            
        $result = $this->db->getLastId();
        if(isset($bid['winner'])){
            $sql = "UPDATE " . DB_PREFIX . "current_bids SET winner = '1' WHERE bid_id = '" . $result . "'";
            $winner = $this->db->query($sql);
        }
        
        return $result;
    }
    
    public function placeProxyBid(){

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
            return array('bid_amount'=>'0.00');
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
        

        $this->db->query($sql);
        $sql = "DELETE FROM " . DB_PREFIX . "current_bids WHERE auction_id = '" . $auction_id . "'";
        $this->db->query($sql);
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
} // End of Model