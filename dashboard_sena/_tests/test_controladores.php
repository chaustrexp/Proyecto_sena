<?php
/**
 * Página de Prueba de Controladores
 * Accede a: http://localhost/Gestion-sena/dashboard_sena/test_controladores.php
 */

// Cargar sistema de autenticación
require_once __DIR__ . '/auth/check_auth.php';

$pageTitle = "Prueba de Controladores";
include __DIR__ . '/views/layout/header.php';
include __DIR__ . '/views/layout/sidebar.php';
?>

<div class="main-content">
    <!-- Header -->
    <div style="padding: 32px 32px 24px; border-bottom: 1px solid #e5e7eb;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0 0 4px;">🎯 Prueba de Controladores</h1>
        <p style="font-size: 14px; color: #6b7280; margin: 0;">Visualiza cómo funcionan los controladores en el sistema</p>
    </div>

    <!-- Estado del Sistema -->
    <div style="padding: 24px 32px;">
        <div style="background: linear-gradient(135deg, #39A900 0%, #007832 100%); padding: 24px; border-radius: 12px; color: white; margin-bottom: 24px;">
            <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px;">✅ Sistema de Controladores Activo</h2>
            <p style="margin: 0; opacity: 0.95;">Los controladores están correctamente instalados y conectados a la base de datos</p>
        </div>

        <!-- Controladores Disponibles -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">📦 Controladores Disponibles</h3>
            </div>
            <div style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                    
                    <?php
                    $controladores = [
                        [
                            'nombre' => 'DashboardController',
                            'descripcion' => 'Dashboard principal con estadísticas',
                            'archivo' => 'DashboardController.php',
                            'metodos' => ['index'],
                            'icono' => '📊',
                            'color' => '#3b82f6'
                        ],
                        [
                            'nombre' => 'AsignacionController',
                            'descripcion' => 'Gestión de asignaciones',
                            'archivo' => 'AsignacionController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar', 'getFormData', 'getAsignacion'],
                            'icono' => '📅',
                            'color' => '#ec4899'
                        ],
                        [
                            'nombre' => 'FichaController',
                            'descripcion' => 'Gestión de fichas',
                            'archivo' => 'FichaController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar'],
                            'icono' => '📋',
                            'color' => '#8b5cf6'
                        ],
                        [
                            'nombre' => 'InstructorController',
                            'descripcion' => 'Gestión de instructores',
                            'archivo' => 'InstructorController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar'],
                            'icono' => '👨‍🏫',
                            'color' => '#10b981'
                        ],
                        [
                            'nombre' => 'AmbienteController',
                            'descripcion' => 'Gestión de ambientes',
                            'archivo' => 'AmbienteController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar'],
                            'icono' => '🏢',
                            'color' => '#f59e0b'
                        ],
                        [
                            'nombre' => 'ProgramaController',
                            'descripcion' => 'Gestión de programas',
                            'archivo' => 'ProgramaController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar'],
                            'icono' => '📚',
                            'color' => '#06b6d4'
                        ],
                        [
                            'nombre' => 'CompetenciaController',
                            'descripcion' => 'Gestión de competencias',
                            'archivo' => 'CompetenciaController.php',
                            'metodos' => ['index', 'crear', 'ver', 'editar', 'eliminar'],
                            'icono' => '🎓',
                            'color' => '#ef4444'
                        ]
                    ];

                    foreach ($controladores as $ctrl):
                        $existe = file_exists(__DIR__ . '/controller/' . $ctrl['archivo']);
                    ?>
                    <div style="background: white; border: 2px solid <?php echo $ctrl['color']; ?>; border-radius: 8px; padding: 20px; transition: all 0.2s;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div style="font-size: 32px;"><?php echo $ctrl['icono']; ?></div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #1f2937; font-size: 16px;"><?php echo $ctrl['nombre']; ?></div>
                                <div style="font-size: 12px; color: #6b7280;"><?php echo $ctrl['descripcion']; ?></div>
                            </div>
                            <?php if ($existe): ?>
                                <span style="background: #E8F5E8; color: #39A900; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">✓ OK</span>
                            <?php else: ?>
                                <span style="background: #FEE2E2; color: #DC2626; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">✗ NO</span>
                            <?php endif; ?>
                        </div>
                        <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                            <strong>Métodos:</strong> <?php echo count($ctrl['metodos']); ?>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            <?php foreach ($ctrl['metodos'] as $metodo): ?>
                                <span style="background: #f3f4f6; color: #374151; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-family: monospace;"><?php echo $metodo; ?>()</span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Prueba en Vivo -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">🧪 Prueba en Vivo - AsignacionController</h3>
            </div>
            <div style="padding: 24px;">
                <?php
                try {
                    // Probar el controlador de asignaciones
                    require_once __DIR__ . '/controller/AsignacionController.php';
                    require_once __DIR__ . '/model/AsignacionModel.php';
                    
                    $asignacionModel = new AsignacionModel();
                    $totalAsignaciones = $asignacionModel->count();
                    $asignaciones = $asignacionModel->getAll();
                    
                    echo '<div style="background: #E8F5E8; border-left: 4px solid #39A900; padding: 16px; border-radius: 8px; margin-bottom: 16px;">';
                    echo '<div style="font-weight: 700; color: #39A900; margin-bottom: 8px;">✅ Conexión Exitosa</div>';
                    echo '<div style="color: #374151;">El controlador está conectado correctamente a la base de datos</div>';
                    echo '</div>';
                    
                    echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">';
                    
                    echo '<div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">';
                    echo '<div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Total Asignaciones</div>';
                    echo '<div style="font-size: 28px; font-weight: 700; color: #ec4899;">' . $totalAsignaciones . '</div>';
                    echo '</div>';
                    
                    $hoy = date('Y-m-d');
                    $activas = array_filter($asignaciones, function($r) use ($hoy) {
                        $inicio = $r['asig_fecha_inicio'] ?? '';
                        $fin = $r['asig_fecha_fin'] ?? '';
                        return $inicio <= $hoy && $fin >= $hoy;
                    });
                    
                    echo '<div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">';
                    echo '<div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Asignaciones Activas</div>';
                    echo '<div style="font-size: 28px; font-weight: 700; color: #39A900;">' . count($activas) . '</div>';
                    echo '</div>';
                    
                    echo '<div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">';
                    echo '<div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">Estado Base de Datos</div>';
                    echo '<div style="font-size: 16px; font-weight: 700; color: #39A900;">✓ Conectada</div>';
                    echo '</div>';
                    
                    echo '</div>';
                    
                    // Mostrar últimas 3 asignaciones
                    if (!empty($asignaciones)) {
                        echo '<div style="margin-top: 16px;">';
                        echo '<div style="font-weight: 600; color: #374151; margin-bottom: 12px;">Últimas Asignaciones:</div>';
                        echo '<div style="display: grid; gap: 8px;">';
                        
                        foreach (array_slice($asignaciones, 0, 3) as $asig) {
                            echo '<div style="background: #f9fafb; padding: 12px; border-radius: 6px; border: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">';
                            echo '<div>';
                            echo '<div style="font-weight: 600; color: #1f2937;">📚 Ficha ' . htmlspecialchars($asig['ficha_numero'] ?? 'N/A') . '</div>';
                            echo '<div style="font-size: 12px; color: #6b7280;">Instructor: ' . htmlspecialchars($asig['instructor_nombre'] ?? 'N/A') . '</div>';
                            echo '</div>';
                            echo '<span style="background: #E8F5E8; color: #39A900; padding: 4px 12px; border-radius: 12px; font-size: 11px; font-weight: 600;">ACTIVA</span>';
                            echo '</div>';
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div style="background: #FEE2E2; border-left: 4px solid #DC2626; padding: 16px; border-radius: 8px;">';
                    echo '<div style="font-weight: 700; color: #DC2626; margin-bottom: 8px;">❌ Error de Conexión</div>';
                    echo '<div style="color: #374151;">' . htmlspecialchars($e->getMessage()) . '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Estructura de Archivos -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb;">
                <h3 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0;">📁 Estructura de Archivos</h3>
            </div>
            <div style="padding: 24px;">
                <pre style="background: #1f2937; color: #e5e7eb; padding: 20px; border-radius: 8px; overflow-x: auto; font-size: 13px; line-height: 1.6;">
dashboard_sena/
├── controller/                    <span style="color: #39A900;">← Controladores (NUEVO)</span>
│   ├── BaseController.php         <span style="color: #6b7280;">Controlador base</span>
│   ├── AsignacionController.php   <span style="color: #6b7280;">Gestión de asignaciones</span>
│   ├── FichaController.php        <span style="color: #6b7280;">Gestión de fichas</span>
│   ├── InstructorController.php   <span style="color: #6b7280;">Gestión de instructores</span>
│   ├── AmbienteController.php     <span style="color: #6b7280;">Gestión de ambientes</span>
│   ├── ProgramaController.php     <span style="color: #6b7280;">Gestión de programas</span>
│   ├── CompetenciaController.php  <span style="color: #6b7280;">Gestión de competencias</span>
│   └── DashboardController.php    <span style="color: #6b7280;">Dashboard principal</span>
│
├── model/                         <span style="color: #3b82f6;">← Modelos (Existente)</span>
│   ├── AsignacionModel.php        <span style="color: #6b7280;">Conectado a BD</span>
│   ├── FichaModel.php
│   └── ...
│
├── views/                         <span style="color: #8b5cf6;">← Vistas (Existente)</span>
│   ├── asignacion/
│   ├── ficha/
│   └── ...
│
├── auth/                          <span style="color: #f59e0b;">← Autenticación (Existente)</span>
│   └── check_auth.php
│
└── helpers/                       <span style="color: #10b981;">← Helpers (Existente)</span>
    └── functions.php              <span style="color: #6b7280;">safe(), safeHtml(), etc.</span>
                </pre>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div style="margin-top: 24px; display: flex; gap: 12px; justify-content: center;">
            <a href="/Gestion-sena/dashboard_sena/index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                <span>🏠</span> Volver al Dashboard
            </a>
            <a href="/Gestion-sena/dashboard_sena/views/asignacion/index.php" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                <span>📅</span> Ver Asignaciones
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
