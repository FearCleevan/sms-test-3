<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $subject_id = $_POST['subject_id'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $semester = $_POST['semester'];

    // Decode the subjects from the JSON string
    $subjects = json_decode($_POST['subjects']);  // Decode JSON into an array

    // Prepare a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO student_subject (subject_id, course, semester, year_level, subjects) 
                            VALUES (?, ?, ?, ?, ?)");

    // Bind parameters
    $subjects_json = json_encode($subjects); // Encode the subjects back into JSON for storage
    $stmt->bind_param(
        "sssss",
        $subject_id,
        $course,
        $semester,
        $year_level,
        $subjects_json // Pass the encoded JSON as a variable
    );

    // Execute the query
    if ($stmt->execute()) {
        echo "Record saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form Clone</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="form-container">
        <!-- Header -->
        <div class="header">
            <div>SAMSON POLYTECHNIC COLLEGE OF DAVAO</div>
            <div>Formerly SAMSON TECHNICAL INSTITUTE</div>
            <div>Magayaysay Avenue corner Chavez Street, Davao</div>
            <div>REGISTRATION FORM</div>
            <div><small>Completely Fill-out this Form</small></div>
        </div>

        <!-- Form Fields -->
        <form action="index.php" method="POST">
            <div class="form-loading-subjects">
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label>Subject ID:</label>
                        <input type="text" name="subject_id">
                    </div>
                </div>

                <div class="form-row">
                    <!-- Course Selection -->
                    <div class="mb-4">
                        <label for="course" class="block text-lg font-medium">Course</label>
                        <select id="course" name="course" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="BSIT">BSIT</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSBA">BSBA</option>
                            <option value="BSTM">BSTM</option>
                            <option value="BTVTeD-AT">BTVTeD-AT</option>
                            <option value="BTVTeD-HVACR TECH">BTVTeD-HVACR TECH</option>
                            <option value="BTVTeD-FSM">BTVTeD-FSM</option>
                            <option value="BTVTeD-ET">BTVTeD-ET</option>
                        </select>
                    </div>

                    <!-- Year Level Selection -->
                    <div class="mb-4">
                        <label for="year_level" class="block text-lg font-medium">Year Level</label>
                        <select id="year_level" name="year_level" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="1stYear">1st Year</option>
                            <option value="2ndYear">2nd Year</option>
                            <option value="3rdYear">3rd Year</option>
                            <option value="4thYear">4th Year</option>
                        </select>
                    </div>

                    <!-- Semester Selection -->
                    <div class="mb-4">
                        <label for="semester" class="block text-lg font-medium">Semester</label>
                        <select id="semester" name="semester" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="1stSem">1st Semester</option>
                            <option value="2ndSem">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                </div>

                <!-- Subject Table -->
                <h2>Subjects</h2>
                <table class="subjects">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Units No</th>
                            <th>Pre Requisite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- New rows will be added here -->
                    </tbody>
                </table>

                <button type="button" onclick="addRow()">Add Another Subject</button>

            </div>
            <input type="submit" value="Submit">
        </form>
    </div>

    <script>
        // Function to add a new row to the table
        function addRow() {
            const table = document.querySelector("table.subjects tbody");
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
            <td><input type="text" style="width: 100px; height: 30px;" name="subjects[][subject_code]" placeholder="Subject Code"></td>
            <td><input type="text" style="width: 600px; height: 30px;" name="subjects[][description]" placeholder="Description"></td>
            <td><input type="number" style="width: 100px; height: 30px;" name="subjects[][lec]" placeholder="Lec"></td>
            <td><input type="number" style="width: 140px; height: 30px;" name="subjects[][lab]" placeholder="Lab"></td>
            <td><input type="number" style="width: 80px; height: 30px;" name="subjects[][unit_no]" placeholder="Units No"></td>
            <td><input type="text" style="width: 80px; height: 30px;" name="subjects[][pre_req]" placeholder="Pre Requisite"></td>
            <td><button type="button" onclick="removeRow(this)">Remove</button></td>
        `;
            table.appendChild(newRow);
        }

        // Function to remove a row
        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
        }

        // Ensure the subjects are grouped properly into an array of objects when submitting
        document.querySelector('form').onsubmit = function() {
            const subjects = [];
            const rows = document.querySelectorAll('table.subjects tbody tr');

            rows.forEach(row => {
                const subject = {
                    subject_code: row.querySelector('input[name="subjects[][subject_code]"]').value,
                    description: row.querySelector('input[name="subjects[][description]"]').value,
                    lec: row.querySelector('input[name="subjects[][lec]"]').value,
                    lab: row.querySelector('input[name="subjects[][lab]"]').value,
                    unit_no: row.querySelector('input[name="subjects[][unit_no]"]').value,
                    pre_req: row.querySelector('input[name="subjects[][pre_req]"]').value
                };
                subjects.push(subject);
            });

            // Add the subjects array to the form as a hidden input before submitting
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'subjects';
            hiddenInput.value = JSON.stringify(subjects); // Ensure it's correctly serialized
            document.querySelector('form').appendChild(hiddenInput);
        }
    </script>

</body>

</html>