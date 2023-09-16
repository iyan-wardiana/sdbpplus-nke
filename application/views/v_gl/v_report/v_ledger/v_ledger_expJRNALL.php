<?php
/* 
 	* Author   		= Dian Hermanto
 	* Create Date  	= 1 Oktober 2018
 	* File Name  	= v_ledger_report.php
 	* Location   	= -
*/

setlocale(LC_ALL, 'id-ID', 'id_ID');
date_default_timezone_set("Asia/Jakarta");

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$LangID 	= $this->session->userdata['LangID'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
endforeach;


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GL_".date('YmdHis').".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>