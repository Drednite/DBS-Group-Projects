<html>
	<head>
		<title>Admin: Member Sales</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h2>Add Member Sales Record</h2>
		<form action="addMemberTicketSales.php" method="post">
			<p>Performance Id: <input type="text" name="perf_id"/></p>
			<p>Member Id: <input type="text" name="m_id"/></p>
			<p>Tickets Given: <input type="text" name="given"/></p>
			<p>Tickets Returned: <input type="text" name="returned"/></p>
			<p>Adult Sold: <input type="text" name="a_sold"/></p>
			<p>Student/Youth Sold: <input type="text" name="sy_sold"/></p>
			<p>Funds Collected: <input type="text" name="funds"/></p>
			<input type="submit" value="Add"/>
		</form>
		<h2>Member Sales Log</h2>
		<form name="sort" method="get">
		<label for="sort"></label>
		<select name="sort">
			<option value="performance">Performance</option>
			<option value="fname">Firt Name</option>
			<option value="lname">Last Name</option>
			<option value="pname">Preferred Name</option>
		</select>
		<input type="submit" value="Sort">
	</form>
		<table>
		<thead>
			<tr>
				<th>Performance</th>
				<th>Member</th>
				<th>Tickets Given</th>
				<th>Tickets Returned</th>
				<th>Tickets Sold</th>
				<th>Funds Collected</th>
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
				case "fname":
					$filter = "ORDER BY Participant.first_name";
					break;
				case "lname":
					$filter = "ORDER BY Participant.last_name";
					break;
				case "pname":
					$filter = "ORDER BY Participant.preferred_name";
					break;
				default:
					break;
			}

			// Define the SQL query to run (replace these values as well)
			$sql = "SELECT * FROM Performance INNER JOIN Member_Ticket_Sales ON Performance.performance_id = Member_Ticket_Sales.performance_id INNER JOIN Member ON Member_Ticket_Sales.member_id = Member.member_id INNER JOIN Participant ON Member.participant_id = Participant.participant_id " . $filter . ";";

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
				echo "<td><a href=\"admin_memberInfo.php?member=" . $row['member_id'] . "\">" . $row['member_id'] . "</a><br>" . $row['first_name'] . " \"" . $row['preferred_name'] . "\" " . $row['last_name'] . "</td>";
				echo "<td>" . $row['tickets_given'] . "</td>";
				echo "<td>" . $row['tickets_returned'] . "</td>";
				echo "<td>Adult: " . $row['adult_sold'] . "<br>Youth/Student: " . $row['student_youth_sold'] . "</td>";
				echo "<td>" . $row['funds_collected'] . "</td>";
				?>
				<td><form action="deleteMemberTicketSales.php" method="post">
					<input type="hidden" name="s_id" value="<?php echo $row['sales_id']?>"/>
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
