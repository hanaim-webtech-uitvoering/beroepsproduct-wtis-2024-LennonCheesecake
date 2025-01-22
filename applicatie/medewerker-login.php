<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/normalize.css">
    <link rel="stylesheet" href="Styles/homepagina.css">
    <title>Sole Machina | Medewerker Login</title>
</head>

<body>
    <div class="login">
        <div class="container">
            <header>
                <h1>Sole Machina</h1>
            </header>

                <main>
                    <h2>Medewerker Login</h2>
                    <form action="medewerker-profiel.php" method="get">
                        <label for="gebruikersnaam">Gebruikersnaam</label>
                        <input type="text" name="gebruikersnaam" id="gebruikersnaam" minlength="6" required>

                        <label for="wachtwoord">Wachtwoord</label>
                        <input type="password" name="wachtwoord" id="wachtwoord" minlength="6" required>

                        <input class="submit" id="inloggen" type="submit" value="Inloggen als medewerker">
                    </form>
                    <button onclick="location.href='registreren.php'">Nog geen account? Registreer hier</button>
                    <button onclick="location.href='index.php'">Doorgaan als gast</button>
                    <button onclick="location.href='login.php'">Klant login</button>
                </main>
        </div>
    </div>
</body>

</html>