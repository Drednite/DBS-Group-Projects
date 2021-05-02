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
		$p_id = $_POST['p_id'];
		$p_fname = $_POST['p_fname'];
		$p_lname = $_POST['p_lname'];
		$p_pname = $_POST['p_pname'];
		$p_birthdate = $_POST['p_birthdate'];
		$p_street = $_POST['p_street'];
		$p_city = $_POST['p_city'];
		$p_state = $_POST['p_state'];
		$p_zip = $_POST['p_zip'];
		$p_hphone = $_POST['p_hphone'];
		$p_cphone = $_POST['p_cphone'];
		$p_wphone = $_POST['p_wphone'];
		$p_email = $_POST['p_email'];
		$p_sfname = $_POST['p_sfname'];
		$p_slname = $_POST['p_slname'];
		$p_spname = $_POST['p_spname'];
		$p_fb = $_POST['p_fb'];
		$p_vp = $_POST['p_vp'];
		$m_id = $_POST['m_id'];
		$m_yf = $_POST['m_yf'];
		$m_arrangement = $_POST['m_arrangement'];
		$m_vs = $_POST['m_vs'];
		$m_gen = $_POST['m_gen'];

		// Define the SQL query to run (replace these values as well)
		$sql = "INSERT INTO Participant VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18);";
		$sql2 = "INSERT INTO Member VALUES ($1, $2, $3, $4, $5, $6);";

		// Run the SQL query
		$result = pg_prepare($dbhost, "insert", $sql);
		$result2 = pg_prepare($dbhost, "insert2", $sql2);
		$result = pg_execute($dbhost, "insert", array($p_id, $p_lname, $p_fname, $p_pname, $p_street, $p_city, $p_state, $p_zip, $p_hphone, $p_cphone, $p_wphone, $p_email, $p_birthdate, $p_sfname, $p_slname, $p_spname, $p_fb, $p_vp));
		$result2 = pg_execute($dbhost, "insert2", array($m_id, $p_id, $m_yf, $m_arrangement, $m_vs, $m_gen));

		// If the $result variable is not defined, there was an error in the query
		if (!$result || !$result2)
		{
			die("Error in query: ".pg_last_error());
		}
		echo "Member Added";

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Add Member</title>
<body>
<form action="admin_members.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
