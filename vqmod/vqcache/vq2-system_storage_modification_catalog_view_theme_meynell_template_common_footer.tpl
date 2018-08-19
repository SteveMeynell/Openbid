<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $feedback; ?>"><?php echo $text_feedback; ?></a></li>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $quote; ?>"><?php echo $text_quote; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>

						<?php if ( $weblog_status && $weblog_footer_link ) { ?>
							<li><a href="<?php echo $weblog; ?>"><?php echo $weblog_text_link_name; ?></a></li>
						<?php } ?>
					
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->


			
				<!-- mps -->
					<?php if($xmas_status) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/flurry/jquery.snow.js"></script>

<script type="text/javascript">
    jQuery(function() {
        jQuery("body").snow({
          intensity: 40,
          sizeRange: [12, 30],
          opacityRange: [0.4, 1],
          driftRange: [10, 20],
          speedRange: [55, 120]
        });
      });
    </script>
    <style type="text/css">
.snowflake {
  -webkit-animation: spin 4s linear infinite;
  -moz-animation: spin 4s linear infinite;
  animation: spin 4s linear infinite;
  color: #<?php echo $xmas_snow_flake_color; ?> !important;
  position: relative;
  z-index: 99;
}
 @-moz-keyframes 
spin { 100% {
-moz-transform: rotate(360deg);
}
}
 @-webkit-keyframes 
spin { 100% {
-webkit-transform: rotate(360deg);
}
}
 @keyframes 
spin { 100% {
-webkit-transform: rotate(360deg);
transform:rotate(360deg);
}
}
</style>
<?php } ?>
				<!-- mpe -->
			
			
</body></html>