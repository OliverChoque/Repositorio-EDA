<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = 'localhost'; // Cambia esto si es necesario
$dbname = 'biblioteca';
$username = 'root'; // Cambia esto si es necesario
$password = ''; // Cambia esto si es necesario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$accion = $_GET['action'] ?? null;

switch ($accion) {
    case 'insertar':
        $titulo = $_POST['titulo'] ?? '';
        $autor = $_POST['autor'] ?? '';

        $stmt = $pdo->prepare('INSERT INTO libros (titulo, autor) VALUES (:titulo, :autor)');
        $stmt->execute(['titulo' => $titulo, 'autor' => $autor]);

        $id = $pdo->lastInsertId();
        echo json_encode(['mensaje' => 'Libro insertado correctamente', 'data' => ['id' => $id, 'titulo' => $titulo, 'autor' => $autor]]);
        break;

    case 'buscar':
        $id = $_GET['id'] ?? null;

        $stmt = $pdo->prepare('SELECT * FROM libros WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['data' => $libro ? $libro : 'Libro no encontrado']);
        break;

    case 'actualizar':
        $id = $_POST['id'] ?? null;
        $nuevoTitulo = $_POST['titulo'] ?? null;
        $nuevoAutor = $_POST['autor'] ?? null;

        $fields = [];
        $params = ['id' => $id];

        if ($nuevoTitulo) {
            $fields[] = 'titulo = :titulo';
            $params['titulo'] = $nuevoTitulo;
        }
        if ($nuevoAutor) {
            $fields[] = 'autor = :autor';
            $params['autor'] = $nuevoAutor;
        }

        if ($fields) {
            $stmt = $pdo->prepare('UPDATE libros SET ' . implode(', ', $fields) . ' WHERE id = :id');
            $stmt->execute($params);

            echo json_encode(['mensaje' => 'Libro actualizado correctamente']);
        } else {
            echo json_encode(['mensaje' => 'Ningún campo para actualizar']);
        }
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? null;

        $stmt = $pdo->prepare('DELETE FROM libros WHERE id = :id');
        $stmt->execute(['id' => $id]);

        echo json_encode(['mensaje' => 'Libro eliminado correctamente']);
        break;

    case 'verTodos':
        $stmt = $pdo->query('SELECT * FROM libros');
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['data' => $libros]);
        break;

    default:
        echo json_encode(['mensaje' => 'Acción no válida']);
        break;
}
?>
