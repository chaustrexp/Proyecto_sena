<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CompetenciaProgramaModel.php';
require_once __DIR__ . '/../../model/CompetenciaModel.php';
require_once __DIR__ . '/../../model/ProgramaModel.php';

$model = new CompetenciaProgramaModel();
$competenciaModel = new CompetenciaModel();
$programaModel = new ProgramaModel();
$competencias = $competenciaModel->getAll();
$programas = $programaModel->getAll();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Relación Competencia-Programa no encontrada';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Competencia-Programa";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Relación Competencia-Programa</h2>
        <form method="POST">
            <div class="form-group">
                <label>Competencia *</label>
                <select name="competencia_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($competencias as $competencia): ?>
                        <option value="<?php echo safeHtml($competencia, 'comp_id'); ?>" 
                                <?php echo (safe($registro, 'COMPETENCIA_comp_id') == safe($competencia, 'comp_id')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($competencia, 'comp_nombre_corto'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
