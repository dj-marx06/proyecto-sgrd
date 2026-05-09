
// Lógica para abrir/cerrar modal (Mismo estilo que tus compañeros)[cite: 19]
const modalRep = document.getElementById('modalRepresentante');
const formRep = document.getElementById('formRepresentante');

async function abrirModalRepresentante() {
    formRep.reset(); // Limpia los campos
    // Animación suave
    setTimeout(() => {
        modalRep.firstElementChild.classList.remove('scale-95', 'opacity-0');
    }, 10);

    // Cargar atletas
    const contenedor = document.getElementById('contenedorCheckboxes');
    contenedor.innerHTML = '<p class="text-xs text-gray-500 animate-pulse p-2">Consultando atletas...</p>';

    try {
        // 1. Pedimos los atletas al controlador a través del API
        const respuesta = await fetch('api.php?c=representante&accion=listarAtletas');
        const atletas = await respuesta.json();

        if (atletas.length > 0) {
            contenedor.innerHTML = ''; // Limpiamos el mensaje de carga
            
            // 2. Construimos los checkboxes dinámicamente
            atletas.forEach(atleta => {
                const div = document.createElement('div');
                div.className = "flex items-center gap-3 p-2 hover:bg-white/5 rounded-lg cursor-pointer transition";
                div.innerHTML = `
                    <input type="checkbox" name="atletas[]" value="${atleta.id_atleta}" 
                           id="atleta_${atleta.id_atleta}"
                           class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-indigo-600 focus:ring-indigo-500">
                    <label for="atleta_${atleta.id_atleta}" class="text-xs text-gray-300 cursor-pointer flex-1">
                        ${atleta.nombres} ${atleta.apellidos} 
                        <span class="text-[10px] text-gray-500 ml-1">(${atleta.cedula})</span>
                    </label>
                `;
                contenedor.appendChild(div);
            });
        } else {
            contenedor.innerHTML = '<p class="text-xs text-yellow-500 p-2">No hay atletas registrados sin representante.</p>';
        }

    } catch (error) {
        contenedor.innerHTML = '<p class="text-xs text-red-500 p-2">Error al cargar la lista.</p>';
    }

    //Fin Cargar atletas

    modalRep.classList.remove('hidden');


}

function cerrarModalRepresentante() {
    modalRep.classList.add('hidden');
    modalRep.firstElementChild.classList.add('scale-95', 'opacity-0');
}

// Cerramos modal con Escape
document.addEventListener('keydown', (e) => {
    if (e.key === "Escape") cerrarModalRepresentante();
});

// Evento Principal de Guardado
document.addEventListener('DOMContentLoaded', () => {
    
    formRep.addEventListener('submit', async function (e) {
        e.preventDefault(); // Evitamos recarga de página (¡Chao pantallas blancas!)

        const btnGuardar = document.getElementById('btnGuardar');
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = 'Procesando Transacción... <i class="fas fa-spinner fa-spin ml-2"></i>';

        // 1. Recolectamos los Atletas seleccionados (Los checkboxes)
        const checkboxes = document.querySelectorAll('input[name="atletas[]"]:checked');
        const atletasAsignados = Array.from(checkboxes).map(cb => parseInt(cb.value));

        // 2. Empaquetamos todo en el JSON
        const datos = {
            cedula: document.getElementById('cedula').value.trim(),
            nombres: document.getElementById('nombres').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            telefonoP: document.getElementById('telefono_principal').value.trim(),
            telefonoE: document.getElementById('telefono_emergencia').value.trim(),
            email: document.getElementById('correo').value.trim(),
            direccion: document.getElementById('direccion_residencia').value.trim(),
            parentesco: document.getElementById('parentesco').value.trim(),
            atletas_ids: atletasAsignados // <-- Aquí va el array de hijos [1, 2]
        };

        try {
            // 3. Enviamos al API REST
            const respuesta = await fetch('api.php?c=representante&accion=registrar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            });

            const resultado = await respuesta.json();

            // 4. Evaluamos y usamos nuestra clase UI de alertas
            if (respuesta.status === 201) {
                UI.exito('Transacción Exitosa', resultado.message);
                cerrarModalRepresentante();
                // Aquí podrías agregar una función cargarTabla() para refrescar
            } else {
                UI.advertencia('Validación', resultado.message);
            }

        } catch (error) {
            UI.error('Error del Servidor', 'No se pudo completar la transacción.');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = 'GUARDAR Y VINCULAR';
        }
    });
});