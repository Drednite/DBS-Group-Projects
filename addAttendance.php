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
		$p_time = $_POST['p_time'];
		$idType = $_POST['idType'];
		$id = $_POST['id'];

		echo $p_time . " " . $idType . " " . $id . " ";

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Attendance.attendance_id) FROM Attendance;";
		$sql3 = "INSERT INTO Attendance VALUES ($1,$2,$3);";

		// Run the SQL query
		$result = pg_query($dbhost, $sql);

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}

		$result = $result + 1;
		// Run 2nd Query
		if($idType == "Member"){
			$sql2 = "SELECT Member.member_id FROM Member WHERE Member.participant_id = $1;";
			$result2 = pg_prepare($dbhost, "insert2", $sql2);
			$result2 = pg_execute($dbhost, "insert2", array($id));
			// If the $result variable is not defined, there was an error in the query
			if (!$result)
			{
				die("Error in query: ".pg_last_error());
			}
		}
		$result3 = pg_prepare($dbhost, "insert3", $sql3);
		if($idType == "Member"){
			$result3 = pg_execute($dbhost, "insert3", array($result,$result2,$p_time));
		}else{
			$result3 = pg_execute($dbhost, "insert3", array($result,$id,$p_time));
		}
		echo "Added";

		pg_free_result($result);
		pg_free_result($result2);
		pg_free_result($result3);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<body>
<form action="admin_attendance.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
