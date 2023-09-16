<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<title>Cross-Browser QRCode generator for Javascript</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="qrcode.js"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/jquery.min.js'; ?>"></script>
<script language="javascript" src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/jsqrcode/qrcode.js'; ?>"></script>
</head>
<body>
<?php
$IR_NUM 	= $default['IR_NUM'];
$IR_CODE 	= $default['IR_CODE'];
$IR_DATE 	= $default['IR_DATE'];
$IR_DUEDATE = $default['IR_DUEDATE'];
$PRJCODE 	= $default['PRJCODE'];
$CUST_CODE 	= $default['SPLCODE'];		// CUST_CODE
$SO_NUM 	= $default['PO_NUM'];		// SO NUM
$SO_CODE 	= $default['PO_CODE'];		// SO CODE
$OFF_NUM 	= $default['PR_NUM'];		// OFFERING NUM
$OFF_CODE 	= $default['PR_CODE'];		// OFFERING NUM
$BOM_NUM 	= $default['IR_REFER'];		// BOM NUM

$SO_DATE	= '';
$sqlSOH	= "SELECT SO_DATE from tbl_so_header WHERE SO_NUM = '$SO_NUM'";
$resSOH	= $this->db->query($sqlSOH)->result();
foreach($resSOH as $row) :
	$SO_DATE	= $row->SO_DATE;
endforeach;

$CUST_DESC	= '';
$sqlCUST	= "SELECT CUST_DESC from tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
$resCUST	= $this->db->query($sqlCUST)->result();
foreach($resCUST as $row) :
	$CUST_DESC	= $row->CUST_DESC;
endforeach;

$JO_CODE	= 'NO JO';

//$COMPIL	= "$IR_CODE~$JO_CODE~$SO_CODE~$CUST_DESC";
$COMPIL	= "$IR_CODE~$SO_NUM";
?>
<input id="text" type="text" value="<?php echo $COMPIL; ?>" style="width:80%; display:none" /><br />
<table width="200" border="1" align="center" style="font-family:'Courier New', Courier, monospace">
  <tr>
    <td>Company</td>
    <td>:</td>
    <td>PT. FRANS</td>
  </tr>
  <tr>
    <td>No. SO</td>
    <td>:</td>
    <td nowrap><?php echo $SO_CODE; ?> </td>
  </tr>
  <tr>
    <td nowrap="nowrap">Nama Pelanggan</td>
    <td>:</td>
    <td nowrap><?php echo $CUST_DESC; ?></td>
  </tr>
  <tr style="text-align:center">
    <td colspan="3" style="text-align:center">
    	<div id="qrcode" style="width:100px; height:100px;"></div>
    </td>
  </tr>
</table>
<style>
#qrcode {
  display: table;
  margin: 0 auto;
}
</style>

<script type="text/javascript">
var qrcode = new QRCode(document.getElementById("qrcode"), {
	width : 100,
	height : 100
});


function makeCode () {		
	var elText = document.getElementById("text");
	
	if (!elText.value) {
		alert("Input a text");
		elText.focus();
		return;
	}
	
	qrcode.makeCode(elText.value);
}

makeCode();

$("#text").
	on("blur", function () {
		makeCode();
	}).
	on("keydown", function (e) {
		if (e.keyCode == 13) {
			makeCode();
		}
	});
</script>
</body>