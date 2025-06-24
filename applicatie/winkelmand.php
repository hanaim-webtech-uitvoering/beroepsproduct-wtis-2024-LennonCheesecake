<?php
session_start();
require_once 'db_connection.php';

// Haal producten/prijzen op uit de database voor prijsberekening
$db = maakVerbinding();
$stmt = $db->query("SELECT name, price FROM [Product]");
$prijzen = [];
while ($row = $stmt->fetch()) {
    $prijzen[$row['name']] = $row['price'];
}

// Bestelling bevestigen en opslaan in database
$bestelMelding = '';
$gastAdres = '';
$adresFout = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bevestig_bestelling']) && !empty($_SESSION['winkelwagen'])) {
    // 1. Verzamel klantgegevens
    $client_username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $client_name = '';
    $address = '';

    if ($client_username) {
        // Haal naam en adres op uit de database
        $stmtUser = $db->prepare("SELECT first_name, last_name, address FROM [Users] WHERE username = :username");
        $stmtUser->execute(['username' => $client_username]);
        $user = $stmtUser->fetch();
        if ($user) {
            $client_name = $user['first_name'] . ' ' . $user['last_name'];
            $address = $user['address'];
        }
    } else {
        // Gast-bestelling: adres verplicht
        $client_name = 'Gast';
        $gastAdres = isset($_POST['gast_adres']) ? trim($_POST['gast_adres']) : '';
        if (empty($gastAdres)) {
            $adresFout = "Vul uw adres in om te bestellen.";
        } else {
            $address = $gastAdres;
        }
    }

    // Alleen verder als er geen adresfout is
    if (empty($adresFout)) {
        // 2. Personeelslid is nog niet bekend bij bestelling plaatsen, zet op NULL
        $personnel_username = 'LennonM';

        // 3. Status op 1 (bijvoorbeeld 'nieuw')
        $status = 1;

        // 4. Voeg bestelling toe aan Pizza_Order
        $insertOrder = $db->prepare("INSERT INTO [Pizza_Order] (client_username, client_name, personnel_username, datetime, status, address)
            VALUES (:client_username, :client_name, :personnel_username, GETDATE(), :status, :address)");
        $insertOrder->execute([
            'client_username' => $client_username,
            'client_name' => $client_name,
            'personnel_username' => $personnel_username,
            'status' => $status,
            'address' => $address
        ]);

        // 5. Haal het order_id op
        $orderId = $db->lastInsertId();

        // 6. Voeg producten toe aan Pizza_Order_Product
        $insertProduct = $db->prepare("INSERT INTO [Pizza_Order_Product] (order_id, product_name, quantity) VALUES (:order_id, :product_name, :quantity)");
        foreach ($_SESSION['winkelwagen'] as $naam => $aantal) {
            $insertProduct->execute([
                'order_id' => $orderId,
                'product_name' => $naam,
                'quantity' => $aantal
            ]);
        }

        // 7. Leeg het winkelmandje en geef een melding
        $_SESSION['winkelwagen'] = [];
        $bestelMelding = "Bestelling succesvol geplaatst!";
    }
}

// Debug: Toon sessiegegevens als ze bestaan
if (isset($_SESSION['username'])) {
    echo "<div style='background: #dfd; padding: 10px; margin: 10px 0;'>Sessie actief!<br>";
    echo "Gebruikersnaam: " . $_SESSION['username'] . "<br>";
    echo "Rol: " . $_SESSION['role'] . "</div>";
}

// Debug: Toon huidige winkelmandje
if (!empty($_SESSION['winkelwagen'])) {
    echo "<div style='background: #ffd; padding: 10px; margin: 10px 0;'>";
    echo "<strong>DEBUG - Winkelmandje:</strong><br>";
    foreach ($_SESSION['winkelwagen'] as $naam => $aantal) {
        echo htmlspecialchars($naam) . ": " . (int)$aantal . "<br>";
    }
    echo "</div>";
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

    <nav>
        <div class="topnav">
            <a class="active" href="index.php">Startpagina</a>
            <a href="winkelmand.php">Winkelmandje</a>
            <a href="bestellingen.php">Bestellingen</a>
            <a href="profiel.php">Profiel</a>
            <a href="logout.php">Uitloggen</a>
            <a class="split" href="privacy.php">Privacy</a>
        </div>
    </nav>

    <main>
        <section class="shopping-cart">
            <h2>Uw Winkelmandje</h2>
            <?php if ($bestelMelding): ?>
                <div class="melding"><?= htmlspecialchars($bestelMelding) ?></div>
            <?php endif; ?>
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
                    <?php if (!isset($_SESSION['username'])): ?>
                        <div>
                            <label for="gast_adres"><strong>Adres (verplicht voor gasten):</strong></label><br>
                            <input type="text" name="gast_adres" id="gast_adres" value="<?= htmlspecialchars($gastAdres) ?>" required>
                            <?php if ($adresFout): ?>
                                <div style="color:red;"><?= htmlspecialchars($adresFout) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="bevestig_bestelling">Bestelling bevestigen</button>
                </form>
            <?php else: ?>
                <h2>Winkelmandje is leeg</h2>
                <h2>Voeg Producten toe via de Startpagina</h2>
            <?php endif; ?>
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