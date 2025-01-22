<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Medewerker Bestellingen Overzicht</title>
</head>

<body>
    <header>
        <h1>Medewerker Bestellingen Overzicht</h1>
    </header>
    
    <nav>
        <div class="topnav">
            <a href="medewerker-bestellingen.php">Bestellingen</a>
            <a href="medewerker-profiel.php">Profiel</a>
            <a href="index.php">Uitloggen</a>
        </div>
    </nav>

    <main>
        <section class="bestellingen">
            <h2>Huidige Bestellingen</h2>
            <div class="bestellingen-info">
                <div class="bestelling">
                    <h3>Bestelling 1: John Doe</h3>
                    <div class="bestelling-sub">
                        <p><strong>Product:</strong> Pizza Margherita</p>
                        <p><strong>Aantal:</strong> 1</p>
                        <p><strong>Prijs:</strong> €9.00</p>
                        <p><strong>Status:</strong> Onderweg</p>
                    </div>
                    <div class="bestelling-sub">
                        <p><strong>Product:</strong> Pizza Pepperoni</p>
                        <p><strong>Aantal:</strong> 1</p>
                        <p><strong>Prijs:</strong> €10.00</p>
                        <p><strong>Status:</strong> Onderweg</p>
                    </div>
                        <strong class="bezorgadres">Bezorgadres: Kaasstraat 26</strong>
                        <button onclick="location.href='medewerker-bestelling-details-johndoe.php '">Bestelling Details</button>
                        <button onclick="location.href='medewerker-bestelling-wijzigen.php '">Bestelling wijzigen</button>
                    </div>

                <div class="bestelling">
                    <h3>Bestelling 2: Karel Pas</h3>
                    <div class="bestelling-sub">
                        <p><strong>Product:</strong> Pizza Margherita</p>
                        <p><strong>Aantal:</strong> 1</p>
                        <p><strong>Prijs:</strong> €9.00</p>
                        <p><strong>Status:</strong> Bestelling ontvangen</p>
                    </div>
                    <div class="bestelling-sub">
                        <p><strong>Product:</strong> Pizza Quattro Stagioni</p>
                        <p><strong>Aantal:</strong> 2</p>
                        <p><strong>Prijs:</strong> €23.00</p>
                        <p><strong>Status:</strong> Bestelling ontvangen</p>
                    </div>
                    <div class="bestelling-sub">
                        <p><strong>Product:</strong> Coca Cola</p>
                        <p><strong>Aantal:</strong> 1</p>
                        <p><strong>Prijs:</strong> €2.50</p>
                        <p><strong>Status:</strong> Bestelling ontvangen</p>
                    </div>
                        <strong class="bezorgadres">Bezorgadres: Vlinderlaan 12</strong>
                        <button onclick="location.href='medewerker-bestelling-details-karelpas.php '">Bestelling Details</button>
                        <button onclick="location.href='medewerker-bestelling-wijzigen.php'">Bestelling wijzigen</button>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <a href="medewerker-profiel.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="medewerker-profiel.php">Accessibility statement |</a>
        <a href="medewerker-profiel.php">@ 2024 Sole Machina |</a>
        <a href="medewerker-profiel.php">Cookie settings</a>
    </footer>
</body>

</html>