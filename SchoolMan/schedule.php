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
      <a class="nav-link" href="./notes.php">Notitzen</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="./settings.php">Einstellungen</a>
    </li>
</ul>
<br>
<div class=container-md>
  <div id="plan-1">
    <h5 class="plan-title">A Woche:</h5>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Montag</th>
          <th scope="col">Dienstag</th>
          <th scope="col">Mitwoch</th>
          <th scope="col">Donnerstag</th>
          <th scope="col">Freitag</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">1</div>
              <div class="col-md-8 text-center fw-normal">7:30</div>
            </div>
          </div>
          </th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">2</div>
              <div class="col-md-8 text-center fw-normal">9:00</div>
          </div>
          </th>
          <td>Jacob</td>
          <td>Thornton</td>
          <td>@fat</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">3</div>
              <div class="col-md-8 text-center fw-normal">9:30</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">4</div>
              <div class="col-md-8 text-center fw-normal">11:00</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">5</div>
              <div class="col-md-8 text-center fw-normal">11:15</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">6</div>
              <div class="col-md-8 text-center fw-normal">12:45</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">7</div>
              <div class="col-md-8 text-center fw-normal">13:15</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">8</div>
              <div class="col-md-8 text-center fw-normal">14:45</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
      </tbody>
    </table>
    <br>
  </div>
  <div id="plan-2">
    <h5 class="plan-title">B Woche:</h5>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Montag</th>
          <th scope="col">Dienstag</th>
          <th scope="col">Mitwoch</th>
          <th scope="col">Donnerstag</th>
          <th scope="col">Freitag</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">1</div>
              <div class="col-md-8 text-center fw-normal">7:30</div>
            </div>
          </div>
          </th>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">2</div>
              <div class="col-md-8 text-center fw-normal">9:00</div>
          </div>
          </th>
          <td>Jacob</td>
          <td>Thornton</td>
          <td>@fat</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">3</div>
              <div class="col-md-8 text-center fw-normal">9:30</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">4</div>
              <div class="col-md-8 text-center fw-normal">11:00</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">5</div>
              <div class="col-md-8 text-center fw-normal">11:15</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">6</div>
              <div class="col-md-8 text-center fw-normal">12:45</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">7</div>
              <div class="col-md-8 text-center fw-normal">13:15</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
        <tr>
        <th scope="row">
          <div class="container g-0">
            <div class="row g-0">
              <div class="col-md-4">8</div>
              <div class="col-md-8 text-center fw-normal">14:45</div>
            </div>
          </div>
          </th>
          <td>Larry</td>
          <td>the Bird</td>
          <td>@twitter</td>
          <td>the Bird</td>
          <td>@twitter</td>
        </tr>
      </tbody>
    </table>
    <br>
  </div>

  <div class="input-group input-group-sm flex-nowrap w-25">
    <span class="input-group-text">Neuer Plan:</span>
    <input class="form-control" type="text" placeholder="Titel..." id="newPlanName" style="min-width: 8em;">
    <button class="btn btn-outline-primary" type="button" id="addNewPlan">Hinzufügen</button>
  </div>
</div>
</body>
</html>