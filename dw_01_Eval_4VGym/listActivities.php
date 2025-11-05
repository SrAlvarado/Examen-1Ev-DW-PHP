<?php
define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/persistence/DAO/ActivityDAO.php';
require_once ROOT_PATH . '/utils/SessionHelper.php';

$dao = new ActivityDAO();
$mensaje_resultado = '';
$filtro_fecha = null;

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id_a_borrar = (int)$_GET['id'];

    // 1. Verificar que el ID de la actividad existe en la BBDD
    if ($dao->findById($id_a_borrar) !== null) {

        // 2. Si existe, se elimina la actividad en BBDD
        if ($dao->delete($id_a_borrar)) {
            $mensaje_resultado = '<div class="alert alert-success">Actividad eliminada correctamente.</div>';
        } else {
            // Esto solo ocurre si la eliminación falla por un error de BD después de verificar la existencia
            $mensaje_resultado = '<div class="alert alert-danger">Error al intentar eliminar la actividad.</div>';
        }

    } else {
        // 1.a. Si el ID no existe en BBDD, no damos nada de información
        $mensaje_resultado = '<div class="alert alert-warning">Operación no válida.</div>';
    }

    // Limpiamos los parámetros GET para evitar re-borrado al recargar

    header('Location: listActivities.php'); exit;

}

if (isset($_GET['activityDate']) && !empty($_GET['activityDate'])) {
    $fecha_input = $_GET['activityDate'];

    // Validación básica de la fecha ( YYYY-MM-DD del input type="date")
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_input)) {
        $filtro_fecha = $fecha_input;
    } else {
        $mensaje_resultado = '<div class="alert alert-warning">El formato de fecha introducido no es válido.</div>';
    }
}

$actividades = $dao->findAll($filtro_fecha);

SessionHelper::setLastViewedPage(SessionHelper::LIST_PAGE);

$pageTitle = 'Listado de Actividades';
include ROOT_PATH . '/app/header.php';
?>

<div class="container-fluid">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="row">
            <div class="col-md-5">
                <img class="img-fluid img-rounded" src="assets/img/main-logo.png" alt="4VGym Logo">
            </div>
            <div class="col-md-7">
                <h1 class="alert-heading">4VGym, GYM de 4V</h1>
                <p>Ponte en forma y ganarás vida</p>
                <hr />

                <form action="listActivities.php" method="get" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <input name="activityDate" id="activityDate" class="form-control" type="date" value="<?= htmlspecialchars($filtro_fecha ?? '') ?>" />
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filter</button>
                    </div>
                    <?php if ($filtro_fecha): ?>
                        <div class="col-auto">
                            <a href="listActivities.php" class="btn btn-outline-secondary my-2 my-sm-0">Clear</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?= $mensaje_resultado ?>

    <?php if ($filtro_fecha && count($actividades) === 0): ?>
        <div class="alert alert-info" role="alert">
            No se encontraron resultados para la fecha seleccionada.
        </div>
    <?php endif; ?>

    <div class="row">

        <?php
        function mapTypeToImage($type) {
            $type = strtolower($type);
            if (strpos($type, 'spinning') !== false) return 'assets/img/spinning2.png';
            if (strpos($type, 'bodypump') !== false) return 'assets/img/bodypump.png';
            if (strpos($type, 'pilates') !== false) return 'assets/img/pilates.png';
            return 'assets/img/small-logo_1.jpg';
        }

        foreach ($actividades as $actividad):
            ?>
            <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <img class="card-img-top w-50 p-3 img-fluid mx-auto"
                         src='<?= htmlspecialchars(mapTypeToImage($actividad->type)) ?>'
                         alt="Imagen de <?= htmlspecialchars($actividad->type) ?>">
                    <div class="card-body">
                        <h2 class="card-title display-6"><?= htmlspecialchars($actividad->place) ?></h2>
                        <p class="card-text lead"><?= htmlspecialchars($actividad->getFormattedDate()) ?></p>
                        <p class="card-text lead text-muted"><?= htmlspecialchars($actividad->monitor) ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        <div class="btn-group">
                            <a type="button" class="btn btn-success"
                               href="editActivity.php?id=<?= htmlspecialchars($actividad->id) ?>">Modificar</a>

                            <a type="button" class="btn btn-danger"
                               href="listActivities.php?action=delete&id=<?= htmlspecialchars($actividad->id) ?>"
                               onclick="return confirm('¿Estás seguro de que quieres eliminar esta actividad?');">Borrar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($actividades) === 0 && !$filtro_fecha): ?>
            <div class="alert alert-info" role="alert">
                No hay actividades programadas. Utiliza el botón "Subir Actividad" para empezar.
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
include ROOT_PATH . '/app/footer.php';
?>
