<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="buttonFacebook" data-toggle="tooltip" onclick="simulate();" title="<?php echo $button_facebook; ?>" class="btn btn-default"><i class="fa fa-upload"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-simulate" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $facebooktest; ?>" method="post" enctype="multipart/form-data" id="form-facebook" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <fieldset>
                  <legend><?php echo $entry_type; ?></legend>
                  <div class="form-radio">
                    <input class="form-radio-input" type="radio" name="simulator" id="test" value="user" checked />
                    <label class="form-radio-label" for="userCheck"><?php echo $entry_facebook_test; ?></label>
                  </div>
                  <div class="form-radio">
                    <input class="form-radio-input" type="radio" name="simulator" id="auction" value="auction"/>
                    <label class="form-radio-label" for="auctionCheck"><?php echo $entry_auctions; ?></label>
                  </div>
                  <div class="form-radio">
                    <input class="form-radio-input" type="radio" name="simulator" id="bid" value="bid"/>
                    <label class="form-radio-label" for="bidsCheck"><?php echo $entry_bids; ?></label>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function simulate(){
  if(document.getElementById("test").checked){
    console.log("got it");
    this.getFacebook();
    
  }
  if(document.getElementById("auction").checked){
    console.log("auction");
    this.getAuction();
  }
  if(document.getElementById("bid").checked){
    console.log("bid");
  }
}

function getFacebook(){
  $.ajax({
    url: 'index.php?route=tool/facebook_test&token=' . $token,
    data: {type:'create'},
    type: "POST",
    dataType: "json",
    success: function(json){
      if(json['fb']) {
        console.log(json['fb']);
      }
    },
    error: function(request, error){
      console.log("error");
      console.log(error);
    }
  });
}
  
function successHandler(da){
  $.ajax({
        url: 'index.php?route=tool/simulate_data&token=' . $token,
        data: {newUser:da},
        type: "POST",
        datatype: "json",
        success: function(json){
          if (json['success']) {
            alert(json['success']);
          }
        },
        error: function(request,error){
          console.log("error");
          console.log(error);
        }
      });
}

function getAuction(){
  var auction = '1';
  $.ajax({
        url: 'index.php?route=tool/simulate_data&token=' . $token,
        type: "POST",
        datatype: "json",
        data: {newAuction:auction},
        success: function(json){
          if (json['success']) {
            console.log(json['success']);
          }
        },
        error: function(request,error){
          console.log("error");
          console.log(error);
        }
      });
}
</script>

<?php echo $footer; ?>
