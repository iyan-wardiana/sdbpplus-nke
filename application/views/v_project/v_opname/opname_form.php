<?php
/*
	* Author		= Dian Hermanto
	* Create Date	= 1 Februari 2018
	* File Name		= opname_form.php
	* Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata('appBody');

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
$decFormat	= 2;

$FlagUSER 		= $this->session->userdata['FlagUSER'];
$DefEmp_ID 		= $this->session->userdata['Emp_ID'];

$PRJHO 			= "";
$PRJNAME		= '';
$PO_RECEIVLOC	= '';
$sql 			= "SELECT PRJCODE_HO, PRJNAME, PRJADD FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result 		= $this->db->query($sql)->result();
foreach($result as $row) :
	$PRJHO 			= $row ->PRJCODE_HO;
	$PRJNAME 		= $row ->PRJNAME;
	$PO_RECEIVLOC 	= $row ->PRJADD;
endforeach;

$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

$currentRow = 0;
$WO_CATEG 	= 'U';
$s_PattC	= "tbl_docpattern WHERE menu_code = '$MenuCode'";
$r_PattC 	= $this->db->count_all($s_PattC);
if($r_PattC > 0)
{
	$isSetDocNo = 1;
	$s_Patt		= "SELECT Pattern_Code, Pattern_Length FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
	$r_Patt 	= $this->db->query($s_Patt)->result();
	foreach($r_Patt as $row) :
		$PATTCODE 	= $row->Pattern_Code;
	endforeach;
}
else
{
	$PATTCODE 		= "XXX";
}

if($task == 'add')
{
	$sql = "SELECT PRJCODE, PRJNAME FROM tbl_project
			WHERE PRJCODE = '$PRJCODE'";
	$resultProj = $this->db->query($sql)->result();
	foreach($resultProj as $row) :
		$PRJNAME = $row->PRJNAME;
	endforeach;

	$TRXTIME		= date('ymdHis');
	$OPNH_NUM		= "$PATTCODE$PRJCODE.$TRXTIME";
	$DocNumber 		= "";
	$OPNH_CODE		= $DocNumber;

	$OPNH_DATEX		= date('Y-m-d');
	$OPNH_DATE		= date('d/m/Y');
	$WO_ENDD		= date('d/m/Y');
	$OPNH_DATESP	= date('d/m/Y', strtotime('-6 days', strtotime($OPNH_DATEX)));
	$OPNH_DATEEP	= date('d/m/Y');
	$JournalY 		= date('Y');
	$JournalM 		= date('n');
	$PRJCODE		= $PRJCODE;
	$SPLCODE		= '';
	$WO_NUM			= '';
	$JOBCODEID		= '';
	$OPNH_NOTE		= '';
	$OPNH_STAT 		= 1;
	$Patt_Year 		= date('Y');
	$Patt_Number	= 0;
	$WO_NUMX		= '';
	$OPNH_NOTE		= '';
	$OPNH_NOTE2		= '';
	$OPNH_MEMO		= '';
	$OPNH_AMOUNT	= 0;
	$OPNH_AMOUNTPPNP= 0;
	$OPNH_AMOUNTPPN	= 0;
	$OPNH_AMOUNTPPHP= 0;
	$OPNH_AMOUNTPPH = 0;
	$OPNH_RETPERC	= 0;
	$OPNH_RETAMN	= 0;
	$OPNH_RETNO 	= "";
	$OPNH_DPPER		= 0;
	$OPNH_DPVAL		= 0;
	$OPNH_POT		= 0;
	$OPNH_TOTAMOUNT	= 0;
	$OPNH_POTREF	= '';
	$OPNH_POTREF1	= '';
	$OPNH_POTACCID	= '';

	$OPNH_AMOUNTB	 = 0;
	$OPNH_PPNVALB 	 = 0;
	$OPNH_AMOUNTPPHBF= 0;
	$OPNH_DPVALB	 = 0;
	$OPNH_RETAMNB	 = 0;
	$PROG_BEF		 = 0;
	$OPNH_AMOUNTPPHBF= 0;

	if(isset($_POST['WO_NUMX']))
	{
		$WO_NUMX		= $_POST['WO_NUMX'];
	}
	else
	{
		$WO_NUMX		= $WO_NUMX;
	}
	
	if($WO_NUMX != '')
	{
		$WO_DPPER	= 0;
		$WO_DPVAL	= 0;
		$WO_RETP	= 0;
		$WO_RETVAL 	= 0;
		$WO_VALUE	= 0;
		$WO_VALPPN	= 0;
		$WO_VALPPH 	= 0;
		$WO_VALUET	= 0;
		$sqlWOH		= "SELECT WO_CODE, WO_CATEG, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_DPPER, WO_DPVAL,
							WO_RETP, WO_RETVAL, WO_VALUE, WO_VALPPN, WO_VALPPH
						FROM tbl_wo_header
						WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE' AND WO_STAT = 3";
		$resWOH 	= $this->db->query($sqlWOH)->result();
		foreach($resWOH as $rosWOH):
			$WO_CODE	= $rosWOH->WO_CODE;
			$WO_CATEG	= $rosWOH->WO_CATEG;
			$WO_DATE	= $rosWOH->WO_DATE;
			$WO_STARTD	= $rosWOH->WO_STARTD;
			$WO_ENDD	= $rosWOH->WO_ENDD;
			$JOBCODEID	= $rosWOH->JOBCODEID;
			$SPLCODE	= $rosWOH->SPLCODE;
			$WO_NOTE	= $rosWOH->WO_NOTE;
			$WO_DPPER	= $rosWOH->WO_DPPER;
			$WO_DPVAL	= $rosWOH->WO_DPVAL;
			$WO_RETP	= $rosWOH->WO_RETP;
			$WO_RETVAL	= $rosWOH->WO_RETVAL;
			$WO_VALUE	= $rosWOH->WO_VALUE;
			$WO_VALPPN	= $rosWOH->WO_VALPPN;
			$WO_VALPPH	= $rosWOH->WO_VALPPH;
			$WO_VALUET	= $WO_VALUE + $WO_VALPPN;
			$WO_DPVAL	= $WO_DPPER * $WO_VALUE / 100;
		endforeach;
		$OPNH_RETPERC 	= $WO_RETP;
		$OPNH_RETAMN 	= $WO_RETVAL;
		$OPNH_RETNO 	= "";

		// DATA NILAI SPK : WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN, WO_VALUET
			/*$WO_DPPER	= 0;
			$WO_DPVAL	= 0;
			$WO_VALUE	= 0;
			$WO_VALPPN	= 0;
			$WO_VALUET	= 0;
			$sqlGTWO	= "SELECT WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN
							FROM tbl_wo_header WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
			$resGTWO	= $this->db->query($sqlGTWO)->result();
			foreach($resGTWO as $rowGTWO) :
				$WO_DPPER	= $rowGTWO->WO_DPPER;			
				$WO_VALUE	= $rowGTWO->WO_VALUE;
				$WO_VALPPN	= $rowGTWO->WO_VALPPN;
				$WO_VALUET	= $WO_VALUE + $WO_VALPPN;
				$WO_DPVAL	= $WO_DPPER * $WO_VALUE / 100;
			endforeach;*/

		// CARI SISA OPNAME UNTUK MENGHITUNG PENGEMBALIAN DP : TOTWO_AMN, TOTWO_VOL, TOTOPN_AMN, TOTOPN_VOL, REMOPN_AMN = OPNH_AMOUNT
			$TOTWO_AMN	= 0;
			$TOTWO_VOL	= 0;
			$sqlTOT_WO	= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWO_AMN, SUM(A.WO_VOLM) AS TOTWO_VOL
							FROM tbl_wo_detail A
							INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE'";
			$resTOT_WO		= $this->db->query($sqlTOT_WO)->result();
			foreach($resTOT_WO as $rowTOT_WO) :
				$TOTWO_AMN	= $rowTOT_WO->TOTWO_AMN;
				$TOTWO_VOL	= $rowTOT_WO->TOTWO_VOL;
			endforeach;

			$TOTOPN_AMN	= 0;
			$TOTOPN_VOL	= 0;
			$sqlTOT_OPN	= "SELECT SUM(DISTINCT A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
								SUM(DISTINCT A.OPND_VOLM) AS TOTOPN_VOL
							FROM tbl_opn_detail A
							INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
								AND B.PRJCODE = '$PRJCODE'
							WHERE B.WO_NUM = '$WO_NUMX'
								AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT IN (3,6) AND A.OPNH_NUM != '$OPNH_NUM'";
			$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
			foreach($resTOT_OPN as $rowTOT_OPN) :
				$TOTOPN_AMN	= $rowTOT_OPN->TOTOPN_AMN;
				$TOTOPN_VOL	= $rowTOT_OPN->TOTOPN_VOL;
				if($TOTOPN_AMN == '')
					$TOTOPN_AMN	= 0;
				if($TOTOPN_VOL == '')
					$TOTOPN_VOL	= 0;
			endforeach;

			$REMOPN_AMN		= $TOTWO_AMN - $TOTOPN_AMN;
			$OPNH_AMOUNT 	= $REMOPN_AMN;

		// CARI DOKUMEN DP UNTUK SPK INI JIKA ADA : WO_DPVAL, WO_DPVALUS, OPNH_DPPER, OPNH_DPVAL
			/*$WO_DPVAL	= 0;									// NILAI DP
			$WO_DPVALUS	= 0;									// DIGUNAKAN
			//$sqlDPV		= "SELECT DP_AMOUNT, DP_AMOUNT_USED FROM tbl_dp_header WHERE DP_REFNUM = '$WO_NUMX' AND DP_PAID = 2";
			$sqlDPV		= "SELECT DP_AMOUNT, DP_AMOUNT_USED
							FROM tbl_dp_header WHERE DP_REFNUM = '$WO_NUMX'";
			$resDPV	= $this->db->query($sqlDPV)->result();
			foreach($resDPV as $rowDPV) :
				$WO_DPVAL	= $rowDPV->DP_AMOUNT;
				$WO_DPVALUS	= $rowDPV->DP_AMOUNT_USED;
			endforeach;*/

			$OPNH_DPPER	= $WO_DPPER;							// PERSENTASE DP
			$OPNH_DPVAL	= $WO_DPPER * $WO_DPVAL / 100;			// NILAI DP DARI SISA OPNAME (DP BACK SAAT INI)

		// CARI TOTAL PENGEMBALIAN DP : OPNH_TDPVAL
			$OPNH_TDPVAL	= 0;
			$sqlGTOPN		= "SELECT SUM(OPNH_DPVAL) AS TOT_DPVAL
								FROM tbl_opn_header WHERE WO_NUM = '$WO_NUMX'
								AND PRJCODE = '$PRJCODE' AND OPNH_STAT IN (2,3,6)";
			$resGTOPN		= $this->db->query($sqlGTOPN)->result();
			foreach($resGTOPN as $rowGTOPN) :
				$OPNH_TDPVAL	= $rowGTOPN->TOT_DPVAL;			// NILAI TOTAL PENGEMBALIAN DP
			endforeach;
			if($OPNH_TDPVAL == '')
				$OPNH_TDPVAL = 0;

		// CARI SISA PENGEMBALIAN DP : OPNH_DPVAL
			$OPNH_REMP	= $WO_DPVAL - $OPNH_TDPVAL;				// SISA DP
			
			if($OPNH_REMP > $OPNH_DPVAL)
			{
				$OPNH_DPVAL	= $OPNH_DPVAL;
			}
			else
			{
				$OPNH_DPVAL	= $OPNH_REMP;
			}

			if($WO_DPPER == 0)
				$OPNH_DPVAL	= 0;

		// CONCLUSION
			$WO_VALUE 			= $WO_VALUE;
			$WO_VALPPNP  		= $WO_VALPPN / $WO_VALUE * 100;
			$WO_VALPPHP  		= $WO_VALPPH / $WO_VALUE * 100;
			$TOTOPN_AMN 		= $TOTOPN_AMN;
			$OPNH_AMOUNT 		= $OPNH_AMOUNT;
			$OPNH_AMOUNTPPNP 	= $WO_VALPPNP;
			$OPNH_AMOUNTPPN 	= $WO_VALPPNP * $OPNH_AMOUNT / 100;
			$OPNH_AMOUNTPPHP 	= $WO_VALPPHP;
			$OPNH_AMOUNTPPH 	= $WO_VALPPHP * $OPNH_AMOUNT / 100;
			$DPVAL_WO 			= $WO_DPVAL;
			$DPVAL_REM 			= $OPNH_REMP;
			$OPNH_DPVAL 		= $WO_DPPER * $OPNH_AMOUNT / 100;
			if($DPVAL_REM <= 0)
				$OPNH_DPVAL 	= 0;
	}
	else
	{
		$WO_CODE			= '';
		$WO_DATE			= '';
		$WO_STARTD			= '';
		$WO_ENDD			= '';
		$WO_VALUE 			= 0;
		$TOTOPN_AMN 		= 0;
		$DPVAL_WO 			= 0;
		$DPVAL_REM 			= 0;
		$OPNH_TOTAMOUNTX 	= 0;
		$JOBCODEID			= '';
		$SPLCODE			= '';

		$WO_NOTE			= '';
	}
}
else
{
	$isSetDocNo 	= 1;
	$OPNH_NUM 		= $default['OPNH_NUM'];
	$DocNumber		= $default['OPNH_NUM'];
	$OPNH_CODE 		= $default['OPNH_CODE'];
	$OPNH_DATE 		= date('d/m/Y', strtotime($default['OPNH_DATE']));
	$OPNH_DATE 		= date('d/m/Y', strtotime($default['OPNH_DATE']));
	$OPNH_DATESP	= date('d/m/Y', strtotime($default['OPNH_DATESP']));
	$OPNH_DATEEP 	= date('d/m/Y', strtotime($default['OPNH_DATEEP']));
	$JournalY 		= date('Y', strtotime($default['OPNH_DATE']));
	$JournalM 		= date('n', strtotime($default['OPNH_DATE']));
	$PRJCODE		= $default['PRJCODE'];
	$SPLCODE 		= $default['SPLCODE'];
	$WO_NUM 		= $default['WO_NUM'];
	$WO_NUMX		= $WO_NUM;
	$JOBCODEID 		= $default['JOBCODEID'];
	$OPNH_NOTE 		= $default['OPNH_NOTE'];
	$OPNH_NOTE2 	= $default['OPNH_NOTE2'];
	$OPNH_STAT 		= $default['OPNH_STAT'];
	$OPNH_MEMO 		= $default['OPNH_MEMO'];
	$PRJNAME 		= $default['PRJNAME'];
	$OPNH_AMOUNT 	= $default['OPNH_AMOUNT'];
	$OPNH_AMOUNTPPNP= $default['OPNH_AMOUNTPPNP'];
	$OPNH_AMOUNTPPN	= $default['OPNH_AMOUNTPPN'];
	$OPNH_AMOUNTPPHP= $default['OPNH_AMOUNTPPHP'];
	$OPNH_AMOUNTPPH	= $default['OPNH_AMOUNTPPH'];
	$OPNH_RETPERC 	= $default['OPNH_RETPERC'];
	$OPNH_RETAMN 	= $default['OPNH_RETAMN'];
	$OPNH_RETNO 	= $default['OPNH_RETNO'];
	$OPNH_DPPER 	= $default['OPNH_DPPER'];
	$OPNH_DPVAL 	= $default['OPNH_DPVAL'];
	$OPNH_POT		= $default['OPNH_POT'];
	$OPNH_POTREF	= $default['OPNH_POTREF'];
	$OPNH_POTREF1	= $default['OPNH_POTREF1'];
	$OPNH_POTACCID	= $default['OPNH_POTACCID'];
	$Patt_Year 		= $default['Patt_Year'];
	$Patt_Month 	= $default['Patt_Month'];
	$Patt_Date 		= $default['Patt_Date'];
	$Patt_Number	= $default['Patt_Number'];

	$WO_DPPER	= 0;
	$WO_DPVAL	= 0;
	$WO_VALUE	= 0;
	$WO_VALPPN	= 0;
	$WO_VALUET	= 0;
	$WO_CATEG 	= 'U';
	$sqlWOH		= "SELECT WO_CODE, WO_CATEG, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN, WO_VALPPH
					FROM tbl_wo_header
					WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
	$resWOH 	= $this->db->query($sqlWOH)->result();
	foreach($resWOH as $rosWOH):
		$WO_CODE	= $rosWOH->WO_CODE;
		$WO_CATEG	= $rosWOH->WO_CATEG;
		$WO_DATE	= $rosWOH->WO_DATE;
		$WO_STARTD	= $rosWOH->WO_STARTD;
		$WO_ENDD	= $rosWOH->WO_ENDD;
		$JOBCODEID	= $rosWOH->JOBCODEID;
		$SPLCODE	= $rosWOH->SPLCODE;
		$WO_NOTE	= $rosWOH->WO_NOTE;
		$WO_DPPER	= $rosWOH->WO_DPPER;
		$WO_DPVAL	= $rosWOH->WO_DPVAL;
		$WO_VALUE	= $rosWOH->WO_VALUE;
		$WO_VALPPN	= $rosWOH->WO_VALPPN;
		$WO_VALPPH	= $rosWOH->WO_VALPPH;
		$WO_VALUET	= $WO_VALUE + $WO_VALPPN;
		//$WO_DPVAL	= $WO_DPPER * $WO_VALUE / 100;
	endforeach;

	/*$WO_VALUE	= 0;
	$WO_VALPPN	= 0;
	$sqlWOH		= "SELECT WO_CODE, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_VALUE, WO_VALPPN
					FROM tbl_wo_header
					WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE' AND WO_STAT = 3";
	$resWOH 	= $this->db->query($sqlWOH)->result();
	foreach($resWOH as $rosWOH):
		$WO_CODE	= $rosWOH->WO_CODE;
		$WO_DATE	= $rosWOH->WO_DATE;
		$WO_STARTD	= $rosWOH->WO_STARTD;
		$WO_ENDD	= $rosWOH->WO_ENDD;
		$JOBCODEID	= $rosWOH->JOBCODEID;
		$SPLCODE	= $rosWOH->SPLCODE;
		$WO_NOTE	= $rosWOH->WO_NOTE;
		$WO_VALUE	= $rosWOH->WO_VALUE;
		$WO_VALPPN	= $rosWOH->WO_VALPPN;
	endforeach;

	// DATA NILAI SPK : WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN, WO_VALUET
		$WO_DPPER	= 0;
		$WO_DPVAL	= 0;
		$WO_VALUE	= 0;
		$WO_VALPPN	= 0;
		$WO_VALUET	= 0;
		$sqlGTWO	= "SELECT WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN
						FROM tbl_wo_header WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
		$resGTWO	= $this->db->query($sqlGTWO)->result();
		foreach($resGTWO as $rowGTWO) :
			$WO_DPPER	= $rowGTWO->WO_DPPER;
			$WO_DPVAL	= $rowGTWO->WO_DPVAL;				
			$WO_VALUE	= $rowGTWO->WO_VALUE;
			$WO_VALPPN	= $rowGTWO->WO_VALPPN;
			$WO_VALUET	= $WO_VALUE + $WO_VALPPN;
		endforeach;*/

	// CARI SISA OPNAME UNTUK MENGHITUNG PENGEMBALIAN DP : TOTWO_AMN, TOTWO_VOL, TOTOPN_AMN, TOTOPN_VOL, REMOPN_AMN = OPNH_AMOUNT
		$TOTWO_AMN	= 0;
		$TOTWO_VOL	= 0;
		$sqlTOT_WO	= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWO_AMN, SUM(A.WO_VOLM) AS TOTWO_VOL
						FROM tbl_wo_detail A
						INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE'";
		$resTOT_WO		= $this->db->query($sqlTOT_WO)->result();
		foreach($resTOT_WO as $rowTOT_WO) :
			$TOTWO_AMN	= $rowTOT_WO->TOTWO_AMN;
			$TOTWO_VOL	= $rowTOT_WO->TOTWO_VOL;
		endforeach;

		$TOTOPN_AMN	= 0;
		$TOTOPN_VOL	= 0;
		$sqlTOT_OPN	= "SELECT SUM(DISTINCT A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
							SUM(DISTINCT A.OPND_VOLM) AS TOTOPN_VOL
						FROM tbl_opn_detail A
						INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
							AND B.PRJCODE = '$PRJCODE'
						WHERE B.WO_NUM = '$WO_NUMX'
							AND B.OPNH_TYPE = 0
							AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT = '3' AND A.OPNH_NUM != '$OPNH_NUM'";
		$resTOT_OPN		= $this->db->query($sqlTOT_OPN)->result();
		foreach($resTOT_OPN as $rowTOT_OPN) :
			$TOTOPN_AMN	= $rowTOT_OPN->TOTOPN_AMN;
			$TOTOPN_VOL	= $rowTOT_OPN->TOTOPN_VOL;
			if($TOTOPN_AMN == '')
				$TOTOPN_AMN	= 0;
			if($TOTOPN_VOL == '')
				$TOTOPN_VOL	= 0;
		endforeach;

		$REMOPN_AMN		= $TOTWO_AMN - $TOTOPN_AMN;
		//$OPNH_AMOUNT 	= $REMOPN_AMN;

	// CARI DOKUMEN DP UNTUK SPK INI JIKA ADA : WO_DPVAL, WO_DPVALUS, OPNH_DPPER, OPNH_DPVAL
		/*$OPNH_DPPER	= $WO_DPPER;							// PERSENTASE DP
		$OPNH_DPVAL	= $WO_DPPER * $WO_DPVAL / 100;			// NILAI DP DARI SISA OPNAME (DP BACK SAAT INI)
		$OPNH_DPVAL	= $OPNH_DPPER * $WO_DPVAL / 100;			// NILAI DP DARI SISA OPNAME (DP BACK SAAT INI)*/

	// CARI TOTAL PENGEMBALIAN DP : OPNH_TDPVAL
		$OPNH_TDPVAL	= 0;
		$sqlGTOPN		= "SELECT SUM(OPNH_DPVAL) AS TOT_DPVAL
							FROM tbl_opn_header WHERE WO_NUM = '$WO_NUMX'
							AND PRJCODE = '$PRJCODE' AND OPNH_STAT IN (3,6) AND OPNH_NUM != '$OPNH_NUM'";
		$resGTOPN		= $this->db->query($sqlGTOPN)->result();
		foreach($resGTOPN as $rowGTOPN) :
			$OPNH_TDPVAL	= $rowGTOPN->TOT_DPVAL;			// NILAI TOTAL PENGEMBALIAN DP
		endforeach;

	// CARI SISA PENGEMBALIAN DP : OPNH_DPVAL
		$OPNH_REMPV	= $WO_DPVAL - $OPNH_TDPVAL;				// SISA DP
		$OPNH_REMP 	= round($OPNH_REMPV,0);
		/*if($OPNH_REMP > $OPNH_DPVAL)
		{
			$OPNH_DPVAL	= $OPNH_DPVAL;
		}
		else
		{
			$OPNH_DPVAL	= $OPNH_REMP;
		}*/
		if($OPNH_DPVAL > $OPNH_REMP)
		{
			$OPNH_DPVAL	= $OPNH_REMP;
		}

		if($WO_DPPER == 0)
			$OPNH_DPVAL	= 0;

	// CONCLUSION
		$WO_VALUE 			= $WO_VALUE;
		$WO_VALPPNP  		= $WO_VALPPN / $WO_VALUE * 100;
		$TOTOPN_AMN 		= $TOTOPN_AMN;
		$OPNH_AMOUNT 		= $OPNH_AMOUNT;
		$OPNH_AMOUNTPPNP 	= $OPNH_AMOUNTPPNP;
		$OPNH_AMOUNTPPN 	= $OPNH_AMOUNTPPN;
		$DPVAL_WO 			= $WO_DPVAL;
		$DPVAL_REM 			= $OPNH_REMP;
		//$OPNH_DPVAL 		= $WO_DPPER * $OPNH_AMOUNT / 100;
		if($DPVAL_REM <= 0)
			$OPNH_DPVAL 	= 0;
}
	
$isDisabled = 0;

$TOT_PPH	= 0;
$sqlWOD		= "SELECT SUM(A.TAXPRICE2) AS TOT_PPH
				FROM tbl_wo_detail A
				INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
				WHERE B.WO_NUM = '$WO_NUMX' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
$resWOD 	= $this->db->query($sqlWOD)->result();
foreach($resWOD as $rosWOD):
	$TOT_PPH	= $rosWOD->TOT_PPH;
endforeach;

$WO_STARTD 	= date('d/m/Y');
$WO_ENDD 	= date('d/m/Y');
$sqlWOH1	= "SELECT WO_CODE, WO_STARTD, WO_ENDD FROM tbl_wo_header WHERE WO_NUM = '$WO_NUMX'";
$resWOH1 	= $this->db->query($sqlWOH1)->result();
foreach($resWOH1 as $rosWOH1):
	$WO_CODE	= $rosWOH1->WO_CODE;
	$WO_STARTD	= date('d/m/Y', strtotime($rosWOH1->WO_STARTD));
	$WO_ENDD	= date('d/m/Y', strtotime($rosWOH1->WO_ENDD));
endforeach;

$OPNH_TOTAMOUNT		= $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH - $OPNH_RETAMN - $OPNH_DPVAL - $OPNH_POT;
$OPNH_TOTAMOUNTX	= $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH - $OPNH_RETAMN - $OPNH_DPVAL - $OPNH_POT;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
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

		<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/pbar/css/cssprogress.css'; ?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

	<?php
		$this->load->view('template/mna');
		//______$this->load->view('template/topbar');
		//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];
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
			if($TranslCode == 'OpnNo')$OpnNo = $LangTransl;
			if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
			if($TranslCode == 'RequestCode')$RequestCode = $LangTransl;
			if($TranslCode == 'Code')$Code = $LangTransl;
			if($TranslCode == 'Notes')$Notes = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'AlertRevisionorReject')$AlertRevisionorReject = $LangTransl;
			if($TranslCode == 'New')$New = $LangTransl;
			if($TranslCode == 'Confirm')$Confirm = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
			if($TranslCode == 'SPKQty')$SPKQty = $LangTransl;
			if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
			if($TranslCode == 'RequestNow')$RequestNow = $LangTransl;
			if($TranslCode == 'Quantity')$Quantity = $LangTransl;
			if($TranslCode == 'Unit')$Unit = $LangTransl;
			if($TranslCode == 'Primary')$Primary = $LangTransl;
			if($TranslCode == 'Secondary')$Secondary = $LangTransl;
			if($TranslCode == 'Remarks')$Remarks = $LangTransl;
			if($TranslCode == 'AddItem')$AddItem = $LangTransl;
			if($TranslCode == 'JobName')$JobName = $LangTransl;
			if($TranslCode == 'ItemQty')$ItemQty = $LangTransl;
			if($TranslCode == 'Search')$Search = $LangTransl;
			if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
			if($TranslCode == 'Supplier')$Supplier = $LangTransl;
			if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
			if($TranslCode == 'Approval')$Approval = $LangTransl;
			if($TranslCode == 'Approved')$Approved = $LangTransl;
			if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
			if($TranslCode == 'rejected')$rejected = $LangTransl;
			if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;

			if($TranslCode == 'Select')$Select = $LangTransl;
    		if($TranslCode == 'Close')$Close = $LangTransl;
    		if($TranslCode == 'NoSPK')$NoSPK = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'StartDate')$StartDate = $LangTransl;
    		if($TranslCode == 'EndDate')$EndDate = $LangTransl;
    		if($TranslCode == 'Description')$Description = $LangTransl;
    		if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
    		if($TranslCode == 'QtyOpnamed')$QtyOpnamed = $LangTransl;
    		if($TranslCode == 'Remain')$Remain = $LangTransl;
    		if($TranslCode == 'WOList')$WOList = $LangTransl;
    		if($TranslCode == 'SPKCost')$SPKCost = $LangTransl;
    		if($TranslCode == 'Price')$Price = $LangTransl;
    		if($TranslCode == 'DPValue')$DPValue = $LangTransl;
    		if($TranslCode == 'DPRem')$DPRem = $LangTransl;
			if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
			if($TranslCode == 'SelectItem')$SelectItem = $LangTransl;
			if($TranslCode == 'ItmList')$ItmList = $LangTransl;
		endforeach;

		if($LangID == 'IND')
		{
			$Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
			$subTitleH	= "Tambah Opname";
			$subTitleD	= "opname proyek";
			$isManual	= "Centang untuk kode manual.";
			$alert1		= "Masukan alasan mengapa dokumen ini di-batalkan/close.";
			$alert2		= "Silahkan pilih nama supplier.";
			$alert3		= "Nilai yang Anda inputkan lebih besar dari sisa.";
			$alert4		= "Nilai yang Anda inputkan lebih besar dari total opname.";
			$alert5		= "Silahkan pilih nomor SPK.";
			$alert6		= "Nilai pengembalian uang muka sudah melebihi batas maksimal.";
			$alert7 	= "Hanya File PDF yang bisa diunggah";
			$alert8 	= "Anda yakin akan menghapus file ini?";
			$alert9 	= "No. Retensi tidak boleh kosong.";
			$alertAcc 	= "Belum diset kode akun penggunaan.";
			$SPeriode	= "Periode Mulai";
			$docalert1	= 'Peringatan';
			$docalert2	= 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
			$alertVOID	= "Tidak dapat dibatalkan. Sudah digunakan oleh Dokumen No.: ";
			$retAlert 	= "No. Retensi sudah digunakan diproyek: ";
		}
		else
		{
			$Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
			$MonthVw 	= $Month[$JournalM-1];
			$docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
			$subTitleH	= "Add Opname";
			$subTitleD	= "project opname";
			$isManual	= "Check to manual code.";
			$alert1		= "Input the reason why you close/void this document.";
			$alert2		= "Please select a supplier.";
			$alert3		= "Amount you inputed is greater than remaining.";
			$alert4		= "Amount you inputed is greater than total opname.";
			$alert5		= "Please select SPK Number.";
			$alert6		= "The value of the down payment has exceeded the maximum limit.";
			$alert7 	= "Only file PDF can be uploaded";
			$alert8 	= "Are you sure want to delete this file?";
			$alert9 	= "Retention number can not empty.";
			$alertAcc 	= "Not set account material usage.";
			$SPeriode	= "Start Periode";
			$docalert1	= 'Warning';
			$docalert2	= 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
			$alertVOID	= "Can not be void. Used by document No.: ";
			$retAlert 	= "Retention number already used in the project: ";
		}

		// VOID CHECKING FUNCTION
			$DOC_NO	= '';
			$sqlTTKC= "tbl_ttk_header A 
						INNER JOIN tbl_ttk_detail B ON B.TTK_NUM = A.TTK_NUM
						WHERE B.TTK_REF1_NUM = '$OPNH_NUM' AND A.TTK_CATEG = 'OPN' AND TTK_STAT NOT IN (5,9)";
			$isUSED	= $this->db->count_all($sqlTTKC);
			if($isUSED > 0)
			{
				$noU	= 0;
				$sqlTTK	= "SELECT A.TTK_CODE FROM tbl_ttk_header A 
							INNER JOIN tbl_ttk_detail B ON B.TTK_NUM = A.TTK_NUM
							WHERE B.TTK_REF1_NUM = '$OPNH_NUM' AND A.TTK_CATEG = 'OPN' AND TTK_STAT NOT IN (5,9)";
				$resTTK	= $this->db->query($sqlTTK)->result();
				foreach($resTTK as $rowTTK):
					$noU	= $noU + 1;
					$DOCNO	= $rowTTK->TTK_CODE;
					if($noU == 1)
						$DOC_NO = $DOCNO;
					else
						$DOC_NO	= $DOC_NO.", ".$DOCNO;
				endforeach;
			}

		$secAddURL	= site_url('c_project/c_spk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		$secGenCode	= base_url().'index.php/c_project/c_spk/genCode/'; // Generate Code

		if($PRJHO == 'KTR')
		{
			// if($WO_CATEG == 'U') $MenuApp 	= "MN503";
			// elseif($WO_CATEG == 'S') $MenuApp 	= "MN504";
			// elseif($WO_CATEG == 'O') $MenuApp 	= "MN505";
			// elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
			// else $MenuApp 	= "MN513";

			$s_DIV 	= "SELECT DISTINCT B.JOBCOD1 FROM tbl_opn_detail A INNER JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.WO_NUM = '$WO_NUM'";
			$r_DIV 	= $this->db->query($s_DIV)->result();
			foreach($r_DIV as $rw_DIV):
				$MenuAppA 	= $rw_DIV->JOBCOD1;
				if($MenuAppA == 'MN437')				// Sekret
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN532";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN533";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN534";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN438')			// Audit
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN535";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN536";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN537";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN439')			// Corp. L1
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN538";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN539";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN540";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN440')			// Corp. L2
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN541";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN542";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN543";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN441')			// QHSSE-SI
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN544";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN545";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN546";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN442')			// Marketing
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN547";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN548";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN549";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN443')			// Operasi
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN550";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN551";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN552";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN444')			// Keuangan
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN553";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN554";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN555";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN445')			// HRD
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN556";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN557";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN558";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}
				elseif($MenuAppA == 'MN446')			// SPK Anak Usaha
				{
					if($WO_CATEG == 'U') $MenuApp 	= "MN559";
					elseif($WO_CATEG == 'S') $MenuApp 	= "MN560";
					elseif($WO_CATEG == 'O') $MenuApp 	= "MN561";
					elseif($WO_CATEG == 'A') $MenuApp 	= "MN513";
					elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
				}

				$s_UPD1 	= "UPDATE tbl_opn_detail SET OPND_DIVID = '$MenuAppA' WHERE OPNH_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($s_UPD1);
			endforeach;
		}

		// echo $MenuApp;
		
		// START : APPROVE PROCEDURE
			if($APPLEV == 'HO')
				$PRJCODE_LEV	= $this->data['PRJCODE_HO'];
			else
				$PRJCODE_LEV	= $this->data['PRJCODE'];
			
			// DocNumber - PO_TOTCOST
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
				$sqlC_App	= "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
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
				$APPROVE_AMOUNT 	= $OPNH_AMOUNT;
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

    <style type="text/css">
		.search-table, td, th {
			border-collapse: collapse;
		}
		.search-table-outter { overflow-x: scroll; }

	    a[disabled="disabled"] {
	        pointer-events: none;
	    }

		label {
			font-size: 10pt;
		}

		.uploaded_area {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}

		.file {
			display: grid;
			grid-template-columns: max-content 1fr;
			grid-template-areas: "iconfile titlefile"
								 "iconfile actfile";
		}

		.iconfile {
			grid-area: iconfile;
			padding-right: 5px;
		}

		.titlefile {
			grid-area: titlefile;
			font-size: 8pt;
		}

		.actfile {
			grid-area: actfile;
			font-size: 8pt;
		}
	</style>
	
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
		<section class="content-header">
			<h1>
			    <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/opname.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo "$subTitleH ($PRJCODE)"; ?>
			    <small><?php echo $PRJNAME; ?></small>
			</h1>
		</section>

		<section class="content">
			<div class="row">
				<div class="col-md-12" id="idprogbar" style="display: none;">
					<div class="cssProgress">
				      	<div class="cssProgress">
						    <div class="progress3">
								<div id="progressbarXX" style="text-align: center;">0%</div>
							</div>
							<span class="cssProgress-label" id="information" ></span>
						</div>
				    </div>
				</div>
			</div>

		    <div class="row">
            	<form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
		            <table>
		                <tr>
		                    <td>
		                        <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
		                        <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $PATTCODE; ?>">
		                        <input type="hidden" name="Pattern_Length" id="Pattern_Length" value="<?php echo $Pattern_Length; ?>">
		                        <input type="hidden" name="useYear" id="useYear" value="<?php echo $useYear; ?>">
		                        <input type="hidden" name="useMonth" id="useMonth" value="<?php echo $useMonth; ?>">
		                        <input type="hidden" name="useDate" id="useDate" value="<?php echo $useDate; ?>">
		                        <input type="hidden" name="WODate" id="WODate" value="">
		                    </td>
		                    <td><a class="tombol-date" id="dateClass">Simpan</a></td>
		                </tr>
		            </table>
		        </form>
		        <!-- Mencari Kode Purchase Request Number -->
		        <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
		            <input type="text" name="WO_NUMX" id="WO_NUMX" class="textbox" value="<?php echo $WO_NUMX; ?>" />
		            <input type="text" name="task" id="task" class="textbox" value="<?php echo $task; ?>" />
		            <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
		        </form>
		        <!-- End -->
		        
		        <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateInData()">
					<?php
                        // START : LOCK PROCEDURE
                            $app_stat   = $this->session->userdata['app_stat'];
                            if($LangID == 'IND')
                            {
                                $appAlert1  = "Terkunci!";
                                $appAlert2  = "Mohon maaf, saat ini transaksi bulan $MonthVw $JournalY sedang terkunci.";
                            }
                            else
                            {
                                $appAlert1  = "Locked!";
                                $appAlert2  = "Sorry, the transaction month $MonthVw $JournalY is currently locked.";
                            }
                            ?>
                                <input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
                                <div class="col-sm-12" id="divAlert" style="display:none;">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h4><i class="icon fa fa-ban"></i> <?php echo $appAlert1; ?>!</h4>
                                                <?php echo $appAlert2; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        // END : LOCK PROCEDURE
                    ?>
					<div class="col-md-6">
						<div class="box box-primary">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-cloud-upload"></i>
								<h3 class="box-title">&nbsp;</h3>
							</div>
							<div class="box-body">
		            			<input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
					            <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
					            <input type="hidden" name="isUSED" id="isUSED" value="<?php echo $isUSED; ?>">
					            <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
					            <input type="Hidden" name="rowCount" id="rowCount" value="0">
		                    	<input type="hidden" name="OPNH_NUM" id="OPNH_NUM" value="<?php echo $OPNH_NUM; ?>" />
		                    	<input type="hidden" name="PRJCODE" id="PRJCODE"  value="<?php echo $PRJCODE; ?>" />
				                <input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>">
		                        <?php if($isSetDocNo == 0) { ?>
					                <div class="form-group">
					                    <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
					                    <div class="col-sm-9">
					                        <div class="alert alert-danger alert-dismissible">
					                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                            <h4><i class="icon fa fa-ban"></i> <?php echo $docalert1; ?>!</h4>
					                            <?php echo $docalert2; ?>
					                        </div>
					                    </div>
					                </div>
				                <?php } ?>

				                <!-- OPNH_CODE -->
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
				                    <div class="col-sm-4">
				                        <input type="text" class="form-control" style="text-align:left" id="OPNH_CODE" name="OPNH_CODE" size="5" value="<?php echo $OPNH_CODE; ?>" readonly />
				                        <label style="display:none;">
				                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
				                        </label>
				                        <label style="font-style:italic;display:none;">
				                            <?php echo $isManual; ?>
				                        </label>
				                    </div>
									<!-- OPNH_RETNO -->
									<?php
										$OPNH_RETNOV 		= strtoupper(substr(md5($OPNH_RETNO),-6));
										if(trim($OPNH_RETNO) == '')
											$OPNH_RETNOV 	= "";
									?>
									<label for="inputName" class="col-sm-2 control-label"><?php echo "No. Retensi"; ?></label>
				                    <div class="col-sm-4">
				                    	<div class="input-group">
						                  	<div class="input-group-addon">
						                    	<i class="fa fa-eye-slash" style="cursor: pointer" onclick="showNum()"></i>
						                  	</div>
						                  	<input type="text" class="form-control" style="text-align:left" id="OPNH_RETNO" name="OPNH_RETNO" size="5" placeholder="No. Retensi" value="<?php echo $OPNH_RETNOV; ?>" <?php if($OPNH_RETPERC == 0) echo "disabled"; ?> />
						                </div>
				                    </div>
				                </div>

				                <script>
				                	function showNum()
				                	{
							            swal(
							            {
							                text: 'Masukan kata kunci Anda ... !',
							            	content: {
												element: "input",
												attributes: {
													placeholder: "kata kunci",
													type: "password",
												},
											},
							                type: "password",
							                button: 
							                {
							                    text: "OK",
							                    closeModal: false,
							                }
							            })
							            .then(pKey => {
							            	var docnum	= "<?=$OPNH_NUM?>";
							                var urlPass = "<?php echo site_url('__l1y/chkPirvKey') ?>";
							                collData    = docnum+'~'+pKey;
							                if(pKey == '')
							                {
					                        	swal('Anda belum memasukan kata kunci apapun', 
												{
													icon: "error",
												});
							                }
							                else
							                {
								                $.ajax({
								                    type: 'POST',
								                    url: urlPass,
								                    data: "collData="+collData,
								                    success: function(isRespon)
								                    {
								                    	myarr 	= isRespon.split("~");
								                        isR 	= myarr[0];
								                        Info 	= myarr[1];

								                    	if(isR == 0)
								                    	{
								                        	swal(Info, 
															{
																icon: "warning",
															});
								                    	}
								                        else
								                        {
								                        	swal(Info, 
															{
																icon: "success",
															});
								                        }
								                    }
								                });
								            }
								        });
				                	}
				                </script>

				                <!-- OPNH_DATESP, OPNH_DATE -->
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Date; ?></label>
				                    <div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATE" class="form-control pull-left" id="datepicker" value="<?php echo $OPNH_DATE; ?>" readonly></div>
				                    </div>
									<div class="col-sm-6">
				                        <div class="input-group">
				                            <div class="input-group-btn">
				                                <button type="button" class="btn btn-primary" onClick="pleaseCheck();"><?php echo "&nbsp;&nbsp;Cari SPK&nbsp;&nbsp;"; ?> </button>
				                            </div>
				                            <input type="hidden" class="form-control" name="WO_NUM" id="WO_NUM" style="max-width:160px" value="<?php echo $WO_NUMX; ?>" >
				                            <input type="hidden" class="form-control" name="WO_CODE" id="WO_CODE" style="max-width:160px" value="<?php echo $WO_CODE; ?>" >
				                            <input type="hidden" class="form-control" name="WO_CATEG" id="WO_CATEG" style="max-width:160px" value="<?php echo $WO_CATEG; ?>" >
				                            <input type="hidden" class="form-control" name="WO_STARTD" id="WO_STARTD" style="max-width:160px" value="<?php echo $WO_STARTD; ?>" >
				                            <input type="hidden" class="form-control" name="WO_ENDD" id="WO_ENDD" style="max-width:160px" value="<?php echo $WO_ENDD; ?>" >
				                            <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo $WO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?> style="display: none;">

		                                    <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo "$WO_CODE"; ?>" onClick="pleaseCheck();">
		                                    <input type="hidden" class="form-control" name="WO_NUM2" id="WO_NUM2" value="<?php echo $WO_CODE; ?>" data-toggle="modal" data-target="#mdl_addJList">
				                        </div>
				                    </div>
				                </div>
								<?php
				                    $selSPKNo	= site_url('c_project/c_o180d0bpnm/popupallOPNH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
				                ?>
				                <script>
				                    var url1 = "<?php echo $selSPKNo;?>";
				                    function pleaseCheck()
				                    {
				                        document.getElementById('WO_NUM2').click();
				                    }
								</script>

								<div class="form-group">
									<label for="inputName" class="col-sm-2 control-label">Periode</label>
									<div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATESP" class="form-control pull-left" id="datepicker1" value="<?php echo $OPNH_DATESP; ?>"></div>
				                    </div>
									<label for="inputName" class="col-sm-2 control-label">s.d.</label>
									<div class="col-sm-4">
				                    	<div class="input-group date">
				                        <div class="input-group-addon">
				                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATEEP" class="form-control pull-left" id="datepicker2" value="<?php echo $OPNH_DATEEP; ?>"></div>
				                    </div>
								</div>

				                <!-- SPLCODE -->
				                <div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
				                    <div class="col-sm-10">
				                    	<select name="SPLCODE" id="SPLCODE" class="form-control select2">
				                          <option value="none">---</option>
				                          <?php
				                            	$sqlSpl	= "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1' ORDER BY SPLDESC ASC";
												$sqlSpl	= $this->db->query($sqlSpl)->result();
												foreach($sqlSpl as $row) :
													$SPLCODE1	= $row->SPLCODE;
													$SPLDESC1	= $row->SPLDESC;
													?>
														<option value="<?php echo "$SPLCODE1"; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
															<?php echo "$SPLDESC1 - $SPLCODE1"; ?>
														</option>
													<?php
												endforeach;
				                            ?>
				                        </select>
				                    </div>
				                </div>

				                <!-- OPNH_NOTE -->
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
				                    <div class="col-sm-10">
				                    	<textarea name="OPNH_NOTE" id="OPNH_NOTE" class="form-control"><?php echo $OPNH_NOTE; ?></textarea>
				                    </div>
				                </div>

				                <!-- OPNH_NOTE2 -->
				            	<div class="form-group">
				                    <label for="inputName" class="col-sm-2 control-label">Cat. Penyetuju</label>
				                    <div class="col-sm-10">
				                    	<textarea name="OPNH_NOTE2" class="form-control" id="OPNH_NOTE2" readonly><?php echo $OPNH_NOTE2; ?></textarea>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </div>

					<div class="col-md-6">
						<div class="box box-warning">
							<div class="box-header with-border" style="display: none;">
								<i class="fa fa-dollar"></i>
								<h3 class="box-title">Informasi Keuangan</h3>
							</div>
							<div class="box-body">
								<?php
									$WO_VALUEP 	= $WO_VALUE;
									if($WO_VALUE == 0)
										$WO_VALUEP 	= 1;
									$percOpn 	= $TOTOPN_AMN / $WO_VALUEP * 100;
									
								?>
				              	<div class="row">
					                <div class="col-xs-4">
					                 	<label for="exampleInputEmail1"><?php echo $SPKCost; ?></label>
					                </div>
					                <div class="col-xs-4">
					                  	<label for="exampleInputEmail1">Diopname ( <?php echo number_format($percOpn,2); ?> % )</label>
					                </div>
					                <div class="col-xs-4">
					                  	<label for="exampleInputEmail1">Opn. saat ini ( Total: <span id="percOpn"><?php echo number_format($percOpn,2); ?></span>% )</label>
					                </div>
				              	</div>

				              	<!-- WO_VALUE, TOTOPN_AMN, OPNH_AMOUNT -->
				              	<div class="row">
					                <div class="col-xs-4">
					                 	<input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="WO_VALUE" id="WO_VALUE" value="<?php echo $WO_VALUE; ?>" >
				                        <input type="text" class="form-control" style="text-align:right" name="WO_VALUEX" id="WO_VALUEX" value="<?php echo number_format($WO_VALUE, 2); ?>" readonly >
					                </div>
					                <div class="col-xs-4">
					                 	<input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="TOTOPN_AMN" id="TOTOPN_AMN" value="<?php echo $TOTOPN_AMN; ?>" >
				                        <input type="text" class="form-control" style="text-align:right" name="TOTOPN_AMN" id="TOTOPN_AMNx" value="<?php echo number_format($TOTOPN_AMN, 2); ?>" readonly >
					                </div>
					                <div class="col-xs-4">
				                        <input type="hidden" class="form-control" style="text-align:right" name="OPNH_AMOUNT" id="OPNH_AMOUNT" value="<?php echo $OPNH_AMOUNT; ?>" readonly >
				                        <input type="text" class="form-control" style="text-align:right" name="OPNH_AMOUNTX" id="OPNH_AMOUNTX" value="<?php echo number_format($OPNH_AMOUNT, 2); ?>" readonly >
					                </div>
				              	</div>
				              	<br>

				              	<div class="row">
					                <div class="col-xs-4">
					                  	<label for="exampleInputEmail1"><?=$DPRem?></label>
					                </div>
					                <div class="col-xs-4">
					                  	<label for="exampleInputEmail1">Pengemb. DP</label>
					                </div>
					                <div class="col-xs-4">
					                 	<label for="exampleInputEmail1">Retensi</label>
					                </div>
				              	</div>

				              	<div class="row">
				              		<!-- WO_DPVAL, OPNH_REMP, OPNH_DPPER, OPNH_DPVAL -->
				              		<div class="col-xs-4">
				              			<label>
					              			<input type="hidden" class="form-control" style="text-align:right;" name="DPVAL_WO" id="DPVAL_WO" value="<?php echo $DPVAL_WO; ?>">
				                            <input type="hidden" class="form-control" style="max-width:160px; text-align:right;" name="DPVAL_WOX" id="DPVAL_WOX" value="<?php echo number_format($DPVAL_WO, $decFormat); ?>" readonly>
				                            <input type="hidden" class="form-control" style="text-align:right;" name="DPVAL_REM" id="DPVAL_REM" value="<?php echo $DPVAL_REM; ?>">
				                            <input type="text" class="form-control" style="max-width:200px; text-align:right;" name="DPVAL_REMX" id="DPVAL_REMX" value="<?php echo number_format($DPVAL_REM, $decFormat); ?>" readonly>
						                </label>
				              		</div>
				              		<!-- OPNH_DPPER, OPNH_DPVAL -->
				              		<div class="col-xs-4">
				              			<label>
					              			<input type="hidden" class="form-control" style="text-align:right;" name="OPNH_DPPER" id="OPNH_DPPER" value="<?php echo $OPNH_DPPER; ?>">
				                            <input type="text" class="form-control" style="max-width:60px; text-align:right;" name="OPNH_DPPERX" id="OPNH_DPPERX" value="<?php echo number_format($OPNH_DPPER, $decFormat); ?>" onBlur="getDPPER()" >
						                </label>
						                <label>
					                        <input type="hidden" class="form-control" style="text-align:right;" name="OPNH_DPVAL" id="OPNH_DPVAL" value="<?php echo $OPNH_DPVAL; ?>">
				                            <input type="text" class="form-control" style="max-width:125px; text-align:right;" name="OPNH_DPVALX" id="OPNH_DPVALX" value="<?php echo number_format($OPNH_DPVAL, $decFormat); ?>" onBlur="getDPVAL(this)" >
						                </label>
				              		</div>
				              		<!-- OPNH_RETPERC, OPNH_RETAMN -->
				              		<div class="col-xs-4">
				              			<label>
					              			<input type="hidden" class="form-control" style="text-align:right" name="OPNH_RETPERC" id="OPNH_RETPERC" value="<?php echo $OPNH_RETPERC; ?>" >
						                    <input type="text" class="form-control" style="max-width: 60px; text-align:right" name="OPNH_RETPERCX" id="OPNH_RETPERCX" value="<?php echo number_format($OPNH_RETPERC, 2); ?>" onBlur="getRETAMN()" onKeyPress="return isIntOnlyNew(event);" >
						                </label>
						                <label>
					                        <input type="hidden" class="form-control" style="text-align:right" name="OPNH_RETAMN" id="OPNH_RETAMN" value="<?php echo $OPNH_RETAMN; ?>" >
					                        <input type="text" class="form-control" style="max-width: 125px; text-align:right" name="OPNH_RETAMNX" id="OPNH_RETAMNX" value="<?php echo number_format($OPNH_RETAMN, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
						                </label>
				              		</div>
				              	</div>
				              	<br>

				              	<div class="row">
					                <div class="col-xs-5">
					                 	<label for="exampleInputEmail1">PPn</label>
					                </div>
					                <div class="col-xs-3">
					                  	<label for="exampleInputEmail1">Pot. PPh</label>
					                </div>
					                <div class="col-xs-4">
					                  	<label for="exampleInputEmail1">Grand Total</label>
					                </div>
				              	</div>

								<?php
									$sqlLO	= "SELECT A.Base_Debet FROM tbl_journaldetail A
												WHERE A.Journal_DK = 'D' AND A.JournalH_Code = '$OPNH_POTREF' LIMIT 1";
									$resLO	= $this->db->query($sqlLO)->result();
									$totRow	= 0;
									$REM_AMOUNT	= 0;
									foreach($resLO as $row) :
										$Base_Debet 	= $row->Base_Debet;

										// CARI SUDAH TEROPNAME
											$TOT_PAID	= 0;
											$sqlTOT	= "SELECT SUM(OPNH_POT) AS TOT_PAID FROM tbl_opn_header
														WHERE OPNH_POTREF = '$OPNH_POTREF'
															AND OPNH_STAT IN (3,6)";
											$resTOT	= $this->db->query($sqlTOT)->result();
											foreach($resTOT as $row) :
												$TOT_PAID 	= $row->TOT_PAID;	// 0
											endforeach;
										$REM_AMOUNT	= $Base_Debet - $TOT_PAID;
									endforeach;
				                ?>

				              	<!-- OPNH_AMOUNTPPNP, OPNH_AMOUNTPPN, OPNH_TOTAMOUNT -->
				              	<div class="row">
				              		<div class="col-xs-5">
				              			<label>
					              			<input type="hidden" class="form-control" style="text-align:right;" name="OPNH_AMOUNTPPNP" id="OPNH_AMOUNTPPNP" value="<?php echo $OPNH_AMOUNTPPNP; ?>">
				                            <input type="text" class="form-control" style="max-width:60px; text-align:right;" name="OPNH_AMOUNTPPNPX" id="OPNH_AMOUNTPPNPX" value="<?php echo number_format($OPNH_AMOUNTPPNP, $decFormat); ?>" onBlur="getPPnPer()" readonly>
						                </label>
						                <label>
				                            <input type="hidden" class="form-control" style="max-width:150px; text-align:right" name="OPNH_AMOUNTPPN" id="OPNH_AMOUNTPPN" value="<?php echo $OPNH_AMOUNTPPN; ?>" >
				                        	<input type="text" class="form-control" style="max-width:125px; text-align:right" name="OPNH_AMOUNTPPNX" id="OPNH_AMOUNTPPNX" value="<?php echo number_format($OPNH_AMOUNTPPN, 2); ?>" onBlur="getPPnVal(this)" onKeyPress="return isIntOnlyNew(event);" readonly >
						                </label>
				              		</div>
				              		<div class="col-xs-3">
				              			<label>
					              			<input type="text" class="form-control" style="text-align:right;" name="OPNH_AMOUNTPPHX" id="OPNH_AMOUNTPPHX" value="<?php echo number_format($OPNH_AMOUNTPPH, 2); ?>" disabled >
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPHP" id="OPNH_AMOUNTPPHP" value="<?php echo $OPNH_AMOUNTPPHP; ?>">
				                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OPNH_AMOUNTPPH" id="OPNH_AMOUNTPPH" value="<?php echo $OPNH_AMOUNTPPH; ?>">
						                </label>
				              		</div>
				              		<div class="col-xs-4">
				              			<label>
					              			<input type="hidden" class="form-control" style="text-align:right;" name="OPNH_TOTAMOUNT" id="OPNH_TOTAMOUNT" value="<?php echo $OPNH_TOTAMOUNT; ?>">
			                            	<input type="text" class="form-control" style="text-align:right;" name="OPNH_TOTAMOUNTX" id="OPNH_TOTAMOUNTX" value="<?php echo number_format($OPNH_TOTAMOUNT, $decFormat); ?>" disabled>
						                </label>
				              		</div>
				              	</div>
				              	<br>

				                <!-- OPNH_POTREF, OPNH_POTACCID, OPNH_POT, OPNH_REMAIN -->
				                <div class="form-group" style="display: none;">
									<label for="inputName" class="col-sm-3 control-label">Pot. Lainnya</label>
									<div class="col-sm-5">
			                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="OPNH_POTREF" id="OPNH_POTREF" value="<?php echo $OPNH_POTREF; ?>">
			                            <input type="text" class="form-control" style="max-width:250px; text-align:right;" name="OPNH_POTREF1" id="OPNH_POTREF1" value="<?php echo $OPNH_POTREF1; ?>" data-placeholder="Kode DP" onClick="getPOTREF();" readonly>
			                            <input type="hidden" class="form-control" style="max-width:140px; text-align:right;" name="OPNH_POTACCID" id="OPNH_POTACCID" value="<?php echo $OPNH_POTACCID; ?>">
									</div>
									<div class="col-sm-4">
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_POT" id="OPNH_POT" value="<?php echo $OPNH_POT; ?>">
			                            <input type="text" class="form-control" style="text-align:right;" name="OPNH_POTX" id="OPNH_POTX" value="<?php echo number_format($OPNH_POT, $decFormat); ?>" onBlur="getPOT()" readonly>
			                            <input type="hidden" class="form-control" style="max-width:200px; text-align:right;" name="OPNH_REMAIN" id="OPNH_REMAIN" value="<?php echo $REM_AMOUNT; ?>">
									</div>
								</div>

				              	<div class="row">
				                 	<label for="inputName" class="col-sm-2 control-label">Status</label>
			                    	<div class="col-sm-8">
				                    	<input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $OPNH_STAT; ?>">
				                        <select name="OPNH_STAT" id="OPNH_STAT" class="form-control select2" onChange="selStat(this.value)">
											<?php
				                                $disableBtn	= 0;
				                                if($OPNH_STAT == 5 || $OPNH_STAT == 6 || $OPNH_STAT == 9)
				                                {
				                                    $disableBtn	= 1;
				                                }
				                                if($OPNH_STAT != 1 AND $OPNH_STAT != 4)
				                                {
				                                    ?>
				                                        <option value="1"<?php if($OPNH_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
				                                        <option value="2"<?php if($OPNH_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
				                                        <option value="3"<?php if($OPNH_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
				                                        <option value="4"<?php if($OPNH_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
				                                        <option value="5"<?php if($OPNH_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
				                                        <option value="6"<?php if($OPNH_STAT == 6) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Closed</option>
				                                        <option value="7"<?php if($OPNH_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
				                                        <option value="9"<?php if($OPNH_STAT == 9) { ?> selected <?php } if($disableBtn == 1) {?> disabled <?php } ?>>Void</option>
				                                    <?php
				                                }
				                                else
				                                {
				                                    ?>
				                                        <option value="1"<?php if($OPNH_STAT == 1) { ?> selected <?php } ?>>New</option>
				                                        <option value="2"<?php if($OPNH_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
				                                    <?php
				                                }
				                                $theProjCode 	= $PRJCODE;
				                                $url_AddItem	= site_url('c_project/c_spk/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
				                            ?>
				                        </select>
				                    </div>
									<div class="col-sm-2">
				                        <div class="pull-right" style="display: <?php if($task  == 'edit' && ($OPNH_STAT == 1 || $OPNH_STAT == 4)) echo ""; else echo "none";?>;">
				                        	<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdl_addItemWO" onclick="ref4()">
				                        		<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;<?php echo $SelectItem; ?>
				                        	</a>
				                        </div>
				                    </div>
				              	</div>
                                <br>

								<?php
				                    $url_popdp	= site_url('c_project/c_o180d0bpnm/ll_4p/?id=');
				                ?>
				                <script>
				                    var urlDP = "<?php echo "$url_popdp";?>";
				                    function getPOTREF()
				                    {
										PRJCODE	= document.getElementById("PRJCODE").value;
										SPLCODE	= document.getElementById("SPLCODE").value;
										if(SPLCODE == '')
										{
											swal('<?php echo $alert2; ?>');
											document.getElementById("SPLCODE").focus();
											return false;
										}
				                        title = 'Select Item';
				                        w = 850;
				                        h = 550;

				                        var left = (screen.width/2)-(w/2);
				                        var top = (screen.height/2)-(h/2);
										return window.open(urlDP+PRJCODE+'&SPLCODE='+SPLCODE, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
				                    }
				                </script>
				                <div id="revMemo" class="form-group" <?php if($OPNH_MEMO == '') { ?> style="display:none" <?php } ?>>
				                    <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
				                    <div class="col-sm-9">
				                        <textarea name="OPNH_MEMO" class="form-control" style="max-width:350px;" id="OPNH_MEMO" cols="30" disabled><?php echo $OPNH_MEMO; ?></textarea>
				                    </div>
				                </div>

				                <script>
									function selStat(thisValue)
									{
										if(thisValue == 9)
										{
											var isUSED	= document.getElementById('isUSED').value;
											if(isUSED > 0)
											{
												swal('<?php echo $alertVOID; ?>'+' <?php echo $DOC_NO; ?>');
												return false;
											}
											else
											{
												document.getElementById('btnSave').style.display 	= '';
												document.getElementById('revMemo').style.display 	= '';
												document.getElementById('OPNH_MEMO').disabled 		= false;
											}
										}
										else if(thisValue == 3)
										{
											document.getElementById('btnSave').style.display 	= 'none';
											document.getElementById('revMemo').style.display 	= 'none';
											document.getElementById('OPNH_MEMO').disabled 		= true;
										}
									}
								</script>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="box box-default">
							<div class="box-header with-border">
								<label for="inputName"><?php echo $UploadDoc; ?></label>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
              					</div>
							</div>
							<div class="box-body">
								<div class="form-group">
									<div class="col-sm-4">
				                		<input type="file" class="form-control" name="userfile[]" id="userfile" accept=".pdf" multiple>
										<span class="text-muted" style="font-size: 9pt; font-style: italic;">Format File: PDF</span>
				                	</div>
									<div class="col-sm-8">
										<?php
											// GET Upload Doc TRx
											$getUPL_DOC = "SELECT * FROM tbl_upload_doctrx
															WHERE REF_NUM = '$OPNH_NUM' AND PRJCODE = '$PRJCODE'";
											$resUPL_DOC = $this->db->query($getUPL_DOC);
											if($resUPL_DOC->num_rows() > 0)
											{
												?>
													<label>List Uploaded</label>
													<div class="uploaded_area">
												<?php
													$newRow = 0;
													foreach($resUPL_DOC->result() as $rDOC):
														$newRow 		= $newRow + 1;
														$UPL_NUM		= $rDOC->UPL_NUM;
														$REF_NUM		= $rDOC->REF_NUM;
														$REF_CODE		= $rDOC->REF_CODE;
														$UPL_PRJCODE	= $rDOC->PRJCODE;
														$UPL_DATE		= $rDOC->UPL_DATE;
														$UPL_FILENAME	= $rDOC->UPL_FILENAME;
														$UPL_FILESIZE	= $rDOC->UPL_FILESIZE;
														$UPL_FILETYPE	= $rDOC->UPL_FILETYPE;

														?>
															<div class="itemFile_<?=$newRow?>">
																<?php
																	if($UPL_FILETYPE == 'application/pdf') $fileicon = "fa-file-pdf-o";
																	else $fileicon = "fa-file-image-o";

																	if($OPNH_STAT == 1 || $OPNH_STAT == 4)
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- Hapus File -->
																					<a href="#" onclick="trashItemFile(<?=$newRow?>, '<?php echo $UPL_FILENAME;?>')" title="Hapus File">
																						<i class="fa fa-trash" style="color: red;"></i> Delete
																					</a> 
																					&nbsp;&nbsp;&nbsp;
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<a href="<?php echo site_url("c_project/C_o180d0bpnm/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																			
																		<?php
																	}
																	else
																	{
																		?>
																			<div class="file">
																				<div class="iconfile">
																					<!-- View File -->
																					<i class="fa <?=$fileicon?> fa-2x"></i>
																				</div>
																				<div class="titlefile">
																					<?php echo $UPL_FILENAME; ?>
																				</div>
																				<div class="actfile">
																					<!-- View File -->
																					<a href="#" onclick="viewFile('<?php echo $UPL_FILENAME;?>')" title="View File">
																						<i class="fa fa-eye" style="color: green;"></i> View
																					</a>
																					&nbsp;
																					<!-- Download File -->
																					<a href="<?php echo site_url("c_project/C_o180d0bpnm/downloadFile/?file=".$UPL_FILENAME."&prjCode=".$UPL_PRJCODE); ?>" title="Download File">
																						<i class="fa fa-download" style="color: green;"></i> Download
																					</a>
																				</div>
																			</div>
																		<?php
																	}
																?>
															</div>
														<?php
													endforeach;

												?>
													</div>
												<?php
											}
										?>
									</div>
				                </div>
							</div>
						</div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-primary">
	                        <div class="search-table-outter">
	                            <table id="tbl" class="table table-bordered table-striped" width="100%">
		                        	<tr style="background:#CCCCCC">
										<th width="3%" height="25" rowspan="2" style="text-align:left">&nbsp;</th>
										<th width="33%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
										<th colspan="5" style="text-align:center"><?php echo $ItemQty; ?> </th>
										<th rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Unit ?> </th>
										<th width="24%" rowspan="2" style="text-align:center; vertical-align: middle;"><?php echo $Remarks ?> </th>
		                          	</tr>
		                            <tr style="background:#CCCCCC">
		                              	<th style="text-align:center;">SPK </th>
		                              	<th style="text-align:center;"><?php echo $QtyOpnamed ?> </th>
		                              	<th style="text-align:center">% Opname</th>
		                              	<th style="text-align:center">Vol. Opname</th>
		                              	<th style="text-align:center; display: none;"><?=$Price?></th>
		                              	<th style="text-align:center">Total</th>
		                            </tr>
		                            <?php
										if($task == 'add' && $WO_NUMX == '')
										{
											$sqlDETC	= "tbl_wo_detail A
																INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																	AND B.PRJCODE = '$PRJCODE'
															WHERE WO_NUM = '$WO_NUMX'
																AND B.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
										elseif($task == 'add' && $WO_NUMX != '')
										{
											$sqlDETWO	= "SELECT A.WO_ID, A.WO_NUM, A.PRJCODE, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT,
																((A.WO_VOLM - A.OPN_VOLM) / 100) AS OPND_PERC, A.WO_VOLM AS OPND_VOLM,
																A.ITM_PRICE AS OPND_ITMPRICE, A.WO_TOTAL AS OPND_ITMTOTAL, A.WO_DESC AS OPND_DESC,
																A.TAXCODE1, A.TAXPERC1, A.TAXPRICE1, A.TAXCODE2, A.TAXPERC2, A.TAXPRICE2
															FROM tbl_wo_detail A
																INNER JOIN tbl_joblist_detail_$PRJCODEVW B ON A.JOBCODEID = B.JOBCODEID
															WHERE WO_NUM = '$WO_NUMX'
																AND A.PRJCODE = '$PRJCODE'";
											$resDETWO 	= $this->db->query($sqlDETWO)->result();

											$sqlDETC	= "tbl_wo_detail A
															WHERE WO_NUM = '$WO_NUMX'
																AND A.PRJCODE = '$PRJCODE'";
											$resultC 	= $this->db->count_all($sqlDETC);
										}
										else
										{
											if($task == 'edit')
											{
												//*from data
												$sqlDET		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.WO_ID, A.ITM_CODE, A.ITM_UNIT,
																	A.OPND_PERC, A.OPND_VOLM, A.OPND_ITMPRICE, A.OPND_ITMTOTAL, A.OPND_DESC,
																	A.TAXCODE1, A.TAXPERC1, A.TAXPRICE1,
																	A.TAXCODE2, A.TAXPERC2, A.TAXPRICE2,
																	B.WO_NUM, B.PRJCODE
																FROM tbl_opn_detail A
																	INNER JOIN tbl_joblist_detail_$PRJCODEVW C ON C.JOBCODEID = A.JOBCODEID
																	INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																WHERE
																	A.OPNH_NUM = '$OPNH_NUM'
																	AND B.PRJCODE = '$PRJCODE'";
												$resDETWO = $this->db->query($sqlDET)->result();
												// count data
												$sqlDETC	= "tbl_opn_detail A
																	INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
																WHERE
																	A.OPNH_NUM = '$OPNH_NUM'
																	AND B.PRJCODE = '$PRJCODE'";
												$resultC 	= $this->db->count_all($sqlDETC);
											}
										}

										if($resultC > 0)
										{
											$i		= 0;
											$j		= 0;

											foreach($resDETWO as $row) :
												$currentRow  	= ++$i;
												$WO_ID 			= $row->WO_ID;
												$WO_NUM 		= $row->WO_NUM;
												$PRJCODE 		= $row->PRJCODE;
												$JOBCODEDET		= $row->JOBCODEDET;
												$JOBCODEID 		= $row->JOBCODEID;
												$ITM_CODE 		= $row->ITM_CODE;

												$ITM_NAME 		= '';
												$ACC_ID_UM 		= '';
												$ITM_GROUP 		= '';

												$sqlDETITM		= "SELECT A.ITM_NAME, A.ACC_ID_UM, A.ITM_GROUP
																	FROM tbl_item_$PRJCODEVW A
																	WHERE A.ITM_CODE = '$ITM_CODE'
																		AND A.PRJCODE = '$PRJCODE'";
												$resDETITM 		= $this->db->query($sqlDETITM)->result();
												foreach($resDETITM as $detITM) :
													$ITM_NAME 		= $detITM->ITM_NAME;
													$ACC_ID_UM 		= $detITM->ACC_ID_UM;
													$ITM_GROUP 		= $detITM->ITM_GROUP;
												endforeach;

												$JOB_VOLM 	= 0;
												$JOB_BUDG 	= 0;
												$s_JD		= "SELECT (ITM_VOLM+AMD_VOL-AMDM_VOL) AS ITMVOLM, (ITM_BUDG+AMD_VAL-AMDM_VAL) AS ITMBUDG
																FROM tbl_joblist_detail_$PRJCODEVW WHERE JOBCODEID = '$JOBCODEID'";
												$r_JD		= $this->db->query($s_JD)->result();
												foreach($r_JD as $rw_JD) :
													$JOB_VOLM	= $rw_JD->ITMVOLM;
													$JOB_BUDG	= $rw_JD->ITMBUDG;
												endforeach;


												$ITM_UNIT 		= $row->ITM_UNIT;
												$ITM_UNIT		= strtoupper($ITM_UNIT);
												$OPND_PERC 		= $row->OPND_PERC;
												$OPND_VOLM 		= $row->OPND_VOLM;
												$ITM_PRICE 		= $row->OPND_ITMPRICE;
												$OPND_ITMTOTAL	= $row->OPND_ITMTOTAL;
												$TAXCODE1		= $row->TAXCODE1;
												$TAXPERC1		= $row->TAXPERC1;
												$TAXPRICE1		= $row->TAXPRICE1;
												$TAXCODE2		= $row->TAXCODE2;
												$TAXPERC2		= $row->TAXPERC2;
												$TAXPRICE2		= $row->TAXPRICE2;
												$OPND_DESC 		= $row->OPND_DESC;
												$itemConvertion	= 1;

												// TOTAL SPK YANG DIPIIH
													$TOTWO_AMN	= 0;
													$TOTWO_VOL	= 0;
													$sqlTOTWO	= "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWO_AMN, 
																	SUM(A.WO_VOLM) AS TOTWO_VOL
																	FROM tbl_wo_detail A
																	INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
																		AND B.PRJCODE = '$PRJCODE'
																	WHERE A.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE'
																		AND A.PRJCODE = '$PRJCODE'
																		AND A.JOBCODEID = '$JOBCODEID' AND A.WO_ID = $WO_ID";
													$resTOTWO	= $this->db->query($sqlTOTWO)->result();
													foreach($resTOTWO as $rowTOTWO) :
														$TOTWO_AMN	= $rowTOTWO->TOTWO_AMN;
														$TOTWO_VOL	= $rowTOTWO->TOTWO_VOL;
													endforeach;

													$TOTWO_VW 		= $TOTWO_VOL;

													$isLSQ			= "tbl_unitls WHERE ITM_UNIT = '$ITM_UNIT'";
													$isLSR			= $this->db->count_all($isLSQ);

													$JOBVAL 		= $JOB_VOLM;
													if($isLSR > 0)
													{
														//$TOTWO_VOL 	= 1;
														$TOTWO_VOL 	= $TOTWO_VOL;
														$TOTWO_AMN 	= $TOTWO_AMN;
														$JOBVAL 	= $JOB_BUDG;
													}

												// TOTAL OPN APPROVED
													$TOTOPN_AMN	= 0;
													$TOTOPN_VOL	= 0;
													$sqlTOTOPN	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
																		SUM(A.OPND_VOLM) AS TOTOPN_VOL
																	FROM tbl_opn_detail A
																	INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																		AND B.PRJCODE = '$PRJCODE'
																	WHERE B.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE'
																		AND A.PRJCODE = '$PRJCODE'
																		AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT IN (3,6)
																		AND B.OPNH_TYPE = 0
																		AND A.OPNH_NUM != '$OPNH_NUM' AND A.WO_ID = $WO_ID";
													$resTOTOPN		= $this->db->query($sqlTOTOPN)->result();
													foreach($resTOTOPN as $rowTOTOPN) :
														$TOTOPN_AMN		= $rowTOTOPN->TOTOPN_AMN;
														$TOTOPN_VOL		= $rowTOTOPN->TOTOPN_VOL;
														if($TOTOPN_AMN == '')
															$TOTOPN_AMN	= 0;
														if($TOTOPN_VOL == '')
															$TOTOPN_VOL	= 0;
													endforeach;

													$TOTOPN_AMNR	= 0;
													$TOTOPN_VOLR	= 0;
													$sqlTOTOPNR	= "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
																		SUM(A.OPND_VOLM) AS TOTOPN_VOL
																	FROM tbl_opn_detail A
																	INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
																		AND B.PRJCODE = '$PRJCODE'
																	WHERE B.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE'
																		AND A.PRJCODE = '$PRJCODE'
																		AND A.JOBCODEID = '$JOBCODEID' AND B.OPNH_STAT IN (1,2,4,7)
																		AND B.OPNH_TYPE = 0
																		AND A.OPNH_NUM != '$OPNH_NUM' AND A.WO_ID = $WO_ID";
													$resTOTOPNR		= $this->db->query($sqlTOTOPNR)->result();
													foreach($resTOTOPNR as $rowTOTOPNR) :
														$TOTOPN_AMNR		= $rowTOTOPNR->TOTOPN_AMN;
														$TOTOPN_VOLR		= $rowTOTOPNR->TOTOPN_VOL;
														if($TOTOPN_AMNR == '')
															$TOTOPN_AMNR	= 0;
														if($TOTOPN_VOLR == '')
															$TOTOPN_VOLR	= 0;
													endforeach;

													$TOTOPN_VW 		= $TOTOPN_VOL;
										  			$OPNBEF_VW 		= number_format($TOTOPN_VOLR, 2);
													if($isLSR > 0)
													{
														//$TOTOPN_VOL = 1;
														$TOTOPN_VOL = $TOTOPN_VOL;
														$TOTOPN_AMN = $TOTOPN_AMN;
											  			$OPNBEF_VW 	= number_format($TOTOPN_AMNR, 2);
													}

												// SISA OPNAME
													if($isLSR > 0)
													{
														$REMOPN_VOL = $TOTWO_VOL - $TOTOPN_VOL;
														$REMOPN_AMN = $TOTWO_AMN - $TOTOPN_AMN;
													}
													else
													{
														$REMOPN_VOL = $TOTWO_VOL - $TOTOPN_VOL;
														$REMOPN_AMN = $TOTWO_AMN - $TOTOPN_AMN;
													}

													$OPND_ITMTOTAL	= $OPND_VOLM * $ITM_PRICE;

													$TOTWO_VOLP 	= $TOTWO_VOL;
													if($TOTWO_VOL == 0 || $TOTWO_VOL == '')
														$TOTWO_VOLP	= 1;

													if($task == 'add')
													{
														$OPND_VOLM 		= $REMOPN_VOL;
														$OPND_ITMTOTAL 	= $REMOPN_VOL * $ITM_PRICE;
														$OPND_PERC 		= $REMOPN_VOL / $TOTWO_VOLP * 100;
													}

													$disableInp 	= 0;

													if($isLSR > 0)
													{
														if($TOTOPN_AMN >= $TOTWO_AMN)
														{
															$disableInp = 1;
															$REMOPN_VOL	= 0;
														}
													}
													else
													{
														if($TOTOPN_VOL >= $TOTWO_VOL)
														{
															$disableInp = 1;
															$REMOPN_VOL	= 0;
														}
													}

												$TOTWO_VOLP 	= $TOTWO_VOL;
												if($TOTWO_VOLP == 0 || $TOTWO_VOLP == '')
													$TOTWO_VOLP	= 1;

												$REMOPN_PERC 	= $REMOPN_VOL / $TOTWO_VOLP * 100;

												$OPND_POTDP 	= $OPNH_DPPER * $OPND_ITMTOTAL / 100;
												$OPND_ITMTOTAL2 = $OPND_ITMTOTAL;

												$OPND_POTRET 	= $OPNH_RETPERC * $OPND_ITMTOTAL2 / 100;

												$OPND_ITMTOTAL3 = $OPND_ITMTOTAL - $OPND_POTDP - $OPND_POTRET;
												$TAXPRICE1 		= $TAXPERC1 * $OPND_ITMTOTAL3 / 100;
												$TAXPRICE2 		= $TAXPERC2 * $OPND_ITMTOTAL3 / 100;
												$OPND_TOTAL 	= $OPND_ITMTOTAL3 + $TAXPRICE1 - $TAXPRICE2;;

												// GET HEADER JOB
													$TOT_BUDVOL 	= 0;
													$TOT_BUDVAL		= 0;
													$JOBHCODE 		= "";
													$JOBHDESC		= "";
													$sqlHDESC		= "SELECT A.JOBPARENT, B.JOBDESC FROM tbl_joblist_detail_$PRJCODEVW A
																		INNER JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID
																			AND B.PRJCODE = '$PRJCODE'
																		WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
													$resHDESC		= $this->db->query($sqlHDESC)->result();
													foreach($resHDESC as $rowHDESC) :
														$JOBHCODE	= $rowHDESC->JOBPARENT;
														$JOBHDESC	= $rowHDESC->JOBDESC;
													endforeach;

												// CEK USAGE ACCOUNT
													$ItmCol0	= '';
													$ItmCol1	= '';
													$ItmCol2	= '';
													$ttl 		= '';
													$divDesc 	= '';
													$ACC_IDVw 	= '';
													if($ACC_ID_UM == '')
													{
														$disBtn 	= 1;

														$secCopyCOA	= base_url().'index.php/__l1y/CopyCOA/?id=';
														$urlCopyCOA = "$secCopyCOA~$PRJCODE~$ITM_CODE";
														$ACC_IDVw 	= "<input type='hidden' name='urlCopyCOA".$currentRow."' id='urlCopyCOA".$currentRow."' value='".$urlCopyCOA."'>
																		<a href='javascript:void(null);' title='Silahkan klik untuk copy akun dari proyek lain' onClick='copyCOA(".$currentRow.")'><i class='fa fa-repeat'></i></a>";

														$ItmCol0	= '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
														$ItmCol1	= '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
														$ItmCol2	= '</span>';
														$ttl 		= 'Belum disetting kode akun penggunaan';
														$divDesc 	= "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc;
														$isDisabled = 1;
													}

												$disRow 			= 1;
												if($OPNH_STAT == 1 || $OPNH_STAT == 4)
												{
													$disRow 		= 0;
												}

												if($REMOPN_VOL == 0)
												{
													$disRow 		= 1;
												}

												// START : GET MAXIMUM OPNAME
													$PROG_PERC 		= 0;
													$MAX_OPN_VOL 	= $TOTWO_VOL;
													$MAX_OPN_VAL 	= $TOTWO_AMN;
													$PERC_GLOB 		= 100;
													if($ITM_GROUP == 'S')
													{
														$JOBHCODELEV1 	= explode(".", $JOBHCODE);
														//$JOBIDLEV1 	= $JOBHCODELEV1[$JOBHCODELEV1C];
														$JOBIDLEV1 		= $JOBHCODE;

														// 1. DAPATKAN TOTAL VOLUME PEKERJAAN SUBKON DALAM SPK
															$TOT_WOVOL 	= 0;
															$TOT_WOVAL 	= 0;
															$s_TWO		= "SELECT SUM(WO_VOLM) AS TOT_WOVOL, SUM(WO_TOTAL) AS TOT_WOVAL
																			FROM tbl_wo_detail
																			WHERE WO_NUM = '$WO_NUM' AND PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
															$r_TWO		= $this->db->query($s_TWO)->result();
															foreach($r_TWO as $rw_TWO) :
																$TOT_WOVOL 	= $rw_TWO->TOT_WOVOL;
																$TOT_WOVAL 	= $rw_TWO->TOT_WOVAL;
															endforeach;
															if($isLSR > 0)
																$TOTWOVAL 	= $TOT_WOVAL;
															else
																$TOTWOVAL 	= $TOT_WOVOL;

														// 2. DAPATKAN TOTAL PROGRESS MINGGUAN S.D. SAAT INI
															$PROG_BUDG 	= 1;
															$TOT_PROGVAL= 0;
															$s_PROG		= "SELECT PROG_BUDG,
																				IF(SUM(PROG_VAL_EKS)='' OR SUM(PROG_VAL_EKS) IS NULL,0,SUM(PROG_VAL_EKS))
																				AS TOT_PROG_VAL
																			FROM tbl_project_progress_det
																			WHERE JOBCODEID LIKE '$JOBIDLEV1%' AND PRJCODE = '$PRJCODE'";
															$r_PROG		= $this->db->query($s_PROG)->result();
															foreach($r_PROG as $rw_PROG) :
																$PROG_BUDG		= $rw_PROG->PROG_BUDG;
																if($PROG_BUDG == 0)
																{
																	$s_BUDG	= "SELECT BOQ_BOBOT FROM tbl_joblist_detail_$PRJCODEVW A
																					WHERE A.JOBCODEID = '$JOBIDLEV1' AND A.PRJCODE = '$PRJCODE'";
																	$r_BUDG		= $this->db->query($s_BUDG)->result();
																	foreach($r_BUDG as $rw_BUDG) :
																		$PROG_BUDG	= $rw_BUDG->BOQ_BOBOT;
																	endforeach;

																	if($PROG_BUDG == 0)
																		$PROG_BUDG 	= 1;

																}
																	
																$s_BUDGD	= "SELECT SUM(AMD_VOL-AMDM_VOL) AS TOT_AMDVOL, SUM(AMD_VAL-AMDM_VAL) AS TOT_AMDVAL FROM tbl_joblist_detail_$PRJCODEVW A
																				WHERE A.JOBCODEID LIKE '$JOBIDLEV1%' AND A.PRJCODE = '$PRJCODE' AND A.ISLASTH = 1";
																$r_BUDGD	= $this->db->query($s_BUDGD)->result();
																foreach($r_BUDGD as $rw_BUDGD) :
																	$BUDG_AMDVOL	= $rw_BUDGD->TOT_AMDVOL;
																	$BUDG_AMDVAL	= $rw_BUDGD->TOT_AMDVAL;
																endforeach;

																$TOT_PROGVAL	= $rw_PROG->TOT_PROG_VAL;
															endforeach;

															$BUDG_AMDVALP 		= $BUDG_AMDVOL;
															if($isLSR > 0)
																$BUDG_AMDVALP 	= $BUDG_AMDVAL;

															// 2.1 DAPATKAN PERC PROGRESS THD VOL HEADER
																// $PERC_GLOB 		= $TOT_PROGVAL / $PROG_BUDG * 100;
																$PERC_GLOB 			= $TOT_PROGVAL / ($PROG_BUDG + $BUDG_AMDVALP) * 100;

															// 2.2 DAPATKAN PROGRESS THD VOL SPK
																$PROG_JOB 		= $PERC_GLOB * $JOBVAL / 100;
																// echo "$PROG_JOB 		= $PERC_GLOB * $JOBVAL / 100 --- $JOBIDLEV1 === $BUDG_AMDVAL<br>";


														// 3. DAPATKAN TOTAL PEKERJAAN YANG DIPROGRESS TEROPNAME
															$TOT_OPNVOL = 0;
															$TOT_OPNVAL = 0;
															/*$s_PROG		= "SELECT IF(SUM(OPND_VOLM)='' OR SUM(OPND_VOLM) IS NULL,0,SUM(OPND_VOLM)) AS TOT_OPN_VOL,
																			IF(SUM(OPND_ITMTOTAL)='' OR SUM(OPND_ITMTOTAL) IS NULL,0,SUM(OPND_ITMTOTAL))
																				AS TOT_OPN_VAL
																			FROM tbl_opn_detail WHERE JOBCODEID LIKE '$JOBIDLEV1%' AND ITM_GROUP = 'S'
																				AND PRJCODE = '$PRJCODE' AND OPNH_NUM != '$OPNH_NUM' AND OPNH_STAT NOT IN (5,9)";*/
															$s_PROG		= "SELECT IF(SUM(OPND_VOLM)='' OR SUM(OPND_VOLM) IS NULL,0,SUM(OPND_VOLM)) AS TOT_OPN_VOL,
																			IF(SUM(OPND_ITMTOTAL)='' OR SUM(OPND_ITMTOTAL) IS NULL,0,SUM(OPND_ITMTOTAL))
																				AS TOT_OPN_VAL
																			FROM tbl_opn_detail WHERE JOBCODEID LIKE '$JOBCODEID%' AND ITM_GROUP = 'S'
																				AND PRJCODE = '$PRJCODE' AND OPNH_NUM != '$OPNH_NUM' AND OPNH_STAT NOT IN (5,9)";
															$r_PROG		= $this->db->query($s_PROG)->result();
															foreach($r_PROG as $rw_PROG) :
																$TOT_OPNVOL	= $rw_PROG->TOT_OPN_VOL;
																$TOT_OPNVAL	= $rw_PROG->TOT_OPN_VAL;
															endforeach;

														// 4. DAPATKAN SELISI UNTUK MENGHITPUNG PERSENTASE YANG DAPAT DIOPNAME THD VOL. PEK. DLM SPK
															if($isLSR > 0)
															{
																$PROG_PERCNEW 	= ($PROG_JOB - $TOT_OPNVAL) / $TOT_WOVAL * 100;
																//$PROG_PERCNEW 	= round((round($PROG_JOB,4) - round($TOT_OPNVAL,4)) / $TOT_WOVAL * 100,4);
																//$PROG_PERCNEWV 	= "$PROG_PERCNEW 	= ($PROG_JOB - $TOT_OPNVAL) / $TOT_WOVAL * 100";
															}
															else
															{
																$PROG_PERCNEW 	= ($PROG_JOB - $TOT_OPNVOL) / $TOT_WOVOL * 100;
																//$PROG_PERCNEW 	= round((round($PROG_JOB,4) - round($TOT_OPNVOL,4)) / $TOT_WOVOL * 100,4);
																//$PROG_PERCNEWV 	= "$PROG_PERCNEW 	= ($PROG_JOB - $TOT_OPNVOL) / $TOT_WOVOL * 10";
															}

															// echo "$PROG_PERCNEW 	= ($PROG_JOB - $TOT_OPNVAL) / $TOT_WOVAL * 100<br>";

														//echo "$PROG_PERCNEWV<br>";
														/*$JOBHBOBOT 		= 0;
														$TOT_BUDVAL 	= 0;
														$JOBH_BUDG 		= 0;
														$s_BOBOT		= "SELECT (A.ITM_VOLM + A.AMD_VOL) AS TOT_BUDVOL, (A.ITM_BUDG + A.AMD_VAL) AS TOT_BUDVAL,
																				A.ITM_BUDG, A.BOQ_BOBOT FROM tbl_joblist_detail_$PRJCODEVW A
																			WHERE A.JOBCODEID = '$JOBIDLEV1' AND A.PRJCODE = '$PRJCODE'";
														$r_BOBOT		= $this->db->query($s_BOBOT)->result();
														foreach($r_BOBOT as $rw_BOBOT) :
															$TOT_BUDVOL	= $rw_BOBOT->TOT_BUDVOL;
															$TOT_BUDVAL	= $rw_BOBOT->TOT_BUDVAL;
															$JOBH_BUDG	= $rw_BOBOT->ITM_BUDG;
															$JOBHBOBOT	= $rw_BOBOT->BOQ_BOBOT;
														endforeach;

														$s_BOBOT2		= "SELECT (A.ITM_VOLM + A.AMD_VOL) AS TOT_BUDVOL, (A.ITM_BUDG + A.AMD_VAL) AS TOT_BUDVAL,
																				A.ITM_BUDG, A.BOQ_BOBOT FROM tbl_joblist_detail_$PRJCODEVW A
																			WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE'";
														$r_BOBOT2		= $this->db->query($s_BOBOT2)->result();
														foreach($r_BOBOT2 as $rw_BOBOT2) :
															$TOT_BUDVOL	= $rw_BOBOT2->TOT_BUDVOL;
															$TOT_BUDVAL	= $rw_BOBOT2->TOT_BUDVAL;
														endforeach;*/

														/*$TOT_BUDVALP = $TOT_BUDVOL;
														if($isLSR > 0)
															$TOT_BUDVALP = $TOT_BUDVAL;

														if($TOT_BUDVAL == 0)
															$TOT_BUDVALP = 1;*/

														/*$JOBH_BUDGP	= $JOBH_BUDG;
														if($JOBH_BUDG == '' || $JOBH_BUDG == 0)
															$JOBH_BUDGP = 1;*/

														/*$s_PROG		= "SELECT IF(SUM(PROG_BUDG)='' OR SUM(PROG_BUDG) IS NULL,0,SUM(PROG_BUDG)) AS TOT_BUDG,
																			IF(SUM(PROG_VAL_EKS)='' OR SUM(PROG_VAL_EKS) IS NULL,0,SUM(PROG_VAL_EKS)) AS TOT_PROG_VAL
																		FROM tbl_project_progress_det
																		WHERE JOBCODEID LIKE '$JOBIDLEV1%' AND PRJCODE = '$PRJCODE'";
														$r_PROG		= $this->db->query($s_PROG)->result();
														foreach($r_PROG as $rw_PROG) :
															$TOT_BUDG		= $rw_PROG->TOT_BUDG;
															$TOT_PROGVAL	= $rw_PROG->TOT_PROG_VAL;

															$PROG_PERC 		= $TOT_PROGVAL / $TOT_BUDVALP * 100;
															if($isLSR > 0)
																$PROG_PERC 	= $TOT_PROGVAL / $TOT_BUDVALP * 100;

															$MAX_OPN_VOL 	= $PROG_PERC * $TOTWO_VOL / 100;
															$MAX_OPN_VAL 	= $PROG_PERC * $TOTWO_AMN / 100;

															if($TOT_BUDG == 0 && $TOT_PROGVAL > 0)
															{
																$PROG_PERC 		= $TOT_PROGVAL / $JOBHBOBOT * 100;

																$MAX_OPN_VOL 	= $PROG_PERC * $TOTWO_VOL / 100;
																$MAX_OPN_VAL 	= $PROG_PERC * $TOTWO_AMN / 100;
															}
														endforeach;*/

														$MAX_OPN_VOL = $PROG_PERCNEW * $TOTWO_VOL / 100;
														$MAX_OPN_VAL = $PROG_PERCNEW * $TOTWO_AMN / 100;

														/*if($JOBIDLEV1 == 'G.01.01')
														{
															$MAX_OPN_VOL = $REMOPN_VOL;
															$MAX_OPN_VAL = $REMOPN_AMN;
														}*/
													}
												// END : GET MAXIMUM OPNAME
												// echo "$MAX_OPN_VOL = $PROG_PERCNEW * $TOTWO_VOL / 100<br>";
												?>
												<tr id="tr_<?php echo $currentRow; ?>">
													<td height="25" style="text-align: center;">
														<?php
															if($disRow == 0)
															{
																?>
																<a href="#" onClick="deleteRow(<?php echo $currentRow; ?>)" title="Delete Document" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
																<?php
															}
															else
															{
																echo "$currentRow.";
															}
				                                        ?>
														<input type="hidden" id="chk" name="chk" value="<?php echo $currentRow; ?>" width="10" size="15" readonly class="form-control" style="max-width:350px;text-align:right">
														<!-- Checkbox -->
													</td>
													<td style="text-align:left">
														<div style="white-space:nowrap">
														  	<strong><i class='fa fa-cube margin-r-5'></i> <?=$ITM_CODE?> 
																<span class='label label-info pull-right' style='font-size:12px' title='Progres Pengakuan'>
																	<?php echo number_format($PERC_GLOB, $decFormat); ?> %
																</span>
															</strong>
													  		<div>
														  		<p class='text-muted' style='margin-left: 20px'>
														  			<?=$ITM_NAME."<br>"?>
														  			<?=$JOBCODEID." : ".$JOBHDESC?>
														  			<?php echo "$ItmCol1$divDesc$ItmCol2$ACC_IDVw"; ?>
														  		</p>
														  	</div>
													  	</div>
														<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_ID]" id="data<?php echo $currentRow; ?>WO_ID" value="<?php echo $WO_ID; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][WO_NUM]" id="data<?php echo $currentRow; ?>WO_NUM" value="<?php echo $WO_NUM; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_NUM]" id="data<?php echo $currentRow; ?>OPNH_NUM" value="<?php echo $OPNH_NUM; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_CODE]" id="data<?php echo $currentRow; ?>OPNH_CODE" value="<?php echo $OPNH_CODE; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][PRJCODE]" id="data<?php echo $currentRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" >
														<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
														<input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
														<input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID_UM" name="data[<?php echo $currentRow; ?>][ACC_ID_UM]" value="<?php echo $ACC_ID_UM; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;" >
														<!-- Item Name -->
													</td>
													
													<td style="text-align:right" nowrap> <!-- SPK Qty -->
														<span class='label label-success' style='font-size:12px'>
															<?php echo number_format($TOTWO_VOL, $decFormat); ?>
														</span>&nbsp;
														<span class='label label-warning' style='font-size:12px'>
															<?php echo number_format($TOTWO_AMN, $decFormat); ?>
														</span>

														<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="TOTWO_VOLx<?php echo $currentRow; ?>" id="TOTWO_VOLx<?php echo $currentRow; ?>" value="<?php echo number_format($TOTWO_VOL, $decFormat); ?>" disabled >
													  	<input type="hidden" style="text-align:right" name="TOTWO_VOL<?php echo $currentRow; ?>" id="TOTWO_VOL<?php echo $currentRow; ?>" value="<?php echo $TOTWO_VOL; ?>" >
													  	<input type="hidden" style="text-align:right" name="TOTWO_AMN<?php echo $currentRow; ?>" id="TOTWO_AMN<?php echo $currentRow; ?>" value="<?php echo $TOTWO_AMN; ?>" >
													  	<input type="hidden" style="text-align:right" name="REMOPN_VOL<?php echo $currentRow; ?>" id="REMOPN_VOL<?php echo $currentRow; ?>" value="<?php echo $REMOPN_VOL; ?>" >
													  	<input type="hidden" style="text-align:right" name="REMOPN_AMN<?php echo $currentRow; ?>" id="REMOPN_AMN<?php echo $currentRow; ?>" value="<?php echo $REMOPN_AMN; ?>" >
											  		</td>

												  	<td  style="text-align:right"> <!-- Opname Approved Qty-->
												  		<?php
												  			$TOTWO_VOLP = $TOTWO_VOL;
												  			if($TOTWO_VOL == 0)
												  				$TOTWO_VOLP = 1;
												  			$OPNPER 	= $TOTOPN_VOL / $TOTWO_VOLP * 100;
												  			
												  			$PERCOL = "success";
												  			if($OPNPER > 100)
												  				$PERCOL = "danger";

												  			$TOTWO_VOLRP = $TOTWO_VOL;
												  			if($TOTOPN_VOLR == 0)
												  				$TOTWO_VOLRP = 1;
												  			$OPNPERR 	= $TOTOPN_VOLR / $TOTWO_VOLRP * 100;
												  		?>
														<span class='label label-primary' style='font-size:12px'>
															<?php
																echo number_format($TOTOPN_VOL, $decFormat);
															?>
														</span>&nbsp;
														<span class='label label-warning' style='font-size:12px'>
															<?php echo number_format($TOTOPN_AMN, $decFormat); ?>
														</span>&nbsp;
														<span class='label label-<?php echo $PERCOL; ?>' style='font-size:12px'>
															<?php echo number_format($OPNPER, $decFormat); ?> %
														</span>

														<br><br>

														<span class='label label-info' title='Teropname (reserve)' style='font-size:12px; font-style: italic; <?php if($OPNBEF_VW == 0) { ?>display: none; <?php } ?>'>
															<?php echo number_format($TOTOPN_VOLR, $decFormat); ?>
														</span>&nbsp;
														<span class='label label-info' title='Teropname (reserve)' style='font-size:12px; font-style: italic; <?php if($OPNBEF_VW == 0) { ?>display: none; <?php } ?>'>
															<?php echo number_format($TOTOPN_AMNR, $decFormat); ?>
														</span>&nbsp;
														<span class='label label-info' title='Teropname (reserve)' style='font-size:12px; font-style: italic; <?php if($OPNBEF_VW == 0) { ?>display: none; <?php } ?>'>
															<?php echo number_format($OPNPERR, $decFormat); ?> %
														</span>

														<input type="hidden" class="form-control" style="text-align:right" name="TOTOPN_VOL<?php echo $currentRow; ?>" id="TOTOPN_VOL<?php echo $currentRow; ?>" value="<?php print $TOTOPN_VOL; ?>" >
														<input type="hidden" class="form-control" style="text-align:right" name="TOTOPN_AMN<?php echo $currentRow; ?>" id="TOTOPN_AMN<?php echo $currentRow; ?>" value="<?php print $TOTOPN_AMN; ?>" >
														<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="MAX_OPN_VOL<?php echo $currentRow; ?>" id="MAX_OPN_VOL<?php echo $currentRow; ?>" value="<?=$MAX_OPN_VOL?>" >
														<input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="MAX_OPN_VAL<?php echo $currentRow; ?>" id="MAX_OPN_VAL<?php echo $currentRow; ?>" value="<?=$MAX_OPN_VAL?>" >
														<input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTOPN_VOLx<?php echo $currentRow; ?>" id="TOTOPN_VOLx<?php echo $currentRow; ?>" value="<?php print number_format($TOTOPN_VOL, $decFormat); ?>" disabled >
												 	</td>

												 	<td style="text-align:right"> <!-- Opname Percentation Now -->
														<?php if($disRow == 0) { ?>
														  	<input type="text" name="OPND_PERC<?php echo $currentRow; ?>" id="OPND_PERC<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_PERC, 2); ?>" class="form-control" style="min-width:70px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgOPNPERC(this,<?php echo $currentRow; ?>);">
														<?php } else { ?>
															<!-- <span class='label label-success' style='font-size:12px'>
																<?php echo number_format($OPND_PERC, $decFormat); ?>
															</span> -->
															<?php echo number_format($OPND_PERC, $decFormat); ?>
														  	
														  	<input type="hidden" name="OPND_PERC<?php echo $currentRow; ?>" id="OPND_PERC<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_PERC, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgOPNPERC(this,<?php echo $currentRow; ?>);">
														<?php } ?>

														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_PERC]" id="data<?php echo $currentRow; ?>OPND_PERC" value="<?php echo $OPND_PERC; ?>" class="form-control" style="max-width:300px;" >
													</td>

												 	<td style="text-align:right"> <!-- Opname Now -->
														<?php if($disRow == 0) { ?>
														  	<input type="text" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_VOLM, 2); ?>" class="form-control" style="min-width:70px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgOPNVOL(this,<?php echo $currentRow; ?>);">
														<?php } else { ?>
															<!-- <span class='label label-success' style='font-size:12px'>
																<?php echo number_format($OPND_VOLM, $decFormat); ?>
															</span> -->
															<?php echo number_format($OPND_VOLM, $decFormat); ?>
														  	
														  	<input type="hidden" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_VOLM, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgOPNVOL(this,<?php echo $currentRow; ?>);">
														<?php } ?>

														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_VOLM]" id="data<?php echo $currentRow; ?>OPND_VOLM" value="<?php echo $OPND_VOLM; ?>" class="form-control" style="max-width:300px;" >

														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMPRICE]" id="data<?php echo $currentRow; ?>OPND_ITMPRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
														<input type="hidden" style="text-align:right" name="itemConvertion<?php echo $currentRow; ?>" id="itemConvertion<?php echo $currentRow; ?>" value="<?php echo $itemConvertion; ?>" >
													</td>

												 	<td style="text-align:right; display: none;"> <!-- Price Opname Now -->
														<?php if($disRow == 0) { ?>
														  	<input type="text" name="OPND_ITMPRICE<?php echo $currentRow; ?>" id="OPND_ITMPRICE<?php echo $currentRow; ?>" value="<?php echo number_format($ITM_PRICE, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="chgOPNPrc(this,<?php echo $currentRow; ?>);">
														<?php } else { ?>
															<!-- <span class='label label-warning' style='font-size:12px'>
																<?php echo number_format($ITM_PRICE, $decFormat); ?>
															</span> -->
																<?php echo number_format($ITM_PRICE, $decFormat); ?>
														<?php } ?>
													</td>

													<td style="text-align:center" nowrap>
														<?php if($disRow == 0) { ?>
															<input type="text" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);">
														<?php } else { ?>
															<?php echo number_format($OPND_ITMTOTAL, 2); ?>
															<input type="hidden" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_VOLM(this.value,<?php echo $currentRow; ?>);" readonly>
														<?php } ?>
														
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMTOTAL]" id="data<?php echo $currentRow; ?>OPND_ITMTOTAL" value="<?php echo $OPND_ITMTOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_POTDP]" id="data<?php echo $currentRow; ?>OPND_POTDP" value="<?php echo $OPND_POTDP; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_POTRET]" id="data<?php echo $currentRow; ?>OPND_POTRET" value="<?php echo $OPND_POTRET; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" value="<?php echo $TAXCODE1; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC1]" id="data<?php echo $currentRow; ?>TAXPERC1" value="<?php echo $TAXPERC1; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="data<?php echo $currentRow; ?>TAXCODE2" value="<?php echo $TAXCODE2; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPERC2]" id="data<?php echo $currentRow; ?>TAXPERC2" value="<?php echo $TAXPERC2; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="data<?php echo $currentRow; ?>TAXPRICE2" value="<?php echo $TAXPRICE2; ?>" class="form-control" style="max-width:300px;">
														<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_TOTAL]" id="data<?php echo $currentRow; ?>OPND_TOTAL" value="<?php echo $OPND_TOTAL; ?>" class="form-control" style="max-width:300px;" >
				                                    </td>

													<td style="text-align:center" nowrap>
													  	<?php echo $ITM_UNIT; ?>
														<input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
				                                    </td>

													<td style="text-align:left;">
														<?php if($disRow == 0) { ?>
															<input type="text" name="data[<?php echo $currentRow; ?>][OPND_DESC]" id="data<?php echo $currentRow; ?>OPND_DESC" size="20" value="<?php print $OPND_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
														<?php } else { ?>
															<?php echo $OPND_DESC; ?>
															<input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_DESC]" id="data<?php echo $currentRow; ?>OPND_DESC" size="20" value="<?php print $OPND_DESC; ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:left">
														<?php } ?>
														
													</td>
										  		</tr>
											<?php
											endforeach;
										}
									?>
		                            <input type="hidden" name="totalrow" id="totalrow" value="<?php echo $currentRow; ?>">
		                        </table>
		                    </div>
		                </div>
		            </div>

					<div class="col-md-6">
		                <div class="form-group">
		                	<label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
		                    <div class="col-sm-9">
		                    	<?php
									if($task=='add')
									{
										if($ISCREATE == 1 && $isDisabled == 0)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									elseif($task=='edit')
									{
										if($OPNH_STAT == 3 && $isDisabled == 0)
										{
											?>
		                                        <button class="btn btn-primary" style="display:none" id="btnSave">
		                                        <i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
										elseif(($OPNH_STAT == 1 || $OPNH_STAT == 4) && $isDisabled == 0)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									else
									{
										if(($OPNH_STAT == 1 && $ISCREATE == 1) && $isDisabled == 0)
										{
											?>
												<button class="btn btn-primary" id="btnSave">
												<i class="fa fa-save"></i></button>&nbsp;
											<?php
										}
									}
									$backURL	= site_url('c_project/c_o180d0bpnm/gal180d0bopn/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
									echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i></button>');
		                        ?>
		                    </div>
		                </div>
		          	</div>
				</form>
		        <div class="col-md-12">
					<?php
                        $DOC_NUM	= $OPNH_NUM;
                        $sqlCAPPH	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH	= $this->db->count_all($sqlCAPPH);
						$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
									AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
						$resAPP	= $this->db->query($sqlAPP)->result();
						foreach($resAPP as $rowAPP) :
							$MAX_STEP		= $rowAPP->MAX_STEP;
							$APPROVER_1		= $rowAPP->APPROVER_1;
							$APPROVER_2		= $rowAPP->APPROVER_2;
							$APPROVER_3		= $rowAPP->APPROVER_3;
							$APPROVER_4		= $rowAPP->APPROVER_4;
							$APPROVER_5		= $rowAPP->APPROVER_5;
						endforeach;
                    ?>
	                <div class="row">
	                    <div class="col-md-12">
	                        <div class="box box-danger collapsed-box">
	                            <div class="box-header with-border">
	                                <h3 class="box-title"><?php echo $Approval; ?></h3>
	                                <div class="box-tools pull-right">
	                                    <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
	                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                                    </button>
	                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
	                                    </button>
	                                </div>
	                            </div>
	                            <div class="box-body">
						            <div class="box-body no-padding">
		                        		<div class="search-table-outter">
							              	<table id="tbl" class="table table-striped" width="100%" border="0">
												<?php
													$s_STEP		= "SELECT DISTINCT APP_STEP FROM tbl_docstepapp_det
																	WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY APP_STEP";
													$r_STEP		= $this->db->query($s_STEP)->result();
													foreach($r_STEP as $rw_STEP) :
														$STEP	= $rw_STEP->APP_STEP;
														$HIDE 	= 0;
														?>
											                <tr>
											                  	<td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
																<?php
																	$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
								                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
								                                    if($r_APPH_1 > 0)
								                                    {
																		$s_00	= "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
																					WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->AH_APPROVER;
																			$APP_NME_1	= $rw_00->complName;
																			$APP_DAT_1	= $rw_00->AH_APPROVED;

									                                    	$APPCOL 	= "success";
									                                    	$APPIC 		= "check";
																			?>
																				<td style="width: 2%;">
																					<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																						<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																					</div>
																				</td>
																				<td>
																					<?=$APP_NME_1?><br>
																					<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APP_DAT_1?></span>
																				</td>
																			<?php
																		endforeach;
																	}
																	else
																	{
																		$s_00	= "SELECT DISTINCT A.APPROVER_1,
																						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
																					FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
																					WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
																		$r_00	= $this->db->query($s_00)->result();
																		foreach($r_00 as $rw_00) :
																			$APP_EMP_1	= $rw_00->APPROVER_1;
																			$APP_NME_1	= $rw_00->complName;
																			$OTHAPP 	= 0;
																			$s_APPH_1	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                    $r_APPH_1	= $this->db->count_all($s_APPH_1);
										                                    if($r_APPH_1 > 0)
										                                    {
										                                    	$HIDE 	= 1;
										                                    	$s_01	= "SELECT AH_APPROVED FROM tbl_approve_hist
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT	= $rw_01->AH_APPROVED;
										                                        endforeach;

										                                    	$APPCOL 	= "success";
										                                    	$APPIC 		= "check";
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
										                                    }
										                                    else
										                                    {
										                                    	$APPCOL 	= "danger";
										                                    	$APPIC 		= "close";
										                                    	$APPDT 		= "-";
										                                    	$s_APPH_O	= "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
											                                    $r_APPH_O	= $this->db->count_all($s_APPH_O);
											                                    if($r_APPH_O > 0)
											                                    	$OTHAPP = 1;
										                                    }
										                                    if($HIDE == 0)
										                                    {
																				?>
																					<td style="width: 2%;">
																						<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																							<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																						</div>
																					</td>
																					<td>
																						<?=$APP_NME_1?><br>
																						<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT?></span>
																					</td>
																				<?php
																			}

																			if($OTHAPP > 0)
																			{
																				$APPDT_OTH 	= "-";
																				$APPNM_OTH 	= "-";
										                                    	$s_01	= "SELECT A.AH_APPROVED, A.AH_APPLEV,
										                                    					CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
										                                    				FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
										                                    					WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
										                                        $r_01	= $this->db->query($s_01)->result();
										                                        foreach($r_01 as $rw_01):
										                                            $APPDT_LEV	= $rw_01->AH_APPLEV;
										                                            $APPDT_OTH	= $rw_01->AH_APPROVED;
										                                            $APPNM_OTH	= $rw_01->COMPLNAME;

											                                    	$APPCOL 	= "success";
											                                    	$APPIC 		= "check";
																					?>
																		                <tr>
																		                  	<td style="width: 10%" nowrap>&nbsp;</td>
																							<td style="width: 2%;">
																								<div style='white-space:nowrap; font-size: 14px; text-align: center;'>
																									<a class="btn btn-social-icon btn-<?=$APPCOL?>"><i class="fa fa-<?=$APPIC?>"></i></a>
																								</div>
																							</td>
																							<td>
																								<?=$APPNM_OTH?><br>
																								<i class='fa fa-calendar margin-r-5'></i><span style="font-style: italic;"><?=$APPDT_OTH?></span>
																							</td>
																						</tr>
																					<?php
										                                        endforeach;
										                                    }
																		endforeach;
																	}
																?>
															</tr>
														<?php
													endforeach;
												?>
							              	</table>
						              	</div>
						            </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
		        </div>
		    </div>

			<!-- ============ START MODAL ITEM LIST =============== -->
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
				<div class="modal fade" id="mdl_addItemWO" name='mdl_addItemWO' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<ul class="nav nav-tabs">
											<li id="li1" class="active">
												<a href="#itm1" data-toggle="tab"><?php echo $ItmList; ?></a>
											</li>
										</ul>
										<div class="box-body">
											<div class="active tab-pane" id="itm1">
												<div class="form-group">
													<form method="post" name="frmSearch1" id="frmSearch1" action="">
														<div class="search-table-outter">
															<table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
																<thead>
																	<tr>
																		<th width="2%" height="25" style="text-align: center;">&nbsp;</th>
																		<th width="5%" style="text-align:center; vertical-align: middle;"><?php echo $ItemCode ?> </th>
																		<th width="40%" style="text-align:center; vertical-align: middle;"><?php echo $ItemName ?> </th>
																		<th width="3%" style="text-align:center; vertical-align: middle;">Sat.</th>
																		<th width="15%" style="text-align:center; vertical-align: middle;">SPK</th>
																		<th width="20%" style="text-align:center; vertical-align: middle;"><?php echo $QtyOpnamed ?> </th>
																		<th width="15%" style="text-align:center; vertical-align: middle;">Sisa</th>
																	</tr>
																</thead>
																<tbody>
																</tbody>
															</table>
														</div>
														<br>
														<button class="btn btn-primary" type="button" id="btnDetail1" name="btnDetail1">
															<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
														</button>
														<button type="button" id="idClose1" class="btn btn-danger" data-dismiss="modal">
															<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
														</button>
														<button class="btn btn-warning" type="button" id="idRefresh1" >
															<i class="glyphicon glyphicon-refresh"></i>
														</button>
													</form>
												</div>
											</div>
										</div>
										<input type="hidden" name="totalrow4" id="totalrow4" value="0">
										<input type="hidden" name="rowCheck4" id="rowCheck4" value="0">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					
				</script>
	    	<!-- ============ END MODAL ITEM LIST =============== -->

		    <?php
		    	$s_01 	= "tbl_wo_header A
			                    LEFT JOIN  tbl_employee B ON A.WO_CREATER = B.Emp_ID
			                    INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
			                WHERE A.WO_STAT = '3' AND A.PRJCODE = '$PRJCODE'";
			    $r_01 	= $this->db->count_all($s_01);
			    
			    if($r_01 > 0)
			    {
		    		?>
			    	<!-- ============ START MODAL JOBLIST =============== -->
				    	<style type="text/css">
				    		.modal-dialog{
							    position: relative;
							    display: table; /* This is important */ 
							    overflow-y: auto;    
							    overflow-x: auto;
							    width: auto;
							    min-width: 300px;   
							}

							th, td { white-space: nowrap; }
				    	</style>
				    	<?php
							$Active1		= "active";
							$Active2		= "";
							$Active1Cls		= "class='active'";
							$Active2Cls		= "";
				    	?>
				        <div class="modal fade" id="mdl_addJList" name='mdl_addJList' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				            <div class="modal-dialog">
					            <div class="modal-content">
					                <div class="modal-body">
										<div class="row">
									    	<div class="col-md-12">
								              	<ul class="nav nav-tabs">
								                    <li id="li1" <?php echo $Active1Cls; ?>>
								                    	<a href="#itm1" data-toggle="tab"><?php echo $WOList; ?></a>
								                    </li>
								                </ul>
									            <div class="box-body">
									            	<div class="<?php echo $Active1; ?> tab-pane" id="itm2">
				                                        <div class="form-group">
				                                        	<form method="post" name="frmSearch1" id="frmSearch1" action="">
					                                            <table id="example0" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
					                                                <thead>
					                                                    <tr>
													                        <th>&nbsp;</th>
										                                    <th style="vertical-align:middle; text-align:center"><?php echo $NoSPK; ?></th>
										                                    <th style="text-align:center"><?php echo $Description; ?></th>
										                                    <th style="text-align:center"><?php echo "SPK"; ?></th>
										                                    <th style="text-align:center"><?php echo $QtyOpnamed; ?></th>
										                                    <th style="text-align:center"><?php echo $Remain; ?></th>
													                  	</tr>
					                                                </thead>
					                                                <tbody>
					                                                </tbody>
					                                            </table>
		                                                    	<button class="btn btn-primary" type="button" id="btnDetail0" name="btnDetail0">
		                                                    		<i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;<?php echo $Select; ?>
		                                                    	</button>
		                                      					<button type="button" id="idClose0" class="btn btn-danger" data-dismiss="modal">
		                                                    		<i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
		                                                    	</button>
		                                      					<button class="btn btn-warning" type="button" id="idRefresh" title="Refresh" >
		                                                    		<i class="glyphicon glyphicon-refresh"></i>
		                                                    	</button>
				                                            </form>
				                                      	</div>
				                                    </div>
		                                      	</div>
		                                      	<input type="hidden" name="rowCheck0" id="rowCheck0" value="0">
			                                </div>
			                            </div>
					                </div>
						        </div>
						    </div>
						</div>

						<script type="text/javascript">
							$(document).ready(function()
							{
						    	$('#example0').DataTable(
						    	{
							        "processing": true,
							        "serverSide": true,
									//"scrollX": false,
									"autoWidth": true,
									"filter": true,
							        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataWO/?id='.$PRJCODE)?>",
							        "type": "POST",
									//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
									"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
									"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
													{ targets: [3,4,5], className: 'dt-body-right' },
												  ],
									 "order": [[ 1, "asc" ]],
									"language": {
							            "infoFiltered":"",
							            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
							        },
								});

						    	/*$('#example1').DataTable(
						    	{
							        "processing": true,
							        "serverSide": true,
									//"scrollX": false,
									"autoWidth": true,
									"filter": true,
							        "ajax": "<?php echo site_url('c_project/c_o180d0bpnm/get_AllDataITMWO/?id='.$PRJCODE.'&WONUM='.$WO_NUMX.'&OPNHNUM='.$OPNH_NUM)?>",
							        "type": "POST",
									//"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
									"lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
									"columnDefs": [	{ targets: [0], className: 'dt-body-center' },
													{ targets: [3,4,5], className: 'dt-body-right' },
												  ],
									 "order": [[ 1, "asc" ]],
									"language": {
							            "infoFiltered":"",
							            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
							        },
								});*/
							});

							var selectedRows = 0;
							function pickThis0(thisobj) 
							{
								var favorite = [];
								$.each($("input[name='chk0']:checked"), function() {
							      	favorite.push($(this).val());
							    });
							    $("#rowCheck0").val(favorite.length);
							}

							$(document).ready(function()
							{
							   	$("#btnDetail0").click(function()
							    {
									var totChck 	= $("#rowCheck0").val();

									if(totChck == 0)
									{
										swal('<?php echo $alert5; ?>',
										{
											icon: "warning",
										})
										.then(function()
							            {
							                swal.close();
							            });
										return false;
									}

								    $.each($("input[name='chk0']:checked"), function()
								    {
								      	add_header($(this).val());
								    });

								    $('#mdl_addJList').on('hidden.bs.modal', function () {
									    $(this)
										    .find("input,textarea,select")
											    .val('')
											    .end()
										    .find("input[type=checkbox], input[type=radio]")
										       .prop("checked", "")
										       .end();
									});
		                        	document.getElementById("idClose0").click()
							    });

							   	$("#idRefresh").click(function()
							    {
									$('#example0').DataTable().ajax.reload();
							    });

							   	$("#idRefresh1").click(function()
							    {
									$('#example1').DataTable().ajax.reload();
							    });
							});
						</script>
			    	<!-- ============ END MODAL JOBLIST =============== -->
			    	<?php
			    }
                $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefEmp_ID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
		</section>
	</body>
</html>
<?php
	$secGTax 	= base_url().'index.php/__l1y/getTaxP/?id=';
	$tblTax 	= "tbl_tax_ppn";
?>
<script>
	<?php
		// START : GENERATE MANUAL CODE
			if($task == 'add')
			{
				?>
					$(document).ready(function()
					{
						setInterval(function(){addUCODE()}, 1000);
					});
				<?php
			}
		// END : GENERATE MANUAL CODE
	?>

	function addUCODE()
	{
		var task 		= "<?=$task?>";
		var DOCNUM		= document.getElementById('OPNH_NUM').value;
		var DOCCODE		= document.getElementById('OPNH_CODE').value;
		var DOCDATE		= document.getElementById('datepicker').value;
		var ACC_ID		= "";
		var PDManNo 	= "";
		var DOCTYPE 	= "OPN";
		var DOCCAT 		= "<?=$WO_CATEG?>";

		var formData 	= {
							PRJCODE 		: "<?=$PRJCODE?>",
							MNCODE 			: "<?=$MenuCode?>",
							DOCNUM 			: DOCNUM,
							DOCCODE 		: DOCCODE,
							DOCCODE2		: PDManNo,
							DOCDATE 		: DOCDATE,
							ACC_ID 			: ACC_ID,
							DOCTYPE 		: DOCTYPE,
							DOCCAT 			: DOCCAT
						};
		$.ajax({
            type: 'POST',
            url: "<?php echo site_url('__l1y/getLastDocNum')?>",
            data: formData,
            success: function(response)
            {
            	//console.log(response)
            	var arrVar 	= response.split('~');
            	var docNum 	= arrVar[0];
            	var docCode	= arrVar[1];
            	var payCode = arrVar[2];
            	var ACCBAL 	= arrVar[3];

            	$('#OPNH_CODE').val(docCode);
            }
        });
	}


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
		  startDate: '+0d',
		  endDate: '+0d',
		});

		//Date picker
		$('#datepicker1').datepicker({
		  autoclose: true,
		});

		//Date picker
        $('#datepicker2').datepicker({
            autoclose: true
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

		$("#userfile").bind("change", (e) => {
			let files 				= e.target.files;
			//console.log(files);
			// const validExtensions 	= ["image/jpeg", "image/jpg", "image/png", "application/pdf"]; 
			const validExtensions 	= ["application/pdf"]; 
			for(let i = 0; i < files.length; i++) {
				let fileType 	= e.target.files[i].type; //getting selected file type
				if(validExtensions.includes(fileType) == true) {
					//console.log(fileType);
				} else {
					swal("<?=$alert7?>");
					$(this).val('');
				}
			}
		});

		$("#OPNH_RETNO").on("change", (e) => {
			let RET_NO = e.target.value;
			//console.log(RET_NO);
			// START: Check RET NO Exist
				$.ajax({
					url: "<?php echo site_url('c_project/c_o180d0bpnm/chkRETNO'); ?>",
					type: "POST",
					dataType: "JSON",
					data: {RET_NO:RET_NO},
					beforeSend: function(xhr, setting) {
						//console.log(xhr);
					},
					success: function(result) {
						if(result.length > 0)
						{
							//console.log(result);
							swal("<?php echo $retAlert; ?>"+result[0].PRJCODE+", kode: "+result[0].OPNH_CODE, 
							{
								icon: "warning",
							}).then(function(){
								$("#OPNH_RETNO").val("");
								$("#OPNH_RETNO").focus();
							});
						}
					},
					error: function (request, status, error) {
						//console.log(request.responseText);
					}
				})
			// END: Check RET NO Exist
		});

	});

	// START : LOCK PROCEDURE
		$(document).ready(function()
		{
			setInterval(function(){chkAppStat()}, 1000);
		});

		function chkAppStat()
		{
			var url         = "<?php echo site_url('lck/appStat')?>";
			let DOC_DATE 	= $('#datepicker').val();
			//console.log(DOC_DATE);
			
				
			$.ajax({
				type: 'POST',
				url: url,
				data: {DOC_DATE:DOC_DATE},
				dataType: "JSON",
				success: function(response)
				{
					// var arrVar      = response.split('~');
					// var arrStat     = arrVar[0];
					// var arrAlert    = arrVar[1];
					// var LockCateg 	= arrVar[2];
					// var app_stat    = document.getElementById('app_stat').value;

					let LockY		= response[0].LockY;	
					let LockM		= response[0].LockM;	
					let LockCateg	= response[0].LockCateg;	
					let isLockJ		= response[0].isLockJ;	
					let LockJDate	= response[0].LockJDate;	
					let UserJLock	= response[0].UserJLock;	
					let isLockT		= response[0].isLock;	
					let LockTDate	= response[0].LockDate;	
					let UserLockT	= response[0].UserLock;
					//console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

					// if(isLockJ == 1)
					// {
					// 	// $('#alrtLockJ').css('display',''); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#OPNH_STAT>option[value="3"]').attr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }
					// else
					// {
					// 	// $('#alrtLockJ').css('display','none'); // not jurnal
					// 	document.getElementById('divAlert').style.display   = 'none';
					// 	// $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
					// 	document.getElementById('btnSave').style.display    = '';
					// }

					if(isLockT == 1)
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#OPNH_STAT').removeAttr('disabled','disabled');
							// $('#OPNH_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = '';
							// $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#OPNH_STAT').attr('disabled','disabled');
							document.getElementById('btnSave').style.display    = 'none';
						}
					}
					else
					{
						if(LockCateg == 1)
						{
							// $('#alrtLockJ').css('display',''); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							$('#OPNH_STAT').removeAttr('disabled','disabled');
							// $('#OPNH_STAT>option[value="3"]').attr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
						else
						{
							// $('#alrtLockJ').css('display','none'); // not jurnal
							document.getElementById('divAlert').style.display   = 'none';
							// $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
							$('#OPNH_STAT').removeAttr('disabled','disabled');
							// document.getElementById('btnSave').style.display    = '';
						}
					}
				}
			});
		}
    // END : LOCK PROCEDURE

	function add_header(strItem)
	{
		arrItem 	= strItem.split('|');
		ilvl 		= arrItem[1];

		WO_NUM		= arrItem[0];

		document.getElementById("WO_NUMX").value = WO_NUM;
		document.frmsrch.submitSrch.click();
	}

	function getRETAMN()
	{
		OPNH_RETPERC1	= document.getElementById('OPNH_RETPERCX');
		OPNH_RETPERC	= parseFloat(eval(OPNH_RETPERC1).value.split(",").join(""));

		if(OPNH_RETPERC > 0)
		{
			document.getElementById('OPNH_RETNO').disabled = false;
		}
		else
		{
			document.getElementById('OPNH_RETNO').disabled = true;
			document.getElementById('OPNH_RETNO').value = "";
		}


		document.getElementById('OPNH_RETPERC').value 	= OPNH_RETPERC;
		document.getElementById('OPNH_RETPERCX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETPERC)), 2));


		countTotalOpn();
	}

	function getDPPER()
	{
		OPNH_DPPER1		= document.getElementById('OPNH_DPPERX');
		OPNH_DPPER		= parseFloat(eval(OPNH_DPPER1).value.split(",").join(""));

		var TOT_OPNVAL	= 0;
		var totRow 		= document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'OPND_ITMTOTAL');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(theObj != null)
			{
				var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
				var TOT_OPNVAL		= parseFloat(TOT_OPNVAL + OPND_ITMTOTAL);
			}
		}

		OPNH_DPVAL		= parseFloat(OPNH_DPPER * TOT_OPNVAL / 100);

		DPVAL_REM		= parseFloat(document.getElementById('DPVAL_REM').value);
		if(OPNH_DPVAL > DPVAL_REM)
		{
			swal('<?php echo $alert6; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
                swal.close();
                document.getElementById('OPNH_DPPERX').focus();

				document.getElementById('OPNH_AMOUNT').value 	= TOT_OPNVAL;
				document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

                document.getElementById('OPNH_DPPER').value 	= 0;
                document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

				document.getElementById('OPNH_DPVAL').value 	= 0;
				document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));

				countTotalOpn();
            });
		}
		else
		{
			document.getElementById('OPNH_AMOUNT').value 	= TOT_OPNVAL;
			document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

			document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
			document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

			document.getElementById('OPNH_DPVAL').value 	= OPNH_DPVAL;
			document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

			countTotalOpn();
		}
	}

	function getDPVAL(thisVal)
	{
		OPNH_DPVAL		= parseFloat(eval(thisVal).value.split(",").join(""));
		if(OPNH_DPVAL == 0)
		{
            document.getElementById('OPNH_DPPER').value 	= 0;
            document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), 2));
		}

		var TOT_OPNVAL	= 0;
		var totRow 		= document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'OPND_ITMTOTAL');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(theObj != null)
			{
				var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
				var TOT_OPNVAL		= parseFloat(TOT_OPNVAL + OPND_ITMTOTAL);
			}
		}

		DPVAL_REM		= parseFloat(document.getElementById('DPVAL_REM').value);
		//console.log('OPNH_DPVAL = '+OPNH_DPVAL)
		//console.log('DPVAL_REM = '+DPVAL_REM)
		if(OPNH_DPVAL > DPVAL_REM)
		{
			swal('<?php echo $alert6; ?>',
			{
				icon: "warning",
			})
			.then(function()
            {
				//OPNH_DPPER		= parseFloat(DPVAL_REM / TOT_OPNVAL * 100);

				document.getElementById('OPNH_AMOUNT').value 	= TOT_OPNVAL;
				document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

				//document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
				//document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

				document.getElementById('OPNH_DPVAL').value 	= DPVAL_REM;
				document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(DPVAL_REM)), 2));

				countTotalOpn();
            });
		}
		else
		{
			//OPNH_DPPER		= parseFloat(OPNH_DPVAL / TOT_OPNVAL * 100);

			document.getElementById('OPNH_AMOUNT').value 	= TOT_OPNVAL;
			document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

			//document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
			//document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

			document.getElementById('OPNH_DPVAL').value 	= OPNH_DPVAL;
			document.getElementById('OPNH_DPVALX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

			countTotalOpn();
		}
	}

	function chgOPNPERC(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		OPND_PERC		= parseFloat(eval(thisVal1).value.split(",").join(""));

		ITM_PRICE		= parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);
		TOTWO_VOL		= parseFloat(document.getElementById('TOTWO_VOL'+row).value);
		TOTWO_AMN		= parseFloat(document.getElementById('TOTWO_AMN'+row).value);
		TOTOPN_VOL		= parseFloat(document.getElementById('TOTOPN_VOL'+row).value);
		TOTOPN_AMN		= parseFloat(document.getElementById('TOTOPN_AMN'+row).value);
		REMOPN_VOL		= parseFloat(document.getElementById('REMOPN_VOL'+row).value);
		REMOPN_AMN		= parseFloat(document.getElementById('REMOPN_AMN'+row).value);
		ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		ITM_UNIT 		= ITMUNIT.toUpperCase();

		OPND_VOLM		= parseFloat(OPND_PERC * TOTWO_VOL / 100);

		// TOTAL OPNAME NOW
			OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);

		// GET MAXIMUM OPNAME
			MAX_OPN_VOL	= parseFloat(document.getElementById('MAX_OPN_VOL'+row).value);
			MAX_OPN_VAL	= parseFloat(document.getElementById('MAX_OPN_VAL'+row).value);
			/*TOT_OPNVOL 	= parseFloat(TOTOPN_VOL + OPND_VOLM);
			TOT_OPNVAL 	= parseFloat(TOTOPN_AMN + OPND_ITMTOTAL);*/
			TOT_OPNVOL 	= parseFloat(OPND_VOLM);
			TOT_OPNVAL 	= parseFloat(OPND_ITMTOTAL);

			// START : KHUSUS 540022
				PRJCODE			= "<?php echo $PRJCODE; ?>";
				/*if(PRJCODE == '537022')
				{
					MAX_OPN_VOL	= parseFloat(TOT_OPNVOL);
					MAX_OPN_VAL	= parseFloat(TOT_OPNVAL);
                    console.log("PRJCODE:"+PRJCODE+" => MAX_OPN_VAL= "+MAX_OPN_VAL);
				}*/
			// END : KHUSUS 540022

			if(TOT_OPNVOL > MAX_OPN_VOL || TOT_OPNVAL > MAX_OPN_VAL)
			{
				/*REMOPN_VOL		= parseFloat(MAX_OPN_VOL - TOTOPN_VOL);
				REMOPN_AMN		= parseFloat(MAX_OPN_VAL - TOTOPN_AMN);*/
				REMOPN_VOL		= parseFloat(MAX_OPN_VOL);
				REMOPN_AMN		= parseFloat(MAX_OPN_VAL);
				if(REMOPN_VOL < 0 || REMOPN_AMN < 0)
				{
					REMOPN_VOL	= 0;
					REMOPN_AMN	= 0;
				}
				//console.log('REMOPN_VOL = '+REMOPN_VOL+' = '+MAX_OPN_VOL+'-'+TOTOPN_VOL)
				//console.log('REMOPN_AMN = '+REMOPN_AMN)

                document.getElementById('data'+row+'OPND_VOLM').value 		= REMOPN_VOL;
				document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMOPN_VOL)), 2));

				OPND_PERC 		= parseFloat(REMOPN_VOL / TOTWO_VOL * 100);
                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
				document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));

				OPND_ITMTOTAL	= parseFloat(REMOPN_VOL) * parseFloat(ITM_PRICE);

				document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
				document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));

				OPND_PERCV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 4));
				swal('Nilai total yang akan diopname sudah melebihi nilai maximum dari total progres pengakuan. Progress '+OPND_PERCV+' %',
				{
					icon: "warning",
				});
			}
			else
			{
				if(ITM_UNIT == 'LS')
				{
					if(OPND_ITMTOTAL > REMOPN_AMN)
					{
						swal('<?php echo $alert3; ?>',
						{
							icon: "warning",
						});

						OPND_VOLM		= parseFloat(REMOPN_VOL);
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}
					else
					{
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}

					OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
					document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));
				}
				else
				{
					if(OPND_VOLM > REMOPN_VOL)
					{
						swal('<?php echo $alert3; ?>',
						{
							icon: "warning",
						});

						OPND_VOLM		= parseFloat(REMOPN_VOL);
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}
					else
					{
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}

					OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
					document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));
				}
			}

		countTotalOpn();
	}

	function chgOPNVOL(thisVal1, row)
	{
		var decFormat	= document.getElementById('decFormat').value;

		OPND_VOLM		= parseFloat(eval(thisVal1).value.split(",").join(""));
		ITM_PRICE		= parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);
		TOTWO_VOL		= parseFloat(document.getElementById('TOTWO_VOL'+row).value);
		TOTWO_AMN		= parseFloat(document.getElementById('TOTWO_AMN'+row).value);
		TOTOPN_VOL		= parseFloat(document.getElementById('TOTOPN_VOL'+row).value);
		TOTOPN_AMN		= parseFloat(document.getElementById('TOTOPN_AMN'+row).value);
		REMOPN_VOL		= parseFloat(document.getElementById('REMOPN_VOL'+row).value);
		REMOPN_AMN		= parseFloat(document.getElementById('REMOPN_AMN'+row).value);
		ITMUNIT			= document.getElementById('data'+row+'ITM_UNIT').value;
		ITM_UNIT 		= ITMUNIT.toUpperCase();

		// TOTAL OPNAME NOW
			OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);
			//console.log('OPND_ITMTOTAL = '+OPND_ITMTOTAL)

		// GET MAXIMUM OPNAME
			MAX_OPN_VOL	= parseFloat(document.getElementById('MAX_OPN_VOL'+row).value);
			MAX_OPN_VAL	= parseFloat(document.getElementById('MAX_OPN_VAL'+row).value);
			/*TOT_OPNVOL 	= parseFloat(TOTOPN_VOL + OPND_VOLM);
			TOT_OPNVAL 	= parseFloat(TOTOPN_AMN + OPND_ITMTOTAL);*/
			TOT_OPNVOL 	= parseFloat(OPND_VOLM);
			TOT_OPNVAL 	= parseFloat(OPND_ITMTOTAL);
			//console.log('TOT_OPNVOLaaa = '+TOT_OPNVOL+'>'+MAX_OPN_VOL +'||'+ TOT_OPNVAL +'>'+ MAX_OPN_VAL)

			// START : KHUSUS 540022
				PRJCODE			= "<?php echo $PRJCODE; ?>";
				/*if(PRJCODE == '537022')
				{
					MAX_OPN_VOL	= parseFloat(TOT_OPNVOL);
					MAX_OPN_VAL	= parseFloat(TOT_OPNVAL);
				}*/
			// END : KHUSUS 540022

			if(TOT_OPNVOL > MAX_OPN_VOL || TOT_OPNVAL > MAX_OPN_VAL)
			{
				/*REMOPN_VOL		= parseFloat(MAX_OPN_VOL - TOTOPN_VOL);
				REMOPN_AMN		= parseFloat(MAX_OPN_VAL - TOTOPN_AMN);*/
				REMOPN_VOL		= parseFloat(MAX_OPN_VOL);
				REMOPN_AMN		= parseFloat(MAX_OPN_VAL);
				if(REMOPN_VOL < 0 || REMOPN_AMN < 0)
				{
					REMOPN_VOL	= 0;
					REMOPN_AMN	= 0;
				}

                document.getElementById('data'+row+'OPND_VOLM').value 		= REMOPN_VOL;
				document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REMOPN_VOL)), 2));

				OPND_PERC 		= parseFloat(REMOPN_VOL / TOTWO_VOL * 100);
                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
				document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));

				OPND_ITMTOTAL	= parseFloat(REMOPN_VOL) * parseFloat(ITM_PRICE);

				document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
				document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));

				OPND_PERCV 		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 4));
				swal('Nilai total yang akan diopname sudah melebihi nilai maximum dari total progres pengakuan. Progress '+OPND_PERCV+' %',
				{
					icon: "warning",
				});
			}
			else
			{
				if(ITM_UNIT == 'LS')
				{
					if(OPND_ITMTOTAL > REMOPN_AMN)
					{
						swal('<?php echo $alert3; ?>',
						{
							icon: "warning",
						});

						OPND_VOLM		= parseFloat(REMOPN_VOL);
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}
					else
					{
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}

					OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
					document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));
				}
				else
				{
					if(OPND_VOLM > REMOPN_VOL)
					{
						swal('<?php echo $alert3; ?>',
						{
							icon: "warning",
						});

						OPND_VOLM		= parseFloat(REMOPN_VOL);
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}
					else
					{
		                document.getElementById('data'+row+'OPND_VOLM').value 		= OPND_VOLM;
						document.getElementById('OPND_VOLM'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

						OPND_PERC 		= parseFloat(OPND_VOLM / TOTWO_VOL * 100);
		                document.getElementById('data'+row+'OPND_PERC').value 		= OPND_PERC;
						document.getElementById('OPND_PERC'+row).value				= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_PERC)), 2));
					}

					OPND_ITMTOTAL	= parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);

					document.getElementById('data'+row+'OPND_ITMTOTAL').value 	= OPND_ITMTOTAL;
					document.getElementById('OPND_ITMTOTAL'+row).value 			= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)), 2));
				}
			}

		countTotalOpn();
	}

	function countTotalOpn()
	{
		OPNH_RETPERC 	= parseFloat(document.getElementById('OPNH_RETPERC').value);
		OPNH_DPPER 		= parseFloat(document.getElementById('OPNH_DPPER').value);
		OPNH_DPVAL 		= parseFloat(document.getElementById('OPNH_DPVAL').value);

		var TOT_OPNVAL	= 0;
		var TAXPERC1 	= 0;
		var TAXPERC2 	= 0;
		var totRow 		= document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'OPND_ITMTOTAL');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';
			
			if(theObj != null)
			{
				var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
				var TOT_OPNVAL		= parseFloat(TOT_OPNVAL + OPND_ITMTOTAL);

				// RETENSI DIHITUNG SETELAH DIPOTONG POT. DP
				var OPND_POTDP 		= parseFloat(OPNH_DPPER * OPND_ITMTOTAL / 100);
				document.getElementById('data'+i+'OPND_POTDP').value 	= OPND_POTDP;

				//var OPND_ITMTOTAL2= parseFloat(OPND_ITMTOTAL - OPND_POTDP);
				var OPND_ITMTOTAL2 	= parseFloat(OPND_ITMTOTAL);
				var OPND_POTRET		= parseFloat(OPNH_RETPERC * OPND_ITMTOTAL2 / 100);
				document.getElementById('data'+i+'OPND_POTRET').value 	= OPND_POTRET;

				var OPND_ITMTOTAL3 	= parseFloat(OPND_ITMTOTAL - OPND_POTDP - OPND_POTRET);
				var TAXPERC1 		= parseFloat(document.getElementById('data'+i+'TAXPERC1').value);
				var TAXPERC2 		= parseFloat(document.getElementById('data'+i+'TAXPERC2').value);
				var TAXPRICE1		= parseFloat(TAXPERC1 * OPND_ITMTOTAL3 / 100);
				var TAXPRICE2 		= parseFloat(TAXPERC2 * OPND_ITMTOTAL3 / 100);
				document.getElementById('data'+i+'TAXPRICE1').value		= TAXPRICE1;
				document.getElementById('data'+i+'TAXPRICE2').value		= TAXPRICE2;
			}
		}

		WO_AMOUNT		= parseFloat(document.getElementById('WO_VALUE').value);
		TOTOPN_AMN_BEF	= parseFloat(document.getElementById('TOTOPN_AMN').value);

		var TOT_OPN_ALL 	= parseFloat(TOTOPN_AMN_BEF + TOT_OPNVAL);
		var TOT_OPN_ALLP 	= parseFloat(TOT_OPN_ALL / WO_AMOUNT * 100);
		//console.log('TOT_OPN_ALLP = '+TOT_OPN_ALLP)
		if(OPNH_DPPER == 0)
			var DPVAL_REM	= parseFloat(0);
		else
			var DPVAL_REM	= parseFloat(document.getElementById('DPVAL_REM').value);

		console.log('DPVAL_REM A = '+DPVAL_REM)

		document.getElementById('percOpn').innerHTML = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPN_ALLP)), 2));
		if(TOT_OPN_ALLP > 80)
		{
			OPNH_DPVALV 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));
			swal({
	            text: "Progress opname sudah lebih dari 80%, Potongan Uang Muka harus dihabiskan. Tetap lanjutkan dengan "+OPNH_DPVALV,
	            icon: "warning",
	            buttons: ["No", "Yes"],
	        }).then((willDelete) => {
				if (willDelete) {
					OPNH_DPVAL 	= parseFloat(OPNH_DPVAL);
					OPNH_DPPER 	= parseFloat(OPNH_DPVAL / TOT_OPNVAL * 100);
					document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
					document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));					

					// HITUNG POT. UM
						//POTDP_VAL 	= parseFloat(OPNH_DPPER * TOT_OPNVAL / 100);
						document.getElementById('OPNH_DPVAL').value		= OPNH_DPVAL;
						document.getElementById('OPNH_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

						//var OPND_ITMTOTAL2= parseFloat(TOT_OPNVAL - OPNH_DPVAL);
						var OPND_ITMTOTAL2 	= parseFloat(TOT_OPNVAL);

					// HITUNG RETENSI
						OPNH_RETAMN 	= parseFloat(OPNH_RETPERC * OPND_ITMTOTAL2 / 100);
						document.getElementById('OPNH_RETAMN').value 	= OPNH_RETAMN;
						document.getElementById('OPNH_RETAMNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)), 2));
						
						var OPND_ITMTOTAL3 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN);
					
					// HITUNG PPN
						PPN_VAL 	= parseFloat(TAXPERC1 * OPND_ITMTOTAL3 / 100);
						document.getElementById('OPNH_AMOUNTPPN').value		= PPN_VAL;
						document.getElementById('OPNH_AMOUNTPPNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_VAL)), 2));
					
					// HITUNG PPH
						PPH_VAL 	= parseFloat(TAXPERC2 * OPND_ITMTOTAL3 / 100);
						document.getElementById('OPNH_AMOUNTPPH').value		= PPH_VAL;
						document.getElementById('OPNH_AMOUNTPPHX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_VAL)), 2));

					// TOTAL OPNAME KOTOR
						document.getElementById('OPNH_AMOUNT').value		= TOT_OPNVAL;
						document.getElementById('OPNH_AMOUNTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

					var GTOT_OPNVAL 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN + PPN_VAL - PPH_VAL);

					// TOTAL OPNAME NETTO
						document.getElementById('OPNH_TOTAMOUNT').value 	= GTOT_OPNVAL;
						document.getElementById('OPNH_TOTAMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT_OPNVAL)), 2));
				}
				else {
					OPNH_DPVAL 	= parseFloat(DPVAL_REM);
					OPNH_DPPER 	= parseFloat(DPVAL_REM / TOT_OPNVAL * 100);
					document.getElementById('OPNH_DPPER').value 	= OPNH_DPPER;
					document.getElementById('OPNH_DPPERX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPPER)), 2));

					// HITUNG POT. UM
						//POTDP_VAL 	= parseFloat(OPNH_DPPER * TOT_OPNVAL / 100);
						document.getElementById('OPNH_DPVAL').value		= OPNH_DPVAL;
						document.getElementById('OPNH_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

						//var OPND_ITMTOTAL2= parseFloat(TOT_OPNVAL - OPNH_DPVAL);
						var OPND_ITMTOTAL2 	= parseFloat(TOT_OPNVAL);


					// HITUNG RETENSI
						OPNH_RETAMN 	= parseFloat(OPNH_RETPERC * OPND_ITMTOTAL2 / 100);
						document.getElementById('OPNH_RETAMN').value 	= OPNH_RETAMN;
						document.getElementById('OPNH_RETAMNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)), 2));
						
						var OPND_ITMTOTAL3 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN);
					
					// HITUNG PPN
						PPN_VAL 	= parseFloat(TAXPERC1 * OPND_ITMTOTAL3 / 100);
						document.getElementById('OPNH_AMOUNTPPN').value		= PPN_VAL;
						document.getElementById('OPNH_AMOUNTPPNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_VAL)), 2));
					
					// HITUNG PPH
						PPH_VAL 	= parseFloat(TAXPERC2 * OPND_ITMTOTAL3 / 100);
						document.getElementById('OPNH_AMOUNTPPH').value		= PPH_VAL;
						document.getElementById('OPNH_AMOUNTPPHX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_VAL)), 2));

					// TOTAL OPNAME KOTOR
						document.getElementById('OPNH_AMOUNT').value		= TOT_OPNVAL;
						document.getElementById('OPNH_AMOUNTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

					var GTOT_OPNVAL 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN + PPN_VAL - PPH_VAL);

					// TOTAL OPNAME NETTO
						document.getElementById('OPNH_TOTAMOUNT').value 	= GTOT_OPNVAL;
						document.getElementById('OPNH_TOTAMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT_OPNVAL)), 2));
				}
			});

		}
		else
		{
			// HITUNG POT. UM
				//POTDP_VAL 	= parseFloat(OPNH_DPPER * TOT_OPNVAL / 100);
				document.getElementById('OPNH_DPVAL').value		= OPNH_DPVAL;
				document.getElementById('OPNH_DPVALX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_DPVAL)), 2));

				//var OPND_ITMTOTAL2= parseFloat(TOT_OPNVAL - OPNH_DPVAL);
				var OPND_ITMTOTAL2 	= parseFloat(TOT_OPNVAL);

			// HITUNG RETENSI
				OPNH_RETAMN 	= parseFloat(OPNH_RETPERC * OPND_ITMTOTAL2 / 100);
				document.getElementById('OPNH_RETAMN').value 	= OPNH_RETAMN;
				document.getElementById('OPNH_RETAMNX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)), 2));
				
				var OPND_ITMTOTAL3 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN);
			
			// HITUNG PPN
				PPN_VAL 	= parseFloat(TAXPERC1 * OPND_ITMTOTAL3 / 100);
				document.getElementById('OPNH_AMOUNTPPN').value		= PPN_VAL;
				document.getElementById('OPNH_AMOUNTPPNX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPN_VAL)), 2));
			
			// HITUNG PPH
				PPH_VAL 	= parseFloat(TAXPERC2 * OPND_ITMTOTAL3 / 100);
				document.getElementById('OPNH_AMOUNTPPH').value		= PPH_VAL;
				document.getElementById('OPNH_AMOUNTPPHX').value	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(PPH_VAL)), 2));

			// TOTAL OPNAME KOTOR
				document.getElementById('OPNH_AMOUNT').value		= TOT_OPNVAL;
				document.getElementById('OPNH_AMOUNTX').value		= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOT_OPNVAL)), 2));

			var GTOT_OPNVAL 	= parseFloat(TOT_OPNVAL - OPNH_DPVAL - OPNH_RETAMN + PPN_VAL - PPH_VAL);

			// TOTAL OPNAME NETTO
				document.getElementById('OPNH_TOTAMOUNT').value 	= GTOT_OPNVAL;
				document.getElementById('OPNH_TOTAMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(GTOT_OPNVAL)), 2));
		}
	}

	function getPOT()
	{
		OPNH_POT1	= document.getElementById('OPNH_POTX');
		OPNH_POT	= parseFloat(eval(OPNH_POT1).value.split(",").join(""));
		OPNH_REMAIN	= parseFloat(document.getElementById('OPNH_REMAIN').value);
		if(OPNH_POT > OPNH_REMAIN)
		{
			swal('<?php echo $alert3; ?>');
			document.getElementById("OPNH_POT").focus();

			document.getElementById('OPNH_POT').value 	= OPNH_REMAIN;
			document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_REMAIN)), 2));
			OPNH_POT	= parseFloat(document.getElementById('OPNH_POT').value);
		}

		var totOPN1	= 0;
		var totRow = document.getElementById('totalrow').value;
		for(i=1;i<=totRow;i++)
		{
			let myObj 	= document.getElementById('data'+i+'OPND_ITMTOTAL');
			var theObj 	= typeof myObj !== 'undefined' ? myObj : '';

			////****console.log(i+' = '+ theObj)
			
			if(theObj != null)
			{
				var OPND_ITMTOTAL 	= parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
				var OPND_TOTTAX 	= parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
				totOPN1				= parseFloat(totOPN1 + OPND_ITMTOTAL);
			}
		}

		//OPNH_AMOUNTX	= parseFloat(document.getElementById('OPNH_AMOUNT').value);
		OPNH_AMOUNTX	= parseFloat(totOPN1);
		if(OPNH_POT > OPNH_AMOUNTX)
		{
			swal('<?php echo $alert4; ?>');
			document.getElementById("OPNH_POT").focus();

			document.getElementById('OPNH_POT').value 	= OPNH_AMOUNTX;
			document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTX)), 2));
			OPNH_POT	= parseFloat(document.getElementById('OPNH_POT').value);
		}

		OPNH_RETAMN		= parseFloat(document.getElementById('OPNH_RETAMN').value);
		OPNH_DPVAL 		= parseFloat(document.getElementById('OPNH_DPVAL').value);
		OPNH_AMOUNTPPN 	= parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);

		//OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
		// OPNH_AMOUNT1	= parseFloat(totOPN1) - parseFloat(OPNH_POT);
		// INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
		OPNH_AMOUNT1	= parseFloat(totOPN1);

		document.getElementById('OPNH_AMOUNT').value 	= OPNH_AMOUNT1;
		document.getElementById('OPNH_AMOUNTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

		document.getElementById('OPNH_POT').value 	= OPNH_POT;
		document.getElementById('OPNH_POTX').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_POT)), 2));

		// AKUMULASI TOTAL AWAL
		/*PROG_BEF 	= parseFloat(document.getElementById('PROG_BEF').value);
		AKUM_PROG	= parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
		document.getElementById('AKUM_PROG').value 	= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));*/

		getPPnPer();
		getDPPER();
		getRETAMN();
	}

	function validateInData()
	{
		var totrow 	= document.getElementById('totalrow').value;
		let WO_CATEG= document.getElementById('WO_CATEG').value;
		
	  	let tglWOA 		= $('#WO_STARTD').val().split('/');
	  	var tglWOA_dy 	= tglWOA[0];
	  	var tglWOA_mn 	= tglWOA[1];
	  	var tglWOA_yr 	= tglWOA[2];
	  	var tglWOA_dt 	= new Date(tglWOA_yr, tglWOA_mn, tglWOA_dy);

	  	let tglWOB 		= $('#WO_ENDD').val().split('/');
	  	var tglWOB_dy 	= tglWOB[0];
	  	var tglWOB_mn 	= tglWOB[1];
	  	var tglWOB_yr 	= tglWOB[2];
	  	var tglWOB_dt 	= new Date(tglWOB_yr, tglWOB_mn, tglWOB_dy);
		
	  	let tglOPN 		= $('#datepicker').val().split('/');
	  	var tglOPN_dy 	= tglOPN[0];
	  	var tglOPN_mn 	= tglOPN[1];
	  	var tglOPN_yr 	= tglOPN[2];
	  	var tglOPN_dt 	= new Date(tglOPN_yr, tglOPN_mn, tglOPN_dy);
		
	  	let tglOPNA 	= $('#datepicker1').val().split('/');
	  	var tglOPNA_dy 	= tglOPNA[0];
	  	var tglOPNA_mn 	= tglOPNA[1];
	  	var tglOPNA_yr 	= tglOPNA[2];
	  	var tglOPNA_dt 	= new Date(tglOPNA_yr, tglOPNA_mn, tglOPNA_dy);

	  	let tglOPNB 	= $('#datepicker2').val().split('/');
	  	var tglOPNB_dy 	= tglOPNB[0];
	  	var tglOPNB_mn 	= tglOPNB[1];
	  	var tglOPNB_yr 	= tglOPNB[2];
	  	var tglOPNB_dt 	= new Date(tglOPNB_yr, tglOPNB_mn, tglOPNB_dy);
				
		if(tglOPN_dt < tglOPNA_dt || tglOPN_dt < tglOPNB_dt)
		{
			console.log(WO_CATEG);
			if(WO_CATEG != 'A' && WO_CATEG != 'O')
			{
				console.log("WO_CATEG : "+WO_CATEG);
				swal('Tanggal opname tidak boleh lebih kecil dari tanggal periode opname',
				{
					icon:"warning",
				})
				.then(function()
				{
					document.getElementById('datepicker1').focus();
				});
				return false;
			}
		}
				
		// if(tglOPNA_dt > tglWOB_dt || tglOPNA_dt < tglWOA_dt || tglOPNB_dt > tglWOB_dt)
		if(tglOPNA_dt > tglWOB_dt || tglOPNA_dt < tglWOA_dt)
		{
			if(WO_CATEG != 'A' && WO_CATEG != 'O' && tglOPNB_dt > tglWOB_dt)
			{
				swal('Periode opname tidak boleh di luar periode SPK',
				{
					icon:"warning",
				})
				.then(function()
				{
					if(tglOPNA_dt < tglWOA_dt)
						document.getElementById('datepicker1').focus();
					else
						document.getElementById('datepicker2').focus();
				});
				return false;
			}
		}

		OPNH_STAT	= document.getElementById("OPNH_STAT").value;
		OPNH_RETPERC= document.getElementById("OPNH_RETPERC").value;
		OPNH_RETNO	= document.getElementById("OPNH_RETNO").value;

		if(OPNH_RETPERC > 0 && OPNH_RETNO == '')
		{
			swal('<?php echo $alert9; ?>', 
			{
				icon: "warning"
			}).then(function(){
				document.getElementById('OPNH_RETNO').focus();
			});
			return false;
		}

		if(OPNH_STAT == 9)
		{
			OPNH_MEMO		= document.getElementById('OPNH_MEMO').value;
			if(OPNH_MEMO == '')
			{
				swal('<?php echo $alert1; ?>');
				document.getElementById('OPNH_MEMO').focus();
				return false;
			}
		}
		document.getElementById('btnSave').style.display 		= 'none';
		document.getElementById('btnBack').style.display 		= 'none';

		let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		});
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

	var selectedRows = 0;
	function pickThis(thisobj,ke)
	{
		if(thisobj.checked)
		{
			document.getElementById('chk'+thisobj.value).checked = true;
		}
		else
		{
			document.getElementById('chk'+thisobj.value).checked = false;
		}

		objTable = document.getElementById('tbl');
		intTable = objTable.rows.length;
		var NumOfRows = intTable-1;
		if (thisobj!= '')
		{
			if (thisobj.checked) selectedRows++;
			else selectedRows--;
		}

		if (selectedRows==NumOfRows)
		{
			document.frm.HChkAllItem.checked = true;
		}
		else
		{
			document.frm.HChkAllItem.checked = false;
		}
	}

	function deleteRow(btn)
	{
		var row = document.getElementById("tr_" + btn);
		row.remove();
	}

	function trashItemFile(row, fileName)
	{		
		swal({
            text: "<?php echo $alert8; ?>",
            icon: "warning",
            buttons: ["No", "Yes"],
        }).then((willDelete) => {
			if (willDelete) {
				let OPNH_NUM	= "<?php echo $OPNH_NUM; ?>";
				let PRJCODE	= "<?php echo $PRJCODE; ?>";
				$.ajax({
					type: "POST",
					url: "<?php echo site_url("c_project/C_o180d0bpnm/trashFile"); ?>",
					data: {OPNH_NUM:OPNH_NUM, PRJCODE:PRJCODE, fileName:fileName},
					beforeSend: function(xhr) {
						//console.log(xhr);
					},
					success: function(callback) {
						//console.log(callback);
						swal("File has been deleted!", {icon: "success",});
						$('.itemFile_'+row).remove();
					},
				});
			}
			else {
				swal("Your file is safe!");
			}
		});
	}

	function viewFile(fileName)
	{
		// const url 		= "<?php // echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		const url 		= "<?php echo 'https://sdbpplus.nke.co.id/assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
		// const urlOpen	= "<?php // echo base_url(); ?>";
		const urlOpen	= "<?php echo "https://sdbpplus.nke.co.id/"; ?>";
		// const urlDom	= "<?php // echo "https://sdbpplus.nke.co.id/"; ?>";
		let PRJCODE 	= "<?php echo $PRJCODE; ?>";
		// let path 		= "OPN_Document/"+PRJCODE+"/";
		let path 		= "OPN_Document/"+PRJCODE+"/"+fileName;
		// let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen+'&base_urlDom='+urlDom;
		let FileUpName	= ''+path+'&fileName='+fileName+'&base_url='+urlOpen;
		title = 'Select Item';
		w = 850;
		h = 550;
		//window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
		let left = (screen.width/2)-(w/2);
		let top = (screen.height/2)-(h/2);
		return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}

	function copyCOA(row)
	{
		let collID  	= document.getElementById('urlCopyCOA'+row).value;
		let myarr 		= collID.split('~');
    	let url			= myarr[0];

		document.getElementById('idprogbar').style.display 		= '';
		document.getElementById("progressbarXX").innerHTML			="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";


        $.ajax({
            type: 'POST',
            url: url,
            data: {collID: collID},
            success: function(response)
            {
				txtArr 		= response.split("~");
				statAlert	= txtArr[0];
				txtAlert 	= txtArr[1];

				if(statAlert == 0) iconAlert = "warning";
				else iconAlert = "success";

                swal(txtAlert, 
                {
                    icon: iconAlert,
                })
                .then(function()
                {
                	location.reload();
					// document.getElementById('some_frame_id').contentWindow.location.reload();

					document.getElementById('idprogbar').style.display 		= 'none';
					document.getElementById("progressbarXX").innerHTML		="<div class='cssProgress-bar cssProgress-primary cssProgress-active' style='width: 100%; text-align:center; font-weight:bold;'><span style='text-align:center; font-weight:bold'>Please wait, we are processing synchronization ...</span></div>";
					
                })
            }
        });
	}

	function RoundNDecimal(X, N)
	{
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

	function validateDouble(vcode, SNCODE)
	{
		var thechk=new Array();
		var duplicate = false;

		var jumchk = document.getElementsByName('chk').length;
		if (jumchk!=null)
		{
			thechk=document.getElementsByName('chk');
			panjang = parseInt(thechk.length);
		}
		else
		{
			thechk[0]=document.getElementsByName('chk');
			panjang = 0;
		}
		var panjang = panjang + 1;
		for (var i=0;i<panjang;i++)
		{
			var temp = 'tr_'+parseInt(i+1);
			if(i>0)
			{
				var elitem1= document.getElementById('data'+i+'ITM_CODE').value;
				var iparent= document.getElementById('data'+i+'SNCODE').value;
				if (elitem1 == vcode && iparent == SNCODE)
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
</script>
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