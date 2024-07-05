<?php
//conexion de BD
include("con_DB.php");

session_start();

// Variables para controlar el estado del mensaje
$mensaje = "";
$mostrarMensaje = false;

//Verificar si hay una búsqueda
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : "";

//Consulta SQL para obtener las tareas
if ($buscar) {
    $sql = "SELECT * FROM tarea WHERE Descripcion LIKE '%$buscar%' OR ID LIKE '%$buscar%'";
} else {
    $sql = "SELECT * FROM tarea";
}

$result = $conn->query($sql);

//Mensaje de búsqueda sin resultados
if ($buscar && $result->num_rows == 0) {
    $_SESSION['mensaje'] = "No se encontraron resultados para: $buscar";
    $mostrarMensaje = true;
}

//Editar tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $asignado_a = $_POST['asignado_a'];

    $sql = "UPDATE tarea SET Descripcion='$descripcion', Fecha='$fecha', Asignado='$asignado_a' WHERE ID=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensaje'] = "Tarea actualizada exitosamente";
    } else {
        $_SESSION['mensaje'] = "Error actualizando la tarea: " . $conn->error;
    }
    $mostrarMensaje = true;
}

// Eliminar tarea
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM tarea WHERE ID=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensaje'] = "Tarea eliminada exitosamente";
        // Verificar si no quedan más tareas
        $sql = "SELECT COUNT(*) AS total FROM tarea";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($row['total'] == 0) {
            // Restablecer el valor de autoincremento
            $sql = "ALTER TABLE tarea AUTO_INCREMENT = 1";
            $conn->query($sql);
        }
    } else {
        $_SESSION['mensaje'] = "Error eliminando la tarea: " . $conn->error;
    }
    $mostrarMensaje = true;
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
    <title>XYLITECH</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Barra de navegación -->
    <?php include("barra_nav.php"); ?>
    <style>
        /* Estilos adicionales para el mensaje emergente */
        .alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 300px;
            background-color: #dff0d8;
            border-color: #d6e9c6;
            padding: 10px;
            display: none; /* Inicialmente oculto */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Estilo para ID en negrita */
        .bold-id {
            font-weight: bold;
        }

        /* Margen entre botones */
        .btn-spacing {
            margin-right: 5px;
        }
    </style>
</head>

<body class="bg-secondary">
    <!-- Contenedor principal -->
    <section class="container my-5 bg-light d-flex justify-content-center align-items-center" style="min-height: 80vh;">

        <!-- Registro de tareas -->
        <div class="row container my-5">
            <div class="col-lg-12">
                <h3 class="text-center text-muted">Listado de tareas</h3>

                <!-- Buscador -->
                <form method="GET" action="?" class="m-4">
                    <input class="form-control" type="search" name="buscar" placeholder="Buscar por descripción o ID" value="<?php echo htmlspecialchars($buscar); ?>">
                </form>

                <!-- Tabla de tareas registradas -->
                <table class="table table-primary table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Asignado a</th>
                            <th scope="col">Opción</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        if ($result->num_rows > 0) {
                            // Mostrar los datos de cada fila
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='bold-id'>" . $row["ID"] . "</td>";
                                echo "<td>" . $row["Descripcion"] . "</td>";
                                echo "<td>" . $row["Fecha"] . "</td>";
                                echo "<td>" . $row["Asignado"] . "</td>";
                                echo "<td>
                                    <button class='btn btn-primary btn-spacing' onclick='openEditModal(" . json_encode($row) . ")'>Editar</button>
                                    <button class='btn btn-danger btn-spacing' onclick='confirmDelete(" . $row["ID"] . ")'>Eliminar</button>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No hay tareas registradas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Termina tabla -->

            </div>

        </div>

    </section>
    <footer class="text-muted p-5 bg-info">
        <div class="container">
            <p class="text-center">Derechos reservados 2024</p>
        </div>
    </footer>


    <!-- libreria de javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <!-- Mensaje emergente -->
    <div id="mensajeAlert" class="alert alert-success text-center">
        <?php echo $mensaje; ?>
    </div>
    <!-- Modal de edición -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h4 class="text-center">Editar Tarea</h4>
            <form method="POST" action="">
                <input type="hidden" id="edit-id" name="id">
                <div class="row mb-3">
                    <label class="col-sm-2">Descripción</label>
                    <div class="col-sm-10">
                        <input type="text" id="edit-descripcion" name="descripcion" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2">Fecha</label>
                    <div class="col-sm-10">
                        <input type="date" id="edit-fecha" name="fecha" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2">Asignado a</label>
                    <div class="col-sm-10">
                        <input type="text" id="edit-asignado" name="asignado_a" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="editar" class="btn btn-success">Actualizar</button>
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h4 class="text-center">Confirmar Eliminación</h4>
            <p class="text-center">¿Estás seguro de eliminar esta tarea?</p>
            <div class="text-center">
                <button id="confirm-delete-btn" class="btn btn-danger">Eliminar</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Scripts de JavaScript -->
    <script>
        // Función para abrir el modal de edición
        function openEditModal(tarea) {
            document.getElementById('edit-id').value = tarea.ID;
            document.getElementById('edit-descripcion').value = tarea.Descripcion;
            document.getElementById('edit-fecha').value = tarea.Fecha;
            document.getElementById('edit-asignado').value = tarea.Asignado;
            document.getElementById('editModal').style.display = "block";
        }

        // Función para cerrar el modal de edición
        function closeEditModal() {
            document.getElementById('editModal').style.display = "none";
        }

        // Función para mostrar mensaje de actualización
        function showUpdateMessage() {
            document.getElementById('mensajeAlert').style.display = 'block';
            setTimeout(() => {
                document.getElementById('mensajeAlert').style.display = 'none';
                // Redireccionar a la página principal después de ocultar el mensaje
                window.location.href = 'tareas.php';
            }, 1000); // Ocultar después de 3 segundos
        }

        // Función para confirmar la eliminación de una tarea
        function confirmDelete(id) {
            document.getElementById('confirm-delete-btn').setAttribute('onclick', 'deleteTask(' + id + ')');
            document.getElementById('deleteModal').style.display = "block";
        }

        // Función para cerrar el modal de eliminación
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = "none";
        }

        // Función para eliminar una tarea
        function deleteTask(id) {
            window.location.href = "?eliminar=" + id;
        }

        /*/ Cerrar el modal cuando el usuario hace clic fuera del contenido del modal
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
            if (event.target == document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
        }/*/

        // Mostrar el mensaje emergente si hay uno
        <?php if ($mostrarMensaje): ?>
            showUpdateMessage();
        <?php endif; ?>
    </script>

</body>

</html>
