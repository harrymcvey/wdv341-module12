<?php
// database connection file
require 'db-connect.php';

// Define variables and initialize with empty values
$name = $description = $presenter = "";
$date_inserted = $date_updated = date("Y-m-d H:i:s"); // Current date and time

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if honeypot field is empty
    if(empty($_POST['honeypot'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $presenter = $_POST['presenter'];

        // Processing the date_time input
        $date_time = $_POST['date_time'];
        $date_time = str_replace("T", " ", $date_time); 
        $date_parts = explode(" ", $date_time);
        $events_date = $date_parts[0]; 
        $events_time = $date_parts[1] ?? '00:00'; 

        // Prepare an insert statement
        $sql = "INSERT INTO wdv341_events (events_name, events_description, events_presenter, events_date, events_time, events_date_inserted, events_date_updated) VALUES (:name, :description, :presenter, :events_date, :events_time, :date_inserted, :date_updated)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":presenter", $presenter);
            $stmt->bindParam(":events_date", $events_date);
            $stmt->bindParam(":events_time", $events_time);
            $stmt->bindParam(":date_inserted", $date_inserted);
            $stmt->bindParam(":date_updated", $date_updated);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                echo "Event inserted successfully.";
            } else {
                echo "Error inserting event.";
            }
        }

        unset($stmt);
    } else {
        echo "Error";
    }
}

unset($pdo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Form</title>
</head>
<body>
    <!-- Link to go back to homepage -->
    <p><a href="homepage.php">Back to Homepage</a></p>

    <form action="eventsForm.php" method="post">
        Name: <input type="text" name="name"><br>
        Description: <textarea name="description"></textarea><br>
        Presenter: <input type="text" name="presenter"><br>
        Date and Time: <input type="datetime-local" name="date_time"><br>
        <!-- Honeypot field for bot protection -->
        <input type="text" name="honeypot" style="display:none;">
        <input type="submit" value="Submit">
    </form>
</body>
</html>

