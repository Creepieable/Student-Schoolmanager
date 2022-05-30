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
      <a class="nav-link" href="./calendar.php">Kalender</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./tasks.php">Anstehende Aufgaben</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="./notes.php">Notizen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="./settings.php">Einstellungen</a>
    </li>
</ul>

<span class="row m-1 p-1 border rounded">
  <div class="col-auto me-auto">
    <div class="form-check mt-2">
      <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
      <label class="form-check-label" for="flexCheckChecked">kalender Notizen zeigen</label>
    </div>
  </div>
  <div class="col-auto">
    <div class="col-auto">
      <button type="button" class="btn btn-outline-success">neue Notiz erstellen...</button>
    </div>
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-outline-primary">Notizen aktualisieren</button>
  </div>
</span>

<div class="container-md my-4">
  <div class="row row-cols-1 row-cols-md-3 g-3">
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <img src="https://media.istockphoto.com/photos/european-short-haired-cat-picture-id1072769156?k=20&m=1072769156&s=612x612&w=0&h=k6eFXtE7bpEmR2ns5p3qe_KYh098CVLMz4iKm5OuO6Y=" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
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
        <textarea class="form-control mb-1" id="noteTextInput" style="min-height: 10em;" required></textarea>

      <span style="font-size: .8em;">Du kannst in deinen <i>Notizen</i> dinge mit <a href="https://www.markdownguide.org/basic-syntax/">Markdown</a> <b>hervorheben</b>!</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">abbrechen</button>
        <button id="addNoteConfirm" type="button" class="btn btn-success">Notiz hinzufügen</button>
      </div>
    </div>
  </div>
</div>

<!-- edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1" aria-labelledby="editNoteModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editNoteModalTitle">Notiz bearbeiten:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="meine neue Notiz..." id="editNoteTitleInput" required>
          <input type="color" class="form-control form-control-color" id="editNoteColourInput" value="#f8f9fa" style="max-width: 40px;">
        </div>
        <textarea class="form-control mb-1" id="editNoteTextInput" style="min-height: 10em;" required></textarea>

      <span style="font-size: .8em;">Du kannst in deinen <i>Notizen</i> dinge mit <a href="https://www.markdownguide.org/basic-syntax/">Markdown</a> <b>hervorheben</b>!</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">abbrechen</button>
        <button id="editNoteNoteConfirm" type="button" class="btn btn-success">Notiz aktualisieren</button>
      </div>
    </div>
  </div>
</div>

<!--rm Note confirm Modal-->
<div class="modal fade" id="rmNoteConfirmModal" tabindex="-1" aria-labelledby="rmNoteConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rmNoteConfirmModalLabel">Notiz entfernen/löschen?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          Notiz entfernen oder entgültig löschen?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="margin-right: auto;">abbrechen</button>
        <div class="btn-group me-2" role="group" aria-label="rmGroup">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="confimNoteRemove">entfernen</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="confimNoteDelete">löschen</button>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>