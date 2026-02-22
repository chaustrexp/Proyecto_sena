<?php
/**
 * Script para actualizar todas las URLs en las vistas
 * Agrega /index a los enlaces que apuntan solo al módulo
 */

$basePath = __DIR__ . '/../views/';
$modulos = [
    'ambiente', 'asignacion', 'centro_formacion', 'competencia', 
    'competencia_programa', 'coordinacion', 'dashboard', 'detalle_asignacion',
    'ficha', 'instru_competencia', 'instructor', 'programa', 'sede', 'titulo_programa'
];

$archivosActualizados = 0;
$reemplazosRealizados = 0;

function actualizarArchivo($archivo, $modulos) {
    global $reemplazosRealizados;
    
    $contenido = file_get_contents($archivo);
    $contenidoOriginal = $contenido;
    
    foreach ($modulos as $modulo) {
        // Patrón 1: href="/Gestion-sena/dashboard_sena/{modulo}" (sin /index)
        $patron1 = '/href="\/Gestion-sena\/dashboard_sena\/' . $modulo . '"(?!\w)/';
        $reemplazo1 = 'href="/Gestion-sena/dashboard_sena/' . $modulo . '/index"';
        $contenido = preg_replace($patron1, $reemplazo1, $contenido, -1, $count1);
        $reemplazosRealizados += $count1;
        
        // Patrón 2: href='/Gestion-sena/dashboard_sena/{modulo}' (comillas simples)
        $patron2 = "/href='\/Gestion-sena\/dashboard_sena\/" . $modulo . "'(?!\w)/";
        $reemplazo2 = "href='/Gestion-sena/dashboard_sena/" . $modulo . "/index'";
        $contenido = preg_replace($patron2, $reemplazo2, $contenido, -1, $count2);
        $reemplazosRealizados += $count2;
    }
    
    // Solo escribir si hubo cambios
    if ($contenido !== $contenidoOriginal) {
        file_put_contents($archivo, $contenido);
        return true;
    }
    
    return false;
}

function escanearDirectorio($directorio, $modulos) {
    global $archivosActualizados;
    
    $archivos = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directorio),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($archivos as $archivo) {
        if ($archivo->isFile() && $archivo->getExtension() === 'php') {
            if (actualizarArchivo($archivo->getPathname(), $modulos)) {
                $archivosActualizados++;
                echo "✓ Actualizado: " . $archivo->getFilename() . "\n";
            }
        }
    }
}

echo "=== Actualizando URLs en Vistas ===\n\n";

// Escanear todas las vistas
escanearDirectorio($basePath, $modulos);

echo "\n=== Resumen ===\n";
echo "Archivos actualizados: $archivosActualizados\n";
echo "Reemplazos realizados: $reemplazosRealizados\n";
echo "\n✅ Proceso completado\n";
?>
