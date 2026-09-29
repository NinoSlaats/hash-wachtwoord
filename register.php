<?php
session_start();
$melding = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = trim($_POST['email'] ?? '');
        $wachtwoord = $_POST['wachtwoord'] ?? '';
        $wachtwoord_check = $_POST['wachtwoord_check'] ?? '';

        // Validatie: lege invoer
        if (empty($email) || empty($wachtwoord) || empty($wachtwoord_check)) {
            throw new Exception("Alle velden zijn verplicht.");
        }

        // Validatie: e-mail formaat
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Ongeldig e-mailadres.");
        }

        // Validatie: dubbele wachtwoorden komen overeen
        if ($wachtwoord !== $wachtwoord_check) {
            throw new Exception("De ingevoerde wachtwoorden komen niet overeen.");
        }

        // Simuleer opslag / controle van dubbele gegevens (bijv. in sessie of bestand)
        if (isset($_SESSION['gebruikers']) && array_key_exists($email, $_SESSION['gebruikers'])) {
            throw new Exception("Dit e-mailadres is al geregistreerd.");
        }

        // Wachtwoord hashen met password_hash (gebruikt automatisch een veilige, unieke salt)
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        if ($hash === false) {
            throw new Exception("Het hashen van het wachtwoord is mislukt.");
        }

        // Opslaan in sessie (voor deze opdracht als database alternatief)
        $_SESSION['gebruikers'][$email] = $hash;
        
        // Doorsturen naar login pagina na succes
        $_SESSION['success'] = "Registratie gelukt! Je kunt nu inloggen.";
        header("Location: login.php");
        exit();

    } catch (Exception $e) {
        $melding = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Registreren</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f4f9; }
        .box { background: white; padding: 20px; border-radius: 8px; width: 350px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box; }
        button { background: #28a745; color: white; padding: 10px; border: none; width: 100%; border-radius: 4px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Registreren</h2>
        <?php if (!empty($melding)) echo "<p class='error'>$melding</p>"; ?>
        <form method="POST">
            <label>E-mailadres:</label>
            <input type="email" name="email" required>
            
            <label>Wachtwoord:</label>
            <input type="password" name="wachtwoord" required>
            
            <label>Herhaal Wachtwoord:</label>
            <input type="password" name="wachtwoord_check" required>
            
            <button type="submit">Registreren</button>
        </form>
        <p><a href="login.php">Al een account? Log hier in</a></p>
    </div>
</body>
</html>
