(function(){
    obtenerTareas();
    let tareas = [];

    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click", function(){
        mostrarFormulario();
    });

    async function obtenerTareas(){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado;
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    }
    
    function mostrarTareas(){
        limpiarTareas();
        if(tareas.length == 0){
            const contenedorTareas = document.querySelector("#listado-tareas");
            const textoNoTareas = document.createElement("LI");
            textoNoTareas.textContent = "No hay Tareas";
            textoNoTareas.classList.add("no-tareas");
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: "Pendiente",
            1: "Completa"
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement("LI");
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add("tarea");

            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function(){
                mostrarFormulario(true,{...tarea});
            };

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.textContent = estados[tarea.estado]; 
            btnEstadoTarea.ondblclick = function(){
                cambiarEstadoTarea({...tarea});
            };
            const btnEliminarTarea = document.createElement("BUTTON");
            btnEliminarTarea.classList.add("eliminar-tarea");
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent="Eliminar";
            btnEliminarTarea.ondblclick = function(){
                confirmarEliminarTarea({...tarea});
            };

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector("#listado-tareas");
            listadoTareas.appendChild(contenedorTarea);
            
        });
    }

    function mostrarFormulario(editar = false,tarea = {}){
       
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML=`
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Añade una Nueva Tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="${tarea.nombre ? "Edita la Tarea" : "Añadir Tarea al Proyecto"}" id="tarea" value='${tarea.nombre ? tarea.nombre : ""}'>

                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? "Guardar Cambios" : "Añadir Tarea"}">
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
        
        
      `;
      setTimeout(() =>{
        const formulario = document.querySelector(".formulario");
        formulario.classList.add("animar");

      } ,0);
      modal.addEventListener("click",function(e){
        e.preventDefault();
        if(e.target.classList.contains("cerrar-modal")){
            const formulario = document.querySelector(".formulario");
            formulario.classList.add("cerrar");
            setTimeout(() =>{
                modal.remove();
              } ,300);


        } 
        if(e.target.classList.contains("submit-nueva-tarea")){
            const nombreTarea = document.querySelector("#tarea").value.trim();
            if(nombreTarea == ""){
                mostrarAlerta("error","El Nombre de la Tarea es Obligatorio",document.querySelector(".formulario legend"));
                return;
            }
            if(editar){
                tarea.nombre = nombreTarea;
                actualizarTarea(tarea);
            }else{
                agregarTarea(nombreTarea);

            }
        } 
      })
      document.querySelector(".dashboard").appendChild(modal);
    }

 
    function mostrarAlerta(tipo,mensaje,referencia){
        const alertaPrevia = document.querySelector(".alerta");

        if(alertaPrevia) alertaPrevia.remove();
        const alerta = document.createElement("DIV");
        alerta.classList.add("alerta",tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta,referencia.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
   async function agregarTarea(tarea){
        const datos = new FormData();
        datos.append("nombre",tarea);
        datos.append("proyectoid",obtenerProyecto());

       

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url,{
                method: "POST",
                body : datos
            });
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.tipo,resultado.mensaje,document.querySelector(".formulario legend"));
            if(resultado.tipo == "exito"){
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    modal.remove();
                }, 3000);
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoid: resultado.proyectoid
                }
                tareas = [...tareas,tareaObj];
                mostrarTareas();
            }


        } catch (error) {
            console.log(error);
        }
    }
    function obtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }
    function limpiarTareas(){
        const listadoTareas = document.querySelector("#listado-tareas");
        while(listadoTareas.firstChild){
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }
    function cambiarEstadoTarea(tarea){
        const nuevoEstado = tarea.estado == "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);


    }
    async function actualizarTarea(tarea){
      
        const {estado, id, nombre, proyectoid} = tarea;
        const datos = new FormData();
        datos.append("id",id);
        datos.append("estado",estado);
        datos.append("nombre",nombre);
        datos.append("proyectoid",obtenerProyecto());
        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            if(resultado.respuesta.tipo == "exito"){
                Swal.fire(resultado.respuesta.mensaje,resultado.respuesta.mensaje,"success");
                const modal = document.querySelector(".modal");
                if(modal){
                modal.remove();
                };
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id == id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
        /*RECORRE UN FORMDATA!
        for(let datoss of datos.values()){
            console.log(datoss);
        };
        */
    }
    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: '¿Eliminar Tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: `No`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
              eliminarTarea(tarea);
            }
          });
    }
    async function eliminarTarea(tarea){
        const {estado, id, nombre} = tarea;
        const datos = new FormData();
        datos.append("id",id);
        datos.append("estado",estado);
        datos.append("nombre",nombre);
        datos.append("proyectoid",obtenerProyecto());
        try {
            url = "http://localhost:3000/api/tarea/eliminar";
            const respuesta = await fetch(url,{
                method: "POST",
                body: datos
            });
            const resultado = await respuesta.json();
            
            if(resultado.resultado){
                //mostrarAlerta("exito",resultado.mensaje,document.querySelector(".contenedor-nueva-tarea"));
                Swal.fire('Eliminado!',resultado.mensaje,"success")


                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            };

        } catch (error) {
            
        }
    }
})();   