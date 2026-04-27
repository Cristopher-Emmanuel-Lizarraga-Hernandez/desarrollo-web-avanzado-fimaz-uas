<?php
/**
 * Controller
 * Instituto: Universidad Tecnologica de Panama
 * Estudiante: Cristopher Hernandez
 * Fecha: 26/04/2026
 */

namespace App\Controllers;

use App\Models\ProductoModel;

class ProductoController
{
    private ProductoModel $model;

    public function __construct()
    {
        $this->model = new ProductoModel();
    }

    public function index(): array
    {
        return $this->model->getAll();
    }

    public function show(int $id): ?array
    {
        return $this->model->getById($id);
    }

    public function store(array $data): int
    {
        $this->validateData($data);
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $this->validateData($data, $id);
        return $this->model->update($id, $data);
    }

    public function destroy(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function search(string $term): array
    {
        return $this->model->search($term);
    }

    private function validateData(array $data, ?int $id = null): void
    {
        if (empty($data['nombre'])) {
            throw new \InvalidArgumentException("El nombre es requerido");
        }
        if (!isset($data['precio']) || $data['precio'] < 0) {
            throw new \InvalidArgumentException("El precio debe ser mayor o igual a 0");
        }
    }
}