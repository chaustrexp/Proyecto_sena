<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CoordinacionModel.php';
require_once __DIR__ . '/../../model/CentroFormacionModel.php';

$model = new CoordinacionModel();
$centroModel = new CentroFormacionModel();
$centros = $centroModel->getAll();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Coordinación no encontrada';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Coordinación";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Coordinación</h2>
        <form method="POST">
            <div class="form-group">
                <label>Descripción *</label>
                <input type="text" name="descripcion" class="form-control" value="<?php echo safeHtml($registro, 'coord_descripcion'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nombre Coordinador</label>
                <input type="text" name="nombre_coordinador" class="form-control" value="<?php echo safeHtml($registro, 'coord_nombre_coordinador'); ?>">
            </div>
            
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="<?php echo safeHtml($registro, 'coord_correo'); ?>">
            </div>
            
            <div class="form-group">
                <label>Centro de Formación</label>
                <select name="centro_formacion_id" class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($centros as $centro): ?>
                        <option value="<?php echo safeHtml($centro, 'cent_id'); ?>" 
                                <?php echo (safe($registro, 'CENTROFORMACION_cent_id') == safe($centro, 'cent_id')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($centro, 'cent_nombre'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
