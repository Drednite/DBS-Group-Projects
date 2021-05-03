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

		//echo "Data entered: " . $p_time . " " . $idType . " " . $id . "<br>";

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Attendance.attendance_id) FROM Attendance;";
		$sql3 = "INSERT INTO Attendance VALUES ($1, $2, $3);";

		// Query: get next attendance id
		$result = pg_query($dbhost, $sql);
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		$row1 = pg_fetch_array($result);
		//echo "row1: " . $row1[0] . "<br>";
		$a_id = ($row1[0] + 1) % 2147483647;
		//echo "aid + 1: " . $a_id . "<br>";
		
		// Query: get pid of member.
		if($idType == "Member")
		{
			$sql2 = "SELECT Member.participant_id FROM Member WHERE Member.member_id = $1;";
			$result2 = pg_prepare($dbhost, "query", $sql2);
			$result2 = pg_execute($dbhost, "query", array($id));
			// If the $result variable is not defined, there was an error in the query
			if (!$result2)
			{
				die("Error in query: ".pg_last_error());
			}
			$row2 = pg_fetch_array($result2);
			//echo "row2: " . $row2['participant_id'] . "<br>";
			$member_p_id = $row2['participant_id'];
			//echo "member_pid: " . $member_p_id . "<br>";
		}

		// Insert: attendance record.
		$result3 = pg_prepare($dbhost, "insert", $sql3);
		if($idType == "Member"){
			$result3 = pg_execute($dbhost, "insert", array($a_id,$member_p_id,$p_time));
		}else{
			$result3 = pg_execute($dbhost, "insert", array($a_id,$id,$p_time));
		}
		if (!$result3)
		{
			die("Error in query: ".pg_last_error());
		}
		//echo "Result3: " . $result3 . "<br>";

		echo "Attendance Record Added";

		// Free results in memory
		pg_free_result($result);
		pg_free_result($result2);
		pg_free_result($result3);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Add Attendance</title>
<body>
<form action="admin_attendance.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
