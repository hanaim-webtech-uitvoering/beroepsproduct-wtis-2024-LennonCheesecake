<?php
session_start();
require_once 'db_connection.php';

// Functie om prijzen op te halen uit de database
function haalPrijzenOp($db) {
    $stmt = $db->query("SELECT name, price FROM [Product]");
    $prijzen = [];
    while ($row = $stmt->fetch()) {
        $prijzen[$row['name']] = $row['price'];
    }
    return $prijzen;
}

// Functie om gebruikersgegevens op te halen
function haalGebruikerOp($db, $username) {
    $stmt = $db->prepare("SELECT first_name, last_name, address FROM [Users] WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch();
}

// Functie om een bestelling toe te voegen
function voegBestellingToe($db, $client_username, $client_name, $personnel_username, $status, $address) {
    $insertOrder = $db->prepare("INSERT INTO [Pizza_Order] (client_username, client_name, personnel_username, datetime, status, address)
        VALUES (:client_username, :client_name, :personnel_username, GETDATE(), :status, :address)");
    $insertOrder->execute([
        'client_username' => $client_username,
        'client_name' => $client_name,
        'personnel_username' => $personnel_username,
        'status' => $status,
        'address' => $address
    ]);
    return $db->lastInsertId();
}

// Functie om producten toe te voegen aan een bestelling
function voegProductenToe($db, $orderId, $winkelwagen) {
    $insertProduct = $db->prepare("INSERT INTO [Pizza_Order_Product] (order_id, product_name, quantity) VALUES (:order_id, :product_name, :quantity)");
    foreach ($winkelwagen as $naam => $aantal) {
        $insertProduct->execute([
            'order_id' => $orderId,
            'product_name' => $naam,
            'quantity' => $aantal
        ]);
    }
}

// Start databaseverbinding en prijzen ophalen
$db = maakVerbinding();
$prijzen = haalPrijzenOp($db);

// Variabelen voor meldingen en gastgegevens
$bestelMelding = '';
$gastAdres = '';
$gastEmail = '';
$adresFout = '';

// Verwerk bestelling als het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bevestig_bestelling']) && !empty($_SESSION['winkelwagen'])) {
    $client_username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $client_name = '';
    $address = '';

    if ($client_username) {
        // Haal naam en adres op uit de database voor ingelogde gebruiker
        $user = haalGebruikerOp($db, $client_username);
        if ($user) {
            $client_name = $user['first_name'] . ' ' . $user['last_name'];
            $address = $user['address'];
        }
    } else {
        // Gast-bestelling: adres en e-mail verplicht
        $client_name = 'Gast';
        $gastAdres = isset($_POST['gast_adres']) ? trim($_POST['gast_adres']) : '';
        $gastEmail = isset($_POST['gast_email']) ? trim($_POST['gast_email']) : '';
        if (empty($gastAdres)) {
            $adresFout = "Vul uw adres in om te bestellen.";
        } elseif (empty($gastEmail)) {
            $adresFout = "Vul uw e-mailadres in om te bestellen.";
        } else {
            $address = $gastAdres;
        }
    }

    // Alleen verder als er geen adresfout is
    if (empty($adresFout)) {
        $personnel_username = 'TestM'; // Zet het personeel_username vast
        $status = 1; // Bestelling ontvangen

        // Voeg bestelling toe en producten toe via functies
        $orderId = voegBestellingToe($db, $client_username, $client_name, $personnel_username, $status, $address);
        voegProductenToe($db, $orderId, $_SESSION['winkelwagen']);

        // Leeg het winkelmandje en geef een melding
        $_SESSION['winkelwagen'] = [];
        if (!$client_username && !empty($gastEmail)) {
            $bestelMelding = "Bestelling succesvol geplaatst! De bevestiging is verstuurd naar " . htmlspecialchars($gastEmail) . ".";
        } else {
            $bestelMelding = "Bestelling succesvol geplaatst!";
        }
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
    <title>Sole Machina | Winkelmandje</title>
</head>

<body>
    <header>
        <h1>Winkelmandje</h1>
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
        <section class="shopping-cart">
            <h2>Uw Winkelmandje</h2>
            <!-- Toon melding na succesvolle bestelling -->
            <?php if ($bestelMelding): ?>
                <div class="melding"><?= htmlspecialchars($bestelMelding) ?></div>
            <?php endif; ?>
            <!-- Toon winkelmandje als er producten in zitten -->
            <?php if (!empty($_SESSION['winkelwagen'])): ?>
                <form method="post">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Aantal</th>
                                <th>Prijs per stuk</th>
                                <th>Totaal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totaal = 0;
                            foreach ($_SESSION['winkelwagen'] as $naam => $aantal):
                                $prijs = isset($prijzen[$naam]) ? $prijzen[$naam] : 0;
                                $subtotaal = $prijs * $aantal;
                                $totaal += $subtotaal;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($naam) ?></td>
                                <td><?= (int)$aantal ?></td>
                                <td>€<?= number_format($prijs, 2, ',', '.') ?></td>
                                <td>€<?= number_format($subtotaal, 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Totaal</strong></td>
                                <td><strong>€<?= number_format($totaal, 2, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <!-- Velden voor gast-bestelling -->
                    <?php if (!isset($_SESSION['username'])): ?>
                        <div>
                            <label for="gast_adres"><strong>Adres (verplicht voor gasten):</strong></label><br>
                            <input type="text" name="gast_adres" id="gast_adres" value="<?= htmlspecialchars($gastAdres) ?>" required>
                            <?php if ($adresFout): ?>
                                <div style="color:red;"><?= htmlspecialchars($adresFout) ?></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="gast_email"><strong>E-mail (verplicht voor gasten):</strong></label><br>
                            <input type="email" name="gast_email" id="gast_email" value="<?= isset($_POST['gast_email']) ? htmlspecialchars($_POST['gast_email']) : '' ?>" required>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="bevestig_bestelling">Bestelling bevestigen</button>
                </form>
            <?php else: ?>
                <!-- Winkelmandje is leeg -->
                <h2>Winkelmandje is leeg</h2>
                <h2>Voeg Producten toe via de Startpagina</h2>
            <?php endif; ?>
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