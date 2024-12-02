<?php
session_start();
include("connect.php");

// Prevent caching to ensure the page is not cached and always fresh on each load
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./image/apple-touch-icon.png">
    <style>
        /* Styles for circular image container */
        .profile-image-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-image-container img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>



    <!-- SIDEBAR MENU -->
    <div class="sidebar-menu">
        <div class="sidebar-logo-container">
            <h3 id="dashboard-title" class="sidebar-title" style="padding-top: 20px; padding-left: 40px;">Dashboard</h3>
        </div>

        <ul class="nav-list">
            <li><a href="./admin-dashboard.php" data-title="Dashboard" class="active"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li><a href="enroll-student.php" data-title="Enroll a Student"><i class="fa-solid fa-user-plus"></i> <span>Enroll a Student</span></a></li>

            <li class="sidebar-dropdown">
                <a href="#department" class="sidebar-dropdown-btn" data-title="Department" class="menu-item">
                    <i class="fa-solid fa-building"></i> <span>Department</span>
                    <i class="fa-solid fa-caret-right dropdown-icon"></i>
                </a>
                <ul class="sidebar-dropdown-content">
                    <li><a href="./tvet_dep.php" data-title="TVET" class="menu-item"><i class="fa-solid fa-tools"></i> <span>TVET</span></a></li>
                    <li><a href="#high-school" data-title="High School" class="menu-item"><i class="fa-solid fa-school"></i> <span>HIGH SCHOOL</span></a></li>
                    <li><a href="#college" data-title="College" class="menu-item"><i class="fa-solid fa-university"></i> <span>COLLEGE</span></a></li>
                </ul>
            </li>

            <li class="sidebar-dropdown">
                <a href="#course" class="sidebar-dropdown-btn" data-title="Course" class="menu-item">
                    <i class="fa-solid fa-book"></i> <span>Course</span>
                    <i class="fa-solid fa-caret-right dropdown-icon"></i>
                </a>
                <ul class="sidebar-dropdown-content">
                    <li><a href="./bsit.php" data-title="BSIT" class="active"><i class="fa-solid fa-laptop-code"></i> <span>BSIT</span></a></li>
                    <li><a href="#bsba" data-title="BSBA" class="menu-item"><i class="fa-solid fa-chart-line"></i> <span>BSBA</span></a></li>
                    <li><a href="#bshm" data-title="BSHM" class="menu-item"><i class="fa-solid fa-utensils"></i> <span>BSHM</span></a></li>
                    <li><a href="#bstm" data-title="BSTM" class="menu-item"><i class="fa-solid fa-plane"></i> <span>BSTM</span></a></li>
                </ul>
            </li>

            <li class="sidebar-dropdown">
                <a href="#subjects" class="sidebar-dropdown-btn" data-title="Subjects" class="menu-item">
                    <i class="fa-solid fa-book"></i> <span>Subjects</span>
                    <i class="fa-solid fa-caret-right dropdown-icon"></i>
                </a>
                <ul class="sidebar-dropdown-content">
                    <li><a href="#bsit" data-title="BSIT" class="menu-item"><i class="fa-solid fa-laptop-code"></i> <span>BSIT</span></a></li>
                    <li><a href="#bsba" data-title="BSBA" class="menu-item"><i class="fa-solid fa-chart-line"></i> <span>BSBA</span></a></li>
                    <li><a href="#bshm" data-title="BSHM" class="menu-item"><i class="fa-solid fa-utensils"></i> <span>BSHM</span></a></li>
                    <li><a href="#bstm" data-title="BSTM" class="menu-item"><i class="fa-solid fa-plane"></i> <span>BSTM</span></a></li>
                    <li><a href="#btvted-at" data-title="BTVTeD-AT" class="menu-item"><i class="fa-solid fa-car"></i> <span>BTVTeD-AT</span></a></li>
                    <li><a href="#btvted-hvacr" data-title="BTVTeD-HVACR TECH" class="menu-item"><i class="fa-solid fa-snowflake"></i> <span>BTVTeD-HVACR TECH</span></a></li>
                    <li><a href="#btvted-fsm" data-title="BTVTeD-FSM" class="menu-item"><i class="fa-solid fa-utensils"></i> <span>BTVTeD-FSM</span></a></li>
                    <li><a href="#btvted-et" data-title="BTVTeD-ET" class="menu-item"><i class="fa-solid fa-bolt"></i> <span>BTVTeD-ET</span></a></li>
                </ul>
            </li>

            <li><a href="#payment-management" data-title="Payment Management" class="menu-item"><i class="fa-solid fa-credit-card"></i> <span>Payment Management</span></a></li>
            <li><a href="#grading-system" data-title="Grading System" class="menu-item"><i class="fa-solid fa-graduation-cap"></i> <span>Grading System</span></a></li>
            <li><a href="#student-attendance" data-title="Student Attendance" class="menu-item"><i class="fa-solid fa-calendar-check"></i> <span>Student Attendance</span></a></li>
            <li><a href="#announcement" data-title="Announcement" class="menu-item"><i class="fa-solid fa-bullhorn"></i> <span>Announcement</span></a></li>
        </ul>
    </div>
    <!-- SIDEBAR MENU -->

    <!-- HEADER -->
    <div class="admin-header">
        <div class="header-container">
            <div class="sidebar-logo">
                <img src="./image/apple-touch-icon.png" alt="Samson Admin Logo" class="logo-img">
                <span class="sidebar-titles">Samson Admin</span>
                <i class="fa-solid fa-bars" id="toggle-menu-btn" class="toggle-btn"></i>
            </div>

            <div class="header-right">
                <div class="header-value-right">
                    <div class="profile-image">
                        <a href="javascript:void(0);" id="profile-link">
                            <?php
                            // Check if session username is set
                            if (isset($_SESSION['username'])) {
                                $email = $_SESSION['username'];
                                $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");

                                if ($row = mysqli_fetch_assoc($query)) {
                                    // Display profile image if it exists
                                    if (!empty($row['profile'])) {
                                        echo '<img src="' . htmlspecialchars($row['profile']) . '" alt="Profile Image">';
                                    } else {
                                        echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image if no profile is found
                                    }
                                } else {
                                    echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image for failed query
                                }
                            } else {
                                echo '<img src="./uploads/default-profile.jpg" alt="Default Profile Image">'; // Default image if no session
                            }
                            ?>
                        </a>
                    </div>

                    <div class="name-access">
                        <?php
                        // Check if session username is set
                        if (isset($_SESSION['username'])) {
                            $email = $_SESSION['username'];
                            $query = mysqli_query($conn, "SELECT * FROM admin_user WHERE username='$email'");

                            if ($row = mysqli_fetch_assoc($query)) {
                                // Display the first and last name with a space between them
                                echo "<p>" . htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['lastName']) . "</p>";

                                // Display the access level (admin or teacher)
                                if (!empty($row['access'])) {
                                    echo "<span>" . htmlspecialchars($row['access']) . "</span>";
                                }
                            } else {
                                echo "<p>Guest</p><span>Guest</span>";
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

                </div>
            </div>
        </div>
    </div>
    <!-- HEADER -->


    <script>
        // Get elements
        const profileLink = document.getElementById("profile-link");
        const profileMenu = document.getElementById("profile-menu");

        // Toggle the visibility of the profile menu when the profile image is clicked
        profileLink.addEventListener("click", function(e) {
            e.preventDefault(); // Prevent default link behavior
            profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
        });

        // Close the profile menu if clicking anywhere outside of the menu or the profile image
        window.addEventListener("click", function(e) {
            if (!profileLink.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.style.display = "none";
            }
        });

        // SIDE BAR JS
        document.querySelectorAll(".sidebar-dropdown-btn").forEach(item => {
            item.addEventListener("click", function() {
                const dropdown = this.parentElement;
                dropdown.classList.toggle("active");

                // Toggle icon between right caret and down caret
                const icon = this.querySelector(".dropdown-icon");
                if (dropdown.classList.contains("active")) {
                    icon.classList.remove("fa-caret-right");
                    icon.classList.add("fa-caret-down"); // Change to down caret when expanded
                } else {
                    icon.classList.remove("fa-caret-down");
                    icon.classList.add("fa-caret-right"); // Change to right caret when collapsed
                }
            });
        });
        // SIDE BAR JS

        // SELECTED MENU
        const dashboardTitle = document.getElementById("dashboard-title");

        // Function to update the title
        function updateTitle(event) {
            const newTitle = event.target.getAttribute("data-title");
            if (newTitle) {
                dashboardTitle.textContent = newTitle;
            }
        }

        // Attach click event listeners to each menu item
        document.querySelectorAll(".sidebar-menu a").forEach(item => {
            item.addEventListener("click", updateTitle);
        });
        // SELECTED MENU

        // ACTIVE MENU
        document.querySelectorAll('.sidebar-menu ul li a').forEach(menuItem => {
            menuItem.addEventListener('click', function() {
                // Remove active class from all menu items
                document.querySelectorAll('.sidebar-menu ul li a').forEach(item => item.classList.remove('active'));

                // Add active class to clicked menu item
                this.classList.add('active');

                // Update dashboard title based on clicked menu item
                document.getElementById('dashboard-title').textContent = this.getAttribute('data-title');
            });
        });

        // HIDE SIDEBAR MENU
        document.getElementById('toggle-menu-btn').addEventListener('click', function() {
            const sidebarMenu = document.querySelector('.sidebar-menu');
            const dashboardContainer = document.querySelector('.dashboard-container');
            const headerContainer = document.querySelector('.header-container');

            // Toggle the 'collapsed' class on the sidebar
            sidebarMenu.classList.toggle('collapsed');

            // Toggle a class to adjust the margin of the dashboard container
            dashboardContainer.classList.toggle('collapsed');
            headerContainer.classList.toggle('collapsed');

            // Toggle icon direction
            const toggleIcon = this.querySelector('i');
            if (sidebarMenu.classList.contains('collapsed')) {
                toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
            } else {
                toggleIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
            }
        });

        // BIG CALENDAR
        const bigCalendar = document.getElementById("big-calendar");
        const currentMonthYear = document.getElementById("current-month-year");

        let currentDate = new Date();
        const today = new Date(); // Current date to compare

        // Example events data with dates in "YYYY-MM-DD" format
        const events = {
            "2024-11-20": "Team Meeting",
            "2024-11-25": "Conference",
        };

        function renderCalendar(date) {
            const month = date.getMonth();
            const year = date.getFullYear();
            currentMonthYear.textContent = `${date.toLocaleString("default", { month: "long" })} ${year}`;

            // Clear previous calendar
            bigCalendar.innerHTML = "";

            // Create days of the week header
            const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement("div");
                dayHeader.className = "day-header";
                dayHeader.textContent = day;
                bigCalendar.appendChild(dayHeader);
            });

            // Get first day of the month and total days in month
            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Fill in blanks for days before the first day
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDiv = document.createElement("div");
                bigCalendar.appendChild(emptyDiv);
            }

            // Add actual days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement("div");
                dayDiv.className = "day";
                dayDiv.textContent = day;

                // Check if this day is today
                const isToday = (
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                );
                if (isToday) {
                    dayDiv.classList.add("today");
                }

                // Check if there's an event on this date
                const dateString = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
                if (events[dateString]) {
                    const eventIndicator = document.createElement("span");
                    eventIndicator.className = "event-indicator";
                    dayDiv.appendChild(eventIndicator);

                    // Optional: add tooltip or alert with event details on hover/click
                    dayDiv.title = events[dateString];
                }

                bigCalendar.appendChild(dayDiv);
            }
        }

        // Event listeners for navigation buttons
        document.getElementById("prev-month").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        document.getElementById("next-month").addEventListener("click", () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        document.getElementById("prev-year").addEventListener("click", () => {
            currentDate.setFullYear(currentDate.getFullYear() - 1);
            renderCalendar(currentDate);
        });

        document.getElementById("next-year").addEventListener("click", () => {
            currentDate.setFullYear(currentDate.getFullYear() + 1);
            renderCalendar(currentDate);
        });

        // Initial render
        renderCalendar(currentDate);
    </script>


</body>

</html>