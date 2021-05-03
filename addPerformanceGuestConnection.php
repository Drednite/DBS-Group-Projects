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
		$guest_id = $_POST['guest_id'];

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Starring.starring_id) FROM Starring;";
		$sql3 = "INSERT INTO Starring VALUES ($1, $2, $3);";

		// Query
		$result = pg_query($dbhost, $sql);
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		$row1 = pg_fetch_array($result);
		//echo "row1: " . $row1[0] . "<br>";
		$star_id = ($row1[0] + 1) % 2147483647;
		//echo "gmid + 1: " . $star_id . "<br>";

		// Insert
		$result3 = pg_prepare($dbhost, "insert", $sql3);
		$result3 = pg_execute($dbhost, "insert", array($star_id, $perf_id, $guest_id));

		if (!$result3)
		{
			die("Error in query: ".pg_last_error());
		}
		//echo "Result3: " . $result3 . "<br>";

		echo "Added Guest Performer " . $guest_id . " to Performance " . $perf_id;
		
		// Free results in memory
		pg_free_result($result);
		pg_free_result($result3);
		// close connection
		pg_close($dbhost);
?>
<html>
<title>Add Performance Guest Performer</title>
<body>
<form action="updatePerformance.php" method="post">
	<input type="hidden" name="perf_id" value="<?php echo $perf_id ?>"/>
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
