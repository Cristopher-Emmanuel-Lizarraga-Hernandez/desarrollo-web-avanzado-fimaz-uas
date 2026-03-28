<?php
require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/models/Producto.php";
require_once __DIR__ . "/controllers/ProductoController.php";

$controller = new ProductoController(); // ← sin 's'

$mensaje = "";
$productoEditar = null;
$terminoBusqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $idEliminar = $_GET['eliminar'];
    if ($controller->eliminar($idEliminar)) { // ❌ $controllers → $controller
        $mensaje = "Producto eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el producto.";
    }
}

// EDITAR: CARGAR DATOS EN FORMULARIO
if (isset($_GET['editar'])) {
    $idEditar = $_GET['editar'];
    $productoEditar = $controller->obtenerPorId($idEditar); // ❌ $controllers → $controller
}

// GUARDAR O ACTUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = !empty($_POST['id']) ? $_POST['id'] : null;
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $existencia  = (int)   $_POST['existencia'];
    $precio      = (float) $_POST['precio'];

    $producto = new Producto();
    $producto->setId($id);
    $producto->setNombre($nombre);
    $producto->setDescripcion($descripcion);
    $producto->setExistencia($existencia);
    $producto->setPrecio($precio);

    if ($id) {
        if ($controller->actualizar($producto)) { // ❌ $controllers → $controller
            $mensaje = "Producto actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el producto.";
        }
    } else {
        if ($controller->crear($producto)) { // ❌ $controllers → $controller
            $mensaje = "Producto agregado correctamente.";
        } else {
            $mensaje = "Error al agregar el producto.";
        }
    }
}

// ❌ listar() no existe → obtenerTodos()
if ($terminoBusqueda !== '') {
    $productos = $controller->buscar($terminoBusqueda);
} else {
    $productos = $controller->obtenerTodos(); // ❌ listar() → obtenerTodos()
}

// ── El HTML queda igual, solo corrige el formulario de editar ─
// $productoEditar ahora es un objeto Producto, no un array
// cambia $productoEditar['campo'] → $productoEditar->getCampo()
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Productos con PHP PDO y POO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    <h1 class="text-center mb-4">CRUD de Productos con PHP, PDO y POO</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <?php echo $productoEditar ? "Editar producto" : "Agregar producto"; ?>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- ❌ $productoEditar['id'] → $productoEditar->getId() -->
                <input type="hidden" name="id" value="<?php echo $productoEditar ? $productoEditar->getId() : ''; ?>">

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control"
                               value="<?php echo $productoEditar ? htmlspecialchars($productoEditar->getNombre()) : ''; ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcion" class="form-control"
                               value="<?php echo $productoEditar ? htmlspecialchars($productoEditar->getDescripcion()) : ''; ?>" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Existencia</label>
                        <input type="number" name="existencia" class="form-control"
                               value="<?php echo $productoEditar ? $productoEditar->getExistencia() : ''; ?>" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control"
                               value="<?php echo $productoEditar ? $productoEditar->getPrecio() : ''; ?>" required>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <?php echo $productoEditar ? "Actualizar" : "Guardar"; ?>
                        </button>
                    </div>
                </div>

                <?php if ($productoEditar): ?>
                    <a href="index.php" class="btn btn-secondary">Cancelar edición</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            Lista de productos
        </div>
        <div class="card-body">
            <form method="GET" action="" class="row g-2 mb-3">
                <div class="col-md-10">
                    <input type="text" name="buscar" class="form-control"
                        placeholder="Buscar por nombre o descripción"
                        value="<?php echo htmlspecialchars($terminoBusqueda); ?>">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
                <?php if ($terminoBusqueda !== ''): ?>
                    <div class="col-12">
                        <a href="index.php" class="btn btn-secondary btn-sm">Mostrar todos</a>
                    </div>
                <?php endif; ?>
            </form>

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th>Precio</th>
                        <th width="180">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($productos) > 0): ?>
                        <?php foreach ($productos as $p): ?>
                            <tr>
                                <td><?php echo $p->getId(); ?></td>
                                <td><?php echo htmlspecialchars($p->getNombre()); ?></td>
                                <td><?php echo htmlspecialchars($p->getDescripcion()); ?></td>
                                <td><?php echo $p->getExistencia(); ?></td>
                                <td>$<?php echo number_format($p->getPrecio(), 2); ?></td>
                                <td>
                                    <a href="index.php?editar=<?php echo $p->getId(); ?>"
                                       class="btn btn-warning btn-sm">Editar</a>
                                    <a href="index.php?eliminar=<?php echo $p->getId(); ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Seguro que deseas eliminar este producto?');">
                                       Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay productos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>