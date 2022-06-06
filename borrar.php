<?php
include 'funciones.php';
csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $id = $_GET['id'];
    $consultaSQL = "DELETE FROM items WHERE id = " . $id;
    
    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    header('Location: /index.php');
} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
} 

?>


<?php require "templates/header.php"; ?>

<div class="container mt-2">
    <div class="row">
        <div class="col md-12">
            <div class="alert alert-danger" role="alert">
                <?php $resultado['mensaje'] ?>
            </div>
        </div>
    </div>
</div>


<?php require "templates/footer.php"; ?> 