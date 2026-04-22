<?php
// Debug script to check login issues
include "config.php";

echo "<h2>Debug: User Login Check</h2>";

if (isset($_POST['test_login'])) {
    $username = trim($_POST['username']);
    $ic_number = trim($_POST['ic_number']);
    $password = $_POST['password'];
    
    echo "<strong>Input:</strong><br>";
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "IC Number: " . htmlspecialchars($ic_number) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br><br>";
    
    // Check if user exists
    $stmt = mysqli_prepare($conn, "SELECT username, ic_number, password, status_akaun, role FROM users WHERE username = ? AND ic_number = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $ic_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<strong>User Found in Database:</strong><br>";
        echo "Username: " . htmlspecialchars($row['username']) . "<br>";
        echo "IC Number: " . htmlspecialchars($row['ic_number']) . "<br>";
        echo "Status: " . htmlspecialchars($row['status_akaun']) . "<br>";
        echo "Role: " . htmlspecialchars($row['role']) . "<br>";
        echo "Password Hash: " . substr($row['password'], 0, 30) . "...<br><br>";
        
        // Check password
        if (password_verify($password, $row['password'])) {
            echo "✅ <strong>Password is CORRECT</strong><br>";
        } else {
            echo "❌ <strong>Password is WRONG</strong><br>";
        }
        
        // Check status
        if ($row['status_akaun'] === 'aktif') {
            echo "✅ <strong>Status is AKTIF</strong><br>";
        } else {
            echo "❌ <strong>Status is: " . $row['status_akaun'] . "</strong><br>";
        }
    } else {
        echo "❌ <strong>User NOT FOUND</strong> with that username AND IC number combination.<br><br>";
        
        // Check if username exists (but IC doesn't match)
        $stmt2 = mysqli_prepare($conn, "SELECT username, ic_number FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt2, "s", $username);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        
        if ($row2 = mysqli_fetch_assoc($result2)) {
            echo "But username exists with IC: " . htmlspecialchars($row2['ic_number']) . "<br>";
            echo "<em>Make sure you enter the exact IC number!</em>";
        }
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="text" name="ic_number" placeholder="Nombor IC" required><br>
    <input type="password" name="password" placeholder="Kata Laluan" required><br>
    <button type="submit" name="test_login">Test Login</button>
</form>

<h3>All Users in Database:</h3>
<?php
$result = mysqli_query($conn, "SELECT username, ic_number, status_akaun, role FROM users");
echo "<table border='1'>";
echo "<tr><th>Username</th><th>IC Number</th><th>Status</th><th>Role</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td>" . htmlspecialchars($row['ic_number']) . "</td>";
    echo "<td>" . htmlspecialchars($row['status_akaun']) . "</td>";
    echo "<td>" . htmlspecialchars($row['role']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>