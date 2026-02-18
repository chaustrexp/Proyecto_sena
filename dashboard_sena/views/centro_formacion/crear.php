<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CentroFormacionModel.php';

$model = new CentroFormacionModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create($_POST);
    header('Location: index.php?msg=creado');
    exit;
}

$pageTitle = "Crear Centro de Formación";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Crear Nuevo Centro de Formación</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="cent_nombre" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="cent_direccion" class="form-control">
            </div>
            
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="cent_telefono" class="form-control">
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
