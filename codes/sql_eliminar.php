<?php
        require_once('conexion.inc');
        //tipo
        $tablas=[
            "accion01"=>["products","ProductID",$_GET['cod']],
            "accion02"=>["categories","CategoryID",$_GET['cod']]
        ];

        $_GET['accion']=="accion01"? $ruta="lstproductos.php?cod=".$_GET['catID']:$ruta="categories.php";
        $auxSql = sprintf('delete from %s where %s = %s',
                                                        $tablas[$_GET['accion']][0],
                                                        $tablas[$_GET['accion']][1],
                                                        $tablas[$_GET['accion']][2]);
        $Regis = mysqli_query($conex, $auxSql ) or die(mysqli_error($conex));
        header("Location: ../".$ruta);
?>
