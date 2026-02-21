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
                <select name="ASIGNACION_ASIG_ID" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($asignaciones as $asignacion): ?>
                        <?php 
                        $asig_id = $asignacion['asig_id'] ?? $asignacion['ASIG_ID'] ?? '';
                        $ficha = $asignacion['ficha_numero'] ?? 'N/A';
                        $instructor = $asignacion['instructor_nombre'] ?? 'N/A';
                        $selected = (safe($registro, 'ASIGNACION_ASIG_ID') == $asig_id) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $asig_id; ?>" <?php echo $selected; ?>>
                            ID: <?php echo $asig_id; ?> - Ficha: <?php echo $ficha; ?> - Instructor: <?php echo $instructor; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Hora Inicio *</label>
                <input type="datetime-local" name="detasig_hora_ini" class="form-control" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime(safe($registro, 'detasig_hora_ini', date('Y-m-d H:i:s')))); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Hora Fin *</label>
                <input type="datetime-local" name="detasig_hora_fin" class="form-control" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime(safe($registro, 'detasig_hora_fin', date('Y-m-d H:i:s')))); ?>" required>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
