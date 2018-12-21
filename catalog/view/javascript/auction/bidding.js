function BuyNow(){
  $('#BuyNowButton').on('click',function(e){
    var buttonStuff = document.getElementById("BuyNowButton");

    var description = document.getElementById("tab-description");
    description.textContent = 'Buy It Now';
  });
}

function PlaceBid(){
  $('proxyBidForm').submit(function(e) {
    e.preventDefault();
    var Amount = $("input:text");
    $("#tab-description").text(Amount.val());
    });
  }