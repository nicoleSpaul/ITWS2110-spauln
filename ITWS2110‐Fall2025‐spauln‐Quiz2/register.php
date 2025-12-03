<?php
session_start();

require_once 'dbConnect.php';

$message = '';

if (isset($_POST['register']) && $_POST['register'] == 'Register') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $nickName = trim($_POST['nickName']);
    $rawPassword = $_POST['password'];

    // Simple validation (can be expanded)
    if (empty($firstName) || empty($lastName) || empty($rawPassword)) {
        $message = 'All fields except Nickname are required.';
    } else {
        try {
            //generate Salt and hash password
            // Generate unique salt
            $salt = bin2hex(random_bytes(32)); 
            
            //hash password with the salt
            $passwordHash = hash('sha256', $salt . $rawPassword);

            //insert User into Database
            $stmt = $dbconn->prepare("
                INSERT INTO users (firstName, lastName, nickName, passwordHash, salt)
                VALUES (:firstName, :lastName, :nickName, :passwordHash, :salt)
            ");
            
            $stmt->execute([
                ':firstName' => $firstName,
                ':lastName' => $lastName,
                ':nickName' => $nickName,
                ':passwordHash' => $passwordHash,
                ':salt' => $salt
            ]);

            //edirect to index.php
            $newUserId = $dbconn->lastInsertId();

            $_SESSION['userId'] = $newUserId;
            $_SESSION['nickName'] = $nickName; 
            header("Location: index.php");
            exit();

        } catch (Exception $e) {
            $message = "Registration failed. Error: " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register New User</h1>
    <?php if ($message) echo "<p style='color: red;'>$message</p>"; ?>
    
    <form method="post" action="register.php">
        <label for="firstName">First Name: </label>
        <input type="text" name="firstName" required /><br/>

        <label for="lastName">Last Name: </label>
        <input type="text" name="lastName" required /><br/>

        <label for="nickName">Nickname (Optional): </label>
        <input type="text" name="nickName" /><br/>

        <label for="password">Password: </label>
        <input type="password" name="password" required /><br/>

        <input name="register" type="submit" value="Register" />
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>