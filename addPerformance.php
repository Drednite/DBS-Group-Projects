<?php
		// Make a connection to the database
		$myfile = fopen("../pg_connection_info.txt", "r") or die("Unable to open \"../pg_connection_info.txt\" file!");
		$my_host = fgets($myfile);
		$my_dbname = fgets($myfile);
		$my_user = fgets($myfile);
		$my_password = fgets($myfile);
		fclose($myfile);
		$dbhost = pg_connect("host=" . $my_host . " dbname=" . $my_dbname . " user=" . $my_user . " password=" . $my_password);

		// If the $dbhost variable is not defined, there was an error
		if(!$dbhost)
		{
			die("Error: ".pg_last_error());
		}

		// Get variables
		$perf_id = $_POST['perf_id'];
		$p_time = $_POST['p_time'];
		$name = $_POST['name'];
		$v_name = $_POST['v_name'];
		$street = $_POST['street'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$o_sales = $_POST['o_sales'];

		// Define the SQL query to run (replace these values as well)
		$sql = "INSERT INTO Performance VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9);";

		// Query
		$result = pg_prepare($dbhost, "query", $sql);
		$result = pg_execute($dbhost, "query", array($perf_id, $p_time, $name, $v_name, $street, $city, $state, $zip, $o_sales));
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		
		echo "Added info.<br>Continue to add Guests Performers.";
		
		// Free results in memory
		pg_free_result($result);
		// close connection
		pg_close($dbhost);
?>
<html>
<title>Add Performance</title>
<body>
<form action="updatePerformance.php" method="post">
	<input type="hidden" name="perf_id" value="<?php echo $perf_id ?>"/>
	<input type="submit" value="Continue"/>
</form>
</body>
</html>
