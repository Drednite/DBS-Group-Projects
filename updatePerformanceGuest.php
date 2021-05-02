<html>
<title>Admin: Update Performance Guest</title>
<link rel="stylesheet" href="style.css">
	<body>
		<h2>Current Record</h2>
		<table>
		<thead>
			<tr>
				<td>Guest Id</td>
				<td>Group Name</td>
				<td>Members</td>
			</tr>
		</thead>
		<tbody>
			<tr>
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

				$guest_id = $_POST['guest_id'];
				//echo $guest_id;
				$sql = "SELECT * FROM Guest_Performer WHERE Guest_Performer.guest_id = $1;";
				$sql2 = "SELECT * FROM Guest_Member WHERE Guest_Member.guest_id = $1;";
				
				// Query
				$result = pg_prepare($dbhost, "query1", $sql);
				$result2 = pg_prepare($dbhost, "query2", $sql2);
				$result = pg_execute($dbhost, "query1", array($guest_id));
				$result2 = pg_execute($dbhost, "query2", array($guest_id));
				//echo "1: " . $result . "<br>2: " . $result2;
				// If the $result variable is not defined, there was an error in the query
				if (!$result || !$result2)
				{
					die("Error in query: ".pg_last_error());
				}
				
				while($row = pg_fetch_array($result))
				{
					echo "<td>" . $row['guest_id'] . "</td>";
					echo "<td>" . $row['group_name'] . "</td>";
					echo "<td>";
					// Iterate through each row of the result 
					while ($row2 = pg_fetch_array($result2))
					{
						echo $row2['first_name'] . " \"" . $row2['preferred_name'] . "\" " . $row2['last_name'];
						?>
						<form action="deletePerformanceGuestMember.php" method="post">
						<input type="hidden" name="gm_id" value="<?php echo $row2['guest_member_id']?>"/>
						<input type="hidden" name="guest_id" value="<?php echo $guest_id ?>"/>
						<input type="submit" style="color:white;background-color:red" value="Delete Member"/>
						</form>
						<?php
						echo "<br>";
					}
					echo "</td>";
				}
				// Free the result from memory
				pg_free_result($result);
				pg_free_result($result2);

				// Close the database connection
				pg_close($dbhost);
				?>
			</tr>
		</tbody>
		</table>
		<form action="admin_performanceGuests.php">
			<input type="submit" value="Done"/>
		</form>
		<h2>Add Member</h2>
		<form action="addPerformanceGuestMember.php" method="post">
			<input type="hidden" name="guest_id" value="<?php echo $guest_id ?>"/>
			<p>First Name: <input type="text" name="first_name"/></p>
			<p>Last Name: <input type="text" name="last_name"/></p>
			<p>Preferred Name: <input type="text" name="preferred_name"/></p>
			<input type="submit" value="Add"/>
		</form>
	</body>
</html>
