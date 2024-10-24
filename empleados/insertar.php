<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar un nuevo empleado</title>
</head>
<body>
    <?php
    require '../auxiliar/auxiliar.php';

    $numero = obtener_post('numero');
    $nombre = obtener_post('nombre');
    $apellidos = obtener_post('apellidos');
    $departamento_id = obtener_post('departamento_id');
    $_csrf = obtener_post('_csrf');
    $pdo = conectar() or die("No se ha podido establecer una conexión");

    if (isset($numero, $nombre, $apellidos, $departamento_id)) {
        comparar_csrf($_csrf);
        $errores = [];
        // comprobar_numero($numero, $errores, $pdo);
        // comprobar_nombre($nombre, $errores, $pdo);
        // comprobar_apellidos($apellidos, $errores);
        comprobar_departamento_id($departamento_id, $errores, $pdo);

        if (!empty($errores)) {
            mostrar_errores($errores);
        } else {
            $stmt = $pdo->prepare('INSERT INTO empleados
                               (numero, nombre, apellidos, departamento_id)
                           VALUES (:numero, :nombre, :apellidos, :departamento_id)');
            $stmt->execute([
                ':numero' => $numero,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':departamento_id' => $departamento_id,
            ]);
            $_SESSION['exito'] = 'El empleado se ha insertado correctamente';
            volver_empleados();
            return;
        }
    }

    cabecera();
    crear_csrf();
    ?>
    <form action="" method="post">
        <input type="hidden" name="_csrf" value="<?= $_SESSION['_csrf'] ?>">
        <label>
            Número:
            <input type="text" name="numero" value="<?= hh($numero) ?>">
        </label>
        <br>
        <label>
            Nombre:
            <input type="text" name="nombre" value="<?= hh($nombre) ?>">
        </label>
        <br>
        <label>
            Apellidos:
            <input type="text" name="apellidos" value="<?= hh($apellidos) ?>">
        </label>
        <br>
        <label>
            Departamento:
            <select name="departamento_id">
                <option value="">(Ninguno)</option>
                <?php foreach (departamentos() as $dep): ?>
                    <option value="<?= hh($dep['id']) ?>">
                        <?= "({$dep['codigo']}) {$dep['denominacion']}" ?>
                    </option>
                <?php endforeach ?>
            </select>
        </label>
        <br>
        <button type="submit">Insertar</button>
        <a href="empleados.php">Cancelar</a>
    </form>
</body>
</html>
