<?php

include_once("./Cuenta.php");

try 
{
    if (isset($_POST['Nombre']) && isset($_POST['Apellido']) 
    && isset($_POST['Email']) && isset($_POST['TipoDocumento']) 
    && isset($_POST['TipoCuenta']) && isset($_POST['NumeroDocumento'])
    && isset($_POST['Moneda']) && isset($_POST['SaldoInicial']))
    {
        $nombre = (string)$_POST['Nombre'];
        $apellido = (string)$_POST['Apellido'];
        $email = (string)$_POST['Email'];
        $tipoDocumento = (string)$_POST['TipoDocumento'];
        $tipoCuenta = (string)$_POST['TipoCuenta'];
        $numeroDocumento = (int)$_POST['NumeroDocumento'];
        $moneda = (string)$_POST['Moneda'];
        $saldoInicial = (float)$_POST['SaldoInicial'];

        $nroCuenta = Cuenta::obtenerSiguienteID();

        $cuentaAux = new Cuenta(
            $nombre,
            $apellido,
            $email,
            $tipoDocumento,
            $tipoCuenta,
            $numeroDocumento,
            $moneda,
            $saldoInicial,
            $nroCuenta
        );

        $mensaje = Cuenta::actualizarArray($cuentaAux);

        if ($mensaje == "La cuenta fue agregada con éxito! </br>") 
        {
            Cuenta::guardarImagen($cuentaAux);
        }


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