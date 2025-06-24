<?php
session_start(); // Start de sessie voor navigatie en loginstatus
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Koppel de CSS-bestanden -->
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Privacy Statement</title>
</head>

<body>
    <header>
        <h1>Privacyverklaring</h1>
    </header>

    <!-- Navigatiebalk -->
    <nav>
        <div class="topnav">
            <a href="index.php">Startpagina</a>
            <a href="winkelmand.php">Winkelmandje</a>
            <a href="bestellingen.php">Bestellingen</a>
            <a href="profiel.php">Profiel</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Uitloggen</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <a class="split" href="privacy.php">Privacy</a>
        </div>
    </nav>

    <main>
        <section class="privacy">
            <!-- Inleiding privacyverklaring -->
            <h2>Inleiding</h2>
            <p>Bij Sole Machina hechten we veel waarde aan de privacy van onze klanten. In deze privacyverklaring leggen we uit welke gegevens we verzamelen, hoe we deze gebruiken en hoe we deze beschermen.</p>

            <!-- Overzicht van verzamelde gegevens -->
            <h2>Welke gegevens verzamelen we?</h2>
            <p>We kunnen de volgende gegevens verzamelen:</p>
            <ul>
                <li>Naam</li>
                <li>Adres</li>
                <li>E-mailadres</li>
                <li>Telefoonnummer</li>
                <li>Betalingsinformatie</li>
                <li>IP-adres</li>
                <li>Browser- en apparaatgegevens</li>
            </ul>

            <!-- Gebruik van gegevens -->
            <h2>Hoe gebruiken we de gegevens?</h2>
            <p>We gebruiken de verzamelde gegevens voor de volgende doeleinden:</p>
            <ul>
                <li>Verwerking van bestellingen</li>
                <li>Verbetering van onze diensten</li>
                <li>Klantenservice</li>
                <li>Marketing en promoties</li>
                <li>Naleving van wettelijke verplichtingen</li>
            </ul>

            <!-- Bescherming van gegevens -->
            <h2>Hoe beschermen we de gegevens?</h2>
            <p>We nemen de bescherming van uw gegevens serieus en hebben passende technische en organisatorische maatregelen getroffen om uw gegevens te beschermen tegen verlies, diefstal en onbevoegde toegang.</p>

            <!-- Rechten van de gebruiker -->
            <h2>Uw rechten</h2>
            <p>U heeft het recht om uw gegevens in te zien, te corrigeren of te verwijderen. U kunt ook bezwaar maken tegen de verwerking van uw gegevens of vragen om beperking van de verwerking. Neem contact met ons op via [contactgegevens] om uw rechten uit te oefenen.</p>

            <!-- Contactgegevens -->
            <h2>Contact</h2>
            <p>Als u vragen heeft over deze privacyverklaring of over hoe we uw gegevens verwerken, neem dan contact met ons op via:</p>
            <p>Email: privacy@solemachina.com</p>
            <p>Telefoon: 0123-456789</p>
            <p>Adres: Pepermolenweg 69, 1234 AB Oss</p>
        </section>
    </main>

    <!-- Footer met links naar de startpagina, behalve privacy -->
    <footer>
        <a href="index.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="index.php">Accessibility statement |</a>
        <a href="index.php">@ 2024 Sole Machina |</a>
        <a href="index.php">Cookie settings</a>
    </footer>
</body>
</html>