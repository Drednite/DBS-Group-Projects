<html>
	<head>
		<title>Performances</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="performance">Performance</option>
			<option value="date">Date</option>
			<option value="id">Performance ID</option>
		</select>
		<input type="submit" value="Sort">
	</form>
	<body>
		<h2>Performances</h2>
		<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="name">Name</option>
			<option value="date">Date</option>
			<option value="id">ID</option>
		</select>
		<input type="submit" value="Sort">
		</form>
		<table>
		<thead>
			<tr>
				<td>Performance Id</td>
				<td>Name</td>
				<td>Date</td>
				<td>Venue</td>
				<td>Guest Performers</td>
				<td>Online Sales</td>
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
			
			$sort = $_GET['sort'];
			$filter = "";

			switch($sort) {
				case "performance":
					$filter = "ORDER BY Performance.name";
					break;
				case "date":
					$filter = "ORDER BY Performance.performance_date";
					break;
				case "id":
					$filter = "ORDER BY Performance.performance_id";
					break;
				default:
					break;
			}

			switch($sort) {
				case "performance":
					$filter = "ORDER BY Performance.name";
					break;
				case "date":
					$filter = "ORDER BY Performance.performance_date";
					break;
				case "id":
					$filter = "ORDER BY Performance.performance_id";
					break;
				default:
					break;
			}

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Performance " . $filter . ";";

			// Run the SQL query
			$result = pg_query($dbhost, $sql);

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
		<form action="index.php" method="post">
			<input type="submit" style="color:white;background-color:blue" value="Home"/>
		</form>
	</body>
</html>
