<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/CoordinacionModel.php';
require_once __DIR__ . '/../../model/CentroFormacionModel.php';

$model = new CoordinacionModel();
$centroModel = new CentroFormacionModel();
$centros = $centroModel->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->create($_POST);
    header('Location: index.php?msg=creado');
    exit;
}

$pageTitle = "Crear Coordinación";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <!-- Form -->
    <div style="max-width: 700px; margin: 0 auto; padding: 32px;">
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 32px;">
            <!-- Header -->
            <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 1px solid #e5e7eb;">
                <a href="index.php" style="display: inline-flex; align-items: center; gap: 8px; color: #6b7280; text-decoration: none; margin-bottom: 16px; font-size: 14px; transition: color 0.2s;">
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
                    Volver a Coordinaciones
                </a>
                <h1 style="font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 4px;">Crear Nueva Coordinación</h1>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">Complete la información de la coordinación</p>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Descripción <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="coord_descripcion" class="form-control" required placeholder="Ej: Coordinación Académica" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Nombre o descripción de la coordinación</small>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Centro de Formación <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="CENTRO_FORMACION_cent_id" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                        <option value="">Seleccione un centro...</option>
                        <?php foreach ($centros as $centro): ?>
                            <option value="<?php echo safeHtml($centro, 'cent_id'); ?>">
                                <?php echo safeHtml($centro, 'cent_nombre'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Nombre del Coordinador <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="coord_nombre_coordinador" class="form-control" required placeholder="Ej: María López Pérez" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Correo Electrónico <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="coord_correo" class="form-control" required placeholder="Ej: maria.lopez@sena.edu.co" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Contraseña
                    </label>
                    <input type="password" name="coord_password" class="form-control" placeholder="Dejar vacío para usar contraseña por defecto (123456)" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px;">
                    <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">Si no ingresa una contraseña, se usará "123456" por defecto</small>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Coordinación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Focus effect en inputs
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = '#39A900';
            this.style.outline = 'none';
            this.style.boxShadow = '0 0 0 3px rgba(57, 169, 0, 0.1)';
        });
        input.addEventListener('blur', function() {
            this.style.borderColor = '#e5e7eb';
            this.style.boxShadow = 'none';
        });
    });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
