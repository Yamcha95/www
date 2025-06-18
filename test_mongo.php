<?php
require __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

try {
    // Change l’URI si besoin (mongodb://user:pass@host:port/dbname)
    $client = new Client('mongodb://localhost:27017');

    $db = $client->selectDatabase('strongbox_db');

    $collections = $db->listCollections();

    echo "Collections dans la base strongbox_db :\n";
    foreach ($collections as $collection) {
        echo "- " . $collection->getName() . "\n";
    }
} catch (Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage() . "\n";
}
