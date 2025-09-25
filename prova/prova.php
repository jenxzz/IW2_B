<?php
 header("Content-Type: application/json; charset=utf-8"); 

 $con=new mysqli('localhost','root','','cadastro');

 if($con ->connect_error){
    http_response_code(500);
    echo json_encode(["error" => "falha de conexao: " . $con->connect_error]);
    exit;
 }
 $method= $_SERVER['REQUEST_METHOD'];

 switch($method){
    case "GET":
        if(isset($_GET['pesquisa'])){
            $pesquisa = "%" . $_GET['pesquisa'] . "%";
            $stmt= $con ->prepare("SELECT * FROM usuarios WHERE NOME LIKE ?");
            $stmt ->bind_param("s",$pesquisa);
            $stmt ->execute();
            $result = $stmt->get_result();
        }
        else{
            $result=$con->query("SELECT * FROM usuarios order by ID desc");
        }
        $retorno = [];

        while ($linha = $result->fetch_assoc()){
            $retorno[]= $linha;
        }
        echo json_encode($retorno);
        break;
    
    case "POST":
        $data= json_decode(file_get_contents("php://input"),true);
        
        $stmt=$con->prepare("INSERT INTO usuarios (NOME,GMAIL,SENHA,ATIVO) VALUES(?,?,?,?)");

        $stmt->bind_param("sssi",$data['NOME'],$data['GMAIL'],$data['SENHA'],$data['ATIVO']);

        $stmt->execute();

        echo json_encode(["status" => "ok", "insert_id" =>$stmt->insert_id]);
        break;
    
    case "PUT":
        $data= json_decode(file_get_contents("php://input"),true);

        $stmt=$con->prepare("UPDATE usuarios SET NOME=?,GMAIL=?,SENHA=?,ATIVO=? WHERE ID=?");

        $stmt->bind_param("sssii",$data["NOME"],$data["GMAIL"],$data["SENHA"],$data["ATIVO"],$data["ID"]);
        
        $stmt->execute();

        echo json_encode(["status" => "ok"]);
        
        break;
    
    case "DELETE":
        $id=$_GET["id"];

        $stmt=$con->prepare("DELETE FROM usuarios WHERE ID=?");

        $stmt ->bind_param("i",$id);

        $stmt->execute();

        echo json_encode(["status" => "ok"]);
        break;
    }
    
    $con->close();
?>