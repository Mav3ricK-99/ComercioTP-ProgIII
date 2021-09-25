<?php

require_once("DB.php");
require_once("Usuario.php");
require_once("Producto.php");

class Venta{

    protected int $idVenta, $idProducto, $idUsuario, $cantidad, $fechaVenta;

    public function __construct(int $idProducto, int $idUsuario, int $cantidad, int $idVenta = 0){

        $this->idProducto = $idProducto;
        $this->idUsuario = $idUsuario;
        $this->cantidad = $cantidad;
        $this->idVenta = $idVenta;

        $this->fechaVenta = date("n/j/yyyy");
    }
    
    public function __get($prop) {
        return $this->$prop;
    }

    public static function TraerVentasDeDB($condicion = '')
    {

        $db = new DB('localhost', 'tpcomercio', 'root');
        $listaVentas = $db->selectObject('venta', '*', 'INNER JOIN (SELECT id_producto, codigo_barra, nombre AS nombre_producto, tipo, stock, precio, fecha_creacion, fecha_modificacion FROM producto) producto ON venta.id_producto = producto.id_producto INNER JOIN (SELECT id_usuario, nombre AS nombre_usuario, apellido, clave, email, localidad, fecha_registro FROM usuario) usuario ON venta.id_usuario = usuario.id_usuario ' . $condicion);

        return $listaVentas;
    }

    public static function EliminarDeDB(){

        $db = new DB('localhost', 'tpcomercio', 'root');
        $resultado = $db->delete('venta', " WHERE venta.id_venta = " . $this->idVenta);

        return $resultado;
    }

    public function GuardarVentaEnDB(){

        $db = new DB('localhost', 'tpcomercio', 'root');
        
        $valuesVenta = "'{$this->__get('idProducto')}','{$this->__get('idUsuario')}','{$this->__get('cantidad')}','{$this->__get('fechaVenta')}'";
        $resultado = $db->insertObject('venta', 'id_producto, id_usuario, cantidad, fecha_venta', $valuesVenta);
    }

    public static function ListarVentas($ventas){

        foreach($ventas as $venta){

            $cantidad = $venta->cantidad;
            $fechaVenta = $venta->fecha_venta;
            echo "Usuario venta: ";
            
            $user = new Usuario($venta->nombre_usuario, $venta->apellido, $venta->clave, $venta->email, $venta->localidad);
            
            $usuarios = [];
            array_push($usuarios, $user);
            Usuario::ListarUsuarios($usuarios);

            echo "Producto de la venta: ";

            $prod = new Producto($venta->nombre_producto, $venta->tipo, $venta->precio, $venta->codigo_barra, $venta->stock, $venta->id_producto, $venta->fecha_creacion, $venta->fecha_modificacion);
            $prods = [];
            array_push($prods, $prod);
            Producto::ListarProductos($prods);

            $comprados = $venta->cantidad;
            $fechaVenta = $venta->fecha_venta;
            echo "Articulos comprados ${comprados} | Fecha VENTA: ${fechaVenta}<br><br>";
        }
    }

    public static function MontoPorVenta($ventas){

        foreach($ventas as $venta){

            $montoTotal = ($venta->cantidad * $venta->precio);
            echo "El monto de esta venta es de: " . $montoTotal . "<br>";
        }
    }

    public static function CantidadTotalVendidoPorUsuario($ventas, $prod = null){

        $cantidadTotalVendido = 0;
        foreach($ventas as $venta){

            if($prod != null){

                $cantidadTotalVendido += $venta->cantidad;
            }else if($prod == $venta->id_producto){
                $cantidadTotalVendido += $venta->cantidad;
            }
        }
        echo "El usuario a vendido producto: " . $cantidadTotalVendido . " veces <br>";
    }

    public static function CantidadProductosVendidos($ventas){
    
        $cantidadTotal = 0;
        foreach($ventas as $venta){

            $cantidadTotal += $venta->cantidad;
            
        }

        echo "Cantidad total de prods vendidos: ${cantidadTotal}";

    }

    public static function ListarVentasEntre($ventas, $fechaDesde, $fechaHasta){

        $ventasEntre = array();
        foreach($ventas as $venta){

            if(strtotime($venta->fecha_venta) > strtotime($fechaDesde) && strtotime($venta->fecha_venta) < strtotime($fechaHasta)){

                array_push($ventasEntre, $venta);
            }
        }
        
        Venta::ListarVentas($ventasEntre);
    }

    public function ToJSON(){

        $obj = new stdClass();

        $obj->idVenta = $this->idVenta;
        $obj->idProducto = $this->idProducto;
        $obj->idUsuario = $this->idUsuario;
        $obj->cantidad = $this->cantidad;

        $obj->fechaVenta = $this->fechaVenta;

        return $obj;
    }


}


?>