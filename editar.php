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

if(!isset($_GET['id'])){
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El ítem no existe';
}

if(isset($_POST['submit'])){
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']); 

        $item = [
            "id" => $_GET['id'],
            "nombre" => $_GET['nombre'],
            "apellido" => $_GET['apellido'],
            "email" => $_GET['email'],
            "edad" => $_GET['edad']
        ];

    } catch() {

    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
    $id = $_GET['id'];
    $consultaSQL = "SELECT * FROM items WHERE id = " . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $item = $sentencia->fetch(PDO::FETCH_ASSOC);

    if(!$item){
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se encuentra el item';
    } 
} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}

?>

<?php require "templates/header.php"; ?>

<?php 
if($resultado['error']){
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <?= $resultado['mensaje'] ?>
                </div>
            </div>
        </div>
    </div>

<?php    
}
if(isset($_POST['submit']) && !$resultado['error']){
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    El ítem se ha actualizado correctamente
                </div>
            </div>
        </div>
    </div>
<?php
}
if(isset($item) && $item){
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando el ítem <?= escapar($item['nombre']) . ' ' . escapar($item['apellido'])?></h2>
                <hr>
                <form mehot="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?= escapar($item['nombre']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" name="apellido" id="apellido" value="<?= escapar($item['apellido']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">email</label>
                        <input type="email" name="email" id="email" value="<?= escapar($item['email']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edad">Edad</label>
                        <input type="text" name="edad" id="edad" value="<?= escapar($item['edad']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="submit" name="submit" value="Actualizar" class="btn btn-primary">
                        <a href="index.php" class="btn btn-primary">Volver a inicio</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>


<?php require "templates/footer.php"; ?>