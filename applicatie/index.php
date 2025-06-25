<?php
session_start();
require_once 'db_connection.php'; // Verbind met de database

// Functie om producten op te halen uit de database
function haalProductenOp($db) {
    $stmt = $db->query("SELECT name, price FROM [Product]");
    $producten = [];
    while ($row = $stmt->fetch()) {
        $producten[$row['name']] = [
            'naam' => $row['name'],
            'prijs' => $row['price']
        ];
    }
    return $producten;
}

// Functie om een product toe te voegen aan het winkelmandje
function voegToeAanWinkelwagen($product, $aantal, &$producten) {
    if (isset($producten[$product]) && $aantal > 0) {
        if (!isset($_SESSION['winkelwagen'][$product])) {
            $_SESSION['winkelwagen'][$product] = 0;
        }
        $_SESSION['winkelwagen'][$product] += $aantal;
        $_SESSION['melding'] = "Product toegevoegd aan winkelwagen!";
    }
}

// Maak verbinding met de database en haal producten op
$db = maakVerbinding();
$producten = haalProductenOp($db);

// Voeg toe aan winkelwagen als er op de knop gedrukt is
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product'], $_POST['aantal'])) {
    $product = $_POST['product'];
    $aantal = (int)$_POST['aantal'];
    voegToeAanWinkelwagen($product, $aantal, $producten);
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
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Koppel de CSS-bestanden -->
    <link rel="stylesheet" href="../Styles/normalize.css">
    <link rel="stylesheet" href="../Styles/homepagina.css">
    <title>Sole Machina</title>
</head>

<body>
    <div class="container">
        <header>
            <h1>Sole Machina</h1>
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
        
        <!-- Toon melding als er een product is toegevoegd -->
        <?php if (!empty($melding)) : ?>
            <div class="melding"><?= htmlspecialchars($melding) ?></div>
        <?php endif; ?>

        <main>
            <h2>Menu</h2>
            <div class="menu-container">
                <?php foreach ($producten as $key => $product): ?>
                    <div class="menu-box">
                        <h3><?= htmlspecialchars($product['naam']) ?></h3>
                        <?php
                        // Bepaal het juiste pad voor de afbeelding
                        $imgPath = "Pictures/" . str_replace(' ', '_', $product['naam']) . ".png";
                        if (!file_exists($imgPath)) {
                            $imgPath = "Pictures/placeholder.png";
                        }
                        ?>
                        <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($product['naam']) ?>" width="300" class="responsive-img">
                        <h4>€<?= number_format($product['prijs'], 2, ',', '.') ?></h4>
                        <!-- Formulier om product toe te voegen aan winkelwagen -->
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
        <!-- Footer met links -->
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