<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main</title>
        
    <link rel="stylesheet" href="./lib/bootstrap-5.1.3/css/bootstrap.min.css">

    <script src="./lib/JQuery/js/jquery-3.6.0.min.js"></script>
    <script src="./lib/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>

    <script src="./js/schedule.js"></script>
</head>

<body>
  <?php include "./header.html" ?>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="./schedule.php">Stundenplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./subsplan.php">Vertretungsplan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./calendar.php">Kalender</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./tasks.php">Anstehende Aufgaben</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./notes.php">Notizen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./settings.php">Einstellungen</a>
    </li>
</ul>
<br>
<div class=container-md>
  <div id="scheduleContainer">
    <!--Filled by JS-->
  </div>
  <br>
  <div class="input-group input-group-sm flex-nowrap w-25">
    <span class="input-group-text">Neuer Plan:</span>
    <input class="form-control" type="text" placeholder="Titel..." id="newPlanName" style="min-width: 8em;">
    <button class="btn btn-outline-primary" type="button" id="addNewPlan">Hinzufügen</button>
  </div>
  <br>
</div>
</body>
</html>