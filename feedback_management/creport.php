<!DOCTYPE html>
<html>
<head>
    <title>Course Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/creport.css">
</head>
<body class="body">
    <!-------Nav bar section-->
    <nav class="navbar navbar-expand-sm navbar-dark container nav" style="background-color:#07075ca7;height: 80px;">
        <div class="container-fluid">
            <a href="#" class="navbar-brand ">
                <img src="icon/logo.jpg" height="70" alt="CoolBrand">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav row ">
                    <li class="nav-item col-1 ms-2 ">
                        <a class="nav-link font" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item dropdown col-3  ">
                        <a class="nav-link dropdown-toggle font" href="#" role="button" data-bs-toggle="dropdown"  style="text-align: center;">USER MANAGEMENT</a>
                        <ul class="dropdown-menu" style="background-color: rgba(96, 96, 153, 0.645);">
                            <li><a class="dropdown-item" href="addstudent.php">ADD NEW STUDENT</a></li>
                            <li><a class="dropdown-item" href="addcourse.php">ADD NEW COURSE</a></li>
                            <li><a class="dropdown-item" href="addlecture.php">ADD NEW LECTURE</a></li>
                            <li><a class="dropdown-item" href="addmanager.php">ADD NEW MANAGEMENT ASSISTANT</a></li>
                            <li><a class="dropdown-item" href="sreport.php">STUDENT REPORT</a></li>
                            <li><a class="dropdown-item" href="creport.php">COURSE REPORT</a></li>
                            <li><a class="dropdown-item" href="lecreport.php">LECTURE REPORT</a></li>
                            <li><a class="dropdown-item" href="mreport.php">MANAGEMENT ASSISTANT REPORT</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown col-3  ">
                        <a class="nav-link dropdown-toggle font" href="#" role="button" data-bs-toggle="dropdown">FEEDBACK MANAGEMENT </a>
                        <ul class="dropdown-menu" style="background-color: rgba(96, 96, 153, 0.855);">
                            <li><a class="dropdown-item" href="coursefeedback.php">COURSE FEEDBACK</a></li>
                            <li><a class="dropdown-item" href="lecfeedback.php">LECTURE FEEDBACK</a></li>
                        </ul>
                    </li>
                    <li class="nav-item col-3 ms-3 " >
                        <a class="nav-link font" href="changepsw.php" style="text-align: center;">CHANGE PASSWORD</a>
                    </li>
                    <li class="nav-item col-1 ">
                        <a class="nav-link font" href="home.php">LOGOUT</a>
                    </li>
                </ul>
            </div>
        </div>
        <form class="d-flex col-3" method="post" action="">
            <input class="form-control me-2" type="search" placeholder="Search by semester/Depar/Credit" name="search" aria-label="Search">
            <button type="submit" class="btn btn-outline-dark me-3" name="submit">Search</button>
        </form>
    </nav>

    <div class="container border" style="background-color: rgba(123, 115, 231, 0.075);padding-bottom: 20px;">  
        <div>
            <h1 class="cont-title container">REPORT OF COURSES</h1>
        </div>

        <!-------table  section-->
        <div class="container con">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Course ID</th>
                                <th scope="col">Course Name</th>
                                <th scope="col">Course Code</th>
                                <th scope="col">Department</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $database = "student_feedback_management_system";

                            // Create connection
                            $connection = new mysqli($servername, $username, $password, $database);

                            // Check connection
                            if ($connection->connect_error) {
                                die("Connection failed: " . $connection->connect_error);
                            }

                            if (isset($_POST['submit']) && !empty($_POST['search'])) {
                                $search = $_POST['search'];
                                // Protect against SQL injection
                                $search = $connection->real_escape_string($search);

                                // Query to search for courses by semester
                                $sql = "SELECT Course_Id, Course_Name, Course_Code, Department, Credits, Semester FROM course WHERE Semester LIKE '%$search%' OR Department LIKE '%$search%' OR Credits LIKE '%$search%' OR Course_Code LIKE '%$search%'";
                            }
                            else 
                            {
                                // Default query to display all courses
                                $sql = "SELECT Course_Id, Course_Name, Course_Code, Department, Credits, Semester FROM course";
                            }

                            $result = $connection->query($sql);

                            if ($result->num_rows > 0) {
                                // Fetch and display the results
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['Course_Id']}</td>
                                        <td>{$row['Course_Name']}</td>
                                        <td>{$row['Course_Code']}</td>
                                        <td>{$row['Department']}</td>
                                        <td>{$row['Credits']}</td>
                                        <td>{$row['Semester']}</td>
                                        <td>
                                            <a class='btn btn-success' href='editcourse.php?id={$row['Course_Id']}'>Edit</a>
                                            <a class='btn btn-danger' href='deletecourse.php?id={$row['Course_Id']}' onclick='return confirmDelete();'>Delete</a>
                                        </td>
                                    </tr>
                                    ";
                                }
                            } 

                            else 
                            {
                                echo "<tr><td colspan='7' class='text-danger text-center'>No results found.</td></tr>";
                         
                            }

                            // Close connection
                            $connection->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
    </div> 

    <script type="text/javascript" src="js/delete.js"></script>

</body>
</html>
