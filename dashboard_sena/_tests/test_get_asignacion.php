<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test - Get Asignación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-section {
            background: #f9fafb;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #39A900;
        }
        button {
            background: #39A900;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        button:hover {
            background: #007832;
        }
        pre {
            background: #1f2937;
            color: #10b981;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
        }
        .error {
            color: #ef4444;
        }
        .success {
            color: #10b981;
        }
    </style>
</head>
<body>
    <h1>🧪 Test - Get Asignación API</h1>
    
    <div class="test-section">
        <h2>1. Listar Asignaciones Disponibles</h2>
        <button onclick="listarAsignaciones()">Listar Todas las Asignaciones</button>
        <pre id="lista-asignaciones">Haz click en el botón para ver las asignaciones...</pre>
    </div>
    
    <div class="test-section">
        <h2>2. Probar Get Asignación por ID</h2>
        <input type="number" id="test-id" placeholder="ID de asignación" value="1" style="padding: 10px; margin-right: 10px;">
        <button onclick="testGetAsignacion()">Probar Get Asignación</button>
        <pre id="resultado-test">Ingresa un ID y haz click en el botón...</pre>
    </div>
    
    <div class="test-section">
        <h2>3. Probar con ID Inexistente</h2>
        <button onclick="testIdInexistente()">Probar ID 99999</button>
        <pre id="resultado-inexistente">Haz click para probar...</pre>
    </div>

    <script>
        function listarAsignaciones() {
            const output = document.getElementById('lista-asignaciones');
            output.textContent = 'Cargando...';
            
            fetch('../views/asignacion/get_form_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.asignaciones && data.asignaciones.length > 0) {
                        let texto = `✅ Se encontraron ${data.asignaciones.length} asignaciones:\n\n`;
                        data.asignaciones.forEach((asig, index) => {
                            const id = asig.asig_id || asig.ASIG_ID || asig.id || 'N/A';
                            texto += `${index + 1}. ID: ${id} - Ficha: ${asig.ficha_numero || 'N/A'}\n`;
                        });
                        output.textContent = texto;
                        output.className = 'success';
                    } else {
                        output.textContent = '⚠️ No se encontraron asignaciones en la base de datos';
                        output.className = 'error';
                    }
                })
                .catch(error => {
                    output.textContent = '❌ Error: ' + error.message;
                    output.className = 'error';
                });
        }
        
        function testGetAsignacion() {
            const id = document.getElementById('test-id').value;
            const output = document.getElementById('resultado-test');
            output.textContent = `Probando con ID: ${id}...`;
            
            fetch(`../views/asignacion/get_asignacion.php?id=${id}`)
                .then(response => {
                    console.log('Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        output.textContent = `❌ Error: ${data.error}\n\n` + JSON.stringify(data, null, 2);
                        output.className = 'error';
                    } else {
                        output.textContent = `✅ Asignación encontrada:\n\n` + JSON.stringify(data, null, 2);
                        output.className = 'success';
                    }
                })
                .catch(error => {
                    output.textContent = `❌ Error de red: ${error.message}`;
                    output.className = 'error';
                });
        }
        
        function testIdInexistente() {
            const output = document.getElementById('resultado-inexistente');
            output.textContent = 'Probando con ID inexistente...';
            
            fetch('../views/asignacion/get_asignacion.php?id=99999')
                .then(response => {
                    console.log('Status:', response.status);
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        output.textContent = `✅ Manejo de error correcto:\n\n` + JSON.stringify(data, null, 2);
                        output.className = 'success';
                    } else {
                        output.textContent = `⚠️ Debería retornar error pero retornó:\n\n` + JSON.stringify(data, null, 2);
                        output.className = 'error';
                    }
                })
                .catch(error => {
                    output.textContent = `❌ Error de red: ${error.message}`;
                    output.className = 'error';
                });
        }
    </script>
</body>
</html>
