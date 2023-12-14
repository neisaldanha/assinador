<?php

use phpDocumentor\Reflection\Types\Null_;

error_reporting(0);
ini_set("display_errors", 0);
// Transforma os resultados, vindo do banco, em o formato JSON 
//header("Content-Type: application/json");
/*
// Conexao com o banco de dados MySql
$dbhost = 'facisb01.l70cnn1109.mysql.dbaas.com.br';
$dbuser = 'facisb01';
$dbpass = 'med678132@i';
$db     = 'facisb01';

$con    = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
*/

$docAssinado = $_GET['doc'];
$docnome = $_GET['nomedoc'];
$upload = json_decode($docAssinado);
$uploadnome = json_decode($docnome);
$del = $_GET['del'];
if ($del) {
    if (unlink('assinados/' . $del)) {
        echo "
      <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"Arquivo foi excluido da pasta com sucesso!.\");
      </script>
    ";
    } else {
        echo "
      <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"Opss...! Não foi possível excluir o documento!\");
      </script>
    ";
    }
} else {
}
