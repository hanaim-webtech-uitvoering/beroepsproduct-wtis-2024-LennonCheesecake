<?php
require_once 'db_connection.php'; // Verbind met de database
require_once 'sanitizeS.php';      // Functie om invoer te schonen

session_start(); // Start een sessie voor ingelogde gebruikers

$melding = ''; // Voor fout- of succesmeldingen

// Functie om gebruiker op te halen uit de database
function haalGebruikerOp($db, $gebruikersnaam) {
    $sql = "SELECT * FROM [Users] WHERE username = :gebruikersnaam";
    $stmt = $db->prepare($sql);
    $stmt->execute(['gebruikersnaam' => $gebruikersnaam]);
    return $stmt->fetch();
}

// Functie om login te verwerken
function verwerkLogin($db, $gebruikersnaam, $wachtwoord) {
    $user = haalGebruikerOp($db, $gebruikersnaam);
    if ($user && password_verify($wachtwoord, $user['password'])) {
        if ($user['role'] === 'Medewerker') {
            // Medewerkers moeten via hun eigen login inloggen
            $_SESSION['melding'] = 'Medewerkers moeten inloggen via de medewerker login.';
            header('Location: medewerker-login.php');
            exit;
        }
        // Inloggen gelukt, sla info op in sessie
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: profiel.php');
        exit;
    } else {
        // Foutmelding bij ongeldige inlog
        return 'Ongeldige gebruikersnaam of wachtwoord.';
    }
    return '';
}

// Verwerk het inlogformulier als er is gepost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal en schoon de invoer op
    $gebruikersnaam = sanitize($_POST['gebruikersnaam']);
    $wachtwoord = sanitize($_POST['wachtwoord']);

    // Maak verbinding en verwerk login
    $db = maakVerbinding();
    $melding = verwerkLogin($db, $gebruikersnaam, $wachtwoord);
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Inloggen</title>
</head>

<body>
    <div class="login">
        <div class="container">
            <header>
                <h1>Sole Machina</h1>
            </header>

            <main>
                <h2>Inloggen</h2>

                <!-- Toon foutmelding indien aanwezig -->
                <div class="foutmelding">
                    <?php if (!empty($melding)) : ?>
                        <p><?= htmlspecialchars($melding) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Inlogformulier -->
                <form action="login.php" method="post">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="gebruikersnaam" id="gebruikersnaam" minlength="4" required value="<?= isset($gebruikersnaam) ? htmlspecialchars($gebruikersnaam) : '' ?>">

                    <label for="wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" id="wachtwoord" minlength="6" required>

                    <input class="submit" id="inloggen" type="submit" value="Inloggen">
                </form>

                <!-- Navigatieknoppen -->
                <button onclick="location.href='registreren.php'">Nog geen account? Registreer hier</button>
                <button onclick="location.href='index.php'">Doorgaan als gast</button>
                <button onclick="location.href='medewerker-login.php'">Medewerker login</button>
            </main>
        </div>
    </div>
</body>

</html>