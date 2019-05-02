<?php
class ModelAccountUser extends Model {

  public function getFirstName($user_id) {
    $userId = $this->db->escape($user_id);
    $query = "SELECT firstname FROM " . DB_PREFIX . "customer WHERE customer_id='" . (int)$userId . "'";
    return $this->db->query($query)->row['firstname'];
  }

  public function getMemberSince($user_id) {
    $userId = $this->db->escape($user_id);
    $query = "SELECT DATE_FORMAT(date_added, '%b %d, %Y') as membership from " . DB_PREFIX . "customer WHERE customer_id='" . (int)$userId . "'";
    return $this->db->query($query)->row['membership'];
  }

  public function getAuctions($user_id) {
    $userId = $this->db->escape($user_id);
    $auctions = array();

    $this->load->model('catalog/auction');
    // As a Seller
    // Auction Infos
    $createdAuctions = $this->db->query("SELECT auction_id from " . DB_PREFIX . "auctions where customer_id='" . (int)$userId . "' AND status='1'")->rows;
    foreach($createdAuctions as $auction) {
      $auctions['seller']['auctions']['created'][] = $this->model_catalog_auction->getAuction($auction['auction_id']);
    }
    $openAuctions = $this->db->query("SELECT auction_id from " . DB_PREFIX . "auctions 
    where customer_id='" . (int)$userId . "' AND status='2'")->rows;
    foreach($openAuctions as $auction) {
      $auctions['seller']['auctions']['open'][] = $this->model_catalog_auction->getAuction($auction['auction_id']);
    }
    $featuredAuctions = $this->db->query("SELECT a.auction_id from " . DB_PREFIX . "auctions a 
    LEFT JOIN " . DB_PREFIX . "auction_options ao ON (ao.auction_id = a.auction_id) 
    WHERE a.customer_id='" . (int)$userId . "' AND a.status='2' AND ao.featured='1'")->rows;
    
    foreach($featuredAuctions as $auction) {
      $auctions['seller']['auctions']['featured'][] = $this->model_catalog_auction->getAuction($auction['auction_id']);
    }
    $closedAuctions = $this->db->query("SELECT auction_id from " . DB_PREFIX . "auctions where customer_id='" . (int)$userId . "' AND status='3'")->rows;

    foreach($closedAuctions as $auction) {
      $auctions['seller']['auctions']['closed'][] = $this->model_catalog_auction->getAuction($auction['auction_id']);
    }
    // Stats    
    $maxWinningBidQuery = "SELECT MAX(winning_bid) as total from " . DB_PREFIX . "auctions where customer_id='" . (int)$userId . "'";
    $mostViewedQuery = "SELECT DISTINCT auction_id from " . DB_PREFIX . "auctions where customer_id='" . (int)$userId . "' ORDER BY viewed DESC";
    $currentBidQuery = "SELECT MAX(cb.bid_amount) as highest FROM " . DB_PREFIX . "auctions a 
    LEFT JOIN " . DB_PREFIX . "current_bids cb ON(a.auction_id = cb.auction_id) 
    WHERE a.customer_id='" . (int)$userId . "'";
    $bidHistoryQuery = "SELECT MAX(bh.bid_amount) as highest FROM " . DB_PREFIX . "auctions a 
    LEFT JOIN " . DB_PREFIX . "bid_history bh ON(a.auction_id = bh.auction_id) 
    WHERE a.customer_id='" . (int)$userId . "'";
    $currentHighestBid = $this->db->query($currentBidQuery)->row['highest'];
    $bidHistoryHighest = $this->db->query($bidHistoryQuery)->row['highest'];
    $mostBidsQuery = "SELECT max(num_bids) as total from " . DB_PREFIX . "auctions 
    WHERE customer_id='" . (int)$userId . "'";
    
    // Ranking and stats
    $totalAuctionRank = $this->db->query("SELECT customer_id, COUNT(*) as num_auctions from " . DB_PREFIX . "auctions GROUP BY customer_id ORDER BY num_auctions DESC")->rows;
    $successfulAuctionsRank = $this->db->query("SELECT customer_id, COUNT(*) as successful_total from " . DB_PREFIX . "auctions WHERE  winning_bid>0 GROUP BY customer_id ORDER BY successful_total DESC")->rows;
    $totalViewsRank = $this->db->query("SELECT customer_id, SUM(viewed) as views from " . DB_PREFIX . "auctions GROUP BY customer_id ORDER BY views DESC")->rows;    //debuglog($totalAuctionRank);

    // Reviews
    //$sellerReviewQuery = "SELECT * FROM " . DB_PREFIX . "review WHERE customer_id='" . (int)$userId . "' AND review_group='2'";
    $sellerStarQuery = "SELECT AVG(bidder_question1) as communication, AVG(bidder_question2) as shipping, AVG(bidder_question3) as quality FROM " . DB_PREFIX . "bidder_reviews WHERE seller_id='" . (int)$userId . "'";
    

    //$auctions['seller']['created'] = $this->db->query($createdQuery)->rows;
    //$auctions['seller']['open'] = $this->db->query($openQuery)->rows;
    //$auctions['seller']['closed'] = $this->db->query($closedQuery)->rows;
    //$auctions['seller']['featured'] = $this->db->query($featuredQuery)->rows;

    foreach($totalAuctionRank as $rank => $stat) {
      if($stat['customer_id'] == $userId) {
        $auctions['seller']['stats']['total_auctions'] = $stat['num_auctions'];
        $auctions['seller']['stats']['total_auctions_rank'] = $rank + 1;
      }
    }
    foreach($successfulAuctionsRank as $rank => $stat) {
      if ($stat['customer_id'] == $userId) {
        $auctions['seller']['stats']['successful_auctions'] = $stat['successful_total'];
        $auctions['seller']['stats']['successful_auctions_rank'] = $rank + 1;
      }
    }
    foreach($totalViewsRank as $rank => $stat) {
      if ($stat['customer_id'] == $userId) {
        $auctions['seller']['stats']['total_views'] = $stat['views'];
        $auctions['seller']['stats']['total_views_rank'] = $rank + 1;
      }
    }
    
    $auctions['seller']['stats']['highest_winning_bid'] = $this->currency->format($this->db->query($maxWinningBidQuery)->row['total'], $this->session->data['currency']);
    $auctions['seller']['stats']['most_viewed'] = $this->db->query($mostViewedQuery)->row['auction_id'];
    $auctions['seller']['stats']['highest_bid_received'] = ($currentHighestBid>$bidHistoryHighest)?$this->currency->format($currentHighestBid, $this->session->data['currency']):$this->currency->format($bidHistoryHighest, $this->session->data['currency']);
    $auctions['seller']['stats']['most_bids'] = $this->db->query($mostBidsQuery)->row['total'];
    //$auctions['seller']['reviews'] = $this->db->query($sellerReviewQuery)->rows;
    $auctions['seller']['star_rating'] = $this->db->query($sellerStarQuery)->row;

    //debuglog($auctions['seller']['reviews']);

    // As a Bidder
    // What stats needed as a bidder?
    // number of bids places.
    // number of winning bids placed.
    // highest bid placed.
    // number of different auctions bid on.
    $bidderStarQuery = "SELECT AVG(seller_question1) as communication, AVG(seller_question2) as shipping, AVG(seller_question3) as quality FROM " . DB_PREFIX . "seller_reviews WHERE bidder_id='" . (int)$userId . "'";
    $bidderNumBidsQuery1 = $this->db->query("SELECT COUNT(*) as bidCount 
    FROM " . DB_PREFIX . "current_bids 
    WHERE bidder_id='" . $userId . "'")->row['bidCount'];
    $bidderNumBidsQuery2 = $this->db->query("SELECT COUNT(*) as bidCount 
    FROM " . DB_PREFIX . "bid_history 
    WHERE bidder_id='" . $userId . "'")->row['bidCount'];
    $auctions['bidder']['stats']['numBids'] = $bidderNumBidsQuery1 + $bidderNumBidsQuery2;
    
    $auctions['bidder']['stats']['numWinningBids'] = $this->db->query("SELECT COUNT(*) as bidCount 
    FROM " . DB_PREFIX . "bid_history 
    WHERE bidder_id='" . $userId . "' AND winner='1'")->row['bidCount'];

    $bidderMaxBidQuery1 = $this->db->query("SELECT MAX(bid_amount) as bidAmount 
    FROM " . DB_PREFIX . "current_bids 
    WHERE bidder_id='" . $userId . "'")->row['bidAmount'];
    $bidderMaxBidQuery2 = $this->db->query("SELECT MAX(bid_amount) as bidAmount 
    FROM " . DB_PREFIX . "bid_history 
    WHERE bidder_id='" . $userId . "'")->row['bidAmount'];
    $auctions['bidder']['stats']['maxBid'] = ($bidderMaxBidQuery1>=$bidderMaxBidQuery2)?$this->currency->format($bidderMaxBidQuery1, $this->session->data['currency']):$this->currency->format($bidderMaxBidQuery2, $this->session->data['currency']);
    $auctions['bidder']['star_rating'] = $this->db->query($bidderStarQuery)->row;

    $bidderNumAuctions1 = $this->db->query("SELECT DISTINCT auction_id FROM " . DB_PREFIX . "current_bids WHERE bidder_id='" . $userId . "'")->rows;
    $bidderNumAuctions2 = $this->db->query("SELECT DISTINCT auction_id FROM " . DB_PREFIX . "bid_history WHERE bidder_id='" . $userId . "'")->rows;

    $auctions['bidder']['stats']['numAuctions'] = count($bidderNumAuctions1) + count($bidderNumAuctions2);

    return $auctions;
  }

  // End of model
}