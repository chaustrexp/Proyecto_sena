<?php
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../model/InstruCompetenciaModel.php';
require_once __DIR__ . '/../../model/InstructorModel.php';
require_once __DIR__ . '/../../model/ProgramaModel.php';
require_once __DIR__ . '/../../model/CompetenciaModel.php';
require_once __DIR__ . '/../../model/CompetenciaProgramaModel.php';

$model = new InstruCompetenciaModel();
$instructorModel = new InstructorModel();
$programaModel = new ProgramaModel();
$competenciaModel = new CompetenciaModel();
$competenciaProgramaModel = new CompetenciaProgramaModel();

$instructores = $instructorModel->getAll();
$programas = $programaModel->getAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que la combinación programa-competencia exista en COMPETxPROGRAMA
    $programa_id = $_POST['COMPETxPROGRAMA_PROGRAMA_prog_id'];
    $competencia_id = $_POST['COMPETxPROGRAMA_COMPETENCIA_comp_id'];
    
    // Verificar que la relación existe
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT COUNT(*) as existe 
        FROM COMPETxPROGRAMA 
        WHERE PROGRAMA_prog_id = ? AND COMPETENCIA_comp_id = ?
    ");
    $stmt->execute([$programa_id, $competencia_id]);
    $resultado = $stmt->fetch();
    
    if ($resultado['existe'] > 0) {
        // La relación existe, proceder a crear
        $model->create($_POST);
        header('Location: index.php?msg=creado');
        exit;
    } else {
        $error = 'La competencia seleccionada no está asociada al programa elegido. Por favor, seleccione una combinación válida.';
    }
}

$pageTitle = "Asignar Competencia a Instructor";
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main-content">
    <div class="form-container">
        <h2>Asignar Competencia a Instructor</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error" style="background: #FEE2E2; color: #DC2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #DC2626;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" id="formInstruCompetencia">
            <div class="form-group">
                <label>Instructor *</label>
                <select name="INSTRUCTOR_inst_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($instructores as $instructor): ?>
                        <option value="<?php echo safeHtml($instructor, 'inst_id'); ?>" <?php echo (safe($_POST, 'INSTRUCTOR_inst_id') == safe($instructor, 'inst_id')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($instructor, 'inst_nombres') . ' ' . safeHtml($instructor, 'inst_apellidos'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Programa *</label>
                <select name="COMPETxPROGRAMA_PROGRAMA_prog_id" id="programa" class="form-control" required>
                    <option value="">Seleccione un programa...</option>
                    <?php foreach ($programas as $programa): ?>
                        <option value="<?php echo safeHtml($programa, 'prog_codigo'); ?>" <?php echo (safe($_POST, 'COMPETxPROGRAMA_PROGRAMA_prog_id') == safe($programa, 'prog_codigo')) ? 'selected' : ''; ?>>
                            <?php echo safeHtml($programa, 'prog_denominacion'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">
                    Primero seleccione el programa para cargar sus competencias asociadas
                </small>
            </div>

            <div class="form-group">
                <label>Competencia *</label>
                <select name="COMPETxPROGRAMA_COMPETENCIA_comp_id" id="competencia" class="form-control" required disabled>
                    <option value="">Primero seleccione un programa...</option>
                </select>
                <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">
                    Solo se mostrarán las competencias asociadas al programa seleccionado
                </small>
            </div>

            <div class="form-group">
                <label>Fecha de Vigencia *</label>
                <input type="date" name="inscomp_vigencia" class="form-control" value="<?php echo safeHtml($_POST, 'inscomp_vigencia'); ?>" required>
                <small style="color: #6b7280; font-size: 12px; margin-top: 4px; display: block;">
                    Fecha hasta la cual el instructor está certificado para impartir esta competencia
                </small>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
// Cargar competencias cuando se selecciona un programa
document.getElementById('programa').addEventListener('change', function() {
    const programaId = this.value;
    const competenciaSelect = document.getElementById('competencia');
    
    if (!programaId) {
        competenciaSelect.innerHTML = '<option value="">Primero seleccione un programa...</option>';
        competenciaSelect.disabled = true;
        return;
    }
    
    // Mostrar loading
    competenciaSelect.innerHTML = '<option value="">Cargando competencias...</option>';
    competenciaSelect.disabled = true;
    
    // Hacer petición AJAX para obtener competencias del programa
    fetch('get_competencias_programa.php?programa_id=' + programaId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.competencias.length > 0) {
                let options = '<option value="">Seleccione una competencia...</option>';
                data.competencias.forEach(comp => {
                    options += `<option value="${comp.comp_id}">${comp.comp_nombre_corto} - ${comp.comp_nombre_unidad_competencia}</option>`;
                });
                competenciaSelect.innerHTML = options;
                competenciaSelect.disabled = false;
            } else {
                competenciaSelect.innerHTML = '<option value="">No hay competencias asociadas a este programa</option>';
                competenciaSelect.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            competenciaSelect.innerHTML = '<option value="">Error al cargar competencias</option>';
            competenciaSelect.disabled = true;
        });
});

// Si hay un programa preseleccionado (después de un error), cargar sus competencias
window.addEventListener('DOMContentLoaded', function() {
    const programaSelect = document.getElementById('programa');
    if (programaSelect.value) {
        programaSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
