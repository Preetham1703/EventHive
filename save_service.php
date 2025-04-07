<?php
// Database connection settings
$host = "localhost"; // Your database host
$username = "root";  // Your database username
$password = "Root";      // Your database password
$dbname = "demo"; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $city = $_POST['city'];
    $services = isset($_POST['service']) ? implode(", ", $_POST['service']) : '';
    $other_service = isset($_POST['otherServiceText']) ? trim($_POST['otherServiceText']) : '';

    // Server-side validation
    $errors = [];
    if (empty($name) || !preg_match("/^[A-Za-z\s]+$/", $name)) {
        $errors[] = "Name must contain only letters and cannot be empty.";
    }
    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Mobile must be a 10-digit number.";
    }
    if (empty($city)) {
        $errors[] = "Please select a city.";
    }
    if (empty($services)) {
        $errors[] = "Please select at least one service.";
    }

    // If no errors, save to database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO services (name, mobile, city, services, other_service) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $mobile, $city, $services, $other_service);

        if ($stmt->execute()) {
            echo "<h3>Service added successfully!</h3>";
            echo '<a href="index.html">Go back</a>'; // Replace "index.html" with your HTML file name
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Display errors
        echo "<h3>Validation Errors:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo '<a href="index.html">Go back</a>'; // Replace "index.html" with your HTML file name
    }
}

// Close connection
$conn->close();
?>