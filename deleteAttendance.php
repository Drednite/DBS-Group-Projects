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
		$a_id = $_POST['a_id'];

		// Define the SQL query to run (replace these values as well)
		$sql = "DELETE FROM Attendance WHERE Attendance.attendance_id = $1;";

		// Run the SQL query
		$result = pg_prepare($dbhost, "delete", $sql);
		$result = pg_execute($dbhost, "delete", array($a_id));

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		echo "Attendance Record Deleted";

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Delete Attendance</title>
<body>
<form action="admin_attendance.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
