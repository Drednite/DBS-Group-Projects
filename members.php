<html>
	<head>
		<title>Members</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="first">First Name</option>
			<option value="last">Last Name</option>
			<option value="id">ID</option>
		</select>
		<input type="submit" value="Sort">
	</form>
	<body>
		<table>
		<thead>
			<tr>
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
			
			$sort = $_GET['sort'];
			$filter = "";

			switch($sort) {
				case "first":
					$filter = "ORDER BY Participant.first_name";
					break;
				case "last":
					$filter = "ORDER BY Participant.last_name";
					break;
				case "id":
					$filter = "ORDER BY Participant.participant_id";
					break;
				default:
					break;
			}

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Participant INNER JOIN Member ON Participant.participant_id = Member.participant_id " . $filter . ";";

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
				echo "<td><a href=\"memberInfo.php?member=" . $row['member_id'] . "\">" . $row['member_id'] . "</a></td>";
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
