<?php
// establish database
include 'conn.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // prevent sql injection
    $stmt = $conn->prepare("SELECT user_id, username, password FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {

        $stmt->bind_result($dbuser_id, $dbUsername, $dbPassword);
        $stmt->fetch();
        // Verify the password
        if ($dbUsername && password_verify($password, $dbPassword)) {
            // Set session variable 
            $_SESSION["user_id"] = $dbuser_id;
            header("Location: index.php");
            exit();
        } else {
            $loginError = "Invalid username or password";
        }
    } else {
        die("Error: Unable to execute the query");
    }

    $stmt->close();
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Login</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($loginError)) {
                            echo "<p class='text-danger'>$loginError</p>";
                        }
                        ?>

                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="d-grid mt-2">
                                <a href="register.php" class="btn btn-primary">Register</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
