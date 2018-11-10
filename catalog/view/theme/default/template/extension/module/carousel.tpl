<div id="carousel<?php echo $module; ?>" class="owl-carousel">
  <?php foreach ($auctions as $auction) { ?>
	<div class="product-layout col-lg-12 col-md-3 col-sm-6 col-xs-12">
		<div class="item text-center">
			<div class="caption"><h2><?php echo $heading_text; ?></h2></div>
			<div class="caption"><h3><a href="<?php echo $auction['link']; ?>"><?php echo $auction['title']; ?></a></h3></div>
			<div class="product-thumb transition">
				<div class="image"><a href="<?php echo $auction['link']; ?>"><img src="<?php echo $auction['image']; ?>" alt="<?php echo $auction['title']; ?>" title="<?php echo $auction['title']; ?>" class="img-responsive" /></a></div>
				<div class="caption">
					<h4><a href="<?php echo $auction['link']; ?>"><?php echo $auction['subtitle']; ?></a></h4>
					<p><?php echo $auction['description']; ?></p>
					<h2><a href="<?php echo $auction['link']; ?>"><?php echo $footer_text; ?></h2></a>
				</div>
			</div>
		</div>
	</div>
  <?php } ?>
</div>
<script type="text/javascript"><!--
$('#carousel<?php echo $module; ?>').owlCarousel({
	animateIn: '',
	animateOut: '<?php echo $transition; ?>',
	items: 1,
	loop: true,
	center: true,
	autoplay: true,
	autoplayTimeout: 5000,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: true
});
--></script>