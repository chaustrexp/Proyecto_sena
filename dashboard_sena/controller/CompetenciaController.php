<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/CompetenciaModel.php';

class CompetenciaController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->model = new CompetenciaModel();
        $this->viewPath = 'competencia';
    }
    
    public function index() {
        $data = [
            'pageTitle' => 'Gestión de Competencias',
            'registros' => $this->model->getAll(),
            'mensaje' => $this->getFlashMessage()
        ];
        $this->render('index', $data);
    }
    
    public function crear() {
        if ($this->isMethod('POST')) return $this->store();
        $this->render('crear', ['pageTitle' => 'Nueva Competencia']);
    }
    
    public function store() {
        $errors = $this->validate($_POST, ['nombre_corto', 'nombre_unidad_competencia', 'horas']);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $_POST;
            $this->redirect('crear.php');
        }
        try {
            $this->model->create($_POST);
            $this->redirect('index.php?msg=creado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            $this->redirect('crear.php');
        }
    }
    
    public function ver() {
        $id = $this->get('id', 0);
        if (!$id) $this->redirect('index.php');
        $registro = $this->model->getById($id);
        if (!$registro) $this->redirect('index.php?msg=no_encontrado');
        $this->render('ver', ['pageTitle' => 'Ver Competencia', 'registro' => $registro]);
    }
    
    public function editar() {
        $id = $this->get('id', 0);
        if (!$id) $this->redirect('index.php');
        if ($this->isMethod('POST')) return $this->update($id);
        $registro = $this->model->getById($id);
        if (!$registro) $this->redirect('index.php?msg=no_encontrado');
        $this->render('editar', ['pageTitle' => 'Editar Competencia', 'registro' => $registro]);
    }
    
    public function update($id) {
        $errors = $this->validate($_POST, ['nombre_corto', 'nombre_unidad_competencia', 'horas']);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect("editar.php?id={$id}");
        }
        try {
            $this->model->update($id, $_POST);
            $this->redirect('index.php?msg=actualizado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            $this->redirect("editar.php?id={$id}");
        }
    }
    
    public function eliminar() {
        $id = $this->get('id', 0);
        if (!$id) $this->redirect('index.php');
        try {
            $this->model->delete($id);
            $this->redirect('index.php?msg=eliminado');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            $this->redirect('index.php');
        }
    }
}
?>
