<?php
require_once "vendor/autoload.php";
require_once "vendor/econea/nusoap/src/nusoap.php";
require_once "conexion.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$namespace = "urn:miserviciowsdl";
$server = new nusoap_server();
$server->configureWSDL("MiServicioWeb", $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir tipos complejos
$server->wsdl->addComplexType(
    'Producto',
    'complexType',
    'struct',
    'all',
    '',
    [
        'id' => ['name' => 'id', 'type' => 'xsd:int'],
        'nombre' => ['name' => 'nombre', 'type' => 'xsd:string'],
        'precio' => ['name' => 'precio', 'type' => 'xsd:decimal'],
        'stock' => ['name' => 'stock', 'type' => 'xsd:int']
    ]
);

$server->wsdl->addComplexType(
    'ListaProductos',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    [],
    [
        ['ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:Producto[]']
    ],
    'tns:Producto'
);

// Registrar métodos
$server->register(
    "Servidor.obtenerProducto",
    ['token' => 'xsd:string', 'id' => 'xsd:int'],
    ['return' => 'tns:Producto'],
    $namespace, false, 'rpc', 'encoded', "Obtiene un producto por ID"
);

$server->register(
    "Servidor.agregarProducto",
    ['token' => 'xsd:string', 'nombre' => 'xsd:string', 'precio' => 'xsd:decimal', 'stock' => 'xsd:int'],
    ['return' => 'xsd:boolean'],
    $namespace, false, 'rpc', 'encoded', "Agrega un nuevo producto"
);

$server->register(
    "Servidor.actualizarProducto",
    ['token' => 'xsd:string', 'id' => 'xsd:int', 'nombre' => 'xsd:string', 'precio' => 'xsd:decimal', 'stock' => 'xsd:int'],
    ['return' => 'xsd:boolean'],
    $namespace, false, 'rpc', 'encoded', "Actualiza un producto existente"
);

$server->register(
    "Servidor.eliminarProducto",
    ['token' => 'xsd:string', 'id' => 'xsd:int'],
    ['return' => 'xsd:boolean'],
    $namespace, false, 'rpc', 'encoded', "Elimina un producto"
);

$server->service(file_get_contents("php://input"));
?>