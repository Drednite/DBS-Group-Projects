<html>
	<head>
		<title>Database</title>
	</head>
	<body>
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

		// Define the SQL query to run (replace these values as well)
		$sql = "SELECT first_name, last_name, street_address, city, state, email, voice_part, youth_form, arrangement 
				FROM Participant LEFT OUTER JOIN Member ON Participant.participant_id = Member.member_id;";

		// Run the SQL query
		$result = pg_query($dbhost, $sql);

		// If the $result variable is not defined, there was an error in the query
		if (!$result)
		{
			die("Error in query: ".pg_last_error());
		}

		echo "<table>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Street Address</th>
					<th>City</th>
					<th>State</th>
					<th>Email</th>
					<th>Voice Part</th>
					<th>Youth Form</th>
					<th>Arrangement</th>
				</tr>";
		// Iterate through each row of the result 
		while ($row = pg_fetch_array($result))
		{
			$length = count($row);
			echo "<tr>";
			for($i = 0; $i < $length; $i++){
			// Write HTML to the page, replace this with whatever you wish to do with the data
				echo "<td>";
				echo $row[$i]."&emsp;&emsp;";
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";

		// Free the result from memory
		pg_free_result($result);

		// Close the database connection
		pg_close($dbhost);
		?>
	</body>
</html>
