!function(){!async function(){try{const e="/api/tareas?id="+t(),a=await fetch(e);!function(e){if(0==e.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No hay Tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const t={0:"Pendiente",1:"Completa"};e.forEach(e=>{const a=document.createElement("LI");a.dataset.tareaId=e.id,a.classList.add("tarea");document.createElement("P").textContent=e.nombre;document.createElement("DIV").classList.add("opciones");const n=document.createElement("BUTTON");n.classList.add("estado-tarea"),n.classList.add(""+t[e.estado].toLowerCase()),n.dataset.estadoTarea=e.estado,n.textContent=t[e.estado],console.log(n)})}(await a.json())}catch(e){console.log(e)}}();function e(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",e),o.textContent=t,a.parentElement.insertBefore(o,a.nextElementSibling),setTimeout(()=>{o.remove()},3e3)}function t(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelector("#agregar-tarea").addEventListener("click",(function(){const a=document.createElement("DIV");a.classList.add("modal"),a.innerHTML='\n            <form class="formulario nueva-tarea">\n                <legend>Añade una Nueva Tarea</legend>\n                <div class="campo">\n                    <label>Tarea</label>\n                    <input type="text" name="tarea" placeholder="Añadir Tarea al Proyecto Actual" id="tarea">\n\n                </div>\n                <div class="opciones">\n                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n        \n        \n      ',setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),a.addEventListener("click",(function(n){if(n.preventDefault(),n.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),setTimeout(()=>{a.remove()},300)}n.target.classList.contains("submit-nueva-tarea")&&function(){const a=document.querySelector("#tarea").value.trim();if(""==a)return void e("error","El Nombre de la Tarea es Obligatorio",document.querySelector(".formulario legend"));!async function(a){const n=new FormData;n.append("nombre",a),n.append("proyectoid",t());try{const t="http://localhost:3000/api/tarea",a=await fetch(t,{method:"POST",body:n}),o=await a.json();if(console.log(o),e(o.tipo,o.mensaje,document.querySelector(".formulario legend")),"exito"==o.tipo){const e=document.querySelector(".modal");setTimeout(()=>{e.remove()},3e3)}}catch(e){console.log(e)}}(a)}()})),document.querySelector(".dashboard").appendChild(a)}))}();