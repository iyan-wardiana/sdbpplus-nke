<?php
require_once APPPATH.'/third_party/spout/src/Spout/Autoloader/autoload.php';

//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Common\Type;

function myerror($error_no, $error_msg){
  	//echo "$error_msg";
}

ini_set('max_execution_time', 0); // to get unlimited php script execution time

if(empty($_SESSION['i'])){
    $_SESSION['i'] = 0;
}

// CHECK STATUS
if(isset($_POST['ITM_CODE']))
{
	date_default_timezone_set("Asia/Jakarta");

	$PRJCODE	= $_POST['PRJCODE'];
	$PRJPERIOD	= $_POST['PRJPERIOD'];
	$ITM_CODE	= $_POST['ITM_CODE'];
	$ITM_PRICE	= $_POST['ITM_PRICE'];

	$PRJCODEVW 		= $PRJCODE;
	$s_prjvw 		= "SELECT PRJCODEVW FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
	$r_prjvw		= $this->db->query($s_prjvw)->result();
	foreach($r_prjvw as $rw_prjvw):
		$PRJCODEVW	= strtolower($rw_prjvw->PRJCODEVW);
	endforeach;

	// 1. MENGUMPULKAN SELURUH PEKERJAAN YANG ETRKAIT DENGAN ITEM
		$s_00	= "vw_joblist_detail_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE'";
		$r_00 	= $this->db->count_all($s_00);

		$percent = intval(0)."%";
		echo '<script>
	    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';
		echo '<script>
	    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' processed</span></div>";</script>';

	    ob_flush();
	    flush();

	// 2. PROCEDUR - RESET PRICE
	    // 0. 	UPDATE ITEM PRICE
			$s_UPD	= "UPDATE tbl_joblist_detail SET ITM_PRICE = $ITM_PRICE, ITM_LASTP = $ITM_PRICE, ITM_AVGP = $ITM_PRICE,
							ITM_BUDG = ITM_VOLM * $ITM_PRICE
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($s_UPD);
		// 1.	MENAMPILKAN SEMUA LEVEL 1. MINIMAL LEVEL 1 STRUKTURNYA HARUS / WAJIB BENAR. MEMANGGIL DARI TBL_JOBLIST AGAR HEADER SAJA
			$PROCID	= 0;
			$JCODE 	= "";
			$s_01	= "SELECT JOBPARENT FROM vw_joblist_detail_$PRJCODEVW WHERE ITM_CODE = '$ITM_CODE' ORDER BY JOBCODEID";
			$r_01 	= $this->db->query($s_01)->result();
			foreach($r_01 as $rw_01) :
				$PROCID		= $PROCID + 1;
				$JOBP_01	= explode(".", $rw_01->JOBPARENT);
				$JOBP_LEN	= count(explode(".", $rw_01->JOBPARENT));
				for($i=0; $i<=$JOBP_LEN-1; $i++)
				{
					if($i == 0)
						$JCODE 	= $JOBP_01[$i];
					else
						$JCODE 	= $JCODE.".".$JOBP_01[$i];

					$s_02	= "UPDATE tbl_joblist_detail A SET A.ITM_BUDG = (SELECT SUM(B.ITM_BUDG) FROM tbl_joblist_detail_$PRJCODEVW B
									WHERE B.JOBCODEID LIKE '$JCODE%' AND B.PRJCODE = A.PRJCODE AND B.ISLAST = 1)
								WHERE A.JOBCODEID = '$JCODE' AND A.PRJCODE = '$PRJCODE'";
					$this->db->query($s_02);
				}

				$percent 	= intval($PROCID/$r_00 * 100)."%";
				echo '<script>
				parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PROCID. ' / '.$r_00.') processed</span></div>";</script>';
				echo '<script>
				parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-warning cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">'.$percent.' ( '.$PROCID. ' / '.$r_00.') processed</span></div>";</script>';

			    ob_flush();
			    flush();
			endforeach;

		// 3.	COMPLETE INFO
			$percent = intval(100)."%";
			echo '<script>
			    parent.document.getElementById("progressbarXX").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo '<script>
			    parent.document.getElementById("progressbarXY").innerHTML="<div class=\"cssProgress-bar cssProgress-success cssProgress-active\" style=\"width:'.$percent.'; text-align:center; font-weight:bold;\"><span style=\"text-align:center; font-weight:bold\">Process completed</span></div>";</script>';
			echo 	'<script>
						parent.document.getElementById("loading_1").style.display ="none";
						parent.document.getElementById("loading_2").style.display ="none";
						parent.document.getElementById("idprogbar").style.display ="none";
						parent.document.getElementById("idprogbarXY").style.display ="none";
				 	</script>';
}
?>
<script > 
    function sleep(milliseconds) { 
        let timeStart = new Date().getTime(); 
        while (true) { 
            let elapsedTime = new Date().getTime() - timeStart; 
            if (elapsedTime > milliseconds) { 
                break; 
            } 
        } 
    }
</script>

<!DOCTYPE html>
<html>
	<head>
		<title>How to Create Progress Bar for Data Insert in PHP using Ajax</title>  
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.8/js/bootstrap.min.js"></script>
	</head>

	<body>
		<br />
		<br />
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-body">
					<span id="success_message"></span>
					<form method="post" id="sample_form">
						<div class="form-group">
							<label>PRJCODE</label>
							<input type="text" name="PRJCODE" id="PRJCODE" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>PERIODE</label>
							<input type="text" name="PRJPERIOD" id="PRJPERIOD" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>IMPORT CODE</label>
							<input type="text" name="ITM_CODE" id="ITM_CODE" class="form-control" value="" />
						</div>
						<div class="form-group">
							<label>TIPE</label>
							<input type="text" name="ITM_PRICE" id="ITM_PRICE" class="form-control" value="" />
						</div>
						<div class="form-group" align="center">
							<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
						</div>
					</form>
					<div class="form-group" id="process" style="display:none;">
						<div class="progress">
							<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
 	</body>
</html>