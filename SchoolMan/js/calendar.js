//check token availability and redirect
if(!validateCurrentToken()){
    window.location.href = "./index.php";
    Cookies.remove('__scman_us_t');
}

let token = Cookies.get('__scman_us_t');

$(document).on('click','#submit-btn', function () {  
    login();
});

const timezoneDiffSec = new Date().getTimezoneOffset() * 60;
let json = 
        {   
            "type": "calendarTasks",
            "from": 0,
            "to": 0,
            "tasks": []
        };

function updateCalendarJSON(from, to){
    let json;
    $.ajax({
        url: "./API/calendar.php",
        type: 'GET',
        async: false,
        headers: {  "usr-token":token,
                    "calendar-from-stamp":from,
                    "calendar-to-stamp":to},
        dataType: "json",
        success: function(text) {
            json = text;
        },
        error: function(xhr, status, error){
            if(xhr.status === 403){
                errMsg.text('Ein Nutzer mit diesem Namen oder dieser Email existiert bereits.');
                errMsg.show();
            }
            else{
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.error('Error - ' + errorMessage);
                console.log(error);
            }
        }
    });
    
    return json;
}

const monthName = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","October","November","Dezember"];
const weekdayName = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
let currentDate = new Date();

$( document ).ready(function() {
    let url = new URL(window.location.href);
    if (!(url.searchParams.has('m') && url.searchParams.has('y'))) {
        setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        updateCalendar();    
    }
    else{
        currentDate.setMonth(url.searchParams.get('m'));
        currentDate.setFullYear(url.searchParams.get('y'));
        updateCalendar(); 
    }
});

$(document).on("click",".cal-btn", function () {
    var clickedBtnID = $(this).attr('id');
    setNewDateByButton(clickedBtnID);
});

function updateCalendar(){
    var firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    var lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 23, 59, 59, 999);

    var from = Math.floor(firstDay / 1000) - timezoneDiffSec;
    var to = Math.floor(lastDay / 1000) - timezoneDiffSec;

    console.log(from);
    console.log(to);
    console.log(timezoneDiffSec);

    json = updateCalendarJSON(from, to);
    console.log(json);

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

    $tasks.addClass('fw-bold');
    $tasks.html(getDaysTasksString(date));

    $row.append($date).append($day).append($tasks);
    return $row;
}

function setNewDateByButton(btn){
    switch(btn) {
        case 'prev-btn':
            currentDate.setFullYear(currentDate.getFullYear()-1);
            currentDate.setMonth(11);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());  
        break;
        case 'next-btn':
            currentDate.setFullYear(currentDate.getFullYear()+1);
            currentDate.setMonth(0);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break;
        case 'jan-btn':
            currentDate.setMonth(0);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break;         
        case 'feb-btn':
            currentDate.setMonth(1);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'merz-btn':
            currentDate.setMonth(2);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'apr-btn':
            currentDate.setMonth(3);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'mai-btn':
            currentDate.setMonth(4);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'jun-btn':
            currentDate.setMonth(5);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'jul-btn':
            currentDate.setMonth(6);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'aug-btn':
            currentDate.setMonth(7);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'sep-btn':
            currentDate.setMonth(8);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'okt-btn':
           currentDate.setMonth(9);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'nov-btn':
            currentDate.setMonth(10);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break; 
        case 'dec-btn':
            currentDate.setMonth(11);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        break;
        default:
            currentDate = new Date();    
    }

    updateCalendar();
}

function setTableH(date){
    $('#table-h').text(monthName[date.getMonth()] + ' - ' + date.getFullYear());
}

function setButtonNames(date){
    $('#prev-btn').text(date.getFullYear()-1);
    $('#next-btn').text(date.getFullYear()+1);
}

function setURLBar(m, y){
    let url = new URL(window.location.href);
    url.searchParams.delete('m');
    url.searchParams.delete('y');
    url.searchParams.set('m', m);
    url.searchParams.set('y', y);
    window.history.pushState('', '',url.toString());

    return url;
}

function getDaysTasksString(date){
    let HTMLstring = '';
    let lBr = false;
    $.each(json.tasks, function( index, value ) {
        let taskDate = new Date((value.dueBy + timezoneDiffSec)*1000);
        if(date.getDate() == taskDate.getDate() && date.getMonth() == taskDate.getMonth() && date.getFullYear() == taskDate.getFullYear()){
            let taskHTMLStr = value.title;

            if(value.note != null){
                taskHTMLStr = taskHTMLStr + ' (<a href="#">Notiz<a>)';
            }

            if(!lBr){ 
                HTMLstring = HTMLstring + taskHTMLStr;
                lBr = true;
            }
            else{
                HTMLstring = HTMLstring + '<br>' + taskHTMLStr;
            }
        }
    });

    return HTMLstring;
}

// helper functions
function daysInMonth (month, year) {
    return new Date(year, month+1, 0).getDate();
}
