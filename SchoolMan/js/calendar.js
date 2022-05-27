////check token availability and redirect
if(!validateCurrentToken(userToken)){
    window.location.href = "./index.html";
    Cookies.remove('__scman_us_t');
}

const monthName = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","October","November","Dezember"];
const weekdayName = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
var currentDate = new Date();

////start
$(document).ready(function() {
    let url = new URL(window.location.href);
    if (!(url.searchParams.has('m') && url.searchParams.has('y'))) {
        setURLBar(currentDate.getMonth(), currentDate.getFullYear());
        update(currentDate);
    }
    else{
        currentDate.setMonth(url.searchParams.get('m'));
        currentDate.setFullYear(url.searchParams.get('y'));
        update(currentDate);
    }
});

////load calender
function update(date){
    //get first and last timestamp of month
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 23, 59, 59, 999);
    var from = Math.floor(firstDay / 1000);
    var to = Math.floor(lastDay / 1000);

    //set button names
    setButtonNames(date);

    //set calendar header
    $('#table-h').text(monthName[date.getMonth()] + ' - ' + date.getFullYear());

    //AJAX request tasks for current displayed month
    $.ajax({
        url: "./API/calendar.php",
        type: 'GET',
        headers: {"usr-token":userToken,
                  "calendar-from-stamp":from,
                  "calendar-to-stamp":to
                },
        dataType: "json",
        success: function(response) {
            updateCalendarHTML(response);
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    });   
}

function updateCalendarHTML(response){
    let $calendar = $('#calendart-body');
    let daysCount = daysInMonth(currentDate.getMonth(), currentDate.getFullYear());

    $calendar.empty();

    for (let i = 0; i < daysCount; i++) {
        let dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i+1);

        //create elements
        let $row = $('<tr></tr>');
        let $date = $('<th></th>');
        let $day = $('<td></td>');
        let $tasks = $('<td></td>');

        $date.attr('scope', 'row').text(dayDate.getDate() + '. ' + (dayDate.getMonth()+1) + '.');

        $day.text(weekdayName[dayDate.getDay()]);

         //colour today blue and saturday and sunday gray 
         let today = new Date();
         if(dayDate.getDate() == today.getDate() && dayDate.getMonth() == today.getMonth() && dayDate.getFullYear() == today.getFullYear()){
             $row.addClass('table-primary');
         }
         else{
             if(dayDate.getDay() == 0 || dayDate.getDay() == 6){
                 $row.addClass('bg-light');
                 $row.addClass('text-muted');
             }
             else{
                 $row.addClass('bg-light');
             }
         }

        //loop response and add tasks
        let lBr = false;
        $.each(response.tasks, function( index, value ) {
            let taskDate = new Date((value.dueBy)*1000);
            if(dayDate.getDate() == taskDate.getDate() && dayDate.getMonth() == taskDate.getMonth() && dayDate.getFullYear() == taskDate.getFullYear()){
                let $taskSpan = $('<span></span>');
                let $taskNoteLink = $('<a type="button" class="task-note-link text-left"></a>');
                let $taskDelLink = $('<a type="button" class="task-del-link text-danger fw-bold" style="margin-left:1em; text-decoration: none;">-</a>');
                    
                $taskNoteLink.attr('noteIDs', value.notes);
                $taskNoteLink.text(value.title);

                $taskDelLink.attr('taskID', value.taskID);

                $taskSpan.append($taskNoteLink).append($taskDelLink);

                if(!lBr){ 
                    $tasks.append($taskSpan);
                    lBr = true;
                }
                else{
                    $tasks.append($('<br>'));
                    $tasks.append($taskSpan);
                }

                //add task show notes event
                $taskNoteLink.click(function () {
                    //TODO: note show handling
                    var nodeIDs = $(this).attr('noteIDs');
                    console.log(nodeIDs);
                });
            
                //add delete task button event
                $taskDelLink.click(function () {
                    //TODO: Deleate handling
                    var taskID = $(this).attr('taskID');
                    console.log(taskID);
                });
            }
        });    

        //append everything to row and to calendar
        $row.append($date).append($day).append($tasks);
        $calendar.append($row);
    }
}

////TODO: add new task handling
$(document).on("click",".add-task", function () {
    console.log('new task');
});

////calendar interface funtions
//calendar month/year button event
$(document).on("click",".cal-btn", function () {
    var clickedBtnID = $(this).attr('id');
    setNewDateByButton(clickedBtnID);
    update(currentDate);
});

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
}

function setButtonNames(date){
    $('.prev-btn').text(date.getFullYear()-1);
    $('.next-btn').text(date.getFullYear()+1);
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

//other helper funtions
function daysInMonth (month, year) {
    return new Date(year, month+1, 0).getDate();
}