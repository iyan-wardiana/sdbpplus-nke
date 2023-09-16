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
$JO_NUM     = $default['JO_NUM'];
$JO_CODE    = $default['JO_CODE'];
$PRJCODE    = $default['PRJCODE'];
$JO_DATE    = $default['JO_DATE'];
$JO_DATE    = date('m/d/Y', strtotime($JO_DATE));
$JO_PRODD     = $default['JO_PRODD'];
$JO_PRODD   = date('m/d/Y', strtotime($JO_PRODD));
$SO_NUM     = $default['SO_NUM'];
$SO_CODE    = $default['SO_CODE'];
$CUST_CODE    = $default['CUST_CODE'];
$CUST_DESC    = $default['CUST_DESC'];
$CUST_ADDRESS = $default['CUST_ADDRESS'];
$JO_DESC    = $default['JO_DESC'];
$JO_VOLM    = $default['JO_VOLM'];
$JO_AMOUNT    = $default['JO_AMOUNT'];
$JO_NOTES     = $default['JO_NOTES'];
$JO_NOTES2    = $default['JO_NOTES2'];
$JO_STAT    = $default['JO_STAT'];

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

//$COMPIL	= "$IR_CODE~$JO_CODE~$SO_CODE~$CUST_DESC";
//$COMPIL	= "$JO_NUM~$SO_NUM";
$COMPIL   = substr($JO_NUM, -12);
?>
<input id="text" type="text" value="<?php echo $COMPIL; ?>" style="width:80%; display:none" /><br />
<table width="200" border="1" align="center" style="font-family:'Courier New', Courier, monospace" rules="all">
  <tr>
    <td>Company</td>
    <td>:</td>
    <td>PT. FRANS</td>
  </tr>
  <tr>
    <td>No. JO</td>
    <td>:</td>
    <td nowrap><?php echo $JO_CODE; ?> </td>
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