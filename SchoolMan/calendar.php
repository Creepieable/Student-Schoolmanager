<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main</title>
        
    <link rel="stylesheet" href="./lib/bootstrap-5.1.3/css/bootstrap.min.css">

    <script src="./lib/JQuery/js/jquery-3.6.0.min.js"></script>
    <script src="./lib/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>

    <script src="./js/calendar.js"></script>

    <style>
    .cal-btn {
        min-width: 3em;
    }
    </style>
</head>

<body>
  <?php include "./header.html" ?>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" aria-current="page" href="./schedule.php">Stundenplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./subsplan.php">Vertretungsplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="./calendar.php">Kalender</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./tasks.php">Anstehende Aufgaben</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./notes.php">Notitzen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./settings.php">Einstellungen</a>
    </li>
</ul>

<div class=container-md>
  <div class="row">
    <div class="col-sm-8">
      <h4 class="mt-3 ms-2" id="table-h">2022</h4>
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
        <div class="btn-group" role="group">
          <button id="prev-btn" type="button" class="btn btn-primary cal-btn">Left</button>
          <button id="next-btn" type="button" class="btn btn-primary cal-btn">Right</button>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      
    </div>
  </div>
</div>
</body>
</html>