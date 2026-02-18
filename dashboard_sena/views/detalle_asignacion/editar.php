<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/DetalleAsignacionModel.php';
require_once __DIR__ . '/../../model/AsignacionModel.php';

$model = new DetalleAsignacionModel();
$asignacionModel = new AsignacionModel();
$asignaciones = $asignacionModel->getAll();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Detalle de Asignación no encontrado';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Detalle Asignación";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Detalle de Asignación</h2>
        <form method="POST">
            <div class="form-group">
                <label>Asignación *</label>
                <select name="asignacion_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($asignaciones as $asignacion): ?>
                        <option value="<?php echo safeHtml($asignacion, 'asig_id'); ?>" 
                                <?php echo (safe($registro, 'ASIGNACION_asig_id') == safe($asignacion, 'asig_id')) ? 'selected' : ''; ?>>
                            ID: <?php echo safeHtml($asignacion, 'asig_id'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Fecha *</label>
                <input type="date" name="fecha" class="form-control" value="<?php echo safeHtml($registro, 'detasig_fecha'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Hora Inicio *</label>
                <input type="time" name="hora_inicio" class="form-control" value="<?php echo safeHtml($registro, 'detasig_hora_inicio'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Hora Fin *</label>
                <input type="time" name="hora_fin" class="form-control" value="<?php echo safeHtml($registro, 'detasig_hora_fin'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="4"><?php echo safeHtml($registro, 'detasig_observaciones'); ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
