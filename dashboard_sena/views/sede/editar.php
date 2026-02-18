<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/SedeModel.php';

$model = new SedeModel();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Sede no encontrada';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Sede";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Sede</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo safeHtml($registro, 'sede_nombre'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo safeHtml($registro, 'sede_direccion'); ?>">
            </div>
            
            <div class="form-group">
                <label>Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="<?php echo safeHtml($registro, 'sede_ciudad'); ?>">
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
