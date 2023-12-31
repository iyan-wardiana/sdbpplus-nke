<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 20 Desember 2021
	* File Name		= v_addjobdet_form.php
	* Location		= -M
*/

$this->load->view('template/head');

date_default_timezone_set("Asia/Jakarta");
set_time_limit(0);

$appName 	= $this->session->userdata('appName');
$appBody 	= $this->session->userdata['appBody'];
$FlagUSER 	= $this->session->userdata['FlagUSER'];
$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
$DEPCODE 	= $this->session->userdata['DEPCODE'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat,APPLEV');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
	$Display_Rows = $row->Display_Rows;
	$decFormat = $row->decFormat;
	$APPLEV = $row->APPLEV;
endforeach;
$decFormat		= 2;

$sql 	= "SELECT PRJNAME, RAPT_STAT, RAPP_STAT, PRJ_LOCK_STAT FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJNAME 	= $row->PRJNAME;
	$RAPT_STAT 	= $row->RAPT_STAT;
	$RAPP_STAT 	= $row->RAPP_STAT;
	$PRJ_ISLOCK	= $row->PRJ_LOCK_STAT;
endforeach;
$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$sqlJlD			= "SELECT JOBDESC, JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
					FROM tbl_joblist_$PRJCODEVW WHERE JOBCODEID = '$JOBPARCODE' AND  PRJCODE = '$PRJCODE' LIMIT 1";
$resJlD			= $this->db->query($sqlJlD)->result();
foreach($resJlD as $rowJLD) :
    $JOBDESC 	= $rowJLD->JOBDESC;
    $JOBUNIT 	= $rowJLD->JOBUNIT;
    $JOBLEV 	= $rowJLD->JOBLEV;
    $JOBVOLM 	= $rowJLD->JOBVOLM;
    $JOBPRICE 	= $rowJLD->PRICE;
    $JOBCOST 	= $rowJLD->JOBCOST;
    $BOQ_VOLM 	= $rowJLD->BOQ_VOLM;
    $BOQ_PRICE 	= $rowJLD->BOQ_PRICE;
    $BOQ_JOBCOST= $rowJLD->BOQ_JOBCOST;
endforeach;
if($task == 'add')
{
	$JOB_NUM		= $PRJCODE.".".date('YmdHis');
	$JOB_DATE 		= date('YmdHis');
	$JOB_PARCODE	= $JOBPARCODE;
	$JOB_PARDESC	= $JOBDESC;
	$JOB_UNIT		= $JOBUNIT;
	$JOB_BOQV		= $BOQ_VOLM;
	$JOB_BOQP		= $BOQ_PRICE;
	$JOB_BOQT		= $BOQ_JOBCOST;
	$JOB_RAPV		= $JOBVOLM;
	$JOB_RAPP		= $JOBPRICE;
	$JOB_RAPT		= $JOBCOST;
	$JOB_NOTE		= "";
	$JOB_STAT 		= 1;
}
$JOB_RAPV		= $JOBVOLM;
$JOB_DEV 	= $JOB_BOQT - $JOB_RAPT;

$s_00 		= "tbl_jobcreate_detail WHERE JOB_NUM = '$JOB_NUM' AND PRJCODE =  '$PRJCODE'";
$totalrow 	= $this->db->count_all($s_00);

$TOT_JOBC 	= 0;
/*$s_ITM		= "SELECT SUM(ITM_TOTAL) AS TOT_JOBCOST FROM tbl_jobcreate_detail 
				WHERE JOB_NUM = '$JOB_NUM' AND JOBPARENT = '$JOB_PARCODE'AND PRJCODE = '$PRJCODE'";*/
$s_ITM		= "SELECT SUM(ITM_TOTAL) AS TOT_JOBCOST FROM tbl_jobcreate_detail_$PRJCODEVW
				WHERE JOBPARENT = '$JOB_PARCODE' AND PRJCODE = '$PRJCODE'";
$r_ITM 		= $this->db->query($s_ITM)->result();
foreach($r_ITM as $rw_ITM) :
	$TOT_JOBC	= $rw_ITM->TOT_JOBCOST;
endforeach;

$TOT_JOBCD 	= 0;
$s_JD		= "SELECT SUM(ITM_BUDG) AS TOT_JOBCD FROM tbl_joblist_detail_$PRJCODEVW 
				WHERE JOBCODEID = '$JOB_PARCODE'AND PRJCODE = '$PRJCODE'";
$r_JD 		= $this->db->query($s_JD)->result();
foreach($r_JD as $rw_JD) :
	$TOT_JOBCD	= $rw_JD->TOT_JOBCD;
endforeach;

$nSyns 	= 0;
if($JOB_RAPT != $TOT_JOBC)
	$nSyns 	= 1;
else if($JOB_RAPT != $TOT_JOBCD)
	$nSyns 	= 1;
else if($TOT_JOBC != $TOT_JOBCD)
	$nSyns 	= 1;

$s_JID 		= "tbl_jobcreate_header WHERE PRJCODE = '$PRJCODE' AND JOB_PARCODE = '$JOB_PARCODE'";
$r_JID 		= $this->db->count_all($s_JID);
if($r_JID == 0)
{
	$PRJ_HO 	= "";
	$s_00 		= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
	$r_00 		= $this->db->query($s_00)->result();
	foreach($r_00 as $rw_00) :
		$PRJ_HO = $rw_00->PRJCODE_HO;
	endforeach;

	$PRJ_NKE 	= $PRJ_HO;
	$s_00a 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
	$r_00a 		= $this->db->query($s_00a)->result();
	foreach($r_00a as $rw_00a) :
		$PRJ_NKE = $rw_00a->PRJCODE;
	endforeach;

	// START : PARENT JOB DETAIL
		$JOBDESC 		= "";
		$JOBUNIT 		= "";
		$JOBLEV 		= 0;
		$JOBVOLM 		= 0;
		$JOBPRICE 		= 0;
		$JOBCOST 		= 0;
		$BOQ_VOLM 		= 0;
		$BOQ_PRICE 		= 0;
		$BOQ_JOBCOST	= 0;
		$sqlJlD			= "SELECT JOBDESC, JOBUNIT, JOBLEV, JOBVOLM, PRICE, JOBCOST, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST
							FROM tbl_joblist WHERE JOBCODEID = '$JOB_PARCODE' AND  PRJCODE = '$PRJCODE' LIMIT 1";
		$resJlD			= $this->db->query($sqlJlD)->result();
		foreach($resJlD as $rowJLD) :
		    $JOBDESC 	= htmlentities($rowJLD->JOBDESC, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
		    $JOBUNIT 	= htmlentities($rowJLD->JOBUNIT, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 | ENT_HTML5);
		    $JOBLEV 	= $rowJLD->JOBLEV;
		    $JOBVOLM 	= $rowJLD->JOBVOLM;
		    $JOBPRICE 	= $rowJLD->PRICE;
		    $JOBCOST 	= $rowJLD->JOBCOST;
		    $BOQ_VOLM 	= $rowJLD->BOQ_VOLM;
		    $BOQ_PRICE 	= $rowJLD->BOQ_PRICE;
		    $BOQ_JOBCOST= $rowJLD->BOQ_JOBCOST;

			// START : SAVE HEADER 		// KTR.2022.20220111103001
				$JOB_NUM 	= $PRJCODE.".".date('YmdHis');
				$JOB_DATE 	= date('Y-m-d H:i:s');
				$s_01		= "INSERT INTO tbl_jobcreate_header (PRJCODE, JOB_NUM, JOB_DATE, JOB_PARCODE, JOB_PARDESC, JOB_UNIT, JOB_BOQV, JOB_BOQP, JOB_BOQT,
									JOB_RAPV, JOB_RAPP, JOB_RAPT, JOB_CREATER, JOB_CREATED, JOB_STAT)
								VALUES ('$PRJCODE', '$JOB_NUM', '$JOB_DATE', '$JOB_PARCODE', '$JOBDESC', '$JOBUNIT', '$BOQ_VOLM', '$BOQ_PRICE', '$BOQ_JOBCOST',
									'$JOBVOLM', '$JOBPRICE', '$JOBCOST', '$DefEmp_ID', '$JOB_DATE', 1)";
				$this->db->query($s_01);
			// END : SAVE HEADER

			// START : SAVE DETAIL
				$sqlJlD			= "SELECT JOBCODEID, ITM_CODE, BOQ_VOLM, BOQ_PRICE, BOQ_JOBCOST, RAPT_VOLM, RAPT_PRICE, RAPT_JOBCOST
									FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT = '$JOB_PARCODE' AND  PRJCODE = '$PRJCODE' LIMIT 1";
				$resJlD			= $this->db->query($sqlJlD)->result();
				foreach($resJlD as $rowJLD) :
				    $JOBID 		= $rowJLD->JOBCODEID;
				    $ITMCODE 	= $rowJLD->ITM_CODE;
				    $BOQVOL 	= $rowJLD->BOQ_VOLM;
				    $BOQPRICE 	= $rowJLD->BOQ_PRICE;
				    $BOQJOBCOST = $rowJLD->BOQ_JOBCOST;
				    $RAPTVOL 	= $rowJLD->RAPT_VOLM;
				    $RAPTPRICE 	= $rowJLD->RAPT_PRICE;
				    $RAPTJOBCOST= $rowJLD->RAPT_JOBCOST;
				    $BOQVOLP 	= $BOQVOL;
				    if($BOQVOL == 0)
				    	$BOQVOLP = 1;

				    $ITM_KOEF 	= $RAPTVOL / $BOQVOLP;

					$s_05b 		= "SELECT ITM_NAME, ITM_CATEG, ITM_TYPE, ITM_CURRENCY, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, ISMTRL, ITM_LR,
										ITM_GROUP, ITM_UNIT, LASTNO
									FROM tbl_item WHERE PRJCODE = '$PRJ_NKE' AND ITM_CODE = '$ITMCODE' LIMIT 1";
					$r_05b 		= $this->db->query($s_05b)->result();
					foreach($r_05b as $rw_05b):
						$ITM_NAME 	= $rw_05b->ITM_NAME;
						$ITM_CATEG 	= $rw_05b->ITM_CATEG;
						$ITM_TYPE 	= $rw_05b->ITM_TYPE;
						$ITM_CURR 	= $rw_05b->ITM_CURRENCY;
						$ACC_ID 	= $rw_05b->ACC_ID;
						$ACC_ID_UM 	= $rw_05b->ACC_ID_UM;
						$ACC_ID_SAL = $rw_05b->ACC_ID_SAL;
						$STATUS 	= $rw_05b->STATUS;
						$ISMTRL 	= $rw_05b->ISMTRL;
						$ITM_LR 	= $rw_05b->ITM_LR;
						$ITM_GROUP 	= $rw_05b->ITM_GROUP;
						$ITM_UNIT 	= $rw_05b->ITM_UNIT;
						$LASTNO 	= $rw_05b->LASTNO;
					endforeach;

					$s_02 	= "tbl_item WHERE ITM_CODE = '$ITMCODE' AND PRJCODE = '$PRJCODE'";
					$r_02 	= $this->db->count_all($s_02);
					if($r_02 == 0)
					{
						$sqlInsITM	= "INSERT INTO tbl_item (PRJCODE, PRJCODE_HO, PRJPERIOD, ITM_CODE, ITM_GROUP, ITM_CATEG, ITM_NAME, 
											ITM_DESC, ITM_TYPE, ITM_UNIT, UMCODE, ITM_CURRENCY, ITM_VOLMBG, ITM_VOLMBGR, 
											ITM_VOLM,  ITM_PRICE, ITM_REMQTY, ITM_TOTALP, ITM_LASTP, ITM_AVGP, BOQ_ITM_VOLM, 
											BOQ_ITM_PRICE, BOQ_ITM_TOTALP, ACC_ID, ACC_ID_UM, ACC_ID_SAL, STATUS, 
											ISMTRL, ISRM, ISWIP, ISFG, ISRIB, NEEDQRC, LASTNO, ITM_LR, CREATED, CREATER, oth_reason)
										VALUES ('$PRJCODE', '$PRJ_HO', '$PRJCODE', '$ITMCODE', '$ITM_GROUP', '$ITM_CATEG', '$ITM_NAME', 
											'', '$ITM_TYPE', '$ITM_UNIT', '$ITM_UNIT', '$ITM_CURR', '$RAPTVOL', '$RAPTVOL', 
											'0', '$RAPTPRICE', '0', '$RAPTJOBCOST', '$RAPTPRICE', '$RAPTPRICE', '0', 
											'0', '0', '$ACC_ID', '$ACC_ID_UM', '$ACC_ID_SAL', '$STATUS', 
											'$ISMTRL', '0', '0', '0', '0', '1', '$LASTNO', '$ITM_LR', '$JOB_DATE', '$DefEmp_ID', 'BoQ Manual')";
						$this->db->query($sqlInsITM);
					}

					$s_03		= "INSERT INTO tbl_jobcreate_detail (PRJCODE, JOB_NUM, JOBCODEID, JOBPARENT, ITM_CODE, ITM_NAME, ITM_UNIT, ITM_GROUP,
										ITM_BOQV, ITM_BOQP, ITM_KOEF, ITM_RAPV, ITM_RAPP, ITM_TOTAL, ISLOCK)
									VALUES ('$PRJCODE', '$JOB_NUM', '$JOBID', '$JOB_PARCODE', '$ITMCODE', '$ITM_NAME', '$ITM_UNIT', '$ITM_GROUP',
										$BOQVOL, $BOQPRICE, $ITM_KOEF, $RAPTVOL, $RAPTPRICE, $RAPTJOBCOST, 1)";
					$this->db->query($s_03);
				endforeach;
			// END : SAVE DETAIL
		endforeach;
	// END : PARENT JOB DETAIL
}

/*$JOBPARENT 	= "B.03.01.17.03";
$arrJID 	= explode(".", $JOBPARENT);
$arrJIDC 	= count($arrJID);
$JIDN 		= "";
for($i=0;$i<$arrJIDC;$i++)
{
	if($i==0)
		$JIDPN 	= $arrJID[$i];
	else
		$JIDPN 	= $JIDPN.".".$arrJID[$i];

	$TOTDET 		= 0;
	$s_TOTDET 		= "SELECT SUM(ITM_BUDG) AS TOTDET FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBPARENT LIKE '$JIDPN%' AND ISLAST = 1 AND PRJCODE = '$PRJCODE'";
	$r_TOTDET 		= $this->db->query($s_TOTDET)->result();
	foreach($r_TOTDET as $rw_TOTDET) :
		$TOTDET 	= $rw_TOTDET->TOTDET;
	endforeach;

	$s_u2a0	= "UPDATE tbl_joblist SET JOBVOLM = IF(JOBVOLM = 0, 1, JOBVOLM), JOBCOST = $TOTDET, PRICE = JOBCOST / IF(JOBVOLM=0,1,JOBVOLM)
				WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE'";
	$this->db->query($s_u2a0);

	$s_u2a0	= "UPDATE tbl_joblist SET JOBVOLM = IF(JOBVOLM = 0, 1, JOBVOLM), JOBCOST = $TOTDET, PRICE = JOBCOST / IF(JOBVOLM=0,1,JOBVOLM)
				WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE'";
	$this->db->query($s_u2a0);

	$s_u2b0	= "UPDATE tbl_joblist_detail SET ITM_VOLM = IF(ITM_VOLM = 0, 1, ITM_VOLM), ITM_BUDG = $TOTDET, ITM_PRICE = ITM_BUDG / ITM_VOLM,
					ITM_LASTP = $TOTDET / IF(ITM_VOLM = 0, 1, ITM_VOLM), ITM_AVGP = $TOTDET / IF(ITM_VOLM = 0, 1, ITM_VOLM),
					RAPT_VOLM = ITM_VOLM, RAPT_PRICE = ITM_PRICE, RAPT_JOBCOST = ITM_BUDG,
					UPDATER = '', UPDATED = '', UPDFLAG = 'SYNC'
				WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE'";
	$this->db->query($s_u2b0);
}*/
/*$s_JD		= "SELECT JOBCODEID, PRJCODE FROM tbl_joblist_detail WHERE ISLASTH = 1 ORDER BY PRJCODE, JOBCODEID";
$r_JD 		= $this->db->query($s_JD)->result();
foreach($r_JD as $rw_JD) :
	$TOT_JOBCD	= $rw_JD->JOBCODEID;
	$PRJCODE1	= $rw_JD->PRJCODE;

	$arrJIDP 	= explode(".", $TOT_JOBCD);
	$arrJIDPC 	= count($arrJIDP);
	$JIDPN 		= "";
	for($i=0;$i<$arrJIDPC;$i++)
	{
		if($i==0)
			$JIDPN 	= $arrJIDP[$i];
		else
			$JIDPN 	= $JIDPN.".".$arrJIDP[$i];

		$TOTDET 		= 0;
		$s_TOTDET 		= "SELECT IF(SUM(ITM_BUDG) IS NULL, 0,SUM(ITM_BUDG)) AS TOTDET FROM tbl_joblist_detail
							WHERE JOBPARENT LIKE '$JIDPN%' AND ISLAST = 1 AND PRJCODE = '$PRJCODE1'";
		$r_TOTDET 		= $this->db->query($s_TOTDET)->result();
		foreach($r_TOTDET as $rw_TOTDET) :
			$TOTDET 	= $rw_TOTDET->TOTDET;
		endforeach;

		$s_u2a0	= "UPDATE tbl_joblist SET JOBVOLM = IF(JOBVOLM = 0, 1, JOBVOLM), JOBCOST = $TOTDET, PRICE = JOBCOST / IF(JOBVOLM=0,1,JOBVOLM)
					WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE1'";
		$this->db->query($s_u2a0);

		$s_u2a0	= "UPDATE tbl_joblist SET JOBVOLM = IF(JOBVOLM = 0, 1, JOBVOLM), JOBCOST = $TOTDET, PRICE = JOBCOST / IF(JOBVOLM=0,1,JOBVOLM)
					WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE1'";
		$this->db->query($s_u2a0);

		$s_u2b0	= "UPDATE tbl_joblist_detail SET ITM_VOLM = IF(ITM_VOLM = 0, 1, ITM_VOLM), ITM_BUDG = $TOTDET, ITM_PRICE = ITM_BUDG / ITM_VOLM,
						ITM_LASTP = $TOTDET / IF(ITM_VOLM = 0, 1, ITM_VOLM), ITM_AVGP = $TOTDET / IF(ITM_VOLM = 0, 1, ITM_VOLM),
						RAPT_VOLM = ITM_VOLM, RAPT_PRICE = ITM_PRICE, RAPT_JOBCOST = ITM_BUDG, ITM_BUDGDET = $TOTDET, RAPT_JOBCOSTDET = $TOTDET
					WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE1'";
		$this->db->query($s_u2b0);
		echo "TOT_JOBCD $TOT_JOBC<br>";
	}
endforeach;*/
/*$s_JD		= "SELECT JOBCODEID, PRJCODE FROM tbl_joblist_detail WHERE ISLASTH = 1 ORDER BY PRJCODE, JOBCODEID";
$r_JD 		= $this->db->query($s_JD)->result();
foreach($r_JD as $rw_JD) :
	$TOT_JOBCD	= $rw_JD->JOBCODEID;
	$PRJCODE1	= $rw_JD->PRJCODE;

	$arrJIDP 	= explode(".", $TOT_JOBCD);
	$arrJIDPC 	= count($arrJIDP);
	$JIDPN 		= "";
	for($i=0;$i<$arrJIDPC;$i++)
	{
		if($i==0)
			$JIDPN 	= $arrJIDP[$i];
		else
			$JIDPN 	= $JIDPN.".".$arrJIDP[$i];

		$TOTDET 		= 0;
		$s_TOTDET 		= "SELECT
								IF(SUM(ADD_JOBCOST) IS NULL, 0,SUM(ADD_JOBCOST)) AS TOTADD,
								IF(SUM(ADDM_JOBCOST) IS NULL, 0,SUM(ADDM_JOBCOST)) AS TOTADDM,
								IF(SUM(AMD_VAL) IS NULL, 0,SUM(AMD_VAL)) AS TOTADD2,
								IF(SUM(AMDM_VAL) IS NULL, 0,SUM(AMDM_VAL)) AS TOTADDM2
							FROM tbl_joblist_detail
							WHERE JOBPARENT LIKE '$JIDPN%' AND ISLAST = 1 AND PRJCODE = '$PRJCODE1'";
		$r_TOTDET 		= $this->db->query($s_TOTDET)->result();
		foreach($r_TOTDET as $rw_TOTDET) :
			$TOTADD1 	= $rw_TOTDET->TOTADD;
			$TOTADDM1 	= $rw_TOTDET->TOTADDM;
			$TOTADD2 	= $rw_TOTDET->TOTADD2;
			$TOTADDM2 	= $rw_TOTDET->TOTADDM2;
		endforeach;

	
		$s_u2a0	= "UPDATE tbl_joblist SET ADD_JOBCOST = $TOTADD2, ADDM_JOBCOST = $TOTADDM2 WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE1'";
		$this->db->query($s_u2a0);

		$s_u2b0	= "UPDATE tbl_joblist_detail SET ADD_JOBCOST = $TOTADD1, ADDM_JOBCOST = $TOTADDM1, AMD_VAL = $TOTADD2, AMDM_VAL = $TOTADDM2
					WHERE JOBCODEID = '$JIDPN' AND PRJCODE = '$PRJCODE1'";
		$this->db->query($s_u2b0);
		echo "TOT_JOBCD $TOT_JOBC<br>";
	}
endforeach;*/

?>
<!DOCTYPE html>
<html>
  	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="icon" type="image/png" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/favicon/lock-02.png'; ?>" sizes="32x32">
        <!-- Tell the browser to be responsive to screen width -->
        <?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk  = $rowcss->cssjs_lnk;
                ?>
                    <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
                <?php
            endforeach;

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }
		
	    a[disabled="disabled"] {
	        pointer-events: none;
	    }
	</style>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');
		
		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
		$ISDELETE	= $this->session->userdata['ISDELETE'];
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'Save')$Save = $LangTransl;
			if($TranslCode == 'Update')$Update = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'JobParent')$JobParent = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'docNotes')$docNotes = $LangTransl;
			if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
			if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'JobCode')$JobCode = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'Price')$Price = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'BudName')$BudName = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'ItemList')$ItemList = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'Requested')$Requested = $LangTransl;
			if($TranslCode == 'Ordered')$Ordered = $LangTransl;
			if($TranslCode == 'Remain')$Remain = $LangTransl;
			if($TranslCode == 'Select')$Select = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;

			if($TranslCode == 'Approve')$Approve = $LangTransl;
			if($TranslCode == 'Approver')$Approver = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
		endforeach;
		
		if($LangID == 'IND')
		{
			$alert1		= "Silahkan pilih ".$BudName."";
			$alert2		= "Silahkan pilih salah satu item yang akan diminta";
			$alert3 	= "Masukan total Qty pemesanan untuk item yang sudah dipilih.";
			$alert4 	= "Volume item kosong / Volume item salah";
			$alert5 	= "Nilai koefisien kosong / Nilai koefisien salah.";
			$alert6 	= "Harga item kosong / Harga item salah.";
			$sureDel 	= "Anda yakin akan menghapus komponen ini?";
			$sureLock 	= "Anda yakin akan mengunci komponen ini?";
			$sureUndo 	= "Anda yakin akan mengedit komponen RAP ini?";
		}
		else
		{
			$alert1		= "Please select ".$BudName."";
			$alert2		= "Please select an item will be requested";
			$alert3 	= "Enter the total order qty for the selected item.";
			$alert4 	= "Item volume is empty / Item volume not correct";
			$alert5 	= "Koefisien value is empty / Koefisien value not correct";
			$alert6 	= "Item Price is empty / Item price not correct";
			$sureDel 	= "Are you sure want to delete this component?";
			$sureLock 	= "Are you sure want to lock this component?";
			$sureUndo 	= "Are you sure want to undo this RAP component?";
		}
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// JOB_NUM - PR_VALUE
			$EMPN_1 	= "";
			$EMPN_2 	= "";
			$EMPN_3 	= "";
			$EMPN_4		= "";
			$EMPN_5 	= "";

			$IS_LAST	= 0;
			$APP_LEVEL	= 0;
			$APPROVER_1	= '';
			$APPROVER_2	= '';
			$APPROVER_3	= '';
			$APPROVER_4	= '';
			$APPROVER_5	= '';	
			$disableAll	= 1;
			$DOCAPP_TYPE= 1;
			$sqlCAPP	= "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
			$resCAPP	= $this->db->count_all($sqlCAPP);
			if($resCAPP > 0)
			{
				$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$MAX_STEP		= $rowAPP->MAX_STEP;
					$APPROVER_1		= $rowAPP->APPROVER_1;
					if($APPROVER_1 != '')
					{
						$EMPN_1		= '';
						$sqlEMPC_1	= "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
						$resEMPC_1	= $this->db->count_all($sqlEMPC_1);
						if($resEMPC_1 > 0)
						{
							$sqlEMP_1	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
							$resEMP_1	= $this->db->query($sqlEMP_1)->result();
							foreach($resEMP_1 as $rowEMP) :
								$FN_1	= $rowEMP->First_Name;
								$LN_1	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_1		= "$FN_1 $LN_1";
						}
					}
					$APPROVER_2	= $rowAPP->APPROVER_2;
					if($APPROVER_2 != '')
					{
						$EMPN_2		= '';
						$sqlEMPC_2	= "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
						$resEMPC_2	= $this->db->count_all($sqlEMPC_2);
						if($resEMPC_2 > 0)
						{
							$sqlEMP_2	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
							$resEMP_2	= $this->db->query($sqlEMP_2)->result();
							foreach($resEMP_2 as $rowEMP) :
								$FN_2	= $rowEMP->First_Name;
								$LN_2	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_2		= "$FN_2 $LN_2";
						}
					}
					$APPROVER_3	= $rowAPP->APPROVER_3;
					if($APPROVER_3 != '')
					{
						$EMPN_3		= '';

						$sqlEMPC_3	= "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
						$resEMPC_3	= $this->db->count_all($sqlEMPC_3);
						if($resEMPC_3 > 0)
						{
							$sqlEMP_3	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
							$resEMP_3	= $this->db->query($sqlEMP_3)->result();
							foreach($resEMP_3 as $rowEMP) :
								$FN_3	= $rowEMP->First_Name;
								$LN_3	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_3		= "$FN_3 $LN_3";
						}
					}
					$APPROVER_4	= $rowAPP->APPROVER_4;
					if($APPROVER_4 != '')
					{
						$EMPN_4		= '';
						$sqlEMPC_4	= "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
						$resEMPC_4	= $this->db->count_all($sqlEMPC_4);
						if($resEMPC_4 > 0)
						{
							$sqlEMP_4	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
							$resEMP_4	= $this->db->query($sqlEMP_4)->result();
							foreach($resEMP_4 as $rowEMP) :
								$FN_4	= $rowEMP->First_Name;
								$LN_4	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_4		= "$FN_4 $LN_4";
						}
					}
					$APPROVER_5	= $rowAPP->APPROVER_5;
					if($APPROVER_5 != '')
					{
						$EMPN_5		= '';
						$sqlEMPC_5	= "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
						$resEMPC_5	= $this->db->count_all($sqlEMPC_5);
						if($resEMPC_5 > 0)
						{
							$sqlEMP_5	= "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
							$resEMP_5	= $this->db->query($sqlEMP_5)->result();
							foreach($resEMP_5 as $rowEMP) :
								$FN_5	= $rowEMP->First_Name;
								$LN_5	= $rowEMP->Last_Name;
							endforeach;
							$EMPN_5		= "$FN_5 $LN_5";
						}
					}
				endforeach;
				$disableAll	= 0;
			
				// CHECK AUTH APPROVE TYPE
				$sqlAPPT	= "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
								AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPPT	= $this->db->query($sqlAPP)->result();
				foreach($resAPPT as $rowAPPT) :
					$DOCAPP_TYPE	= $rowAPPT->DOCAPP_TYPE;
				endforeach;
			}
			
			$sqlSTEPAPP	= "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
							AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
			$resSTEPAPP	= $this->db->count_all($sqlSTEPAPP);
			
			if($resSTEPAPP > 0)
			{
				$canApprove	= 1;
				$APPLIMIT_1	= 0;
				
				$sqlAPP	= "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
							AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
				$resAPP	= $this->db->query($sqlAPP)->result();
				foreach($resAPP as $rowAPP) :
					$APPLIMIT_1	= $rowAPP->APPLIMIT_1;
					$APP_STEP	= $rowAPP->APP_STEP;
					$MAX_STEP	= $rowAPP->MAX_STEP;
				endforeach;
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$JOB_NUM'";
				$resC_App 	= $this->db->count_all($sqlC_App);
				
				$BefStepApp	= $APP_STEP - 1;
				if($resC_App == $BefStepApp)
				{
					$canApprove	= 1;
				}
				elseif($resC_App == $APP_STEP)
				{
					$canApprove	= 0;
					$descApp	= "You have Approved";
					$statcoloer	= "success";
				}
				else
				{
					$canApprove	= 0;
					$descApp	= "Awaiting";
					$statcoloer	= "warning";
				}
							 
				if($APP_STEP == $MAX_STEP)
					$IS_LAST		= 1;
				else
					$IS_LAST		= 0;
				
				// Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
				// This roles are for All Approval. Except PR and Receipt
				// NOTES
				// $APPLIMIT_1 		= Maximum Limit to Approve
				// $APPROVE_AMOUNT	= Amount must be Approved
				$APPROVE_AMOUNT 	= 10000000000;
				//$APPROVE_AMOUNT	= 10000000000;
				//$DOCAPP_TYPE	= 1;
				if($DOCAPP_TYPE == 1)
				{
					if($APPLIMIT_1 < $APPROVE_AMOUNT)
					{
						$canApprove	= 0;
						$descApp	= "You can not approve caused of the max limit.";
						$statcoloer	= "danger";
					}
				}
			}
			else
			{
				$canApprove	= 0;
				$descApp	= "You can not approve this document.";
				$statcoloer	= "danger";
				$IS_LAST	= 0;
				$APP_STEP	= 0;
			}
			
			$APP_LEVEL	= $APP_STEP;
		// END : APPROVE PROCEDURE

		$comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/purchase_req.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$Add ($PRJCODE)"; ?>
			    <small><?php echo "$mnName - $PRJNAME"; ?></small>
			</h1>
		</section>

		<section class="content">
		    <div class="row">
	            <form class="form-horizontal" name="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return checkForm()">
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
				                <input type="Hidden" name="rowCount" id="rowCount" value="0">
				                <input type="hidden" name="JOB_NUM" id="JOB_NUM" value="<?php echo $JOB_NUM; ?>" />
				                <input type="hidden" name="PRJCODE" id="PRJCODE" value="<?php echo $PRJCODE; ?>" />
				                <input type="hidden" name="JOB_PARCODE" id="JOB_PARCODE" value="<?php echo $JOB_PARCODE; ?>" />
				                <input type="hidden" name="JOB_PARDESC" id="JOB_PARDESC" value="<?php echo $JOB_PARDESC; ?>" />
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: center;">
			                    		<?php echo "<strong>$JobName</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
				                    		<div class="col-md-12">
					                    		<?php echo "$JOBPARCODE<br>$JOBDESC"; ?>
					                    	</div>
				                    	</div>
			                    		<div class="row">
				                    		<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i>Volume</i>";
					                    		?>
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i>Harga</i>";
					                    		?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i>Total</i>";
					                    		?>
					                    	</div>
				                    	</div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php echo "<strong>BoQ</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqVOL'><strong>"
					                    					.number_format($JOB_BOQV, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_UNIT" id="JOB_UNIT" value="<?php echo $JOB_UNIT; ?>" />
					                    		<input type="hidden" name="JOB_BOQV" id="JOB_BOQV" value="<?php echo $BOQ_VOLM; ?>" />
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqPRC'><strong>"
					                    					.number_format($JOB_BOQP, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_BOQP" id="JOB_BOQP" value="<?php echo $JOB_BOQP; ?>" />
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i class='text-primary' style='font-size: 16px;' id='boqTOT'><strong>"
					                    					.number_format($JOB_BOQT, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_BOQT" id="JOB_BOQT" value="<?php echo $JOB_BOQT; ?>" />
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php echo "<strong>RAP</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapVOL'><strong>"
					                    					.number_format($JOBVOLM, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPV" id="JOB_RAPV" value="<?php echo $JOB_RAPV; ?>" />
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapPRC'><strong>"
					                    					.number_format($JOBPRICE, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPP" id="JOB_RAPP" value="<?php echo $JOB_RAPP; ?>" />
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			echo 	"<i class='text-yellow' style='font-size: 16px;' id='rapTOT'><strong>"
					                    					.number_format($JOBCOST, 2).
					                    					"</strong></i>";
					                    		?>
					                    		<input type="hidden" name="JOB_RAPT" id="JOB_RAPT" value="<?php echo $JOB_RAPT; ?>" />
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
			                  	<div class="row">
			                    	<div class="col-md-4" style="text-align: right;">
			                    		<?php //echo "<strong>Deviasi</strong>"; ?>
			                    	</div>
			                    	<div class="col-md-8">
			                    		<div class="row">
					                    	<div class="col-md-3">
					                    		<?php
					                    			echo 	"&nbsp;";
					                    		?>
					                    	</div>
					                    	<div class="col-md-4">
					                    		<?php
					                    			echo 	"";
					                    		?>
					                    	</div>
					                    	<div class="col-md-5">
					                    		<?php
					                    			if($JOB_DEV >=0)
					                    			{
					                    				//echo 	"<i class='text-success' style='font-size: 16px;' id='devAMN'><strong>".number_format($JOB_DEV, 2)."</strong></i>";
					                    				echo "";
					                    			}
					                    			else
					                    			{
					                    				//echo 	"<i class='text-danger' style='font-size: 16px;' id='devAMN'><strong>".number_format($JOB_DEV, 2)."</strong></i>";
					                    				echo "";
					                    			}
					                    		?>
					                    	</div>
					                    </div>
			                    	</div>
		                    	</div>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
				                    <div class="col-sm-9">
				                        <textarea name="JOB_NOTE" class="form-control" id="JOB_NOTE" style="height: 70px"><?php echo $JOB_NOTE; ?></textarea>
				                    </div>
				                </div>
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
				                    <div class="col-sm-6" style="display: none;">
				                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $JOB_STAT; ?>">
				                        <?php
				                            $isDisabled = 1;
				                            if($JOB_STAT == 1 || $JOB_STAT == 4)
				                            {
				                                $isDisabled = 0;
				                            }
				                        ?>
		                                <select name="JOB_STAT" id="JOB_STAT" class="form-control select2">
		                                    <?php
			                                    $disableBtn	= 0;
			                                    if($JOB_STAT == 5 || $JOB_STAT == 6 || $JOB_STAT == 9)
			                                    {
			                                        $disableBtn	= 1;
			                                    }
			                                    if($JOB_STAT != 1 AND $JOB_STAT != 4)
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($JOB_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
			                                            <option value="2"<?php if($JOB_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
			                                            <option value="3"<?php if($JOB_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
			                                            <option value="4"<?php if($JOB_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
			                                            <option value="5"<?php if($JOB_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
			                                            <option value="6"<?php if($JOB_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
			                                            <option value="7"<?php if($JOB_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
			                                            <option value="9"<?php if($JOB_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
			                                        <?php
			                                    }
			                                    else
			                                    {
			                                        ?>
			                                            <option value="1"<?php if($JOB_STAT == 1) { ?> selected <?php } ?>>New</option>
			                                            <option value="2"<?php if($JOB_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
			                                        <?php
			                                    }
			                                ?>
		                                </select>
				                    </div>
				                    <div class="col-sm-2">
				                    	<?php
			                    			$disSelITM	= 0;
			                    			/* COMMENT ON 18 05 2022 KARENA BOLEH TIDAK ADA VOL BOQ UTK MENAMBAHKAN VOL. RAP
			                    			if($JOB_BOQV == 0)
			                    				$disSelITM	= 1;*/

			                    			$ANLCAT 	= 0;
				                    		$MDLITM 	= "disabled";
				                    		if($RAPT_STAT == 0)
				                    		{
				                    			$ANLCAT 	= 1;
				                    			$MDLITM 	= "data-toggle='modal' data-target='#mdl_addItm'";
				                    		}
				                    		else if($RAPT_STAT == 1)
				                    		{
				                    			$ANLCAT 	= 2;
				                    			$MDLITM 	= "data-toggle='modal' data-target='#mdl_addItm'";
				                    		}
				                    		else if($RAPT_STAT == 1 && $RAPP_STAT == 1)
				                    		{
				                    			$ANLCAT 	= 0;
				                    			$MDLITM 	= "disabled";
				                    		}

				                    		if($PRJ_ISLOCK == 1)
				                    		{
				                    			//
				                    		}
					                    	elseif($disSelITM == 0)
					                    	{ 
					                    		?>
						                        	<a class='btn btn-sm btn-warning' <?=$MDLITM?>>
						                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
						                        	</a>
					                        	<?php
					                        }
					                        else
					                        {
					                        	?>
					                        		<span class="text-red" style="font-weight: bold; font-style: italic;">Tidak bisa memilih item karena Volume BoQ masih 0.00 <i class="glyphicon glyphicon-question-sign" title="Silahkan update volume BoQ pekerjaan ini pada menu Anggaran Proyek"></i></span>
					                        	<?php
					                        }
				                        ?>
				                    </div>
				                    <div class="col-sm-7">
				                    	<?php
				                    		if($PRJ_ISLOCK == 1)
				                    		{
					                        	?>
					                        		<span class="text-red" style="font-weight: bold; font-style: italic;">Tidak dapat melakukan pemilihan item komponen RAPT/RAPP karena proyek ini dalam keadaan terkunci &nbsp;<i class="glyphicon glyphicon-question-sign" title="Pemilihan item saat ini adalah pemiliihan item yang akan ditambahkan dalam komponen RAPP."></i></span>
					                        	<?php
				                    		}
					                    	elseif($ANLCAT == 1)
					                    	{ 
					                    		?>
						                        	<span class="text-red" style="font-weight: bold; font-style: italic;">Pemilihan item komponen RAPT &nbsp;<i class="glyphicon glyphicon-question-sign" title="Pemilihan item saat ini adalah pemiliihan item yang akan ditambahkan dalam komponen RAPT"></i></span>
					                        	<?php
					                        }
					                        else if($ANLCAT == 2)
					                        {
					                        	?>
					                        		<span class="text-red" style="font-weight: bold; font-style: italic;">Pemilihan item komponen RAPP &nbsp;<i class="glyphicon glyphicon-question-sign" title="Pemilihan item saat ini adalah pemiliihan item yang akan ditambahkan dalam komponen RAPP."></i></span>
					                        	<?php
					                        }
					                        else if($ANLCAT == 0)
					                        {
					                        	?>
					                        		<span class="text-red" style="font-weight: bold; font-style: italic;">Pemilihan item komponen sudah terkunci &nbsp;<i class="glyphicon glyphicon-question-sign" title="Pemilihan item sudah terkunci karena RAPT dan RAPP sudah dikunci oleh departemen terkait."></i></span>
					                        	<?php
					                        }
				                        ?>
				                        <input type="hidden" name="ANLCAT" id="ANLCAT" value="<?php echo $ANLCAT; ?>">
				                    </div>
				                </div>
							</div>
						</div>
					</div>

	                <div class="col-md-12">
                        <div class="search-table-outter">
							<table id="tbl" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
						        <thead>
						            <tr>
						                <th width="2%" height="25" style="text-align:left">&nbsp;</th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemCode ?> </th>
	                                  	<th width="30%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;">Volume</th>
	                                  	<th width="5%" style="text-align:center; vertical-align: middle;">Koef</th>
	                                  	<th width="10%" style="text-align:center; vertical-align: middle;"><?php echo $Price ?> </th>
	                                  	<th width="15%" style="text-align:center; vertical-align: middle;">Total </th>
	                                  	<th width="3%" style="text-align:center; vertical-align: middle;">Sat.</th>
	                                  	<th width="15%" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
						          	</tr>
						        </thead>
								<tbody>
								</tbody>
								<tfoot>
				                </tfoot>
							</table>
							<input type="hidden" name="totalrow" id="totalrow" value="<?php echo $totalrow; ?>">
                        </div>
		            </div>
	                <br>
	                <br>
	                <div class="col-md-12">
		                <div class="form-group">
		                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<br>
		                        <!-- <button class="btn btn-primary" id="btnSave" <?php if($task == 'edit') { ?> style="display: none;" <?php } ?>> -->
		                        <button class="btn btn-primary" id="btnSave" style="display: none;">
		                        	<i class="fa fa-save"></i>
		                        </button>
		                        <button type="button" class="btn btn-warning" id="btnSync" <?php if($nSyns == 0 || $PRJ_ISLOCK == 1) { ?> style="display: none;" <?php } ?> title="Sinkronkan" onClick="SyncVal()">
		                        	<i class="glyphicon glyphicon-refresh"></i>
		                        </button>
		                        <?php
		                            echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		            </div>
	            </form>
	        </div>

	    	<!-- ============ START MODAL =============== -->
		    	<style type="text/css">
		    		.modal-dialog{
					    position: relative;
					    display: table; /* This is important */ 
					    overflow-y: auto;    
					    overflow-x: auto;
					    width: auto;
					    min-width: 300px;   
					}
		    	</style>
		    	<?php
					$Active1		= "active";
					$Active2		= "";
					$Active3		= "";
					$Active4		= "";
					$Active5		= "";
					$Active9		= "";
					$Active1Cls		= "class='active'";
					$Active2Cls		= "";
					$Active3Cls		= "";
					$Active4Cls		= "";
					$Active5Cls		= "";
					$Active9Cls		= "";
		    	?>
		        <div class="modal fade" id="mdl_addItm" name='mdl_addItm' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		            <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-body">
								<div class="row">
							    	<div class="col-md-12">
						              	<ul class="nav nav-tabs">
						                    <li id="li1" <?php echo $Active1Cls; ?>>
						                    	<a href="#itm1" data-toggle="tab" onClick="setType(1)">Material</a>
						                    </li>
						                    <li id="li2" <?php echo $Active2Cls; ?>>
						                    	<a href="#itm2" data-toggle="tab" onClick="setType(2)">Upah</a>
						                    </li>
						                    <li id="li3" <?php echo $Active3Cls; ?>>
						                    	<a href="#itm3" data-toggle="tab" onClick="setType(3)">Subkon</a>
						                    </li>
						                    <li id="li4" <?php echo $Active4Cls; ?>>
						                    	<a href="#itm4" data-toggle="tab" onClick="setType(4)">Alat</a>
						                    </li>
						                    <li id="li5" <?php echo $Active5Cls; ?>>
						                    	<a href="#itm5" data-toggle="tab" onClick="setType(5)">Lainnya</a>
						                    </li>
						                    <li id="li9" <?php echo $Active9Cls; ?>>
						                    	<a href="#itm9" data-toggle="tab" onClick="setType(9)">Master Item</a>
						                    </li>
						                </ul>
							            <div class="box-body">
							            	<div class="<?php echo $Active1; ?> tab-pane" id="itm1">
		                                        <div class="form-group">
		                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
			                                            <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
			                                                <thead>
			                                                    <tr>
			                                                        <th style="text-align: center; vertical-align: middle;">&nbsp;</th>
			                                                        <th style="text-align: center; vertical-align: middle;" nowrap><?php echo $ItemName; ?></th>
			                                                        <th style="text-align: center; vertical-align: middle;" nowrap>Sat.</th>
			                                                  	</tr>
			                                                </thead>
			                                                <tbody>
			                                                </tbody>
			                                            </table>
                                                    	<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
                                                    	</button>
                                      					<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    	</button>
														<button class="btn btn-warning" type="button" id="idRefresh1" title="Refresh" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
		                                            </form>
		                                      	</div>
		                                    </div>
                                      	</div>
                                      	<input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                      	<button type="button" id="idClose" class="btn btn-default" data-dismiss="modal" style="display: none";>Close</button>
	                                </div>
	                            </div>
			                </div>
				        </div>
				    </div>
				</div>

				<script type="text/javascript">
					function setType(tabType)
					{
						if(tabType == 1)
							var ITMCAT 	= 'M';
						else if(tabType == 2)
							var ITMCAT 	= 'U';
						else if(tabType == 3)
							var ITMCAT 	= 'S';
						else if(tabType == 4)
							var ITMCAT 	= 'T';
						else
							var ITMCAT 	= 'O';

						if(tabType == 9)
						{
							$('#example1').DataTable(
					    	{
		    					"destroy": true,
						        "processing": true,
						        "serverSide": true,
								//"scrollX": false,
								"autoWidth": true,
								"filter": true,
						        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM4/?id='.$PRJCODE.'&JID='.$JOB_PARCODE.'&CATEG=')?>"+ITMCAT,
						        "type": "POST",
								//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
								"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
								"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
												{ "width": "5px", "targets": [0,2] }
											  ],
								"language": {
						            "infoFiltered":"",
						            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
						        },
							});
						}
						else
						{
							$('#example1').DataTable(
					    	{
		    					"destroy": true,
						        "processing": true,
						        "serverSide": true,
								//"scrollX": false,
								"autoWidth": true,
								"filter": true,
						        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM3/?id='.$PRJCODE.'&JID='.$JOB_PARCODE.'&CATEG=')?>"+ITMCAT,
						        "type": "POST",
								//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
								"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
								"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
												{ "width": "5px", "targets": [0,2] }
											  ],
								"language": {
						            "infoFiltered":"",
						            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
						        },
							});
						}
					}

					$(document).ready(function()
					{
				    	/*$('#example1').DataTable(
				    	{
		    				"destroy": true,
					        "processing": true,
					        "serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
					        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM3/?id='.$PRJCODE.'&JID='.$JOB_PARCODE.'&CATEG=M')?>",
					        "type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
											{ "width": "5px", "targets": [0,2] }
										  ],
							"language": {
					            "infoFiltered":"",
					            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
					        },
						});*/

						$('#example1').DataTable(
						{
							"destroy": true,
							"processing": true,
							"serverSide": true,
							//"scrollX": false,
							"autoWidth": true,
							"filter": true,
							"ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataITM3/?id='.$PRJCODE.'&JID='.$JOB_PARCODE.'&CATEG=M')?>",
							"type": "POST",
							//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
							"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
							"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
											{ "width": "5px", "targets": [0,2] }
											],
							"language": {
								"infoFiltered":"",
								"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
							},
						});

						$("#idRefresh1").click(function()
						{
							$('#example1').DataTable().ajax.reload();
						});
					});

					var selectedRows = 0;
					function pickThis1(thisobj) 
					{
						var favorite = [];
						$.each($("input[name='chk1']:checked"), function() {
					      	favorite.push($(this).val());
					    });
					    $("#rowCheck").val(favorite.length);
					}

					$(document).ready(function()
					{
					   	$("#btnDetail1").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk1']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });

					   	$("#btnDetail2").click(function()
					    {
							var totChck 	= $("#rowCheck").val();
							if(totChck == 0)
							{
								swal('<?php echo $alert2; ?>',
								{
									icon: "warning",
								})
								.then(function()
					            {
					                swal.close();
					            });
								return false;
							}

						    $.each($("input[name='chk2']:checked"), function()
						    {
						      	add_item($(this).val());
						    });

						    $('#mdl_addItm').on('hidden.bs.modal', function () {
							    $(this)
								    .find("input,textarea,select")
									    //.val('')
									    .end()
								    .find("input[type=checkbox], input[type=radio]")
								       .prop("checked", "")
								       .end();
							});
                        	document.getElementById("idClose").click()
					    });
					});
				</script>
	    	<!-- ============ END MODAL =============== -->

        	<?php
				$act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        		if($DefEmp_ID == 'D15040004221')
                	echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<?php
	$collData 	= "$PRJCODE~$JOB_PARCODE";
	$AddItm 	= base_url().'index.php/c_project/c_joblist/addItmTmp/?id=';
	$chgVK 		= base_url().'index.php/c_project/c_joblist/chgVOLKOEF/?id=';
	$SynsVal	= base_url().'index.php/c_project/c_joblist/SyncVal/?id=';
?>
<script>
	/*function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

	$(document).ready(function()
	{
		$(document).on("keydown", disableF5);
	});*/

	$(function() {
        $(this).bind("contextmenu", function(e) {
            e.preventDefault();
        });
    });

	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
		
		//Datemask dd/mm/yyyy
		$("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
		//Datemask2 mm/dd/yyyy
		$("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
		//Money Euro
		$("[data-mask]").inputmask();
		
		//Date range picker
		$('#reservation').daterangepicker();
		//Date range picker with time picker
		$('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
		//Date range as a button
		$('#daterange-btn').daterangepicker(
			{
			  ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			  },
			  startDate: moment().subtract(29, 'days'),
			  endDate: moment()
			},
			function (start, end) {
			  $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			}
		);
		
		//Date picker
		$.fn.datepicker.defaults.format = "dd/mm/yyyy";
	    $('#datepicker').datepicker({
	      autoclose: true,
		  endDate: '+1d'
	    });
		
		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		  startDate: '+0d'
		});
		
		//iCheck for checkbox and radio inputs
		$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		  checkboxClass: 'icheckbox_minimal-blue',
		  radioClass: 'iradio_minimal-blue'
		});
		//Red color scheme for iCheck
		$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
		  checkboxClass: 'icheckbox_minimal-red',
		  radioClass: 'iradio_minimal-red'
		});
		//Flat red color scheme for iCheck
		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		  checkboxClass: 'icheckbox_flat-green',
		  radioClass: 'iradio_flat-green'
		});
		
		//Colorpicker
		$(".my-colorpicker1").colorpicker();
		//color picker with addon
		$(".my-colorpicker2").colorpicker();
		
		//Timepicker
		$(".timepicker").timepicker({
		  showInputs: false
		});
	});

	var selectedRows = 0;
	function check_all(chk) 
	{
		var totRow = document.getElementById('totalrow').value;
		if(chk.checked == true)
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = true;
			}
		}
		else
		{
			for(i=1;i<=totRow;i++)
			{
				var aaaa = document.getElementById('data['+i+'][chk]').checked = false;
			}
		}
	}

	function alertLOCK() 
	{
		swal("Maaf!", "Tidak bisa update RAPT/RAPP, proyek ini dalam keadaan terkunci", "warning");
	}

	$(document).ready(function()
	{
		// Copy RAP dari tbl_joblist_detail jika di tbl_jobcreat_detail tidak ada
		let PRJCODE 	= $('#PRJCODE').val();
		let JOB_NUM 	= $('#JOB_NUM').val();
		let JOB_PARCODE = $('#JOB_PARCODE').val();
		$.ajax({
			url: "<?php echo site_url('c_project/c_joblist/copyRAPDET') ?>",
			type: "POST",
			data: {PRJCODE:PRJCODE,JOB_NUM:JOB_NUM,JOB_PARCODE:JOB_PARCODE},
			error: function(xhr, status, err) {
				console.log('status ='+status+' error = '+err);
			},	
			success: function(result) {
				reCountVal();
				$('#tbl').DataTable().ajax.reload();
				console.log(result);
			}
		});

    	$('#tbl').DataTable(
    	{
    		"destroy": true,
	        "processing": true,
	        "serverSide": true,
			//"scrollX": false,
			"autoWidth": true,
			"filter": true,
	        // "ajax": "<?php // echo site_url('c_project/c_joblist/get_AllDataRAPDET/?id='.$collData)?>",
	        "ajax": {
		        "url": "<?php echo site_url('c_project/c_joblist/get_AllDataRAPDET/?id='.$collData)?>",
		        "type": "POST",
		        "complete": function()
		        {
		        	url 	= "<?php echo site_url('c_project/c_joblist/get_AllDataRAPDETC/?id='.$collData)?>"
		        	$.ajax({
			            type: 'POST',
			            url: url,
			            success: function(tRow)
			            {
			            	document.getElementById('totalrow').value = tRow;
			            }
			        });
		        }
	        }, 
	        "type": "POST",
			//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
			"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,7], className: 'dt-body-center' },
							{ targets: [3,4,5,6], className: 'dt-body-right' },
						  ],
        	"order": [[ 0, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});

	function add_item_220113(strItem) 
	{
		arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;

		var JOB_NUMx 	= "<?php echo $JOB_NUM; ?>";
		ilvl = arrItem[1];
		
		validateDouble(arrItem[3])
		if(validateDouble(arrItem[3]))
		{
			swal("Double Item for " + arrItem[3],
			{
				icon: "warning",
			});
			return;
		}

		PRJCODE 		= arrItem[0];
		JOBPARENT 		= arrItem[1];
		JOBPARDESC 		= arrItem[2];
		ITM_CODE 		= arrItem[3];
		ITM_NAME 		= arrItem[4];
		ITM_UNIT 		= arrItem[5];
		ITM_GROUP 		= arrItem[6];

		ITM_BOQV 		= 0;
		ITM_BOQP 		= 0;

		ITM_RAPV 		= 0;
		ITM_RAPP 		= 0;

		// START : SETTING ROWS INDEX
			intIndexA 		= parseFloat(document.getElementById('totalrow').value);
			intIndex 		= parseInt(intIndexA)+1;
			objTable 		= document.getElementById('tbl');
			intTable 		= objTable.rows.length;

			document.frm.rowCount.value = intIndex;
			
			objTR 			= objTable.insertRow(intTable);
			objTR.id 		= 'tr_' + intIndex;
		// START : SETTING ROWS INDEX
		
		// Checkbox
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = "center";
			objTD.noWrap = true;
			objTD.innerHTML = '<a href="#" onClick="deleteRow('+intIndex+')" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a><input type="hidden" id="chk" name="chk" value="'+intIndex+'" width="10" size="15" readonly class="form-control" style="max-width:300px;">';
		
		// ITM_CODE, PRJCODE, ITM_GROUP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_CODE+'<input type="hidden" id="data'+intIndex+'ITM_CODE" name="data['+intIndex+'][ITM_CODE]" value="'+ITM_CODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'PRJCODE" name="data['+intIndex+'][PRJCODE]" value="'+PRJCODE+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'JOBPARENT" name="data['+intIndex+'][JOBPARENT]" value="'+JOBPARENT+'" class="form-control" style="max-width:300px;"><input type="hidden" id="data'+intIndex+'ITM_GROUP" name="data['+intIndex+'][ITM_GROUP]" value="'+ITM_GROUP+'" class="form-control" style="max-width:300px;">';
		
		// ITM_NAME
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'right';
			objTD.innerHTML = ''+ITM_NAME+'<input type="hidden" id="data'+intIndex+'ITM_NAME" name="data['+intIndex+'][ITM_NAME]" value="'+ITM_NAME+'" class="form-control" style="max-width:300px;">';
		
		// ITM_RAPV
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_RAPVX'+intIndex+'" id="ITM_RAPVX'+intIndex+'" value="0.00" class="form-control" style="min-width:80px; max-width:90px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgVOL(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_RAPV]" id="data'+intIndex+'ITM_RAPV" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_KOEF
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_KOEFX'+intIndex+'" id="ITM_KOEFX'+intIndex+'" value="0.00" class="form-control" style="min-width:60px; max-width:70px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgKOEF(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_KOEF]" id="data'+intIndex+'ITM_KOEF" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_RAPP
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_RAPPX'+intIndex+'" id="ITM_RAPPX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:120px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgPRICE(this,'+intIndex+');" ><input type="hidden" name="data['+intIndex+'][ITM_RAPP]" id="data'+intIndex+'ITM_RAPP" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_TOTAL
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'right';
			objTD.innerHTML = '<input type="text" name="ITM_TOTALX'+intIndex+'" id="ITM_TOTALX'+intIndex+'" value="0.00" class="form-control" style="min-width:110px; max-width:130px; text-align:right" onKeyPress="return isIntOnlyNew(event);" ><input type="hidden" name="data['+intIndex+'][ITM_TOTAL]" id="data'+intIndex+'ITM_TOTAL" value="0" class="form-control" style="max-width:300px;" >';
		
		// ITM_UNIT
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.style.textAlign = 'center';
			objTD.innerHTML = ''+ITM_UNIT+'<input type="hidden" name="data['+intIndex+'][ITM_UNIT]" id="data'+intIndex+'ITM_UNIT" value="'+ITM_UNIT+'" class="form-control" style="max-width:300px;" >';
		
		// ITM_NOTES
			objTD = objTR.insertCell(objTR.cells.length);
			objTD.align = 'left';
			objTD.innerHTML = '<input type="text" name="data['+intIndex+'][ITM_NOTES]" id="data'+intIndex+'ITM_NOTES" size="20" value="" class="form-control" style="min-width:110px; max-width:300px; text-align:left"><input type="hidden" name="data['+intIndex+'][ISLOCK]" id="data'+intIndex+'ISLOCK" size="20" value="0" class="form-control" style="min-width:110px; max-width:300px; text-align:left">';

		document.getElementById('totalrow').value = intIndex;
		//document.getElementById('btnSave').style.display = ''; di hidden karena saat additem, otomatis save
	}

	function add_item(strItem) 
	{
		/*arrItem = strItem.split('|');
		var objTable, objTR, objTD, intIndex, arrItem;*/

		var JOB_NUM 	= "<?php echo $JOB_NUM; ?>";
		var JOB_NOTE 	= document.getElementById('JOB_NOTE').value;
		//ilvl = arrItem[1];
		
		/*validateDouble(arrItem[3])
		if(validateDouble(arrItem[3]))
		{
			swal("Double Item for " + arrItem[3],
			{
				icon: "warning",
			});
			return;
		}*/

		/*PRJCODE 		= arrItem[0];
		JOBPARENT 		= arrItem[1];
		JOBPARDESC 		= arrItem[2];
		ITM_CODE 		= arrItem[3];
		ITM_NAME 		= arrItem[4];
		ITM_UNIT 		= arrItem[5];
		ITM_GROUP 		= arrItem[6];*/

		collDt 			= strItem+'|'+JOB_NUM+'|'+JOB_NOTE;

		url 			= "<?=$AddItm?>";
		$.ajax({
            type: 'POST',
            url: url,
            data: {collDt: collDt},
            success: function(response)
            {
				document.getElementById('totalrow').value 	= response;

		    	$('#tbl').DataTable(
		    	{
		    		"destroy": true,
			        "processing": true,
			        "serverSide": true,
					//"scrollX": false,
					"autoWidth": true,
					"filter": true,
			        "ajax": "<?php echo site_url('c_project/c_joblist/get_AllDataRAPDET/?id='.$collData)?>",
			        "type": "POST",
					//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
					"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
					"columnDefs": [	{ targets: [0,7], className: 'dt-body-center' },
									{ targets: [3,4,5,6], className: 'dt-body-right' },
								  ],
		        	"order": [[ 2, "desc" ]],
					"language": {
			            "infoFiltered":"",
			            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			        },
				});
            }
        });
	}
	
	function chgVOL(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_RAPV	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_RAPV').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2);
		document.getElementById('ITM_RAPVX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2));

		//JOBH_VOLM 	= parseFloat(document.getElementById('JOB_BOQV').value);
		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_RAPV').value);
		ITM_KOEF		= parseFloat(ITM_RAPV / JOBH_VOLM);
		document.getElementById('data'+row+'ITM_KOEF').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4);
		document.getElementById('ITM_KOEFX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4));

		ITM_RAPP 		= parseFloat(document.getElementById('data'+row+'ITM_RAPP').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}
		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);

		JOBUNIT 		= document.getElementById('JOB_UNIT').value;
		JOB_UNIT 		= JOBUNIT.toUpperCase();
		if(JOB_UNIT == 'LS')
		{
			GTVolITM 	= 1;
			ITM_AVGP 	= GTAmnITM;
		}
		/*else if(JOB_UNIT == 'BLN')
		{
			GTVolITM 	= 12;
			ITM_AVGP 	= parseFloat(GTAmnITM) / parseFloat(GTVolITM);
		}*/

		//document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		/* ---------------------------------- hidden => just info ----------------------------------------------------------
		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));
		----------------------------------------------------- end hidden -------------------------------------------------- */

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			/*document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');*/

		// AUTO SAVE TO DATABASE
			var PRJCODE 	= "<?php echo $PRJCODE; ?>";
			var JOB_NUM 	= "<?php echo $JOB_NUM; ?>";
			var ITM_CODE 	= document.getElementById('data'+row+'ITM_CODE').value;
			var JOB_PARC 	= document.getElementById('JOB_PARCODE').value;
			var collID		= PRJCODE+'~'+JOB_NUM+'~'+ITM_CODE+'~'+ITM_RAPV+'~'+ITM_KOEF+'~'+ITM_RAPP+'~'+ITM_TOTAL+'~'+JOB_PARC;
	        var url 		= "<?=$chgVK?>";

	        $.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(response)
	            {
	            	//swal(response)
	            },
	            error:(error) => {
                     console.log(JSON.stringify(error));}
	        });
	}
	
	function chgKOEF(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_KOEF	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_KOEF').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4);
		document.getElementById('ITM_KOEFX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_KOEF)), 4));

		//JOBH_VOLM 	= parseFloat(document.getElementById('JOB_BOQV').value);
		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_RAPV').value);
		ITM_RAPV		= parseFloat(ITM_KOEF * JOBH_VOLM);
		document.getElementById('data'+row+'ITM_RAPV').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2);
		document.getElementById('ITM_RAPVX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPV)), 2));

		ITM_RAPP 		= parseFloat(document.getElementById('data'+row+'ITM_RAPP').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}
		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);

		JOBUNIT 		= document.getElementById('JOB_UNIT').value;
		JOB_UNIT 		= JOBUNIT.toUpperCase();
		if(JOB_UNIT == 'LS')
		{
			GTVolITM 	= 1;
			ITM_AVGP 	= GTAmnITM;
		}
		/*else if(JOB_UNIT == 'BLN')
		{
			GTVolITM 	= 12;
			ITM_AVGP 	= parseFloat(GTAmnITM) / parseFloat(GTVolITM);
		}*/
		
		//document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		/* ---------------------------------- hidden => just info ----------------------------------------------------------
		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));
		----------------------------------------------------- end hidden -------------------------------------------------- */

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			/*document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');*/

		// AUTO SAVE TO DATABASE
			var PRJCODE 	= "<?php echo $PRJCODE; ?>";
			var JOB_NUM 	= "<?php echo $JOB_NUM; ?>";
			var ITM_CODE 	= document.getElementById('data'+row+'ITM_CODE').value;
			var JOB_PARC 	= document.getElementById('JOB_PARCODE').value;
			var collID		= PRJCODE+'~'+JOB_NUM+'~'+ITM_CODE+'~'+ITM_RAPV+'~'+ITM_KOEF+'~'+ITM_RAPP+'~'+ITM_TOTAL+'~'+JOB_PARC;
	        var url 		= "<?=$chgVK?>";

	        $.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(response)
	            {
	            	//swal(response)
	            }
	        });
	}
	
	function chgPRICE(thisval, row)
	{
		var decFormat	= document.getElementById('decFormat').value;
		var ITM_RAPP	= parseFloat(eval(thisval).value.split(",").join(""));
		document.getElementById('data'+row+'ITM_RAPP').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_RAPP)), 2);
		// document.getElementById('ITM_RAPPX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_RAPP)), 2));
		// Req. By. Pak Wawan tgl. 22-02-2023 diperbolehkan input harga minus berdasarkan berita acara pernyataan & pengesahan RAP PELAKSANAAN (RAPP)
		document.getElementById('ITM_RAPPX'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(ITM_RAPP), 2));

		//JOBH_VOLM 	= parseFloat(document.getElementById('JOB_BOQV').value);
		JOBH_VOLM 		= parseFloat(document.getElementById('JOB_RAPV').value);
		ITM_RAPV 		= parseFloat(document.getElementById('data'+row+'ITM_RAPV').value);
		ITM_TOTAL		= parseFloat(ITM_RAPV * ITM_RAPP);

		// document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2);
		document.getElementById('data'+row+'ITM_TOTAL').value 	= RoundNDecimal(parseFloat(ITM_TOTAL), 2);
		// document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)), 2));
		document.getElementById('ITM_TOTALX'+row).value 		= doDecimalFormat(RoundNDecimal(parseFloat(ITM_TOTAL), 2));

		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}

		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);

		JOBUNIT 		= document.getElementById('JOB_UNIT').value;
		JOB_UNIT 		= JOBUNIT.toUpperCase();
		if(JOB_UNIT == 'LS')
		{
			GTVolITM 	= 1;
			ITM_AVGP 	= GTAmnITM;
		}
		/*else if(JOB_UNIT == 'BLN')
		{
			GTVolITM 	= 12;
			ITM_AVGP 	= parseFloat(GTAmnITM) / parseFloat(GTVolITM);
		}*/
		
		//document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		/* ---------------------------------- hidden => just info ----------------------------------------------------------
		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));
		----------------------------------------------------- end hidden -------------------------------------------------- */

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			/*document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');*/

		// AUTO SAVE TO DATABASE
			var PRJCODE 	= "<?php echo $PRJCODE; ?>";
			var JOB_NUM 	= "<?php echo $JOB_NUM; ?>";
			var ITM_CODE 	= document.getElementById('data'+row+'ITM_CODE').value;
			var ITM_KOEF	= parseFloat(document.getElementById('data'+row+'ITM_KOEF').value);
			var JOB_PARC 	= document.getElementById('JOB_PARCODE').value;
			var collID		= PRJCODE+'~'+JOB_NUM+'~'+ITM_CODE+'~'+ITM_RAPV+'~'+ITM_KOEF+'~'+ITM_RAPP+'~'+ITM_TOTAL+'~'+JOB_PARC;
	        var url 		= "<?=$chgVK?>";

	        $.ajax({
	            type: 'POST',
	            url: url,
	            data: {collID: collID},
	            success: function(response)
	            {
	            	//swal(response)
	            }
	        });
	}
	
	function validateDouble(vcode) 
	{
		var totRow 		= document.getElementById('totalrow').value;
		var duplicate 	= false;

		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_CODE');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				var elitem1	= document.getElementById('data'+i+'ITM_CODE').value;
				if (elitem1 == vcode)
				{
					if (elitem1 == vcode) 
					{
						duplicate = true;
						break;
					}
				}
			}
		}
		return duplicate;
	}
	
	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
		reCountVal();
	}
	
	function delRow(row)
	{
	    /* DI-HIDE BY REQUEST PAK EDY ON 13 JAN 22
	    swal({
            text: "<?php echo $sureDel; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {*/
                var collID	= document.getElementById('urlDel'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
		            success: function(response)
		            {
		            	/* DI-HIDE BY REQUEST PAK EDY ON 13 JAN 22
		            	swal(response, 
						{
							icon: "success",
						});*/
		                $('#tbl').DataTable().ajax.reload();
		                reCountVal();
		            }
		        });
            /*} 
            else 
            {
                //
            }
        });*/
	}
	
	function reCountVal()
	{
		var decFormat	= document.getElementById('decFormat').value;
		var totRow 		= document.getElementById('totalrow').value;
		
		var GTVolITM	= 0;
		var GTAmnITM	= 0;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'ITM_TOTAL');
			var values 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(values != null)
			{
				totVITM 	= parseFloat(document.getElementById('data'+i+'ITM_RAPV').value);
				GTVolITM 	= parseFloat(GTVolITM) + parseFloat(totVITM);

				totITM 		= parseFloat(document.getElementById('data'+i+'ITM_TOTAL').value);
				GTAmnITM 	= parseFloat(GTAmnITM) + parseFloat(totITM);
			}
		}

		GTVolITMP 		= parseFloat(GTVolITM);
		if(GTVolITM == 0)
			GTVolITMP 	= 1;

		ITM_AVGP 		= parseFloat(GTAmnITM) / parseFloat(GTVolITMP);

		JOBUNIT 		= document.getElementById('JOB_UNIT').value;
		JOB_UNIT 		= JOBUNIT.toUpperCase();
		if(JOB_UNIT == 'LS')
		{
			GTVolITM 	= 1;
			ITM_AVGP 	= GTAmnITM;
		}
		/*else if(JOB_UNIT == 'BLN')
		{
			GTVolITM 	= 12;
			ITM_AVGP 	= parseFloat(GTAmnITM) / parseFloat(GTVolITM);
		}*/

		//document.getElementById('JOB_RAPV').value 		= RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2);
		document.getElementById('JOB_RAPP').value 		= RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2);
		document.getElementById('JOB_RAPT').value 		= RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2);

		/* ---------------------------------- hidden => just info ----------------------------------------------------------
		document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
		document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
		document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));
		----------------------------------------------------- end hidden -------------------------------------------------- */

		// DEVIASI
			var JOB_BOQT	= document.getElementById('JOB_BOQT').value;
			var DevAmn 		= parseFloat(GTAmnITM - JOB_BOQT);
			/*document.getElementById('devAMN').innerHTML 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DevAmn)), 2));
			if(DevAmn >= 0)
				$("#devAMN").removeClass('text-success').addClass('text-danger');
			else
				$("#devAMN").removeClass('text-danger').addClass('text-success');*/
	}

	function lockRow(row)
	{
	    /* DI-HIDE BY REQUEST PAK EDY ON 13 JAN 22
	    swal({
            text: "<?php echo $sureLock; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {*/
                var collIDA		= document.getElementById('urlLock'+row).value;
                var ITM_NOTES	= document.getElementById('data'+row+'ITM_NOTES').value;
                var ANLCAT		= document.getElementById('ANLCAT').value;
                var collID		= collIDA+'~'+ITM_NOTES+'~'+ANLCAT;
		        var myarr 		= collID.split("~");

		        var url 		= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
					beforeSend: function(jqXHR, settings) {
						$('#lockITM'+row).css('display','none');
					},
		            success: function(response)
		            {
		            	 //DI-HIDE BY REQUEST PAK EDY ON 13 JAN 22
		            	swal(response,
						{
							icon: "success",
						});
		                $('#tbl').DataTable().ajax.reload();
		            },
					complete: function() {
						$('#lockITM'+row).css('display','');
					}
		        });
            /*} 
            else 
            {
            	//
            }
        });*/
	}

	function undoRow(row)
	{
	    swal({
            text: "<?php echo $sureUndo; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        })
        .then((willDelete) => 
        {
            if (willDelete) 
            {
                var collID	= document.getElementById('urlUndo'+row).value;
		        var myarr 	= collID.split("~");

		        var url 	= myarr[0];

		        $.ajax({
		            type: 'POST',
		            url: url,
		            data: {collID: collID},
					beforeSend: function(xhr, settings) {
						$('#undoITM'+row).css('display','none');
					},
		            success: function(response)
		            {
		            	/* DI-HIDE BY REQUEST PAK EDY ON 13 JAN 22*/
		            	/*swal(response,
						{
							icon: "success",
						});*/
		                $('#tbl').DataTable().ajax.reload();
		            },
					complete: function() {
						$('#undoITM'+row).css('display','');
					}
		        });
            } 
            else 
            {
                /*swal("<?php echo $cancDel; ?>", 
				{
					icon: "error",
				});*/
            }
        });
	}

	function SyncVal()
	{
		var PRJCODE 	= "<?php echo $PRJCODE; ?>";
		var JOB_NUM 	= "<?php echo $JOB_NUM; ?>";
		var JOB_PARC 	= document.getElementById('JOB_PARCODE').value;
        var collID 		= PRJCODE+'~'+JOB_NUM+'~'+JOB_PARC;
        var url 		= "<?=$SynsVal?>";

        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
            	arrVar 	= response.split("~");
            	TOT_V 	= arrVar[0];
            	AVG_P 	= arrVar[1];
            	TOT_C 	= arrVar[2];

				//document.getElementById('JOB_RAPV').value 			= RoundNDecimal(parseFloat(Math.abs(TOT_V)), 2);
				document.getElementById('JOB_RAPP').value 			= RoundNDecimal(parseFloat(Math.abs(AVG_P)), 2);
				document.getElementById('JOB_RAPT').value 			= RoundNDecimal(parseFloat(Math.abs(TOT_C)), 2);

				/* ---------------------------------- hidden => just info ----------------------------------------------------------
				document.getElementById('rapVOL').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTVolITM)), 2));
				document.getElementById('rapPRC').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_AVGP)), 2));
				document.getElementById('rapTOT').innerHTML 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTAmnITM)), 2));
				----------------------------------------------------- end hidden -------------------------------------------------- */

				document.getElementById('btnSync').style.display 	= 'none';
				location.reload();
            }
        });
	}
	
	function checkForm(value)
	{
		var totrow 		= document.getElementById('totalrow').value;
		var PR_REFNO 	= document.getElementById('PR_REFNO').value;
		var PR_NOTE 	= document.getElementById('PR_NOTE').value;

		var STAT_BEFORE	= document.getElementById('STAT_BEFORE').value;
		var JOB_STAT 	= document.getElementById('JOB_STAT').value;
		reCountVal();
		return false;
		if(PR_NOTE == "")
		{
			swal('<?php echo $docNotes; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                $('#PR_NOTE').focus();
            });
			return false;
		}
	
		function getJob(event)
		{
			var chCode = ('charCode' in event) ? event.charCode : event.keyCode;
            alert ("The Unicode character code is: " + chCode);
		}
	}
  
	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		c = "";
		var panjang = b.length;
		j = 0;
		for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) c = b.substr(i-1,1) + "," + c;
			else c = b.substr(i-1,1) + c;
		}
		if(dec == -1) return angka;
		else return (c + '.' + dec); 
	}

	function RoundNDecimal(X, N) {
		var T, S=new String(Math.round(X*Number("1e"+N)))
		while (S.length<=N) S='0'+S
		return S.substr(0, T=(S.length-N)) + '.' + S.substr(T, N)
	}
	
	function isIntOnlyNew(evt)
	{
		if (evt.which){ var charCode = evt.which; }
		else if(document.all && event.keyCode){ var charCode = event.keyCode; }
		else { return true; }
		return ((charCode == 45) || (charCode == 46) || (charCode == 8) || (charCode >= 48) && (charCode <= 57));
	}

	function decimalin(ini)
	{
		var i, j;
		var bil2 = deletecommaperiod(ini.value,'both')
		var bil3 = ""
		j = 0
		for (i=bil2.length-1;i>=0;i--)
		{
			j = j + 1;
			if (j == 3)
			{
				bil3 = "." + bil3
			}
			else if ((j >= 6) && ((j % 3) == 0))
			{
				bil3 = "," + bil3
			}
			bil3 = bil2.charAt(i) + "" + bil3
		}
		ini.value = bil3
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
    $rescss = $this->db->query($sqlcss)->result();
    foreach($rescss as $rowcss) :
        $cssjs_lnk  = $rowcss->cssjs_lnk;
        ?>
            <script src="<?php echo base_url($cssjs_lnk) ?>"></script>
        <?php
    endforeach;

	// Right side column. contains the Control Panel
	//______$this->load->view('template/aside');

	//______$this->load->view('template/js_data');

	//______$this->load->view('template/foot');
?>