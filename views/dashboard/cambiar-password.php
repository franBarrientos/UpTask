<?php include_once __DIR__."/header-dashboard.php";?>


<div class="contenedor-sm">
    <?php include_once __DIR__."/../templates/alertas.php";?>
    <a href="/perfil" class="enlace">Volver a Perfil</a>

    <form action="/cambiar-password" method="POST" class="formulario">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password" name="password_actual" value="" placeholder="Tu Password Actual">
        </div>
        <div class="campo">
            <label for="email">Password Nuevo</label>
            <input type="password" name="password_nuevo" value="" placeholder="Tu Password Nuevo">
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__."/footer-dashboard.php";?>