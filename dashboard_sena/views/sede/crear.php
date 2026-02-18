<?php 
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/SedeModel.php';

$model = new SedeModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create($_POST);
    header('Location: index.php?msg=creado');
    exit;
}

$pageTitle = "Crear Sede";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Crear Nueva Sede</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre de la Sede *</label>
                <input type="text" name="sede_nombre" class="form-control" maxlength="45" required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
