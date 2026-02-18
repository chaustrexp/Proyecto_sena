<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/FichaModel.php';
require_once __DIR__ . '/../../model/ProgramaModel.php';

$model = new FichaModel();
$programaModel = new ProgramaModel();
$programas = $programaModel->getAll();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Ficha no encontrada';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Ficha";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Ficha</h2>
        <form method="POST">
            <div class="form-group">
                <label>Número *</label>
                <input type="text" name="numero" class="form-control" value="<?php echo safeHtml($registro, 'fich_id'); ?>" required>
            </div>
            <div class="form-group">
                <label>Programa *</label>
                <select name="programa_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($programas as $programa): ?>
                        <option value="<?php echo safeHtml($programa, 'prog_codigo'); ?>" 
                                <?php echo (safe($registro, 'PROGRAMA_prog_codigo') == safe($programa, 'prog_codigo')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($programa, 'prog_denominacion'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?php echo safeHtml($registro, 'fich_fecha_inicio'); ?>" required>
            </div>
            <div class="form-group">
                <label>Fecha Fin *</label>
                <input type="date" name="fecha_fin" class="form-control" value="<?php echo safeHtml($registro, 'fich_fecha_fin'); ?>" required>
            </div>
            <div class="form-group">
                <label>Estado *</label>
                <select name="estado" class="form-control" required>
                    <option value="Activa" <?php echo (safe($registro, 'fich_estado') == 'Activa') ? 'selected' : ''; ?>>Activa</option>
                    <option value="Inactiva" <?php echo (safe($registro, 'fich_estado') == 'Inactiva') ? 'selected' : ''; ?>>Inactiva</option>
                    <option value="Finalizada" <?php echo (safe($registro, 'fich_estado') == 'Finalizada') ? 'selected' : ''; ?>>Finalizada</option>
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
