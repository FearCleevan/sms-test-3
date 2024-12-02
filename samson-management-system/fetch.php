<?php
session_start();
include("connect.php");

// Check if student_id is set in the POST request
if (isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];

    // SQL query to fetch student details and their subjects
    $sql = "
        SELECT
            cs.student_id,
            cs.first_name,
            cs.course,
            cs.year_level,
            cs.semester,
            s.subject_code,
            s.subject_name
        FROM
            college_students cs
        JOIN
            student_subjects ss ON cs.student_id = ss.student_id
        JOIN
            subjects s ON ss.subject_code = s.subject_code
        WHERE
            cs.student_id = ?"; // Use parameterized query for security

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $student_id);
        
        // Execute the query
        $stmt->execute();
        
        // Bind the result variables
        $stmt->bind_result($student_id, $first_name, $course, $year_level, $semester, $subject_code, $subject_name);
        
        // Fetch and display the results
        echo "<h2>Student Details and Loaded Subjects</h2>";
        echo "<strong>Student ID:</strong> " . $student_id . "<br>";
        echo "<strong>Name:</strong> " . $name . "<br>";
        echo "<strong>Course:</strong> " . $course . "<br>";
        echo "<strong>Year Level:</strong> " . $year_level . "<br>";
        echo "<strong>Semester:</strong> " . $semester . "<br>";

        // Display subjects
        echo "<h3>Loaded Subjects:</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                </tr>";

        while ($stmt->fetch()) {
            echo "<tr>
                    <td>" . $subject_code . "</td>
                    <td>" . $subject_name . "</td>
                  </tr>";
        }

        echo "</table>";

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No student ID provided.";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Subjects</title>
</head>
<body>

    <h1>Enter Student ID to Fetch Subjects</h1>

    <form method="POST" action="fetch.php">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
        <input type="submit" value="Fetch Subjects">
    </form>

</body>
</html>
