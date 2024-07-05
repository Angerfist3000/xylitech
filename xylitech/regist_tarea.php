<?php
//conexion de nuestro BD
include("con_DB.php");

session_start();

// Variables para controlar el estado del mensaje
$mensaje = "";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $asignado_a = $_POST['asignado_a'];

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO tarea (Descripcion, Fecha, Asignado) VALUES ('$descripcion', '$fecha', '$asignado_a')";

    if ($conn->query($sql) === TRUE) {
        // Configurar el mensaje de éxito
        $_SESSION['mensaje'] = "Guardado exitosamente";
    } else {
        $_SESSION['mensaje'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    // Redirigir para evitar la duplicación de datos al recargar la página
    header("Location: regist_tarea.php");
    exit();
}

// Obtener mensaje de la sesión si existe
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <!-- título de la página -->
    <title>XYLITECH</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Estilos adicionales para el mensaje emergente */
        .alert {
            position: fixed;
            top: 90%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            width: 300px;
            background-color: #dff0d8;
            border-color: #d6e9c6;
            padding: 10px;
            display: none; /* Inicialmente oculto */
        }
    </style>
</head>
<body class="bg-secondary">
   <!--aqui debe ir la barra de navegación -->
   <?php include("barra_nav.php"); ?>

<section class="container my-5 bg-light d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <!-- registro de tareas -->
    <div class="row w-100 justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center text-muted">Registrar Tareas</h3>
            <!-- formulario de registro -->
            <form method="POST" action="">
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Descripción</label>
                    <div class="col-sm-10">
                        <input type="text" name="descripcion" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Fecha</label>
                    <div class="col-sm-10">
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Asignado a</label>
                    <div class="col-sm-10">
                        <input type="text" name="asignado_a" class="form-control" required>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
            <!-- Mensaje emergente -->
            <?php if ($mensaje): ?>
                <div id="mensajeAlert" class="alert alert-success text-center">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

   <footer class="text-muted p-5 bg-info">
       <div class="container">
         <p class="text-center">Derechos reservados 2024</p>
       </div>
   </footer>
   <!-- librería de javascript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
   <script>
       // Mostrar el mensaje emergente
       <?php if ($mensaje): ?>
           document.getElementById('mensajeAlert').style.display = 'block';
           setTimeout(() => {
               document.getElementById('mensajeAlert').style.display = 'none';
           }, 3000); // Ocultar después de 3 segundos
       <?php endif; ?>
   </script>
</body>
</html>

</html>
