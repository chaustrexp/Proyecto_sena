<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/ProgramaModel.php';
require_once __DIR__ . '/../../model/TituloProgramaModel.php';

$model = new ProgramaModel();
$tituloModel = new TituloProgramaModel();
$titulos = $tituloModel->getAll();

$id = safe($_GET, 'id', 0);

if (!$id) {
    header('Location: index.php');
    exit;
}

$registro = $model->getById($id);

// Verificar si el registro existe
if (!registroValido($registro)) {
    $_SESSION['error'] = 'Programa no encontrado';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->update($id, $_POST);
    header('Location: index.php?msg=actualizado');
    exit;
}

$pageTitle = "Editar Programa";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Editar Programa</h2>
        <form method="POST">
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" class="form-control" value="<?php echo safeHtml($registro, 'prog_codigo'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Denominación *</label>
                <input type="text" name="denominacion" class="form-control" value="<?php echo safeHtml($registro, 'prog_denominacion'); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Tipo *</label>
                <select name="tipo" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <option value="Técnico" <?php echo (safe($registro, 'prog_tipo') == 'Técnico') ? 'selected' : ''; ?>>Técnico</option>
                    <option value="Tecnólogo" <?php echo (safe($registro, 'prog_tipo') == 'Tecnólogo') ? 'selected' : ''; ?>>Tecnólogo</option>
                    <option value="Especialización" <?php echo (safe($registro, 'prog_tipo') == 'Especialización') ? 'selected' : ''; ?>>Especialización</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Título Programa</label>
                <select name="titulo_programa_id" class="form-control">
                    <option value="">Seleccione...</option>
                    <?php foreach ($titulos as $titulo): ?>
                        <option value="<?php echo safeHtml($titulo, 'titpro_id'); ?>" 
                                <?php echo (safe($registro, 'TITULOPROGRAMA_titpro_id') == safe($titulo, 'titpro_id')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($titulo, 'titpro_nombre'); ?>
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
