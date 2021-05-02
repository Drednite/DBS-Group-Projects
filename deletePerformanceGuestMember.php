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
		$gm_id = $_POST['gm_id'];
		$guest_id = $_POST['guest_id'];

		// Define the SQL query to run (replace these values as well)
		$sql = "DELETE FROM Guest_Member WHERE Guest_Member.guest_member_id = $1;";

		// Run transaction
		$result = pg_prepare($dbhost, "delete", $sql);
		$result = pg_execute($dbhost, "delete", array($gm_id));

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}

		echo "Performance Guest Member Deleted";

		// free memory
		pg_free_result($result);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Delete Performance Guest Member</title>
<body>
<form action="updatePerformanceGuest.php" method="post">
	<input type="hidden" name="guest_id" value="<?php echo $guest_id ?>"/>
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
