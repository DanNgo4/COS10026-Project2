<?php
session_start();
if (isset($_SESSION["id"]) && isset($_SESSION["name"])) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="HR manager queries">
    <meta name="keywords" content="PHP, MySql, HTML">
    <title>EOI Management</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="icon" type="image/x-icon" href="images/soe_logo_transparent_small.png">
</head>
<body>
    <h1>EOI Management</h1>
    <h2>List All EOIs</h2>
    <form action="manage.php" method="GET"> <!-- Add the opening <form> tag -->
        <input type="hidden" name="action" value="list_all"> <!-- Add the hidden input for the action -->
        <input type="submit" value="List All">
</form>
    <hr>
    <h2>List EOIs For A Particular Position</h2>
    <form action="manage.php" method="GET" class="position-form">
        <input type="hidden" name="action" value="list_by_position">
        <label for="Job_Reference">Job Reference:</label>
        <input type="text" name="Job_Reference" id="Job_Reference">
        <input type="submit" value="SUBMIT">
    </form>
    <hr>
    <h2>List EOIs For A Particular Applicant</h2>
    <form action="manage.php" method="GET">
        <input type="hidden" name="action" value="list_by_applicant">
        <label for="First_Name">First Name:</label>
        <input type="text" name="First_Name" id="First_Name">
        <br>
        <label for="Last_name">Last Name:</label>
        <input type="text" name="Last_name" id="Last_Name">
        <br>
        <input type="submit" value="SUBMIT">
    </form>
    <hr>
    <h2>Delete EOIs With A Specified Job Reference Number</h2>
    <form action="manage.php" method="GET">
        <input type="hidden" name="action" value="delete_by_position">
        <label for="Job_Reference_delete">Job Reference:</label>
        <input type="text" name="Job_Reference" id="Job_Reference_delete">
        <input type="submit" value="DELETE">
    </form>
    <hr>
    <h2>Change The Status Of An EOI</h2>
    <form action="manage.php" method="GET">
        <input type="hidden" name="action" value="change_status">
        <label for="eoi_number">EOI Number:</label>
        <input type="text" name="eoi_number" id="eoi_number">
        <br>
        <label for="status">Status:</label>
        <input type="text" name="status" id="status">
        <br>
        <input type="submit" value="CHANGE">
    </form>
    <?php
    // Database connection
    require_once("settings.php");
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    // Check if connection is successful
    if (!$conn) {
        // Display an error message
        echo "<p>Database connection failure</p>";
    }
    // Function to sanitize user input
    function sanitizeInput($input)
    {
        // Perform sanitization here (e.g., trim, remove HTML control characters, etc.)
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    
    // List all EOIs
function listAllEOIs($conn, $list_all_EOIs)
{
    $list_all_EOIs = sanitizeInput($list_all_EOIs);
    $sql = "SELECT * FROM EOI";
    $result = $conn->query($sql);
    // Display the results
    if ($result->num_rows > 0) {
        echo "<h2>All EOIs:</h2>";
        echo "<table>";
        //outcome
        echo "<tr><th>EOInumber</th>
        <th>Job Reference</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>dob</th>
        <th>Gender</th>
        <th>Street Address</th>
        <th>Surburb Town</th>
        <th>State</th>
        <th>Postcode</th>
        <th>Email Address</th>
        <th>Phone Number</th>
        <th>Skill 01</th>
        <th>Skill 02</th>
        <th>Skill 03</th>
        <th>Skill 04</th>
        <th>Skill 05</th>
        <th>Other Skills</th>
        <th>Status</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>" . $row["EOInumber"] . "</td>
            <td>" . $row["Job_Reference"] . "</td>
            <td>" . $row["First_Name"] . "</td>
            <td>" . $row["Last_Name"] . "</td>
            <td>" . $row["dob"] . "</td>
            <td>" . $row["Gender"] . "</td>
            <td>" . $row["Street_Address"] . "</td>
            <td>" . $row["Suburb_Town"] . "</td>
            <td>" . $row["State"] . "</td>
            <td>" . $row["Postcode"] . "</td>
            <td>" . $row["Email_Address"] . "</td>
            <td>" . $row["Phone_Number"] . "</td>
            <td>" . $row["Skill_01"] . "</td>
            <td>" . $row["Skill_02"] . "</td>
            <td>" . $row["Skill_03"] . "</td>
            <td>" . $row["Skill_04"] . "</td>
            <td>" . $row["Skill_05"] . "</td>
            <td>" . $row["OtherSkills"] . "</td>
            <td>" . $row["Status"] . "</td>
            </tr>";
        }
        echo "</table>";
        echo "<p class='success-message'>EOIs listed successfully.</p>";
    } else {
        echo "<p class='error-message'>No EOIs found.</p>";
    }
}
    // List EOIs for a particular position (given a job reference number)
    function listEOIsForPosition($conn, $position)
    {
        $position = sanitizeInput($position);
        $sql = "SELECT * FROM EOI WHERE Job_Reference = '$position'";
        $result = $conn->query($sql);
        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>EOIs for Job Reference: $position</h2>";
            echo "<table>";
            echo "<tr><th>EOInumber</th>
            <th>Job Reference</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Street Address</th>
            <th>Surburb Town</th>
            <th>State</th>
            <th>Postcode</th>
            <th>Email Address</th>
            <th>Phone Number</th>
            <th>Skill 01</th>
            <th>Skill 02</th>
            <th>Skill 03</th>
            <th>Skill 04</th>
            <th>Skill 05</th>
            <th>Other Skills</th>
            <th>Status</th>
            </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
            <td>" . $row["EOInumber"] . "</td>
            <td>" . $row["Job_Reference"] . "</td>
            <td>" . $row["First_Name"] . "</td>
            <td>" . $row["Last_Name"] . "</td>
            <td>" . $row["dob"] . "</td>
            <td>" . $row["Gender"] . "</td>
            <td>" . $row["Street_Address"] . "</td>
            <td>" . $row["Suburb_Town"] . "</td>
            <td>" . $row["State"] . "</td>
            <td>" . $row["Postcode"] . "</td>
            <td>" . $row["Email_Address"] . "</td>
            <td>" . $row["Phone_Number"] . "</td>
            <td>" . $row["Skill_01"] . "</td>
            <td>" . $row["Skill_02"] . "</td>
            <td>" . $row["Skill_03"] . "</td>
            <td>" . $row["Skill_04"] . "</td>
            <td>" . $row["Skill_05"] . "</td>
            <td>" . $row["OtherSkills"] . "</td>
            <td>" . $row["Status"] . "</td>
            </tr>";
            }
            echo "</table>";
            echo "<p class='success-message'>EOIs for position $jobReference listed successfully.</p>";
        } else {
            echo "<p class='error-message'>No EOIs found for position $jobReference.</p>";
        }
    }
    // List EOIs for a particular applicant given their first name, last name, or both
    function listEOIsForApplicant($conn, $firstName, $lastName)
    {
        $firstName = sanitizeInput($firstName);
        $lastName = sanitizeInput($lastName);

        $sql = "SELECT * FROM EOI WHERE First_Name LIKE '%$firstName%' AND Last_Name LIKE '%$lastName%'";
        $result = $conn->query($sql);

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>EOIs for Applicant: $firstName $lastName</h2>";
            echo "<table>";
            echo "<tr><th>EOInumber</th>
            <th>Job Reference</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Street Address</th>
            <th>Surburb Town</th>
            <th>State</th>
            <th>Postcode</th>
            <th>Email Address</th>
            <th>Phone Number</th>
            <th>Skill 01</th>
            <th>Skill 02</th>
            <th>Skill 03</th>
            <th>Skill 04</th>
            <th>Skill 05</th>
            <th>Other Skills</th>
            <th>Status</th>
            </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["EOInumber"] . "</td>
                <td>" . $row["Job_Reference"] . "</td>
                <td>" . $row["First_Name"] . "</td>
                <td>" . $row["Last_Name"] . "</td>
                <td>" . $row["dob"] . "</td>
                <td>" . $row["Gender"] . "</td>
                <td>" . $row["Street_Address"] . "</td>
                <td>" . $row["Suburb_Town"] . "</td>
                <td>" . $row["State"] . "</td>
                <td>" . $row["Postcode"] . "</td>
                <td>" . $row["Email_Address"] . "</td>
                <td>" . $row["Phone_Number"] . "</td>
                <td>" . $row["Skill_01"] . "</td>
                <td>" . $row["Skill_02"] . "</td>
                <td>" . $row["Skill_03"] . "</td>
                <td>" . $row["Skill_04"] . "</td>
                <td>" . $row["Skill_05"] . "</td>
                <td>" . $row["OtherSkills"] . "</td>
                <td>" . $row["Status"] . "</td>
                </tr>";
            }
            echo "</table>";
            echo "<p>EOIs for applicant $firstName $lastName listed successfully.</p>";
        } else {
            echo "<p>No EOIs found for applicant $firstName $lastName.</p>";
        }
    }

    // Delete all EOIs with a specified job reference number
    function deleteEOIsWithJobReference($conn, $jobReference)
    {
        $jobReference = sanitizeInput($jobReference);
        $sql = "DELETE FROM EOI WHERE Job_Reference = '$jobReference'";
        $result = $conn->query($sql);
        if($result) {
            echo "<p class='success-message'>EOIs for position $jobReference deleted successfully.</p>";
        } else {
            echo "<p class='error-message'>Failed to delete EOIs for position $jobReference.</p>";
        }
    }
    // Change the Status of an EOI
    function changeEOIStatus($conn, $EOinumber, $Status)
    {
        $EOInumber = sanitizeInput($EOInumber);
        $Status = sanitizeInput($Status);

        $sql = "UPDATE EOI SET status = '$Status' WHERE EOInumber = $EOInumber";
        $result = $conn->query($sql);

        if ($result) {
            echo "<p class='success-message'>EOI status changed successfully.</p>";
        } else {
            echo "<p class='error-message'>Failed to change EOI status.</p>";
        }
    }
    // Check the requested action and execute the appropriate query
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'list_all':
                $list_all_EOIs = isset($_GET['list_all_EOIs']) ? $_GET['list_all_EOIs'] : 'EOInumber';
                listAllEOIs($conn, $list_all_EOIs);
                break;
            case 'list_by_position':
                $position = isset($GET['Job_Reference']) ? $_GET['Job_Reference'] : '';
                listEOIsByPosition($conn, $position);
                break;
                case 'list_by_applicant':
                    $firstName = isset($_GET['First_Name']) ? $_GET['First_Name'] : '';
                    $lastName = isset($_GET['Last_Name']) ? $_GET['Last_Name'] : '';
                    listEOIsForApplicant($conn, $firstName, $lastName);
                    break;                
                case 'delete_by_position':
                    $jobReference = isset($_GET['Job_Reference']) ? $_GET['Job_Reference'] : '';
                    deleteEOIsByPosition($conn, $jobReference);
                    break;
                case 'change_status':
                    $EOInumber = isset($_GET['EOInumber']) ? $_GET['EOInumer'] : '';
                    $Status = isset($_GET['Status']) ? $_GET['Status'] : '';
                    changeEOIStatus($conn, $EOInumber, $Status);
                    break;
                default:
                    echo "<p class='error-message'>Invalid action.</p>";
                    break;
            }
        }
    
    // Close the database connection
    $conn->close();
?>
<a href = "logout.php"><h1>LOGOUT</h1></a>
</body>
</html>
<?php 
 } else{
     header("Location: phpenhancements.php?error");
     exit();
}
include 'Footer.inc';
 ?>