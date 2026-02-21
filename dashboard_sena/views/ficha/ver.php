<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../helpers/functions.php';

// El registro viene del controlador
$registro = $registro ?? null;

$pageTitle = "Ver Ficha";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="detail-card">
        <?php if (registroValido($registro)): ?>
            <h2>Detalle de la Ficha</h2>
            <div class="detail-row">
                <div class="detail-label">ID:</div>
                <div><?php echo safeHtml($registro, 'fich_id'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Número:</div>
                <div><?php echo safeHtml($registro, 'fich_numero'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Programa:</div>
                <div><?php echo safeHtml($registro, 'prog_denominacion'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Instructor Líder:</div>
                <div><?php echo safeHtml($registro, 'instructor_lider'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Jornada:</div>
                <div><?php echo safeHtml($registro, 'fich_jornada'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Coordinación:</div>
                <div><?php echo safeHtml($registro, 'coord_descripcion'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Fecha Inicio Lectiva:</div>
                <div><?php echo safeHtml($registro, 'fich_fecha_ini_lectiva'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Fecha Fin Lectiva:</div>
                <div><?php echo safeHtml($registro, 'fich_fecha_fin_lectiva'); ?></div>
            </div>
            <div class="btn-group" style="margin-top: 20px;">
                <a href="/Gestion-sena/dashboard_sena/ficha/edit/<?php echo safeHtml($registro, 'fich_id'); ?>" class="btn btn-primary">Editar</a>
                <a href="/Gestion-sena/dashboard_sena/ficha" class="btn btn-secondary">Volver</a>
            </div>
        <?php else: ?>
            <h2>Registro no encontrado</h2>
            <p style="padding: 20px; text-align: center; color: #666;">No se encontraron datos para esta ficha.</p>
            <div class="btn-group" style="margin-top: 20px; justify-content: center;">
                <a href="/Gestion-sena/dashboard_sena/ficha" class="btn btn-secondary">Volver al Listado</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
