<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/AmbienteModel.php';
require_once __DIR__ . '/../../model/SedeModel.php';

$model = new AmbienteModel();
$sedeModel = new SedeModel();
$sedes = $sedeModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create($_POST);
    header('Location: index.php?msg=creado');
    exit;
}

$pageTitle = "Crear Ambiente";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Crear Nuevo Ambiente</h2>
        <form method="POST">
            <div class="form-group">
                <label>Código del Ambiente * (Ej: A101, B205)</label>
                <input type="text" name="amb_id" class="form-control" maxlength="5" placeholder="A101" required>
                <small style="color: #6b7280;">Máximo 5 caracteres</small>
            </div>
            <div class="form-group">
                <label>Nombre del Ambiente *</label>
                <input type="text" name="amb_nombre" class="form-control" maxlength="45" required>
            </div>
            <div class="form-group">
                <label>Sede *</label>
                <select name="SEDE_sede_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($sedes as $sede): ?>
                        <option value="<?php echo safeHtml($sede, 'sede_id'); ?>"><?php echo safeHtml($sede, 'sede_nombre'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
