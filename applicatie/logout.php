<?php
session_start(); // Start de sessie
session_unset(); // Verwijder alle sessievariabelen
session_destroy(); // Vernietig de sessie
header('Location: login.php'); // Stuur gebruiker terug naar loginpagina
exit;
?>