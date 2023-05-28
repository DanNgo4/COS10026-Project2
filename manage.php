<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
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

        <h2>List EOIs</h2>
        <form action="manage.php" method="GET">
    <input type="hidden" name="action" value="list_all">
    <label for="sort_field">Sort By:</label>
    <select name="sort_field" id="sort_field">
        <option value="EOInumber">EOI Number</option>
        <option value="job_reference">Job Reference</option>
        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="status">Status</option>
    </select>
    <br>
    <input type="submit" value="List EOIs">
</form>
       <hr> <!--This is horizon-->
        <h2>List EOIs For A Particular Position</h2>
<form action="manage.php" method="GET" class="position-form">
    <input type="hidden" name="action" value="list_by_position">
        <label for="job_reference">Job Reference:</label>
        <input type="text" name="job_reference" id="job_reference">
        <input type="submit" value="List EOIs for Position">
</form>
         <hr> <!--This is horizon-->
        <h2>List EOIs For A Particular Applicant</h2>
        <form action="manage.php" method="GET">
            <input type="hidden" name="action" value="list_by_applicant">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name">
            <br>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name">
            <br>
            <input type="submit" value="List EOIs for Applicant">
        </form>
       <hr> <!--This is horizon-->
        <h2>Delete EOIs With A Specified Job Reference Number</h2>
        <form action="manage.php" method="GET">
            <input type="hidden" name="action" value="delete_by_position">
            <label for="job_reference_delete">Job Reference:</label>
            <input type="text" name="job_reference" id="job_reference_delete">
            <input type="submit" value="Delete EOIs">
        </form>
       <hr> <!--This is horizon-->
        <h2>Change The Status Of An EOI</h2>
        <form action="manage.php" method="GET">
            <input type="hidden" name="action" value="change_status">
            <label for="eoi_number">EOI Number:</label>
            <input type="text" name="eoi_number" id="eoi_number">
            <br>
            <label for="status">Status:</label>
            <input type="text" name="status" id="status">
            <br>
            <input type="submit" value="Change Status">
        </form>
<?php
    include ("Header.inc");
    // Database connection
    require_once("settings.php");
    $conn = @mysqli_connect ( $host,$user, $pwd, $sql_db);
    //Checks if connection is successful
    if (!$conn) {
        //displays an error message 
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
    function listAllEOIs($conn, $sortField)
    {
        $sortField = sanitizeInput($sortField);
        $sql = "SELECT * FROM eoi ORDER BY $sortField";
        $result = $conn->query($sql);

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>All EOIs:</h2>";
            echo "<table>";
            echo "<tr><th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>Status</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["EOInumber"] . "</td><td>" . $row["job_reference"] . "</td><td>" . $row["first_name"] . "</td><td>" . $row["last_name"] . "</td><td>" . $row["status"] . "</td></tr>";
            }
            echo "</table>";
            echo "<p class='success-message'>EOIs listed successfully.</p>";
        } else {
            echo "<p class='error-message'>No EOIs found.</p>";
        }
    }
    // List EOIs for a particular position (given a job reference number)
    function listEOIsForPosition($conn, $jobReference)
    {
        $jobReference = sanitizeInput($jobReference);
        $sql = "SELECT * FROM eoi WHERE job_reference = '$jobReference'";
        $result = $conn->query($sql);

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>EOIs for Job Reference: $jobReference</h2>";
            echo "<table>";
            echo "<tr><th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>Status</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["EOInumber"] . "</td><td>" . $row["job_reference"] . "</td><td>" . $row["first_name"] . "</td><td>" . $row["last_name"] . "</td><td>" . $row["status"] . "</td></tr>";
            }
            echo "</table>";
            echo "<p class='success-message'> EOIs for position $position listed successfully. </p>";
        } else { 
            echo"<p class='error-message'> No EOIs found for position $position.</p>";
        }
        }

    // List EOIs for a particular applicant given their first name, last name, or both
    function listEOIsForApplicant($conn, $firstName, $lastName)
    {
        $firstName = sanitizeInput($firstName);
        $lastName = sanitizeInput($lastName);

        $sql = "SELECT * FROM eoi WHERE first_name LIKE '%$firstName%' AND last_name LIKE '%$lastName%'";
        $result = $conn->query($sql);

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>EOIs for Applicant: $firstName $lastName</h2>";
            echo "<table>";
            echo "<tr><th>EOInumber</th><th>Job Reference</th><th>First Name</th><th>Last Name</th><th>Status</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["EOInumber"] . "</td><td>" . $row["job_reference"] . "</td><td>" . $row["first_name"] . "</td><td>" . $row["last_name"] . "</td><td>" . $row["status"] . "</td></tr>";
            }
            echo "</table>";
            echo "<p>EOIs for applicant $applicant listed successfully.</p>";
        } else {
            echo "<p>No EOIs found for applicant $applicant.</p>";
        }
    }

    // Delete all EOIs with a specified job reference number
    function deleteEOIsWithJobReference($conn, $jobReference)
    {
        $jobReference = sanitizeInput($jobReference);
        $sql = "DELETE FROM eoi WHERE job_reference = '$jobReference'";
        $result = $conn->query($sql);
        if($result) {
            echo "<p class='success-message'>EOIs for position $position deleted successfully.</p>";
        } else {
            echo "<p class='error-message'>Failed to delete EOIs for position $position.</p>";
        }
        }
    // Change the Status of an EOI
    function changeEOIStatus($conn, $eoiNumber, $status)
    {
        $eoiNumber = sanitizeInput($eoiNumber);
        $status = sanitizeInput($status);

        $sql = "UPDATE eoi SET status = '$status' WHERE EOInumber = $eoiNumber";
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
                $sortField = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'EOInumber';
                listAllEOIs($conn, $sortField);
                break;
            case 'list_by_position':
                $position = isset($GET['position']) ? $_GET['position'] : '';
                listEOIsByPosition($conn, $position);
                break;
                case 'list_by_applicant':
                    $applicant = isset($_GET['applicant']) ? $_GET['applicant'] : '';
                    listEOIsByApplicant($conn, $applicant);
                    break;
                case 'delete_by_position':
                    $position = isset($_GET['position']) ? $_GET['position'] : '';
                    deleteEOIsByPosition($conn, $position);
                    break;
                case 'change_status':
                    $eoiNumber = isset($_GET['eoi_number']) ? $_GET['eoi_number'] : '';
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    changeEOIStatus($conn, $eoiNumber, $status);
                    break;
                default:
                    echo "<p class='error-message'>Invalid action.</p>";
                    break;
            }
        }
    // Close the database connection
    $conn->close();

    include 'Footer.inc'
?>
</body>
</html>
<?php
}
else {
    header("Location: phpenhancements.php");
    exit();
}
?>