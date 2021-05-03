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
		$m_id = $_POST['m_id'];
		$name = $_POST['name'];

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Roles.role_id) FROM Roles;";
		$sql3 = "INSERT INTO Roles VALUES ($1, $2, $3)";

		// Query: get next attendance id
		$result = pg_query($dbhost, $sql);
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		$row1 = pg_fetch_array($result);
		//echo "row1: " . $row1[0] . "<br>";
		$r_id = ($row1[0] + 1) % 2147483647;
		//echo "rid + 1: " . $r_id . "<br>";

		// Insert: roles record.
		$result3 = pg_prepare($dbhost, "insert", $sql3);
		$result3 = pg_execute($dbhost, "insert", array($r_id, $m_id, $name));
		if (!$result3)
		{
			die("Error in query: ".pg_last_error());
		}
		//echo "Result3: " . $result3 . "<br>";

		echo "Role Added";

		// Free results in memory
		pg_free_result($result);
		pg_free_result($result3);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Add Role</title>
<body>
<form action="admin_roles.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
