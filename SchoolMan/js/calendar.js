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

    //calendar month/year button event
    $(document).on("click",".cal-btn", function () {
        var clickedBtnID = $(this).attr('id');
        setNewDateByButton(clickedBtnID);
        update(currentDate);
    });

    //new Task Modal Checkbox event
    $('#taskTime').parent().hide();
    $('#wholeDayCheck').change(function() {
        if(this.checked) {
            $('#taskTime').parent().fadeOut(200);
        }else{
            $('#taskTime').parent().fadeIn(200);
        }
    });

    //taskAdd Modal confirm add button event
    $(document).on("click","#addTask", function () {
        addTask();
    });

    //taskDel Modal confirm delete button event
    $(document).on("click","#confimDelete", function () {
        removeTaskConfirmed();
    });

    //noteRm Modal confirm rm button event
    $(document).on("click","#confimRemove", function () {
        removeNoteFromtaskConfirm();
    });

    //addNoteSelect change envent
    $('#noteAddSelect').change(function(){
        addNotesByIDs($('#offcanvasNotes').attr('taskID'), parseInt($(this).val()));
    });

    //noteAddNew Modal confirmbutton event
    $(document).on("click","#addNoteConfirm", function () {
        writeNewNoteForTask($('#offcanvasNotes').attr('taskID'));
    });

    //initialize date picker to todays date
    $('#taskDate').val(formatDateString(currentDate));
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
                let $taskNoteLink = $('<a type="button" class="task-note-link text-left fw-bold" style="text-decoration: none;"></a>');
                let $taskDelLink = $('<a type="button" class="task-del-link text-danger fw-bold ms-1" style="text-decoration: none;">-</a>');

                $taskNoteLink.css("color", '#'+value.colour);
                    
                $taskSpan.attr('noteIDs', value.notes);
                $taskSpan.attr('id', value.taskID);

                $taskNoteLink.text(value.title + ' (Notizen)');

                

                if(value.isTimed){
                    let timeStr = taskDate.toLocaleTimeString()
                    let $timeDisp = $('<span class="fw-bold ms-1"></span>');
                    $timeDisp.text(timeStr.slice(0, timeStr.lastIndexOf(':')));
                    $taskSpan.append($taskNoteLink).append($timeDisp).append($taskDelLink);
                }
                else {
                    $taskSpan.append($taskNoteLink).append($taskDelLink);
                }

                if(!lBr){ 
                    $tasks.append($taskSpan);
                    lBr = true;
                }
                else{
                    $taskSpan.prepend($('<br>'));
                    $tasks.append($taskSpan);
                }

                //add task show notes event
                $taskNoteLink.click(function () {
                    var noteIDsString = $(this).parent().attr('noteIDs');
                    var taskID = $(this).parent().attr('id');

                    var taskTitle = $(this).text();
                    taskTitle = taskTitle.slice(0, taskTitle.lastIndexOf('('));

                    if(noteIDsString === undefined || noteIDsString === null) showNotes(taskID, taskTitle, null);
                    else{
                        var noteIDs = noteIDsString.split(',');
                        showNotes(taskID, taskTitle, noteIDs);
                    }                    
                });
            
                //add delete task button event
                $taskDelLink.click(function () {
                    var taskID = $(this).parent().attr('taskID');
                    removeTask(this, taskID);
                });
            }
        });    

        //append everything to row and to calendar
        $row.append($date).append($day).append($tasks);
        $calendar.append($row);
    }
}

////add new/delete task handling
function addTask(){
    let taskTitle = $('#taskTitleInput').val();
    let taskColour = $('#taskColourInput').val();
    let taskIsTimed = $('#wholeDayCheck').is(':checked');
    let taskDateStr = $('#taskDate').val();
    let taskTime = $('#taskTime').val();

    //---------!!!!---------
    //TODO: FIX wrong date upon adding new task
    //---------!!!!---------

    //this does not give the right date
    //maybe add time zoine offset to Timestamp!!!!!!!!!!!
    console.log(taskDateStr+'T'+taskTime+':00');
    let taskDate = new Date(taskDateStr+'T'+taskTime+':00');
    let taskDateStamp = Math.floor(taskDate.getTime() / 1000);

    let postJSON =
    {
        "request":"POST",
        "type":"calendar",
        "data":{
            "title": taskTitle,
            "dueStamp": taskDateStamp,
            "isTimed": !taskIsTimed,
            "colour": taskColour.substring(1),
            "noteIDs": [] 
        }
    }

    console.log(postJSON);

    $.ajax({
        url: "./API/calendar.php",
        type: 'POST',
        headers: {"usr-token":userToken,
                },
        dataType: "json",
        data: JSON.stringify(postJSON),
        success: function(response) {
            update(currentDate);
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    });  
}

let delTaskElem = null;
let delTaskID = null;
function removeTask(taskElem ,taskID){
    delTaskElem = taskElem;
    delTaskID = taskID;
    var deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteConfirmModal.show();
}
function removeTaskConfirmed(){
    console.log(delTaskID);

    $.ajax({
        url: "./API/calendar.php",
        type: 'DELETE',
        headers: {"usr-token":userToken,
                  "calendar-entry-id":delTaskID
                },
        dataType: "json",
        success: function(response) {
            $(delTaskElem).parent().remove();
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    });  
}


////Note Showing
function showNotes(taskID, taskTitle, noteIDs){
    var offcanvasNotes = new bootstrap.Offcanvas(document.getElementById('offcanvasNotes'));
    offcanvasNotes.show();
    
    //set title of offcanvas
    $('#offcanvasNotesHeading').text('Notizen für: ' + taskTitle);

    //set task ID reference
    $('#offcanvasNotes').attr('taskID', taskID);

    //loading datalist for users Notes to be added
    fillNoteDatalist();

    if(noteIDs != null){
        $.ajax({
            url: "./API/notes.php",
            type: 'GET',
            cache: false,
            headers: {"usr-token":userToken,
                      "note-ids": noteIDs.toString()},
            dataType: "json",
            success: function(response) {
                console.log(response);
                fillNoteDisp(response);
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText
                console.error('Error - ' + errorMessage);
                console.log(error);
            }
        });
    }
}

function fillNoteDisp(response){
    let $offcanvasNotesBody = $('#offcanvas-notes-body');

    $offcanvasNotesBody.empty();

    $.each(response.notes, function( index, value ) {
        $offcanvasNotesBody.append(createNoteCard(value.noteID, value.title, value.text));
    });
}

function createNoteCard(noteID, title, text){
    let $noteCard = $('<div class="card mb-2">\
                            <div class="card-header">\
                                <div class="row">\
                                    <div class="col-10"><h5 class="card-header-text my-auto"></h5></div>\
                                    <div class="col-2 text-end"><button type="button" class="btn btn-danger btn-sm noteDelBtn" style="height: 24px; width: 24px;"></button></div>\
                                </div>\
                            </div>\
                            <div class="card-body">\
                                <p class="card-text"></p>\
                            </div>\
                        </div>');
        
    $noteCard.attr('noteID', noteID);       

    $noteCard.find('.card-header-text').text(title);
    $noteCard.find('.card-text').text(text);

    $noteCard.find('.noteDelBtn').click(function () {
        let id = parseInt($(this).closest('.card').attr('noteID'));
        removeNoteFromtask(id, $(this).closest('.card'));
    });

    return $noteCard;
}

////Notes adding
//write new Note
function writeNewNoteForTask(taskID){
    let noteTitle = $('#noteTitleInput').val();
    let noteColour = $('#noteColourInput').val();
    let noteText = $('#noteTextInput').val();

    let postJSON = {
                        "request":"POST",
                        "type":"notes",
                        "data":{
                            "title": noteTitle,
                            "text": noteText,
                            "taskID": taskID,
                            "colour": noteColour.substring(1)
                        }
                    }

    $.ajax({
        url: "./API/notes.php",
        type: 'POST',
        headers: {"usr-token":userToken},
        dataType: "json",
        data: JSON.stringify(postJSON),
        success: function(response) {
            //reset modal
            var notesModal = bootstrap.Modal.getInstance(document.getElementById('newNoteModal'));
            notesModal.hide();

            $('#noteTitleInput').val('');
            $('#noteColourInput').val('#f8f9fa');
            $('#noteTextInput').val('');
            
            //append to Notes
            $('#offcanvas-notes-body').append(createNoteCard(response.added, noteTitle, noteText));
            $taskButton = $('#'+$('#offcanvasNotes').attr('taskID'));
            addNoteID($taskButton, response.added);
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    });  
}
//add existing note
function fillNoteDatalist(){
    let $list = $('#noteAddSelect');
    $list.empty();
    $list.html('<option value="-1" selected>Notiz hinufügen...</option>');

    $.ajax({
        url: "./API/notes.php",
        type: 'GET',
        headers: {"usr-token":userToken},
        dataType: "json",
        success: function(response) {
            $.each(response.notes, function( index, value ) {
                let $optionElement = $('<option>One</option>');
                $optionElement.val(value.noteID);
                $optionElement.text(value.title);
                $list.append($optionElement);
            });            
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    }); 
}
function addNotesByIDs(taskID, noteIDs){
    if(noteIDs === NaN || noteIDs < 0) return;

    $.ajax({
        url: "./API/notes.php",
        type: 'PATCH',
        headers: {"usr-token":userToken,
                  "task-id": taskID,
                  "note-ids": noteIDs.toString()},
        dataType: "json",
        success: function(response) {
            $.ajax({
                url: "./API/notes.php",
                type: 'GET',
                headers: {"usr-token":userToken,
                          "note-ids": noteIDs.toString()},
                dataType: "json",
                success: function(response) {
                    $.each(response.notes, function( index, value ) {
                        $('#offcanvas-notes-body').append(createNoteCard(value.noteID, value.title, value.text));
                    });

                    $taskButton = $('#'+$('#offcanvasNotes').attr('taskID'));
                    addNoteID($taskButton, noteIDs);
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    console.error('Error - ' + errorMessage);
                    console.log(error);
                }
            }); 

            //reset selector
            $('#noteAddSelect').val(-1);

        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    }); 
}

//remove note from task
let rmID = null;
let rmNoteElem = null;
function removeNoteFromtask(noteID, noteElem){
    rmID = noteID;
    rmNoteElem = noteElem;
    var deleteConfirmModal = new bootstrap.Modal(document.getElementById('rmNoteConfirmModal'));
    deleteConfirmModal.show();
}
function removeNoteFromtaskConfirm(){
    $.ajax({
        url: "./API/notes.php",
        type: 'PATCH',
        headers: {"usr-token":userToken,
                  "note-ids":rmID.toString()
                },
        dataType: "json",
        success: function(response) {
            $(rmNoteElem).remove();

            $taskButton = $('#'+$('#offcanvasNotes').attr('taskID'));
            rmNoteID($taskButton, rmID);
        },
        error: function(xhr, status, error){
            var errorMessage = xhr.status + ': ' + xhr.statusText
            console.error('Error - ' + errorMessage);
            console.log(error);
        }
    });  
}

////calendar interface funtions
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
        case 'prev-mon-btn':
            currentDate.setMonth(currentDate.getMonth()-1);
            setURLBar(currentDate.getMonth(), currentDate.getFullYear());  
        break;
        case 'next-mon-btn':
            currentDate.setMonth(currentDate.getMonth()+1);
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

////other helper funtions
function daysInMonth (month, year) {
    return new Date(year, month+1, 0).getDate();
}

function formatDateString(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

function addNoteID($buttonElem, addID){
    let ids = ($buttonElem.attr('noteIDs').split(','));
    ids.push(addID);
    $buttonElem.attr('noteIDs', ids.toString());
}

function rmNoteID($buttonElem, rmID){
    let ids = ($buttonElem.attr('noteIDs')).split(',');
    
    
    var idIndex = ids.indexOf(rmID.toString());
    if (idIndex !== -1) {
        ids.splice(idIndex, 1);
    }

    console.log(ids);
    $buttonElem.attr('noteIDs', ids.toString());
}