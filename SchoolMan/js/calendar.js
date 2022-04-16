const monthName = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","October","November","Dezember"];
const weekdayName = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
let currentDate = new Date();

$( document ).ready(function() {  
    updateCalendar();
});

$(document).on("click",".cal-btn", function () {
    var clickedBtnID = $(this).attr('id');
    nextPrevDate(clickedBtnID);
 });

function updateCalendar(){
    setTableH(currentDate);
    setButtonNames(currentDate);  
    buildTable();
}

function buildTable(){
    let $cal = $('#calendart-body');
    let daysCount = daysInMonth(currentDate.getMonth(), currentDate.getFullYear());

    $cal.empty();

    for (let i = 0; i < daysCount; i++) {
        let d = new Date(currentDate.getFullYear(), currentDate.getMonth(), i+1);
        $cal.append(buildTableRow(d));
    }
}

function buildTableRow (date){
    let $row = $('<tr></tr>');
    let $date = $('<th></th>');
    let $day = $('<td></td>');
    let $tasks = $('<td></td>');

    let today = new Date();
    if(date.getDate() == today.getDate() && date.getMonth() == today.getMonth() && date.getFullYear() == today.getFullYear()){
        $row.addClass('table-primary');
    }
    else{
        if(date.getDay() == 0 || date.getDay() == 6){
            $row.addClass('bg-light');
            $row.addClass('text-muted');
        }
        else{
            $row.addClass('bg-light');
        }
    }

    $date.attr('scope', 'row').text(date.getDate() + '. ' + (date.getMonth()+1) + '.');
    $day.text(weekdayName[date.getDay()]);

    $row.append($date).append($day).append($tasks);
    return $row;
}

function nextPrevDate(btn){
    if(btn == 'next-btn'){
        currentDate = new Date(currentDate.setMonth(currentDate.getMonth()+1));    
    }
    if(btn == 'prev-btn'){
        currentDate = new Date(currentDate.setMonth(currentDate.getMonth()-1));

        console.log(currentDate);
    }

    updateCalendar();
}

function setTableH(date){
    $('#table-h').text(monthName[date.getMonth()] + ' - ' + date.getFullYear());
}

function setButtonNames(date){
    $('#prev-btn').text(monthName[new Date(date.getFullYear(), date.getMonth()-1).getMonth()]);
    $('#next-btn').text(monthName[new Date(date.getFullYear(), date.getMonth()+1).getMonth()]);
}

// helper functions
function daysInMonth (month, year) {
    return new Date(year, month+1, 0).getDate();
}
