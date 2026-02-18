<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CompetenciaModel.php';

$model = new CompetenciaModel();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Competencia no encontrada';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Competencia";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Competencia</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre Corto *</label>
                <input type="text" name="nombre_corto" class="form-control" value="<?php echo safeHtml($registro, 'comp_nombre_corto'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nombre Unidad de Competencia *</label>
                <input type="text" name="nombre_unidad" class="form-control" value="<?php echo safeHtml($registro, 'comp_nombre_unidad_competencia'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Horas</label>
                <input type="number" name="horas" class="form-control" value="<?php echo safeHtml($registro, 'comp_horas'); ?>">
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
