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
               address, email, phone, edu_level, grade_level, lrn, session AS semester, 'Active' AS status 
        FROM jhs_students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student</title>
    <link rel="stylesheet" href="jhs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./image/apple-touch-icon.png">
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

    <!-- MAIN CONTAINER -->
    <div class="main-container">
        <div class="sub-main-container">
            <div class="enroll-options">
                <button class="enroll-btn">
                    <a href="./college.php" onclick="setActive(this)">
                        College Department
                    </a>
                </button>
                <button class="enroll-btn">
                    <a href="./tvet_dep.php" onclick="setActive(this)">
                        TVET Department
                    </a>
                </button>
                <button class="enroll-btn">
                    <a href="#" onclick="setActive(this)">
                        Junior High School Department
                    </a>
                </button>
                <button class="enroll-btn">
                    <a href="#" onclick="setActive(this)">
                        Senior High School Department
                    </a>
                </button>
            </div>

        </div>


        <div class="main-container-header">
            <div class="sub-main-container-header start">
                <i class="fa-solid fa-bars-progress"></i> <span id="department-name">Junior High School Department</span>
            </div>
        </div>

        <div class="main-container-form">

            <div class="main-crud-container">
                <header>
                    <div class="filterEntries">
                        <div class="entries">
                            Show
                            <select title="show" name="" id="table_size">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries
                        </div>

                        <div class="filter">
                            <label for="search">Search:</label>
                            <input type="search" name="" id="search" placeholder="Enter student ID or full name">
                        </div>
                    </div>
                </header>

                <!-- Horizontal scroll wrapper -->
                <div class="table-scroll">
                    <div class="table-container">
                        <table class="table-class">
                            <thead>
                                <tr class="heading">
                                    <th>Student ID</th>
                                    <th>Profile</th>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Grade Level</th>
                                    <th>LRN</th>
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
                                        echo "<td><img class='profile-img' src='data:image/jpeg;base64," . base64_encode($row['profile']) . "' alt='Profile'></td>";
                                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['edu_level']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['grade_level']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['lrn']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['semester']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                        echo "<td>
                                                <button class='view-btn' data-id='" . htmlspecialchars($row['student_id']) . "' 
                                                        data-name='" . htmlspecialchars($row['full_name']) . "' 
                                                        data-email='" . htmlspecialchars($row['email']) . "' 
                                                        data-phone='" . htmlspecialchars($row['phone']) . "' 
                                                        data-address='" . htmlspecialchars($row['address']) . "' 
                                                        data-edu_level='" . htmlspecialchars($row['edu_level']) . "' 
                                                        data-grade_level='" . htmlspecialchars($row['grade_level']) . "' 
                                                        data-lrn='" . htmlspecialchars($row['lrn']) . "' 
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
                        </table>
                    </div>

                </div>


                <!-- Modal Structure -->
                <div id="viewProfileModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-footer">
                            <h4 class="modal-title">Student Profile</h4>
                            <i class="fa-solid fa-xmark modal-close" style="cursor: pointer;"></i>
                        </div>
                        <div id="profileDetails">
                            <!-- Profile details will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>



                <footer>
                    <span>Showing 1 to 10 of 50 entries</span>
                    <div class="pagination">
                        <button title="prev" id="prev-btn">Prev</button>
                        <button class="active" id="page-1">1</button>
                        <button id="page-2">2</button>
                        <button id="page-3">3</button>
                        <button id="page-4">4</button>
                        <button id="page-5">5</button>
                        <button title="next" id="next-btn">Next</button>
                    </div>
                </footer>
            </div>


            <style>
                /* General container styling */
                .sub-main-container,
                .main-container-form {
                    width: 100%;
                    margin: 0 auto;
                    padding: 1rem;
                }

                .main-crud-container {
                    border: 1px solid #ddd;
                    padding: 20px;
                    border-radius: 3px;
                }

                .main-crud-container .filterEntries {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;

                }

                .filterEntries .entries {
                    color: var(--secondary-text);
                    margin-right: 10px;
                }

                .filterEntries .entries select,
                .filterEntries .filter input {
                    padding: 5px 10px;
                    border: 1px solid #aaa;
                    color: var(--secondary-text);
                    background: var(--pagination-active-text);
                    border-radius: 3px;
                    outline: none;
                    transition: 0.3s;
                    cursor: pointer;
                    font-size: 12px;
                }

                .filterEntries .entries select {
                    padding: 5px 10px;
                }

                .filterEntries .filter {
                    display: flex;
                    align-items: center;
                }

                .filter {
                    gap: 10px;
                }

                /* Table scroll wrapper */
                .table-scroll {
                    overflow-x: auto;
                    /* Enables horizontal scrolling if needed */
                    width: 100%;
                    margin-top: 5px;
                    border: 1px solid #ddd;
                }

                .table-wrapper {
                    width: 100%;
                    /* Ensures the container fits the parent element */
                    overflow-x: auto;
                    /* Allows horizontal scrolling if the table overflows */
                    overflow-y: hidden;
                    /* Prevents vertical scroll (if not needed) */
                }

                /* Table styling */
                .table-class {
                    width: 100%;
                    border-collapse: collapse;
                    background-color: #fff;
                    font-family: 'Arial', sans-serif;
                    font-size: 12px;
                    color: #333;
                    overflow-x: scroll;
                }

                .table-class th,
                .table-class td {
                    padding: 12px 15px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }

                .table-class td {
                    color: #333;
                    font-size: 11px;
                }

                .table-class th {
                    background-color: #f4f4f4;
                    font-weight: bold;
                    position: sticky;
                    top: 0;
                    /* Sticky header for better usability */
                    z-index: 1;
                }

                .table-class tbody tr:hover {
                    background-color: #f9f9f9;
                    /* Hover effect */
                }

                .table-class img.profile-img {
                    width: 50px;
                    height: 50px;
                    object-fit: cover;
                    border-radius: 50%;
                    border: 1px solid #ccc;
                }

                .main-crud-container footer {
                    margin-top: 5px;
                    font-size: 14px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .main-crud-container footer span {
                    color: var(--primary-text);
                }

                footer .pagination {
                    display: flex;
                }

                footer .pagination button {
                    width: 40px;
                    padding: 5px 0;
                    color: var(--primary-text);
                    background: transparent;
                    font-size: 14px;
                    cursor: pointer;
                    pointer-events: auto;
                    outline: none;
                    border: 1px solid var(--secondary-text);
                    margin: 0;
                }

                .pagination button:first-child {
                    width: 85px;
                    border-top-left-radius: 3px;
                    border-bottom-left-radius: 3px;
                    border-left: 1px solid var(--secondary-text);
                    opacity: 0.6;
                    pointer-events: none;
                }

                .pagination button:last-child {
                    width: 85px;
                    border-top-right-radius: 3px;
                    border-bottom-right-radius: 3px;
                    opacity: 0.6;
                    pointer-events: none;
                }

                .pagination button.active,
                .pagination button:hover {
                    background: var(--accent-edit);
                }

                table td button {
                    margin: 0 3px;
                    padding: 5px;
                    width: 35px;
                    color: var(--secondary-text);
                    font-size: 14px;
                    cursor: pointer;
                    pointer-events: auto;
                    border-radius: 2px;
                    outline: none;
                    border: 1px solid var(--pagination-border);
                    background: var(--pagination-bg);
                }

                /* Responsive styles */
                @media screen and (max-width: 768px) {

                    .table-class th,
                    .table-class td {
                        font-size: 12px;
                        padding: 8px;
                    }
                }

                /* Pagination styling */
                .pagination {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-top: 1rem;
                    gap: 0.5rem;
                }

                .pagination button {
                    padding: 8px 12px;
                    border: 1px solid #ddd;
                    background-color: #f4f4f4;
                    color: #333;
                    cursor: pointer;
                    border-radius: 4px;
                    transition: background-color 0.3s ease;
                }

                .pagination button:hover {
                    background-color: #007bff;
                    color: #fff;
                }

                .pagination .active {
                    background-color: #007bff;
                    color: #fff;
                    font-weight: bold;
                }

                /* Modal styles */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.5);
                    padding: 2rem;
                }

                .modal-content {
                    background-color: #fff;
                    margin: auto;
                    padding: 1.5rem;
                    border-radius: 8px;
                    width: 90%;
                    max-width: 600px;
                }

                .modal-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 1rem;
                }

                .modal-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin: 0;
                }

                .modal-close {
                    font-size: 20px;
                    color: #333;
                }

                #profileDetails div {
                    margin: 10px 0;
                    font-size: 14px;
                }

                #profileDetails img.profile-img {
                    display: block;
                    margin: 10px auto;
                    max-width: 100px;
                    border: 1px solid #ddd;
                    border-radius: 50%;
                }
            </style>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Get modal and close icon
                    const modal = document.getElementById('viewProfileModal');
                    const closeButton = document.querySelector('.modal-close');

                    // Add event listener for "View" button click
                    const viewButtons = document.querySelectorAll('.view-btn');

                    viewButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const studentId = this.getAttribute('data-id');
                            const fullName = this.getAttribute('data-name');
                            const email = this.getAttribute('data-email');
                            const phone = this.getAttribute('data-phone');
                            const address = this.getAttribute('data-address');
                            const course = this.getAttribute('data-course');
                            const year = this.getAttribute('data-year');
                            const semester = this.getAttribute('data-semester');
                            const status = this.getAttribute('data-status');
                            const profileBase64 = this.getAttribute('data-profile');

                            // Set modal content
                            const profileDetails = document.getElementById('profileDetails');
                            profileDetails.innerHTML = `
                                                        <div><strong>Full Name:</strong> ${fullName}</div>
                                                        <div><strong>Email:</strong> ${email}</div>
                                                        <div><strong>Phone:</strong> ${phone}</div>
                                                        <div><strong>Address:</strong> ${address}</div>
                                                        <div><strong>Course:</strong> ${course}</div>
                                                        <div><strong>Year:</strong> ${year}</div>
                                                        <div><strong>Semester:</strong> ${semester}</div>
                                                        <div><strong>Status:</strong> ${status}</div>
                                                        <div><strong>Profile:</strong><br><img src="data:image/jpeg;base64,${profileBase64}" alt="Profile Image" class="profile-img"></div>
                                                    `;

                            // Show modal
                            modal.style.display = 'block';
                        });
                    });

                    // Close modal when the close icon is clicked
                    closeButton.addEventListener('click', function() {
                        modal.style.display = 'none';
                    });

                    // Close modal when clicking outside of the modal content
                    window.addEventListener('click', function(event) {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                });
            </script>

            <style>
                /* Modal styles */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.4);
                }

                .modal-content {
                    background-color: #fefefe;
                    margin: 15% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    max-width: 1200px;
                    /* Adjust width as needed */
                }

                .modal-footer {
                    display: flex;
                    justify-content: space-between;
                    /* Align title and close icon */
                    align-items: center;
                    padding: 10px;
                }

                .modal-title {
                    margin: 0;
                }

                .modal-close {
                    font-size: 24px;
                    /* Adjust size of the close icon */
                    color: #ff4d4d;
                    cursor: pointer;
                }

                .modal-close:hover {
                    color: #ff1a1a;
                }

                .profile-img {
                    width: 50px;
                    /* Adjust the profile image size as needed */
                    height: 50px;
                    border-radius: 50%;
                    object-fit: cover;
                }
            </style>


            <script>
                let currentPage = 1;
                let rowsPerPage = 5; // Default rows per page
                let totalRows = 50; // Total rows without search
                let filteredRows = []; // Store filtered rows based on search
                let allRows = []; // Store all rows for reference

                // Function to update the visible rows based on the current page
                function updateTableRows() {
                    const tableSize = parseInt(document.getElementById('table_size').value, 10);
                    const rowsToDisplay = filteredRows.length > 0 ? filteredRows : allRows;
                    const totalEntries = rowsToDisplay.length;
                    const totalPages = Math.ceil(totalEntries / tableSize);

                    // Calculate the start and end indexes for pagination
                    const startIndex = (currentPage - 1) * tableSize;
                    const endIndex = Math.min(startIndex + tableSize, totalEntries);

                    // Hide all rows first
                    allRows.forEach((row) => {
                        row.style.display = "none"; // Hide all rows
                    });

                    // Show the rows for the current page
                    rowsToDisplay.slice(startIndex, endIndex).forEach((row) => {
                        row.style.display = ""; // Show only the rows for the current page
                    });

                    // If no rows to display after search, show "No Data Found"
                    if (filteredRows.length === 0 && document.getElementById('search').value !== "") {
                        const tableBody = document.querySelector(".table-class tbody");
                        // Check if "No Data Found" already exists to avoid duplication
                        if (!document.querySelector(".table-class tbody tr.no-data-row")) {
                            const noDataRow = document.createElement("tr");
                            noDataRow.classList.add("no-data-row");
                            noDataRow.innerHTML = "<td colspan='11' style='text-align:center;'>No Data Found</td>";
                            tableBody.appendChild(noDataRow);
                        }

                        // Hide pagination controls if no data is found
                        document.querySelector('.pagination').style.display = 'none';
                    } else {
                        // Remove the "No Data Found" row if there are search results
                        const noDataRow = document.querySelector(".table-class tbody tr.no-data-row");
                        if (noDataRow) {
                            noDataRow.remove();
                        }

                        // Show pagination controls if there are results
                        document.querySelector('.pagination').style.display = 'block';
                    }

                    // Update footer to show correct page range
                    updateFooter(totalEntries, tableSize, currentPage, Math.ceil(totalEntries / tableSize));
                }

                // Function to update the pagination footer
                function updateFooter(totalEntries, tableSize, currentPage, totalPages) {
                    const footerText = document.querySelector("footer span");
                    const start = (currentPage - 1) * tableSize + 1;
                    const end = Math.min(currentPage * tableSize, totalEntries);
                    footerText.textContent = `Showing ${start} to ${end} of ${totalEntries} entries`;

                    // Update pagination buttons
                    const pageButtons = document.querySelectorAll('.pagination button');
                    pageButtons.forEach(button => button.classList.remove('active'));
                    document.getElementById('page-' + currentPage)?.classList.add('active');
                }

                // Function to handle the next page
                function nextPage() {
                    const rowsToDisplay = filteredRows.length > 0 ? filteredRows : allRows;
                    const totalPages = Math.ceil(rowsToDisplay.length / rowsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        updateTableRows();
                    }
                }

                // Function to handle the previous page
                function prevPage() {
                    if (currentPage > 1) {
                        currentPage--;
                        updateTableRows();
                    }
                }

                // Function to go to a specific page
                function goToPage(page) {
                    currentPage = page;
                    updateTableRows();
                }

                // Function to change rows per page
                function changeRowsPerPage() {
                    rowsPerPage = parseInt(document.getElementById('table_size').value, 10);
                    currentPage = 1; // Reset to the first page when changing rows per page
                    updateTableRows();
                }

                // Function to handle the search input
                function searchTable() {
                    const searchInput = document.getElementById('search').value.toLowerCase();
                    filteredRows = []; // Reset filtered rows

                    allRows.forEach(row => {
                        const studentId = row.cells[0].textContent.toLowerCase();
                        const fullName = row.cells[2].textContent.toLowerCase();

                        if (studentId.includes(searchInput) || fullName.includes(searchInput)) {
                            filteredRows.push(row);
                        }
                    });

                    currentPage = 1; // Reset to the first page on search
                    updateTableRows(); // Update the rows based on the search
                }

                // Initial setup
                document.addEventListener("DOMContentLoaded", () => {
                    // Store all rows for reference
                    allRows = Array.from(document.querySelectorAll(".table-class tbody tr"));

                    // Set default rows on load
                    updateTableRows();

                    // Event listeners for pagination buttons
                    document.getElementById('prev-btn').addEventListener('click', prevPage);
                    document.getElementById('next-btn').addEventListener('click', nextPage);
                    document.querySelectorAll('.pagination button').forEach(button => {
                        if (button.id.startsWith('page-')) {
                            button.addEventListener('click', () => goToPage(parseInt(button.id.replace('page-', ''), 10)));
                        }
                    });

                    // Event listener for rows per page change
                    document.getElementById("table_size").addEventListener("change", changeRowsPerPage);

                    // Event listener for the search input
                    document.getElementById('search').addEventListener('input', searchTable);
                });
            </script>

        </div>

    </div>

    <script>
        // Function to set active state and display text
        function setActive(button) {
            // Get all buttons
            const buttons = document.querySelectorAll('.enroll-btn');

            // Remove 'active' class from all buttons
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            // Add 'active' class to the clicked button
            button.classList.add('active');

            // Get the text of the clicked button
            const buttonText = button.innerText;

            // Update the display span with the clicked button's text
            document.getElementById('department-name').innerText = `${buttonText}`;
        }
    </script>

    <!-- MAIN CONTAINER -->

    <script>
        // Sidebar active menu highlighting
        document.querySelectorAll('.sidebar-menu ul li a').forEach(menuItem => {
            menuItem.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-menu ul li a').forEach(item => item.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('dashboard-title').textContent = this.getAttribute('data-title');
            });
        });

        // Toggle sidebar
        const toggleMenuBtn = document.getElementById('toggle-menu-btn');
        toggleMenuBtn.addEventListener('click', () => {
            document.querySelector('.sidebar-menu').classList.toggle('collapsed');
        });

        // Profile menu toggle
        const profileLink = document.getElementById("profile-link");
        const profileMenu = document.getElementById("profile-menu");
        profileLink.addEventListener("click", function(e) {
            e.preventDefault();
            profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
        });

        // Close profile menu on outside click
        window.addEventListener("click", function(e) {
            if (!profileLink.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = "none";
            }
        });
    </script>
</body>


</html>