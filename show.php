<?php
// ✅ Handle logout
if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

session_start();
include "db.php";

// ✅ Check if cities were selected
if (!isset($_SESSION['selectedCities']) || count($_SESSION['selectedCities']) === 0) {
    echo "<h3 style='color: red; text-align:center;'>❌ No cities selected. Please go back and select some cities.</h3>";
    exit;
}

$selectedCities = $_SESSION['selectedCities'];

// ✅ Prepare SQL with placeholders
$placeholders = implode(',', array_fill(0, count($selectedCities), '?'));
$sql = "SELECT city_name, aqi FROM aqi_table WHERE city_name IN ($placeholders)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo "<h2 style='color: red; text-align:center;'>❌ SQL Error: " . mysqli_error($conn) . "</h2>";
    exit;
}

// ✅ Bind parameters dynamically
$types = str_repeat('s', count($selectedCities));
$refs = [];
foreach ($selectedCities as $key => $value) {
    $refs[$key] = &$selectedCities[$key];
}
mysqli_stmt_bind_param($stmt, $types, ...$refs);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Selected Cities & AQI</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            margin: 30px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #2a7ae2;
            margin-bottom: 25px;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 700px;
            margin: 0 auto 40px auto;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 18px;
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
            font-size: 16px;
        }
        th {
            background-color: #2a7ae2;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:hover {
            background-color: #f1f7ff;
        }
        @media (max-width: 600px) {
            table {
                width: 100%;
                font-size: 14px;
            }
            th, td {
                padding: 10px;
            }
        }
        /* Logout button style */
        .logout-container {
            text-align: right;
            margin-bottom: 20px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-family: Arial, sans-serif;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #d73833;
        }
    </style>
</head>
<body>

<h2>✅ Selected Cities and Their Air Quality Index (AQI)</h2>

<table>
    <thead>
        <tr>
            <th>City</th>
            <th>AQI</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['city_name']) ?></td>
                <td><?= htmlspecialchars($row['aqi']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="logout-container">
    <a href="show.php?logout=true" class="logout-btn">Logout</a>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
</body>
</html>
