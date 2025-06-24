<?php
session_start();
include "db.php";

$login_error = "";
$registration_success = "";

// ---------- LOGIN HANDLER ----------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_name = mysqli_real_escape_string($conn, $_POST["login_name"]);
    $login_pass = mysqli_real_escape_string($conn, $_POST["login_pass"]);

    $sql = "SELECT * FROM user_regi WHERE name='$login_name' AND pass='$login_pass' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
       // $_SESSION["username"] = $login_name;
        header("Location: request.php");
        exit;
    } else {
        $login_error = "❌ Invalid username or password.";
    }
}

// ---------- REGISTRATION HANDLER ----------
/*if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name       = mysqli_real_escape_string($conn, $_POST["name"]);
    $email      = mysqli_real_escape_string($conn, $_POST["email"]);
    $password   = mysqli_real_escape_string($conn, $_POST["pass"]);
    $cPassword  = mysqli_real_escape_string($conn, $_POST["cPassword"]);
    $location   = mysqli_real_escape_string($conn, $_POST["location"]);
    $postalCode = mysqli_real_escape_string($conn, $_POST["pCode"]);
    $city       = mysqli_real_escape_string($conn, $_POST["city"]);
    $terms      = isset($_POST["terms"]) ? 1 : 0;

    if ($password !== $cPassword) {
        $registration_success = "<span style='color: red;'>❌ Passwords do not match.</span>";
    } else {
        $sql = "INSERT INTO user_regi (name, email, pass, cpassword, location, pCode, city, terms)
                VALUES ('$name', '$email', '$password', '$cPassword', '$location', '$postalCode', '$city', '$terms')";
        if (mysqli_query($conn, $sql)) {
            $registration_success = "<span style='color: green;'>✅ Registration successful! Redirecting...</span>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'process.php';
                }, 2000);
            </script>";
        } else {
            $registration_success = "<span style='color: red;'>❌ Error: " . mysqli_error($conn) . "</span>";
        }
    }
}*/
$bgColor = "";
if (isset($_COOKIE['cancelBG']) && $_COOKIE['cancelBG'] == "1") {
    $bgColor = "background-color: #ffdddd;"; // Light red for cancel
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AIUB</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>" />
  </head>
  <body class="pbody" style="<?= $bgColor ?>">
    <!-- Logo and Title -->
    <div class="header">
      <img
        src="https://upload.wikimedia.org/wikipedia/en/thumb/8/8c/American_International_University-Bangladesh_Monogram.svg/1200px-American_International_University-Bangladesh_Monogram.svg.png"
        alt="AIUB Logo"
        class="logo"
      />
      <h1>AIUB</h1>
    </div>

    <!-- Centering Boxes -->
    <div class="container">
      <div class="column">
        <div class="box box1">
          <table class="box1 table" style="width: 100%">
            <thead>
              <tr>
                <th>Select</th>
                <th>Country</th>
                <th>City</th>
                <!-- <th>AQI</th> -->
              </tr>
            </thead>
            <tbody>
              <!-- (your city checkboxes rows here, unchanged) -->
              <tr><td><input type="checkbox" name="selectedCities" value="Dhaka" /></td><td>Bangladesh</td><td>Dhaka</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Barisal" /></td><td>Bangladesh</td><td>Barisal</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Sylhet" /></td><td>Bangladesh</td><td>Sylhet</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Khulna" /></td><td>Bangladesh</td><td>Khulna</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Chittagong" /></td><td>Bangladesh</td><td>Chittagong</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Rangpur" /></td><td>Bangladesh</td><td>Rangpur</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Mymensingh" /></td><td>Bangladesh</td><td>Mymensingh</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Rajshahi" /></td><td>Bangladesh</td><td>Rajshahi</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Gazipur" /></td><td>Bangladesh</td><td>Gazipur</td></tr>
              <tr><td><input type="checkbox" name="selectedCities" value="Feni" /></td><td>Bangladesh</td><td>Feni</td></tr>
              <!-- Add more rows as needed -->
            </tbody>
          </table>
        </div>
        <div class="box box2"></div>
      </div>

      <div class="column">
        <div class="box box3">
          <form
            id="registrationForm"
            action="process.php"
            method="post"
           onsubmit="return validateForm()"
          >
            <h3 style="text-align: center">Registration</h3>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" /><br /><br />
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" /><br /><br />
            <label for="pass">Password</label>
            <input type="password" id="pass" name="pass" /><br /><br />
            <label for="cPassword">Confirm Password</label>
            <input type="password" id="cPassword" name="cPassword" /><br /><br />
            <label for="location">Location</label>
            <input type="text" id="location" name="location" /><br /><br />
            <label for="pCode">Postal Code</label>
            <input type="number" id="pCode" name="pCode" /><br /><br />
            <label for="city">Preferred City</label>
            <select id="city" name="city">
              <option value="dha">Dhaka</option>
              <option value="ctg">Chattogram</option>
              <option value="raj">Rajshahi</option>
              <option value="khu">Khulna</option>
              <option value="syl">Sylhet</option>
              <option value="bar">Barishal</option>
              <option value="rang">Rangpur</option>
              <option value="mym">Mymenshing</option>
            </select>
            <br /><br /><br />
            <input type="checkbox" id="terms" name="terms" />
            <label for="terms">I agree to the terms and conditions</label><br />
            <button type="submit" name="register">Sign Up</button>
            <div id="messageBox" class="error"></div>
          </form>
        </div>

        <div class="box box4">
          <?php
          if (!empty($login_error)) {
              echo "<p style='color: red;'>$login_error</p>";
          }
          // // if (!empty($login_success)) {
          // //     echo "<p style='color: green;'>$login_success</p>";
          // }
          // if (isset($_SESSION['username'])) {
          //     echo "<p style='color: green;'>Logged in as: " . htmlspecialchars($_SESSION['username']) . "</p>";
          // }
          ?>
          <form id="login_form" class="login" method="post">
            <label for="login_name">Username:</label>
            <input type="text" id="login_name" name="login_name" /><br /><br />
            <label for="login_pass">Password:</label>
            <input type="password" id="login_pass" name="login_pass" /><br /><br />
            <button type="submit" name="login">Login</button>
          </form>
        </div>
      </div>
    </div>

    <script src="script.js"></script>
    <?php
//session_unset();
//session_destroy();
?>
  </body>
</html>
