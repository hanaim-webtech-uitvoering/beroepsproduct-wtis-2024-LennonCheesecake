<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css ">
    <title>Sole Machina | Bestelling afhandelen</title>
</head>

<body>
    <header>
        <h1>Bestelling afhandelen</h1>
    </header>

    <nav>
        <div class="topnav">
            <a class="active" href="index.php">Startpagina</a>
            <a href="winkelmand-uitgelogd.php">Winkelmandje</a>
            <a href="bestellingen-uitgelogd.php">Bestellingen</a>
            <a href="profiel-uitgelogd.php">Profiel</a>
            <a href="login.php">Login</a>
            <a class="split" href="privacy.php">Privacy</a>
        </div>
    </nav>

    <main>
        <section class="order-form">
            <h2>Bestelgegevens</h2>
            <form action="bestellingen-uitgelogd.php" method="get">
                <label for="name">Naam:</label>
                <input type="text" id="name" name="name" required>

                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" required>

                <label for="city">Stad:</label>
                <input type="text" id="city" name="city" required>

                <label for="postalcode">Postcode:</label>
                <input type="text" id="postalcode" name="postalcode" pattern="[0-9]{4}[A-Z]{2}" required>

                <label for="phone">Telefoonnummer:</label>
                <input type="tel" id="phone" name="phone" pattern="[0-9]*" min="10" required>

                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit">Bestelling Plaatsen</button>
            </form>
        </section>
    </main>

    <footer>
        <a href="index.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="index.php">Accessibility statement |</a>
        <a href="index.php">@ 2024 Sole Machina |</a>
        <a href="index.php">Cookie settings</a>
    </footer>
</body>

</html>