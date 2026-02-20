<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/FichaModel.php';
require_once __DIR__ . '/../model/ProgramaModel.php';
require_once __DIR__ . '/../model/InstructorModel.php';
require_once __DIR__ . '/../model/CoordinacionModel.php';

/**
 * Controlador de Fichas
 */
class FichaController extends BaseController {
    private $programaModel;
    private $instructorModel;
    private $coordinacionModel;
    
    public function __construct() {
        parent::__construct();
        $this->model = new FichaModel();
        $this->programaModel = new ProgramaModel();
        $this->instructorModel = new InstructorModel();
        $this->coordinacionModel = new CoordinacionModel();
        $this->viewPath = 'ficha';
    }
    
    /**
     * Listado de fichas
     */
    public function index() {
        try {
            $registros = $this->model->getAll();
            
            // Calcular estadísticas
            $totalFichas = count($registros);
            $fichasActivas = 0;
            $hoy = date('Y-m-d');
            
            foreach ($registros as $ficha) {
                if ($ficha['fich_fecha_ini_lectiva'] && $ficha['fich_fecha_fin_lectiva']) {
                    if ($ficha['fich_fecha_ini_lectiva'] <= $hoy && $ficha['fich_fecha_fin_lectiva'] >= $hoy) {
                        $fichasActivas++;
                    }
                }
            }
            
            $data = [
                'pageTitle' => 'Gestión de Fichas',
                'registros' => $registros,
                'totalFichas' => $totalFichas,
                'fichasActivas' => $fichasActivas,
                'mensaje' => $this->getFlashMessage()
            ];
            
            $this->render('index', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar fichas: ' . $e->getMessage();
            $this->render('index', [
                'pageTitle' => 'Gestión de Fichas',
                'registros' => [],
                'totalFichas' => 0,
                'fichasActivas' => 0
            ]);
        }
    }
    
    /**
     * Formulario de creación
     */
    public function crear() {
        if ($this->isMethod('POST')) {
            return $this->store();
        }
        
        try {
            $data = [
                'pageTitle' => 'Nueva Ficha',
                'programas' => $this->programaModel->getAll(),
                'instructores' => $this->instructorModel->getAll(),
                'coordinaciones' => $this->coordinacionModel->getAll(),
                'old_input' => $_SESSION['old_input'] ?? [],
                'errors' => $_SESSION['errors'] ?? []
            ];
            
            // Limpiar sesión
            unset($_SESSION['old_input'], $_SESSION['errors']);
            
            $this->render('crear', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar formulario: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
    
    /**
     * Guardar nueva ficha
     */
    public function store() {
        // Validación básica
        $required = ['fich_numero', 'programa_id', 'jornada', 'fecha_inicio', 'fecha_fin'];
        $errors = [];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = 'Este campo es requerido';
            }
        }
        
        // Validar que el número de ficha sea numérico
        if (!empty($_POST['fich_numero']) && !is_numeric($_POST['fich_numero'])) {
            $errors['fich_numero'] = 'El número de ficha debe ser numérico';
        }
        
        // Validar fechas
        if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            if (strtotime($_POST['fecha_inicio']) > strtotime($_POST['fecha_fin'])) {
                $errors['fecha_fin'] = 'La fecha fin debe ser posterior a la fecha inicio';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
            return;
        }
        
        try {
            $this->model->create($_POST);
            $_SESSION['success'] = 'Ficha creada exitosamente';
            $this->redirect('index.php?msg=creado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear la ficha: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
        }
    }
    
    /**
     * Ver detalle de ficha
     */
    public function ver() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $_SESSION['error'] = 'ID de ficha no válido';
            $this->redirect('index.php');
            return;
        }
        
        try {
            $registro = $this->model->getById($id);
            
            if (!$registro) {
                $_SESSION['error'] = 'Ficha no encontrada';
                $this->redirect('index.php');
                return;
            }
            
            $data = [
                'pageTitle' => 'Ver Ficha #' . $registro['fich_numero'],
                'registro' => $registro
            ];
            
            $this->render('ver', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar ficha: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
    
    /**
     * Formulario de edición
     */
    public function editar() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $_SESSION['error'] = 'ID de ficha no válido';
            $this->redirect('index.php');
            return;
        }
        
        if ($this->isMethod('POST')) {
            return $this->update($id);
        }
        
        try {
            $registro = $this->model->getById($id);
            
            if (!$registro) {
                $_SESSION['error'] = 'Ficha no encontrada';
                $this->redirect('index.php');
                return;
            }
            
            $data = [
                'pageTitle' => 'Editar Ficha #' . $registro['fich_numero'],
                'registro' => $registro,
                'programas' => $this->programaModel->getAll(),
                'instructores' => $this->instructorModel->getAll(),
                'coordinaciones' => $this->coordinacionModel->getAll(),
                'old_input' => $_SESSION['old_input'] ?? [],
                'errors' => $_SESSION['errors'] ?? []
            ];
            
            // Limpiar sesión
            unset($_SESSION['old_input'], $_SESSION['errors']);
            
            $this->render('editar', $data);
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar ficha: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
    
    /**
     * Actualizar ficha
     */
    public function update($id) {
        // Validación básica
        $required = ['fich_numero', 'programa_id', 'jornada', 'fecha_inicio', 'fecha_fin'];
        $errors = [];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = 'Este campo es requerido';
            }
        }
        
        // Validar que el número de ficha sea numérico
        if (!empty($_POST['fich_numero']) && !is_numeric($_POST['fich_numero'])) {
            $errors['fich_numero'] = 'El número de ficha debe ser numérico';
        }
        
        // Validar fechas
        if (!empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin'])) {
            if (strtotime($_POST['fecha_inicio']) > strtotime($_POST['fecha_fin'])) {
                $errors['fecha_fin'] = 'La fecha fin debe ser posterior a la fecha inicio';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("editar.php?id={$id}");
            return;
        }
        
        try {
            $this->model->update($id, $_POST);
            $_SESSION['success'] = 'Ficha actualizada exitosamente';
            $this->redirect('index.php?msg=actualizado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la ficha: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect("editar.php?id={$id}");
        }
    }
    
    /**
     * Eliminar ficha
     */
    public function eliminar() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $_SESSION['error'] = 'ID de ficha no válido';
            $this->redirect('index.php');
            return;
        }
        
        try {
            $this->model->delete($id);
            $_SESSION['success'] = 'Ficha eliminada exitosamente';
            $this->redirect('index.php?msg=eliminado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar la ficha: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
}
?>
