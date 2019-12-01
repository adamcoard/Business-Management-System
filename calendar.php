<?php include_once('functions.php'); ?>
<!DOCTYPE html>
<head>
	<title> Project Management </title>
	<link type = "text/css" rel = "stylesheet" href = "style.css"/>
	<link href = "https://fonts.googleapis.com/css?family=ABeeZee&display=swap" rel = "stylesheet">
	<script src = "jquery.min.js"></script>
</head>
<body>
	<h2> Project Management </h2>
	<nav>
      	<a href = "homepage.html" class = "reg">Home</a>
		<a href = "employees.html" class = "reg">Employee</a>
		<a href = "salaries.html" class = "reg">Salary</a>
		<a href = "calendar.php" class = "curr">Project</a>
		<a href = "piechart.php" class = "reg">Finance</a>
  	</nav>
	<div id = "calendar_div">
		<?php echo get_calender_full(); ?>
	</div>
</body>
</html>