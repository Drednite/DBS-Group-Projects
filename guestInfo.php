<html>
	<head>
		<title>Guest Info</title>
	</head>
	<body>
		<table>
		<thead>
			<tr>
				<td>Participant Id</td>
				<td>Name</td>
				<td>Voice Part</td>
				<td>Address</td>
				<td>Phone</td>
				<td>Email</td>
				<td>Facebook</td>
				<td>Birthdate</td>
				<td>Spouse</td>
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
			
			$guest = $_GET['guest'];

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Participant WHERE Participant.participant_id = " . $guest . ";";

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
				echo "<td>" . $row['participant_id'] . "</td>";
				echo "<td>" . $row['first_name'] . " \"" . $row['preferred_name'] . "\" " . $row['last_name'] . "</td>";
				echo "<td>" . $row['voice_part'] . "</td>";
				echo "<td>" . $row['street_address'] . "<br>" . $row['city'] . ", " . $row['state'] . " " . $row['zip'] . "</td>";
				echo "<td>Home: " . $row['home_phone'] . "<br>Cell: " . $row['cell_phone'] . "<br>Work: " . $row['work_phone'] . "</td>";
				echo "<td>" . $row['email'] . "</td>";
				echo "<td>" . $row['on_facebook'] . "</td>";
				echo "<td>" . $row['birthdate'] . "</td>";
				echo "<td>" . $row['spouse_first'] . " \"" . $row['spouse_preferred'] . "\" " . $row['spouse_last'] . "</td>";
			echo "</tr>";
			}

			// Free the result from memory
			pg_free_result($result);

			// Close the database connection
			pg_close($dbhost);
			?>
		</tbody>
		</table>
	</body>
</html>
