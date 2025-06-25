<?php
session_start();
require_once 'db_connection.php';

// Functie om bestellingen van een gebruiker op te halen
function haalBestellingenOp($db, $username) {
    $stmt = $db->prepare("SELECT * FROM [Pizza_Order] WHERE client_username = :username ORDER BY datetime DESC");
    $stmt->execute(['username' => $username]);
    return $stmt->fetchAll();
}

// Functie om producten bij een bestelling op te halen
function haalProductenBijBestellingOp($db, $order_id) {
    $stmtProd = $db->prepare("SELECT p.product_name, p.quantity, pr.price 
        FROM [Pizza_Order_Product] p
        JOIN [Product] pr ON p.product_name = pr.name
        WHERE p.order_id = :order_id");
    $stmtProd->execute(['order_id' => $order_id]);
    return $stmtProd->fetchAll();
}

// Functie om de status van een bestelling om te zetten naar tekst
function statusText($status) {
    switch ($status) {
        case 1: return "Bestelling ontvangen";
        case 2: return "In behandeling";
        case 3: return "Onderweg";
        case 4: return "Geannuleerd";
        case 5: return "Bezorgd";
        default: return "Onbekend";
    }
}

// Maak verbinding met de database
$db = maakVerbinding();
$bestellingen = [];

// Alleen bestellingen tonen als gebruiker is ingelogd
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $bestellingen = haalBestellingenOp($db, $username);

    // Haal alle producten per bestelling op
    foreach ($bestellingen as &$bestelling) {
        $bestelling['producten'] = haalProductenBijBestellingOp($db, $bestelling['order_id']);
    }
    unset($bestelling); // Verbreek de referentie na gebruik
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Bestellingen</title>
</head>

<body>
    <header>
        <h1>Bestellingen</h1>
    </header>
    
    <!-- Navigatiebalk -->
    <nav>
        <div class="topnav">
            <a class="active" href="index.php">Startpagina</a>
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
        <section class="bestellingen">
            <h2>Uw Bestellingen</h2>
            <div class="bestellingen-info">
                <?php if (empty($bestellingen)): ?>
                    <?php if (!isset($_SESSION['username'])): ?>
                        <p>Gast bestellingen worden via uw opgegeven email verzonden.</p>
                    <?php else: ?>
                        <p>U heeft nog geen bestellingen geplaatst.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Toon alle bestellingen van de gebruiker -->
                    <?php foreach ($bestellingen as $bestelling): ?>
                        <div class="bestelling">
                            <h3>
                                Bestelling #<?= htmlspecialchars($bestelling['order_id']) ?> 
                                (<?= htmlspecialchars($bestelling['datetime']) ?>)
                            </h3>
                            <p><strong>Status:</strong> <?= statusText($bestelling['status']) ?></p>
                            <?php 
                            $totaal = 0;
                            // Toon alle producten in deze bestelling
                            foreach ($bestelling['producten'] as $product): 
                                $subtotaal = $product['quantity'] * $product['price'];
                                $totaal += $subtotaal;
                            ?>
                                <div class="bestelling-sub">
                                    <p><strong>Product:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                                    <p><strong>Aantal:</strong> <?= (int)$product['quantity'] ?></p>
                                    <p><strong>Prijs:</strong> €<?= number_format($product['price'], 2, ',', '.') ?></p>
                                    <p><strong>Subtotaal:</strong> €<?= number_format($subtotaal, 2, ',', '.') ?></p>
                                </div>
                            <?php endforeach; ?>
                            <strong id="bezorgadres">Bezorgadres: <?= htmlspecialchars($bestelling['address']) ?></strong><br>
                            <strong style="color: #007700;">Totaalprijs: €<?= number_format($totaal, 2, ',', '.') ?></strong>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer met links naar deze pagina, behalve privacy -->
    <footer>
        <a href="bestellingen.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="bestellingen.php">Accessibility statement |</a>
        <a href="bestellingen.php">@ 2024 Sole Machina |</a>
        <a href="bestellingen.php">Cookie settings</a>
    </footer>
</body>

</html>