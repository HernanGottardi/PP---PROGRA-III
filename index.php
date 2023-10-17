
<?php

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch ($_GET['accion']){
            case 'leer':
                echo 'leer archivo';
                // Ejemplo de in
                include 'Usuario.php';
                //Usuario::LeerUsuarios();
                break;
            case 'buscar':
                echo 'buscar archivo';
                break;
            case '':
                echo "nada";
        }
        break;
    case 'POST':
        include_once("./CuentaAlta.php");
        break;
    default:
        echo 'Verbo no permitido';
        break;
}

?>