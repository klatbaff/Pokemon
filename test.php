<?php
try {
    $pdo = new PDO('mysql:host=localhost:3306;dbname=testtest', 'root', 'root');
    echo "Connexion réussie!";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>