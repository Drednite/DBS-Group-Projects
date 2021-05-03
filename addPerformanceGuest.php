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
		$guest_id = $_POST['guest_id'];
		$group_name = $_POST['group_name'];

		// Define the SQL query to run (replace these values as well)
		$sql = "INSERT INTO guest_performer VALUES ($1, $2);";

		// Query
		$result = pg_prepare($dbhost, "query", $sql);
		$result = pg_execute($dbhost, "query", array($guest_id, $group_name));
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		
		echo "Added info.<br>Continue to add Members to group.";
		
		// Free results in memory
		pg_free_result($result);
		// close connection
		pg_close($dbhost);
?>
<html>
<title>Add Guest Performer</title>
<body>
<form action="updatePerformanceGuest.php" method="post">
	<input type="hidden" name="guest_id" value="<?php echo $guest_id ?>"/>
	<input type="submit" value="Continue"/>
</form>
</body>
</html>
