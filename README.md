# Examen Parcial - PDO con Namespaces, Try/Catch y Autoload

## Instituto
UTP

## Estudiante
Cristopher Hernandez

## Fecha
26/04/2026

## Descripcion
Sistema CRUD de productos desarrollado con las mejores practicas de PHP:
- **Namespaces**: Organizacion del codigo en espacios de nombres
- **PDO**: Manipulador de Objetos de PHP para bases de datos
- **Try/Catch**: Manejo de excepciones
- **Autoload**: Carga automatica de clases con Composer

## Requisitos
- PHP 7.4+
- MySQL/MariaDB
- Composer

## Instalacion

1. Importar la base de datos:
```sql
mysql -u root -p < database.sql
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar credenciales en `src/Database/DatabaseConnection.php`

4. Ejecutar en servidor:
```bash
php -S localhost:8000 -t public
```

## Estructura del Proyecto
```
Examen_Parcial/
├── public/
│   └── index.php          # Punto de entrada
├── src/
│   ├── Config/
│   │   └── Config.php
│   ├── Database/
│   │   └── DatabaseConnection.php
│   ├── Models/
│   │   └── ProductoModel.php
│   ├── Controllers/
│   │   └── ProductoController.php
│   └── Views/
│       ├── index.phtml
│       └── editar.phtml
├── vendor/               # Dependencias Composer
├── composer.json
└── database.sql
```

## Caracteristicas
- CRUD completo de productos
- Conexion PDO con manejo de excepciones
- Validacion de datos
- Interfaz responsive
- Informacion del estudiante visible