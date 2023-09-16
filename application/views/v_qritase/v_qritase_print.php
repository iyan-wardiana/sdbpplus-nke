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
	<?php
		//$COMPIL = "$PRJCODE - $QRIT_NOPOL - $QRIT_DRIVER - $QRIT_DEST - $QRIT_PJG x $QRIT_LBR x $QRIT_TNG : ".number_format($QRIT_VOL)." $QRIT_UNIT $QRIT_MATERIAL";
		$COMPIL = "$QRIT_CODE - $QRIT_NOPOL - $QRIT_DRIVER - $QRIT_DEST -  ".number_format($QRIT_VOL)." $QRIT_UNIT $QRIT_MATERIAL";
	?>
	<body>
		<input id="text" type="text" value="<?php echo $COMPIL; ?>" style="width:80%; display:none" /><br />
		<table width="200" border="1" align="center" style="font-family:'Courier New', Courier, monospace" rules="all">
		  	<tr style="text-align:center">
		  	  	<td style="text-align:center" rowspan="3"><img src="<?=base_url()?>/assets/AdminLTE-2.0.5/dist/img/NKELogo.jpg" style="width: 160px; height : 40px"></td>
		  	  	<td style="text-align:center"><?=$QRIT_DEST?></td>
		  	  	<td style="text-align:center" rowspan="4">
		    		<div id="qrcode" style="width:100px; height:100px;"></div>
		    	</td>
		  	</tr>
		  	<tr>
				<td style="text-align: center;" nowrap>&nbsp;<?=$QRIT_NOPOL." ".$QRIT_DRIVER?>&nbsp;</td>
			</tr>
		  	<tr>
				<td style="text-align: center;" nowrap>&nbsp;<?php echo "$QRIT_DIM : ".number_format($QRIT_VOL)." $QRIT_UNIT"; ?>&nbsp;</td>
			</tr>
		  	<tr>
				<td style="text-align: center; font-weight: bold;" nowrap>&nbsp;<?=$QRIT_CODE?>&nbsp;</td>
				<td style="text-align: center;" nowrap>&nbsp;<?php echo "$QRIT_MATERIAL"; ?>&nbsp;</td>
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
</html>