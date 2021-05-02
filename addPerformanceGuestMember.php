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
		$f_name = $_POST['first_name'];
		$l_name = $_POST['last_name'];
		$p_name = $_POST['preferred_name'];

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Guest_Member.guest_member_id) FROM Guest_Member;";
		$sql3 = "INSERT INTO Guest_Member VALUES ($1, $2, $3, $4, $5);";

		// Query: get next attendance id
		$result = pg_query($dbhost, $sql);
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		$row1 = pg_fetch_array($result);
		//echo "row1: " . $row1[0] . "<br>";
		$gm_id = ($row1[0] + 1) % 2147483647;
		//echo "gmid + 1: " . $gm_id . "<br>";

		// Insert
		$result3 = pg_prepare($dbhost, "insert", $sql3);
		$result3 = pg_execute($dbhost, "insert", array($gm_id, $guest_id, $l_name, $f_name, $p_name));

		if (!$result3)
		{
			die("Error in query: ".pg_last_error());
		}
		//echo "Result3: " . $result3 . "<br>";

		echo "Added Member to Guest Performer";
		
		// Free results in memory
		pg_free_result($result);
		pg_free_result($result3);
		// close connection
		pg_close($dbhost);
?>
<html>
<title>Add Performance Guest Member</title>
<body>
<form action="updatePerformanceGuest.php" method="post">
	<input type="hidden" name="guest_id" value="<?php echo $guest_id ?>"/>
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
