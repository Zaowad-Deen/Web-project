<?php
session_start();
include "db.php";

// ---------- Cancel Action: Set cookie and wait 5s with red background ----------
if (isset($_POST['cancel_action'])) {
    setcookie("cancelBG", "1", time() + 5, "/"); // Set cookie for 5 seconds
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Canceling...</title>
        <style>
            body {
                background-color: #ffdddd;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: Arial, sans-serif;
                font-size: 22px;
                color: #b00;
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 5000);
        </script>
    </head>
    <body>
        <!-- ❌ You cancelled the registration.<br>Redirecting to homepage in 5 seconds... -->
    </body>
    </html>
    <?php
    exit();
}

// ---------- Show Confirmation Page after Registration Form ----------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name       = $_POST["name"] ?? "";
    $email      = $_POST["email"] ?? "";
    $password   = $_POST["pass"] ?? "";
    $cPassword  = $_POST["cPassword"] ?? "";
    $location   = $_POST["location"] ?? "";
    $postalCode = $_POST["pCode"] ?? "";
    $city       = $_POST["city"] ?? "";
    $terms      = isset($_POST["terms"]) ? "Agreed" : "Not Agreed";

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Confirm Registration</title>
        <style>
            body { font-family: sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
            .container {
                background: #fff; padding: 30px 40px; max-width: 600px; margin: 60px auto;
                border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h2 { text-align: center; color: #333; }
            p { font-size: 16px; margin: 8px 0; }
            button {
                padding: 10px 20px; margin-top: 20px; border: none; border-radius: 5px;
                font-size: 16px; cursor: pointer;
            }
            .confirm-btn { background-color: #28a745; color: white; }
            .cancel-btn { background-color: #dc3545; color: white; }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Confirm Your Details</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <p><strong>Password:</strong> <?= htmlspecialchars($password) ?></p>
        <p><strong>Confirm Password:</strong> <?= htmlspecialchars($cPassword) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
        <p><strong>Postal Code:</strong> <?= htmlspecialchars($postalCode) ?></p>
        <p><strong>Preferred City:</strong> <?= htmlspecialchars($city) ?></p>
        <p><strong>Terms:</strong> <?= $terms ?></p>

        <!-- Confirm Form -->
        <form method="post" action="process.php" style="display:inline-block;">
            <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="hidden" name="pass" value="<?= htmlspecialchars($password) ?>">
            <input type="hidden" name="cPassword" value="<?= htmlspecialchars($cPassword) ?>">
            <input type="hidden" name="location" value="<?= htmlspecialchars($location) ?>">
            <input type="hidden" name="pCode" value="<?= htmlspecialchars($postalCode) ?>">
            <input type="hidden" name="city" value="<?= htmlspecialchars($city) ?>">
            <input type="hidden" name="terms" value="<?= ($terms === "Agreed") ? 1 : 0 ?>">
            <button type="submit" name="confirm" class="confirm-btn">Confirm</button>
        </form>

        <!-- Cancel Button -->
        <form method="post" action="process.php" style="display:inline-block;">
            <input type="hidden" name="cancel_action" value="1">
            <button type="submit" class="cancel-btn">Cancel</button>
        </form>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// ---------- Save to Database if Confirmed ----------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $name       = mysqli_real_escape_string($conn, $_POST["name"]);
    $email      = mysqli_real_escape_string($conn, $_POST["email"]);
    $password   = mysqli_real_escape_string($conn, $_POST["pass"]);
    $cPassword  = mysqli_real_escape_string($conn, $_POST["cPassword"]);
    $location   = mysqli_real_escape_string($conn, $_POST["location"]);
    $postalCode = mysqli_real_escape_string($conn, $_POST["pCode"]);
    $city       = mysqli_real_escape_string($conn, $_POST["city"]);
    $terms      = mysqli_real_escape_string($conn, $_POST["terms"]);

    // Check duplicate email
    $check = mysqli_query($conn, "SELECT * FROM user_regi WHERE email = '$email' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        echo "<p style='color:red;'>❌ Email already registered.</p><a href='index.php'>Back</a>";
        exit();
    }

    // Password match check
    if ($password !== $cPassword) {
        echo "<p style='color:red;'>❌ Passwords do not match.</p><a href='index.php'>Back</a>";
        exit();
    }

    // Insert
    $sql = "INSERT INTO user_regi (name, email, pass, cpassword, location, pCode, city, terms)
            VALUES ('$name', '$email', '$password', '$cPassword', '$location', '$postalCode', '$city', '$terms')";
    if (mysqli_query($conn, $sql)) {
        setcookie("msgColor", "green", time() + 5, "/");
        echo "<p style='color:green;'>✅ Registration successful!</p><a href='index.php'>Go to Login</a>";
    } else {
        setcookie("msgColor", "red", time() + 5, "/");
        echo "<p style='color:red;'>❌ DB Error: " . mysqli_error($conn) . "</p><a href='index.php'>Try Again</a>";
    }
    exit();
}
?>
