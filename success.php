<?php
session_start();

// If purchase was not successful, redirect to the index page
if (!isset($_SESSION['purchase_success'])) {
    header('Location: index.php');
    exit;
}

// Unset the purchase success flag
unset($_SESSION['purchase_success']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <h1 class="display-4 text-success">Thank You for Your Purchase!</h1>
        <p class="lead">Your order has been completed successfully.</p>
        <p>You will be redirected to the product page in a few seconds...</p>
    </div>

    <script>
        // Redirect back to the product page after 5 seconds
        setTimeout(function() {
            window.location.href = "index.php";
        }, 5000);
    </script>
</body>

</html>