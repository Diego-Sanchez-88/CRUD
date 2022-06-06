<?php   
include 'funciones.php';
csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if(isset($_POST['submit'])){
    $resultado = [
        'error' => false,
        'mensaje' => 'El ítem ' . escapar($_POST['nombre']) . ' agregado con éxito'
    ];

    $config = include 'config.php';

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $item = [
            "nombre" => $_POST['nombre'],
            "apellido" => $_POST['apellido'],
            "email" => $_POST['email'],
            "edad" => $_POST['edad'],
        ];

        $consultaSQL = "INSERT INTO items (nombre, apellido, email, edad)";
        $consultaSQL .= "values (:" . implode(", :", array_keys($item)) . ")";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($item);

    } catch(PDOExcepcion $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

?>

<?php include "templates/header.php"; ?>

<?php 
if(isset($resultado)){
    ?>
    <div class="container mt-e">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                <?= $resultado['mensaje'] ?> 
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">Crear un ítem</h2>
            <hr>
            <form method="post" class="form-inline">
                <div class="form-group mr-3">
                    <input type="text" id="apelllido" name="apellido" placeholder="Buscar por apellido" class="form-control">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
            </form>
            

            <form action="post">
                <div class="form-group">
                    <label for="">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Edad</label>
                    <input type="text" name="edad" id="edad" class="form-control">
                </div>
                <div class="form-group">
                    <label for=""></label>
                    <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
                    <a href="index.php" class="btn btn-primary">Volver a inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include "templates/footer.php"; ?>