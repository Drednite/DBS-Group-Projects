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
		$s_id = $_POST['s_id'];

		// Define the SQL query to run (replace these values as well)
		$sql = "DELETE FROM Member_Ticket_Sales WHERE Member_Ticket_Sales.sales_id = $1;";

		// Run the SQL query
		$result = pg_prepare($dbhost, "delete", $sql);
		$result = pg_execute($dbhost, "delete", array($s_id));

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}
		echo "Member Sales Record Deleted";

		// Free memory
		pg_free_result($result);
		// Close the database connection
		pg_close($dbhost);
?>
<html>
<title>Delete Member Sales</title>
<body>
<form action="admin_memberTicketSales.php">
	<input type="submit" value="Ok"/>
</form>
</body>
</html>
