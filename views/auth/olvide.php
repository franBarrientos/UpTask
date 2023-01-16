<div class="contenedor olvide">
    <?php include_once __DIR__."/../templates/header.php";?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">
        Ingrese su Email para Restablecer Password
    </p>
    <?php include_once __DIR__."/../templates/alertas.php";?>

        <form action="/olvide" class="formulario" method="POST">
          
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>
            <input type="submit" name="" id="" class="boton" value="Enviar Instrucciones"> 
        </form>

        <div class="acciones">
             <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>   
             <a href="/crear">¿Aun no tienes una cuenta? Crear una</a>   
        </div>
    </div>


</div>