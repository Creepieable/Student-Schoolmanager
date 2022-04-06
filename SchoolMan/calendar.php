<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Main</title>
        
    <link rel="stylesheet" href="./lib/bootstrap-5.1.3/css/bootstrap.min.css">

    <script src="./lib/JQuery/js/jquery-3.6.0.min.js"></script>
    <script src="./lib/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
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
      <h4 class="mt-3 ms-2">2022</h4>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Datum</th>
            <th scope="col">Tag</th>
            <th scope="col">Aufgaben</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">05.04.</th>
            <td>Dienstag</td>
            <td></td>
          </tr>
          <tr>
            <th scope="row">06.04.</th>
            <td>Mitwoch</td>
            <td></td>
          </tr>
          <tr>
            <th scope="row">07.04.</th>
            <td>Donnerstag</td>
            <td></td>
          </tr>
          <tr>
            <th scope="row">08.04.</th>
            <td>Freitag</td>
            <td></td>
          </tr>
          <tr class="bg-light">
            <th scope="row">09.04.</th>
            <td>Samstag</td>
            <td></td>
          </tr>
          <tr class="bg-light">
            <th scope="row">10.04.</th>
            <td>Sonntag</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-sm-4">
      
    </div>
  </div>
</div>
</body>
</html>