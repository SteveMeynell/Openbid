function displayTimeRemaining() {
    var myTime = new Date(),
    da = checkTime(myTime.toDateString()),
    ti = checkTime(myTime.toDateString());
    var testtime = document.getElementsByClassName("starting_in_time");
    var Times=document.getElementsByClassName('startingTime');
    var l = testtime.length;
    for(nodecounter=0;nodecounter<l;nodecounter++){
        var myDateTime = checkTime(testtime[nodecounter].attributes.hidden.value);
        var daysRemaining = DateDiff("d",myTime,myDateTime,1);
        var hoursRemaining = DateDiff("h",myTime,myDateTime,1) - (daysRemaining*24);
        var minutesRemaining = DateDiff("n",myTime,myDateTime,1) - ((daysRemaining*1440) + (hoursRemaining*60));
        var secondsRemaining = DateDiff("s",myTime,myDateTime,1) - ((daysRemaining*86400) + (hoursRemaining*3600) + (minutesRemaining*60));
        
        if (secondsRemaining < 0){
            document.location.reload();
        }
        
        if (daysRemaining) {
            Times[nodecounter].textContent = daysRemaining + " Days and " + hoursRemaining + " Hours " + minutesRemaining + " Minutes!";
        } else if (hoursRemaining){
            Times[nodecounter].textContent = hoursRemaining + " Hours " + minutesRemaining + " Minutes!";
        } else if (minutesRemaining){
            Times[nodecounter].textContent = minutesRemaining + " Minutes " + secondsRemaining + " Seconds!";
        } else if (secondsRemaining){
            Times[nodecounter].textContent = secondsRemaining + " Seconds!";
        } 
        
    }
    t = setTimeout(function() {
        displayTimeRemaining()
    }, 500);
    
}
function checkTime(i) {
        return (i < 10) ? "0" + i : i;
    }

$(document).ready(function () {
    
    function startTime() {
        var today = new Date(),
            D = checkTime(today.toDateString()),
            T = checkTime(today.toTimeString());
        document.getElementById('TimeDate').innerHTML = ":  " + D + " - " + T;
        t = setTimeout(function () {
            startTime()
        }, 500);
    }
    startTime();
    displayTimeRemaining();
});

