<?php
session_start();

$error = "";
$selected = [];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle city selection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selectedCities'])) {
        $selected = $_POST['selectedCities'];
        if (count($selected) > 10) {
            $error = "❌ Please select 10 cities or fewer.";
        } else {
            $_SESSION['selectedCities'] = $selected;
            header("Location: show.php");
            exit();
        }
    }
} else {
    // If session exists, prefill selected
    if (isset($_SESSION['selectedCities'])) {
        $selected = $_SESSION['selectedCities'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Select Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 25px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 2px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9fbfd;
        }
        .error {
            color: #d93025;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        input[type="submit"], .logout-btn {
            background-color: #007BFF;
            border: none;
            color: white;
            padding: 12px 25px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 15px 5px 0 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover, .logout-btn:hover {
            background-color: #0056b3;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select up to 10 Cities</h2>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Country</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $cities = [
                    "Dhaka", "Barisal", "Sylhet", "Khulna", "Chittagong", "Rangpur", "Mymensingh", "Rajshahi", "Gazipur", "Feni",
                    "Narayanganj", "Comilla", "Noakhali", "Jessore", "Pabna", "Bogura", "Tangail", "Kushtia", "Jamalpur", "Dinajpur"
                ];
                foreach ($cities as $city) {
                    $isChecked = in_array($city, $selected) ? "checked" : "";
                    echo "<tr>
                            <td><input type='checkbox' name='selectedCities[]' value='$city' $isChecked></td>
                            <td>Bangladesh</td>
                            <td>$city</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

        <div class="button-container">
            <input type="submit" name="SUBMIT" value="Submit" />
            <?php if (isset($_SESSION['selectedCities'])): ?>
                <a href="?logout=true" class="logout-btn">Logout</a>
            <?php endif; ?>
        </div>
    </form>
</div>
<?php
//session_unset();
//session_destroy();
?>

</body>
</html>
