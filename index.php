
<?php

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch ($_GET['accion']){
            case 'leer':
<<<<<<< HEAD
                break;
            case 'buscar':
=======
                echo 'leer archivo';
                // Ejemplo de in
                include 'Usuario.php';
                //Usuario::LeerUsuarios();
                break;
            case 'buscar':
                echo 'buscar archivo';
>>>>>>> 3524b0f8e18795ffba848d2b35fad10ac5f73129
                break;
            case '':
                echo "nada";
        }
        break;
    case 'POST':
<<<<<<< HEAD
        // include_once("./CuentaAlta.php");
        include_once("./ConsultarCuenta.php");
=======
        include_once("./CuentaAlta.php");
>>>>>>> 3524b0f8e18795ffba848d2b35fad10ac5f73129
        break;
    default:
        echo 'Verbo no permitido';
        break;
}

?>