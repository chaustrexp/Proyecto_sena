<?php
require_once __DIR__ . '/../conexion.php';

class CompetenciaModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM COMPETENCIA ORDER BY comp_nombre_corto");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM COMPETENCIA WHERE comp_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO COMPETENCIA (comp_nombre_corto, comp_horas, comp_nombre_unidad_competencia) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([
            $data['nombre_corto'],
            $data['horas'] ?? null,
            $data['nombre_unidad_competencia']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE COMPETENCIA 
            SET comp_nombre_corto = ?, comp_horas = ?, comp_nombre_unidad_competencia = ?
            WHERE comp_id = ?
        ");
        return $stmt->execute([
            $data['nombre_corto'],
            $data['horas'],
            $data['nombre_unidad_competencia'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM COMPETENCIA WHERE comp_id = ?");
        return $stmt->execute([$id]);
    }
}
?>
