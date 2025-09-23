<?php
 header("Content-Type: application/json; charset=utf-8"); //modificar o php para o tipo JSON
 header("Access-Control-Allow-Origin: *");
 header("access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

 //configuracao do banco de dados
 $host="localhost";
 $usuario="root";
 $senha="";
 $db="servidor";
//ligar com o banco de dados
 $con=new mysqli($host,$usuario,$senha,$db);

 //verificar se contem erros
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
                //preparao caminho sql
            $stmt= $con ->prepare("SELECT * FROM usuarios WHERE NOME LIKE ?");
                //evita sql injection 
            $stmt ->bind_param("s",$pesquisa);
                //executa a query
            $stmt ->execute();
                //salva o retorno da pesquisa no banco de dados para variavel result
            $result = $stmt->get_result();
        }
        else{
                //busca uma lista de usuarios ordenadas pelo ID  chave primaria
            $result=$con->query("SELECT * FROM usuarios order by ID desc");
        }
        $retorno = [];

        while ($linha = $result->fetch_assoc()){
            $retorno[]= $linha;
        }
        echo json_encode($retorno);
        break;
    
    case "POST":
        //le todo o conteudo do body e transforma o em json
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