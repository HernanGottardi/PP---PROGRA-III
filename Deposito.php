<?php

include_once("./Cuenta.php");

class Deposito implements JsonSerializable 
{
    private $id;
    private $tipoCuenta;
    private $nroCuenta;
    private $moneda;
    private $monto;
    private $fecha;

    public function __construct($id, $tipoCuenta, $nroCuenta, $moneda, $monto, $fecha) {
        $this->setId($id);
        $this->setTipoCuenta($tipoCuenta);
        $this->setNroCuenta($nroCuenta);
        $this->setMoneda($moneda);
        $this->setMonto($monto);
        $this->setFecha($fecha);
    }

    public function setId($id) 
    {

        if (is_numeric($id)) 
        {
            $this->id = $id;
        } 
        else 
        {
            throw new InvalidArgumentException("El ID debe ser un número.");
        }
    }
    public function getId() {
        return $this->id;
    }

    public function setTipoCuenta($tipoCuenta) 
    {
        if (!empty($tipoCuenta) && is_string($tipoCuenta)) 
        {
            $this->tipoCuenta = $tipoCuenta;
        } 
        else 
        {
            throw new InvalidArgumentException("El tipo de cuenta no puede estar vacío y debe ser una cadena de texto.");
        }
    }
    public function getTipoCuenta() 
    {
        return $this->tipoCuenta;
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

    public function setMoneda($moneda) 
    {
        if (!empty($moneda) && is_string($moneda)) 
        {
            $this->moneda = $moneda;
        } 
        else 
        {
            throw new InvalidArgumentException("La moneda no puede estar vacía y debe ser una cadena de texto.");
        }
    }
    public function getMoneda() 
    {
        return $this->moneda;
    }

    public function setMonto($monto) 
    {
        if (is_numeric($monto) && $monto >= 0) 
        {
            $this->monto = $monto;
        } 
        else 
        {
            throw new InvalidArgumentException("El monto debe ser un número y no puede ser negativo.");
        }
    }
    public function getMonto() 
    {
        return $this->monto;
    }

    public function setFecha($fecha) 
    {
        $this->fecha = $fecha;
    }
    public function getFecha() 
    {
        return $this->fecha;
    }

     // ////////////////////////////////////////////////////////////////////

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function obtenerSiguienteID() 
    {
        $data = file_get_contents('./depositos.json');
        $registros = json_decode($data, true);

        if (empty($registros)) {
            // Si no hay registros, inicia desde 1
            return 1;
        }

        // Encuentra el último ID y agrégale 1
        $ultimoRegistro = end($registros);
        $nuevoID = $ultimoRegistro['id'] + 1;

        return $nuevoID;
    }

    public static function escribirJSON($listaDepositos, $archivo = "./depositos.json") 
    {
        // Intenta leer el archivo existente
        if (file_exists($archivo) && is_array($listaDepositos)) 
        {
            file_put_contents($archivo, json_encode($listaDepositos, JSON_PRETTY_PRINT));
        } 
    }

    public static function leerJSON($archivo = "./depositos.json") 
    {
        $arrayDepositos = array();

        // Intenta leer el archivo
        if (file_exists($archivo)) 
        {
            $depositosData = json_decode(file_get_contents($archivo), true);
            
            if (is_array($depositosData)) 
            {
                // Convierte los datos en instancias de Persona
                foreach ($depositosData as $data) 
                {
                    $arrayDepositos[] = new Deposito($data['id'], $data['tipoCuenta'], 
                    $data['nroCuenta'], $data['moneda'], $data['monto'], $data['fecha']);
                }
            }
        }

        return $arrayDepositos;
    }

    public static function actualizarArray($deposito)
    {
        $arrayDepositos = Deposito::leerJSON();

        $arrayCuentas = Cuenta::leerJSON();

        if (is_array($arrayDepositos) && is_array($arrayCuentas)) 
        {
            // Si la cuenta existe en banco.json, se agrega.
            $cuentaAux = Cuenta::leerCuenta($deposito->getTipoCuenta(), $deposito->getNroCuenta(), $arrayCuentas);

            if ( $cuentaAux != null) 
            {
                var_dump($cuentaAux);
                // Actualizo el saldo de la cuenta.
                $cuentaAux->setSaldoInicial($cuentaAux->getSaldoInicial() + $deposito->getMonto());
                // Agrego el deposito.
                array_push($arrayDepositos, $deposito);
                // informo
                echo "El deposito se realizo con exito! </br>";
            }
            else 
            {
                // informo error.
                echo "No existe la cuenta donde se desea efectuar el deposito. </br>";
            }

            // Guarda los cambios realizados.
            Deposito::escribirJSON($arrayDepositos);
            Cuenta::escribirJSON($arrayCuentas);
        }
    }

    public static function guardarImagen ($deposito, $folder = "ImagenesDeDepositos2023")
    {
        // tipo + sabor + mail(solo usuario hasta el @) y fecha de la venta en la carpeta.
        $nombre_archivo = $deposito->getTipoCuenta() . "-" . $deposito->getNroCuenta() . "-" . $deposito->getId() .".jpg";

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
}

?>