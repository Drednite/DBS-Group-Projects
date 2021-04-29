<html>
	<head>
		<title>Member Info</title>
	</head>
	<body>
		<table>
		<thead>
			<tr>
				<td>Member Id</td>
				<td>Name</td>
				<td>Voice Part</td>
				<td>Address</td>
				<td>Phone</td>
				<td>Email</td>
				<td>Facebook</td>
				<td>Birthdate</td>
				<td>Generation</td>
				<td>Spouse</td>
				<td>Arrangement</td>
				<td>Vest Size</td>
				<td>Youth Form</td>
				<td>Roles</td>
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
			
			$member = $_GET['member'];

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Participant INNER JOIN Member ON Participant.participant_id = Member.participant_id WHERE Member.member_id = $1;";
			$sql2 = "SELECT * FROM Roles WHERE Roles.member_id = $1;";

			// Run the SQL query
			$result = pg_prepare($dbhost, "query", $sql);
			$result2 = pg_prepare($dbhost, "query2", $sql2);
			$result = pg_execute($dbhost, "query", array($member));
			$result2 = pg_execute($dbhost, "query2", array($member));

			// If the $result variable is not defined, there was an error in the query
			if (!$result || !$result2)
			{
				die("Error in query: ".pg_last_error());
			}

			// Iterate through each row of the result 
			while ($row = pg_fetch_array($result))
			{
			echo "<tr>";
				echo "<td>" . $row['member_id'] . "</td>";
				echo "<td>" . $row['first_name'] . " \"" . $row['preferred_name'] . "\" " . $row['last_name'] . "</td>";
				echo "<td>" . $row['voice_part'] . "</td>";
				echo "<td>" . $row['street_address'] . "<br>" . $row['city'] . ", " . $row['state'] . " " . $row['zip'] . "</td>";
				echo "<td>Home: " . $row['home_phone'] . "<br>Cell: " . $row['cell_phone'] . "<br>Work: " . $row['work_phone'] . "</td>";
				echo "<td>" . $row['email'] . "</td>";
				echo "<td>" . $row['on_facebook'] . "</td>";
				echo "<td>" . $row['birthdate'] . "</td>";
				echo "<td>" . $row['generation'] . "</td>";
				echo "<td>" . $row['spouse_first'] . " \"" . $row['spouse_preferred'] . "\" " . $row['spouse_last'] . "</td>";
				echo "<td>" . $row['arrangement'] . "</td>";
				echo "<td>" . $row['vest_size'] . "</td>";
				echo "<td>" . $row['youth_form'] . "</td>";
				echo "<td>";
				while ($row2 = pg_fetch_array($result2))
				{
					echo $row2['name'] . "<br>";
				}
				echo "</td>";
				pg_free_result($result2);
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
