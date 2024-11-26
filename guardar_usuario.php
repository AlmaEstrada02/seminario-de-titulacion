<?php
// Configuración de la base de datos
$host = "localhost";
$dbname = "asistencia_app";
$username = "root";
$password = "";

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Modo de error

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Verificar los datos recibidos
        print_r($_POST);

        // Recoger los datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $id_usuario = $_POST['id_usuario'] ?? '';
        $password = md5($_POST['password'] ?? ''); // Encriptar la contraseña con MD5

        if (empty($nombre) || empty($email) || empty($id_usuario) || empty($password)) {
            die("Error: Todos los campos son obligatorios.");
        }

        // Agregar el usuario en la base de datos con el formulario
        $sql = "INSERT INTO usuario (nombre, email, id_usuario, password) VALUES (:nombre, :email, :id_usuario, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            // Volver al inicio después de registrar
            header("Location: inicio.html");
            exit;
        } else {
            echo "Error al registrar el usuario";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
