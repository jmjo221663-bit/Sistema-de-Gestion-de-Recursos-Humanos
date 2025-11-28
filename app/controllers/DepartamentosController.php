
<?php
class DepartamentosController extends Controller {
    public function index() {
        Auth::authorize(['admin','rh']);
        $departamentos = Departamento::all();
        $this->view('departamentos/index', compact('departamentos'));
    }
    public function create() {
        Auth::authorize(['admin','rh']);
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $this->verifyCsrf();
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            if (!$nombre) { $error="Nombre requerido"; return $this->view('departamentos/create', compact('error')); }
            Departamento::create(['nombre'=>$nombre,'descripcion'=>$descripcion]);
            return $this->redirect('/SGRH_PHP_MVC/public/?route=departamentos');
        }
        $this->view('departamentos/create');
    }
    public function edit() {
        Auth::authorize(['admin','rh']);
        $id = intval($_GET['id'] ?? 0);
        $departamento = Departamento::find($id);
        if (!$departamento) { http_response_code(404); echo "No encontrado"; return; }
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $this->verifyCsrf();
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            Departamento::update($id,['nombre'=>$nombre,'descripcion'=>$descripcion]);
            return $this->redirect('/SGRH_PHP_MVC/public/?route=departamentos');
        }
        $this->view('departamentos/edit', compact('departamento'));
    }
    public function delete() {
        Auth::authorize(['admin']);
        $this->verifyCsrf();
        $id = intval($_POST['id'] ?? 0);
        Departamento::delete($id);
        $this->redirect('/SGRH_PHP_MVC/public/?route=departamentos');
    }
}
