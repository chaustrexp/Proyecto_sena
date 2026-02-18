<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/ProgramaModel.php';
require_once __DIR__ . '/../model/FichaModel.php';
require_once __DIR__ . '/../model/InstructorModel.php';
require_once __DIR__ . '/../model/AmbienteModel.php';
require_once __DIR__ . '/../model/AsignacionModel.php';

/**
 * Controlador del Dashboard Principal
 */
class DashboardController extends BaseController {
    private $programaModel;
    private $fichaModel;
    private $instructorModel;
    private $ambienteModel;
    private $asignacionModel;
    
    public function __construct() {
        parent::__construct();
        $this->programaModel = new ProgramaModel();
        $this->fichaModel = new FichaModel();
        $this->instructorModel = new InstructorModel();
        $this->ambienteModel = new AmbienteModel();
        $this->asignacionModel = new AsignacionModel();
        $this->viewPath = '';
    }
    
    /**
     * Página principal del dashboard
     */
    public function index() {
        try {
            // Obtener estadísticas
            $totalProgramas = $this->programaModel->count();
            $totalFichas = $this->fichaModel->count();
            $totalInstructores = $this->instructorModel->count();
            $totalAmbientes = $this->ambienteModel->count();
            $totalAsignaciones = $this->asignacionModel->count();
            
            // Obtener últimas asignaciones
            $ultimasAsignaciones = $this->asignacionModel->getRecent(5);
            
            // Obtener asignaciones para el calendario
            $asignacionesCalendario = $this->asignacionModel->getForCalendar();
            
            $data = [
                'pageTitle' => 'Dashboard Principal',
                'totalProgramas' => $totalProgramas,
                'totalFichas' => $totalFichas,
                'totalInstructores' => $totalInstructores,
                'totalAmbientes' => $totalAmbientes,
                'totalAsignaciones' => $totalAsignaciones,
                'totalCompetenciasInstructor' => 0,
                'competenciasVigentes' => 0,
                'ultimasAsignaciones' => $ultimasAsignaciones,
                'asignacionesCalendario' => $asignacionesCalendario
            ];
            
            // Renderizar vista del dashboard
            $pageTitle = $data['pageTitle'];
            extract($data);
            
            include __DIR__ . '/../views/layout/header.php';
            include __DIR__ . '/../views/layout/sidebar.php';
            include __DIR__ . '/../index_content.php'; // Contenido del dashboard
            include __DIR__ . '/../views/layout/footer.php';
            
        } catch (Exception $e) {
            // En caso de error, mostrar valores por defecto
            $data = [
                'pageTitle' => 'Dashboard Principal',
                'totalProgramas' => 0,
                'totalFichas' => 0,
                'totalInstructores' => 0,
                'totalAmbientes' => 0,
                'totalAsignaciones' => 0,
                'totalCompetenciasInstructor' => 0,
                'competenciasVigentes' => 0,
                'ultimasAsignaciones' => [],
                'asignacionesCalendario' => []
            ];
            
            $pageTitle = $data['pageTitle'];
            extract($data);
            
            include __DIR__ . '/../views/layout/header.php';
            include __DIR__ . '/../views/layout/sidebar.php';
            include __DIR__ . '/../index_content.php';
            include __DIR__ . '/../views/layout/footer.php';
        }
    }
}
?>
