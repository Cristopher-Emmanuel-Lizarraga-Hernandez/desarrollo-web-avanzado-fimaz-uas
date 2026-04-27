<?php
/**
 * Bootstrap
 * Instituto: UTP
 * Estudiante: Cristopher Hernandez
 * Fecha: 26/04/2026
 */

require_once __DIR__ . '/../src/autoload.php';

use App\Controllers\ProductoController;
use InvalidArgumentException;
use RuntimeException;

$controller = new ProductoController();
$message = '';
$type = 'error';

try {
    $action = $_GET['action'] ?? 'index';

    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->store($_POST);
                $message = 'Producto creado exitosamente';
                $type = 'success';
            }
            $productos = $controller->index();
            break;

        case 'edit':
            $id = (int) ($_GET['id'] ?? 0);
            $producto = $controller->show($id);
            if (!$producto) {
                throw new RuntimeException('Producto no encontrado');
            }
            include __DIR__ . '/../src/Views/editar.phtml';
            exit;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = (int) ($_GET['id'] ?? 0);
                $controller->update($id, $_POST);
                $message = 'Producto actualizado exitosamente';
                $type = 'success';
            }
            $productos = $controller->index();
            break;

        case 'delete':
            $id = (int) ($_GET['id'] ?? 0);
            $controller->destroy($id);
            $message = 'Producto eliminado exitosamente';
            $type = 'success';
            $productos = $controller->index();
            break;

        case 'index':
        default:
            $productos = $controller->index();
            break;
    }
} catch (InvalidArgumentException $e) {
    $message = $e->getMessage();
    $productos = $controller->index();
} catch (RuntimeException $e) {
    $message = $e->getMessage();
    $productos = $controller->index();
} catch (Exception $e) {
    $message = 'Error: ' . $e->getMessage();
    $productos = $controller->index();
}

include __DIR__ . '/../src/Views/index.phtml';