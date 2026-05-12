<?php
    require_once(__DIR__ . "/../../controllers/torneosController.php");
    $objTorneosController = new torneosController();
    $objTorneosController->delete($_GET['id']);
?>
