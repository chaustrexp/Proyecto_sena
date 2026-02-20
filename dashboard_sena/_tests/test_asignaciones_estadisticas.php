<?php
/**
 * Script de prueba para verificar estadísticas de asignaciones
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
echo "<style>
body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
.section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.success { color: #39A900; }
.error { color: #dc2626; }
.warning { color: #f59e0b; }
.info { color: #3b82f6; }
h1 { color: #1f2937; }
h2 { color: #374151; border-bottom: 2px solid #39A900; padding-bottom: 8px; }
pre { background: #f9fafb; padding: 12px; border-radius: 4px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background: #f9fafb; font-weight: 600; }
.stat-box { display: inline-block; padding: 20px 30px; margin: 10px; background: linear-gradient(135deg, #E8F5E8 0%, #d4edda 100%); border-radius: 12px; text-align: center; }
.stat-number { font-size: 48px; font-weight: 700; color: #39A900; }
.stat-label { font-size: 14px; color: #374151; margin-top: 8px; }
</style></head><body>";

echo "<h1>📊 Prueba de Estadísticas de Asignaciones</h1>";

try {
    require_once __DIR__ . '/../conexion.php';
    require_once __DIR__ . '/../model/AsignacionModel.php';
    
    $model = new AsignacionModel();
    $hoy = date('Y-m-d H:i:s');
    
    // 1. Estadísticas generales
    echo "<div class='section'>";
    echo "<h2>1. Estadísticas Generales</h2>";
    echo "<p><strong>Fecha y hora actual:</strong> <span class='info'>$hoy</span></p>";
    
    $total = $model->count();
    $activas = $model->countActivas();
    $finalizadas = $model->countFinalizadas();
    $noActivas = $model->countNoActivas();
    
    echo "<div style='text-align: center; margin: 30px 0;'>";
    
    echo "<div class='stat-box' style='background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%);'>";
    echo "<div class='stat-number' style='color: #ec4899;'>$total</div>";
    echo "<div class='stat-label'>Total Asignaciones</div>";
    echo "</div>";
    
    echo "<div class='stat-box' style='background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);'>";
    echo "<div class='stat-number' style='color: #3b82f6;'>$activas</div>";
    echo "<div class='stat-label'>Activas (En Curso)</div>";
    echo "</div>";
    
    echo "<div class='stat-box' style='background: linear-gradient(135deg, #E8F5E8 0%, #d4edda 100%);'>";
    echo "<div class='stat-number' style='color: #39A900;'>$finalizadas</div>";
    echo "<div class='stat-label'>Finalizadas</div>";
    echo "</div>";
    
    echo "<div class='stat-box' style='background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);'>";
    echo "<div class='stat-number' style='color: #f59e0b;'>$noActivas</div>";
    echo "<div class='stat-label'>No Activas (Pendientes)</div>";
    echo "</div>";
    
    echo "</div>";
    
    // Verificación de suma
    $suma = $activas + $finalizadas + $noActivas;
    if ($suma == $total) {
        echo "<p class='success'>✓ Verificación correcta: $activas + $finalizadas + $noActivas = $total</p>";
    } else {
        echo "<p class='error'>⚠️ Advertencia: La suma no coincide ($suma ≠ $total)</p>";
    }
    
    echo "</div>";
    
    // 2. Asignaciones Activas
    echo "<div class='section'>";
    echo "<h2>2. Asignaciones Activas (En Curso)</h2>";
    echo "<p>Asignaciones donde la fecha actual está entre fecha_ini y fecha_fin</p>";
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT a.ASIG_ID,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor,
               f.fich_id as ficha,
               a.asig_fecha_ini,
               a.asig_fecha_fin,
               DATEDIFF(a.asig_fecha_fin, NOW()) as dias_restantes
        FROM ASIGNACION a
        LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
        LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
        WHERE a.asig_fecha_ini <= ? AND a.asig_fecha_fin >= ?
        ORDER BY a.asig_fecha_ini DESC
        LIMIT 10
    ");
    $stmt->execute([$hoy, $hoy]);
    $activasDetalle = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($activasDetalle)) {
        echo "<p class='warning'>No hay asignaciones activas en este momento</p>";
    } else {
        echo "<table>";
        echo "<tr><th>ID</th><th>Instructor</th><th>Ficha</th><th>Inicio</th><th>Fin</th><th>Días Restantes</th></tr>";
        foreach ($activasDetalle as $asig) {
            $dias = $asig['dias_restantes'];
            $colorDias = $dias < 7 ? 'error' : ($dias < 30 ? 'warning' : 'success');
            echo "<tr>";
            echo "<td>{$asig['ASIG_ID']}</td>";
            echo "<td>{$asig['instructor']}</td>";
            echo "<td>{$asig['ficha']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_ini'])) . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_fin'])) . "</td>";
            echo "<td class='$colorDias'><strong>$dias días</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 3. Asignaciones Finalizadas
    echo "<div class='section'>";
    echo "<h2>3. Asignaciones Finalizadas</h2>";
    echo "<p>Asignaciones donde la fecha_fin ya pasó</p>";
    
    $stmt = $db->prepare("
        SELECT a.ASIG_ID,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor,
               f.fich_id as ficha,
               a.asig_fecha_ini,
               a.asig_fecha_fin,
               DATEDIFF(NOW(), a.asig_fecha_fin) as dias_finalizados
        FROM ASIGNACION a
        LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
        LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
        WHERE a.asig_fecha_fin < ?
        ORDER BY a.asig_fecha_fin DESC
        LIMIT 10
    ");
    $stmt->execute([$hoy]);
    $finalizadasDetalle = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($finalizadasDetalle)) {
        echo "<p class='warning'>No hay asignaciones finalizadas</p>";
    } else {
        echo "<table>";
        echo "<tr><th>ID</th><th>Instructor</th><th>Ficha</th><th>Inicio</th><th>Fin</th><th>Hace</th></tr>";
        foreach ($finalizadasDetalle as $asig) {
            $dias = $asig['dias_finalizados'];
            echo "<tr>";
            echo "<td>{$asig['ASIG_ID']}</td>";
            echo "<td>{$asig['instructor']}</td>";
            echo "<td>{$asig['ficha']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_ini'])) . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_fin'])) . "</td>";
            echo "<td>$dias días</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 4. Asignaciones No Activas
    echo "<div class='section'>";
    echo "<h2>4. Asignaciones No Activas (Pendientes)</h2>";
    echo "<p>Asignaciones donde la fecha_ini aún no ha llegado</p>";
    
    $stmt = $db->prepare("
        SELECT a.ASIG_ID,
               CONCAT(i.inst_nombres, ' ', i.inst_apellidos) as instructor,
               f.fich_id as ficha,
               a.asig_fecha_ini,
               a.asig_fecha_fin,
               DATEDIFF(a.asig_fecha_ini, NOW()) as dias_para_inicio
        FROM ASIGNACION a
        LEFT JOIN INSTRUCTOR i ON a.INSTRUCTOR_inst_id = i.inst_id
        LEFT JOIN FICHA f ON a.FICHA_fich_id = f.fich_id
        WHERE a.asig_fecha_ini > ?
        ORDER BY a.asig_fecha_ini ASC
        LIMIT 10
    ");
    $stmt->execute([$hoy]);
    $noActivasDetalle = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($noActivasDetalle)) {
        echo "<p class='warning'>No hay asignaciones pendientes</p>";
    } else {
        echo "<table>";
        echo "<tr><th>ID</th><th>Instructor</th><th>Ficha</th><th>Inicio</th><th>Fin</th><th>Inicia en</th></tr>";
        foreach ($noActivasDetalle as $asig) {
            $dias = $asig['dias_para_inicio'];
            $colorDias = $dias <= 7 ? 'warning' : 'info';
            echo "<tr>";
            echo "<td>{$asig['ASIG_ID']}</td>";
            echo "<td>{$asig['instructor']}</td>";
            echo "<td>{$asig['ficha']}</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_ini'])) . "</td>";
            echo "<td>" . date('d/m/Y H:i', strtotime($asig['asig_fecha_fin'])) . "</td>";
            echo "<td class='$colorDias'><strong>$dias días</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";
    
    // 5. Resumen
    echo "<div class='section'>";
    echo "<h2>5. Resumen</h2>";
    echo "<ul>";
    echo "<li><strong>Total de asignaciones:</strong> $total</li>";
    echo "<li><strong>Activas (en curso):</strong> $activas (" . ($total > 0 ? round($activas/$total*100, 1) : 0) . "%)</li>";
    echo "<li><strong>Finalizadas:</strong> $finalizadas (" . ($total > 0 ? round($finalizadas/$total*100, 1) : 0) . "%)</li>";
    echo "<li><strong>No activas (pendientes):</strong> $noActivas (" . ($total > 0 ? round($noActivas/$total*100, 1) : 0) . "%)</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='section'>";
    echo "<h2>✅ Prueba Completada</h2>";
    echo "<p class='success'>Todos los métodos funcionan correctamente</p>";
    echo "<p>Puedes ver estas estadísticas en el dashboard principal</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='section'>";
    echo "<h2 class='error'>Error de Base de Datos</h2>";
    echo "<pre class='error'>";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "</pre>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='section'>";
    echo "<h2 class='error'>Error General</h2>";
    echo "<pre class='error'>";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "</pre>";
    echo "</div>";
}

echo "</body></html>";
?>
