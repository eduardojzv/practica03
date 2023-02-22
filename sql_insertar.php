<?php
    require_once('codes/conexion.inc');
    session_start();

    if ($_SESSION["autenticado"] != "SI") {
        header("Location:login.php");
        exit();
    }
    $catID=$_GET["cod"];
    $tabla=$_GET['form'];
    $tabla=="categories"?$title="Insertar categoria nueva":$title="Insertar producto, categoria ".$catID;
    $configuracion=[
        "categories"=>["CategoryName","Description"],
        "products"=>["ProductName","SupplierID","QuantityPerUnit","CategoryID","UnitPrice","UnitsInStock","UnitsOnOrder","ReorderLevel","Discontinued"]
    ];
    if ((isset($_POST["OC_insertar"])) && ($_POST["OC_insertar"] == "formita")) {
        $armar="";
        $data=$configuracion[$_POST["codForm"]];
        $categoriaID=$_POST["categoria"];
        for($i=0;$i<count($data);$i++){
            $inputs="txt".$data[$i];
            $dataInput=$_POST[$inputs];
            ($i+1)==count($data)? $separador="":$separador=",";
            if (is_numeric($dataInput)){
                $armar.=$dataInput.$separador;
            }else{
                $armar.="'".$dataInput."'".$separador;
            }
        }
        $auxSql = sprintf("insert into %s(%s) values(%s)",$_POST["codForm"],implode(",",$data),$armar);
        $Regis = mysqli_query($conex,$auxSql) or die(mysqli_error($conex));
        $_POST["codForm"]=="products"? $ruta="lstproductos.php?cod=".$categoriaID:$ruta=$_POST["codForm"].".php";
        header("Location:".$ruta);
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <?php
            include_once ('sections/head.inc');
        ?>
        <meta http-equiv="refresh" content="180;url=codes/salir.php">
        <title>Create Category</title>
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
                <h4 class="card-title"><?php echo($title);?></h4>
            </div>
            <div class="card-body">
                <form method="post" name="formita" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table class="table table-bordered" name="<?php echo($tabla);?>">
                        <?php 
                        foreach ($configuracion[$tabla] as $val){
                            if($val!="ProductID"){
                                $armar.= sprintf('
                                    <tr>
                                        <td><strong> %s </strong></td>
                                        <td><input type="text" name="txt%s" size="15" maxlength="15"></td>
                                    </tr>',$val,$val);
                            }
                        }
                        echo ($armar);
                        ?>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="Aceptar">
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="OC_insertar" value="formita">
                    <input type="hidden" name="codForm" value="<?php echo ($tabla); ?>">
                    <input type="hidden" name="categoria" value="<?php echo ($catID); ?>">
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
