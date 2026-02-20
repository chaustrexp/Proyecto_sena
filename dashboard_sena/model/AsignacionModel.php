<?php
require_once __DIR__ . '/../conexion.php';

class AsignacionModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT a.*,
                   a.ASIG_ID as asig_id,
                   f.fich_id as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre,
                   DATE(a.asig_fecha_ini) as asig_fecha_inicio,
                   DATE(a.asig_fecha_fin) as asig_fecha_fin,
                   DATE(a.asig_fecha_ini) as fecha_inicio,
                   DATE(a.asig_fecha_fin) as fecha_fin
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            ORDER BY a.asig_fecha_ini DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*,
                   a.ASIG_ID as asig_id,
                   f.fich_id as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre,
                   DATE(a.asig_fecha_ini) as asig_fecha_inicio,
                   DATE(a.asig_fecha_fin) as asig_fecha_fin,
                   DATE(a.asig_fecha_ini) as fecha_inicio,
                   DATE(a.asig_fecha_fin) as fecha_fin
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            WHERE a.ASIG_ID = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO ASIGNACION (INSTRUCTOR_inst_id, asig_fecha_ini, asig_fecha_fin, FICHA_fich_id, AMBIENTE_amb_id, COMPETENCIA_comp_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        // Obtener fechas y horas
        $fecha_inicio = $data['fecha_inicio'] ?? $data['asig_fecha_inicio'] ?? date('Y-m-d');
        $fecha_fin = $data['fecha_fin'] ?? $data['asig_fecha_fin'] ?? $fecha_inicio;
        $hora_inicio = $data['hora_inicio'] ?? '06:00';
        $hora_fin = $data['hora_fin'] ?? '22:00';
        
        // Combinar fecha y hora en formato DATETIME
        $fecha_ini = $fecha_inicio . ' ' . $hora_inicio . ':00';
        $fecha_fin_dt = $fecha_fin . ' ' . $hora_fin . ':00';
        
        return $stmt->execute([
            $data['instructor_id'] ?? $data['INSTRUCTOR_inst_id'],
            $fecha_ini,
            $fecha_fin_dt,
            $data['ficha_id'] ?? $data['FICHA_fich_id'],
            $data['ambiente_id'] ?? $data['AMBIENTE_amb_id'] ?? null,
            $data['competencia_id'] ?? $data['COMPETENCIA_comp_id'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE ASIGNACION 
            SET INSTRUCTOR_inst_id = ?, asig_fecha_ini = ?, asig_fecha_fin = ?, FICHA_fich_id = ?, AMBIENTE_amb_id = ?, COMPETENCIA_comp_id = ?
            WHERE ASIG_ID = ?
        ");
        
        // Obtener fechas y horas
        $fecha_inicio = $data['fecha_inicio'] ?? $data['asig_fecha_inicio'] ?? date('Y-m-d');
        $fecha_fin = $data['fecha_fin'] ?? $data['asig_fecha_fin'] ?? $fecha_inicio;
        $hora_inicio = $data['hora_inicio'] ?? '06:00';
        $hora_fin = $data['hora_fin'] ?? '22:00';
        
        // Combinar fecha y hora en formato DATETIME
        $fecha_ini = $fecha_inicio . ' ' . $hora_inicio . ':00';
        $fecha_fin_dt = $fecha_fin . ' ' . $hora_fin . ':00';
        
        return $stmt->execute([
            $data['instructor_id'] ?? $data['INSTRUCTOR_inst_id'],
            $fecha_ini,
            $fecha_fin_dt,
            $data['ficha_id'] ?? $data['FICHA_fich_id'],
            $data['ambiente_id'] ?? $data['AMBIENTE_amb_id'] ?? null,
            $data['competencia_id'] ?? $data['COMPETENCIA_comp_id'] ?? null,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM ASIGNACION WHERE ASIG_ID = ?");
        return $stmt->execute([$id]);
    }
    
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM ASIGNACION");
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Cuenta asignaciones activas (en curso)
     * Fecha actual está entre fecha_ini y fecha_fin
     */
    public function countActivas() {
        $hoy = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM ASIGNACION
            WHERE asig_fecha_ini <= ? AND asig_fecha_fin >= ?
        ");
        $stmt->execute([$hoy, $hoy]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Cuenta asignaciones finalizadas
     * Fecha fin ya pasó
     */
    public function countFinalizadas() {
        $hoy = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM ASIGNACION
            WHERE asig_fecha_fin < ?
        ");
        $stmt->execute([$hoy]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Cuenta asignaciones no activas (pendientes)
     * Fecha inicio aún no ha llegado
     */
    public function countNoActivas() {
        $hoy = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total
            FROM ASIGNACION
            WHERE asig_fecha_ini > ?
        ");
        $stmt->execute([$hoy]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function getRecent($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT a.*,
                   a.ASIG_ID as asig_id,
                   f.fich_id as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre,
                   DATE(a.asig_fecha_ini) as fecha_inicio,
                   DATE(a.asig_fecha_fin) as fecha_fin
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            ORDER BY a.asig_fecha_ini DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getForCalendar($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');

        $stmt = $this->db->prepare("
            SELECT a.ASIG_ID as id,
                   f.fich_id as ficha_numero,
                   CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor_nombre,
                   amb.amb_nombre as ambiente_nombre,
                   c.comp_nombre_corto as competencia_nombre,
                   p.prog_denominacion as programa_nombre,
                   a.asig_fecha_ini as fecha_inicio,
                   a.asig_fecha_fin as fecha_fin
            FROM ASIGNACION a
            LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
            LEFT JOIN PROGRAMA p ON f.PROGRAMA_prog_id = p.prog_codigo
            LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
            LEFT JOIN AMBIENTE amb ON a.AMBIENTE_amb_id = amb.amb_id
            LEFT JOIN COMPETENCIA c ON a.COMPETENCIA_comp_id = c.comp_id
            WHERE YEAR(a.asig_fecha_ini) = ? OR YEAR(a.asig_fecha_fin) = ?
            ORDER BY a.asig_fecha_ini ASC
        ");
        $stmt->execute([$year, $year]);
        return $stmt->fetchAll();
    }
}
?>
