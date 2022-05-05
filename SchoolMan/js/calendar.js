const json = {
    user:"blabla",
    type:"dateTasks",
    month: 3,
    year: 2022,
    tasks:[
        {
            title:'Essen essen',
            due:1651667400000, //Wednesday, 4. May 2022 12:30:00
            note:null,
            withTime:true
        },
        {
            title:'Geschichte Test',
            due:1651671900000, //Wednesday, 4. May 2022 13:45:00
            note:12,
            withTime:true
        },
        {
            title:'Raum 213 Veanstaltung',
            due:1651852800000, //Friday, 6. May 2022 16:00:00
            note:null,
            withTime:true
        },
        {
            title:'Abiturvorbereitung',
            due:1670341500000, //Tuesday, 6. December 2022 15:45:00
            note:132,
            withTime:false
        },
    ]
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
    if(btn == 'next-btn'){
        currentDate = new Date(currentDate.setMonth(currentDate.getMonth()+1));
        setURLBar(currentDate.getMonth(), currentDate.getFullYear());    
    }
    if(btn == 'prev-btn'){
        currentDate = new Date(currentDate.setMonth(currentDate.getMonth()-1));
        setURLBar(currentDate.getMonth(), currentDate.getFullYear());

        console.log(currentDate);
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
        let taskDate = new Date(value.due);
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
