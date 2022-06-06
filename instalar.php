<?php
$config = include 'config.php';

try {
    $conexion = new PDO('mysql:host=' . $config['db']['host'], $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    // $sql = file_get_contents("data/migracion.sql");  //-->> este archivo tendría la query de crear una tabla

    // $conexion->exec($sql);  //-->> ejecuta la query 
    echo "La base de datos y la tabla se han creado con éxito";
} catch(PDOExcepcion $error) {
    echo $error->getMessage();
}
