<?php
session_start();
include 'dbinit.php';

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the cart items for the user
$user_id = $_SESSION['user_id'];
$query = "SELECT products.name, products.price, cart.quantity 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Handle checkout process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($clear_cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the success page
    $_SESSION['purchase_success'] = true;
    header('Location: success.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><b>E-Commerce</b></a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center my-4">Checkout</h1>

        <?php if (count($cart_items) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-price mb-4">
                <p>Total Price: $<?= number_format($total_price, 2) ?></p>
            </div>

            <form action="checkout.php" method="POST">
                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg">Complete Purchase</button>
                </div>
            </form>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>