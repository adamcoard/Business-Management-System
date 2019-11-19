
<html>
<head>
    <title> Finance - Table </title>
    <style type = "text/css">
    
    body
	{
		background-color: snow;
		text-align: center;
		font-family: arial;
		color: black;
	}

	table
	{
		border-collapse: collapse;
		width: 100%;
	}

	th, td
	{
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even)
	{
		background-color: #f2f2f2;
	}
	
	tr:nth-child(1) 
	{
		color: black;
		text-decoration: underline;
	}

	.button
	{
    	background-color: #A9A9A9;
    	border: solid;
    	border-color: #778899;
		color: white;
    	padding: 7px 10px;
    	text-align: center;
    	text-decoration: none;
    	display: inline-block;
    	font-size: 12px;
	}
	
    </style>
</head>
<body>

<?php

	// Get input data
    $eID = (isset($_POST['eID']) ? $_POST['eID'] : '');
	$Salary = (isset($_POST['Salary']) ? $_POST['Salary'] : '');
	$action = (isset($_POST['action']) ? $_POST['action'] : '');

	// Connect to MySQL
	$db = mysqli_connect('localhost', 'root', '', 'employees') or die("Unable to connect");

	if(!$db)
	{
    	print "Error - Could not connect to MySQL";
     	exit;
	}
	
	// Select the database
	$er = mysqli_select_db($db, "employees");
	if(!$er)
	{
    	print "Error - Could not select the database";
    	exit;
	}

	// Select query based on action selected
	if($action == "display")
    	$query = "";
	else if($action == "insert")
    	$query = "insert into Salaries values($eID, '$Salary')";
	else if($action == "update")
    	$query = "update Salaries set Salary = $Salary where eID = $eID";
	else if($action == "delete")
    	$query = "delete from Salaries where eID = $eID";
	else if($action == "search")
		$query = "select * from Salaries where eID = $eID";
	else if($action == "user")
    	$query = $statement;

	if($query != "")
	{
    	trim($query);
    	$query_html = htmlspecialchars($query);
    
    	$result = mysqli_query($db,$query);
    	
    	if(!$result)
    	{
        	print "Error - this query could not be executed";
        	$error = mysqli_error($db);
        	print "<p>" . $error . "</p>";
    	}
	}

	// Display result of database query
	if($action != "search")
	{	
		$query = "SELECT * FROM Salaries";
	}

	$result = mysqli_query($db,$query);
	if(!$result)
	{
    	print "Error - the query could not be executed";
    	$error = mysqli_error($db);
    	print "<p>" . $error . "</p>";
    	exit;
	}

	// Get the number of rows in the result, as well as the first row and the number of fields in the rows
	$num_rows = mysqli_num_rows($result);

	print "<table><caption> <h2>  </h2> </caption>";
	print "<tr align = 'center'>";

	$row = mysqli_fetch_array($result);
	$num_fields = mysqli_num_fields($result);

	// Produce column labels
	$keys = array_keys($row);
	for($index = 0; $index < $num_fields; $index++) 
    	print "<th>" . $keys[2 * $index + 1] . "</th>";
	print "</tr>";
    
	// Output the values
	for($row_num = 0; $row_num < $num_rows; $row_num++)
	{
    	print "<tr align = 'center'>";
    	$values = array_values($row);
    	
    	for($index = 0; $index < $num_fields; $index++)
    	{
        	$value = htmlspecialchars($values[2 * $index + 1]);
        	print "<th>" . $value . "</th> ";
    	}
    	
    	print "</tr>";
    	$row = mysqli_fetch_array($result);
	}
	
	print "</table>";
?>
</body>
</html>
