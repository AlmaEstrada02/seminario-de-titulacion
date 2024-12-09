<?php
// Configuración de la base de datos
$host = "localhost";
$dbname = "asistencia_app";
$username = "root";
$password = "";

try {
    // Conexión a la base de datos
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validar y limpiar datos
        $nombre = trim(htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'));
        $email = trim(filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL));
        $id_usuario = trim(filter_var($_POST['id_usuario'] ?? '', FILTER_SANITIZE_NUMBER_INT));
        $password = $_POST['password'] ?? '';
    
        // Validar que la contraseña cumpla con los requisitos
        $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordPattern, $password)) {
            die("Error: La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial.");
        }
    
        // Cifrar la contraseña de manera segura
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        if (empty($nombre) || !$email || empty($id_usuario) || empty($password)) {
            die("Error: Todos los campos son obligatorios o no válidos.");
        }
    
        // Agregar el usuario en la base de datos
        $sql = "INSERT INTO usuario (nombre, email, id_usuario, password) VALUES (:nombre, :email, :id_usuario, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':password', $hashedPassword);
    
        if ($stmt->execute()) {
            // Redirigir al inicio después de registrar
            header("Location: inicio.html", true, 302);
            exit;
        } else {
            echo "Error al registrar el usuario.";
        }
    }
    
} catch (PDOException $e) {
    // Registrar el error en un log
    error_log("Error: " . $e->getMessage(), 3, '/path/to/error.log');
    die("Error del servidor. Por favor, inténtelo más tarde.");
}
?>
