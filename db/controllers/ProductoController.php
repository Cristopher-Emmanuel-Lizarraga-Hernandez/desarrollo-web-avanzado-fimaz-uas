<?php
namespace Controllers;

use Models\ProductoModel;

class ProductoController {
    private ProductoModel $ProductoModel;

    public function __construct() {
        $this->ProductoModel = new ProductoModel();
    }

    private function verificarSesion() {
        if(session_status()===PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION ['admin'])) {
            header('Location: index.php?route=login');
            exit;
        }
    }

    public function index(){
        $this->verificarSesion();
        $productos =$this->ProductoModel->ObtenerTodos();
        require_once __DIR__. '/../views/productos/index.php';
    }

    public function create(){
        $this->verificarSesion();
        require_once __DIR__. '/../views/productos/create.php';
    }

    public function store(): void{
        $this->verificarSesion();

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_compra' => floatval($_POST['precio_compra'] ?? ''),
            'precio_venta' => floatval($_POST['precio_venta'] ?? ''),
            'existencia' => intval($_POST['existencia'] ?? '')
        ];

        if(
            $data['sku'] === '' ||
            $data['nombre'] === '' ||
            $data['descripcion'] === '' ||
            $data['precio_compra'] <= '' ||
            $data['precio_venta'] <= '' ||
            $data['existencia'] < ''
            
        ){
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('location: index.php?route=productos/create');
            exit;
        }

        if(!is_numeric($data['precio_compra']) || !is_numeric($data['precio_venta'])
            || !is_numeric($data['existencia'])){
            $_SESSION['error'] = 'precio compra, precio venta y 
            existencia deben ser numericos.';
            header('Location: index.php?route=productos/create');
            exit;
        }

        if((float)$data['precio_compra'] <0 || (float)$data['precio_venta']< 0
        || (int)$data['existencia']<0){
            $_SESSION['error'] = 'No se permiten valores negativos.';
            header('Location: index.php?route=productos/create');
            exit;
        }
        
        if($this->ProductoModel->create($data)) {
            $_SESSION['success'] = 'producto registrado correctamente.';
        }else {
            $_SESSION['error'] = 'no fue posible registrar el producto.';
        }

        header('Location: index.php?route=productos');
        exit;
    }

    public function edit(): void {
        $this->verificarSesion();

        $id = (int)($_GET['id'] ?? 0);
        $producto = $this->ProductoModel->buscarPorId($id);

        if (!$producto){
            $_SESSION['error']= 'producto no encontrado.';
            header('Location: index.php?route=productos');
            exit;
        }
        require_once __DIR__. '/../views/productos/edit.php';
    }

    public function update(): void {
        $this->verificarSesion();
        $id = (int)($_POST['id'] ?? 0);

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_compra' => trim($_POST['precio_compra'] ?? ''),
            'precio_venta' => trim($_POST['precio_venta'] ?? ''),
            'existencia' => trim($_POST['existencia'] ?? '')
        ];

        if ($id <= 0){
            $_SESSION['error'] = 'ID inválido';
            header('Location: index.php?route=productos');
            exit;
        }

        if(
            $data['sku'] === '' ||
            $data['nombre'] === '' ||
            $data['descripcion'] === '' ||
            $data['precio_compra'] <= '' ||
            $data['precio_venta'] <= '' ||
            $data['existencia'] < ''
            
        ){
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('location: index.php?route=productos/edit&id=' . $id);
            exit;
        }

        if(!is_numeric($data['precio_compra']) || !is_numeric($data['precio_venta'])
            || !is_numeric($data['existencia'])){
            $_SESSION['error'] = 'precio compra, precio venta y 
            existencia deben ser numericos.';
            header('Location: index.php?route=productos/edit&id=' . $id);
            exit;
        }

        if((float)$data['precio_compra'] <0 || (float)$data['precio_venta']< 0
        || (int)$data['existencia']<0){
            $_SESSION['error'] = 'No se permiten valores negativos.';
            header('Location: index.php?route=productos/edit&id=' . $id);
            exit;
        }

        if($this->ProductoModel->actualizar($id, $data)) {
            $_SESSION['success'] = 'producto actualizado correctamente.';
        }else {
            $_SESSION['error'] = 'no fue posible actualizar el producto.';
        }

        header('Location: index.php?route=productos');
        exit;
    }

    public function delete(): void{
        $this->verificarSesion();

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0){
            $_SESSION['error'] = 'ID inválido';
            header('Location: index.php?route=productos');
            exit;
        }

        if($this->ProductoModel->eliminar($id)) {
            $_SESSION['success'] = 'producto eliminado correctamente.';
        }else {
            $_SESSION['error'] = 'no fue posible eliminar el producto.';
        }

        header('Location: index.php?route=productos');
        exit;
    }
}
?>