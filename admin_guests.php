<html>
	<head>
		<title>Guests</title>
	</head>
	<body>
		<h2>Add Guest</h2>
		<form action="addGuest.php" method="post">
			<p>Id: <input type="text" name="p_id"/></p>
			<p>First Name: <input type="text" name="p_fname"/>&emsp;
			Last Name: <input type="text" name="p_lname"/>&emsp;
			Preferred First Name: <input type="text" name="p_pname"/></p>
			<p>Birthdate: <input type="date" name="p_birthdate"/></p>
			<p>Street: <input type="text" name="p_street"/>&emsp;
			City: <input type="text" name="p_city"/>&emsp;
			State: <input type="text" name="p_state"/>&emsp;
			Zip: <input type="text" name="p_zip"/></p>
			<p>Home Phone: <input type="text" name="p_hphone"/>&emsp;
			Cell Phone: <input type="text" name="p_cphone"/>&emsp;
			Work Phone: <input type="text" name="p_wphone"/></p>
			<p>Email: <input type="text" name="p_email"/></p>
			<p>Spouse First Name: <input type="text" name="p_sfname"/>&emsp;
			Spouse Last Name: <input type="text" name="p_slname"/>&emsp;
			Spouse Preferred First: <input type="text" name="p_spname"/></p>
			<p>On Facebook? <input type="checkbox" name="p_fb"/>Yes</p>
			<p>Voice Part: 
				<select name="p_vp">
				<option value="Tenor">Tenor</option>
				<option value="Lead">Lead</option>
				<option value="Baritone">Baritone</option>
				<option value="Bass">Bass</option>
				</select></p>
			<input type="submit" value="Add"/>
		</form>
		<h2>Current Guests</h2>
		<table>
		<thead>
			<tr>
				<td>Participant Id</td>
				<td>Last Name</td>
				<td>First Name</td>
				<td>Preferred Name</td>
				<td>Voice Part</td>
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
			$sql = "SELECT * FROM Participant WHERE NOT EXISTS (SELECT * FROM Member WHERE Member.participant_id = Participant.participant_id);";

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
				echo "<td><a href=\"admin_guestInfo.php?guest=" . $row['participant_id'] . "\">" . $row['participant_id'] . "</a></td>";
				echo "<td>" . $row['last_name'] . "</td>";
				echo "<td>" . $row['first_name'] . "</td>";
				echo "<td>" . $row['preferred_name'] . "</td>";
				echo "<td>" . $row['voice_part'] . "</td>";
				?>
				<td><form action="deleteGuest.php" method="post">
					<input type="hidden" name="p_id" value="<?php echo $row['participant_id']?>"/>
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
	</body>
</html>
