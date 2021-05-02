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
		$perf_id = $_POST['perf_id'];
		$m_id = $_POST['m_id'];
		$given = $_POST['given'];
		$returned = $_POST['returned'];
		$a_sold = $_POST['a_sold'];
		$sy_sold = $_POST['sy_sold'];
		$funds = $_POST['funds'];

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT MAX(Member_Ticket_Sales.sales_id) FROM Member_Ticket_Sales;";
		$sql3 = "INSERT INTO Member_Ticket_Sales VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";

		// Query: get next attendance id
		$result = pg_query($dbhost, $sql);
		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		$row1 = pg_fetch_array($result);
		//echo "row1: " . $row1[0] . "<br>";
		$s_id = ($row1[0] + 1) % 2147483647;
		//echo "sid + 1: " . $s_id . "<br>";

		// Insert: roles record.
		$result3 = pg_prepare($dbhost, "insert", $sql3);
		$result3 = pg_execute($dbhost, "insert", array($s_id, $perf_id, $m_id, $given, $returned, $a_sold, $sy_sold, $funds));
		if (!$result3)
		{
			die("Error in query: ".pg_last_error());
		}
		//echo "Result3: " . $result3 . "<br>";

		echo "Added Member Sales Record";

		// Free results in memory
		pg_free_result($result);
		pg_free_result($result3);

		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Add Member Sales</title>
<body>
<form action="admin_memberTicketSales.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
