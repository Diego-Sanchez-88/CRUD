<?php

include 'funciones.php';
csrf();

$error = false;
$config = include 'config.php';

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    if (isset($_POST['apellido'])) {
        $consultaSQL = "SELECT * FROM items WHERE apellido LIKE '%" . $_POST['apellido'] . "%'";
    } else {
        $consultaSQL = "SELECT * FROM items";
    }

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $items = $sentencia->fetchAll();
} catch (PDOException $error) {
    $error = $error->getMessage();
}

$titulo = isset($_POST['apellido']) ? 'Lista de items (' . $_POST['apellido'] . ')' : 'Lista de items';
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<!-- hay que agregar este campo oculto en todos los formularios de la aplicación:
    <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>"> -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="crear.php" class="btn btn-primary mt-4">Crear ítem</a>
            <hr>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Edad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($items && $sentencia->rowCount() > 0) {
                            foreach ($items as $item)
                        ?>
                            <tr>
                                <td><?php echo escapar($fila["id"]); ?></td>
                                <td><?php echo escapar($fila["nombre"]); ?></td>
                                <td><?php echo escapar($fila["apellido"]); ?></td>
                                <td><?php echo escapar($fila["email"]); ?></td>
                                <td><?php echo escapar($fila["edad"]); ?></td>
                                <td>
                                    <a href="<?= 'editar.php?id=' . escapar($fila["id"]) ?>">Editar</a>
                                    <a href="<?= 'borrar.php?id=' . escapar($fila["id"]) ?>">Borrar</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </h2>
        </div>
    </div>
</div>

<?php include "templates/footer.php"; ?>