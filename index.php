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


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/logo_teste.png">
    <!--<link rel="stylesheet" href="css/style.css">-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.3.0/introjs.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/2.3.0/intro.min.js"></script>-->
    <script src="js/intro.min.js"></script>
    <link rel="stylesheet" href="css/home.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <title class="label-menu">Assinador</title>
    <script>
        $(function() {
            $('#iniciar-tour').click(function() {
                introJs().start();
            })
            $('.select2').select2({
                closeOnSelect: false
            });
        })
    </script>

</head>

<body style="background-color:#F5F5F5">

    <br>
    <div class="container">
        <div style="background-color: #7CCD7C;" class="col-md-12  mb-4">
            <strong>
                <h3 align="center">Assinador</h3>
            </strong>
        </div>
        <button class="btn btn-success  mb-4" id="iniciar-tour">Como usar</button>
        <section class="">
            <div class="row">
                <div class="mb-4" data-step="1" data-intro="Aqui você envia documentos para ser assinados!">
                    <div class="card">
                        <strong>
                            <h3 align="center">Assinar documento</h3>
                        </strong>
                        <div class="card-body">
                            <div class="form-control">
                                <form name="form" method="POST" action="gerapdf.php" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-5 mb-2">
                                            <label> Selecione um arquivo PDF: </label>
                                            <input type="file" name="documento" multiple>
                                        </div>
                                        <div class="col-5 m-2">
                                            <label></label>
                                            <button class="btn btn-success" type="submit" name="envia"><i class="fa-solid fa-file-circle-check"></i> Assinar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12"></div>
            <div style="background-color: #7CCD7C;" class="col-md-12  mb-4">
                <strong>
                    <h3 align="center">Documentos Assinados</h3>
                </strong>
            </div>

            <table id="example" class="display compact" style="width:100%">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Arquivo</th>
                        <th>____</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $path = "assinados/"; // Pasta onde fica os arquivos
                    $diretorio = dir($path); // Reconhecendo pasta
                    while ($arquivo = $diretorio->read()) {
                        // Retirnado arquivos de lixo para mostrar somente os arquivos reais
                        if ($arquivo == '..' or $arquivo == '.') {
                            // Se encontrar Lixo não faz nada
                        } else {
                    ?>
                            <tr>
                                <td><?php echo $arquivo; ?></td>
                                <td><a target="_blank" href="<?php echo $path . $arquivo ?>"><img src="img/pdf_1.png" alt="arquivo" height="50" width="60"></a></td>
                                <td><a id="delete" href="index.php?del=<?php echo $arquivo; ?>" title="Excluir <?php echo $arquivo; ?>" type="button" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Excluir</a></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <div class="container">
            <footer class="py-3 my-4">
                <ul class="nav justify-content-center border-bottom pb-3 mb-3"></ul>
                <!--
                <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                    <li class="nav-item"><a href="#" target="_blank" class="btn btn-warning nav-link px-2 text-muted pt-20">Teste</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted"></a></li>
                    <li class="nav-item"><a href="#" target="_blank" class="btn btn-success nav-link px-2 text-muted">Teste 2</a></li>
                </ul>
    -->
                <p class="text-center text-muted">© 2022 - <?php echo date('Y') ?> - Teste Assinador de documentos</p>
            </footer>
        </div>
    </div>


    <!-- Bootstrap core JavaScript
        ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>

</html>