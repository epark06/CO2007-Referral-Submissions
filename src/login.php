<?php
session_start();

$users = [
    'gharrison' => ['password' => 'Password', 'role' => 'patient'],
    'jdoe'      => ['password' => 'Password', 'role' => 'practitioner'],
    'mthompson' => ['password' => 'Password', 'role' => 'referring manager'],
    'dxu'       => ['password' => 'Password', 'role' => 'admin']
];

$error = "";
$accessDenied = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $role = $users[$username]['role'];
        // Authorization Logic
        if ($role === 'referring manager' || $role === 'admin') {
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $role;
            header("Location: referralForm.php");
            exit;
        } else {
            $accessDenied = true;
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Matters - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="main-header">
    <div class="header-content">
        <img src="img/health-matters.svg" alt="Health Matters Logo" class="header-logo">
        <h1 class="header-title">Health Matters</h1>
    </div>
</header>

<div class="container">
    <?php if ($accessDenied): ?>
        <div class="error-banner" style="display: flex; flex-direction: column; align-items: flex-start;">
            <strong>Access Denied</strong>
            <p>Your account level does not have permission to access the referral system. Please contact your system administrator.</p>
            <a href="login.php" style="color: var(--error); font-weight: bold;">Try Again</a>
        </div>
    <?php else: ?>
        <h2>Staff Login</h2>
        
        <?php if ($error): ?>
            <div class="error" style="text-align: center; margin-bottom: 15px;"><?= $error ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>