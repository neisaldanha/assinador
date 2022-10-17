<?php

// Fazendo o Upload do arquivo a ser assinado. 

$arquivo = file_get_contents($_FILES['documento']['tmp_name']);

$target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["documento"]["name"]);

$uploadOk = 1;
$pdfFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Checa se o arquivo é valido
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["documento"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "
          <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"O Arquivo não é valido.\");
      </script>
    ";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    unlink($target_file);
    $uploadOk = 1;
}
/*
// Checa o tamanho
if ($_FILES["documento"]["size"] > 5000000) {
    echo "
          <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"O Arquivo é maio que .\");
      </script>
    ";
    $uploadOk = 0;
}
*/
// Verifica se o formato é PDF
if (
    $pdfFileType != "pdf") {
    echo "
      <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"Ops...! Só aceita PDF!.\");
      </script>
    ";
    $uploadOk = 0;
}

// Checa se foi feito o Upload
if ($uploadOk == 0) {
    echo "Ops...! Seu arquivo não foi salvo.";
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["documento"]["tmp_name"], $target_file)) {
       echo "
      <script type=\"text/javascript\">
        alert(\"O Arquivo" . htmlspecialchars(basename($_FILES["documento"]["name"])) . " Foi Salvo.\");
      </script>
    ";
    } else {
        echo "
      <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"Ops...! Algo de errado não está certo, arquivo não foi Salvo.\");
      </script>
    ";
    }
}

// Gerando QrCode no arquivo PDF

// Include da biblioteca phpqrcode para gerar o QRCode
include 'phpqrcode/qrlib.php';

$text = "facisb.edu.br";
$qrcode = "qrcode.png";

// $ecc stores error correction capability('L')
$ecc = 'L';
$pixel_Size = 1;
$frame_size = 1;

// Generates QR Code and Stores it in directory given
QRcode::png($text, $qrcode, $ecc, $pixel_Size, $frame_size);

// Gerando o PDF e assinando

//use setasign\Fpdi\Fpdi;
use setasign\Fpdi\Tcpdf\Fpdi;
//require_once('fpdf/fpdf.php');
require_once('vendor/autoload.php');

// Iniciando o  FPDI
$pdf = new Fpdi('P', 'mm', array(210, 297));

$url = 'http://facisb.edu.br'; // URL de validação do documento
$file = $pdf->setSourceFile($target_file);

// Laço para calcular e assinar em todas as paginas.
for ($i = 1; $i <= $file; $i++) {
    // add a page
    $pdf->AddPage();
    // set the source file
    $pdf->setSourceFile($target_file);
    // Importando as pagina(as) do documento
    $tplIdx = $pdf->importPage($i);
    // use the imported page and place it at position 10,10 with a width of 100 mm
    $pdf->useTemplate($tplIdx);
    // agora escreva algum texto acima da página importada
    $pdf->SetFont('', '', 8); // Fonte, Efeito e Tamanho
    $pdf->SetTextColor(169, 169, 169); // Cor da Fonte
    $pdf->SetXY(50, 290); // Posição do texto, nos eixos X e Y 
    $pdf->SetAutoPageBreak(100); // Código para não deixar quebrar a pagina 
    $pdf->Write(0, 'Documento assinado Digitalmente -  '); // Texto a ser escrito no rodapé
    $pdf->writeHTML('<a class="btm btn-success" href="' . $url . '" target="' . "_blank" . '">Clique aqui para Validar </a>'); // Escrevendo Texto a ser escrito no rodapé
    $pdf->SetXY(5, 286); // Posição do texto, nos eixos X e Y 
    $pdf->writeHTML('<a class="btm btn-success" href="' . $url . '" target="' . "_blank" . '"><img src="' . $qrcode . '"></a>');
  
}
$dirArq = basename($_FILES["documento"]["name"]);
ob_end_clean(); // Limpa buffer
$pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'pdf2/assinados/Assinado-'. $dirArq, 'F');
//$docAssi = $_SERVER['DOCUMENT_ROOT'] . 'pdf2/assinados/Assinado-'. $dirArq;
echo "
      <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php'>
      <script type=\"text/javascript\">
        alert(\"Arquivo Assinado e salvo!.\");
      </script>
    ";