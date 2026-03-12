<?php
// Include class files (like libraries)
require_once 'Usuario.php';
require_once 'Admin.php';
require_once 'Alumno.php';

// Array to store users we manage to create
$listaUsuarios = array();

// --- TRY/CATCH TEST BLOCK ---

try {
    // 1. Create a valid Admin
    $admin1 = new Admin("Carlos Director", "carlos@gmail.edu");
    $listaUsuarios[] = $admin1; // Add to list

    // 2. Create a valid Student
    $alumno1 = new Alumno("Juan Perez", "juan@gmail.edu", "A00123456");
    $listaUsuarios[] = $alumno1; // Add to list

    // 3. Try to create a Student with INVALID EMAIL (This will fail)
    // The email "correo_malo" has no @ or domain, so it will throw Exception
    $alumnoMalo = new Alumno("Pedro Error", "correo_malo", "A00999999");
    $listaUsuarios[] = $alumnoMalo; 

} catch (Exception $e) {
    // Here we catch the error so the page doesn't break ugly
    echo "<div style='background-color: #ffcccc; border: 1px solid red; padding: 10px; margin-bottom: 20px;'>";
    echo "<strong>An error was detected!</strong> " . $e->getMessage();
    echo "</div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User System OOP</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>

    <h1>List of Created Users</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Student ID</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop through the list of users created successfully
            foreach ($listaUsuarios as $usuario) {
                echo "<tr>";
                echo "<td>" . $usuario->getNombre() . "</td>";
                echo "<td>" . $usuario->getCorreo() . "</td>";
                echo "<td>" . $usuario->getRol() . "</td>";
                
                // Only show student ID if object has that method (is Student)
                // Use method_exists to be safe, though we could use instanceof
                if (method_exists($usuario, 'getMatricula')) {
                    echo "<td>" . $usuario->getMatricula() . "</td>";
                } else {
                    echo "<td>-</td>"; // If Admin, put a dash
                }
                
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>