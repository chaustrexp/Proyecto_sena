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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create($_POST);
    header('Location: index.php?msg=creado');
    exit;
}

$pageTitle = "Crear Competencia-Programa";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Crear Nueva Relación Competencia-Programa</h2>
        <form method="POST">
            <div class="form-group">
                <label>Programa *</label>
                <select name="PROGRAMA_prog_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($programas as $programa): ?>
                        <option value="<?php echo safeHtml($programa, 'prog_codigo'); ?>"><?php echo safeHtml($programa, 'prog_denominacion'); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Competencia *</label>
                <select name="COMPETENCIA_comp_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($competencias as $competencia): ?>
                        <option value="<?php echo safeHtml($competencia, 'comp_id'); ?>"><?php echo safeHtml($competencia, 'comp_nombre_corto'); ?> - <?php echo safeHtml($competencia, 'comp_nombre_unidad_competencia'); ?></option>
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
