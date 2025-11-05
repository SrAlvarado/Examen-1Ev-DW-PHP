<?php

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/persistence/DAO/ActivityDAO.php';
require_once ROOT_PATH . '/model/Activity.php';
require_once ROOT_PATH . '/utils/SessionHelper.php';

SessionHelper::setLastViewedPage(SessionHelper::CREATE_PAGE);

$errors = [];
$form_data = [
    'type' => '',
    'monitor' => '',
    'place' => '',
    'date' => ''
];
$allowed_types = ['Spinning', 'BodyPump', 'Pilates'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form_data['type'] = trim($_POST['type'] ?? '');
    $form_data['monitor'] = trim($_POST['monitor'] ?? '');
    $form_data['place'] = trim($_POST['place'] ?? '');
    $form_data['date'] = trim($_POST['date'] ?? ''); // Fecha/hora en formato datetime-local
    // 2. Validar datos
    if (empty($form_data['type'])) $errors['type'] = 'El Tipo de actividad es obligatorio.';
    if (empty($form_data['monitor'])) $errors['monitor'] = 'El nombre del Monitor es obligatorio.';
    if (empty($form_data['place'])) $errors['place'] = 'El Lugar es obligatorio.';
    if (empty($form_data['date'])) $errors['date'] = 'La Fecha y hora son obligatorias.';

    if (!in_array($form_data['type'], $allowed_types)) {
        $errors['type'] = 'Tipo de actividad no válido. Debe ser Spinning, BodyPump o Pilates.';
    }

    if (empty($errors['date'])) {
        try {
            $fecha_actividad = new DateTime($form_data['date']);
            $fecha_actual = new DateTime('now');

            if ($fecha_actividad <= $fecha_actual) {
                $errors['date'] = 'La actividad debe ser programada para una fecha y hora futura.';
            }
        } catch (Exception $e) {
            $errors['date'] = 'Formato de fecha y hora inválido.';
        }
    }


    if (empty($errors)) {
        $activity_dto = new Activity(
            null,
            $form_data['type'],
            $form_data['monitor'],
            $form_data['place'],
            $form_data['date']
        );

        $dao = new ActivityDAO();
        $new_id = $dao->create($activity_dto);

        if ($new_id !== false) {
            header("Location: listActivities.php?message=success_create");
            exit;
        } else {
            $errors['general'] = 'Hubo un error al guardar la actividad en la base de datos.';
        }
    }
}

$pageTitle = 'Crear Nueva Actividad';
include ROOT_PATH . '/includes/header.php';
?>

    <div class="container mt-5">
        <h1 class="mb-4">Crear Nueva Actividad</h1>

        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <form class="form-horizontal" method="POST" action="createActivity.php">

            <div class="form-group mb-3">
                <label for="type" class="form-label">Tipo</label>
                <div class="col-sm-10">
                    <select id="type" class="form-control" name="type" required>
                        <option value="">-- Seleccione Tipo --</option>
                        <?php foreach ($allowed_types as $type): ?>
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
                        <!-- htmlspecialchars() convierte caracteres especiales (como <, >, &, ") a entidades HTML.-->
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
                    <button type="submit" class="btn btn-primary">Insertar Actividad</button>
                </div>
            </div>
        </form>
    </div>

<?php
include ROOT_PATH . '/includes/footer.php';
?>