<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_varneega";			// change "cwl" to your own CWL
$config["dbpassword"] = "a84819218";	// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = NULL;	// login credentials are used in connectToDB()

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP
?>

<html>

<head>
	<title>VolleyVision Pro</title>
	<style>
        body {
            background-color: #ffffff;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: #1e6091;
			text-align: center;
        }

        h2 {
            color: #1e6091;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #1e6091;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        input[type="text"] {
            padding: 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #1e6091;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #1e6091;
            color: #ffffff;
        }

    </style>
</head>

<body>
	<h1> VolleyVision Pro </h1>
	<h1> <img class="volleyball-image" src="https://t4.ftcdn.net/jpg/04/18/08/23/360_F_418082327_vJjeEA2NMyk7Eg8JpdlJsC2LVMBwj7CV.jpg"
              alt="Volleyball Image" width="300" height="300"> </h1>
    <hr />

    <h2>Please register as a member below!</h2>
        <form method="POST" action="volleyball.php">
             <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

             Name: <input type="text" name="name"> <br /><br />
             Phone Number: <input type="number" name="phoneNum"> <br /><br />
             Address: <input type="text" name="address"> <br /><br />
             City: <input type="text" name="city"> <br /><br />
             Birthday (yyyy-mm-dd): <input type="text" name="birthday"> <br /><br />
             Age: <input type="number" name="age"> <br /><br />

             <input type="submit" value="Register" name="insertRegisterMember"></p>
        </form>

        <?php
            if (isset($_POST['insertQueryRequest'])) {
                    handlePOSTRequest();
            }
        ?>

        <hr />

        <h2>If you are a coach, please use your membership ID generated above to register!</h2>
        <form method="POST" action="volleyball.php">
        <input type="hidden" id="insertQueryRequestCoach" name="insertQueryRequestCoach">

            Member ID: <input type="number" name="memberID"> <br /><br />
            Number of Years Coached: <input type="number" name="yearsCoached"> <br /><br />

        <input type="submit" value="Register" name="insertRegisterCoach"></p>
        </form>

         <?php
               if (isset($_POST['insertQueryRequestCoach'])){
                   handlePOSTRequest();
               }
           ?>
         <hr />

        <h2>Update Player Stats</h2>

        <form method="POST" action="volleyball.php">
            <input type="hidden" id="updateStatsRequest" name="updateStatsRequest">

            What do you want to update?:
            <select name="selectedColumn">
                <option value="SIN">SIN</option>
                <option value="SIN">Jersey Number</option>
                <option value="SIN">Position</option>
                <option value="MatchesPlayed">Matches Played</option>
                <option value="GamesWon">Games Won</option>
                <option value="NumberofPoints">Number of Points</option>
            </select>
            <br /><br />

            Player ID: <input type="number" name="playerID"> <br /><br />
            New Value: <input type="number" name="newValue"> <br /><br />

            <input type="submit" value="Update" name="updateStats"></p>
        </form>

        <?php
            if (isset($_POST['updateStats'])){
                handlePOSTRequest();
            }
        ?>

        <hr />

        <h2>Remove a player on your team here!</h2>

            <form method="POST" action="volleyball.php">
                <input type="hidden" id="deletePlayerRequest" name="deletePlayerRequest">
                Your Team ID: <input type="number" name="teamID"> <br /><br />
                Player ID: <input type="number" name="playerID"> <br /><br />

                <input type="submit" value="Remove" name="removePlayer"></p>
            </form>

            <?php
                if (isset($_POST['removePlayer'])){
                    handlePOSTRequest();
                }
            ?>

            <hr />

	<!-- JOIN: Members & Players table, search by PlayerID -->
	<h2> Checkout some player stats! </h2>
	<form method="GET" action="volleyball.php">
		<input type="hidden" id="joinRequest" name="joinRequest">
		Player ID: <input type="text" name="playerID"> <br /><br />

		<input type="submit" value="Check" name="join"></p>
	</form>

	<?php
        if (isset($_GET['join'])){
            handleGETRequest();
        }
    ?>

	<hr />

	<!-- NESTED AGG W/ GROUP BY: Average sponsorship amount per tournament -->
	<h2> Tournament Showcase: Average Sponsorship Insights </h2>
	<form method="GET" action="volleyball.php">
		<input type="hidden" id="avgSponsorshipRequest" name="avgSponsorshipRequest">
		<input type="submit" name="avgSponsorship"></p>
	</form>

    <?php
        if (isset($_GET['avgSponsorship'])){
            handleGETRequest();
        }
    ?>

	<hr />


	<!-- PROJECTION -->
	<h2>Search Here!</h2>
	<form method="GET" action="volleyball.php">
		<label for="selectedTable">Select a table:</label>
		<select id="selectedTable" name="selectedTable">
			<?php
			
			// Connect to the db on page reload
			connectToDB();
			$tableNames = fetchTableNames();

			// Display the fetched table names
			foreach ($tableNames as $tableName) {
				echo "<option value=\"$tableName\">$tableName</option>";
			}
			?>
		</select>
		Attributes to search for (comma separated): <input type="text" name="attributeSearch"> <br /><br />
		<br><br>
		<input type="submit" name="displayTableSubmit" value="Search">
	</form>

    <?php
            if (isset($_GET['displayTableSubmit'])){
                handleGETRequest();
            }
        ?>

	<hr />

    <!-- SELECTION: Members from Vancouver-->
    <h2>Member DoB</h2>
    <p>DoB for members residing in</p>
    <form method="GET" action="volleyball.php">
        <input type="hidden" id="selectionRequest" name="selectionRequest">
        <input type="text" name="city" placeholder="city"> <br /><br />
        <input type="submit" name="selection"></p>
    </form>

    <?php
            if (isset($_GET['selection'])){
                handleGETRequest();
            }
        ?>
    <hr />

    <!-- AGGREGATION W GROUP BY: -->
    <h2>Team Size</h2>
    <!-- <p>Find the number of players in each team in our database</p> -->
    <form method="GET" action="volleyball.php">
        <input type="hidden" id="AggGBRequest" name="AggGBRequest">
        <input type="submit" name="AggGB"></p>
    </form>
    <?php
            if (isset($_GET['AggGB'])){
                handleGETRequest();
            }
        ?>
    <hr />
    <!-- AGGREGATION W HAVING: -->
    <h2> Our Valued Sponsors </h2>
    <p>List of organizations with an average sponsorship amount of $200K+ for tournaments</p>
    <form method="GET" action="volleyball.php">
        <input type="hidden" id="AggHRequest" name="AggHRequest">
        <input type="submit" name="AggH"></p>
    </form>

    <?php
            if (isset($_GET['AggH'])){
                handleGETRequest();
            }
        ?>

    <hr />

    <h2>Click to check which tournament every club participates</h2>
             <form method="GET" action="volleyball.php">
             <input type="hidden" id="displayTournamentRequest" name="displayTournamentRequest">
             <input type="submit" name="displayTournament"></p>
             </form>

            <?php
                if (isset($_GET['displayTournamentRequest'])){
                    handleGETRequest();
                }
            ?>

   <hr />

	<?php
	// The following code will be parsed as PHP

	function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function executePlainSQL($cmdstr)
	{ //takes a plain (no bound variables) SQL command and executes it
		//echo "<br>running ".$cmdstr."<br>";
		global $db_conn, $success;

		$statement = oci_parse($db_conn, $cmdstr);
		//There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
			echo htmlentities($e['message']);
			$success = False;
		}

		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = oci_error($statement); // For oci_execute errors pass the statementhandle
			echo htmlentities($e['message']);
			$success = False;
		}

		return $statement;
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				//echo $val;
				//echo "<br>".$bind."<br>";
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}
	}

	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		// $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}

	function fetchTableNames()
	{
		global $db_conn;
	
		$query = "SELECT table_name FROM user_tables";
		$result = executePlainSQL($query);
	
		$tableNames = array();
		while ($row = oci_fetch_assoc($result)) {
			$tableNames[] = $row['TABLE_NAME'];
		}
	
		return $tableNames;
	}

	function handleJoinRequest()
	{

		global $db_conn;
        connectToDB();
		$playerID = $_GET['playerID'];

		// Validate that the input is a numeric value
		if (!is_numeric($playerID)) {
			echo "Error: PlayerID must be a numeric value.";
			return;
		}

		$sql = "SELECT P.ID, P.SIN, P.JerseyNum, P.Position, P.TeamID, PS.StatID, PS.MatchesPlayed, PS.GamesWon, PS.NumofPoints
				FROM Players P, PlayerStats PS
				WHERE P.ID = PS.PlayerID
					AND P.ID = '" . $_GET['playerID'] . "'";

		$result = executePlainSQL($sql);

		echo "<br>Retrieved data from the join query:<br>";
		echo "<table border='1'>";
		echo "<tr><th>ID</th><th>SIN</th><th>JerseyNum</th><th>Position</th><th>TeamID</th><th>StatID</th><th>MatchesPlayed</th><th>GamesWon</th><th>NumofPoints</th></tr>";
		
		while ($row = OCI_fetch_array($result, OCI_NUM)) {

			if ($row === false) {
				$e = oci_error($result);
				echo "Error fetching data: " . htmlentities($e['message']);
				break;
			}

			echo "<tr>";
			echo "<td>" . $row[0] . "</td>";
			echo "<td>" . $row[1] . "</td>";
			echo "<td>" . $row[2] . "</td>";
			echo "<td>" . $row[3] . "</td>";
			echo "<td>" . $row[4] . "</td>";
			echo "<td>" . $row[5] . "</td>";
			echo "<td>" . $row[6] . "</td>";
			echo "<td>" . $row[7] . "</td>";
			echo "<td>" . $row[8] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	function handleAvgSponsorshipRequest() {
		global $db_conn;
	
		$sql = "SELECT t.Name AS TournamentName, AVG(ts.AmountSponsored) AS AvgSponsorship FROM 
					(SELECT s.TournamentID, o.AmountSponsored 
					FROM Sponsors s 
					JOIN Organization o 
					ON s.OrganizationID = o.OrganizationID) ts JOIN Tournament t ON ts.TournamentID = t.TournamentID GROUP BY t.Name, t.TournamentID ORDER BY t.TournamentID";
	
	
		$result = executePlainSQL($sql);
		if (!$result) {
			$e = OCI_Error($db_conn);
			echo "Error executing query: " . htmlentities($e['message']);
		}
		else {
		echo "<br>Tournaments & Average Sponsorships:<br>";
		echo "<table border='1'>";
		echo "<tr><th>Tournament</th><th>Average Sponsorship Amount Received</th></tr>";

		while ($row = OCI_fetch_array($result, OCI_NUM)) {
			echo "<tr>";
			echo "<td>" . $row[0] . "</td>";
			echo "<td>$" . $row[1] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		}
	}

	function handleSearchRequest() {
		global $db_conn;

		// Retrieve user input
		$tableName = $_GET['selectedTable'];
		$attributeSearchList = $_GET['attributeSearch'];

		// Validate input format (numerical values separated by commas)
		if (!preg_match('/^(\d+,)*\d+$/', $attributeSearchList)) {
			echo "Error: Invalid input format. Please enter numerical values separated by commas.";
			return;
		}

		$columnNumbers = explode(',', $attributeSearchList);
	
		// Query to get all column names of the selected table
		$sqlTemplate = "SELECT column_name FROM USER_TAB_COLUMNS WHERE table_name = '$tableName'";
		$result = executePlainSQL($sqlTemplate);
	
		// Declare an empty array to store db fetched column names
		$allColumnNames = array();
		while ($row = OCI_fetch_array($result, OCI_BOTH)) {
			$allColumnNames[] = $row[0];
		}

		// Error handling for out of bounds column
		foreach($columnNumbers as $columnNumber) {
			if ($columnNumber > (count($allColumnNames) - 1)) {
				echo "Error: Invalid input format. Please enter numerical values separated by commas.";
				return;
			}
		}

		// Filtered column names based on user input
		$filteredColumnNames = array();
		foreach ($columnNumbers as $columnNumber) {
			if (isset($allColumnNames[$columnNumber])) {
				$filteredColumnNames[] = $allColumnNames[$columnNumber];
			}
		}
	
		// Construct the SQL query with the selected columns
		$selectedColumns = implode(', ', $filteredColumnNames);
		$sql = "SELECT $selectedColumns FROM $tableName";
	
		// Execute the query
		$result = executePlainSQL($sql);
	
		echo "<br>Search results from $tableName with Selected Columns:<br>";
		echo "<table border='1'>";
		echo "<tr>";


		// Display column headers
		foreach ($filteredColumnNames as $filteredColumnName) {
			echo "<th>$filteredColumnName</th>";
		}
		echo "</tr>";
	

		// Display query results
		while ($row = OCI_fetch_array($result, OCI_BOTH)) {
			echo "<tr>";
			for ($i = 0; $i < sizeof($filteredColumnNames); $i++) {
				if (isset($row[$i])) {
					echo "<td>" . $row[$i] . "</td>";
				}
				else {
					echo "<td>Not available</td>";
				}
			}
			echo "</tr>";
		}
	
		echo "</table>";
	}

    function handleSelectionRequest()
    {
        global $db_conn;

        $city = $_GET['city'];
        if (!preg_match('/^[a-zA-Z]+$/', $city)) {
            echo "ERROR: Invalid input format. Please enter a valid city name.";
            return;
        }

        $sql = "SELECT Name, BirthDate
        FROM Members
        WHERE UPPER(City) = UPPER('" . $city . "')";
        $result = executePlainSQL($sql);

        echo "<br>Retrieved data from SELECTION query:<br>";
        if(oci_fetch_assoc($result)===false) {
            echo "No results for the specified city";
            return;
        }
        echo "<table border='1'>";
        echo "<tr>
              <th>Name</th>
              <th>BirthDate</th>
              </tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>";
            echo "<td>" . $row[0] . "</td>";
            echo "<td>" . $row[1] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function handleAggregation_GBRequest()
    {
        global $db_conn;

        $sql = "SELECT TeamID, COUNT(*) AS NumPlayers
                FROM Players
                GROUP BY TeamID";
        $result = executePlainSQL($sql);

        echo "<br>Retrieved data from AGGREGATION (GB) query:<br>";
        echo "<table border='1'>";
        echo "<tr>
              <th>TeamID</th>
              <th>NumPlayers</th>
              </tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>";
            echo "<td>" . $row[0] . "</td>";
            echo "<td>" . $row[1] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    function handleAggregation_HRequest()
    {
        global $db_conn;

        $sql = "SELECT o.OrganizationID, o.Name, AVG(s.AmountSponsored) AS AverageSponsorship
                FROM Organization o
                JOIN Sponsors s ON o.OrganizationID = s.OrganizationID
                GROUP BY o.OrganizationID, o.Name
                HAVING AVG(s.AmountSponsored) > 200000";
        $result = executePlainSQL($sql);

        echo "<br>Retrieved data from AGGREGATION (H) query:<br>";
        echo "<table border='1'>";
        echo "<tr>
              <th>OrganizationID</th>
              <th>Name</th>
              <th>AverageSponsorship</th>
              </tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>";
            echo "<td>" . $row[0] . "</td>";
            echo "<td>" . $row[1] . "</td>";
            echo "<td>" . $row[2] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

	function handleUpdateRequest()
             {

                global $db_conn;
                $selected_column = $_POST['selectedColumn'];
                $player_id = (int)$_POST['playerID'];
                $new_value = (int)$_POST['newValue'];

                 if ($selected_column === 'SIN' || $selected_column === 'JerseyNum' || $selected_column === 'Position') {
                    $query = "UPDATE Players
                              SET $selected_column = :new_value
                              WHERE ID = :player_id";
                 } else {
                    $query = "UPDATE PlayerStats
                              SET $selected_column = :new_value
                              WHERE PlayerID = :player_id";
                 }

                $statement = oci_parse($db_conn, $query);

                oci_bind_by_name($statement, ":player_id", $player_id);
                oci_bind_by_name($statement, ":new_value", $new_value);

                $result = oci_execute($statement, OCI_DEFAULT);

                if ($result) {
                    oci_commit($db_conn);
                    echo "<p>Stats has been updated successfully!</p>";
                } else {
                    echo "<p>Error updating stats!</p>";
                }

                oci_free_statement($statement);

             }

            function handleDeleteRequest()
                     {
                        global $db_conn;
                        $team_id = (int)$_POST['teamID'];
                        $player_id = (int)$_POST['playerID'];

                        if(empty($team_id) || empty($player_id)){
                            echo "<p>All fields must be filled!</p>";
                            return;
                        }

                        $query = "DELETE FROM Players
                                  WHERE ID = :player_id
                                  AND TeamID = :team_id";

                        $statement = oci_parse($db_conn, $query);

                        oci_bind_by_name($statement, ":player_id", $player_id);
                        oci_bind_by_name($statement, ":team_id", $team_id);

                        $result = oci_execute($statement, OCI_DEFAULT);

                        if ($result) {
                            oci_commit($db_conn);
                            echo "<p>Player deleted successfully!</p>";
                        } else {
                            echo "<p>Error deleting player!</p>";
                        }

                        oci_free_statement($statement);

                     }

             function handleInsertRequest()
             {
                global $db_conn;

                $name = $_POST['name'];
                $phoneNum = (int)$_POST['phoneNum'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $birthday = $_POST['birthday'];
                $age = (int)$_POST['age'];

                if(empty($name) || empty($phoneNum) || empty($address) || empty($city) || empty($birthday) || empty($age)){
                    echo "<p>All fields must be filled!</p>";
                    return;
                }

                // Validation method below referred to StackOverflow https://stackoverflow.com/questions/17577584/php-check-user-input-date
                $dateObj = DateTime::createFromFormat('Y-m-d', $birthday);

                if (!$dateObj || $dateObj->format('Y-m-d') !== $birthday) {
                    echo "<p>Invalid date format! Please use yyyy-mm-dd. For example, 1990-05-10 for May 10th, 1990.</p>";
                    return;
                }

                $sqlMember = "INSERT INTO Members(Name, PhoneNum, Address, City, Birthdate, Age)
                              VALUES (:name, :phoneNum, :address, :city, TO_DATE(:birthday, 'YYYY-MM-DD'), :age)
                              RETURNING ID INTO :newID";

                $statement = oci_parse($db_conn, $sqlMember);

                oci_bind_by_name($statement, ":name", $name);
                oci_bind_by_name($statement, ":phoneNum", $phoneNum);
                oci_bind_by_name($statement, ":address", $address);
                oci_bind_by_name($statement, ":city", $city);
                oci_bind_by_name($statement, ":birthday", $birthday);
                oci_bind_by_name($statement, ":age", $age);
                oci_bind_by_name($statement, ':newID', $newID, 10);

                $result = oci_execute($statement, OCI_DEFAULT);

                if ($result) {
                    oci_commit($db_conn);
                    echo "<p>You have registered successfully! Your Member ID is: $newID</p>";
                } else {
                    $e = oci_error($statement);
                    echo "<p>Error: " . htmlentities($e['message']) . "</p>";
                }

                oci_free_statement($statement);
             }

             function handleCoachInsertRequest()
             {
                     global $db_conn;

                  $id = (int)$_POST['memberID'];
                  $yearsCoached = (int)$_POST['yearsCoached'];

                  if(empty($id) || empty($yearsCoached)){
                    echo "<p>All fields must be filled!</p>";
                    return;
                  }

                  $check = "SELECT 1 FROM Members WHERE ID = :id";
                  $checkStatement = oci_parse($db_conn, $check);
                  oci_bind_by_name($checkStatement, ":id", $id);
                  oci_execute($checkStatement);

                  if (!oci_fetch($checkStatement)) {
                      echo "<p>The ID does not exist, double check your id! Make sure you are a member first!</p>";
                      oci_free_statement($checkStatement);
                      return;
                  }
                  oci_free_statement($checkStatement);

                  $sqlCoach = "INSERT INTO Coaches(ID, YearsCoached)
                                VALUES (:id, :yearsCoached)";

                  $statement = oci_parse($db_conn, $sqlCoach);

                  oci_bind_by_name($statement, ":id", $id);
                  oci_bind_by_name($statement, ":yearsCoached", $yearsCoached);

                  $result = oci_execute($statement, OCI_DEFAULT);

                  if ($result) {
                      oci_commit($db_conn);
                      echo "<p>You have registered successfully!</p>";
                  } else {
                      echo "<p>Error, register unsuccessful!</p>";
                  }

                  oci_free_statement($statement);
             }

           //Referred to Starter code
            function printDivisionResult($result)
            {
                echo "<table>";
                echo "<tr><th>Tournament Name</th></tr>";

                while ($row = oci_fetch_assoc($result)) {
                        echo "<tr><td>" . $row["TOURNAMENTNAME"] . "</td></tr>";
                }
                if (!oci_num_rows($result)) {
                    echo "<tr><td>No data!</td></tr>";
                }
                echo "</table>";
            }

             function handleDivisionRequest()
             {
                     global $db_conn;

             $query = "SELECT T1.Name AS TournamentName
                       FROM Tournament T1
                       WHERE NOT EXISTS (
                           SELECT C.ClubID
                           FROM Club C
                           WHERE NOT EXISTS (
                               SELECT P.TeamID
                               FROM Participate P
                               WHERE P.TournamentID = T1.TournamentID
                               AND P.TeamID = C.ClubID
                           )
                       )";
            $result = executePlainSQL($query);
            printDivisionResult($result);
             }

    	// HANDLE ALL POST ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest(){
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateStatsRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('insertQueryRequestCoach', $_POST)) {
                    handleCoachInsertRequest();
                } else if (array_key_exists('deletePlayerRequest', $_POST)) {
                    handleDeleteRequest();
                }
                disconnectFromDB();
            }
        }

	// HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handleGETRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('displayTournament', $_GET)) {
				handleDivisionRequest();
			} elseif (array_key_exists('join', $_GET)) {
				handleJoinRequest();
			} elseif (array_key_exists('avgSponsorship', $_GET)) {
				handleAvgSponsorshipRequest();
			} elseif (array_key_exists('displayTableSubmit', $_GET)) {
				handleSearchRequest();
			} elseif (array_key_exists('selection', $_GET)) {
                handleSelectionRequest();
            } elseif (array_key_exists('AggGB', $_GET)) {
                handleAggregation_GBRequest();
            } elseif (array_key_exists('AggH', $_GET)) {
                handleAggregation_HRequest();
            }

			disconnectFromDB();
		}
	}

	// End PHP parsing and send the rest of the HTML content
	?>
</body>

</html>
