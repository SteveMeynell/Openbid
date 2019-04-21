<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<!-- <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"> -->
<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">-->

<link href="view/javascript/font-awesome/css/all.min.css" type="text/css" rel="stylesheet" />
<script defer src="view/javascript/font-awesome/js/all.js"></script>

<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/common.js" type="text/javascript"></script>
<script src="view/javascript/auction/auction.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fas fa-outdent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a>
    <label>Current Date and Time </label><label id="TimeDate"></label>
  </div>
  <?php if ($logged) { ?>
  <ul class="nav pull-right">
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if($alerts > 0) { ?><span class="label label-info pull-left"><?php echo $alerts; ?></span><?php } ?> <i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_auction; ?></li>
        <li><a href="<?php echo $opening_one_day; ?>" style="display: block; overflow: auto;"><span class="label label-info pull-right"><?php echo $opening_one_day; ?></span><?php echo $text_opening_soon; ?></a></li>
        <li><a href="<?php echo $created_auction_status; ?>" style="display: block; overflow: auto;"><span class="label label-success pull-right"><?php echo $created_auction_status_total; ?></span><?php echo $text_created_auction_status; ?></a></li>
        <li><a href="<?php echo $open_auction_status; ?>" style="display: block; overflow: auto;"><span class="label label-success pull-right"><?php echo $open_auction_status_total; ?></span><?php echo $text_open_auction_status; ?></a></li>
        <li><a href="<?php echo $closed_auction_status; ?>"><span class="label label-warning pull-right"><?php echo $closed_auction_status_total; ?></span><?php echo $text_closed_auction_status; ?></a></li>
        <li><a href="<?php echo $suspended_status; ?>"><span class="label label-danger pull-right"><?php echo $suspended_status_total; ?></span><?php echo $text_suspended_status; ?></a></li>
        <li><a href="<?php echo $moderation_status; ?>"><span class="label label-danger pull-right"><?php echo $moderation_status_total; ?></span><?php echo $text_moderation_status; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_customer; ?></li>
        <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
        <li><a href="<?php echo $customer_approval_link; ?>"><span class="label label-danger pull-right"><?php echo $customer_approval; ?></span><?php echo $text_approval; ?></a></li>
        <li><a href="<?php echo $bidders_only; ?>"><span class="label label-info pull-right"><?php echo $bidders_only_total; ?></span><?php echo $text_bidders_only; ?></a></li>
        <li><a href="<?php echo $sellers_only; ?>"><span class="label label-info pull-right"><?php echo $sellers_only_total; ?></span><?php echo $text_sellers_only; ?></a></li>
        <li><a href="<?php echo $bidders_sellers; ?>"><span class="label label-info pull-right"><?php echo $bidders_sellers_total; ?></span><?php echo $text_bidders_sellers; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_totals; ?></li>
        <li><a href="<?php echo $auction; ?>"><span class="label label-info pull-right"><?php echo $auction_total; ?></span><?php echo $text_auction; ?></a></li>
        <li><a href="<?php echo $customer_total_link; ?>"><span class="label label-info pull-right"><?php echo $customer_total; ?></span><?php echo $text_customer; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
        <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo $text_store; ?></li>
        <?php foreach ($stores as $store) { ?>
        <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
        <?php } ?>
      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-life-ring fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo $text_help; ?></li>
        <li><a href="http://www.openbid.com" target="_blank"><?php echo $text_homepage; ?></a></li>
        <li><a href="http://docs.openbid.com" target="_blank"><?php echo $text_documentation; ?></a></li>
        <li><a href="http://forum.openbid.com" target="_blank"><?php echo $text_support; ?></a></li>
      </ul>
    </li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fas fa-sign-out-alt fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>
