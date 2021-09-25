<?php

require_once('./Clases/DB.php');
require_once('./Clases/Usuario.php');
require_once('./Clases/Producto.php');
require_once('./Clases/Venta.php');

//1

$usuarios = Usuario::TraerUsuariosDeDB("ORDER BY Usuario.apellido");
Usuario::ListarUsuarios($usuarios);
echo "<br>";/*
//2

$prods = Producto::TraerProductosDeDB("WHERE tipo = 'liquido'");
Producto::ListarProductos($prods);
echo "<br>";/*

//3

$ventas = Venta::TraerVentasDeDB("WHERE cantidad BETWEEN 6 AND 10");
Venta::ListarVentas($ventas);
echo "<br>";/*

//4

$ventas = Venta::TraerVentasDeDB();
Venta::CantidadProductosVendidos($ventas);
echo "<br><br>";/*

//5

$ventas = Venta::TraerVentasDeDB("LIMIT 3");
echo "Venta 1: " . $ventas[0]->codigo_barra . "<br>";
echo "Venta 2: " . $ventas[1]->codigo_barra . "<br>";
echo "Venta 3: " . $ventas[2]->codigo_barra . "<br><br>";/*

//6

echo "<hr>";
$ventas = Venta::TraerVentasDeDB();
Venta::ListarVentas($ventas);
echo "<br>";
echo "<hr>";/*

//7

$ventas = Venta::TraerVentasDeDB();
Venta::MontoPorVenta($ventas);
echo "<br>";/*

//8

$ventas = Venta::TraerVentasDeDB("WHERE usuario.id_usuario = '4'");
Venta::CantidadTotalVendidoPorUsuario($ventas, 1003);
echo "<br>";/*

//9
$ciudad = "Quilmes";
$ventas = Venta::TraerVentasDeDB("WHERE localidad = '${ciudad}'");

foreach($ventas as $venta){

    $nombreCompleto = $venta->nombre_usuario . " " . $venta->apellido;
    $prodVenta = $venta->id_producto;
    $diaVenta = $venta->fecha_venta;
    echo "De todas las ventas realizadas en ${ciudad}, ${nombreCompleto} realizo la venta de el producto ${prodVenta} el dia ${diaVenta}<br>";
}/*

//10

echo "<br>";
$usuarios = Usuario::TraerUsuariosDeDB("WHERE 'nombre' LIKE '%u%' OR 'apellido' LIKE '%u%'"); //Por alguna razon esta no funciona
Usuario::ListarUsuarios($usuarios);
echo "<br>";/*

//11

echo "<br>";
$ventas = Venta::TraerVentasDeDB(); //Probe todas las formas posibles y ninguna funciono o tiraba mal la data, creeria que porque el tipo de dato no es DATE en sql
Venta::ListarVentasEntre($ventas, "06/01/2020", "01/01/2021");
echo "<br>";/*

//12

echo "<br>";
$usuarios = Usuario::TraerUsuariosDeDB();
Usuario::RegistradosAntesDe($usuarios, "01/01/2021");
echo "<br>";/*

//13
$prod = new Producto("Chocolate", "solido", 25.35, 4124124, 10);
$prod->AgregarProductoEnDB();
echo "<br>";/*

//14
$user = new Usuario("Federico", "Gutierrez", "123456", "federico1999g@gmail.com", "Avellaneda2");
$user->AgregarUsuarioEnDB();
echo "<br>";/*

//15
$prods = Producto::TraerProductosDeDB("WHERE tipo = 'solido'");
//Producto::ModificarProductos($prods, ["tipo" => "solido"], ["precio" => 66.60]);
echo "<br>";/*

//16
/*
$prods = Producto::TraerProductosDeDB("WHERE stock <= 20"); //Revisar
Producto::ModificarProductos($prods, ["stock" => 0]);
echo "<br>";/*

//17
Producto::EliminarProductoDeDB(1010);
echo "<br>";/*

//18
$usuarios = Usuario::TraerUsuariosDeDB("WHERE id_usuario NOT IN (select venta.id_usuario from `venta`)");

foreach($usuarios as $user){

    Usuario::EliminarUsuarioDeDB($user->email);
}
/*
?>