<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; padding: 24px 32px;">
    
    <!-- Programas -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #E8F5E8 0%, #d4edda 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="book-open" style="width: 28px; height: 28px; color: #39A900;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Programas</div>
                <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalProgramas; ?></div>
            </div>
        </div>
    </div>

    <!-- Fichas -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="file-text" style="width: 28px; height: 28px; color: #3b82f6;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Fichas</div>
                <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalFichas; ?></div>
            </div>
        </div>
    </div>

    <!-- Instructores -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="users" style="width: 28px; height: 28px; color: #8b5cf6;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Instructores</div>
                <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalInstructores; ?></div>
            </div>
        </div>
    </div>

    <!-- Ambientes -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="home" style="width: 28px; height: 28px; color: #f59e0b;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Ambientes</div>
                <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalAmbientes; ?></div>
            </div>
        </div>
    </div>

    <!-- Asignaciones -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="calendar" style="width: 28px; height: 28px; color: #ec4899;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Asignaciones</div>
                <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalAsignaciones; ?></div>
            </div>
        </div>
    </div>

    <!-- Competencias de Instructores -->
    <div class="stat-card" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.3s;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="award" style="width: 28px; height: 28px; color: #10b981;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Competencias</div>
                <div style="display: flex; align-items: baseline; gap: 8px;">
                    <div style="font-size: 28px; font-weight: 600; color: #1f2937; line-height: 1;"><?php echo $totalCompetenciasInstructor; ?></div>
                    <div style="display: flex; align-items: center; gap: 4px; padding: 3px 8px; background: #D1FAE5; border-radius: 12px;">
                        <i data-lucide="check-circle" style="width: 12px; height: 12px; color: #10b981;"></i>
                        <span style="font-size: 11px; color: #059669; font-weight: 600;"><?php echo $competenciasVigentes; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
