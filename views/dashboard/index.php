<?php include_once __DIR__."/header-dashboard.php";?>

<?php  if($proyectos == null){?>
        <p class="no-proyectos">
            No Hay Proyectos Aun
            <a href="/crear-proyecto">Crea Uno</a>

        </p>
<?php }else{;?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto){;?>

                <li class="proyecto">
                    <a href="/proyecto?id=<?php echo $proyecto->url;?>">
                        <?php echo $proyecto->proyecto;?>
                    </a>
                </li>

            <?php };?>
        </ul>

<?php };?>    
<?php include_once __DIR__."/footer-dashboard.php";?>