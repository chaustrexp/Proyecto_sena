<?php
require_once __DIR__ . '/../conexion.php';

class FichaModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT f.*, 
                   p.prog_denominacion,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_lider,
                   c.coord_descripcion
            FROM FICHA f
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON f.INSTRUCTOR_inst_id_lider = i.inst_id
            LEFT JOIN COORDINACION c ON f.COORDINACION_coord_id = c.coord_id
            ORDER BY f.fich_id DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT f.*, 
                   p.prog_denominacion,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_lider,
                   c.coord_descripcion
            FROM FICHA f
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON f.INSTRUCTOR_inst_id_lider = i.inst_id
            LEFT JOIN COORDINACION c ON f.COORDINACION_coord_id = c.coord_id
            WHERE f.fich_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO FICHA (fich_numero, PROGRAMA_prog_id, INSTRUCTOR_inst_id_lider, fich_jornada, COORDINACION_coord_id, fich_fecha_ini_lectiva, fich_fecha_fin_lectiva) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['fich_numero'],
            $data['PROGRAMA_prog_id'],
            $data['INSTRUCTOR_inst_id_lider'],
            $data['fich_jornada'],
            $data['COORDINACION_coord_id'],
            $data['fich_fecha_ini_lectiva'],
            $data['fich_fecha_fin_lectiva']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE FICHA 
            SET fich_numero = ?, PROGRAMA_prog_id = ?, INSTRUCTOR_inst_id_lider = ?, fich_jornada = ?, COORDINACION_coord_id = ?, fich_fecha_ini_lectiva = ?, fich_fecha_fin_lectiva = ?
            WHERE fich_id = ?
        ");
        return $stmt->execute([
            $data['fich_numero'],
            $data['PROGRAMA_prog_id'],
            $data['INSTRUCTOR_inst_id_lider'],
            $data['fich_jornada'],
            $data['COORDINACION_coord_id'],
            $data['fich_fecha_ini_lectiva'],
            $data['fich_fecha_fin_lectiva'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM FICHA WHERE fich_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM FICHA");
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>
