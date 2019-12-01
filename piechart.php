<?php
	// Connect to MySQL
	$db = mysqli_connect('localhost', 'root', '', 'employees') or die("Unable to connect");
	$con = mysqli_connect('localhost', 'root', '', 'project_calendar') or die("Unable to connect");
	
	$salary_result = mysqli_query($db, 'SELECT SUM(Salary) AS salary_sum FROM Salaries'); 
	$salary_row = mysqli_fetch_assoc($salary_result); 
	$salary_sum = $salary_row['salary_sum'];
	
	$project_result = mysqli_query($con, 'SELECT SUM(cost) AS project_sum FROM projects'); 
	$project_row = mysqli_fetch_assoc($project_result); 
	$project_sum = $project_row['project_sum'];
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title> Finance Management </title>
<link href="https://fonts.googleapis.com/css?family=ABeeZee&display=swap" rel="stylesheet">
<link type = "text/css" rel = "stylesheet" href = "style.css"/>
<body>

<h2> Finance Management </h2>
      	<nav>
      		<a href = "homepage.html" class = "reg">Home</a>
			<a href = "employees.html" class = "reg">Employee</a>
			<a href = "salaries.html" class = "reg">Salary</a>
			<a href = "calendar.php" class = "reg">Project</a>
			<a href = "piechart.php" class = "curr">Finance</a>
  		</nav>
  		<br />
  		
<form method = "post" action = ""> Please enter current budget:
	<input type = "number" name = "getBudget" value = "" id = "BR">
	<input type="submit" value="Submit" onClick="drawChart();  return false;">
</form>

<div id="piechart"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
var salary_sum = "<?php echo $salary_sum ?>";
salary_sum = parseInt(salary_sum);

var project_sum = "<?php echo $project_sum ?>";
project_sum = parseInt(project_sum);

</script>

<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});

// Draw the chart and set the chart values
function drawChart() {
var budget = parseInt(document.getElementById('BR').value);
budget = budget - (salary_sum + project_sum);
  var data = google.visualization.arrayToDataTable([
  ['Cost', 'Dollars'],
  ['Budget Remaining', budget],
  ['Salaries', salary_sum],
  ['Projects', project_sum]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'width':1500, 'height':500};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>

</body>
</html>