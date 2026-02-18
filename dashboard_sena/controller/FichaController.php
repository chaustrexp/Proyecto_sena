<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/FichaModel.php';
require_once __DIR__ . '/../model/ProgramaModel.php';

/**
 * Controlador de Fichas
 */
class FichaController extends BaseController {
    private $programaModel;
    
    public function __construct() {
        parent::__construct();
        $this->model = new FichaModel();
        $this->programaModel = new ProgramaModel();
        $this->viewPath = 'ficha';
    }
    
    public function index() {
        $registros = $this->model->getAll();
        
        $data = [
            'pageTitle' => 'Gestión de Fichas',
            'registros' => $registros,
            'totalFichas' => count($registros),
            'mensaje' => $this->getFlashMessage()
        ];
        
        $this->render('index', $data);
    }
    
    public function crear() {
        if ($this->isMethod('POST')) {
            return $this->store();
        }
        
        $data = [
            'pageTitle' => 'Nueva Ficha',
            'programas' => $this->programaModel->getAll()
        ];
        
        $this->render('crear', $data);
    }
    
    public function store() {
        $errors = $this->validate($_POST, ['numero', 'programa_id', 'fecha_inicio', 'fecha_fin', 'estado']);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
        }
        
        try {
            $this->model->create($_POST);
            $this->redirect('index.php?msg=creado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear la ficha: ' . $e->getMessage();
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
            'pageTitle' => 'Ver Ficha',
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
            'pageTitle' => 'Editar Ficha',
            'registro' => $registro,
            'programas' => $this->programaModel->getAll()
        ];
        
        $this->render('editar', $data);
    }
    
    public function update($id) {
        $errors = $this->validate($_POST, ['numero', 'programa_id', 'fecha_inicio', 'fecha_fin', 'estado']);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect("editar.php?id={$id}");
        }
        
        try {
            $this->model->update($id, $_POST);
            $this->redirect('index.php?msg=actualizado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar la ficha: ' . $e->getMessage();
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
            $_SESSION['error'] = 'Error al eliminar la ficha: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
}
?>
