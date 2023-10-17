
<?php

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch ($_GET['accion']){
            case 'leer':
                break;
            case 'buscar':
                break;
            case '':
                echo "nada";
        }
        break;
    case 'POST':
        // include_once("./CuentaAlta.php");
        include_once("./ConsultarCuenta.php");
        break;
    default:
        echo 'Verbo no permitido';
        break;
}

?>