<html>
	<head>
		<title>Attendance</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<table>
		<thead>
			<tr>
				<th>Date</th>
				<th>Member Id</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Preferred Name</th>
				<th>Voice Part</th>
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

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Attendance INNER JOIN Participant ON Attendance.participant_id = Participant.participant_id LEFT OUTER JOIN Member ON Participant.participant_id = Member.participant_id;";

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
				echo "<td>" . date( "n/d/Y h:i:s A" , strtotime($row['attend_date']) ) . "</td>";
				echo "<td><a href=\"http://students.cs.ndsu.nodak.edu/~christien.frank/memberInfo.php?member=" . $row['member_id'] . "\">" . $row['member_id'] . "</a></td>";
				echo "<td>" . $row['last_name'] . "</td>";
				echo "<td>" . $row['first_name'] . "</td>";
				echo "<td>" . $row['preferred_name'] . "</td>";
				echo "<td>" . $row['voice_part'] . "</td>";
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
