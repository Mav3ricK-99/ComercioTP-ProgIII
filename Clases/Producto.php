<?php

require_once('DB.php');

class Producto{

    protected $idProducto, $codigoBarra, $stock;
    protected string $nombre, $tipo, $fechaCreacion, $fechaModificacion;
    protected float $precio;

    public function __construct()
    {
        $params = func_get_args();
        $num = func_num_args();

        if (method_exists($this,'__construct'.$num)) {
            call_user_func_array(array($this,'__construct'.$num),$params);
        }

        if($num == 5){
            call_user_func_array(array($this,'__construct8'),$params);
        }

    }

    public function __construct8(string $nombre, string $tipo, float $precio, int $codigoBarra, int $stock, int $idProducto = 0, $fecha_creacion = 0, $fecha_modificacion = 0){

        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->codigoBarra = $codigoBarra;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->idProducto = $idProducto;
        $this->fechaModificacion = $fecha_modificacion;

        if($fecha_creacion == 0){

            $this->fechaCreacion = date("n/j/Y");
        }else{
            $this->fechaCreacion = $fecha_creacion;
        }
        

    }

    public function __get($prop) {
        if($prop == "codigoBarra" || $prop == "codigo_barra"){
           return (!isset($this->codigo_barra) || $this->codigo_barra == 0) ? $this->codigoBarra : $this->codigo_barra;
        }
        return $this->$prop;
    }

    public function __set($prop, $value) {
        $this->$prop = $value;
    }
    
    public function AgregarProductoEnDB()
    {

        $db = new DB('localhost', 'tpcomercio', 'root');

        $valuesProducto = "'{$this->__get('nombre')}','{$this->__get('tipo')}','{$this->__get('codigoBarra')}','{$this->__get('stock')}', '{$this->__get('precio')}', '{$this->__get('fechaCreacion')}'";
        $columnasProducto = "nombre, tipo, codigo_barra, stock, precio, fecha_creacion";

        if($this->idProducto != 0){
            $columnasProducto .= ",id_producto";
            $valuesProducto .= ",'{$this->__get('idProducto')}'";
        }
        $resultado = $db->insertObject("producto", $columnasProducto, $valuesProducto);
        
        return $resultado;
    }

    
    public static function TraerProductosDeDB($condicion = '')
    {

        $db = new DB('localhost', 'tpcomercio', 'root');
        $listadeProductos = $db->selectAllObjects('producto', $condicion);

        return $listadeProductos;
    }

    public static function EliminarProductoDeDB($idProd){

        $db = new DB('localhost', 'tpcomercio', 'root');
        $resultado = $db->delete('producto', "WHERE id_producto = '${idProd}'");

        return $resultado;
    }

    public static function ModificarProductoEnDB(Producto $prod)
    {
        $db = new DB('localhost', 'tpcomercio', 'root');

        $fechaHoy = date("n/j/Y");
        $set = "producto.nombre = '{$prod->__get('nombre')}', producto.tipo = '{$prod->__get('tipo')}', producto.precio = '{$prod->__get('precio')}', producto.codigo_barra = '{$prod->__get('codigoBarra')}', producto.stock = '{$prod->__get('stock')}', producto.fecha_modificacion = '${fechaHoy}'";
        $resultado = $db->updateObject('producto', $set, "WHERE codigo_barra = '{$prod->__get('codigoBarra')}'");

        return $resultado;
    }

    public static function ListarProductos($prods){

        foreach($prods as $prod){

            $nombreProd = $prod->nombre;
            $tipoProd = $prod->tipo;
            $codProd = !isset($prod->codigo_barra) ? $prod->codigoBarra : $prod->codigo_barra;
            $stock = $prod->stock;
            $fechaCreacion =  !isset($prod->fecha_creacion) ? $prod->fechaCreacion : $prod->fecha_creacion;
            $fechaModificacion = !isset($prod->fecha_modificacion) ? $prod->fechaModificacion : $prod->fecha_modificacion;

            echo "Nombre: ${nombreProd} - | Tipo: ${tipoProd} | CodigoBarra: ${codProd} | Stock: ${stock} | Fecha Creacion: ${fechaCreacion} | Fecha modificacion: ${fechaModificacion}<br>";
        }
    }

    public static function ModificarProductos($prods, $attr, $valor = null){

        foreach($prods as $prod){
            
            if(isset($valor)){

                if($prod->__get(key($attr)) == $attr[array_keys($attr)[0]]){
                
                    $prod->__set(key($valor), $valor[array_keys($valor)[0]]);
                    Producto::ModificarProductoEnDB($prod);
                }
            }else{

                $prod->__set(key($attr), $attr[key($attr)]);
                Producto::ModificarProductoEnDB($prod);
            }
            
        }
    }

    public function ToJSON(){

        $obj = new stdClass();

        $obj->nombre = $this->nombre;
        $obj->tipo = $this->tipo;
        $obj->precio = $this->precio;
        $obj->stock = $this->stock;
        $obj->codigoBarra = $this->codigoBarra;

        $obj->idProducto = $this->idProducto;
        $obj->fechaCreacion = $this->fechaCreacion;

        return $obj;
    }

}

?>