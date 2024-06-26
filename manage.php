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
        <label for="Last_Name">Last Name:</label>
        <input type="text" name="Last_Name" id="Last_Name">
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
        exit();
    }
    function sanitizeInput($input) {
        $input = trim($input); // Remove leading/trailing whitespace
        $input = stripslashes($input); // Remove backslashes
        $input = htmlspecialchars($input); // Convert special characters to HTML entities
        return $input;
    }
    // Perform the requested query
    if (isset($_GET['action'])) {
        $query =sanitizeInput($_GET['action']);

        switch ($query) {
            case "list_all":
                $sql = "SELECT * FROM EOI";
                break;
            case "list_by_position":
                if (isset($_GET["Job_Reference"])) {
                    $jobReference = sanitizeInput($_GET["Job_Reference"]);
                    $sql = "SELECT * FROM EOI WHERE Job_Reference = '$jobReference'";
                } else {
                    // Redirect to an error page if the job reference is not provided
                    header("Location: error.php");
                    exit();
                }
                break;
            case "list_by_applicant":
                if (isset($_GET["First_Name"]) && isset($_GET["Last_Name"])) {
                    $firstName = sanitizeInput($_GET["First_Name"]);
                    $lastName = sanitizeInput($_GET["Last_Name"]);
                    $sql = "SELECT * FROM EOI WHERE First_Name = '$firstName' AND Last_Name = '$lastName'";
                } else {
                    // Redirect to an error page if the first name or last name is not provided
                    header("Location: error.php");
                    exit();
                }
                break;
            case "delete_by_position":
                if (isset($_GET["Job_Reference"])) {
                    $jobReference = sanitizeInput($_GET["Job_Reference"]);
                    $sql = "DELETE FROM EOI WHERE Job_Reference = '$jobReference'";
                    // Perform the delete operation
                    if (mysqli_query($conn, $sql)) {
                        echo "EOIs with job reference '$jobReference' have been deleted successfully.";
                    // Reset the EOInumber
                     $resetSql = "ALTER TABLE EOI AUTO_INCREMENT = 1";
                     mysqli_query($conn, $resetSql);
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                    exit();
                } else {
                    // Redirect to an error page if the job reference is not provided
                    header("Location: error.php");
                    exit();
                }
                break;
            case "change_status":
                if (isset($_GET["eoi_number"]) && isset($_GET["status"])) {
                    $eoiNumber = sanitizeInput($_GET["eoi_number"]);
                    $status = sanitizeInput($_GET["status"]);
                    $sql = "UPDATE EOI SET Status = '$status' WHERE EOInumber = $eoiNumber";
                    // Perform the status change operation
                    if (mysqli_query($conn, $sql)) {
                        echo "EOI with EOInumber '$eoiNumber' has been updated successfully.";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                    exit();
                } else {
                    // Redirect to an error page if the EOInumber or status is not provided
                    header("Location: error.php");
                    exit();
                }
                break;
            default:
                // Redirect to an error page if an invalid query is requested
                header("Location: error.php");
                exit();
        }

        // Execute the query
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Display the results in a table format
            echo "<table>
                    <tr>
                    <th>EOInumber</th>
                    <th>Job Reference</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Street Address</th>
                    <th>Suburb Town</th>
                    <th>State</th>
                    <th>Postcode</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Skill 01</th>
                    <th>Skill 02</th>
                    <th>Skill 03</th>
                    <th>Status</th>    
                    </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row["EOInumber"] . "</td>
                <td>" . $row["Job_Reference"] . "</td>
                <td>" . $row["First_Name"] . "</td>
                <td>" . $row["Last_Name"] . "</td>
                <td>" . $row["DOB"] . "</td>
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
                <td>" . $row["Status"] . "</td>
                    </tr>";
            }

            echo "</table>";

            // Free the result set
            mysqli_free_result($result);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
?>

<a href="logout.php"><h1>LOGOUT</h1></a>
</body>
</html>
<?php 
 } else{
     header("Location: phpenhancements.php?error");
     exit();
}
include 'Footer.inc';
?>
