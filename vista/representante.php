<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Representantes | SGRD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Mismos estilos base de tus compañeros */
        body { background-color: #0f0d23; color: #a0a0c0; font-family: 'Segoe UI', sans-serif; }
        .sidebar { background-color: #161430; width: 260px; border-right: 1px solid #252345; }
        .tarjeta { background-color: #161430; border: 1px solid #252345; border-radius: 15px; }
        .input-dark { background: #0f0d23; border: 1px solid #252345; color: white; transition: all 0.3s ease; }
        .input-dark:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); outline: none; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f0d23; }
        ::-webkit-scrollbar-thumb { background: #252345; border-radius: 10px; }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Menú Lateral -->
    <?php include RAIZ . 'vista/complementos/menu.php'; ?>

    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-12">
            <h1 class="text-2xl font-bold text-white">Gestión de Representantes</h1>
            
            <!-- Perfil y Salida[cite: 20] -->
            <div class="flex items-center gap-3 border-l border-gray-700 pl-6">
                <div class="text-right mr-2">
                    <p class="text-sm text-white font-medium"><?php echo $_SESSION['nombre'] ?? 'Admin'; ?></p>
                    <a href="?p=salir" class="text-[10px] text-red-400 hover:text-red-300 font-bold uppercase tracking-widest transition">
                        Cerrar Sesión <i class="fas fa-sign-out-alt ml-1"></i>
                    </a>
                </div>
            </div>
        </header>
        
        <!-- Barra de Control -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
            <div class="flex items-center gap-2 text-sm text-indigo-400">
                <i class="fas fa-users"></i>
                <span class="font-medium tracking-wide uppercase text-xs">Directorio Familiar</span>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" id="busquedaCedula" placeholder="Buscar por cédula..." 
                           class="input-dark w-full pl-11 pr-4 py-3 rounded-xl text-sm shadow-inner">
                </div>
                <button onclick="abrirModalRepresentante()" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-500/20 active:scale-95">
                    <i class="fas fa-plus"></i> Nuevo Representante
                </button>
            </div>
        </div>

        <!-- Tabla de Datos (Estructura visual idéntica a la tuya)[cite: 19] -->
        <div class="tarjeta overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="tablaRepresentantes">
                    <thead class="bg-[#1c1a3a] text-gray-400 text-xs uppercase tracking-widest">
                        <tr>
                            <th class="p-4">Representante</th>
                            <th class="p-4">Cédula</th>
                            <th class="p-4">Teléfono</th>
                            <th class="p-4">Parentesco</th>
                            <th class="p-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-800" id="listaRepresentantes">
                        <!-- Aquí se inyectarán las filas con JS (Fetch) o PHP -->
                        <tr>
                            <td colspan="5" class="text-center p-4 text-gray-500">Cargando datos...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL DE REGISTRO (CON SELECCIÓN MULTIPLE DE ATLETAS) -->
    <div id="modalRepresentante" class="fixed inset-0 bg-[#0f0d23]/90 backdrop-blur-md hidden flex items-center justify-center p-4 z-50">
        <div class="tarjeta w-full max-w-3xl p-8 shadow-2xl transform transition-all scale-95 animate-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-lg text-white"><i class="fas fa-user-shield"></i></div>
                    <h2 class="text-xl font-bold text-white">Registro de Representante</h2>
                </div>
                <button type="button" onclick="cerrarModalRepresentante()" class="text-gray-500 hover:text-white transition-colors"><i class="fas fa-times text-2xl"></i></button>
            </div>

            <form id="formRepresentante">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Datos Personales -->
                    <div class="space-y-4 border-r border-gray-800 pr-4">
                        <h3 class="text-sm text-indigo-400 font-bold uppercase tracking-widest mb-4">Datos Personales</h3>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Cédula</label>
                            <input type="text" id="cedula" required class="input-dark w-full p-3 rounded-xl">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="space-y-2">
                                <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Nombres</label>
                                <input type="text" id="nombres" required class="input-dark w-full p-3 rounded-xl">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Apellidos</label>
                                <input type="text" id="apellidos" required class="input-dark w-full p-3 rounded-xl">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Teléfono</label>
                            <input type="text" id="telefono" required class="input-dark w-full p-3 rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Email</label>
                            <input type="email" id="email" class="input-dark w-full p-3 rounded-xl">
                        </div>
                    </div>

                    <!-- Vinculación de Atletas (La Magia 1:N) -->
                    <div class="space-y-4 pl-2">
                        <h3 class="text-sm text-indigo-400 font-bold uppercase tracking-widest mb-4">Vincular Atletas</h3>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Parentesco</label>
                            <select id="parentesco" class="input-dark w-full p-3 rounded-xl">
                                <option value="Padre/Madre">Padre / Madre</option>
                                <option value="Tío/a">Tío / Tía</option>
                                <option value="Abuelo/a">Abuelo / Abuela</option>
                                <option value="Otro">Otro (Legal)</option>
                            </select>
                        </div>

                        <!-- Lista scrolleable de checkboxes para Tailwind -->
                        <div class="space-y-2 mt-4">
                            <label class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Seleccionar Atletas (Sin asignar)</label>
                            <div class="input-dark rounded-xl h-40 overflow-y-auto p-2 space-y-1" id="listaAtletasCheckbox">
                                <!-- Se llena dinámicamente con JS, pero aquí un ejemplo estático -->
                                <label class="flex items-center gap-3 p-2 hover:bg-white/5 rounded cursor-pointer transition">
                                    <input type="checkbox" name="atletas[]" value="1" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded">
                                    <span class="text-sm text-gray-300">Miguel Pirolo (V-25.000.000)</span>
                                </label>
                                <label class="flex items-center gap-3 p-2 hover:bg-white/5 rounded cursor-pointer transition">
                                    <input type="checkbox" name="atletas[]" value="2" class="w-4 h-4 text-indigo-600 bg-gray-800 border-gray-600 rounded">
                                    <span class="text-sm text-gray-300">Ana Pérez (V-30.123.456)</span>
                                </label>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">* Puedes seleccionar varios atletas si son hermanos.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="cerrarModalRepresentante()" class="flex-1 bg-gray-800 text-gray-400 py-4 rounded-xl font-bold transition hover:bg-gray-700">CANCELAR</button>
                    <button type="submit" id="btnGuardar" class="flex-[2] bg-indigo-600 hover:bg-indigo-500 py-4 rounded-xl font-bold text-white shadow-lg shadow-indigo-500/20 active:scale-95 transition">
                        GUARDAR Y VINCULAR
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Referencia a utilidades globales -->
    <script src="assets/js/alertas.js"></script>
    <script src="assets/js/representante.js"></script>
</body>
</html>