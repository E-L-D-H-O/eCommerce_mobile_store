<?php
session_start();
include 'dbinit.php';

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set session and redirect to homepage
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .login-sidebar {
            background-color: #343a40;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px;
        }

        .login-sidebar h1 {
            font-size: 36px;
            font-weight: bold;
        }

        .login-sidebar p {
            font-size: 16px;
        }

        .form-container {
            padding: 30px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .btn-primary {
            width: 100%;
        }

        .form-label {
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .login-sidebar {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container login-container mt-5">
        <div class="row">
            <!-- Sidebar with Welcome Message -->
            <div class="col-md-6 d-none d-md-flex login-sidebar">
                <div>
                    <h1>Welcome Back!</h1>
                    <p>Login to your account and continue your shopping journey.</p>
                </div>
            </div>

            <!-- Login Form -->
            <div class="col-md-6 col-sm-12 form-container">
                <h2 class="text-center mb-4">Login</h2>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>

                <div class="text-center mt-3">
                    <span>Don't have an account? <a href="signup.php">Sign up here</a></span>
                </div>

                <!-- Error message -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mt-3"><?= $error ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>