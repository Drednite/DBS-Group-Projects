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
		// echo $m_id;
		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT Member.participant_id FROM Member WHERE Member.member_id = $1;";
		$sql2 = "DELETE FROM Member WHERE Member.member_id = $1;";
		$sql3 = "DELETE FROM Participant WHERE Participant.participant_id = $1;";

		// Begin transaction
		pg_query($dbhost, "BEGIN;");

		// Run transaction
		$result = pg_prepare($dbhost, "delete", $sql);
		$result2 = pg_prepare($dbhost, "delete2", $sql2);
		$result3 = pg_prepare($dbhost, "delete3", $sql3);
		$result = pg_execute($dbhost, "delete", array($m_id));
		$result2 = pg_execute($dbhost, "delete2", array($m_id));
		$row = pg_fetch_array($result);
		$p_id = $row['participant_id'];
		// echo $p_id;
		$result3 = pg_execute($dbhost, "delete3", array($p_id));
		// echo "success";
		// echo $result . $result2;

		// If the $result variable is not defined, there was an error in the query
		if (!$result || !$result2)
		{
			pg_query($dbhost, "ROLLBACK;");
			die("Error in query: ".pg_last_error());
		}

		pg_query($dbhost, "COMMIT;");
		echo "Member " . $m_id . " Deleted";

		// free memory
		pg_free_result($result);
		pg_free_result($result2);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Delete Member</title>
<body>
<form action="admin_members.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>