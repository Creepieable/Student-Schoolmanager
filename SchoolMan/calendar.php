<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main</title>
        
    <link rel="stylesheet" href="./lib/bootstrap-5.1.3/css/bootstrap.min.css">

    <script src="./lib/JQuery/js/jquery-3.6.0.min.js"></script>
    <script src="./lib/js-cookie/js.cookie.min.js"></script>
    <script src="./lib/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="./js/userMan.js"></script>
    <script src="./js/calendar.js"></script>

    <style>
    .cal-btn {
      min-width: 3em;
    }
    .offcanvas-end{
      width: 500px;
    }
    </style>
</head>

<body>
  <?php include "./header.html" ?>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link disabled" aria-current="page" href="./schedule.php">Stundenplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="./subsplan.php">Vertretungsplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="./calendar.php">Kalender</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./tasks.php">Anstehende Aufgaben</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./notes.php">Notizen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="./settings.php">Einstellungen</a>
    </li>
</ul>

<div class=container-md>
  <h4 class="mt-3 ms-2" id="table-h">2022</h4>
  
  <div class="text-center">
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newTaskModal">neuer Termin</button>
    <button id="akt" type="button" class="btn btn-primary cal-btn">heute</button>
    <div class="btn-group" role="group">
      <button id="prev-btn" type="button" class="btn btn-secondary cal-btn prev-btn"><</button>
      <button id="prev-mon-btn" type="button" class="btn btn-secondary cal-btn"><</button>
      <button id="jan-btn" type="button" class="btn btn-primary cal-btn">Jan</button>
      <button id="feb-btn" type="button" class="btn btn-primary cal-btn">Feb</button>
      <button id="merz-btn" type="button" class="btn btn-primary cal-btn">Merz</button>
      <button id="apr-btn" type="button" class="btn btn-primary cal-btn">Apr</button>
      <button id="mai-btn" type="button" class="btn btn-primary cal-btn">Mai</button>
      <button id="jun-btn" type="button" class="btn btn-primary cal-btn">Juni</button>
      <button id="jul-btn" type="button" class="btn btn-primary cal-btn">Juli</button>
      <button id="aug-btn" type="button" class="btn btn-primary cal-btn">Aug</button>
      <button id="sep-btn" type="button" class="btn btn-primary cal-btn">Sept</button>
      <button id="okt-btn" type="button" class="btn btn-primary cal-btn">Okt</button>
      <button id="nov-btn" type="button" class="btn btn-primary cal-btn">Nov</button>
      <button id="dec-btn" type="button" class="btn btn-primary cal-btn">Dec</button>
      <button id="next-mon-btn" type="button" class="btn btn-secondary cal-btn">></button>
      <button id="next-btn" type="button" class="btn btn-secondary cal-btn next-btn">></button>
    </div>
  </div>
  <table class="table">
    <thead id='calendart-head'>
      <tr>
        <th scope="col">Datum</th>
        <th scope="col">Tag</th>
        <th scope="col">Aufgaben</th>
      </tr>
    </thead>
    <tbody id='calendart-body'>
      <!-- filled by JS -->
    </tbody>
  </table>

  <div class="text-center">
    <button id="akt" type="button" class="btn btn-primary cal-btn">heute</button>
    <div class="btn-group" role="group">
      <button id="prev-btn" type="button" class="btn btn-secondary cal-btn prev-btn"><</button>
      <button id="prev-mon-btn" type="button" class="btn btn-secondary cal-btn"><</button>
      <button id="jan-btn" type="button" class="btn btn-primary cal-btn">Jan</button>
      <button id="feb-btn" type="button" class="btn btn-primary cal-btn">Feb</button>
      <button id="merz-btn" type="button" class="btn btn-primary cal-btn">Merz</button>
      <button id="apr-btn" type="button" class="btn btn-primary cal-btn">Apr</button>
      <button id="mai-btn" type="button" class="btn btn-primary cal-btn">Mai</button>
      <button id="jun-btn" type="button" class="btn btn-primary cal-btn">Juni</button>
      <button id="jul-btn" type="button" class="btn btn-primary cal-btn">Juli</button>
      <button id="aug-btn" type="button" class="btn btn-primary cal-btn">Aug</button>
      <button id="sep-btn" type="button" class="btn btn-primary cal-btn">Sept</button>
      <button id="okt-btn" type="button" class="btn btn-primary cal-btn">Okt</button>
      <button id="nov-btn" type="button" class="btn btn-primary cal-btn">Nov</button>
      <button id="dec-btn" type="button" class="btn btn-primary cal-btn">Dec</button>
      <button id="next-mon-btn" type="button" class="btn btn-secondary cal-btn">></button>
      <button id="next-btn" type="button" class="btn btn-secondary cal-btn next-btn">></button>
    </div>
  </div>
</div>


<!-- Note Offcanvas element -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNotes" aria-labelledby="offcanvasNotesHeading">
  <div class="offcanvas-header">
    <h5 id="offcanvasNotesHeading">Notien für ...</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div id="offcanvas-notes-body">
      <!--filled by js-->
    </div>
    <form class="border rounded p-2">
      <label for="newAddInputGroup" class="form-label">Notiz hinzufügen:</label>
      <div id="newAddInputGroup" class="input-group">
      <select class="form-select" id="noteAddSelect">
        <!--filled by js-->
      </select>
        <button class="btn btn-outline-success" type="button" id="buttonNewNote" data-bs-toggle="modal" data-bs-target="#newNoteModal">neue Notiz erstellen</button>
      </div>
    </form>
  </div>
</div>

<!-- new Task Modal -->
<div class="modal fade " id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newTaskModalTitle">Neuer Termin:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="mein neuer Termin..." id="taskTitleInput">
          <input type="color" class="form-control form-control-color" id="taskColourInput" value="#0d6efd" style="max-width: 40px;">
        </div>
        <div class="row" >
          <div class="col-auto">
            <input type="date" id="taskDate" name="trip-start">
          </div>
          <div class="col-auto form-check form-switch">
            <input class="form-check-input" type="checkbox" id="wholeDayCheck" checked>
            <label class="form-check-label" for="wholeDayCheck">Ganztägig</label>
          </div>
          <div class="col-auto"> <!--hidden by default-->
            <input type="time" id="taskTime" value="12:00">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">abbrechen</button>
        <button id="addTask" type="button" class="btn btn-success" data-bs-dismiss="modal">Termin hinzufügen</button>
      </div>
    </div>
  </div>
</div>

<!-- write Note Modal -->
<div class="modal fade" id="newNoteModal" tabindex="-1" aria-labelledby="newNoteModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newNoteModalTitle">Neue Notiz:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="meine neue Notiz..." id="noteTitleInput" required>
          <input type="color" class="form-control form-control-color" id="noteColourInput" value="#f8f9fa" style="max-width: 40px;">
        </div>
        <textarea class="form-control" id="noteTextInput" style="min-height: 10em;" required></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">abbrechen</button>
        <button id="addNoteConfirm" type="button" class="btn btn-success">Notiz hinzufügen</button>
      </div>
    </div>
  </div>
</div>

<!--del Task confirm Modal-->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmModalLabel">Kaleinereintrag wirklich löschen?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-right: auto;">abbrechen</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="confimDelete">löschen</button>
      </div>
    </div>
  </div>
</div>

<!--rm Note confirm Modal-->
<div class="modal fade" id="rmNoteConfirmModal" tabindex="-1" aria-labelledby="rmNoteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rmNoteConfirmModalLabel">Notiz wirklich entfernen?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-right: auto;">abbrechen</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="confimRemove">entfernen</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>