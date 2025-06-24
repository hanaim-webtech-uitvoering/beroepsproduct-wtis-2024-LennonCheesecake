<?php

require_once 'db_connection.php'; // Verbind met de database
require_once 'sanitizeS.php';      // Functie om invoer te schonen

$db = maakVerbinding();            // Maak databaseverbinding
$fouten = [];                      // Array voor foutmeldingen
$foutmelding = '';                 // HTML voor foutmeldingen
$melding = '';                     // HTML voor succesmelding
$gebruikersnaam = '';
$wachtwoord = '';
$wachtwoordcheck = '';
$voornaam = '';
$achternaam = '';
$adres = '';
$hash = '';

// Controleer of het registratieformulier is verzonden
if (isset($_POST['registreren'])) {
    // Schoon alle invoervelden
    $gebruikersnaam = sanitize($_POST['gebruikersnaam']);
    $wachtwoord = sanitize($_POST['wachtwoord']);
    $wachtwoordcheck = sanitize($_POST['herhaal-wachtwoord']);
    $voornaam = sanitize($_POST['voornaam']);
    $achternaam = sanitize($_POST['achternaam']);
    $adres = sanitize($_POST['adres']);

    // Valideer gebruikersnaam
    if (strlen($gebruikersnaam) < 4) {
        $fouten[] = 'Gebruikersnaam moet minstens 4 karakters lang zijn.';
    }
    if (strlen($gebruikersnaam) > 200) {
        $fouten[] = 'Gebruikersnaam mag maximaal 200 karakters lang zijn.';
    }

    // Valideer wachtwoord
    if (empty($wachtwoord)) {
        $fouten[] = 'Wachtwoord is verplicht.';
    }
    if (strlen($wachtwoord) < 8) {
        $fouten[] = 'Wachtwoord moet minstens 8 karakters lang zijn.';
    }
    if (strlen($wachtwoord) > 50) {
        $fouten[] = 'Wachtwoord mag maximaal 50 karakters lang zijn.';
    }
    if ($wachtwoordcheck != $wachtwoord) {
        $fouten[] = 'Wachtwoorden komen niet overeen.';
    }
    // Controleer op minimaal 1 hoofdletter, kleine letter, cijfer en speciaal teken
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/';
    if (!preg_match($passwordPattern, $wachtwoord)) {
        $fouten[] = 'Wachtwoord moet minstens 1 hoofdletter, 1 kleine letter, 1 cijfer en 1 speciaal karakter bevatten.';
    }

    // Valideer voornaam
    if (empty($voornaam)) {
        $fouten[] = 'Voornaam is verplicht.';
    }
    if (strlen($voornaam) > 20) {
        $fouten[] = 'Voornaam mag maximaal 20 karakters lang zijn.';
    }

    // Valideer achternaam
    if (empty($achternaam)) {
        $fouten[] = 'Achternaam is verplicht.';
    }
    if (strlen($achternaam) > 50) {
        $fouten[] = 'Achternaam mag maximaal 50 karakters lang zijn.';
    }

    // Valideer adres (optioneel)
    if (strlen($adres) > 255) {
        $fouten[] = 'Adres mag maximaal 255 karakters lang zijn.';
    }

    // Voeg gegevens toe als er geen fouten zijn
    if (empty($fouten)) {
        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT); // Hash het wachtwoord

        // Controleer of gebruikersnaam al bestaat
        $checkquery = "SELECT COUNT(username) AS COUNT FROM [Users] WHERE username = :gebruikersnaam";
        $check = $db->prepare($checkquery);
        $check->execute(['gebruikersnaam' => $gebruikersnaam]);
        $result = $check->fetch();

        if ($result['COUNT'] > 0) {
            $melding = 'Gebruikersnaam bestaat al in de database!';
        } else {
            // Voeg nieuwe gebruiker toe aan de database
            $sql = "INSERT INTO [Users] (username, password, first_name, last_name, address, role)
                    VALUES (:gebruikersnaam, :wachtwoord, :voornaam, :achternaam, :adres, 'Client')";
            $query = $db->prepare($sql);
            $success = $query->execute([
                'gebruikersnaam' => $gebruikersnaam,
                'voornaam' => $voornaam,
                'achternaam' => $achternaam,
                'adres' => $adres,
                'wachtwoord' => $hash
            ]);

            // Reset velden bij succes
            if ($success) {
                $gebruikersnaam = '';
                $voornaam = '';
                $achternaam = '';
                $adres = '';
                $hash = '';
                $melding = 'Registratie succesvol!';
            } else {
                $melding = 'Er is een fout opgetreden bij het registreren.';
            }
        }
    } else {
        // Toon alle foutmeldingen in een lijst
        $foutmelding = "Er waren fouten in de invoer<ul>";
        foreach ($fouten as $fout) {
            $foutmelding .= "<li>{$fout}</li>";
        }
        $foutmelding .= "</ul>";
    }
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
    <title>Sole Machina | Registreren</title>
</head>

<body>
    <div class="login">
        <div class="container">
            <header>
                <h1>Sole Machina</h1>
            </header>

            <main>
                <h2>Registreren</h2>
                <!-- Registratieformulier -->
                <form action="" method="post">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="gebruikersnaam" id="gebruikersnaam" value="<?= htmlspecialchars($gebruikersnaam) ?>">

                    <label for="wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" id="wachtwoord">

                    <label for="herhaal-wachtwoord">Herhaal Wachtwoord</label>
                    <input type="password" name="herhaal-wachtwoord" id="herhaal-wachtwoord">

                    <label for="voornaam">Naam</label>
                    <input type="text" name="voornaam" id="voornaam" value="<?= htmlspecialchars($voornaam) ?>">

                    <label for="achternaam">Achternaam</label>
                    <input type="text" name="achternaam" id="achternaam" value="<?= htmlspecialchars($achternaam) ?>">

                    <label for="adres">Adres (optioneel)</label>
                    <input type="text" name="adres" id="adres" value="<?= htmlspecialchars($adres) ?>">

                    <input class="submit" id="registreren" type="submit" value="registreren" name="registreren">
                </form>
                <!-- Navigatieknoppen -->
                <button onclick="location.href='login.php'">Al een account? Log hier in</button>
                <button onclick="location.href='index.php'">Doorgaan als gast</button>
                <button onclick="location.href='medewerker-login.php'">Medewerker login</button>
                <!-- Toon foutmeldingen en succesmelding -->
                <?= $foutmelding ?>
                <?= $melding ?>
            </main>
        </div>
    </div>
</body>
</html>