// =====================================================================
// CONFIGURACIÓN PRINCIPAL
// =====================================================================
const modalRep = document.getElementById('modalRepresentante');
const formRep = document.getElementById('formRepresentante');
const API_URL = 'api.php?c=representante'; // Cambia esto si tu URL base es otra

/**
 * Función centralizada para todo el CRUD (Reutilizable para Guardar, Editar y Eliminar)
 * @param {string} accion - Lo que enviamos por GET a la URL (ej: registrar, eliminar)
 * @param {FormData|null} datos - Los datos del formulario si es POST
 * @returns {object} - El JSON del servidor
 */
async function peticionAjax(accion, datos = null) {
    const opciones = {
        method: datos ? 'POST' : 'GET'
    };
    
    // Si hay datos, los enviamos. Fetch configura el 'Content-Type' automáticamente para FormData
    if (datos) opciones.body = datos; 

    try {
        const respuesta = await fetch(`${API_URL}&accion=${accion}`, opciones);
        if (!respuesta.ok) throw new Error('Error de red');
        return await respuesta.json();
    } catch (error) {
        console.error("Error en petición AJAX:", error);
        UI.error('Error del Servidor', 'No se pudo comunicar con el sistema.');
        return null;
    }
}

// =====================================================================
// FUNCIONES DE INTERFAZ (UI)
// =====================================================================
function cerrarModalRepresentante() {
    modalRep.classList.add('hidden');
    modalRep.firstElementChild.classList.add('scale-95', 'opacity-0');
}

document.addEventListener('keydown', (e) => {
    if (e.key === "Escape" && !modalRep.classList.contains('hidden')) {
        cerrarModalRepresentante();
    }
});

// =====================================================================
// 1. LEER (Consultar Atletas para el Modal)
// =====================================================================
async function abrirModalRepresentante(idRepresentante = null) {
    formRep.reset(); 
    
    // Si viene un ID, significa que es EDITAR. Si no, es REGISTRAR.
    if (idRepresentante) {
        // Aquí puedes agregar un input oculto al formulario para saber qué ID editar
        // document.getElementById('cedula_original').value = idRepresentante;
    }

    setTimeout(() => {
        modalRep.firstElementChild.classList.remove('scale-95', 'opacity-0');
    }, 10);

    const contenedor = document.getElementById('contenedorCheckboxes');
    contenedor.innerHTML = '<p class="text-xs text-gray-500 animate-pulse p-2">Consultando atletas...</p>';

    // Usamos nuestra función centralizada en modo GET
    const atletas = await peticionAjax('listarAtletas');

    if (atletas && atletas.length > 0) {
        contenedor.innerHTML = ''; 
        atletas.forEach(atleta => {
            const div = document.createElement('div');
            div.className = "flex items-center gap-3 p-2 hover:bg-white/5 rounded-lg cursor-pointer transition";
            div.innerHTML = `
                <input type="checkbox" name="atletas_ids[]" value="${atleta.id_atleta}" 
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
        contenedor.innerHTML = '<p class="text-xs text-yellow-500 p-2">No hay atletas disponibles.</p>';
    }

    modalRep.classList.remove('hidden');
}

// =====================================================================
// 2. CREAR / ACTUALIZAR (Procesar el Formulario)
// =====================================================================
document.addEventListener('DOMContentLoaded', () => {
    
    formRep.addEventListener('submit', async function (e) {
        e.preventDefault(); 

        const btnGuardar = document.getElementById('btnGuardar');
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = 'Procesando... <i class="fas fa-spinner fa-spin ml-2"></i>';

        // MAGIA DE FORMDATA: Empaca todos los inputs y checkboxes automáticamente
        // Asegúrate de que los 'name' de tus inputs en HTML coincidan con lo que espera PHP
        const datosForm = new FormData(formRep);

        // Usamos la función centralizada en modo POST
        const resultado = await peticionAjax('guardar', datosForm);

        if (resultado) {
            // Manejamos las 3 respuestas del Controlador Pivote
            if (resultado.status === 'success') {
                UI.exito('Transacción Exitosa', resultado.message);
                cerrarModalRepresentante();
                // recargarTabla(); <-- Llamar función para actualizar listado
            } 
            else if (resultado.status === 'warning') {
                // Errores de validación del Modelo (ej: Cédula duplicada)
                let msjErrores = Object.values(resultado.errores).join("<br>");
                UI.advertencia('Revise los datos', msjErrores);
            } 
            else {
                // Error de Base de datos ('error')
                UI.error('Atención', resultado.message);
            }
        }

        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
    });
});

// =====================================================================
// 3. ELIMINAR (Ejemplo de cómo reusar la función central)
// =====================================================================
async function eliminarRepresentante(id_representante) {
    // Aquí puedes llamar a tu SweetAlert para confirmar
    /* Si confirma: */
    
    let datosDelete = new FormData();
    datosDelete.append('id_representante', id_representante);
    
    // Usamos EXACTAMENTE la misma función
    const resultado = await peticionAjax('eliminar', datosDelete);
    
    if (resultado && resultado.status === 'success') {
        UI.exito('Eliminado', resultado.message);
        // recargarTabla();
    }
}