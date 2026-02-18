<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CentroFormacionModel.php';

$model = new CentroFormacionModel();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Centro de Formación no encontrado';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Centro de Formación";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Centro de Formación</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo safeHtml($registro, 'cent_nombre'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo safeHtml($registro, 'cent_direccion'); ?>">
            </div>
            
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo safeHtml($registro, 'cent_telefono'); ?>">
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
