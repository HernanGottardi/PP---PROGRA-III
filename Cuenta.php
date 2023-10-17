<?php
class Cuenta implements JsonSerializable
{
    private $nombre;
    private $apellido;
    private $email;
    private $tipoDoc;
    private $tipoCuenta;
    private $nroDoc;
    private $moneda;
    private $saldoInicial;
    private $nroCuenta;

    public function __construct($nombre, $apellido, $email, $tipoDoc, $tipoCuenta, $nroDoc, $moneda, $saldoInicial, $nroCuenta)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setEmail($email);
        $this->setTipoDoc($tipoDoc);
        $this->setTipoCuenta($tipoCuenta);
        $this->setNroDoc($nroDoc);
        $this->setMoneda($moneda);
        $this->setSaldoInicial($saldoInicial);
        $this->nroCuenta = $nroCuenta;
    }
    public function getNroCuenta() 
    {
        return $this->nroCuenta;
    }

    public function setNroCuenta($nroCuenta) 
    {
        if (!is_numeric($nroCuenta) || $nroCuenta < 0) 
        {
            throw new InvalidArgumentException("El Nro. de Doc no es válido. </br>");
        }

        $num_str = (string)$nroCuenta;
        $digitos = strlen($num_str);

        if ($digitos >= 6) 
        {
            $this->nroCuenta = (int)$nroCuenta;
        }
    }

    // ----------------------------------- NOMBRE
    public function getNombre() 
    {
        return $this->nombre;
    }

    public function setNombre($nombre) 
    {
        if (!is_string($nombre) || empty($nombre)) {
            //echo "El nombre debe ser una cadena no vacía. </br>" ;
            throw new InvalidArgumentException("El nombre debe ser una cadena no vacía. </br>");

        }
        $this->nombre = $nombre;
    }

    // ----------------------------------- APELLIDO
    public function getApellido() 
    {
        return $this->apellido;
    }

    public function setApellido($apellido) 
    {
        if (!is_string($apellido) || empty($apellido)) {
            //echo "El apellido debe ser una cadena no vacía. </br>";
            throw new InvalidArgumentException("El apellido debe ser una cadena no vacía. </br>");
        }
        $this->apellido = $apellido;
    }

    // ----------------------------------- EMAIL

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //echo "El email no es válido. </br>";
            throw new InvalidArgumentException("El email no es válido.  </br>");
        }
        $this->email = $email;
    }

    // ----------------------------------- TIPO DOCUMENTO

    public function getTipoDoc() 
    {
        return $this->tipoDoc;
    }


    public function setTipoDoc($tipoDoc) 
    {
        $tiposValidos = ['DNI', 'Pasaporte', 'Carnet de conducir'];
        if (!in_array($tipoDoc, $tiposValidos)) {
            //echo "El tipo de documento no es válido. </br>";
            throw new InvalidArgumentException("El tipo de documento no es válido. </br>");
        }
        $this->tipoDoc = $tipoDoc;
    }

    // ----------------------------------- TIPO CUENTA

    public function setTipoCuenta($tipoCuenta) 
    {
        // Aquí puedes realizar validaciones específicas según tus necesidades
        $tiposValidos = ['CA', 'CC'];
        if (!in_array($tipoCuenta, $tiposValidos)) {
            //echo "El tipo de cuenta no es válido. </br>";
            throw new InvalidArgumentException("El tipo de cuenta no es válido. </br>");
        }
        $this->tipoCuenta = $tipoCuenta;
    }

    public function getTipoCuenta() 
    {
        return $this->tipoCuenta;
    }

    // ----------------------------------- TIPO NUMERO DE DOCUMENTO

    public function setNroDoc($nroDoc) 
    {
        if (!is_numeric($nroDoc) || $nroDoc < 0) {
            //echo "El saldo inicial no es válido. </br>";
            throw new InvalidArgumentException("El Nro. de Doc no es válido. </br>");
        }
        $this->nroDoc = $nroDoc;
    }

    public function getNroDoc() 
    {
        return $this->nroDoc;
    }


    // ----------------------------------- MONEDA

    public function setMoneda($moneda) 
    {
        $monedasValidas = ['$', 'U$S'];
        if (!in_array($moneda, $monedasValidas)) {
            //echo "La moneda no es válida. </br>";
            throw new InvalidArgumentException("La moneda no es válida. </br>");
        }
        $this->moneda = $moneda;
    }

    public function getMoneda() 
    {
        return $this->moneda;
    }


    // ----------------------------------- SALDO INICIAL

    public function setSaldoInicial($saldoInicial) 
    {
        if (!is_numeric($saldoInicial) || $saldoInicial < 0) {
            //echo "El saldo inicial no es válido. </br>";
            throw new InvalidArgumentException("El saldo inicial no es válido. </br>");
        }
        $this->saldoInicial = $saldoInicial;
    }

    public function getSaldoInicial() 
    {
        return $this->saldoInicial;
    }



    // /////////////////////////////// LOGICA ///////////////////////////////////////////////////////////


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function escribirJSON($listaPizzas, $archivo = "./banco.json") 
    {
        // Intenta leer el archivo existente
        if (file_exists($archivo) && is_array($listaPizzas)) 
        {
            file_put_contents($archivo, json_encode($listaPizzas, JSON_PRETTY_PRINT));
        } 
    }

    public static function leerJSON($archivo = "./banco.json") 
    {
        $cuentas = array();

        // Intenta leer el archivo
        if (file_exists($archivo)) 
        {
            $cuentasData = json_decode(file_get_contents($archivo), true);
            
            if (is_array($cuentasData)) 
            {
                // Convierte los datos en instancias de Persona
                foreach ($cuentasData as $data) 
                {
                    $cuentas[] = new Cuenta($data['nombre'], $data['apellido'], $data['email'], 
                    $data['tipoDoc'], $data['tipoCuenta'], $data['nroDoc'], $data['moneda'], $data['saldoInicial'],$data['nroCuenta']);
                }
            }
        }

        return $cuentas;
    }

    public static function actualizarArray($cuenta)
    {
        $arrayCuentas = Cuenta::leerJSON();

        $mensaje = "";

        if (is_array($arrayCuentas)) 
        {
            // Si la cuenta NO existe, se agrega.
            if (!$cuenta->cuentaExiste($arrayCuentas)) 
            {
                array_push($arrayCuentas, $cuenta);
                $mensaje = "La cuenta fue agregada con éxito! </br>";
            }
            else
            {
                // Si la cuenta ya existe, se actualiza su saldo.
                foreach ($arrayCuentas as $c) 
                {
                    if ($c->__Equals($cuenta)) 
                    {
                        $c->setSaldoInicial($c->getSaldoInicial() + $cuenta->getSaldoInicial());
                        $mensaje = "El saldo fue modificado con éxito! </br>";
                    }
                }   
            }
            // Guarda el array completo en el archivo JSON
            Cuenta::escribirJSON($arrayCuentas);
            return $mensaje;
        }

    }

    public function __Equals($cuenta) : bool
    {
        if ($cuenta instanceof Cuenta)
        {
            if ($cuenta->getTipoCuenta() == $this->getTipoCuenta() && $cuenta->getNombre() == $this->getNombre()) 
            {
                return true;
            }
            else
            {
                return false;
            }
        } 
        return false;
    }

    public function cuentaExiste($arrayCuentas):bool
    {
        if(!empty($arrayCuentas))
        {
            foreach ($arrayCuentas as $cuenta) 
            {
                if ($this->__Equals($cuenta)) 
                {
                    return true;
                }
            }
        }
        return false;
    }

    public static function guardarImagen ($cuenta, $folder = "ImagenesDeCuentas/2023")
    {
        // tipo + sabor + mail(solo usuario hasta el @) y fecha de la venta en la carpeta.
        $nombre_archivo = $cuenta->getNroCuenta() . $cuenta->getTipoCuenta() . ".jpg";

        $destino = $folder . "/" . $nombre_archivo;

        // Mueve la imagen a la carpeta de destino usando move_uploaded_file
        if (move_uploaded_file($_FILES['Imagen']['tmp_name'], $destino)) 
        {
            echo "La imagen se ha guardado con éxito. </br>";
        } 
        else 
        {
            echo "Hubo un problema al guardar la imagen. </br>";
        }
    }


    public static function buscarCuenta ($tipoCuenta, $nroCuenta) 
    {
        $arrayCuentas = Cuenta::leerJSON();

        if (is_array($arrayCuentas)) 
        {
            if (count($arrayCuentas) > 0) 
            {
                foreach ($arrayCuentas as $c) 
                {
                    if ($c->getNroCuenta() == $nroCuenta && $c->getTipoCuenta() == $tipoCuenta)
                    {
                        return "Saldo: " . $c->moneda . $c->saldoInicial . "</br>";
                    }
                    else if ($c->getNroCuenta() == $nroCuenta) 
                    {
                        return "Tipo de cuenta incorrecto. </br>";
                    }
                }

                return "No existe la combinacion de Tipo y Numero de Cuenta. </br>";
            }
        }
        return "No hay cuentas cargadas! </br>";
    }
}

?>