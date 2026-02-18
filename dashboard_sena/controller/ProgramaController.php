<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/ProgramaModel.php';
require_once __DIR__ . '/../model/TituloProgramaModel.php';

class ProgramaController extends BaseController {
    private $tituloModel;
    
    public function __construct() {
        parent::__construct();
        $this->model = new ProgramaModel();
        $this->tituloModel = new TituloProgramaModel();
        $this->viewPath = 'programa';
    }
    
    public function index() {
        $data = [
            'pageTitle' => 'Gestión de Programas',
            'registros' => $this->model->getAll(),
            'mensaje' => $this->getFlashMessage()
        ];
        $this->render('index', $data);
    }
    
    public function crear() {
        if ($this->isMethod('POST')) return $this->store();
        $data = [
            'pageTitle' => 'Nuevo Programa',
            'titulos' => $this->tituloModel->getAll()
        ];
        $this->render('crear', $data);
    }
    
    public function store() {
        $errors = $this->validate($_POST, ['codigo', 'denominacion', 'tipo', 'titulo_id']);
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
        $this->render('ver', ['pageTitle' => 'Ver Programa', 'registro' => $registro]);
    }
    
    public function editar() {
        $id = $this->get('id', 0);
        if (!$id) $this->redirect('index.php');
        if ($this->isMethod('POST')) return $this->update($id);
        $registro = $this->model->getById($id);
        if (!$registro) $this->redirect('index.php?msg=no_encontrado');
        $data = [
            'pageTitle' => 'Editar Programa',
            'registro' => $registro,
            'titulos' => $this->tituloModel->getAll()
        ];
        $this->render('editar', $data);
    }
    
    public function update($id) {
        $errors = $this->validate($_POST, ['codigo', 'denominacion', 'tipo', 'titulo_id']);
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
