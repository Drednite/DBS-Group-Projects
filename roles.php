<html>
	<head>
		<title>Board Members</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h2>Board Members</h2>
		<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="fname">First Name</option>
			<option value="lname">Last Name</option>
			<option value="pname">Preferred Name</option>
			<option value="role">Role</option>
			<option value="id">ID</option>
		</select>
		<input type="submit" value="Sort">
	</form>
		<table>
		<thead>
			<tr>
				<th>Role</th>
				<th>Member Id</th>
				<th>Name</th>
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
				case "name":
					$filter = "ORDER BY Participant.first_name";
					break;
				case "role":
					$filter = "ORDER BY Roles.name";
					break;
				case "id":
					$filter = "ORDER BY Participant.participant_id";
					break;
				default:
					break;
			}

$sort = $_GET['sort'];
			$filter = "";

			switch($sort) {
				case "fname":
					$filter = "ORDER BY Participant.first_name";
					break;
				case "lname":
					$filter = "ORDER BY Participant.last_name";
					break;
				case "pname":
					$filter = "ORDER BY Participant.preferred_name";
					break;
				case "role":
					$filter = "ORDER BY Roles.name";
					break;
				case "id":
					$filter = "ORDER BY Participant.participant_id";
					break;
				default:
					break;
			}

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Roles INNER JOIN Member ON Roles.member_id = Member.member_id INNER JOIN Participant ON Participant.participant_id = Member.participant_id " . $filter . ";";

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
				echo "<td>" . $row['name'] . "</td>";
				echo "<td><a href=\"memberInfo.php?member=" . $row['member_id'] . "\">" . $row['member_id'] . "</a></td>";
				echo "<td>" . $row['first_name'] . " \"" . $row['preferred_name'] . "\" " . $row['last_name'] . "</td>";
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
