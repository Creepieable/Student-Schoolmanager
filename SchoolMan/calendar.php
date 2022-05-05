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
    .offcanvas-end{
      width: 500px;
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
      <a class="nav-link" href="./notes.php">Notizen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./settings.php">Einstellungen</a>
    </li>
</ul>

<div class=container-md>
  <h4 class="mt-3 ms-2" id="table-h">2022</h4>
  <div class="text-center">
    <button id="akt" type="button" class="btn btn-primary cal-btn">heute</button>
    <div class="btn-group" role="group">
      <button id="prev-btn" type="button" class="btn btn-secondary cal-btn"><</button>
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
      <button id="next-btn" type="button" class="btn btn-secondary cal-btn">></button>
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
      <button id="prev-btn" type="button" class="btn btn-secondary cal-btn"><</button>
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
      <button id="next-btn" type="button" class="btn btn-secondary cal-btn">></button>
    </div>
  </div>
</div>

<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Toggle right offcanvas</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel">Notiz: ...</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <img width='80%' src="https://img.chefkoch-cdn.de/rezepte/3146641468504732/bilder/1303719/crop-960x720/hokkaido-milk-bread.jpg" class="rounded mx-auto d-block" alt="...">
    <br>
    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
    <br>
    <img width='80%' src="https://cdn.pixabay.com/photo/2019/02/06/17/09/snake-3979601__480.jpg" class="rounded mx-auto d-block" alt="...">
    <br>
    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
  </div>
</div>
</body>
</html>