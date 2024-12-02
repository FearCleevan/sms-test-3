<?php
// Start session and include database connection
session_start();
include("connect.php");

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch data from the database
$sql = "SELECT student_id, profile, CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, 
               CONCAT(address, ' ', province, ' ', city) as address, email, phone, course AS course, course_level AS year, session AS semester, 'Active' AS status 
        FROM college_students";
$result = $conn->query($sql);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./image/apple-touch-icon.png">

    <!-- Data Table CSS -->
    <link rel="stylesheet" href="./assets/js/datatables.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="table.css">

</head>

<body>

    <!-- SIDEBAR MENU -->
    <div class="sidebar-menu">
        <div class="sidebar-logo-container">
            <h3 id="dashboard-title" class="sidebar-title" style="padding-top: 20px; padding-left: 40px;">Department</h3>
        </div>
        <ul class="nav-list">
            <li><a href="./admin-dashboard.php" data-title="Dashboard"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li><a href="./enroll-student.php" data-title="Enroll a Student"><i class="fa-solid fa-user-plus"></i> <span>Enroll a Student</span></a></li>
            <li><a href="./test.php" data-title="Department" class="active"><i class="fa-solid fa-building"></i> <span>Department</span></a></li>
            <li><a href="#course" data-title="Course"><i class="fa-solid fa-book"></i> <span>Course</span></a></li>
            <li><a href="./add-subjects.php" data-title="Subjects"><i class="fa-solid fa-book-open"></i> <span>Subjects</span></a></li>
            <li><a href="#payment-management" data-title="Payment Management"><i class="fa-solid fa-credit-card"></i> <span>Payment Management</span></a></li>
            <li><a href="#grading-system" data-title="Grading System"><i class="fa-solid fa-graduation-cap"></i> <span>Grading System</span></a></li>
            <li><a href="#student-attendance" data-title="Student Attendance"><i class="fa-solid fa-calendar-check"></i> <span>Student Attendance</span></a></li>
            <li><a href="#announcement" data-title="Announcement"><i class="fa-solid fa-bullhorn"></i> <span>Announcement</span></a></li>
        </ul>
    </div>
    <!-- SIDEBAR MENU -->

    <!-- HEADER -->
    <div class="admin-header">
        <div class="header-container">
            <div class="sidebar-logo">
                <img src="./image/apple-touch-icon.png" alt="Samson Admin Logo" class="logo-img">
                <span class="sidebar-titles">Samson Admin</span>
                <i class="fa-solid fa-bars" id="toggle-menu-btn"></i>
            </div>
            <div class="header-right">
                <div class="header-value-right">
                    <div class="profile-image">
                        <a href="javascript:void(0);" id="profile-link">
                            <?php
                            if (isset($_SESSION['username'])) {
                                $email = $_SESSION['username'];

                                if ($conn && mysqli_ping($conn)) {
                                    $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");
                                    if ($row = mysqli_fetch_assoc($query)) {
                                        echo '<img src="' . htmlspecialchars($row['profile']) . '" alt="Profile Image">';
                                    } else {
                                        echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">';
                                    }
                                } else {
                                    echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">';
                                }
                            } else {
                                echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">';
                            }
                            ?>
                        </a>
                    </div>
                    <div class="name-access">
                        <?php
                        if (isset($_SESSION['username'])) {
                            $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");
                            if ($row = mysqli_fetch_assoc($query)) {
                                echo "<p>" . htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']) . "</p>";
                                echo "<span>" . htmlspecialchars($row['access'] ?? "Guest") . "</span>";
                            }
                        } else {
                            echo "<p>Guest</p><span>Guest</span>";
                        }
                        ?>
                    </div>
                    <!-- Dropdown Menu -->
                    <div class="profile-menu" id="profile-menu">
                        <ul>
                            <li><a href="user-info.php"><i class="fas fa-cogs"></i> Account Settings</a></li>
                            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                    <!-- Dropdown Menu -->
                </div>
            </div>
        </div>
    </div>
    <!-- HEADER -->

    <div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Student Profile</h5>
                </div>
                <div class="modal-body" id="student-details">
                    <!-- Student details will be injected here -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>



    <div class="table-main-container">
        <div class="table-container">
            <div class="table-class">

                <table id="example" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Display each student record
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td><img class='profile-image' src='data:image/jpeg;base64," . base64_encode($row['profile']) . "' alt='Profile'></td>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['semester']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>
                                                <button class='view-btn' data-id='" . htmlspecialchars($row['student_id']) . "' 
                                                        data-name='" . htmlspecialchars($row['full_name']) . "' 
                                                        data-email='" . htmlspecialchars($row['email']) . "' 
                                                        data-phone='" . htmlspecialchars($row['phone']) . "' 
                                                        data-address='" . htmlspecialchars($row['address']) . "' 
                                                        data-course='" . htmlspecialchars($row['course']) . "' 
                                                        data-year='" . htmlspecialchars($row['year']) . "' 
                                                        data-semester='" . htmlspecialchars($row['semester']) . "' 
                                                        data-status='" . htmlspecialchars($row['status']) . "' 
                                                        data-profile='" . base64_encode($row['profile']) . "'>
                                                    <i class='fa-regular fa-eye'></i>
                                                </button>
                                                <button title='Edit'><i class='fa-regular fa-pen-to-square'></i></button>
                                                <button title='Delete'><i class='fa-regular fa-trash-can'></i></button>
                                            </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Student ID</th>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>

            </div>




        </div>

        <!-- Modal Structure -->
        <div id="viewProfileModal" class="modal">
            <div id="profileDetails" class="modal-content">
                <div class="modal-footer">
                    <h4 class="modal-title">Student Profile</h4>
                    <i class="fa-solid fa-xmark modal-close" style="cursor: pointer;"></i>
                </div>
                <!-- Profile details will be loaded here via JavaScript -->
            </div>
        </div>
    </div>



    <style>
        :root {
            /* Primary Colors */
            --primary-bg: #f8f9fa;
            --primary-text: #212529;
            --primary-color-bg: #d8d8d8;
            --secondary-text: #6c757d;

            /* Accent Colors */
            --accent-edit: #007bff;
            --accent-delete: #dc3545;
            --accent-hover: #0056b3;

            /* Table Borders */
            --table-border: #dee2e6;

            /* Pagination Colors */
            --pagination-bg: #ffffff;
            --pagination-border: #dee2e6;
            --pagination-active-bg: #007bff;
            --pagination-active-text: #ffffff;

            /* Misc */
            --shadow: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            /* Set the default font */
        }

        body {
            background: #f2f2f2;
        }

        /* HEADER MENU */
        .admin-header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            z-index: 1001;
            /* Higher z-index so it stays on top of the sidebar */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px 10px;
            margin-bottom: 100px;
        }

        /* Container for the header contents */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            transition: transform 0.5s;
        }

        /* Left side of the header */

        .header-left .button-toggle {
            border: none;
        }

        /* Sidebar Logo Styles */
        .sidebar-logo {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 50px;
            /* Set image size to 50px */
            height: 50px;
            /* Maintain aspect ratio */
            border-radius: 50%;
            /* Optionally make the image circular */
        }

        .sidebar-titles {
            margin-top: 0;
            margin-left: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #34495e;
            /* Adjust color as needed */
        }

        .sidebar-logo i {
            font-size: 20px;
            margin-left: 10px;
            cursor: pointer;
            margin-top: 0;
            color: #34495e;
        }

        /* Additional Styling if needed */
        .sidebar-logo:hover .toggle-btn {
            color: #2c3e50;
            /* Darken the color when hovered */
        }


        .dashboard-title {
            font-size: 16px;
            /* Adjust font size for the text */
            color: white;
            /* Adjust the color as needed */
            margin: 0;
            /* Remove any default margin */
            text-align: center;
        }


        .header-right {
            margin-right: 40px;
        }

        /* Right side of the header */
        .header-right .header-value-right {
            display: flex;
            align-items: center;
            position: relative;
        }

        /* Profile image styles */
        .profile-image img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            overflow: hidden;
        }

        /* Name and access styling */
        .name-access {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 10px;
        }

        .name-access p {
            margin: 0;
            font-weight: bold;
            font-size: 13px;
            /* Adjusted font size */
            color: #333;
        }

        .name-access span {
            font-size: 11px;
            padding: 0;
            color: gray;
            margin-top: 5px;
            /* Space between name and access level */
        }

        /* Profile menu styles */
        .profile-menu {
            display: none;
            /* Hidden by default */
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 200px;
            /* Adjust width as needed */
        }

        /* List and links in the dropdown */
        .profile-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .profile-menu ul li {
            padding: 3px;
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #333;
        }

        /* Styling for icons */
        .profile-menu ul li a i {
            margin-right: 10px;
            margin-left: 10px;
            font-size: 16px;
            color: #333;
        }

        /* Links inside the menu */
        .profile-menu ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            padding: 8px 0;
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 100%;
        }

        /* Hover effect */
        .profile-menu ul li a:hover {
            background-color: #f4f4f4;
            color: #007bff;
        }

        /* Show the menu when hovered or clicked */
        #profile-link:hover+.profile-menu,
        .profile-menu:hover {
            display: block;
        }

        /* HEADER MENU */

        /* SIDE BAR MENU */
        .sidebar-menu {
            width: 250px;
            height: calc(100vh - 60px);
            /* Adjusted height to account for header height */
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            font-family: Arial, sans-serif;
            transition: width 0.3s;
            position: fixed;
            top: 60px;
            /* Offset to sit below the fixed header */
            left: 0;
            z-index: 1000;
            /* Lower z-index so header overlaps */
        }


        /* Additional adjustments if sidebar is collapsed */
        .sidebar-menu.collapsed {
            width: 70px;
        }

        /* Adjust icon positions when collapsed */
        .sidebar-menu.collapsed ul li a {
            justify-content: center;
        }

        /* Hide the sidebar title, text, and header-value-left elements when collapsed */
        .sidebar-menu.collapsed .sidebar-title,
        .sidebar-menu.collapsed ul li a span,
        .sidebar-menu.collapsed .sidebar-dropdown .dropdown-icon,
        .sidebar-menu.collapsed .header-value-left,
        /* Hides the entire header-value-left container */
        .sidebar-menu.collapsed .header-value-left *,
        /* Hides all children of header-value-left */
        .sidebar-menu.collapsed .header-value-left p {
            /* Ensures the title text is hidden */
            display: none;
        }

        .sidebar-logo-container h3 {
            margin-left: 0;
            margin-right: 20px;
        }


        .sidebar-menu h2 {
            font-size: 20px;
            margin-bottom: 20px;
            margin-left: 30px;
        }

        .sidebar-menu ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar-menu ul li {
            margin: 10px 0;
        }

        /* Main menu links */
        .sidebar-menu ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 12px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar-menu ul li a i {
            margin-right: 10px;
            /* Add space between icon and text */
            font-size: 15px;
            /* Icon size */
        }

        .sidebar-menu ul li a:hover {
            background-color: #34495e;
        }

        /* Active menu item */
        .sidebar-menu ul li a.active {
            background-color: #2980b9;
            color: #ffffff;
        }

        .sidebar-menu ul li a.active i {
            color: #ffffff;
        }

        /* Dropdown styling */
        .sidebar-dropdown-btn {
            cursor: pointer;
        }

        .sidebar-dropdown-content {
            display: none;
            list-style-type: none;
            padding: 0;
            margin: 5px 0 0 15px;
        }

        /* Dropdown menu links */
        .sidebar-dropdown-content li a {
            font-size: 12px;
            padding: 8px;
            color: #bdc3c7;
            display: flex;
            align-items: center;
            border-radius: 4px;
        }

        .sidebar-dropdown-content li a:hover {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .nav-list li a.active {
            background-color: #2980b9;
            border-left: 3px solid white;
            color: #ffffff;
        }

        /* Active dropdown */
        .sidebar-menu .sidebar-dropdown.active .sidebar-dropdown-content {
            display: block;
        }

        .sidebar-menu .sidebar-dropdown .dropdown-icon {
            margin-left: auto;
            /* Move the dropdown icon to the far right */
            transition: transform 0.3s;
        }

        /* SIDE BAR MENU */

        .table-class thead tr th,
        .table-class tfoot tr th {
            font-size: 13px;
            /* Font size for header and footer */
            font-weight: bold;
            /* Bold text */
            padding: 10px;
            /* Consistent padding */
            text-align: left;
            /* Align text to the left */
            color: #000;
            /* Text color */
            background-color: #f4f4f4;
            /* Background color */
            border-bottom: 2px solid #ddd;
            /* Optional: Add a bottom border for design */
        }


        .table-class tbody tr td {
            font-size: 12px !important;
            /* Set the font size */
            padding: 8px;
            /* Optional: Add some padding for better spacing */
            text-align: left;
            /* Optional: Align text to the left */
            color: #333;
            font-weight: 500;
            /* Optional: Text color for consistency */
            vertical-align: middle;
            /* Center vertically */
        }

        tbody tr td:first-child {
            text-align: center;
            /* Horizontal alignment */
            vertical-align: middle;
            /* Vertical alignment */
        }


        .table-main-container {
            background: #fff;
            margin: 20px;
            margin-left: 260px;
            margin-top: 100px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;

            /* Adds a soft shadow */
        }


        .table-class {
            padding: 5px;
        }

        .table-class table {
            margin-top: 5px;
            border: 1px solid #ddd;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            object-fit: cover;
            object-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dt-length label {
            font-weight: 500;
            font-size: 14px;
        }

        .dt-buttons {
            margin-top: 5px;
            gap: 3px;
        }

        .dt-buttons button {
            color: #fff;
            background: #34495e;
            transition: opacity all ease 0.3s;
        }

        .dt-buttons button span {
            font-size: 13px;
            font-weight: 600;
        }

        .dt-buttons button:hover {
            opacity: 0.8;
        }

        .dt-info {
            margin-top: 5px;
            font-weight: 500;
            font-size: 14px;
        }

        .dt-paging li button {
            margin-top: 5px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- JQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Data Table JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Bootstrap JS and jQuery (needed for modal) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <script src="./assets/js/datatables.min.js"></script>
    <script src="./assets/js/app.js"></script>

</body>

</html>