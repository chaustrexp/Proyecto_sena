<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../model/FichaModel.php';
require_once __DIR__ . '/../../model/ProgramaModel.php';
require_once __DIR__ . '/../../model/InstructorModel.php';
require_once __DIR__ . '/../../model/CoordinacionModel.php';

$fichaModel = new FichaModel();
$programaModel = new ProgramaModel();
$instructorModel = new InstructorModel();
$coordinacionModel = new CoordinacionModel();

$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación
    $required = ['fich_numero', 'programa_id', 'jornada', 'fecha_inicio', 'fecha_fin'];
    $errors = [];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Este campo es requerido';
        }
    }
    
    if (!empty($_POST['fich_numero']) && !is_numeric($_POST['fich_numero'])) {
        $errors['fich_numero'] = 'El número de ficha debe ser numérico';
    }
    
    if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
        if (strtotime($_POST['fecha_inicio']) > strtotime($_POST['fecha_fin'])) {
            $errors['fecha_fin'] = 'La fecha fin debe ser posterior a la fecha inicio';
        }
    }
    
    if (empty($errors)) {
        try {
            $fichaModel->create($_POST);
            $_SESSION['success'] = 'Ficha creada exitosamente';
            header('Location: index.php?msg=creado');
            exit;
        } catch (Exception $e) {
            $errors['general'] = 'Error al crear la ficha: ' . $e->getMessage();
        }
    }
    
    $old_input = $_POST;
}

$programas = $programaModel->getAll();
$instructores = $instructorModel->getAll();
$coordinaciones = $coordinacionModel->getAll();

$pageTitle = "Nueva Ficha";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <!-- Header -->
    <div style="padding: 32px 32px 24px; border-bottom: 1px solid #e5e7eb;">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 4px;">
            <a href="index.php" style="color: #6b7280; hover:color: #39A900;">
                <i data-lucide="arrow-left" style="width: 20px; height: 20px;"></i>
            </a>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0;">Nueva Ficha</h1>
        </div>
        <p style="font-size: 14px; color: #6b7280; margin: 0 0 0 36px;">Registra una nueva ficha de formación</p>
    </div>

    <!-- Errores generales -->
    <?php if (!empty($errors['general'])): ?>
        <div style="margin: 24px 32px; padding: 16px; background: #FEE2E2; border-left: 4px solid #DC2626; border-radius: 8px; color: #991B1B;">
            <strong>Error:</strong> <?php echo htmlspecialchars($errors['general']); ?>
        </div>
    <?php endif; ?>

    <!-- Formulario -->
    <div style="padding: 32px;">
        <form method="POST" action="" style="max-width: 800px; margin: 0 auto;">
            <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 32px;">
                
                <!-- Número de Ficha -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Número de Ficha <span style="color: #DC2626;">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="fich_numero" 
                        value="<?php echo htmlspecialchars($old_input['fich_numero'] ?? ''); ?>"
                        placeholder="Ej: 3115418"
                        required
                        style="width: 100%; padding: 12px; border: 2px solid <?php echo isset($errors['fich_numero']) ? '#DC2626' : '#e5e7eb'; ?>; border-radius: 8px; font-size: 14px;"
                    >
                    <?php if (isset($errors['fich_numero'])): ?>
                        <p style="color: #DC2626; font-size: 12px; margin: 4px 0 0;"><?php echo $errors['fich_numero']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Programa -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Programa <span style="color: #DC2626;">*</span>
                    </label>
                    <select 
                        name="programa_id" 
                        required
                        style="width: 100%; padding: 12px; border: 2px solid <?php echo isset($errors['programa_id']) ? '#DC2626' : '#e5e7eb'; ?>; border-radius: 8px; font-size: 14px; background: white;"
                    >
                        <option value="">Seleccione un programa</option>
                        <?php foreach ($programas as $programa): ?>
                            <option value="<?php echo $programa['prog_codigo']; ?>" <?php echo ($old_input['programa_id'] ?? '') == $programa['prog_codigo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($programa['prog_denominacion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['programa_id'])): ?>
                        <p style="color: #DC2626; font-size: 12px; margin: 4px 0 0;"><?php echo $errors['programa_id']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Instructor Líder -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Instructor Líder
                    </label>
                    <select 
                        name="instructor_id" 
                        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: white;"
                    >
                        <option value="">Seleccione un instructor (opcional)</option>
                        <?php foreach ($instructores as $instructor): ?>
                            <option value="<?php echo $instructor['inst_id']; ?>" <?php echo ($old_input['instructor_id'] ?? '') == $instructor['inst_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($instructor['inst_nombres'] . ' ' . $instructor['inst_apellidos']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jornada -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Jornada <span style="color: #DC2626;">*</span>
                    </label>
                    <select 
                        name="jornada" 
                        required
                        style="width: 100%; padding: 12px; border: 2px solid <?php echo isset($errors['jornada']) ? '#DC2626' : '#e5e7eb'; ?>; border-radius: 8px; font-size: 14px; background: white;"
                    >
                        <option value="">Seleccione una jornada</option>
                        <option value="Diurna" <?php echo ($old_input['jornada'] ?? '') == 'Diurna' ? 'selected' : ''; ?>>Diurna</option>
                        <option value="Nocturna" <?php echo ($old_input['jornada'] ?? '') == 'Nocturna' ? 'selected' : ''; ?>>Nocturna</option>
                        <option value="Mixta" <?php echo ($old_input['jornada'] ?? '') == 'Mixta' ? 'selected' : ''; ?>>Mixta</option>
                        <option value="Fin de Semana" <?php echo ($old_input['jornada'] ?? '') == 'Fin de Semana' ? 'selected' : ''; ?>>Fin de Semana</option>
                    </select>
                    <?php if (isset($errors['jornada'])): ?>
                        <p style="color: #DC2626; font-size: 12px; margin: 4px 0 0;"><?php echo $errors['jornada']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Coordinación -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Coordinación
                    </label>
                    <select 
                        name="coordinacion_id" 
                        style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; background: white;"
                    >
                        <option value="">Seleccione una coordinación (opcional)</option>
                        <?php foreach ($coordinaciones as $coord): ?>
                            <option value="<?php echo $coord['coord_id']; ?>" <?php echo ($old_input['coordinacion_id'] ?? '') == $coord['coord_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($coord['coord_descripcion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fechas -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Fecha Inicio Lectiva <span style="color: #DC2626;">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="fecha_inicio" 
                            value="<?php echo htmlspecialchars($old_input['fecha_inicio'] ?? ''); ?>"
                            required
                            style="width: 100%; padding: 12px; border: 2px solid <?php echo isset($errors['fecha_inicio']) ? '#DC2626' : '#e5e7eb'; ?>; border-radius: 8px; font-size: 14px;"
                        >
                        <?php if (isset($errors['fecha_inicio'])): ?>
                            <p style="color: #DC2626; font-size: 12px; margin: 4px 0 0;"><?php echo $errors['fecha_inicio']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                            Fecha Fin Lectiva <span style="color: #DC2626;">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="fecha_fin" 
                            value="<?php echo htmlspecialchars($old_input['fecha_fin'] ?? ''); ?>"
                            required
                            style="width: 100%; padding: 12px; border: 2px solid <?php echo isset($errors['fecha_fin']) ? '#DC2626' : '#e5e7eb'; ?>; border-radius: 8px; font-size: 14px;"
                        >
                        <?php if (isset($errors['fecha_fin'])): ?>
                            <p style="color: #DC2626; font-size: 12px; margin: 4px 0 0;"><?php echo $errors['fecha_fin']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Botones -->
                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                        Guardar Ficha
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
