<html>
<title>Admin: Update Record</title>
<link rel="stylesheet" href="style.css">
	<body>
		<h2>Current Record</h2>
		<table>
		<thead>
			<tr>
				<th>Performance Id</th>
				<th>Name</th>
				<th>Date</th>
				<th>Venue</th>
				<th>Guest Performers</th>
				<th></th>
				<th>Online Sales</th>
			</tr>
		</thead>
		<tbody>
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

			$perf_id = $_POST['perf_id'];

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Performance WHERE Performance.performance_id = $1;";

			// Run the SQL query
			$result = pg_prepare($dbhost, "query", $sql);
			$result = pg_execute($dbhost, "query", array($perf_id));

			// If the $result variable is not defined, there was an error in the query
			if (!$result)
			{
				die("Error in query: ".pg_last_error());
			}

			// Iterate through each row of the result 
			while ($row = pg_fetch_array($result))
			{
			echo "<tr>";
				echo "<td>" . $row['performance_id'] . "</td>";
				echo "<td>" . $row['name'] . "</td>";
				echo "<td>" . date( "n/d/Y h:i:s A" , strtotime($row['performance_date']) ) . "</td>";
				echo "<td>" . $row['venue_name'] . "<br>" . $row['street'] . "<br>" . $row['city'] . ", " . $row['state'] . " " . $row['zip'] . "</td>";
				echo "<td>";

				// Define the SQL query to run (replace these values as well)
				$sql2 = "SELECT * FROM Starring INNER JOIN Guest_Performer ON Starring.guest_id = Guest_Performer.guest_id WHERE Starring.performance_id = " . $row['performance_id'] . ";";

				// Run the SQL query
				$result2 = pg_query($dbhost, $sql2);

				// If the $result variable is not defined, there was an error in the query
				if (!$result2)
				{
					die("Error in query: ".pg_last_error());
				}

				// Iterate through each row of the result 
				while ($row2 = pg_fetch_array($result2))
				{
					echo $row2['group_name'] . "<br>";

					// Define the SQL query to run (replace these values as well)
					$sql3 = "SELECT * FROM Guest_Member WHERE Guest_Member.guest_id = " . $row2['guest_id'] . ";";

					// Run the SQL query
					$result3 = pg_query($dbhost, $sql3);

					// If the $result variable is not defined, there was an error in the query
					if (!$result3)
					{
						die("Error in query: ".pg_last_error());
					}

					// Iterate through each row of the result 
					while ($row3 = pg_fetch_array($result3))
					{
						echo "&emsp;" . $row3['first_name'] . " \"" . $row3['preferred_name'] . "\" " . $row3['last_name'] . "<br>";
					}
					pg_free_result($result3);

					?>
					<form action="deletePerformanceGuestConnection.php" method="post">
					<input type="hidden" name="star_id" value="<?php echo $row2['starring_id']?>"/>
					<input type="hidden" name="perf_id" value="<?php echo $perf_id ?>"/>
					<input type="submit" style="color:white;background-color:green" value="Remove Group"/>
					</form>
					<?php
				}
				pg_free_result($result2);

				echo "</td>";
				echo "<td>" . $row['online_sales'] . "</td>";
			echo "</tr>";
			}

			// Free the result from memory
			pg_free_result($result);

			// Close the database connection
			pg_close($dbhost);
			?>
		</tbody>
		</table>
		<form action="admin_performances.php">
			<input type="submit" value="Done"/>
		</form>
		<h2>Add Guest Performer</h2>
		<form action="addPerformanceGuestConnection.php" method="post">
			<input type="hidden" name="perf_id" value="<?php echo $perf_id ?>"/>
			<p>Guest Id: <input type="text" name="guest_id"/></p>
			<input type="submit" value="Add"/>
		</form>
	</body>
</html>
