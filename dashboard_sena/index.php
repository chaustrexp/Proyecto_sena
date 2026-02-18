<?php
// Proteger página con autenticación
require_once __DIR__ . '/auth/check_auth.php';

require_once __DIR__ . '/model/ProgramaModel.php';
require_once __DIR__ . '/model/FichaModel.php';
require_once __DIR__ . '/model/InstructorModel.php';
require_once __DIR__ . '/model/AmbienteModel.php';
require_once __DIR__ . '/model/AsignacionModel.php';
require_once __DIR__ . '/model/InstruCompetenciaModel.php';

// Verificar si la base de datos existe
try {
    $programaModel = new ProgramaModel();
    $fichaModel = new FichaModel();
    $instructorModel = new InstructorModel();
    $ambienteModel = new AmbienteModel();
    $asignacionModel = new AsignacionModel();
    $instruCompetenciaModel = new InstruCompetenciaModel();

    $totalProgramas = $programaModel->count();
    $totalFichas = $fichaModel->count();
    $totalInstructores = $instructorModel->count();
    $totalAmbientes = $ambienteModel->count();
    $totalAsignaciones = $asignacionModel->count();
    $totalCompetenciasInstructor = $instruCompetenciaModel->count();
    $competenciasVigentes = $instruCompetenciaModel->countVigentes();
    $ultimasAsignaciones = $asignacionModel->getRecent(5);
    $asignacionesCalendario = $asignacionModel->getForCalendar();
} catch (Exception $e) {
    // Si hay error, simplemente mostrar arrays vacíos
    $totalProgramas = 0;
    $totalFichas = 0;
    $totalInstructores = 0;
    $totalAmbientes = 0;
    $totalAsignaciones = 0;
    $totalCompetenciasInstructor = 0;
    $competenciasVigentes = 0;
    $ultimasAsignaciones = [];
    $asignacionesCalendario = [];
}

$pageTitle = "Dashboard Principal";
include __DIR__ . '/views/layout/header.php';
include __DIR__ . '/views/layout/sidebar.php';
?>

<div class="main-content">
    <!-- Header del Dashboard -->
    <div style="padding: 32px 32px 24px; border-bottom: 1px solid #e5e7eb;">
        <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0 0 4px;">Bienvenido al Sistema SENA</h1>
        <p style="font-size: 14px; color: #6b7280; margin: 0;">Resumen general de la gestión académica</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 24px 32px;">
        
        <!-- Programas -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #E8F5E8; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="book-open" style="width: 24px; height: 24px; color: #39A900;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Programas</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalProgramas; ?></div>
        </div>

        <!-- Fichas -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="file-text" style="width: 24px; height: 24px; color: #3b82f6;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Fichas</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalFichas; ?></div>
        </div>

        <!-- Instructores -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #F5F3FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="users" style="width: 24px; height: 24px; color: #8b5cf6;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Instructores</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalInstructores; ?></div>
        </div>

        <!-- Ambientes -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="home" style="width: 24px; height: 24px; color: #f59e0b;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Ambientes</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalAmbientes; ?></div>
        </div>

        <!-- Asignaciones -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #FCE7F3; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="calendar" style="width: 24px; height: 24px; color: #ec4899;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Asignaciones</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalAsignaciones; ?></div>
        </div>

        <!-- Competencias de Instructores -->
        <div style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <div style="width: 48px; height: 48px; background: #F5F3FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="award" style="width: 24px; height: 24px; color: #8b5cf6;"></i>
                </div>
            </div>
            <div style="font-size: 13px; color: #6b7280; margin-bottom: 4px;">Competencias Instructor</div>
            <div style="font-size: 32px; font-weight: 700; color: #1f2937;"><?php echo $totalCompetenciasInstructor; ?></div>
            <div style="font-size: 12px; color: #10b981; margin-top: 8px;">
                <i data-lucide="check-circle" style="width: 14px; height: 14px; vertical-align: middle;"></i>
                <?php echo $competenciasVigentes; ?> vigentes
            </div>
        </div>
    </div>

    <!-- Calendario de Asignaciones -->
    <div style="padding: 0 32px 24px;">
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
            
            <!-- Header del calendario -->
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 4px;">
                        <i data-lucide="calendar-days" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 8px;"></i>
                        Calendario de Asignaciones
                    </h2>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Vista mensual de todas las asignaciones</p>
                </div>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <button id="prevMonth" class="btn btn-secondary btn-sm">
                        <i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i>
                    </button>
                    <span id="currentMonth" style="font-weight: 600; color: #1f2937; min-width: 150px; text-align: center;"></span>
                    <button id="nextMonth" class="btn btn-secondary btn-sm">
                        <i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i>
                    </button>
                    <button id="todayBtn" class="btn btn-primary btn-sm" style="margin-left: 8px;">Hoy</button>
                </div>
            </div>

            <!-- Calendario -->
            <div id="calendar" style="padding: 24px;"></div>
        </div>
    </div>

    <!-- Tabla de Últimas Asignaciones -->
    <div style="padding: 0 32px 32px;">
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
            
            <!-- Header de la tabla -->
            <div style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin: 0 0 4px;">Últimas Asignaciones</h2>
                    <p style="font-size: 13px; color: #6b7280; margin: 0;">Asignaciones recientes del sistema</p>
                </div>
                <a href="/Gestion-sena/dashboard_sena/views/asignacion/index.php" class="btn btn-secondary btn-sm">
                    Ver todas
                </a>
            </div>

            <!-- Tabla -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Ficha</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Instructor</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Ambiente</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Fecha Inicio</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Fecha Fin</th>
                            <th style="padding: 12px 24px; text-align: left; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ultimasAsignaciones)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px 20px; color: #6b7280;">
                                <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
                                <p style="margin: 0; font-size: 16px;">No hay asignaciones registradas</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($ultimasAsignaciones as $asignacion): ?>
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;">
                                <td style="padding: 16px 24px;">
                                    <strong style="color: #1f2937;"><?php echo htmlspecialchars($asignacion['ficha_numero'] ?? ''); ?></strong>
                                </td>
                                <td style="padding: 16px 24px; color: #6b7280;">
                                    <?php echo htmlspecialchars($asignacion['instructor_nombre'] ?? ''); ?>
                                </td>
                                <td style="padding: 16px 24px; color: #6b7280;">
                                    <?php echo htmlspecialchars($asignacion['ambiente_nombre'] ?? ''); ?>
                                </td>
                                <td style="padding: 16px 24px; color: #6b7280;">
                                    <?php echo isset($asignacion['fecha_inicio']) ? date('d/m/Y', strtotime($asignacion['fecha_inicio'])) : 'N/A'; ?>
                                </td>
                                <td style="padding: 16px 24px; color: #6b7280;">
                                    <?php echo isset($asignacion['fecha_fin']) ? date('d/m/Y', strtotime($asignacion['fecha_fin'])) : 'N/A'; ?>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <?php 
                                    $hoy = date('Y-m-d');
                                    $fecha_inicio = $asignacion['fecha_inicio'] ?? '';
                                    $fecha_fin = $asignacion['fecha_fin'] ?? '';
                                    
                                    if ($fecha_fin && $fecha_fin < $hoy) {
                                        echo '<span style="background: #FEE2E2; color: #DC2626; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Finalizada</span>';
                                    } elseif ($fecha_inicio && $fecha_inicio > $hoy) {
                                        echo '<span style="background: #FEF3C7; color: #D97706; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Pendiente</span>';
                                    } else {
                                        echo '<span style="background: #E8F5E8; color: #39A900; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Activa</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Datos de asignaciones desde PHP
    const asignaciones = <?php echo json_encode($asignacionesCalendario); ?>;
    
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    
    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    
    function renderCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const prevLastDay = new Date(currentYear, currentMonth, 0);
        
        const firstDayIndex = firstDay.getDay();
        const lastDayDate = lastDay.getDate();
        const prevLastDayDate = prevLastDay.getDate();
        
        // Actualizar título del mes
        document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;
        
        let calendarHTML = '<div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">';
        
        // Headers de días
        dayNames.forEach(day => {
            calendarHTML += `<div style="text-align: center; font-weight: 600; color: #6b7280; font-size: 12px; padding: 8px 0; text-transform: uppercase;">${day}</div>`;
        });
        
        // Días del mes anterior
        for (let i = firstDayIndex; i > 0; i--) {
            calendarHTML += `<div style="min-height: 100px; padding: 8px; background: #f9fafb; border-radius: 8px; opacity: 0.5;">
                <div style="font-size: 14px; color: #9ca3af; font-weight: 500;">${prevLastDayDate - i + 1}</div>
            </div>`;
        }
        
        // Días del mes actual
        const today = new Date();
        for (let day = 1; day <= lastDayDate; day++) {
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
            
            // Buscar asignaciones para este día
            const dayAssignments = asignaciones.filter(a => {
                const inicio = new Date(a.fecha_inicio);
                const fin = new Date(a.fecha_fin);
                const currentDay = new Date(currentYear, currentMonth, day);
                return currentDay >= inicio && currentDay <= fin;
            });
            
            let dayStyle = 'min-height: 100px; padding: 8px; background: white; border: 1px solid #e5e7eb; border-radius: 8px; transition: all 0.2s;';
            if (isToday) {
                dayStyle = 'min-height: 100px; padding: 8px; background: #E8F5E8; border: 2px solid #39A900; border-radius: 8px; transition: all 0.2s;';
            }
            
            calendarHTML += `<div style="${dayStyle}" class="calendar-day">
                <div style="font-size: 14px; color: ${isToday ? '#39A900' : '#1f2937'}; font-weight: ${isToday ? '700' : '500'}; margin-bottom: 4px;">${day}</div>`;
            
            // Mostrar asignaciones
            if (dayAssignments.length > 0) {
                dayAssignments.slice(0, 2).forEach(asig => {
                    calendarHTML += `<div style="background: #39A900; color: white; padding: 4px 6px; border-radius: 4px; font-size: 10px; margin-bottom: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${asig.instructor_nombre} - Ficha ${asig.ficha_numero}">
                        📚 ${asig.ficha_numero}
                    </div>`;
                });
                if (dayAssignments.length > 2) {
                    calendarHTML += `<div style="font-size: 10px; color: #6b7280; margin-top: 2px;">+${dayAssignments.length - 2} más</div>`;
                }
            }
            
            calendarHTML += '</div>';
        }
        
        // Días del siguiente mes
        const remainingDays = 7 - ((firstDayIndex + lastDayDate) % 7);
        if (remainingDays < 7) {
            for (let i = 1; i <= remainingDays; i++) {
                calendarHTML += `<div style="min-height: 100px; padding: 8px; background: #f9fafb; border-radius: 8px; opacity: 0.5;">
                    <div style="font-size: 14px; color: #9ca3af; font-weight: 500;">${i}</div>
                </div>`;
            }
        }
        
        calendarHTML += '</div>';
        document.getElementById('calendar').innerHTML = calendarHTML;
        
        // Hover effect en días
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('mouseenter', function() {
                if (!this.style.background.includes('#E8F5E8')) {
                    this.style.background = '#f9fafb';
                    this.style.transform = 'scale(1.02)';
                }
            });
            day.addEventListener('mouseleave', function() {
                if (!this.style.background.includes('#E8F5E8')) {
                    this.style.background = 'white';
                    this.style.transform = 'scale(1)';
                }
            });
        });
        
        lucide.createIcons();
    }
    
    // Event listeners para navegación
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    });
    
    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar();
    });
    
    document.getElementById('todayBtn').addEventListener('click', () => {
        const today = new Date();
        currentMonth = today.getMonth();
        currentYear = today.getFullYear();
        renderCalendar();
    });
    
    // Renderizar calendario inicial
    renderCalendar();
    
    lucide.createIcons();
    
    // Hover effect en cards
    document.querySelectorAll('[style*="background: white"]').forEach(card => {
        if (card.querySelector('[data-lucide]')) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        }
    });
    
    // Hover effect en filas de tabla
    document.querySelectorAll('tbody tr').forEach(row => {
        if (row.cells.length > 1) {
            row.addEventListener('mouseenter', function() {
                this.style.background = '#f9fafb';
            });
            row.addEventListener('mouseleave', function() {
                this.style.background = 'white';
            });
        }
    });
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
