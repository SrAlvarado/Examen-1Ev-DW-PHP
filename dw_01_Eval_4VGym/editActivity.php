<?php

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/persistence/DAO/ActivityDAO.php';
require_once ROOT_PATH . '/model/Activity.php';
require_once ROOT_PATH . '/utils/SessionHelper.php';
require_once ROOT_PATH . '/utils/validation_functions.php';

SessionHelper::setLastViewedPage(SessionHelper::LIST_PAGE); // Siempre redirige al listado tras la edición

$dao = new ActivityDAO();
$errors = [];
$activity_id = null;
$tipos_permitidos = ['Spinning', 'BodyPump', 'Pilates']; // Usado en la vista

$form_data = ['id' => null, 'type' => '', 'monitor' => '', 'place' => '', 'date' => ''];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $activity_id = (int)$_GET['id'];
} elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $activity_id = (int)$_POST['id'];
}

if ($activity_id === null || $activity_id <= 0) {
    header("Location: listActivities.php?message=error_id_missing");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $datos_recibidos = [
            'type' => trim($_POST['type'] ?? ''),
            'monitor' => trim($_POST['monitor'] ?? ''),
            'place' => trim($_POST['place'] ?? ''),
            'date' => trim($_POST['date'] ?? '')
    ];
    $form_data = array_merge($form_data, $datos_recibidos);

    $errores_encontrados = validar_datos_formulario_de_actividad($datos_recibidos);

    if (empty($errores_encontrados)) {

        $activity_dto = new Activity(
                $activity_id, // Usamos el ID recuperado para el UPDATE
                $datos_recibidos['type'],
                $datos_recibidos['monitor'],
                $datos_recibidos['place'],
                $datos_recibidos['date']
        );

        if ($dao->update($activity_dto)) {
            header("Location: listActivities.php?message=success_update");
            exit;
        } else {
            $errores_encontrados['general'] = 'Hubo un error al guardar los cambios en la base de datos.';
        }
    }
    $errors = $errores_encontrados;
}


// D. Precarga de Datos (Para GET o POST con errores)
$actividad_existente = $dao->findById($activity_id);

// Si la actividad no existe, redirigir
if ($actividad_existente === null) {
    header("Location: listActivities.php?message=error_not_found");
    exit;
}

// Si es GET o POST con errores, cargamos los datos para el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET' || !empty($errors)) {

    // Si es GET, cargamos los datos de la BD (si no es POST con errores, los datos del POST tienen prioridad)
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $form_data['id'] = $actividad_existente->id;
        $form_data['type'] = $actividad_existente->type;
        $form_data['monitor'] = $actividad_existente->monitor;
        $form_data['place'] = $actividad_existente->place;

        // Formateamos la fecha a YYYY-MM-DDTHH:MM para el input datetime-local
        try {
            $dateTime = new DateTime($actividad_existente->date);
            $form_data['date'] = $dateTime->format('Y-m-d\TH:i');
        } catch (Exception $e) {
            $form_data['date'] = $actividad_existente->date;
        }
    }
}

$pageTitle = 'Editar Actividad ' . $activity_id;
include ROOT_PATH . '/includes/header.php';
?>

    <div class="container mt-5">
        <h1 class="mb-4">Editar Actividad (ID: <?= htmlspecialchars($activity_id) ?>)</h1>

        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form class="form-horizontal" method="POST" action="editActivity.php">

            <input type="hidden" name="id" value="<?= htmlspecialchars($form_data['id']) ?>">

            <div class="form-group mb-3">
                <label for="type" class="form-label">Tipo</label>
                <div class="col-sm-10">
                    <select id="type" class="form-control" name="type" required>
                        <option value="">-- Seleccione Tipo --</option>
                        <?php foreach ($tipos_permitidos as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>"
                                    <?= ($form_data['type'] === $type) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['type'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['type']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="monitor" class="form-label">Monitor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="monitor" id="monitor"
                           placeholder="Nombre del Monitor" required
                           value="<?= htmlspecialchars($form_data['monitor']) ?>">
                    <?php if (isset($errors['monitor'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['monitor']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="place" class="form-label">Lugar</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="place" id="place"
                           placeholder="Ej: Aula 14, Sala B" required
                           value="<?= htmlspecialchars($form_data['place']) ?>">
                    <?php if (isset($errors['place'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['place']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="date" class="form-label">Fecha y Hora</label>
                <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" name="date" id="date" required
                           value="<?= htmlspecialchars($form_data['date']) ?>">
                    <?php if (isset($errors['date'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['date']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">Guardar Edición</button>
                </div>
            </div>
        </form>
    </div>

<?php

include ROOT_PATH . '/includes/footer.php';
?>