<?php
session_start();
include('../../wp-load.php');
if(!isset($_SESSION["display_name"])){
  $user_login = $_POST['log'];
  $user_password = $_POST['pwd'];
  $credentials = array(
    'user_login'    => $user_login,
    'user_password' => $user_password,
    'remember'      => false,
  );
  $result = wp_signon($credentials);
  $json = json_encode($result,true);
  //echo $json."<br>";
  $errors = strpos($json,"errors");
  //echo $errors;
  if(!$errors){
    $array = json_decode($json,true);
    $_SESSION["user_login"]=$array["data"]["user_login"];
    $_SESSION["user_email"]=$array["data"]["user_email"];
    $_SESSION["display_name"]=$array["data"]["display_name"];
  } else {
    session_unset();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>OBBServer HRIS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Year', 'Income', 'Expenses', 'Profit'],
            ['2020', 1000, 400, 200],
            ['2021', 1170, 460, 250],
            ['2022', 660, 1120, 300],
            ['2023', 1030, 540, 350]
        ]);

        var options = {
            chart: {
                title: 'Company Performance',
                subtitle: 'Income, Expenses, and Profit: 2020-2023',
            },
            bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
    </script>
</head>

<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">OBBServer HRIS</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <?php
      if(isset($_SESSION["display_name"])){
      ?>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Employee<span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="employee_list.php">Master List</a></li>
                        <li><a href="employee_attendance.php">Time Sheet</a></li>
                    </ul>
                </li>
                <li><a href="#">Reports</a></li>
                <?php
      }
      ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
                <?php 
        if(!isset($_SESSION["display_name"])){
          ?>
                <li><a href="#" data-toggle="modal" data-target="#myModal"><span
                            class="glyphicon glyphicon-log-in"></span> Login</a></li>
                <?php
        } else {
          ?>
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Welcome back
                        <?php echo ucwords($_SESSION["display_name"]); ?> &nbsp; </a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                <?php
        }
      ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <form action="index.php" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Login</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email">Username or Email address:</label>
                                <input type="text" class="form-control" id="email" name="log">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type="password" class="form-control" id="pwd" name="pwd">
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox"> Remember me</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <h3>OBBServer HRIS</h3>
        <p>This web application streamlines human resource management for schools by offering a user-friendly interface
            to manage employee information, such as personal details, certifications, and employment history. It
            facilitates efficient communication through a centralized platform, allowing administrators to handle leave
            requests, track attendance, and generate reports effortlessly. Additionally, the system ensures compliance
            with relevant regulations by securely storing and managing sensitive personnel data.</p>
        <hr>
        <?php
      if(isset($_SESSION["display_name"])){
        ?>
        <div id="barchart_material" style="width: 900px; height: 500px;"></div>
        <?php
      }
      ?>
    </div>
</body>

</html>