
<?php

include_once("./Cuenta.php");

try 
{
    if (isset($_POST['TipoCuenta']) && isset($_POST['NroCuenta']))
    {
        $tipoCuenta = (string)$_POST['TipoCuenta'];
        $numeroCuenta = (int)$_POST['NroCuenta'];

        $mensaje = Cuenta::buscarCuenta($tipoCuenta, $numeroCuenta);

        echo $mensaje;
    }
    else
    {
        echo "Los parametros llegaron mal o incompletos. </br>";
    }
} 
catch (InvalidArgumentException $th) 
{
    echo $th->getMessage();
}
 

?>