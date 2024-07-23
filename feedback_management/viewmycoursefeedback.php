<?php
session_start();

// Assuming the user is logged in and the username is stored in the session
if (!isset($_SESSION['User_Name'])) 
{
    die("User not logged in.");
}

$logged_in_user = $_SESSION['User_Name'];

$servername = "localhost";
$username = "root";
$password = "";
$database = "student_feedback_management_system";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) 
{
    die("Connection error: " . $connection->connect_error);
}

// Default query to display all feedbacks for the logged-in user
$sql = "SELECT Id, Semester, Course_Name, Course_Code, Date_Time FROM course_feedback WHERE User_Name = ?";
$params = [$logged_in_user];

if (isset($_POST['submit']) && !empty($_POST['search'])) {
    $search = $_POST['search'];
    // Protect against SQL injection
    $search = "%" . $connection->real_escape_string($search) . "%";

    // Query to search for feedbacks by semester, course name, or course code for the logged-in user
    $sql = "SELECT Id, Semester, Course_Name, Course_Code, Date_Time FROM course_feedback 
            WHERE User_Name = ? AND (Semester LIKE ? OR Course_Name LIKE ? OR Course_Code LIKE ?)";
    $params = [$logged_in_user, $search, $search, $search];
    
    // Store the search query in the session to persist it across the redirect
    $_SESSION['search_query'] = $sql;
    $_SESSION['search_params'] = $params;
    
    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}



// Update the query if the search form is when empty input submitted
else if (isset($_POST['submit']) && empty($_POST['search']))
{
    $sql = "SELECT Id, Semester, Course_Name, Course_Code, Date_Time FROM course_feedback WHERE User_Name = ?";
    $params = [$logged_in_user];
        // Store the search query in the session to persist it across the redirect
        $_SESSION['search_query'] = $sql;
        $_SESSION['search_params'] = $params;
        
        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
}




// Check if there's a search query stored in the session
if (isset($_SESSION['search_query']) && isset($_SESSION['search_params'])) {
    $sql = $_SESSION['search_query'];
    $params = $_SESSION['search_params'];
    // Clear the session variables
    unset($_SESSION['search_query']);
    unset($_SESSION['search_params']);
}

$stmt = $connection->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Invalid query: " . $connection->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View My Course Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6oIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/viewcoursefeedback.css">

    

</head>
<body class="body">
    <!-- Nav bar section -->
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
                        <a class="nav-link font" href="index1.php">HOME</a>
                    </li>
                    <li class="nav-item dropdown col-3">
                        <a class="nav-link dropdown-toggle font" href="#" role="button" data-bs-toggle="dropdown" style="text-align: center;">ADD FEEDBACK</a>
                        <ul class="dropdown-menu" style="background-color: rgba(96, 96, 153, 0.645);">
                            <li><a class="dropdown-item" href="addcoufeedback.php">ADD COURSE FEEDBACK</a></li>
                            <li><a class="dropdown-item" href="addlecfeedback.php">ADD LECTURE FEEDBACK</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown col-3">
                        <a class="nav-link dropdown-toggle font" href="#" role="button" data-bs-toggle="dropdown">VIEW MY FEEDBACK</a>
                        <ul class="dropdown-menu" style="background-color: rgba(96, 96, 153, 0.645);">
                            <li><a class="dropdown-item" href="viewmycoursefeedback.php">VIEW MY COURSE FEEDBACK</a></li>
                            <li><a class="dropdown-item" href="viewmylecfeedback.php">VIEW MY LECTURE FEEDBACK</a></li>
                        </ul>
                    </li>
                    <li class="nav-item col-3 ms-3">
                        <a class="nav-link font" href="changepsw1.php" style="text-align: center;">CHANGE PASSWORD</a>
                    </li>
                    <li class="nav-item col-1">
                        <a class="nav-link font" href="home.php">LOGOUT</a>
                    </li>
                </ul>
            </div>
            <form class="d-flex col-3" method="post" action="">
                <input class="form-control me-2" type="search" placeholder="Search by C_Code/C_Name/Sem" name="search" aria-label="Search">
                <button type="submit" class="btn btn-outline-dark me-3" name="submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="container border" style="background-color: rgba(123, 115, 231, 0.06);padding-bottom: 20px;">    
        <!-- Feedback section -->
        <h1 style="text-align: center; font-size: 50px; color: #8a9abf; padding-top: 50px;">VIEW MY COURSE EVALUATION</h1>
        <p class="font" style="text-align: justify;">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam quo voluptatum perspiciatis placeat, excepturi eos sapiente, magni cumque dolores autem earum quis sint, inventore quia iure recusandae quibusdam eveniet. Aspernatur! Esse nobis iste unde, nemo excepturi voluptatibus laborum ut dolorum dicta, non ipsam quidem aspernatur eveniet quibusdam, nisi iure consectetur libero placeat quas eaque! Harum sint porro corrupti tempore a?</p>
      
        <div class="container con">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Semester</th>
                                <th scope="col">Course Name</th>
                                <th scope="col">Course Unit</th>
                                <th scope="col">Date/Time</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                // Fetch and display the results
                                while ($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['Semester']}</td>
                                        <td>{$row['Course_Name']}</td>
                                        <td>{$row['Course_Code']}</td>
                                        <td>{$row['Date_Time']}</td>
                                        <td>
                                            <a class='btn btn-primary' href='viewcoursefeedback1.php?id={$row['Id']}'>View</a>
                                            <a class='btn btn-success' href='editcoufeedback.php?id={$row['Id']}'>Edit</a>
                                            <a class='btn btn-danger col-3' href='deletecoufeedback1.php?id=$row[Id]' onclick='return confirmDelete();'>Delete</a>
                                        </td>
                                    </tr>
                                    ";
                                }
                            } 
                            
                            else
                            {
                                echo "<tr><td colspan='5' class='text-danger text-center'>No results found.</td></tr>";
                            }
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

<?php
// Close connection
$connection->close();
?>