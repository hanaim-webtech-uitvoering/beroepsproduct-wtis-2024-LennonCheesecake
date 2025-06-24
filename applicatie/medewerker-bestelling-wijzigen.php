<?php
session_start();
require_once 'db_connection.php';

// Alleen toegankelijk voor medewerkers
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Medewerker') {
    header('Location: medewerker-login.php');
    exit;
}

$db = maakVerbinding();

// Haal order_id uit de URL
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Haal de bestelling op uit de database
$stmt = $db->prepare("SELECT * FROM [Pizza_Order] WHERE order_id = :order_id");
$stmt->execute(['order_id' => $order_id]);
$bestelling = $stmt->fetch();

if (!$bestelling) {
    // Bestelling niet gevonden, toon melding en stop
    echo "<p>Bestelling niet gevonden.</p>";
    exit;
}

// Haal producten bij deze bestelling op
$stmtProd = $db->prepare("SELECT p.product_name, p.quantity, pr.price 
    FROM [Pizza_Order_Product] p
    JOIN [Product] pr ON p.product_name = pr.name
    WHERE p.order_id = :order_id");
$stmtProd->execute(['order_id' => $order_id]);
$productList = $stmtProd->fetchAll();

// Status wijzigen als het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $nieuweStatus = (int)$_POST['status'];
    $update = $db->prepare("UPDATE [Pizza_Order] SET status = :status WHERE order_id = :order_id");
    $update->execute(['status' => $nieuweStatus, 'order_id' => $order_id]);
    // Stuur terug naar medewerker-bestellingen.php met klant als parameter
    $klant = urlencode($bestelling['client_name']);
    header("Location: medewerker-bestellingen.php?klant=$klant");
    exit;
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
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Medewerker Bestelling Wijzigen</title>
</head>
<body>
    <header>
        <h1>Bestelling #<?= htmlspecialchars($bestelling['order_id']) ?> wijzigen</h1>
    </header>
    <nav>
        <div class="topnav">
            <a href="medewerker-bestellingen.php">Bestellingen</a>
            <a href="medewerker-profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
        </div>
    </nav>
    <main>
        <section class="bestellingen">
            <div class="bestellingen-info">
                <div class="bestelling">
                    <h3>Klant: <?= htmlspecialchars($bestelling['client_name']) ?></h3>
                    <strong class="bezorgadres">Bezorgadres: <?= htmlspecialchars($bestelling['address']) ?></strong>
                    <!-- Formulier om status te wijzigen -->
                    <form method="post">
                        <?php foreach ($productList as $product): ?>
                            <div class="bestelling-sub">
                                <p><strong>Product:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                                <p><strong>Aantal:</strong> <?= (int)$product['quantity'] ?></p>
                                <p><strong>Prijs:</strong> €<?= number_format($product['price'], 2, ',', '.') ?></p>
                            </div>
                        <?php endforeach; ?>
                        <p><strong>Status:</strong>
                            <select class="menu-box-button" name="status">
                                <option value="1" <?= $bestelling['status']==1?'selected':''; ?>>Bestelling ontvangen</option>
                                <option value="2" <?= $bestelling['status']==2?'selected':''; ?>>In behandeling</option>
                                <option value="3" <?= $bestelling['status']==3?'selected':''; ?>>Onderweg</option>
                                <option value="4" <?= $bestelling['status']==4?'selected':''; ?>>Geannuleerd</option>
                                <option value="5" <?= $bestelling['status']==5?'selected':''; ?>>Bezorgd</option>
                            </select>
                        </p>
                        <button type="submit" class="menu-box-button" style="margin-top:10px;">Wijzigingen opslaan</button>
                    </form>
                </div>
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