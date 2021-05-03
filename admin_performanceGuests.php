<html>
	<head>
		<title>Admin: Performance Guests</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h2>Add Guest Performer</h2>
		<form action="addPerformanceGuest.php" method="post">
			<p>Guest Id: <input type="text" name="guest_id"/></p>
			<p>Group Name: <input type="text" name="group_name"/></p>
			<input type="submit" value="Add"/>
		</form>
		<h2>Log of Guest Performers</h2>
		<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="guest_id">Guest Id</option>
			<option value="name">Group Name</option>
		</select>
		<input type="submit" value="Sort">
	</form>
		<table>
		<thead>
			<tr>
				<th>Guest Id</th>
				<th>Group Name</th>
				<th>Members</th>
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

			switch($sort) {
				case "guest_id":
					$filter = "ORDER BY Guest_Performer.guest_id";
					break;
				case "group_name":
					$filter = "ORDER BY Guest_Performer.group_name";
					break;
				default:
					break;
			}

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Guest_Performer " . $filter . ";";

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
				echo "<td>" . $row['guest_id'] . "</td>";
				echo "<td>" . $row['group_name'] . "</td>";
				
				echo "<td>";

				// Define the SQL query to run (replace these values as well)
				$sql3 = "SELECT * FROM Guest_Member WHERE Guest_Member.guest_id = " . $row['guest_id'] . ";";

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
					echo $row3['first_name'] . " \"" . $row3['preferred_name'] . "\" " . $row3['last_name'] . "<br>";
				}
				pg_free_result($result3);

				echo "</td>";
				?>
				<td><form action="updatePerformanceGuest.php" method="post">
					<input type="hidden" name="guest_id" value="<?php echo $row['guest_id']?>"/>
					<input type="submit" style="color:white;background-color:green" value="Update"/>
				</form></td>
				<?php
				?>
				<td><form action="deletePerformanceGuest.php" method="post">
					<input type="hidden" name="guest_id" value="<?php echo $row['guest_id']?>"/>
					<input type="submit" style="color:white;background-color:red" value="Delete"/>
				</form></td>
				<?php
			echo "</tr>";
			}

			// Free the result from memory
			pg_free_result($result);

			// Close the database connection
			pg_close($dbhost);
			?>
		</tbody>
		</table>
		<form action="admin.php" method="post">
			<input type="submit" style="color:white;background-color:blue" value="Admin Home"/>
		</form>
	</body>
</html>
