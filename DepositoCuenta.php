
<?php

include_once("./Cuenta.php");
include_once("./Deposito.php");

try 
{
    if (isset($_POST['TipoCuenta']) && isset($_POST['NroCuenta'])
    && isset($_POST['Moneda']) && isset($_POST['Monto']))
    {
        $tipoCuenta = (string)$_POST['TipoCuenta'];
        $numeroCuenta = (int)$_POST['NroCuenta'];
        $moneda = (string)$_POST['Moneda'];
        $monto = (float)$_POST['Monto'];
        $id = Deposito::obtenerSiguienteID();
        $fecha = date("d-m-y");

        $depositoAux = new Deposito($id, $tipoCuenta, $numeroCuenta, $moneda , $monto, $fecha);
        
        Deposito::actualizarArray($depositoAux);
        Deposito::guardarImagen($depositoAux);
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