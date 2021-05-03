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
		$star_id = $_POST['star_id'];
		$perf_id = $_POST['perf_id'];

		// Define the SQL query to run (replace these values as well)
		$sql = "DELETE FROM Starring WHERE Starring.starring_id = $1;";

		// Run transaction
		$result = pg_prepare($dbhost, "delete", $sql);
		$result = pg_execute($dbhost, "delete", array($star_id));

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}

		echo "Guest Performer " . $guest_id . " Removed from Performance " . $perf_id;

		// free memory
		pg_free_result($result);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Delete Performance Guest Performer</title>
<body>
<form action="updatePerformance.php" method="post">
	<input type="hidden" name="perf_id" value="<?php echo $perf_id ?>"/>
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
