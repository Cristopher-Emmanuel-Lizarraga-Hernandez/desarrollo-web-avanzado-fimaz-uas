Tarea de PHP - Sistema de Usuarios POO
Hola, este es mi proyecto para la clase de Programación Web. Aquí intento aplicar lo que aprendí sobre Clases, Objetos y Herencia en PHP.
¿Qué hace este código?
Básicamente creé un sistema pequeño donde hay usuarios normales, pero también hay Administradores y Alumnos. Lo importante es que si pones un correo electrónico mal escrito, el programa te avisa con un error y no deja crear al usuario.
Archivos que hice
Son 4 archivos en total:
Usuario.php: Es la clase principal. Aquí validé el correo.
Admin.php: Es un usuario pero con rol de administrador.
Alumno.php: Es un usuario pero tiene matrícula además del correo.
index.php: Aquí es donde pruebo todo y se ve la tabla en la página web.
¿Cómo correrlo?
Yo lo estoy usando con XAMPP, estos son los pasos que seguí:
Copié la carpeta del proyecto en C:\xampp\htdocs\.
Encendí el Apache en el panel de XAMPP.
Abrí el navegador y entré a http://localhost/desarrollo-web-avanzado-fimaz-uas/parcial-1-poo/examen-parcial/
Lo que cumple la tarea
Creo que cubrí todos los puntos que pidieron:
Hay una clase base Usuario.
Valida el correo en el constructor (si está mal lanza excepción).
La clase Admin hereda de Usuario y dice su rol.
La clase Alumno hereda de Usuario y tiene matrícula.
Usé try y catch para que salga el mensaje de error bonito y no se rompa la página.
Se muestra una tabla HTML con los usuarios.