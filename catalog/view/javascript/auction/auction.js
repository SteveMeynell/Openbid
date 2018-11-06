function displayTimeRemaining() {
    var myTime = new Date(),
    da = checkTime(myTime.toDateString()),
    ti = checkTime(myTime.toDateString());
    var testtime = document.getElementsByClassName("starting_in_time");
    var Times=document.getElementsByClassName('startingTime');
    var l = testtime.length;
    for(nodecounter=0;nodecounter<l;nodecounter++){
        var myDateTime = checkTime(testtime[nodecounter].attributes.hidden.value);
        var daysDifference = DateDiff("d",myTime,myDateTime,1);
        var secondsDifference = DateDiff("s",myTime,myDateTime,1);
        
        var timeDifference = DateDiff("n",myTime,myDateTime,1);
        var hoursDifference = DateDiff("h",myTime,myDateTime,1);
        var timeRemainder = timeDifference - daysDifference * 24;
        var minutesRemainder = timeRemainder - hoursDifference*60;
        if(secondsDifference<=0){
            document.location.reload();
        }
        if (daysDifference) {
            Times[nodecounter].textContent = daysDifference + " Days and " + hoursDifference + " Hours " + minutesRemainder + " Minutes!";
        } else if (secondsDifference<=120){
            Times[nodecounter].textContent = secondsDifference + " Seconds!";
        } else {
            Times[nodecounter].textContent = hoursDifference + " Hours " + minutesRemainder + " Minutes!";
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

