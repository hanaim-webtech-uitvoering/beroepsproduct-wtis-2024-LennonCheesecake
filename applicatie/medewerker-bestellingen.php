<?php
session_start();
require_once 'db_connection.php';

// Alleen toegankelijk voor medewerkers
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Medewerker') {
    header('Location: medewerker-login.php');
    exit;
}

$db = maakVerbinding();

// Functie om alle bestellingen op te halen
function haalAlleBestellingenOp($db) {
    $stmt = $db->prepare("SELECT * FROM [Pizza_Order] ORDER BY datetime DESC");
    $stmt->execute();
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

// Functie om bestellingen te groeperen per klantnaam
function groepeerBestellingenPerKlant($bestellingen) {
    $klanten = [];
    foreach ($bestellingen as $bestelling) {
        $klant = $bestelling['client_name'];
        if (!isset($klanten[$klant])) {
            $klanten[$klant] = [];
        }
        $klanten[$klant][] = $bestelling;
    }
    return $klanten;
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

// Haal alle bestellingen op en voeg producten toe
$bestellingen = haalAlleBestellingenOp($db);
foreach ($bestellingen as &$bestelling) {
    $bestelling['producten'] = haalProductenBijBestellingOp($db, $bestelling['order_id']);
}
unset($bestelling);

// Groepeer bestellingen per klantnaam
$klanten = groepeerBestellingenPerKlant($bestellingen);

// Klanten ophalen voor de dropdown
$stmtKlanten = $db->prepare("SELECT DISTINCT client_name FROM [Pizza_Order]");
$stmtKlanten->execute();
$klantenLijst = $stmtKlanten->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="nl">

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
    
    <!-- Navigatiebalk voor medewerkers -->
    <nav>
        <div class="topnav">
            <a href="medewerker-bestellingen.php">Bestellingen</a>
            <a href="medewerker-profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
        </div>
    </nav>

    <main>
        <section class="bestellingen">
            <h2>Huidige Bestellingen</h2>
            
            <!-- Klant selectie formulier -->
            <form method="get" style="margin-bottom:20px;">
                <label for="klant_select">Selecteer klant:</label>
                <select name="klant" id="klant_select" onchange="this.form.submit()">
                    <option value="">-- Kies een klant --</option>
                    <?php foreach (array_keys($klanten) as $klant): ?>
                        <option value="<?= htmlspecialchars($klant) ?>" <?= (isset($_GET['klant']) && $_GET['klant'] === $klant) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($klant) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <div class="bestellingen-info">
                <?php
                // Haal gekozen klant op uit de GET-parameter
                $gekozenKlant = $_GET['klant'] ?? '';
                if ($gekozenKlant && isset($klanten[$gekozenKlant])): ?>
                    <h3>Bestellingen van <?= htmlspecialchars($gekozenKlant) ?></h3>
                    <?php foreach ($klanten[$gekozenKlant] as $bestelling): ?>
                        <div class="bestelling">
                            <h4>
                                Bestelling #<?= htmlspecialchars($bestelling['order_id']) ?>
                                (<?= htmlspecialchars($bestelling['datetime']) ?>)
                            </h4>
                            <p><strong>Status:</strong> <?= statusText($bestelling['status']) ?></p>
                            <?php foreach ($bestelling['producten'] as $product): ?>
                                <div class="bestelling-sub">
                                    <p><strong>Product:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                                    <p><strong>Aantal:</strong> <?= (int)$product['quantity'] ?></p>
                                    <p><strong>Prijs:</strong> €<?= number_format($product['price'], 2, ',', '.') ?></p>
                                </div>
                            <?php endforeach; ?>
                            <strong class="bezorgadres">Bezorgadres: <?= htmlspecialchars($bestelling['address']) ?></strong>
                            <br>
                            <!-- Knop om bestelling te wijzigen -->
                            <a href="medewerker-bestelling-wijzigen.php?order_id=<?= urlencode($bestelling['order_id']) ?>" class="wijzig-knop" style="margin-top:10px;display:inline-block;">Wijzig bestelling</a>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($gekozenKlant): ?>
                    <p>Geen bestellingen gevonden voor deze klant.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer met links naar medewerker-profiel, behalve privacy -->
    <footer>
        <a href="medewerker-profiel.php">Legal information |</a>
        <a href="privacy.php">Privacy policy |</a>
        <a href="medewerker-profiel.php">Accessibility statement |</a>
        <a href="medewerker-profiel.php">@ 2024 Sole Machina |</a>
        <a href="medewerker-profiel.php">Cookie settings</a>
    </footer>
</body>
</html>