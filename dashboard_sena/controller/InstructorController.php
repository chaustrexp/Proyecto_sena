<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/InstructorModel.php';
require_once __DIR__ . '/../model/CentroFormacionModel.php';

/**
 * Controlador de Instructores
 */
class InstructorController extends BaseController {
    private $centroModel;
    
    public function __construct() {
        parent::__construct();
        $this->model = new InstructorModel();
        $this->centroModel = new CentroFormacionModel();
        $this->viewPath = 'instructor';
    }
    
    public function index() {
        $registros = $this->model->getAll();
        
        $data = [
            'pageTitle' => 'Gestión de Instructores',
            'registros' => $registros,
            'totalInstructores' => count($registros),
            'mensaje' => $this->getFlashMessage()
        ];
        
        $this->render('index', $data);
    }
    
    public function crear() {
        if ($this->isMethod('POST')) {
            return $this->store();
        }
        
        $data = [
            'pageTitle' => 'Nuevo Instructor',
            'centros' => $this->centroModel->getAll()
        ];
        
        $this->render('crear', $data);
    }
    
    public function store() {
        $errors = $this->validate($_POST, ['nombres', 'apellidos', 'correo', 'centro_id']);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
        }
        
        try {
            $this->model->create($_POST);
            $this->redirect('index.php?msg=creado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el instructor: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
        }
    }
    
    public function ver() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $this->redirect('index.php');
        }
        
        $registro = $this->model->getById($id);
        
        if (!$registro) {
            $this->redirect('index.php?msg=no_encontrado');
        }
        
        $data = [
            'pageTitle' => 'Ver Instructor',
            'registro' => $registro
        ];
        
        $this->render('ver', $data);
    }
    
    public function editar() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $this->redirect('index.php');
        }
        
        if ($this->isMethod('POST')) {
            return $this->update($id);
        }
        
        $registro = $this->model->getById($id);
        
        if (!$registro) {
            $this->redirect('index.php?msg=no_encontrado');
        }
        
        $data = [
            'pageTitle' => 'Editar Instructor',
            'registro' => $registro,
            'centros' => $this->centroModel->getAll()
        ];
        
        $this->render('editar', $data);
    }
    
    public function update($id) {
        $errors = $this->validate($_POST, ['nombres', 'apellidos', 'correo', 'centro_id']);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("editar.php?id={$id}");
        }
        
        try {
            $this->model->update($id, $_POST);
            $this->redirect('index.php?msg=actualizado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el instructor: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            $this->redirect("editar.php?id={$id}");
        }
    }
    
    public function eliminar() {
        $id = $this->get('id', 0);
        
        if (!$id) {
            $this->redirect('index.php');
        }
        
        try {
            $this->model->delete($id);
            $this->redirect('index.php?msg=eliminado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el instructor: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
}
?>
