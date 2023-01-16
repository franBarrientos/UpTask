(function(){
    obtenerTareas();

    const nuevaTareaBtn = document.querySelector("#agregar-tarea");
    nuevaTareaBtn.addEventListener("click",mostrarFormulario);

    async function obtenerTareas(){
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const tareas = await respuesta.json();
            mostrarTareas(tareas);
        } catch (error) {
            console.log(error);
        }
    }
    
    function mostrarTareas(tareas){
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

            const opcionesDiv = document.createElement("DIV");
            opcionesDiv.classList.add("opciones");

            const btnEstadoTarea = document.createElement("BUTTON");
            btnEstadoTarea.classList.add("estado-tarea");
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.textContent = estados[tarea.estado]; 
            console.log(btnEstadoTarea);
        });
    }

    function mostrarFormulario(){
        const modal = document.createElement("DIV");
        modal.classList.add("modal");
        modal.innerHTML=`
            <form class="formulario nueva-tarea">
                <legend>Añade una Nueva Tarea</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input type="text" name="tarea" placeholder="Añadir Tarea al Proyecto Actual" id="tarea">

                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">
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
            submitFormularioNuevaTarea();
        } 
      })
      document.querySelector(".dashboard").appendChild(modal);
    }
    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector("#tarea").value.trim();
        if(tarea == ""){
            mostrarAlerta("error","El Nombre de la Tarea es Obligatorio",document.querySelector(".formulario legend"));
            return;
        }
        agregarTarea(tarea);

         
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
            console.log(resultado);
            mostrarAlerta(resultado.tipo,resultado.mensaje,document.querySelector(".formulario legend"));
            if(resultado.tipo == "exito"){
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    modal.remove();
                }, 3000);
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
})();   