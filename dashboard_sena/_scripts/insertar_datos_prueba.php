<?php
/**
 * Script para Insertar Datos de Prueba
 * Crea registros de ejemplo en todas las tablas para facilitar el testing
 */

require_once 'conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Insertar Datos de Prueba - Sistema SENA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #1f2937; 
            margin-bottom: 10px; 
            font-size: 32px;
        }
        .subtitle { 
            color: #6b7280; 
            margin-bottom: 30px; 
            font-size: 14px;
        }
        .section { 
            margin: 20px 0; 
            padding: 20px; 
            background: #f9fafb; 
            border-radius: 12px;
            border-left: 4px solid #39A900;
        }
        .section h2 { 
            color: #1f2937; 
            margin-bottom: 12px; 
            font-size: 18px;
        }
        .success { 
            color: #10b981; 
            padding: 8px 12px; 
            background: #d1fae5; 
            border-radius: 6px; 
            margin: 6px 0;
            font-size: 14px;
        }
        .error { 
            color: #ef4444; 
            padding: 8px 12px; 
            background: #fee2e2; 
            border-radius: 6px; 
            margin: 6px 0;
            font-size: 14px;
        }
        .info { 
            color: #3b82f6; 
            padding: 8px 12px; 
            background: #dbeafe; 
            border-radius: 6px; 
            margin: 6px 0;
            font-size: 14px;
        }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            background: #39A900; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            margin: 10px 10px 0 0;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn:hover { 
            background: #2d8700;
            transform: translateY(-2px);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #39A900;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🧪 Insertar Datos de Prueba</h1>
        <p class='subtitle'>Este script crea registros de ejemplo en todas las tablas del sistema</p>";

try {
    $db = Database::getInstance()->getConnection();
    $insertados = 0;
    
    // 1. TITULO_PROGRAMA
    echo "<div class='section'><h2>📚 Títulos de Programa</h2>";
    $titulos = ['Técnico', 'Tecnólogo', 'Especialización Tecnológica'];
    foreach ($titulos as $titulo) {
        try {
            $stmt = $db->prepare("INSERT INTO TITULO_PROGRAMA (titpro_nombre) VALUES (?)");
            $stmt->execute([$titulo]);
            echo "<div class='success'>✓ Título creado: $titulo</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: $titulo</div>";
            }
        }
    }
    echo "</div>";
    
    // 2. CENTRO_FORMACION
    echo "<div class='section'><h2>🏛️ Centros de Formación</h2>";
    $centros = ['Centro de Formación Cúcuta', 'Centro de Formación Bogotá', 'Centro de Formación Medellín'];
    foreach ($centros as $centro) {
        try {
            $stmt = $db->prepare("INSERT INTO CENTRO_FORMACION (cent_nombre) VALUES (?)");
            $stmt->execute([$centro]);
            echo "<div class='success'>✓ Centro creado: $centro</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: $centro</div>";
            }
        }
    }
    echo "</div>";
    
    // Obtener IDs
    $stmt = $db->query("SELECT titpro_id FROM TITULO_PROGRAMA LIMIT 1");
    $titulo_id = $stmt->fetch()['titpro_id'];
    
    $stmt = $db->query("SELECT cent_id FROM CENTRO_FORMACION LIMIT 1");
    $centro_id = $stmt->fetch()['cent_id'];
    
    // 3. PROGRAMA
    echo "<div class='section'><h2>📖 Programas de Formación</h2>";
    $programas = [
        ['Análisis y Desarrollo de Software', 'Tecnólogo'],
        ['Gestión Administrativa', 'Técnico'],
        ['Diseño Gráfico', 'Tecnólogo'],
        ['Contabilidad y Finanzas', 'Técnico']
    ];
    foreach ($programas as $prog) {
        try {
            $stmt = $db->prepare("INSERT INTO PROGRAMA (prog_denominacion, TIT_PROGRAMA_titpro_id, prog_tipo) VALUES (?, ?, ?)");
            $stmt->execute([$prog[0], $titulo_id, $prog[1]]);
            echo "<div class='success'>✓ Programa creado: {$prog[0]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$prog[0]}</div>";
            }
        }
    }
    echo "</div>";
    
    // Obtener programa_id
    $stmt = $db->query("SELECT prog_codigo FROM PROGRAMA LIMIT 1");
    $programa_id = $stmt->fetch()['prog_codigo'];
    
    // 4. COMPETENCIA
    echo "<div class='section'><h2>🎯 Competencias</h2>";
    $competencias = [
        ['PROG-001', 120, 'Programar aplicaciones web con lenguajes de programación'],
        ['ADMIN-001', 80, 'Gestionar procesos administrativos según normativa'],
        ['DIS-001', 100, 'Diseñar piezas gráficas según requerimientos del cliente'],
        ['CONT-001', 90, 'Realizar procesos contables según normativa vigente']
    ];
    foreach ($competencias as $comp) {
        try {
            $stmt = $db->prepare("INSERT INTO COMPETENCIA (comp_nombre_corto, comp_horas, comp_nombre_unidad_competencia) VALUES (?, ?, ?)");
            $stmt->execute([$comp[0], $comp[1], $comp[2]]);
            echo "<div class='success'>✓ Competencia creada: {$comp[0]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$comp[0]}</div>";
            }
        }
    }
    echo "</div>";
    
    // 5. INSTRUCTOR
    echo "<div class='section'><h2>👥 Instructores</h2>";
    $instructores = [
        ['Juan Carlos', 'Pérez García', 'juan.perez@sena.edu.co', 3001234567],
        ['María Fernanda', 'García López', 'maria.garcia@sena.edu.co', 3009876543],
        ['Carlos Alberto', 'López Martínez', 'carlos.lopez@sena.edu.co', 3005551234],
        ['Ana María', 'Rodríguez Silva', 'ana.rodriguez@sena.edu.co', 3007778888]
    ];
    foreach ($instructores as $inst) {
        try {
            $stmt = $db->prepare("INSERT INTO INSTRUCTOR (inst_nombres, inst_apellidos, inst_correo, inst_telefono, CENTRO_FORMACION_cent_id, inst_password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $inst[0], 
                $inst[1], 
                $inst[2], 
                $inst[3], 
                $centro_id,
                password_hash('password', PASSWORD_DEFAULT)
            ]);
            echo "<div class='success'>✓ Instructor creado: {$inst[0]} {$inst[1]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$inst[2]}</div>";
            }
        }
    }
    echo "</div>";
    
    // 6. SEDE
    echo "<div class='section'><h2>🏫 Sedes</h2>";
    $sedes = ['Sede Principal', 'Sede Norte', 'Sede Sur'];
    foreach ($sedes as $sede) {
        try {
            $stmt = $db->prepare("INSERT INTO SEDE (sede_nombre, CENTRO_FORMACION_cent_id) VALUES (?, ?)");
            $stmt->execute([$sede, $centro_id]);
            echo "<div class='success'>✓ Sede creada: $sede</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: $sede</div>";
            }
        }
    }
    echo "</div>";
    
    // Obtener sede_id
    $stmt = $db->query("SELECT sede_id FROM SEDE LIMIT 1");
    $sede_id = $stmt->fetch()['sede_id'];
    
    // 7. AMBIENTE
    echo "<div class='section'><h2>🏢 Ambientes</h2>";
    $ambientes = [
        ['A101', 'Laboratorio de Sistemas'],
        ['A102', 'Aula de Clase 1'],
        ['A103', 'Taller de Diseño'],
        ['A104', 'Sala de Conferencias'],
        ['A105', 'Laboratorio de Redes']
    ];
    foreach ($ambientes as $amb) {
        try {
            $stmt = $db->prepare("INSERT INTO AMBIENTE (amb_id, amb_nombre, SEDE_sede_id) VALUES (?, ?, ?)");
            $stmt->execute([$amb[0], $amb[1], $sede_id]);
            echo "<div class='success'>✓ Ambiente creado: {$amb[0]} - {$amb[1]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$amb[0]}</div>";
            }
        }
    }
    echo "</div>";
    
    // 8. FICHA
    echo "<div class='section'><h2>📝 Fichas</h2>";
    $fichas = [
        ['2024001', '2024-01-15', '2024-07-15'],
        ['2024002', '2024-02-01', '2024-08-01'],
        ['2024003', '2024-03-01', '2024-09-01']
    ];
    foreach ($fichas as $ficha) {
        try {
            $stmt = $db->prepare("INSERT INTO FICHA (fich_numero, PROGRAMA_prog_codigo, fich_fecha_inicio, fich_fecha_fin) VALUES (?, ?, ?, ?)");
            $stmt->execute([$ficha[0], $programa_id, $ficha[1], $ficha[2]]);
            echo "<div class='success'>✓ Ficha creada: {$ficha[0]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$ficha[0]}</div>";
            }
        }
    }
    echo "</div>";
    
    // 9. COORDINACION
    echo "<div class='section'><h2>👔 Coordinaciones</h2>";
    $coordinaciones = [
        ['Coordinación Académica', 'María López Pérez', 'maria.lopez@sena.edu.co'],
        ['Coordinación Administrativa', 'Pedro Gómez Silva', 'pedro.gomez@sena.edu.co']
    ];
    foreach ($coordinaciones as $coord) {
        try {
            $stmt = $db->prepare("INSERT INTO COORDINACION (coord_descripcion, CENTRO_FORMACION_cent_id, coord_nombre_coordinador, coord_correo, coord_password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $coord[0], 
                $centro_id, 
                $coord[1], 
                $coord[2],
                password_hash('password', PASSWORD_DEFAULT)
            ]);
            echo "<div class='success'>✓ Coordinación creada: {$coord[0]}</div>";
            $insertados++;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo "<div class='info'>ℹ Ya existe: {$coord[2]}</div>";
            }
        }
    }
    echo "</div>";
    
    // Estadísticas finales
    echo "<div class='section'>
            <h2>📊 Resumen de Datos Insertados</h2>
            <div class='stats'>";
    
    $tablas = [
        'TITULO_PROGRAMA' => 'Títulos',
        'CENTRO_FORMACION' => 'Centros',
        'PROGRAMA' => 'Programas',
        'COMPETENCIA' => 'Competencias',
        'INSTRUCTOR' => 'Instructores',
        'SEDE' => 'Sedes',
        'AMBIENTE' => 'Ambientes',
        'FICHA' => 'Fichas',
        'COORDINACION' => 'Coordinaciones'
    ];
    
    foreach ($tablas as $tabla => $nombre) {
        $stmt = $db->query("SELECT COUNT(*) as total FROM $tabla");
        $total = $stmt->fetch()['total'];
        echo "<div class='stat-card'>
                <div class='stat-number'>$total</div>
                <div class='stat-label'>$nombre</div>
              </div>";
    }
    
    echo "</div></div>";
    
    echo "<div class='section'>
            <div class='success' style='font-size: 16px; padding: 16px;'>
                ✅ <strong>Proceso completado!</strong> Se insertaron $insertados nuevos registros.
            </div>
            <div class='info' style='margin-top: 12px;'>
                <strong>Nota:</strong> Los registros duplicados fueron omitidos automáticamente.
            </div>
          </div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error: " . $e->getMessage() . "</div>";
}

echo "
        <div style='margin-top: 30px; padding-top: 30px; border-top: 2px solid #e5e7eb;'>
            <a href='index.php' class='btn'>📊 Ir al Dashboard</a>
            <a href='auth/login.php' class='btn' style='background: #6b7280;'>🔐 Ir al Login</a>
        </div>
    </div>
</body>
</html>";
?>
