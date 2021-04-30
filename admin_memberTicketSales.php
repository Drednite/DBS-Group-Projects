<html>
	<head>
		<title>Member Sales</title>
	</head>
	<body>
		<table>
		<thead>
			<tr>
				<td>Performance</td>
				<td>Member</td>
				<td>Tickets Given</td>
				<td>Tickets Returned</td>
				<td>Tickets Sold</td>
				<td>Funds Collected</td>
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
			$sql = "SELECT * FROM Performance INNER JOIN Member_Ticket_Sales ON Performance.performance_id = Member_Ticket_Sales.performance_id INNER JOIN Member ON Member_Ticket_Sales.member_id = Member.member_id INNER JOIN Participant ON Member.participant_id = Participant.participant_id;";

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
				echo "<td>" . $row['performance_id'] . "<br>" . $row['name'] . "<br>" . date( "n/d/Y h:i:s A" , strtotime($row['performance_date']) ) . "</td>";
				echo "<td><a href=\"http://students.cs.ndsu.nodak.edu/~christien.frank/memberInfo.php?member=" . $row['member_id'] . "\">" . $row['member_id'] . "</a><br>" . $row['first_name'] . " \"" . $row['preferred_name'] . "\" " . $row['last_name'] . "</td>";
				echo "<td>" . $row['tickets_given'] . "</td>";
				echo "<td>" . $row['tickets_returned'] . "</td>";
				echo "<td>Adult: " . $row['adult_sold'] . "<br>Youth/Student: " . $row['student_youth_sold'] . "</td>";
				echo "<td>" . $row['funds_collected'] . "</td>";
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