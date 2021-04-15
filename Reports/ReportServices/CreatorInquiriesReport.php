<?php 
// Cargamos la librería dompdf que hemos instalado en la carpeta dompdf
require_once '../../Libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

header('Access-Control-Allow-Origin: http://localhost:3000');

$cityFilter = $_GET['city'];
$interestedName = $_GET['intName'];

// Introducimos HTML de prueba
function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
//TODO Cambiar el host dependiendo del ambiente donde corra!!!!!!!!!!!!!!!!!!!!
$html=file_get_contents_curl("http://localhost/PrograTres/Reports/FilesPDF/InquiriesReport.php?city=" . $cityFilter . "&intName=" . $interestedName); 
// Instanciamos un objeto de la clase DOMPDF.
$pdf = new DOMPDF();
 
// Definimos el tamaño y orientación del papel que queremos.
$pdf->set_paper("letter", "landscape");
//$pdf->set_paper(array(0,0,104,250));
 
// Cargamos el contenido HTML.
$pdf->load_html(utf8_decode($html));
 
// Renderizamos el documento PDF.
$pdf->render();

// Enviamos el fichero PDF al navegador.
$pdf->stream('Reporte de Consultas App.pdf');

?>