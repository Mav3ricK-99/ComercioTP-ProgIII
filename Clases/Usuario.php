<?php

require_once('DB.php');

class Usuario{

    public string $apellido, $nombre, $clave, $email, $localidad, $fechaRegistro, $idUsuario;

    public function __construct()
    {
        $params = func_get_args();
        $num = func_num_args();

        if (method_exists($this,'__construct'.$num)) {
            call_user_func_array(array($this,'__construct'.$num),$params);
        }

    }
    
    public function __construct5(string $nombre, string $apellido, string $clave, string $email, string $localidad,string $idUsuario = "0"){
        
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->clave = $clave;
        $this->email = $email;
        $this->idUsuario = $idUsuario;
        $this->localidad = $localidad;

        $this->fechaRegistro = date("n/j/Y");
    }

    public function AgregarUsuarioEnDB()
    {

        $db = new DB('localhost', 'tpcomercio', 'root');

        $valuesUsuario = "'{$this->__get('nombre')}','{$this->__get('apellido')}','{$this->__get('clave')}','{$this->__get('email')}','{$this->__get('localidad')}','{$this->__get('fechaRegistro')}'";
        $resultado = $db->insertObject("usuario", "nombre, apellido, clave, email, localidad, fecha_registro", $valuesUsuario);
        
        return $resultado;
    }


    public static function TraerUsuariosDeDB($condicion = '')
    {

        $db = new DB('localhost', 'tpcomercio', 'root');
        $listaDeUsuarios = $db->selectAllObjects('usuario', $condicion);

        return $listaDeUsuarios;
    }

    public static function EliminarUsuarioDeDB($email){

        $db = new DB('localhost', 'tpcomercio', 'root');
        $resultado = $db->delete('usuario', "WHERE email = '${email}'");

        return $resultado;
    }

    public function ModificarUsuarioEnDB(Usuario $usuario)
    {
        $db = new DB('localhost', 'tpcomercio', 'root');

        $set = "usuario.nombre = '{$usuario->__get('nombre')}', usuario.apellido = '{$usuario->__get('apellido')}', usuario.email = '{$usuario->__get('email')}', usuario.localidad = '{$usuario->__get('localidad')}'";
        $resultado = $db->updateObject('usuario', $set, "WHERE email = {$usuario->__get('email')}");

        return $resultado;
    }

    public function __get($prop) {
        return $this->$prop;
    }
    
    public function ToJSON(){

        $obj = new stdClass();

        $obj->nombre = $this->nombre;
        $obj->apellido = $this->apellido;
        $obj->clave = $this->clave;
        $obj->email = $this->email;

        return $obj;
    }

    public static function ListarUsuarios($usuarios){

        foreach($usuarios as $user){

            $nombreCompleto = $user->apellido . " " . $user->nombre;
            $email = $user->email;
            $localidad = $user->localidad;
            $fechaRegistro = !isset($user->fecha_registro) ? $user->fechaRegistro : $user->fecha_registro;
            echo "Nombre completo: ${nombreCompleto} - | Correo: ${email} | Localidad: ${localidad} | Fecha Registro ${fechaRegistro}<br>";
        }
    }

    public static function RegistradosAntesDe($usuarios, $fechaHasta){

        $usuariosHasta = array();
        foreach($usuarios as $usuario){

            if(strtotime($usuario->fecha_registro) < strtotime($fechaHasta)){

                array_push($usuariosHasta, $usuario);
            }
        }
        
        Usuario::ListarUsuarios($usuariosHasta);
    }



}