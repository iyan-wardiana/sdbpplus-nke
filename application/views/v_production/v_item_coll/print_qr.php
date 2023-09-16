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
		//$COMPIL	= "$ICOLL_NUM~$PRJCODE";
		$COMPIL	= "$ICOLL_CODE";
	?>
	<input id="text" type="text" value="<?php echo $COMPIL; ?>" style="width:80%; display:none" /><br />
	<table width="200" border="1" align="center" style="font-family:'Courier New', Courier, monospace" rules="all">
		<tr>
			<td>Company</td>
			<td>:</td>
			<td>PT. FRANS</td>
		</tr>
		<tr>
			<td>Kode</td>
			<td>:</td>
			<td nowrap><?php echo $ICOLL_CODE; ?> </td>
		</tr>
		<tr>
			<td nowrap="nowrap">Customer</td>
			<td>:</td>
			<td nowrap><?php echo $CUST_DESC; ?></td>
		</tr>
		<tr>
			<td>Dibuat</td>
			<td>:</td>
			<td nowrap><?php echo date('d/m/y H:i:s', strtotime($ICOLL_CREATED)); ?> </td>
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