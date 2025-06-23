<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css ">
    <title>Sole Machina | Winkelmandje</title>
</head>

<body>
    <header>
        <h1>Winkelmandje</h1>
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
        <section class="shopping-cart">
            <h2>Uw Winkelmandje</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs per stuk</th>
                        <th>Totaal</th>
                        <th>Verwijderen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pizza Margherita</td>
                        <td><input type="number" value="1" min="1" max="99" required></td>
                        <td>€9.00</td>
                        <td>€9.00</td>
                        <td><button>Verwijderen</button></td>
                    </tr>
                    <tr>
                        <td>Pizza Quattro Stagioni</td>
                        <td><input type="number" value="2" min="1" max="99" required></td>
                        <td>€11.50</td>
                        <td>€23.00</td>
                        <td><button>Verwijderen</button></td>
                    </tr>
                    <tr>
                        <td>Coca Cola</td>
                        <td><input type="number" value="1" min="1" max="99" required></td>
                        <td>€2.50</td>
                        <td>€2.50</td>
                        <td><button>Verwijderen</button></td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-total">
                <h3>Totaal: €32.00</h3>
                <button onclick="window.location.href='bestelling2.php'">Bestelling Plaatsen</button>
            </div>
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