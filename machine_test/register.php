<?php
//establish database connection
include 'conn.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check existing username 
    $checkStmt = $conn->prepare("SELECT user_id FROM user WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $registrationError = "Username already exists. Please choose a different one.";
    } else {
        $insertStmt = $conn->prepare("INSERT INTO user (username, password, entry_date) VALUES (?, ?, NOW())");
        $insertStmt->bind_param("ss", $username, $hashedPassword);

        //check registration status
        if ($insertStmt->execute()) {
            $_SESSION["user_id"] = $insertStmt->insert_id;
            header("Location: index.php");
            exit();
        } else {
            $registrationError = "Failed to register user";
        }
        $insertStmt->close();
    }
    $checkStmt->close();
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
                        <h2 class="text-center">User Register</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($registrationError)) {
                            echo "<p class='text-danger'>$registrationError</p>";
                        }
                        ?>

                        <form action="register.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                        <div class="d-grid mt-2">
                                <a href="login.php" class="btn btn-primary">Login</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
</body>
</html>
