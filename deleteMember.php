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

		// Define the SQL query to run (replace these values as well)
		$sql = "DELETE FROM Participant WHERE Participant.participant_id IN (SELECT Member.participant_id FROM Member WHERE Member.member_id = $1);";
		$sql2 = "DELETE FROM Member WHERE Member.member_id = $1";

		$result = pg_prepare($dbhost, "delete", $sql);
		$result2 = pg_prepare($dbhost, "delete2", $sql2);
		$result = pg_execute($dbhost, "delete", array($m_id));
		$result2 = pg_execute($dbhost, "delete2", array($m_id));

		// If the $result variable is not defined, there was an error in the query
		if (!$result || !$result2)
		{
			die("Error in query: ".pg_last_error());
		}
		echo "Member " . $m_id . " Deleted";

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<body>
<form action="admin_members.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
