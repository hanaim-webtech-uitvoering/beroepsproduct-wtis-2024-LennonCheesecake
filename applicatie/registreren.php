<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Registreren</title>
</head>

<body>
    <div class="login">
        <div class="container">
            <header>
                <h1>Sole Machina</h1>
            </header>

            <main>
                <h2>Registreren</h2>
                <form action="profiel.php" method="get">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="gebruikersnaam" id="gebruikersnaam" minlength="6" required>

                    <label for="wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" id="wachtwoord" minlength="6" required>

                    <label for="herhaal-wachtwoord">Herhaal Wachtwoord</label>
                    <input type="password" name="herhaal-wachtwoord" id="herhaal-wachtwoord" minlength="6" required>

                    <label for="voornaam">Naam</label>
                    <input type="text" name="voornaam" id="voornaam" required>

                    <label for="achternaam">Achternaam</label>
                    <input type="text" name="achternaam" id="achternaam" required>

                    <label for="adres">Adres</label>
                    <input type="text" name="adres" id="adres" required>

                    <input class="submit" id="registreren" type="submit" value="Registreren">
                </form>
                <button onclick="location.href='login.php'">Al een account? Log hier in</button>
                <button onclick="location.href='index.php'">Doorgaan als gast</button>
                <button onclick="location.href='medewerker-login.php'">Medewerker login</button>
            </main>
        </div>
    </div>
</body>

</html>