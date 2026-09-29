<?php
session_start();
$melding = "";
$success = "";

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = trim($_POST['email'] ?? '');
        $wachtwoord = $_POST['wachtwoord'] ?? '';

        if (empty($email) || empty($wachtwoord)) {
            throw new Exception("Vul alle velden in om in te loggen.");
        }

        // Controleer of gebruiker bestaat
        if (!isset($_SESSION['gebruikers']) || !array_key_exists($email, $_SESSION['gebruikers'])) {
            throw new Exception("Onjuist e-mailadres of wachtwoord.");
        }

        $opgeslagenHash = $_SESSION['gebruikers'][$email];

        // Verifieer het wachtwoord
        if (password_verify($wachtwoord, $opgeslagenHash)) {
            $success = "Welkom! Je bent succesvol ingelogd.";
        } else {
            throw new Exception("Onjuist e-mailadres of wachtwoord.");
        }

    } catch (Exception $e) {
        $melding = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Inloggen</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .box { background: white; padding: 20px; border-radius: 8px; width: 350px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px; border: none; width: 100%; border-radius: 4px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Inloggen</h2>
        <?php 
            if (!empty($melding)) echo "<p class='error'>$melding</p>"; 
            if (!empty($success)) echo "<p class='success'>$success</p>"; 
        ?>
        <form method="POST">
            <label>E-mailadres:</label>
            <input type="email" name="email" required>
            
            <label>Wachtwoord:</label>
            <input type="password" name="wachtwoord" required>
            
            <button type="submit">Inloggen</button>
        </form>
        <p><a href="register.php">Geen account? Registreer hier</a></p>
    </div>
</body>
</html>
