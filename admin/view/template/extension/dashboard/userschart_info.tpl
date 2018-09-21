<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-calendar"></i> <i class="caret"></i></a>
      <ul id="userrange" class="dropdown-menu dropdown-menu-right">
        <li><a href="day"><?php echo $text_day; ?></a></li>
        <li><a href="week"><?php echo $text_week; ?></a></li>
        <li class="active"><a href="month"><?php echo $text_month; ?></a></li>
        <li><a href="year"><?php echo $text_year; ?></a></li>
      </ul>
    </div>
    <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="panel-body">
    <div id="chart-users" style="width: 100%; height: 260px;"></div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript"><!--
$('#userrange a').on('click', function(e) {
	e.preventDefault();
	
	$(this).parent().parent().find('li').removeClass('active');
	
	$(this).parent().addClass('active');
	
	$.ajax({
		type: 'get',
		url: 'index.php?route=extension/dashboard/userschart/chart&token=<?php echo $token; ?>&range=' + $(this).attr('href'),
		dataType: 'json',
		success: function(json) {
                        if (typeof json['both'] == 'undefined') { return false; }
			var option = {	
				shadowSize: 0,
				colors: ['#6600FF', '#6699FF', '#66FFFF'],
				bars: { 
					show: true,
					fill: true,
					lineWidth: 1
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false
				},
				xaxis: {
					show: true,
            		ticks: json['xaxis']
				}
			}
			
			$.plot('#chart-users', [json['both'], json['seller'], json['bidder']], option);	
					
			$('#chart-users').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#chart-users').css('cursor', 'pointer');		
			  	} else {
					$('#chart-users').css('cursor', 'auto');
				}
			});
		},
        error: function(xhr, ajaxOptions, thrownError) {
           alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});

$('#userrange .active a').trigger('click');
//--></script> 