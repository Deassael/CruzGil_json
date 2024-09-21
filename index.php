<?php
require("conn.php");

$arreglo = array(
    "sucess"=>false,
    "status"=>400,
    "data"=>"",
    "nessage"=>"";
    "cant" => 0
);

if($_SERVER["REQUEST_METHOD"] == "GET"){
    // El metodo es get
    if(isset($_GET["type"]) && $_GET["type"] != ""){
        // Si se envio el parametro type
        $conexion = new conexion;
        $con = $conexion->conectar();

        $datos = $conn->query('SELECT * FROM empleado');
        $resultados = $datos->fetchAll();

        switch($_GET["type"]){
            case "json":
                result_json($resultados);
                break;
            case "xml":
                result_xml($resultados);
                break;
            default:
                echo("Por favor, defina el tipo de resultado");
                break;
        }
echo "";

    }else{
        // No hay valores para el parametro type
        $arreglo = array(
            "sucess"=>false,
            "status"=>array("status_code"=>412,"status_text"=> "Precondition Failed"),
            "data"=>"",
            "message"=>"Se espara el parametro 'type' con el tipo de resultado",   
            "cant"=>0
        );
    }
}else{
    //No se acepta el metodo
    $arreglo = array(
        "sucess"=>false,
        "status"=>array("status_code"=>405,"status_text"=> "Method Nos Allowed"),
        "data"=>"",
        "message"=>"NO SE ACEPTA EL METODO",    
        "cant" => 0
    );
}

function result_json($resultado){
    $arreglo = array(
        "sucess"=>true,
        "status"=>array("status_code"=>200,"status_text"=> "OK"),
        "data"=>$resultado,
        "message"=>"",    
        "cant" => sizeof($resultado) 
    );

    header("HTTP/1.1".$arreglo["status"]["status_code"]." ".$arreglo["status"]["status_text"]);
    header("Content-Type: Application/json");
    echo(json_encode($arreglo));
}

function result_xml($resultado){
    $xml = new SimpleXMLElement("<empleados />");
    foreach($resultado as $i => $v){
        $subnodo = $xml->addChild("empleado");
        $invertir = array_flip($v);
        array_walk_recursive($invertir, array($subnodo, 'addChild'));

    }
    header("Content-type: text/xml");
    echo($xml->asXML());
}

?>
