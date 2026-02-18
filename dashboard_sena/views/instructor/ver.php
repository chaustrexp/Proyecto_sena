<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/InstructorModel.php';

$model = new InstructorModel();
$id = safe($_GET, 'id', 0);
$registro = $model->getById($id);

$pageTitle = "Ver Instructor";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="detail-card">
        <?php if (registroValido($registro)): ?>
            <h2>Detalle del Instructor</h2>
            <div class="detail-row">
                <div class="detail-label">ID:</div>
                <div><?php echo safeHtml($registro, 'inst_id'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">NOMBRES:</div>
                <div><?php echo safeHtml($registro, 'inst_nombres'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">APELLIDOS:</div>
                <div><?php echo safeHtml($registro, 'inst_apellidos'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">CORREO:</div>
                <div><?php echo safeHtml($registro, 'inst_correo'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">TELÉFONO:</div>
                <div><?php echo safeHtml($registro, 'inst_telefono'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">CENTRO DE FORMACIÓN:</div>
                <div><?php echo safeHtml($registro, 'cent_nombre'); ?></div>
            </div>
            <div class="btn-group" style="margin-top: 20px;">
                <a href="editar.php?id=<?php echo safeHtml($registro, 'inst_id'); ?>" class="btn btn-primary">Editar</a>
                <a href="index.php" class="btn btn-secondary">Volver</a>
            </div>
        <?php else: ?>
            <h2>Registro no encontrado</h2>
            <p style="padding: 20px; text-align: center; color: #666;">No se encontró el instructor solicitado.</p>
            <div class="btn-group" style="margin-top: 20px; justify-content: center;">
                <a href="index.php" class="btn btn-secondary">Volver al Listado</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
