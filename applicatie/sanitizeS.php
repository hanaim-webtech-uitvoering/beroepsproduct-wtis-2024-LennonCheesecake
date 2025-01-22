<?php

function sanitize($unclean) {
    $cleaner = strip_tags($unclean);
    $cleaner = htmlspecialchars($cleaner);
    $cleaner = trim($cleaner);
    return $cleaner;
}

?>