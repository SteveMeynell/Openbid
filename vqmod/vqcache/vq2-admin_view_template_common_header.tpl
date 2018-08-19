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
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

				
				<!--  Weblog -->
				
				<?php if (!empty($weblog_article)) { // First check if one of the following variables is set ?>

					<style>
					/*Fix the arrow > V at the right of the Weblog logo */
					#menu li#weblog li a.parent::after, #column-left.active #menu > li#weblog a.parent::after {
						position:relative;
						top: 5px;
					}
					
					#menu li#weblog > a,
					#menu li#weblog li a::before {color:#43D2F9;}
					#menu li li a.wl-article::before {content: "";}
					#menu li li a.wl-category::before {content: "";}
					#menu li li a.wl-author::before {content: "";}
					#menu li li a.wl-comment::before {content: "";}
					#menu li li a.wl-report::before {content: "";}
					#menu li li a.wl-setting::before {content: "";}
					#menu li li a.wl-backup::before {content: "";}
					
					#menu li#weblog li.active a:last-child::before {margin-left:10px;}
					#menu li#weblog li.active > a:last-child {color:#43D2F9;}
					</style>
				
					<script>
					$(function() {
					
						var $menu = $('#column-left ul#menu');
						
						var comments = '<?php if ($pending_reply_total) { ?><em style="margin-left:15px;color:lime;">+<b style="font-weight:normal; "><?php echo $pending_reply_total ?> <i class="fa fa-comments" aria-hidden="true"></i></b></em><?php } ?>'; 
						
						var html = '';
						
						html += '<li id="weblog"><a class="parent"><i class="fa fa-file-text-o fa-fw" aria-hidden="true"></i> <span><img style="width:80px" src="view/image/weblog-logo.gif" /><?php //echo $text_weblog; ?></span>' + comments + '</a>';
						html += 	'<ul>';
						html += 		'<li><a class="wl-article" href="<?php echo $weblog_article; ?>"><?php echo $text_weblog_article; ?></a></li>';
						html += 		'<li><a class="wl-category" href="<?php echo $weblog_category; ?>"><?php echo $text_weblog_category; ?></a></li>';
						html += 		'<li><a class="wl-author" href="<?php echo $weblog_author; ?>"><?php echo $text_weblog_author; ?></a></li>';
						html += 		'<li><a class="wl-comment" href="<?php echo $weblog_comment; ?>"><?php echo $text_weblog_article_comment; ?>' + comments + '</a></li>';
						html += 		'<li><a class="wl-report" href="<?php echo $weblog_view_report; ?>"><?php echo $text_weblog_view_report; ?></a></li>';
						html += 		'<li><a class="wl-setting parent" ><?php echo $text_weblog_setting; ?></a>';
						html += 			'<ul>';
						html += 				'<li><a href="<?php echo $weblog_general_setting; ?>"><?php echo $text_weblog_general_setting; ?></a></li>';
						html += 				'<li><a href="<?php echo $weblog_add_modules; ?>"><?php echo $text_weblog_add_modules; ?></a></li>';
						html +=				'</ul>';
						html += 		'</li>';
						html += 		'<li><a class="wl-backup" href="<?php echo $weblog_backup; ?>"><?php echo $text_weblog_backup; ?></a></li>';					
						html += 	'</ul>';
						html += '</li>';
						
						$menu.append(html);
				
					});
				
					</script>
				
				<?php } ?>
				
				<!-- END Weblog -->
				
				
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a></div>
  <?php if ($logged) { ?>
  <ul class="nav pull-right">

				
				<!-- Weblog	-->
				
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><img style="width:80px" src="view/image/weblog-logo.gif" /></a>
					<ul class="dropdown-menu dropdown-menu-right">					
						<?php foreach ($store_blogs as $blog) { ?>
						<li><a href="<?php echo $blog['href']; ?>" target="_blank"><?php echo $blog['name']; ?></a></li>
						<?php } ?>
						
					</ul>
				</li>

				<!-- END Weblog -->
				
				
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if($alerts > 0) { ?><span class="label label-danger pull-left"><?php echo $alerts; ?></span><?php } ?> <i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header"><?php echo $text_order; ?></li>
        <li><a href="<?php echo $processing_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $processing_status_total; ?></span><?php echo $text_processing_status; ?></a></li>
        <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
        <li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_customer; ?></li>
        <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
        <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_product; ?></li>
        <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
        <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
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
        <li><a href="http://www.opencart.com" target="_blank"><?php echo $text_homepage; ?></a></li>
        <li><a href="http://docs.opencart.com" target="_blank"><?php echo $text_documentation; ?></a></li>
        <li><a href="http://forum.opencart.com" target="_blank"><?php echo $text_support; ?></a></li>
      </ul>
    </li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>
