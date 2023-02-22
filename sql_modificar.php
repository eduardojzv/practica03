<?php
    require_once('codes/conexion.inc');
    session_start();
    if ($_SESSION["autenticado"] != "SI") {
        header("Location:login.php");
        exit(); //fin del scrip
    }
    $tablas=[
        "accion01"=>["products","ProductName","UnitPrice","ProductID","CategoryID",$_GET['cod']],
    ];
    if(!isset($_POST["OC_Modi"])){
        $codigo = $_GET['cod'];
    }else{
        $codigo = $_POST['ocCodigo'];
    }
    $cont=0;
    $id_inputs=["nombre","valor","categoria"];
    $dato=$tablas[$_GET['accion']];
    if((isset($_POST["OC_Modi"])) && ($_POST["OC_Modi"] == "formita")) {
        $dato=$tablas["accion01"];
        $XX=$_POST["categoria"];
        // $auxSql = sprintf("update products set ProductName = '%s', UnitPrice = '%s',CategoryID='%s' where ProductID = %s";
        $auxSql = sprintf("update %s set %s = '%s', %s = '%s',%s = '%s' where %s = %s",
                                                                            $dato[0],
                                                                            $dato[1],
                                                                            $_POST["nombre"],
                                                                            $dato[2],//UnitPrice
                                                                            $_POST["valor"],
                                                                            $dato[4],//CategoryID
                                                                            $_POST["categoria"],
                                                                            $dato[3],
                                                                            $_POST["ocCodigo"]
                                                                        );
        $Regis = mysqli_query($conex,$auxSql) or die(mysqli_error($conex));
        $xx="Location:lstproductos.php?cod=".$_POST["categoria"];
        header($xx);
        exit;
    }
    if(!isset($_POST["OC_Modi"])){
        $codigo = $_GET['cod'];
    }else{
        $codigo = $_POST['ocCodigo'];
    }
    //"accion01"=>["products","ProductName","UnitPrice","ProductID","CategoryID",$_GET['cod']],
    $auxSql = sprintf("select %s,%s,%s from %s where %s = %s", $dato[1],$dato[2],$dato[4],$dato[0],$dato[3],$dato[5]);

    $regis = mysqli_query($conex,$auxSql) or die(mysqli_error($conex));
    $tupla = mysqli_fetch_assoc($regis);
?>
<!doctype html>
<html lang="en">
    <head>
        <?php
            include_once ('sections/head.inc');
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <meta http-equiv="refresh" content="180;url=codes/salir.php">
        <title>Update Category</title>
    </head>
    <body class="container-fluid">
    <header class="row">
        <?php
            include_once ('sections/header.inc');
        ?>
    </header>
    <main class="row contenido">
        <div class="card tarjeta">
            <div class="card-header">
                <h4 class="card-title">Modificar Categoría</h4>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" name="formita" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table class="table table-bordered">
                            <?php
                            foreach($tupla as $key => $val) {
                                $armar.=sprintf('
                                <tr>
                                <td><strong>%s</strong></td>
                                <td><input type="text" name="%s" size="15" maxlength="15" value="%s"></td>
                                </tr>',$key,$id_inputs[$cont],$val);
                                $cont++;
                              }
                            echo ($armar);
                            ?>
                            <td colspan="2">
                                <input type="submit" value="Aceptar">
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="OC_Modi" value="formita">
                    <input type="hidden" name="ocCodigo" value="<?php echo $codigo; ?>">
                </form>
            </div>
        </div>
    </main>
    <footer class="row pie">
        <?php
            include_once ('sections/foot.inc');
        ?>
    </footer>
</body>
</html>
<?php
    if(isset($regis)){
        mysqli_free_result($regis);
    }
?>
