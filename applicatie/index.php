<?php
session_start();
require_once 'db_connection.php'; // Zorg dat je deze regel toevoegt!

// Haal producten op uit de database
$db = maakVerbinding();
$stmt = $db->query("SELECT name, price FROM [Product]");
$producten = [];
while ($row = $stmt->fetch()) {
    $producten[$row['name']] = [
        'naam' => $row['name'],
        'prijs' => $row['price']
    ];
}

// Voeg toe aan winkelwagen als er op de knop gedrukt is
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['aantal'])) {
    $product = $_POST['product'];
    $aantal = (int)$_POST['aantal'];
    if (isset($producten[$product]) && $aantal > 0) {
        // Voeg toe of verhoog aantal in winkelwagen
        if (!isset($_SESSION['winkelwagen'][$product])) {
            $_SESSION['winkelwagen'][$product] = 0;
        }
        $_SESSION['winkelwagen'][$product] += $aantal;
        // Zet een melding in de sessie
        $_SESSION['melding'] = "Product toegevoegd aan winkelwagen!";
    }
    // Redirect naar dezelfde pagina om dubbele invoer te voorkomen
    header("Location: index.php");
    exit;
}

// Haal melding op uit de sessie (indien aanwezig)
$melding = '';
if (isset($_SESSION['melding'])) {
    $melding = $_SESSION['melding'];
    unset($_SESSION['melding']);
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/normalize.css">
    <link rel="stylesheet" href="../Styles/homepagina.css">
    <title>Sole Machina</title>
</head>

<body>
    <div class="container">
        <header>
            <h1>Sole Machina</h1>
        </header>

        <nav>
            <div class="topnav">
                <a class="active" href="index.php">Startpagina</a>
                <a href="winkelmand.php">Winkelmandje</a>
                <a href="bestellingen-uitgelogd.php">Bestellingen</a>
                <a href="profiel.php">Profiel</a>
                <a href="login.php">Login</a>
                <a class="split" href="privacy.php">Privacy</a>
            </div>
        </nav>
        
        <?php if (!empty($melding)) : ?>
    <div class="melding"><?= htmlspecialchars($melding) ?></div>
<?php endif; ?>

        <main>
            <h2>Menu</h2>
            <div class="menu-container">
                <?php foreach ($producten as $key => $product): ?>
                    <div class="menu-box">
                        <h3><?= htmlspecialchars($product['naam']) ?></h3>
                        <!-- Optioneel: voeg plaatjes toe op basis van naam -->
                        <img src="Pictures/<?= str_replace(' ', '_', $product['naam']) ?>.png" alt="<?= htmlspecialchars($product['naam']) ?>" width="300" class="responsive-img">
                        <h4>€<?= number_format($product['prijs'], 2, ',', '.') ?></h4>
                        <form method="post" action="">
                            <select name="aantal">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <input type="hidden" name="product" value="<?= htmlspecialchars($product['naam']) ?>">
                            <button type="submit">Voeg toe aan winkelwagen</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
        <footer>
            <a href="index.php">Legal information |</a>
            <a href="privacy.php">Privacy policy |</a>
            <a href="index.php">Accessibility statement |</a>
            <a href="index.php">@ 2024 Sole Machina |</a>
            <a href="index.php">Cookie settings</a>
        </footer>
    </div>
</body>

</html>