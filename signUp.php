<?php
session_start();
include 'dbinit.php';

// Process signup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            header('Location: index.php');
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .signup-container {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }

        .signup-sidebar {
            background-color: #343a40;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 30px;
        }

        .signup-sidebar h1 {
            font-size: 36px;
            font-weight: bold;
        }

        .signup-sidebar p {
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
            .signup-sidebar {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container signup-container mt-5">
        <div class="row">
            <!-- Sidebar with Welcome Message -->
            <div class="col-md-6 d-none d-md-flex signup-sidebar">
                <div>
                    <h1>Join Us!</h1>
                    <p>Sign up and start exploring exclusive deals on our products.</p>
                </div>
            </div>

            <!-- Signup Form -->
            <div class="col-md-6 col-sm-12 form-container">
                <h2 class="text-center mb-4">Sign Up</h2>
                <form action="signup.php" method="POST" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>

                <div class="text-center mt-3">
                    <span>Already have an account? <a href="login.php">Login here</a></span>
                </div>

                <!-- Error message -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mt-3"><?= $error ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            let isValid = true;

            // Clear previous error messages
            const existingError = document.querySelector('.alert-danger');
            if (existingError) {
                existingError.remove();
            }

            // Password length check
            if (password.length < 8) {
                errorDiv.innerText = "Password must be at least 8 characters long!";
                document.querySelector('.form-container').appendChild(errorDiv);
                isValid = false;
            }

            // Check if passwords match
            if (password !== confirm_password) {
                errorDiv.innerText = "Passwords do not match!";
                document.querySelector('.form-container').appendChild(errorDiv);
                isValid = false;
            }

            return isValid;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>