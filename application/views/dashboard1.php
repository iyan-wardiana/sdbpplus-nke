<?php
	$sqlApp         = "SELECT * FROM tappname";
    $resultaApp     = $this->db->query($sqlApp)->result();
    foreach($resultaApp as $therow) :
        $appName    = $therow->app_name;
        $comp_init  = $therow->comp_init;
        $comp_name  = $therow->comp_name;
        $top_name   = $therow->top_name;
    endforeach;

    // _global function
        $this->db->select('Display_Rows,decFormat,CompDesc');
        $resGlobal = $this->db->get('tglobalsetting')->result();
        foreach($resGlobal as $row) :
            $Display_Rows   = $row->Display_Rows;
            $decFormat    = $row->decFormat;
            $CompDesc     = $row->CompDesc;
        endforeach;
        $decFormat  = 2;

        date_default_timezone_set("Asia/Jakarta");

		$tNow    	= date('Y-m-d');
		$tNDate1    = date('Y-m-t', strtotime('-11 month', strtotime($tNow)));
		$tY       	= date('Y', strtotime($tNDate1));
		$tM       	= date('n', strtotime($tNDate1));

        $myPRJCODE  = '';

        // $this->load->view('template/head');

        $appName 	= $this->session->userdata('appName');
		$vers     	= $this->session->userdata['vers'];
		$FlagUSER 	= $this->session->userdata['FlagUSER'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		$appBody    = $this->session->userdata['appBody'];
		$sysMnt		= $this->session->userdata['sysMnt'];
		$LastMntD	= $this->session->userdata['LastMntD'];
		$PRJSCATEG	= $this->session->userdata['PRJSCATEG'];
		$tgl1 		= new DateTime($LastMntD);
		$tgl2 		= new DateTime();
		$dif1 		= $tgl1->diff($tgl2);
		$dif2 		= $dif1->days;

		$cLogV		= "tbl_emp_vers WHERE EMP_ID = '$Emp_ID' AND VERS = '$vers'";
		$vLogV		= $this->db->count_all($cLogV);

		/*$isEnd 		= 0;
		if($sysMnt == 1 && $tgl2 >= $tgl1)
		{
			$isEnd 	= 2;
		}
		elseif($sysMnt == 1 && $dif2 < 6) 
		{
			$isEnd 	= 1;
		}*/

		$PRJ_IMGNAME= "building.jpg";
		$lasTDMnt	= date('d M Y', strtotime($LastMntD));
		$mntWarn1	= "";
		$mntWarn2	= "";

		$LangID 	= $this->session->userdata['LangID'];

		if($LangID == 'IND')
		{
			$transL01	= "Perangkat lunak ini mutahkir.";
			$transL02	= "NKE-Sys v$vers berhasil dipasang. Sembunyikan pesan ini.";
			$transL03	= "Anda tidak akan melihat peringatan ini di lain waktu. Terimakasih.";
			$transL04	= "Anda akan tetap melihat peringatan ini di saat Anda Log In. Terimakasih.";
			$transL05	= "Ya";
			$transL06	= "Tidak";
			$transL07	= "Anda dapat mengganti warna template.";
			$transL08	= "Cara Penggunaan";
			$transL09	= "Klik ikon pengaturan pada kanan atas, kemudian pilih warna.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "Akan berakhir pada $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "Perawatan Sistem";
				$mntWarn2	= "sudah berkahir pada $lasTDMnt.";
			}*/
		}
		else
		{
			$transL01	= "The software is up to date.";
			$transL02	= "NKE-Sys v$vers successfully installed. Please hide this message.";
			$transL03	= "You will not see this warning again. Thank you.";
			$transL04	= "You will still see this warning when you log in. Thank you.";
			$transL05	= "Yes";
			$transL06	= "No";
			$transL07	= "You can change the template color.";
			$transL08	= "How to use";
			$transL09	= "Click the settings icon on the top right. And select the color.";

			/*if($isEnd == 1)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "will expire on $lasTDMnt.";
			}
			else if($isEnd == 2)
			{
				$mntWarn1	= "System Maintenance";
				$mntWarn2	= "has expired on $lasTDMnt.";
			}*/
		}

        //$this->load->view('template/topbar');
        //$this->load->view('template/sidebar');

        $LOGHIST  = 1;

        $WEEK_BEF1  = mktime(0, 0, 0, date("m"), date("d")-31, date("Y"));
        $WEEK_BEF = date("Y-m-d", $WEEK_BEF1);
        $WEEK_NEXT1 = mktime(0, 0, 0, date("m"), date("d")+2, date("Y"));
        $WEEK_NEXT  = date("Y-m-d", $WEEK_NEXT1);
        $THISD    = date('d');
        $THISM    = date('m');
        $THISY    = date('Y');

    // SET PROJECT
        $PRJCODEDEF		= "";
        $S_PRJDEF		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
                            FROM tbl_employee_proj A
                            INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
                            WHERE A.Emp_ID = '$Emp_ID' LIMIT 1";
        $R_PRJDEF 		= $this->db->query($S_PRJDEF)->result();
        foreach($R_PRJDEF as $RW_PRJDEF) :
            $PRJCODEDEF	= $RW_PRJDEF->proj_Code;
        endforeach;

		$selPRJCODE   = $PRJCODEDEF;
		if(isset($_POST['submitPRJ']))
		{
			$selPRJCODE = $_POST['selPRJCODE'];
		}
		
		$myPRJCODE   	= "";
		$PRJCODEX 		= "";
		$PRJCOSTX 		= 0;
		$PRJAMDX 		= 0;
		$PRJDATEX   	= "-";
		$PRJEDATX   	= "-";
		$myPRJCODE  	= "'$selPRJCODE'";
		$myPRJCODEX 	= "'$selPRJCODE'";
		$PRJCODEX 		= $selPRJCODE;
		$PRJNAMEX 		= $selPRJCODE;
		$timeR 			= 0;
		$timP 			= 0;
		$sqlPRJ   		= "SELECT PRJNAME, PRJCOST, PRJAMD, PRJDATE, PRJEDAT, PRJ_IMGNAME, PRJ_MNG FROM tbl_project WHERE PRJCODE = $myPRJCODE LIMIT 1";
		$resPRJ   		= $this->db->query($sqlPRJ)->result();
		foreach($resPRJ as $rowPRJ) :
			$PRJNAMEX   = $rowPRJ->PRJNAME;
			$PRJCOSTX   = $rowPRJ->PRJCOST;
			$PRJAMDX   	= $rowPRJ->PRJAMD;
			$PRJDATEN   = date('Y-m-d', strtotime($rowPRJ->PRJDATE));
			$PRJDATEA   = date('Y-m-d', strtotime($rowPRJ->PRJDATE));
			$PRJEDATA   = date('Y-m-d', strtotime($rowPRJ->PRJEDAT));
			$PRJDATEX   = date('d.m.Y', strtotime($rowPRJ->PRJDATE));
			$PRJEDATX   = date('d.m.Y', strtotime($rowPRJ->PRJEDAT));
			$PRJ_IMGNAME= $rowPRJ->PRJ_IMGNAME;
			$PRJ_MNGX 	= $rowPRJ->PRJ_MNG;

			$dt1 	= new DateTime($PRJDATEA);
			$dt2 	= new DateTime($PRJEDATA);
			$dtN 	= date('Y-m-d');
			$dt3 	= new DateTime($dtN);
			$d1 	= $dt2->diff($dt1)->days + 1;
			$d2 	= $dt3->diff($dt1)->days + 1;

			$timeR 	= $d1 - $d2;
			$timP 	= $d2 / $d1 * 100;
		endforeach;

		if($PRJ_MNGX == "")
		{
			$MNG_NIK	= "-";
			$MNG_NAME	= "-";
		}
		else
		{
			$s_MNG		= "SELECT A.Emp_ID, CONCAT(A.First_Name, ' ', A.Last_Name) AS compName FROM tbl_employee A WHERE Emp_ID = '$PRJ_MNGX'";
			$r_MNG	= $this->db->query($s_MNG)->result();
			foreach($r_MNG as $rw_MNG):
				$MNG_NIK	= $rw_MNG->Emp_ID;
				$MNG_NAME	= $rw_MNG->compName;
			endforeach;
		}

  	// ----------------------- REQUEST / PR
		$REQ_NEW  = 0;
		$REQ_CON  = 0;
		$REQ_APP  = 0;
		$REQ_REV  = 0;
		$REQ_REJ  = 0;
		$REQ_CLS  = 0;
		  	$TOT_REQ_NEW  = 0;
		  	$TOT_REQ_CON  = 0;
		  	$TOT_REQ_APP  = 0;
		  	$TOT_REQ_REV  = 0;
		  	$TOT_REQ_REJ  = 0;
      		$TOT_REQ_CLS  = 0;
  	// ----------------------- PURCHASE ORDER / PO
		$PO_NEW   = 0;
		$PO_CON   = 0;
		$PO_APP   = 0;
		$PO_REV   = 0;
		$PO_REJ   = 0;
		$PO_CLS   = 0;
			$TOT_PO_NEW = 0;
			$TOT_PO_CON = 0;
			$TOT_PO_APP = 0;
			$TOT_PO_REV = 0;
			$TOT_PO_REJ = 0;
			$TOT_PO_CLS = 0;
  	// ----------------------- ITEM RECEIPT / IR
		$IR_NEW   = 0;
		$IR_CON   = 0;
		$IR_APP   = 0;
		$IR_REV   = 0;
		$IR_REJ   = 0;
		$IR_CLS   = 0;
			$TOT_IR_NEW = 0;
			$TOT_IR_CON = 0;
			$TOT_IR_APP = 0;
			$TOT_IR_REV = 0;
			$TOT_IR_REJ = 0;
      		$TOT_IR_CLS = 0;
 	 // ----------------------- PURCHASE INVOICE / FAKTUR
		$PINV_NEW = 0;
		$PINV_CON = 0;  // PAYMENT
		$PINV_APP = 0;
		$PINV_HP  = 0;
		$PINV_FP  = 0;
		$PINV_REJ = 0;
			$TOT_PINV_NEW = 0;
			$TOT_PINV_CON = 0;
			$TOT_PINV_APP = 0;
			$TOT_PINV_HP  = 0;
			$TOT_PINV_FP  = 0;
			$TOT_PINV_REJ = 0;
  	// ----------------------- MC PROYEK
		$MC_NEW   = 0;
		$MC_CON   = 0;
		$MC_APP   = 0;
		$MC_REV   = 0;
		$MC_REJ   = 0;
		$MC_CLS   = 0;
			$TOT_MC_NEW = 0;
			$TOT_MC_CON = 0;
			$TOT_MC_APP = 0;
			$TOT_MC_REV = 0;
			$TOT_MC_REJ = 0;
  	// ----------------------- INVOICE / FAKTUR
		$INV_NEW  = 0;
		$INV_CON  = 0;
		$INV_APP  = 0;
		$INV_REV  = 0;
		$INV_REJ  = 0;
		$INV_CLS  = 0;
			$TOT_INV_NEW  = 0;
			$TOT_INV_CON  = 0;
			$TOT_INV_APP  = 0;
			$TOT_INV_REV  = 0;
			$TOT_INV_REJ  = 0;
			$TOT_INV_CLS  = 0;
			$TOT_INV_FP   = 0;
			$TOT_INV_NP   = 0;
  	// ----------------------- BANK PAYMENT / CB
		$CB_NEW   = 0;
		$CB_CON   = 0;
		$CB_APP   = 0;
		$CB_REV   = 0;
		$CB_REJ   = 0;
		$CB_CLS   = 0;
			$TOT_CB_NEW = 0;
			$TOT_CB_CON = 0;
			$TOT_CB_APP = 0;
			$TOT_CB_REV = 0;
			$TOT_CB_REJ = 0;
			$TOT_CB_CLS = 0;
  	// ----------------------- BANK PAYMENT / CB
		$BR_NEW   = 0;
		$BR_CON   = 0;
		$BR_APP   = 0;
		$BR_REV   = 0;
		$BR_REJ   = 0;
		$BR_CLS   = 0;
			$TOT_BR_NEW = 0;
			$TOT_BR_CON = 0;
			$TOT_BR_APP = 0;
			$TOT_BR_REV = 0;
			$TOT_BR_REJ = 0;
			$TOT_BR_CLS = 0;
  	// ----------------------- OFFERING LETTER / OFF
		$OFF_NEW   = 0;
		$OFF_CON   = 0;
		$OFF_APP   = 0;
		$OFF_REV   = 0;
		$OFF_REJ   = 0;
		$OFF_CLS   = 0;
			$TOT_OFF_NEW = 0;
			$TOT_OFF_CON = 0;
			$TOT_OFF_APP = 0;
			$TOT_OFF_REV = 0;
			$TOT_OFF_REJ = 0;
			$TOT_OFF_CLS = 0;
	// ----------------------- SALES ORDER / SO
		$SO_NEW  = 0;
		$SO_CON  = 0;
		$SO_APP  = 0;
		$SO_REV  = 0;
		$SO_REJ  = 0;
		$SO_CLS  = 0;
			$TOT_SO_NEW  = 0;
			$TOT_SO_CON  = 0;
			$TOT_SO_APP  = 0;
			$TOT_SO_REV  = 0;
			$TOT_SO_REJ  = 0;
			$TOT_SO_CLS  = 0;
	// ----------------------- JOB ORDER / JO
		$JO_NEW  = 0;
		$JO_CON  = 0;
		$JO_APP  = 0;
		$JO_REV  = 0;
		$JO_REJ  = 0;
		$JO_CLS  = 0;
			$TOT_JO_NEW  = 0;
			$TOT_JO_CON  = 0;
			$TOT_JO_APP  = 0;
			$TOT_JO_REV  = 0;
			$TOT_JO_REJ  = 0;
			$TOT_JO_CLS  = 0;
	// ----------------------- MATERIAL REQUEST / MR
		$MR_NEW  = 0;
		$MR_CON  = 0;
		$MR_APP  = 0;
		$MR_REV  = 0;
		$MR_REJ  = 0;
		$MR_CLS  = 0;
			$TOT_MR_NEW  = 0;
			$TOT_MR_CON  = 0;
			$TOT_MR_APP  = 0;
			$TOT_MR_REV  = 0;
			$TOT_MR_REJ  = 0;
			$TOT_MR_CLS  = 0;
	// ----------------------- WORK ORDER / JO / SPK
		$WO_NEW  = 0;
		$WO_CON  = 0;
		$WO_APP  = 0;
		$WO_REV  = 0;
		$WO_REJ  = 0;
		$WO_CLS  = 0;
			$TOT_WO_NEW  = 0;
			$TOT_WO_CON  = 0;
			$TOT_WO_APP  = 0;
			$TOT_WO_REV  = 0;
			$TOT_WO_REJ  = 0;
			$TOT_WO_CLS  = 0;
	// ----------------------- OPNAME
		$OPN_NEW  = 0;
		$OPN_CON  = 0;
		$OPN_APP  = 0;
		$OPN_REV  = 0;
		$OPN_REJ  = 0;
		$OPN_CLS  = 0;
			$TOT_OPN_NEW  = 0;
			$TOT_OPN_CON  = 0;
			$TOT_OPN_APP  = 0;
			$TOT_OPN_REV  = 0;
			$TOT_OPN_REJ  = 0;
			$TOT_OPN_CLS  = 0;
	// ----------------------- SHIPMENT NOTE / SN
		$SN_NEW  = 0;
		$SN_CON  = 0;
		$SN_APP  = 0;
		$SN_REV  = 0;
		$SN_REJ  = 0;
		$SN_CLS  = 0;
			$TOT_SN_NEW  = 0;
			$TOT_SN_CON  = 0;
			$TOT_SN_APP  = 0;
			$TOT_SN_REV  = 0;
			$TOT_SN_REJ  = 0;
			$TOT_SN_CLS  = 0;
	// ----------------------- SHIPMENT NOTE / SN
		$SINV_NEW  = 0;
		$SINV_CON  = 0;
		$SINV_APP  = 0;
		$SINV_REV  = 0;
		$SINV_REJ  = 0;
		$SINV_CLS  = 0;
			$TOT_SINV_NEW  = 0;
			$TOT_SINV_CON  = 0;
			$TOT_SINV_APP  = 0;
			$TOT_SINV_REV  = 0;
			$TOT_SINV_REJ  = 0;
			$TOT_SINV_CLS  = 0;

  	// ----------------------- CHART
		$GRF_LOGHIST  	= 0;
		$GRF_SALES     	= 0;
		$GRF_DPROD 		= 0;
		$GRF_PURCH     	= 0;
		$GRF_BP			= 0;
		$GRF_GOALC		= 0;
		$SSWIP_INFO		= 0;
		$BR_INFO		= 0;
		$MC_PRJ			= 0;

	// START : GET QTY FOR EACH STATUS
		$sqlAllData   	= "SELECT
							SUM(TOT_REQ) AS TOT_REQ, SUM(TOT_REQ_N) AS TOT_REQ_NEW, SUM(TOT_REQ_C) AS TOT_REQ_CON,
								SUM(TOT_REQ_A) AS TOT_REQ_APP, SUM(TOT_REQ_R) AS TOT_REQ_REV, SUM(TOT_REQ_RJ) AS TOT_REQ_REJ,
								SUM(TOT_REQ_CL) AS TOT_REQ_CLS,
							SUM(TOT_PO) AS TOT_PO, SUM(TOT_PO_N) AS TOT_PO_NEW, SUM(TOT_PO_C) AS TOT_PO_CON, SUM(TOT_PO_A) AS TOT_PO_APP,
								SUM(TOT_PO_R) AS TOT_PO_REV, SUM(TOT_PO_RJ) AS TOT_PO_REJ, SUM(TOT_PO_CL) AS TOT_PO_CLS,
							SUM(TOT_IR) AS TOT_IR, SUM(TOT_IR_N) AS TOT_IR_NEW, SUM(TOT_IR_C) AS TOT_IR_CON, SUM(TOT_IR_A) AS TOT_IR_APP,
								SUM(TOT_IR_R) AS TOT_IR_REV, SUM(TOT_IR_RJ) AS TOT_IR_REJ, SUM(TOT_IR_CL) AS TOT_IR_CLS,
							SUM(TOT_PINV) AS TOT_PINV, SUM(TOT_PINV_N) AS TOT_PINV_NEW, SUM(TOT_PINV_C) AS TOT_PINV_CON, SUM(TOT_PINV_A) AS TOT_PINV_APP,
								SUM(TOT_PINV_HP) AS TOT_PINV_HP, SUM(TOT_PINV_FP) AS TOT_PINV_FP, SUM(TOT_PINV_R) AS TOT_PINV_REJ,
							SUM(TOT_INV) AS TOT_INV, SUM(TOT_INV_FP) AS TOT_INV_FP, SUM(TOT_INV_NP) AS TOT_INV_NP,
							SUM(TOT_CB) AS TOT_CB, SUM(TOT_CB_N) AS TOT_CB_NEW, SUM(TOT_CB_C) AS TOT_CB_CON, SUM(TOT_CB_A) AS TOT_CB_APP,
								SUM(TOT_CB_R) AS TOT_CB_REV, SUM(TOT_CB_RJ) AS TOT_CB_REJ, SUM(TOT_CB_CL) AS TOT_CB_CLS,
							SUM(TOT_OFF) AS TOT_OFF, SUM(TOT_OFF_N) AS TOT_OFF_NEW, SUM(TOT_OFF_C) AS TOT_OFF_CON, SUM(TOT_OFF_A) AS TOT_OFF_APP,
								SUM(TOT_OFF_R) AS TOT_OFF_REV, SUM(TOT_OFF_RJ) AS TOT_OFF_REJ, SUM(TOT_OFF_CL) AS TOT_OFF_CLS,
							SUM(TOT_SO) AS TOT_SO, SUM(TOT_SO_N) AS TOT_SO_NEW, SUM(TOT_SO_C) AS TOT_SO_CON, SUM(TOT_SO_A) AS TOT_SO_APP,
								SUM(TOT_SO_R) AS TOT_SO_REV, SUM(TOT_SO_RJ) AS TOT_SO_REJ, SUM(TOT_SO_CL) AS TOT_SO_CLS,
							SUM(TOT_JO) AS TOT_JO, SUM(TOT_JO_N) AS TOT_JO_NEW, SUM(TOT_JO_C) AS TOT_JO_CON, SUM(TOT_JO_A) AS TOT_JO_APP,
								SUM(TOT_JO_R) AS TOT_JO_REV, SUM(TOT_JO_RJ) AS TOT_JO_REJ, SUM(TOT_JO_CL) AS TOT_JO_CLS,
							SUM(TOT_WO) AS TOT_WO, SUM(TOT_WO_N) AS TOT_WO_NEW, SUM(TOT_WO_C) AS TOT_WO_CON, SUM(TOT_WO_A) AS TOT_WO_APP,
								SUM(TOT_WO_R) AS TOT_WO_REV, SUM(TOT_WO_RJ) AS TOT_WO_REJ, SUM(TOT_WO_CL) AS TOT_WO_CLS,
							SUM(TOT_OPN) AS TOT_OPN, SUM(TOT_OPN_N) AS TOT_OPN_NEW, SUM(TOT_OPN_C) AS TOT_OPN_CON, SUM(TOT_OPN_A) AS TOT_OPN_APP,
								SUM(TOT_OPN_R) AS TOT_OPN_REV, SUM(TOT_OPN_RJ) AS TOT_OPN_REJ, SUM(TOT_OPN_CL) AS TOT_OPN_CLS,
							SUM(TOT_MR) AS TOT_MR, SUM(TOT_MR_N) AS TOT_MR_NEW, SUM(TOT_MR_C) AS TOT_MR_CON, SUM(TOT_MR_A) AS TOT_MR_APP,
								SUM(TOT_MR_R) AS TOT_MR_REV, SUM(TOT_MR_RJ) AS TOT_MR_REJ, SUM(TOT_MR_CL) AS TOT_MR_CLS,
							SUM(TOT_SN) AS TOT_SN, SUM(TOT_SN_N) AS TOT_SN_NEW, SUM(TOT_SN_C) AS TOT_SN_CON, SUM(TOT_SN_A) AS TOT_SN_APP,
								SUM(TOT_SN_R) AS TOT_SN_REV, SUM(TOT_SN_RJ) AS TOT_SN_REJ, SUM(TOT_SN_CL) AS TOT_SN_CLS,
							SUM(TOT_SINV) AS TOT_SINV, SUM(TOT_SINV_N) AS TOT_SINV_NEW, SUM(TOT_SINV_C) AS TOT_SINV_CON, SUM(TOT_SINV_A) AS TOT_SINV_APP,
								SUM(TOT_SINV_R) AS TOT_SINV_REV, SUM(TOT_SINV_RJ) AS TOT_SINV_REJ, SUM(TOT_SINV_CL) AS TOT_SINV_CLS,
							SUM(TOT_MC) AS TOT_MC, SUM(TOT_MC_N) AS TOT_MC_NEW, SUM(TOT_MC_C) AS TOT_MC_CON, SUM(TOT_MC_A) AS TOT_MC_APP,
								SUM(TOT_MC_R) AS TOT_MC_REV, SUM(TOT_MC_RJ) AS TOT_MC_REJ, SUM(TOT_MC_CL) AS TOT_MC_CLS,
							SUM(TOT_BR) AS TOT_BR, SUM(TOT_BR_N) AS TOT_BR_NEW, SUM(TOT_BR_C) AS TOT_BR_CON, SUM(TOT_BR_A) AS TOT_BR_APP,
								SUM(TOT_BR_R) AS TOT_BR_REV, SUM(TOT_BR_RJ) AS TOT_BR_REJ, SUM(TOT_BR_CL) AS TOT_BR_CLS
						-- FROM tbl_dash_transac WHERE PRJ_CODE IN ($myPRJCODE)
						FROM tbl_dash_transac WHERE PRJ_CODE = $myPRJCODE";
		$resAllData   	= $this->db->query($sqlAllData)->result();
		foreach($resAllData as $rowAllD) :
			// PR
				$TOT_REQ    	= $rowAllD->TOT_REQ;
				$TOT_REQ_NEW	= $rowAllD->TOT_REQ_NEW;
				$TOT_REQ_CON  	= $rowAllD->TOT_REQ_CON;
				$TOT_REQ_APP  	= $rowAllD->TOT_REQ_APP;
				$TOT_REQ_REV  	= $rowAllD->TOT_REQ_REV;
				$TOT_REQ_REJ  	= $rowAllD->TOT_REQ_REJ;
				$TOT_REQ_CLS  	= $rowAllD->TOT_REQ_CLS;  // CLOSE
			// PO
				$TOT_PO     	= $rowAllD->TOT_PO;
				$TOT_PO_NEW  	= $rowAllD->TOT_PO_NEW;
				$TOT_PO_CON   	= $rowAllD->TOT_PO_CON;
				$TOT_PO_APP   	= $rowAllD->TOT_PO_APP;
				$TOT_PO_REV   	= $rowAllD->TOT_PO_REV;
				$TOT_PO_REJ   	= $rowAllD->TOT_PO_REJ;
				$TOT_PO_CLS  	= $rowAllD->TOT_PO_CLS;
			// IR
				$TOT_IR     	= $rowAllD->TOT_IR;
				$TOT_IR_NEW   	= $rowAllD->TOT_IR_NEW;
				$TOT_IR_CON   	= $rowAllD->TOT_IR_CON;
				$TOT_IR_APP   	= $rowAllD->TOT_IR_APP;
				$TOT_IR_REV   	= $rowAllD->TOT_IR_REV;
				$TOT_IR_REJ   	= $rowAllD->TOT_IR_REJ;
				$TOT_IR_CLS   	= $rowAllD->TOT_IR_CLS;
			// PINV
				$TOT_PINV   	= $rowAllD->TOT_PINV;
				$TOT_PINV_NEW 	= $rowAllD->TOT_PINV_NEW;
				$TOT_PINV_CON 	= $rowAllD->TOT_PINV_CON;
				$TOT_PINV_APP 	= $rowAllD->TOT_PINV_APP;
				$TOT_PINV_HP  	= $rowAllD->TOT_PINV_HP;
				$TOT_PINV_FP  	= $rowAllD->TOT_PINV_FP;
				$TOT_PINV_REJ 	= $rowAllD->TOT_PINV_REJ;
				$TOT_INV    	= $rowAllD->TOT_INV;
				$TOT_INV_FP   	= $rowAllD->TOT_INV_FP;
				$TOT_INV_NP   	= $rowAllD->TOT_INV_NP;
			// CB
				$TOT_CB     	= $rowAllD->TOT_CB;
				$TOT_CB_NEW   	= $rowAllD->TOT_CB_NEW;
				$TOT_CB_CON   	= $rowAllD->TOT_CB_CON;
				$TOT_CB_APP   	= $rowAllD->TOT_CB_APP;
				$TOT_CB_REV   	= $rowAllD->TOT_CB_REV;
				$TOT_CB_REJ   	= $rowAllD->TOT_CB_REJ;
				$TOT_CB_CLS   	= $rowAllD->TOT_CB_CLS;
			// OFF LETTER
				$TOT_OFF     	= $rowAllD->TOT_OFF;
				$TOT_OFF_NEW   	= $rowAllD->TOT_OFF_NEW;
				$TOT_OFF_CON   	= $rowAllD->TOT_OFF_CON;
				$TOT_OFF_APP   	= $rowAllD->TOT_OFF_APP;
				$TOT_OFF_REV   	= $rowAllD->TOT_OFF_REV;
				$TOT_OFF_REJ   	= $rowAllD->TOT_OFF_REJ;
				$TOT_OFF_CLS   	= $rowAllD->TOT_OFF_CLS;
			// SO
				$TOT_SO     	= $rowAllD->TOT_SO;
				$TOT_SO_NEW   	= $rowAllD->TOT_SO_NEW;
				$TOT_SO_CON   	= $rowAllD->TOT_SO_CON;
				$TOT_SO_APP   	= $rowAllD->TOT_SO_APP;
				$TOT_SO_REV   	= $rowAllD->TOT_SO_REV;
				$TOT_SO_REJ   	= $rowAllD->TOT_SO_REJ;
				$TOT_SO_CLS   	= $rowAllD->TOT_SO_CLS;
			// JO
				$TOT_JO     	= $rowAllD->TOT_JO;
				$TOT_JO_NEW   	= $rowAllD->TOT_JO_NEW;
				$TOT_JO_CON   	= $rowAllD->TOT_JO_CON;
				$TOT_JO_APP   	= $rowAllD->TOT_JO_APP;
				$TOT_JO_REV   	= $rowAllD->TOT_JO_REV;
				$TOT_JO_REJ   	= $rowAllD->TOT_JO_REJ;
				$TOT_JO_CLS   	= $rowAllD->TOT_JO_CLS;
			// MR
				$TOT_MR     	= $rowAllD->TOT_MR;
				$TOT_MR_NEW   	= $rowAllD->TOT_MR_NEW;
				$TOT_MR_CON   	= $rowAllD->TOT_MR_CON;
				$TOT_MR_APP   	= $rowAllD->TOT_MR_APP;
				$TOT_MR_REV   	= $rowAllD->TOT_MR_REV;
				$TOT_MR_REJ   	= $rowAllD->TOT_MR_REJ;
				$TOT_MR_CLS   	= $rowAllD->TOT_MR_CLS;
			// WO
				$TOT_WO     	= $rowAllD->TOT_WO;
				$TOT_WO_NEW   	= $rowAllD->TOT_WO_NEW;
				$TOT_WO_CON   	= $rowAllD->TOT_WO_CON;
				$TOT_WO_APP   	= $rowAllD->TOT_WO_APP;
				$TOT_WO_REV   	= $rowAllD->TOT_WO_REV;
				$TOT_WO_REJ   	= $rowAllD->TOT_WO_REJ;
				$TOT_WO_CLS   	= $rowAllD->TOT_WO_CLS;
			// OPN
				$TOT_OPN     	= $rowAllD->TOT_OPN;
				$TOT_OPN_NEW   	= $rowAllD->TOT_OPN_NEW;
				$TOT_OPN_CON   	= $rowAllD->TOT_OPN_CON;
				$TOT_OPN_APP   	= $rowAllD->TOT_OPN_APP;
				$TOT_OPN_REV   	= $rowAllD->TOT_OPN_REV;
				$TOT_OPN_REJ   	= $rowAllD->TOT_OPN_REJ;
				$TOT_OPN_CLS   	= $rowAllD->TOT_OPN_CLS;
			// SN
				$TOT_SN     	= $rowAllD->TOT_SN;
				$TOT_SN_NEW   	= $rowAllD->TOT_SN_NEW;
				$TOT_SN_CON   	= $rowAllD->TOT_SN_CON;
				$TOT_SN_APP   	= $rowAllD->TOT_SN_APP;
				$TOT_SN_REV   	= $rowAllD->TOT_SN_REV;
				$TOT_SN_REJ   	= $rowAllD->TOT_SN_REJ;
				$TOT_SN_CLS   	= $rowAllD->TOT_SN_CLS;
			// SINV
				$TOT_SINV     	= $rowAllD->TOT_SINV;
				$TOT_SINV_NEW   = $rowAllD->TOT_SINV_NEW;
				$TOT_SINV_CON   = $rowAllD->TOT_SINV_CON;
				$TOT_SINV_APP   = $rowAllD->TOT_SINV_APP;
				$TOT_SINV_REV   = $rowAllD->TOT_SINV_REV;
				$TOT_SINV_REJ   = $rowAllD->TOT_SINV_REJ;
				$TOT_SINV_CLS   = $rowAllD->TOT_SINV_CLS;
			// MC
				$TOT_MC     	= $rowAllD->TOT_MC;
				$TOT_MC_NEW   	= $rowAllD->TOT_MC_NEW;
				$TOT_MC_CON   	= $rowAllD->TOT_MC_CON;
				$TOT_MC_APP   	= $rowAllD->TOT_MC_APP;
				$TOT_MC_REV   	= $rowAllD->TOT_MC_REV;
				$TOT_MC_REJ   	= $rowAllD->TOT_MC_REJ;
				$TOT_MC_CLS   	= $rowAllD->TOT_MC_CLS;
			// BR
				$TOT_BR     	= $rowAllD->TOT_BR;
				$TOT_BR_NEW   	= $rowAllD->TOT_BR_NEW;
				$TOT_BR_CON   	= $rowAllD->TOT_BR_CON;
				$TOT_BR_APP   	= $rowAllD->TOT_BR_APP;
				$TOT_BR_REV   	= $rowAllD->TOT_BR_REV;
				$TOT_BR_REJ   	= $rowAllD->TOT_BR_REJ;
				$TOT_BR_CLS   	= $rowAllD->TOT_BR_CLS;
		endforeach;
	// END : GET QTY FOR EACH STATUS

    // GET EMP SETT DS_TYPE
      	$sqlDBoard  = "SELECT DS_TYPE FROM tbl_dash_sett_emp WHERE EMP_ID = '$Emp_ID'";
      	$resDBoard  = $this->db->query($sqlDBoard)->result();
      	foreach($resDBoard as $rowDB) :
	        $DS_TYPE  = $rowDB->DS_TYPE;

	        if($DS_TYPE == "GRF_LOGHIST")
	        {
				$sqlLogH  = "SELECT LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC FROM tbl_login_concl";
				$resLogH  = $this->db->query($sqlLogH)->result();
				foreach($resLogH as $rowLH) :
					$LCONC_NEVER  = $rowLH->LCONC_NEVER;
					$LCONC_SOMET  = $rowLH->LCONC_SOMET;
					$LCONC_OFTEN  = $rowLH->LCONC_OFTEN;
					$LCONC_FANT   = $rowLH->LCONC_FANTASTIC;
				endforeach;     
				$GRF_LOGHIST  = 1;
	        }
      	endforeach;

    // URL
	    $imgLoc		= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
		$showAllOUT = site_url('__180c2f/prjlist/?id='.$this->url_encryption_helper->encode_url($appName));
		$showAboutC = site_url('__180c2f/aboutcomp/?id='.$this->url_encryption_helper->encode_url($appName));
		$logURL 	= site_url('__180c2f/crtLogV/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
		$GraphicTitleText	= 3;					
?>
<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title>Dashboard</title>
	    <!-- Tell the browser to be responsive to screen width -->
	    <?php
		    $vers     	= $this->session->userdata['vers'];

	        $sqlcss 	= "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'css' AND isAct IN (1,2) AND cssjs_vers IN ('$vers', 'All')";
	        $rescss 	= $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk  = $rowcss->cssjs_lnk;
	            ?>
	                <link rel="stylesheet" href="<?php echo base_url($cssjs_lnk) ?>">
	            <?php
	        endforeach;

	        $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct IN (1,2) AND cssjs_vers IN ('$vers', 'All')";
	        $rescss = $this->db->query($sqlcss)->result();
	        foreach($rescss as $rowcss) :
	            $cssjs_lnk1  = $rowcss->cssjs_lnk;
	            ?>
	                <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
	            <?php
        	endforeach;
		?>

		<!-- Google Font -->

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/chartjs/Chart.min.js'); ?>"></script>
	<style>
		#dailyProd {
		  	width: 100%;
		  	height: 400px;
		}

		#salesInfo {
		  	width: 100%;
		  	height: 400px;
		}
		
		.swal-overlay {
			  background-color: rgba(43, 165, 137, 0.45);
			}

		.preloader {
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  z-index: 9999;
		  background-color: #fff;
		}
		.preloader .loading {
		  position: absolute;
		  left: 50%;
		  top: 50%;
		  transform: translate(-50%,-50%);
		  font: 14px arial;
		}
		canvas{
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>
	<script type="text/javascript">
		function taskNotif()
		{
			var empId		= "<?=$DefEmp_ID?>";
			var formData 	= {EMPID : empId};

			$.ajax({
	            type: 'POST',
	            url: "<?php echo site_url('__180c2f/getTaskNotif')?>",
	            data: formData,
	            success: function(response)
	            {
	            	var myarrA  = response.split("|");
					var lgth 	= myarrA.length;
					if(lgth > 0)
					{
						for(i=0; i<lgth; i++)
						{
		            		myarr 		= myarrA[i].split("~");
			            	taskTtl 	= myarr[0];
			            	taskTMsg 	= myarr[1];

							title 		= taskTtl;
							message 	= taskTMsg;

							if(title != '')
							{
								window.createNotification
								({
									closeOnClick: true,
									displayCloseButton: true,
									positionClass: 'nfc-bottom-right',
									showDuration: 600000, // 1K = 1s
									theme: 'info' // success, warning, info, error, None
								})
								({
									title: title,
									message: message
								});
							}
						}
					}
	            }
	        });
		}

		function taskNotifApp()
		{
			var empId		= "<?=$DefEmp_ID?>";
			var formData 	= {EMPID : empId};

			$.ajax({
	            type: 'POST',
	            url: "<?php echo site_url('__180c2f/getTaskNotifAPP')?>",
	            data: formData,
	            success: function(response)
	            {
	            	var myarrA  = response.split("|");
					var lgth 	= myarrA.length;
					if(lgth > 0)
					{
						for(i=0; i<lgth; i++)
						{
		            		myarr 	= myarrA[i].split("~");
			            	docTtl 	= myarr[0];
			            	docList = myarr[1];
			            	docCat 	= myarr[2];

							title 	= docTtl;
							message = docList;

							if(title != '')
							{
								window.createNotification
								({
									closeOnClick: true,
									displayCloseButton: true,
									positionClass: 'nfc-bottom-right',
									showDuration: 600000, // 1K = 1s
									theme: 'warning' // success, warning, info, error, None
								})
								({
									title: title,
									message: message
								});
							}
						}
					}
	            }
	        });
		}
	</script>

	<?php
		// Top Bar
  			//______$this->load->view('template/topbar');

		// Left side column. contains the logo and sidebar
  			//______$this->load->view('template/sidebar');

		$ISREAD 	= $this->session->userdata['ISREAD'];
		$ISCREATE 	= $this->session->userdata['ISCREATE'];
		$ISAPPROVE 	= $this->session->userdata['ISAPPROVE'];
		$ISDWONL 	= $this->session->userdata['ISDWONL'];	
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl	= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl	= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$Translate	= $rowTransl->LangTransl;
			if($TranslCode == 'Dashboard')$Dashboard = $Translate;
			if($TranslCode == 'DailyNews')$DailyNews = $Translate;
			if($TranslCode == 'Supply')$Supply = $Translate;
			if($TranslCode == 'WIP')$WIP = $Translate;
			if($TranslCode == 'Waste')$Waste = $Translate;
			if($TranslCode == 'Sales')$Sales = $Translate;
			if($TranslCode == 'SalesOrder')$SalesOrder = $Translate;
			if($TranslCode == 'Production')$Production = $Translate;
			if($TranslCode == 'Shipment')$Shipment = $Translate;

			if($TranslCode == 'PurchaseRequest')$PurchaseRequest = $Translate;
			if($TranslCode == 'Purchase')$Purchase = $Translate;
			if($TranslCode == 'MtrReceipt')$MtrReceipt = $Translate;
			if($TranslCode == 'WorkOrder')$WorkOrder = $Translate;
			if($TranslCode == 'JobOpname')$JobOpname = $Translate;
			if($TranslCode == 'mtrUsed')$mtrUsed = $Translate;
			if($TranslCode == 'mcprj')$mcprj = $Translate;
			if($TranslCode == 'InvoiceRealization')$InvoiceRealization = $Translate;
		endforeach;
		
		$sql001   	= "SELECT browsername FROM browser";
		$res001 	= $this->db->query($sql001)->result();
		foreach($res001 as $row001) :
			$browsername 	= $row001->browsername;
			$trendbrowser	= $browsername;    
			$sql002   	= "SELECT total FROM browser WHERE browsername = '$trendbrowser'";
			$res002 	= $this->db->query($sql002)->result();
			foreach($res002 as $row002) :
				$total 	= $row002->total;
			endforeach;
		endforeach;
		
		$thisDay	= (int)date('d');
		$modDay		= $thisDay % 6;
		if($modDay == 0)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading.gif";
		}
		elseif($modDay == 1)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif";
		}
		elseif($modDay == 2)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading2.gif";
		}
		elseif($modDay == 3)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading3.gif";
		}
		elseif($modDay == 4)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading4.gif";
		}
		elseif($modDay == 5)
		{
			$loadFile = "assets/AdminLTE-2.0.5/dist/img/loading/loading5.gif";
		}

		if($LangID == 'IND')
		{
			$transL1	= "Mengaktfikan 'Fixed Layout'. Anda tidak dapat menggunakan 'Fixed Layout' dan 'Boxed Layout' bersama-sama.";
			$transL2	= "Mengaktifkan 'Boxed Layout'.";
			$transL3	= "Sembunyikan menu utama sisi kiri.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Aktifitas";
			$transL6	= "Persediaan bahan produksi";
			$transL7	= "Sales Order disetujui per tahun berjalan";
			$transL8	= "Persediaan material dalam proses";
			$transL9	= "Persediaan barang jadi";
			$transL10	= "Progres Penyelesaian";
			$transL11	= "Informasi Umum";
			$transL12	= "Latest Login";
		}
		else
		{
			$transL1	= "Activate the 'Fixed Layout'. You can't use 'Fixed Layout' and 'Boxed Layout' together.";
			$transL2	= "Activate the boxed layout";
			$transL3	= "Toggle the left sidebar's state.";
			$transL4	= "SO Confirmed vs Approved";
			$transL5	= "Activity Graph";
			$transL6	= "Inventory of production materials";
			$transL7	= "Sales Order Approved Annualy";
			$transL8	= "Material inventory in process";
			$transL9	= "Finished goods inventory";
			$transL10	= "Completion Progress";
			$transL11	= "General Information";
			$transL12	= "Latest Login";
		}

		$imgPRJ		= base_url('assets/AdminLTE-2.0.5/project_image/'.$PRJCODEX.'/'.$PRJ_IMGNAME);
		if (!file_exists('assets/AdminLTE-2.0.5/project_image/'.$PRJCODEX))
		{
			$imgPRJ	= base_url('assets/AdminLTE-2.0.5/project_image/building.jpg');
		}
		$strD 		= date('d/m/y', strtotime($PRJDATEA));
		$endD 		= date('d/m/y', strtotime($PRJEDATA));
		$tgl0 		= strtotime(date('Y-m-d'));
		$tgl1 		= strtotime("$PRJDATEA");
		$tgl2 		= strtotime("$PRJEDATA");
		$jarak 		= $tgl2 - $tgl1;
		$remDay		= $tgl2 - $tgl0;
		$hari 		= $jarak / 60 / 60 / 24;
		$hariRem	= $remDay / 60 / 60 / 24;
		$hariP 		= $hari;
		if($hari == 0)
		{
			$hariP 		= 1;
			$hariRemP 	= 0;
		}
		else
			$hariRemP 	= $hariRem / $hariP * 100;

		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODEX));

		$TOT_RAP 	= 0;
		$TOT_BOQ 	= 0;
		$TOT_ADD 	= 0;
		$TOT_RAPP 	= 0;
		$s_00 		= "SELECT SUM(ITM_BUDG) AS TOT_RAP, SUM(BOQ_JOBCOST) AS TOT_BOQ, SUM(ADD_JOBCOST) AS TOT_ADD
						FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODEX' AND ISLASTH = 1";
		$r_00 		= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00):
			$TOT_BOQ 	= $rw_00->TOT_BOQ;
			$TOTBOQ		= $TOT_BOQ;
			if($TOT_BOQ == 0)
				$TOTBOQ = 1;
			$TOT_RAP 	= $rw_00->TOT_RAP;
			$TOT_RAPP 	= $TOT_RAP / $TOTBOQ * 100;
			$TOT_ADD 	= $rw_00->TOT_ADD;
		endforeach;

		$TOT_PROG 	= 0;
		$PROG_NOTES	= "-";
		$PROG_UPDAT = "00:00 am";
		$s_01 		= "SELECT Prg_RealAkum AS TOT_PROG, Prg_ProjNotes, Prg_LastUpdate FROM tbl_projprogres WHERE proj_Code = '$PRJCODEX' AND isShowRA = 1 ORDER BY Prg_Step DESC";
		$r_01 		= $this->db->query($s_01)->result();
		foreach($r_01 as $rw_01):
			$TOT_PROG 	= $rw_01->TOT_PROG;
			$PROG_NOTES	= $rw_01->Prg_ProjNotes;
			$PROG_UPDAT	= date('d M y H:i a', strtotime($rw_01->Prg_LastUpdate));
		endforeach;
		if($PROG_NOTES == '')
			$PROG_NOTES = "No comment";

		$M_PLAN 	= 0;
		$U_PLAN 	= 0;
		$S_PLAN 	= 0;
		$T_PLAN 	= 0;
		$I_PLAN 	= 0;
		$R_PLAN 	= 0;
		$O_PLAN 	= 0;

		$M_REAL 	= 0;
		$U_REAL 	= 0;
		$S_REAL 	= 0;
		$T_REAL 	= 0;
		$I_REAL 	= 0;
		$R_REAL 	= 0;
		$O_REAL 	= 0;

		$M_REALP 	= 0;
		$U_REALP 	= 0;
		$S_REALP 	= 0;
		$T_REALP 	= 0;
		$I_REALP 	= 0;
		$R_REALP 	= 0;
		$O_REALP 	= 0;

		$M_PR_VOL 	= 0;
		$M_PO_VOL 	= 0;
		$M_PO_VAL 	= 0;
		$M_IR_VAL 	= 0;
		$M_WO_VAL 	= 0;
		$M_OPN_VAL 	= 0;

		$U_PR_VOL 	= 0;
		$U_PO_VOL 	= 0;
		$U_PO_VAL 	= 0;
		$U_IR_VAL 	= 0;
		$U_WO_VAL 	= 0;
		$U_OPN_VAL 	= 0;

		$S_PR_VOL 	= 0;
		$S_PO_VOL 	= 0;
		$S_PO_VAL 	= 0;
		$S_IR_VAL 	= 0;
		$S_WO_VAL 	= 0;
		$S_OPN_VAL 	= 0;

		$T_PR_VOL 	= 0;
		$T_PO_VOL 	= 0;
		$T_PO_VAL 	= 0;
		$T_IR_VAL 	= 0;
		$T_WO_VAL 	= 0;
		$T_OPN_VAL 	= 0;

		$I_PR_VOL 	= 0;
		$I_PO_VOL 	= 0;
		$I_PO_VAL 	= 0;
		$I_IR_VAL 	= 0;
		$I_WO_VAL 	= 0;
		$I_OPN_VAL 	= 0;

		$R_PR_VOL 	= 0;
		$R_PO_VOL 	= 0;
		$R_PO_VAL 	= 0;
		$R_IR_VAL 	= 0;
		$R_WO_VAL 	= 0;
		$R_OPN_VAL 	= 0;

		$O_PR_VOL 	= 0;
		$O_PO_VOL 	= 0;
		$O_PO_VAL 	= 0;
		$O_IR_VAL 	= 0;
		$O_WO_VAL 	= 0;
		$O_OPN_VAL 	= 0;

		$s_MPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS M_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS M_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'M' AND ISLAST = 1";
		$r_MPLAN	= $this->db->query($s_MPLAN)->result();
		foreach($r_MPLAN as $rw_MPLAN):
			$TOT_BUDG		= $rw_MPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_MPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_MPLAN->TOT_ADDMIN;
			$M_TADDP		= $rw_MPLAN->TOT_ADDPLUS;
			$M_TADDM		= $rw_MPLAN->TOT_ADDMIN;
			$M_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$M_REAL 		= $rw_MPLAN->M_REAL;
			if($M_REAL == '') $M_REAL = 0;
			$M_PLANP 		= $M_PLAN;
			if($M_PLANP == 0) $M_PLANP = 1;

			$M_REALP 		= $M_REAL / $M_PLANP * 100;

			$M_PR_VOL		= $rw_MPLAN->PR_VOL;
			$M_PO_VOL		= $rw_MPLAN->PO_VOL;
			$M_PO_VAL		= $rw_MPLAN->PO_VAL;
			$M_IR_VAL		= $rw_MPLAN->IR_VAL;
			$M_WO_VAL		= $rw_MPLAN->WO_VAL;
			$M_OPN_VAL		= $rw_MPLAN->OPN_VAL;
		endforeach;

		$s_UPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS U_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS U_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'U' AND ISLAST = 1";
		$r_UPLAN	= $this->db->query($s_UPLAN)->result();
		foreach($r_UPLAN as $rw_UPLAN):
			$TOT_BUDG		= $rw_UPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_UPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_UPLAN->TOT_ADDMIN;
			$U_TADDP		= $rw_UPLAN->TOT_ADDPLUS;
			$U_TADDM		= $rw_UPLAN->TOT_ADDMIN;
			$U_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$U_REAL 		= $rw_UPLAN->U_REAL;
			if($U_REAL == '') $U_REAL = 0;
			$U_PLANP 		= $U_PLAN;
			if($U_PLANP == 0) $U_PLANP = 1;

			$U_REALP 		= $U_REAL / $U_PLANP * 100;

			$U_PR_VOL		= $rw_UPLAN->PR_VOL;
			$U_PO_VOL		= $rw_UPLAN->PO_VOL;
			$U_PO_VAL		= $rw_UPLAN->PO_VAL;
			$U_IR_VAL		= $rw_UPLAN->IR_VAL;
			$U_WO_VAL		= $rw_UPLAN->WO_VAL;
			$U_OPN_VAL		= $rw_UPLAN->OPN_VAL;
		endforeach;

		$s_SPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS S_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS S_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'S' AND ISLAST = 1";
		$r_SPLAN	= $this->db->query($s_SPLAN)->result();
		foreach($r_SPLAN as $rw_SPLAN):
			$TOT_BUDG		= $rw_SPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_SPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_SPLAN->TOT_ADDMIN;
			$S_TADDP		= $rw_SPLAN->TOT_ADDPLUS;
			$S_TADDM		= $rw_SPLAN->TOT_ADDMIN;
			$S_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$S_REAL 		= $rw_SPLAN->S_REAL;
			if($S_REAL == '') $S_REAL = 0;
			$S_PLANP 		= $S_PLAN;
			if($S_PLANP == 0) $S_PLANP = 1;
			
			$S_REALP 		= $S_REAL / $S_PLANP * 100;

			$S_PR_VOL		= $rw_SPLAN->PR_VOL;
			$S_PO_VOL		= $rw_SPLAN->PO_VOL;
			$S_PO_VAL		= $rw_SPLAN->PO_VAL;
			$S_IR_VAL		= $rw_SPLAN->IR_VAL;
			$S_WO_VAL		= $rw_SPLAN->WO_VAL;
			$S_OPN_VAL		= $rw_SPLAN->OPN_VAL;
		endforeach;

		$s_TPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS T_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS T_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'T' AND ISLAST = 1";
		$r_TPLAN	= $this->db->query($s_TPLAN)->result();
		foreach($r_TPLAN as $rw_TPLAN):
			$TOT_BUDG		= $rw_TPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_TPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_TPLAN->TOT_ADDMIN;
			$T_TADDP		= $rw_TPLAN->TOT_ADDPLUS;
			$T_TADDM		= $rw_TPLAN->TOT_ADDMIN;
			$T_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$T_REAL 		= $rw_TPLAN->T_REAL;
			if($T_REAL == '') $T_REAL = 0;
			$T_PLANP 		= $T_PLAN;
			if($T_PLANP == 0) $T_PLANP = 1;

			$T_REALP 	= $T_REAL / $T_PLANP * 100;

			$T_PR_VOL		= $rw_TPLAN->PR_VOL;
			$T_PO_VOL		= $rw_TPLAN->PO_VOL;
			$T_PO_VAL		= $rw_TPLAN->PO_VAL;
			$T_IR_VAL		= $rw_TPLAN->IR_VAL;
			$T_WO_VAL		= $rw_TPLAN->WO_VAL;
			$T_OPN_VAL		= $rw_TPLAN->OPN_VAL;
		endforeach;

		$s_IPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS I_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS I_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'I' AND ISLAST = 1";
		$r_IPLAN	= $this->db->query($s_IPLAN)->result();
		foreach($r_IPLAN as $rw_IPLAN):
			$TOT_BUDG		= $rw_IPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_IPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_IPLAN->TOT_ADDMIN;
			$I_TADDP		= $rw_IPLAN->TOT_ADDPLUS;
			$I_TADDM		= $rw_IPLAN->TOT_ADDMIN;
			$I_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$I_REAL 		= $rw_IPLAN->I_REAL;
			if($I_REAL == '') $I_REAL = 0;
			$I_PLANP 		= $I_PLAN;
			if($I_PLANP == 0) $I_PLANP = 1;

			$I_REALP 		= $I_REAL / $I_PLANP * 100;

			$I_PR_VOL		= $rw_IPLAN->PR_VOL;
			$I_PO_VOL		= $rw_IPLAN->PO_VOL;
			$I_PO_VAL		= $rw_IPLAN->PO_VAL;
			$I_IR_VAL		= $rw_IPLAN->IR_VAL;
			$I_WO_VAL		= $rw_IPLAN->WO_VAL;
			$I_OPN_VAL		= $rw_IPLAN->OPN_VAL;
		endforeach;
		
		$s_RPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS R_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS R_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'R' AND ISLAST = 1";
		$r_RPLAN	= $this->db->query($s_RPLAN)->result();
		foreach($r_RPLAN as $rw_RPLAN):
			$TOT_BUDG		= $rw_RPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_RPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_RPLAN->TOT_ADDMIN;
			$R_TADDP		= $rw_RPLAN->TOT_ADDPLUS;
			$R_TADDM		= $rw_RPLAN->TOT_ADDMIN;
			$R_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$R_REAL 		= $rw_RPLAN->R_REAL;
			if($R_REAL == '') $R_REAL = 0;
			$R_PLANP 		= $R_PLAN;
			if($R_PLANP == 0) $R_PLANP = 1;

			$R_REALP 		= $R_REAL / $R_PLANP * 100;

			$R_PR_VOL		= $rw_RPLAN->PR_VOL;
			$R_PO_VOL		= $rw_RPLAN->PO_VOL;
			$R_PO_VAL		= $rw_RPLAN->PO_VAL;
			$R_IR_VAL		= $rw_RPLAN->IR_VAL;
			$R_WO_VAL		= $rw_RPLAN->WO_VAL;
			$R_OPN_VAL		= $rw_RPLAN->OPN_VAL;
		endforeach;
		
		$s_OPLAN	= "SELECT SUM(ITM_BUDG) AS TOT_BUDG, SUM(AMD_VAL) AS TOT_ADDPLUS, SUM(AMDM_VAL) AS TOT_ADDMIN,
							-- SUM(IR_VAL+IR_VAL_R+WO_VAL+WO_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R-WO_CVAL) AS O_REAL,
							SUM(IR_VAL+IR_VAL_R+OPN_VAL+OPN_VAL_R+VCASH_VAL+VCASH_VAL_R+VLK_VAL+VLK_VAL_R+PPD_VAL+PPD_VAL_R) AS O_REAL,
							SUM(PR_VOL-PR_CVOL) AS PR_VOL, SUM(PO_VOL-PO_CVOL) AS PO_VOL, SUM(PO_VAL-PO_CVAL) AS PO_VAL, SUM(IR_VAL) AS IR_VAL,
							SUM(WO_VAL-WO_CVAL) AS WO_VAL, SUM(OPN_VAL) AS OPN_VAL
						FROM tbl_joblist_detail_$PRJCODEVW WHERE ITM_GROUP = 'O' AND ISLAST = 1";
		$r_OPLAN	= $this->db->query($s_OPLAN)->result();
		foreach($r_OPLAN as $rw_OPLAN):
			$TOT_BUDG		= $rw_OPLAN->TOT_BUDG;
			$TOT_ADDPLUS	= $rw_OPLAN->TOT_ADDPLUS;
			$TOT_ADDMIN		= $rw_OPLAN->TOT_ADDMIN;
			$O_TADDP		= $rw_OPLAN->TOT_ADDPLUS;
			$O_TADDM		= $rw_OPLAN->TOT_ADDMIN;
			$O_PLAN			= $TOT_BUDG + $TOT_ADDPLUS - $TOT_ADDMIN;
			$O_REAL 		= $rw_OPLAN->O_REAL;
			if($O_REAL == '') $O_REAL = 0;
			$O_PLANP 		= $O_PLAN;
			if($O_PLANP == 0) $O_PLANP = 1;

			$O_REALP 		= $O_REAL / $O_PLANP * 100;

			$O_PR_VOL		= $rw_OPLAN->PR_VOL;
			$O_PO_VOL		= $rw_OPLAN->PO_VOL;
			$O_PO_VAL		= $rw_OPLAN->PO_VAL;
			$O_IR_VAL		= $rw_OPLAN->IR_VAL;
			$O_WO_VAL		= $rw_OPLAN->WO_VAL;
			$O_OPN_VAL		= $rw_OPLAN->OPN_VAL;
		endforeach;

		$TOTAL_ADD 		= $M_TADDP - $M_TADDM + $U_TADDP - $U_TADDM + $S_TADDP - $S_TADDM + $T_TADDP - $T_TADDM + $I_TADDP - $I_TADDM + $R_TADDP - $R_TADDM + $O_TADDP - $O_TADDM;
		// AMANDEMEN DIAMBIL DARI SI
			$TOTAL_ADD	= 0;
	        $sqlGET_ADD	= "SELECT SUM(SI_APPVAL) AS TOTAL_ADD FROM tbl_siheader WHERE PRJCODE = '$PRJCODEX' AND SI_STAT IN (3,6)";
	        $resGET_ADD	= $this->db->query($sqlGET_ADD)->result();
	        foreach($resGET_ADD as $rowGET_ADD):
	            $TOTAL_ADD		= $rowGET_ADD->TOTAL_ADD;
	        endforeach;

		$MC_PROGAPP		= 0;
		$MC_PROGAPPVAL	= 0;
        $sqlGET_MC		= "SELECT SUM(MC_PROGCUR) AS MC_PROGCUR, SUM(MC_PROG) AS MC_PROG, SUM(MC_PROGAPP) AS MC_PROGAPP, 
								SUM(MC_PROGVAL) AS MC_PROGVAL, SUM(MC_PROGAPPVAL) AS MC_PROGAPPVAL FROM tbl_mcheader 
                            WHERE PRJCODE = '$PRJCODEX' AND MC_STAT = 3 ORDER BY MC_STAT DESC LIMIT 1";
        $resGET_MC		= $this->db->query($sqlGET_MC)->result();
        foreach($resGET_MC as $rowGET_MC):
            $MC_PROGCUR		= $rowGET_MC->MC_PROGCUR;
            $MC_PROG		= $rowGET_MC->MC_PROG;
            $MC_PROGAPP		= $rowGET_MC->MC_PROGAPP;
            $MC_PROGVAL		= $rowGET_MC->MC_PROGVAL;
            $MC_PROGAPPVAL	= $rowGET_MC->MC_PROGAPPVAL;
        endforeach;

        $PRJP_DATE 		= date('Y-m-d');
		$PROG_PLAN		= 0;
		$PROG_REAL		= 0;
		$PROG_PLAN_K	= 0;
		$PROG_REAL_K	= 0;
		$PROG_REAL_V	= 0;
		$PRJP_STEP 		= "";
        /*$s_PROGPRJ	= "SELECT Prg_Plan, Prg_PlanAkum, Prg_Real, Prg_RealAkum
        					FROM tbl_projprogres WHERE proj_Code = '$PRJCODEX' AND DATE(NOW()) BETWEEN Prg_Date1 AND Prg_Date2";*/
        $s_PROGPRJ		= "SELECT A.PRJP_STEP, B.Prg_Date1, B.Prg_Plan, B.Prg_PlanAkum, A.PRJP_DATE, A.PRJP_GTOT, A.PRJP_PERC_PI
        					FROM tbl_project_progress A INNER JOIN tbl_projprogres B ON A.PRJP_STEP = B.Prg_Step AND A.PRJCODE = B.proj_Code
        					WHERE PRJCODE = '$PRJCODEX' ORDER BY PRJP_STEP DESC LIMIT 1";
        $r_PROGPRJ		= $this->db->query($s_PROGPRJ)->result();
        foreach($r_PROGPRJ as $rw_PROGPRJ):
            //$PROG_PLAN	= $rw_PROGPRJ->Prg_Plan;
            //$PROG_REAL	= $rw_PROGPRJ->Prg_Real;
            $PRJP_STEP		= $rw_PROGPRJ->PRJP_STEP;
            $PRJP_DATE		= $rw_PROGPRJ->PRJP_DATE;
            $PROG_PLAN_K	= $rw_PROGPRJ->Prg_PlanAkum;
            $PROG_REAL_K	= $rw_PROGPRJ->PRJP_PERC_PI;
        endforeach;

    	$PROG_REAL_V 	= $PROG_REAL_K * ($PRJCOSTX + $TOTAL_ADD) / 100;
	?>
	<body class="<?php echo $appBody; ?>" onLoad="taskNotifApp();taskNotif();">
		<!-- <input type="hidden" name="isEnd" id="isEnd" value="<?php // echo $isEnd; ?>"> -->
		<input type="hidden" name="hasSv" id="hasSv" value="<?php echo $vLogV; ?>">
		<input type="hidden" name="EMPID" id="EMPID" value="<?php echo $Emp_ID; ?>">
		<div class="preloader">
			<div class="loading">
				<img src="<?php echo base_url() . $loadFile; ?>" width="150">
			</div>
		</div>

		<section class="content">
			<div class="row">
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-yellow-gradient"><i class="fa fa-calendar"></i></span>
					    <?php
							/*$cLogTask	= "tbl_project WHERE proj = '$Emp_ID'";
							$cLogTaskv	= $this->db->count_all($cLogTask);*/
							$cPRJACT	= "tbl_project WHERE PRJCATEG IN ('GDG','SPL') AND PRJSTAT = 1";
							$cPRJACTv	= $this->db->count_all($cPRJACT);
							$cPRJACTALL	= "tbl_project WHERE PRJCATEG IN ('GDG','SPL')";
							$cPRJACTALLV= $this->db->count_all($cPRJACTALL);
						?>
						<!-- <div class="info-box-content">
							<span class="info-box-text">PROJECT ACTIVE</span>
							<span class="info-box-number" style="font-size: 40px"><?php echo number_format($cPRJACTv,0); ?> <sup> / <?php echo number_format($cPRJACTALLV,0); ?></sup></span>
						</div> -->
						<div class="info-box-content">
							<span class="info-box-text">
								PERIODE<br>
								<?php
									$timeRD = '<span class="info-box-number">S : '.date('d/m/Y', strtotime($PRJDATEA)).'</span>';
									$timeOD = '<span class="info-box-number">E : '.date('d/m/Y', strtotime($PRJEDATA)).'</span>';
								?>
								<?=$timeRD?>
								<?=$timeOD?>
							</span>
							<!-- <span class="info-box-number" style="font-size: 40px"><?php echo number_format($cPRJACTv,0); ?> <sup> / <?php echo number_format($cPRJACTALLV,0); ?></sup></span> -->
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-green-gradient"><i class="fa fa-clock-o"></i></span>
					    <?php
							/*$cLogHist	= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
							$cLogHistV	= $this->db->count_all($cLogHist);*/
							$TPROJ_ACT 	= 0;
							$s_PRJACT	= "SELECT SUM(PRJCOST) AS TPROJ_ACT FROM tbl_project WHERE PRJCATEG IN ('GDG','SPL') AND PRJSTAT = 1";
							$r_PRJACT	= $this->db->query($s_PRJACT)->result();
							foreach($r_PRJACT as $rw_PRJACT):
								$TPROJ_ACT 	= $rw_PRJACT->TPROJ_ACT;
							endforeach;

							$TPROJ_NACT = 0;
							$s_PRJNACT	= "SELECT SUM(PRJCOST) AS TPROJ_NACT FROM tbl_project WHERE PRJCATEG IN ('GDG','SPL')";
							$r_PRJNACT	= $this->db->query($s_PRJNACT)->result();
							foreach($r_PRJNACT as $rw_PRJNACT):
								$TPROJ_NACT = $rw_PRJNACT->TPROJ_NACT;
							endforeach;
						?>
						<!-- <div class="info-box-content">
							<span class="info-box-text">PROJECT VALUE</span>
							<span class="info-box-number" style="font-size: 20px"><div class="pull-right" title="Nilai Proyek Aktif"><?php echo number_format($TPROJ_ACT/1000000000,2); ?> M</div><br><div class="pull-right" title="Nilai Proyek Keseluruhan"><?php echo number_format($TPROJ_NACT/1000000000,2); ?> M</div></span>
						</div> -->
						<div class="info-box-content">
							TOTAL  <?=$d1?> hari
							<?php
								$timeRD 	= '<span class="info-box-number">R : '.$d2.' hari</span>';
								$timeOD 	= "OVER : -";
								if($timeR < 0)
								{
									$timeRD = '<span class="info-box-number">R : '.$d2.' hari</span>';
									$timeOD = '<span class="info-box-number">O : '.$timeR.' hari</span>';
								}
							?>
							<?=$timeRD?>
							<?=$timeOD?>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="info-box">
						<span class="info-box-icon bg-blue-gradient"><i class="fa fa-flag-o"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">
						    <?php
								$getCount		= "tbl_employee_proj WHERE Emp_ID = '$Emp_ID'";
								$resGetCount	= $this->db->count_all($getCount);
							?>
						    <form name="frmsrch" id="frmsrch" action="" method=POST>
						        <select name="selPRJCODE" id="selPRJCODE" class="form-control select2" onChange="chooseProject(this)">
						        	<!-- <option value="AllPRJ" <?php if($selPRJCODE == "AllPRJ") { ?> selected <?php } ?>> All</option> -->
									<?php                
						                if($resGetCount > 0 && $PRJSCATEG == 1)
						                {
						                    $getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
						                                        FROM tbl_employee_proj A
						                                        INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
						                                        WHERE A.Emp_ID = '$Emp_ID' ORDER BY B.PRJLEV, B.PRJCODE ASC";
						                    $resGetData 	= $this->db->query($getData)->result();
						                    foreach($resGetData as $rowData) :
						                        $Emp_ID 	= $rowData->Emp_ID;
						                        $proj_Code 	= $rowData->proj_Code;
						                        $proj_Name 	= $rowData->PRJNAME;
						                        ?>
						                        <option value="<?php echo $proj_Code; ?>" <?php if($proj_Code == $selPRJCODE) { ?> selected <?php } ?>><?php echo "$proj_Code - $proj_Name"; ?></option>
						                        <?php
						                    endforeach;
						                }
						            ?>
						        </select>
						        <input type="submit" name="submitPRJ" id="submitPRJ" style="display:none">
						    </form>
							</span>
							<span class="info-box-number"><?php echo number_format($resGetCount,0); ?><font size="2px" style="font-style: italic;"> - company(s) associated with you</font> </span>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12" title="Nilai Kontrak">
					<div class="small-box bg-aqua-gradient">
						<div class="inner">
							<h3 style="font-size: 25px"><?php echo number_format($PRJCOSTX,2,",","."); ?></h3>
							<p>Nilai Proyek</p>
						</div>
						<div class="icon">
							<i class="ion ion-home"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12" title="Total Pek. Tambah Kurang (SI)">
					<div class="small-box bg-yellow-gradient">
						<div class="inner">
							<h3 style="font-size: 25px"><?php echo number_format($TOTAL_ADD,2,",","."); ?></h3>
							<p>Addendum</p>
						</div>
						<div class="icon">
							<i class="ion ion-arrow-up-b"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12" title="Kontrak + Addendum">
					<div class="small-box bg-blue-gradient">
						<div class="inner">
						    <?php
						    	$TOT_PROJCOST 	= $PRJCOSTX + $TOTAL_ADD;
							?>
							<h3 style="font-size: 25px"><?php echo number_format($TOT_PROJCOST,2,",","."); ?></h3>
							<p>Nilai Proyek <i>+ Addendum</i></p>
						</div>
						<div class="icon">
							<i class="ion ion-speedometer"></i>
						</div>
					</div>
				</div>
                <div class="col-md-3 col-sm-6 col-xs-12" title="Realisasi * Total Kontrak">
					<div class="small-box bg-green-gradient">
						<div class="inner">
							<h3 style="font-size: 25px"><?php echo number_format($PROG_REAL_V,2,",","."); ?></h3>
							<p>Nilai Progres ( <?php echo number_format($PROG_REAL_K,2,",","."); ?> % )</p>
						</div>
						<div class="icon">
							<i class="ion ion-connection-bars"></i>
						</div>
					</div>
				</div>
		    </div>

		    <!-- START : STYLE CSS -->
				<style type="text/css">
				   .row.no-gutter {
				        margin-left: 0;
				        margin-right: 0;
				        margin-top: 0;
				        margin-bottom:0;
				        width:100%;
				  		height:100%;

				    }

				    .row.no-gutter [class*='col-']:not(:first-child),
				    .row.no-gutter [class*='col-']:not(:last-child) {
				        padding-right: 4px;
				        padding-left: 4px;
				        padding-top: 1px;
				        padding-bottom:1px;
				     }
				    .container
				    {
				        text-align:center;
				        font-family:Arial;
				        font-size: 62.5%;
					  	background-color:yellow;
					  	width:100%;
					  	height:200px;
					  	border:1px solid gray;
					}

					.box1{
						text-align: center;
						border: 1px solid #fff;
						padding-right: 0px;
					}
					.box2{
						text-align: center;
						border: 1px solid #fff;
						padding-left: 0px;
					}
					#img1{
					    /* background-image:url("<?php // echo $imgPRJ; ?>"); */
					    -moz-background-size:100% 100%;
					    -webkit-background-size:100% 100%;
					    background-size:100% 100%;
					    min-width: 100%;
	  					min-height: 100%;
					}
				</style>
		    <!-- END : STYLE CSS -->

			<!-- START 	: PROJECT INFORMATION AND REALISATION 				: UPDATED ON 19-03-2023 -->
				<div class="row">
	                <div class="col-md-8 col-sm-8 col-xs-12" style="padding-right: 2px;">
				        <div class="box box-info">
							<div class="box-body no-padding">
					            <div class="box-header with-border" style="text-align: center;">
					              	<h3 class="box-title">Progress Proyek <?php echo $PRJNAMEX; ?> (TimeP : <?=number_format($timP,2)?> %)</h3>
					            </div>
					            <div class="row">
									<div class="col-md-10 col-sm-10 box1">
										<div class="nav-tabs-custom">
						                    <div class="chart" id="line-chart" style="min-width: 310px; height: 395px; margin: 0 auto; display: none;"></div>
			                    			<div id="chart-proj_progress" style="min-width: 310px; height: 395px; margin: 0 auto"></div>
										</div>
									</div>
									<div class="col-md-2 col-sm-2 box2">
										<div class="pad box-pane-right bg-green-gradient" style="height: 395px;">
											<div class="description-block margin-bottom">
												<span class="description-text">Progres ( <?=$PRJP_STEP;?> )</span>
											</div>
											<div class="description-block margin-bottom">
												<div class="sparkbar" data-color="#fff">90,80,90,85,80,80,70</div>
												<span class="description-header" style="font-size: 23px">
													<?php
														if($PROG_REAL_K == 100)
															echo number_format($PROG_REAL_K)."%";
														else
															echo number_format($PROG_REAL_K,2)."%";
													?>
												</span>
												<span class="description-text">Realisasi</span>
											</div>

											<div class="description-block margin-bottom">
												<div class="sparkbar" data-color="#fff">90,50,90,70,61,83,63</div>
												<span class="description-header" style="font-size: 23px">
													<?php
														if($PROG_PLAN_K == 100)
															echo number_format($PROG_PLAN_K)."%";
														else
															echo number_format($PROG_PLAN_K,2)."%";
													?>
												</span>
												<span class="description-text">Rencana</span>
											</div>

											<?php
												$PROG_REAL_DEV	= $PROG_REAL_K - $PROG_PLAN_K;
											?>
											<div class="description-block">
												<div class="sparkbar" data-color="#fff">90,70,90,70,75,80,70</div>
												<span class="description-header" style="font-size: 23px">
													<?php
														if($PROG_REAL_DEV == 100)
															echo number_format($PROG_REAL_DEV)."%";
														else
															echo number_format($PROG_REAL_DEV,2)."%";
													?>
												</span>
												<span class="description-text">Deviasi</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
						$DS_PROGVAL = 1;
						$DS_MC 		= 1;
					?>

	                <div class="col-md-4 col-sm-4 col-xs-12" style="padding-bottom: 1px;">
						<div class="box box-warning" style="padding-bottom: 1px">
							<div class="box-header with-border">
								<h3 class="box-title">Realisasi Pelaksanaan Proyek</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse" style="display: none;"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove" style="display: none;"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body no-padding" style="padding-bottom: 1px;">
								<div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="chart-responsive">
											<canvas id="pieChart" height="150"></canvas>
										</div>
									</div>
									<?php
										$PeriodeD 	= date('Y-m-d');
										$LinkBef_M	= "$PRJCODEX~$PeriodeD~M";
										$LinkBef_U	= "$PRJCODEX~$PeriodeD~U";
										$LinkBef_S	= "$PRJCODEX~$PeriodeD~S";
										$LinkBef_T	= "$PRJCODEX~$PeriodeD~T";
										$LinkBef_I	= "$PRJCODEX~$PeriodeD~I";
										$LinkBef_R	= "$PRJCODEX~$PeriodeD~R";
										$LinkBef_O	= "$PRJCODEX~$PeriodeD~O";
										$secPrintM	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_M));
										$secPrintU	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_U));
										$secPrintS	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_S));
										$secPrintT	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_T));
										$secPrintI	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_I));
										$secPrintR	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_R));
										$secPrintO	= site_url('c_gl/c_r3p0r77l/idxpr0f17l005vwD/?id='.$this->url_encryption_helper->encode_url($LinkBef_O));
									?>
				                    <script>
										function showDetB(LinkD)
										{
											w = 1000;
											h = 550;
											var left = (screen.width/2)-(w/2);
											var top = (screen.height/2)-(h/2);
											window.open(LinkD,'popUpWindow','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
										}
				                    </script>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<ul class="chart-legend clearfix">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-red"></i>
														<span> M</span> <span class="pull-right" title="<?="dari ".number_format($M_PLANP,2)." (".number_format($M_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintM; ?>')" style="cursor: pointer;">
																<?=number_format($M_REAL)." (".number_format($M_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-green"></i>
														<span> U</span> <span class="pull-right" title="<?="dari ".number_format($U_PLANP,2)." (".number_format($U_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintU; ?>')" style="cursor: pointer;">
																<?=number_format($U_REAL)." (".number_format($U_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-yellow"></i>
														<span> S</span> <span class="pull-right" title="<?="dari ".number_format($S_PLANP,2)." (".number_format($S_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintS; ?>')" style="cursor: pointer;">
																<?=number_format($S_REAL)." (".number_format($S_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-aqua"></i> 
														<span> T</span> <span class="pull-right" title="<?="dari ".number_format($T_PLANP,2)." (".number_format($T_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintT; ?>')" style="cursor: pointer;">
																<?=number_format($T_REAL)." (".number_format($T_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-gray"></i>
														<span> I</span> <span class="pull-right" title="<?="dari ".number_format($I_PLANP,2)." (".number_format($I_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintT; ?>')" style="cursor: pointer;">
																<?=number_format($I_REAL)." (".number_format($I_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-gray"></i>
														<span> R</span> <span class="pull-right" title="<?="dari ".number_format($I_PLANP,2)." (".number_format($R_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintR; ?>')" style="cursor: pointer;">
																<?=number_format($R_REAL)." (".number_format($R_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<li><i class="fa fa-circle-o text-light-blue"></i>
														<span> O</span> <span class="pull-right" title="<?="dari ".number_format($O_PLANP,2)." (".number_format($O_REALP,2)."%)"?>">
															<a onclick="showDetB('<?php echo $secPrintO; ?>')" style="cursor: pointer;">
																<?=number_format($O_REAL)." (".number_format($O_REALP,1)."%)";?>
															</a>
														</span>
													</li>
												</div>
												<?php
													$TOT_PLANP 	= $M_PLAN + $U_PLAN + $S_PLAN + $T_PLAN + $I_PLAN + $R_PLAN + $O_PLAN;
													$TOT_REAL 	= $M_REAL + $U_REAL + $S_REAL + $T_REAL + $I_REAL + $R_REAL + $O_REAL;

													if($TOT_PLANP == 0)
														$TOT_PLANP = 1;

													$TOT_REALP 	= $TOT_REAL / $TOT_PLANP * 100;
												?>
												<div class="col-md-12 col-sm-12 col-xs-12">
													<span class="pull-right" title="<?="dari ".number_format($O_PLANP,2)." (".number_format($O_REALP,2)."%)"?>">
														<a onclick="#" style="cursor: pointer; font-weight: bold; font-size: 14px; color: darkred;">
															<?=number_format($TOT_REAL)." (".number_format($TOT_REALP,1)."%)";?>
														</a>
													</span>
												</div>
											</div>
										</ul>
									</div>
								
									<script type="text/javascript">
										// - PIE CHART -
										// -------------
										// Get context with jQuery - using jQuery's .get() method.
										var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
										var pieChart       = new Chart(pieChartCanvas);
										var PieData        = [
											{
												value    : <?=$M_REAL?>,
												color    : '#f56954',
												highlight: '#f56954',
												label    : 'Material (M)'
											},
											{
												value    : <?=$U_REAL?>,
												color    : '#00a65a',
												highlight: '#00a65a',
												label    : 'Upah (U)'
											},
											{
												value    : <?=$S_REAL?>,
												color    : '#f39c12',
												highlight: '#f39c12',
												label    : 'Subkon (S)'
											},
											{
												value    : <?=$T_REAL?>,
												color    : '#00c0ef',
												highlight: '#00c0ef',
												label    : 'Alat (T)'
											},
											{
												value    : <?=$I_REAL?>,
												color    : '#d2d6de',
												highlight: '#d2d6de',
												label    : 'Lain-Lain (I)'
											},
											{
												value    : <?=$O_REAL?>,
												color    : '#3c8dbc',
												highlight: '#3c8dbc',
												label    : 'Overhead (O)'
											}
										];
										var pieOptions     = {
											// Boolean - Whether we should show a stroke on each segment
											segmentShowStroke    : true,
											// String - The colour of each segment stroke
											segmentStrokeColor   : '#fff',
											// Number - The width of each segment stroke
											segmentStrokeWidth   : 1,
											// Number - The percentage of the chart that we cut out of the middle
											percentageInnerCutout: 50, // This is 0 for Pie charts
											// Number - Amount of animation steps
											animationSteps       : 100,
											// String - Animation easing effect
											animationEasing      : 'easeOutBounce',
											// Boolean - Whether we animate the rotation of the Doughnut
											animateRotate        : true,
											// Boolean - Whether we animate scaling the Doughnut from the centre
											animateScale         : false,
											// Boolean - whether to make the chart responsive to window resizing
											responsive           : true,
											// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
											maintainAspectRatio  : false,
											// String - A legend template
											legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
											// String - A tooltip template
											//tooltipTemplate      : '<%=value %> <%=label%>'
											tooltipTemplate      : '<%=label%>'
										};
										// Create pie or douhnut chart
										// You can switch between pie and douhnut using the method below.
										pieChart.Doughnut(PieData, pieOptions);
										// -----------------
										// - END PIE CHART -
										// -----------------
									</script>

									<?php
				                        $MC_STEP 	= 0;
				                        $MC_DATE 	= 0;
				                        $MC_PROG 	= 0;
				                        $MC_STAT 	= 0;
				                        $MC_NOTES 	= 0;
				                        $TOT_MC 	= 0;
										/*$s_MC	= "SELECT PRJCODE, MC_STEP, MC_DATE, MC_PROG, MC_STAT, MC_NOTES, (MC_TOTVAL+ MC_TOTVAL_PPn-MC_TOTVAL_PPh) AS TOT_MC
													FROM tbl_mcheader WHERE PRJCODE = '$PRJCODEX' ORDER BY MC_STEP";*/
										$s_MC	= "SELECT PRJCODE, MC_STEP, MC_DATE, MC_PROG, MC_STAT, MC_NOTES, MC_PROGVAL AS TOT_MC
													FROM tbl_mcheader WHERE PRJCODE = '$PRJCODEX' ORDER BY MC_STEP DESC LIMIT 1";
					                    $r_MC 	= $this->db->query($s_MC)->result();
					                    foreach($r_MC as $rw_MC) :
					                        $MC_STEP 	= $rw_MC->MC_STEP;
					                        $MC_DATE 	= $rw_MC->MC_DATE;
					                        $MC_PROG 	= $rw_MC->MC_PROG;
					                        $MC_STAT 	= $rw_MC->MC_STAT;
					                        $MC_NOTES 	= $rw_MC->MC_NOTES;
					                        //$TOT_MC 	= $TOT_MC+$rw_MC->TOT_MC;
					                        $TOT_MC 	= $rw_MC->TOT_MC;
					                    endforeach;

				                        $TOT_BR 	= 0;
										$s_MC		= "SELECT SUM(BR_TOTAM) AS TOT_REC
														FROM tbl_br_header WHERE PRJCODE = '$PRJCODEX' AND BR_RECTYPE = 'PRJ' AND BR_STAT = 3";
					                    $r_MC 		= $this->db->query($s_MC)->result();
					                    foreach($r_MC as $rw_MC) :
					                        $TOT_BR 	= $rw_MC->TOT_REC;		// BELUM TERMASUK DP
					                    endforeach;

					                    $TOT_MCP 	= $TOT_MC;
					                    if($TOT_MC == 0)
					                    	$TOT_MCP= 1;
					                    $BR_PERC 	= $TOT_BR / $TOT_MCP * 100;

					                    $BR_REM 	= $TOT_MC - $TOT_BR;
					                    $BRPERC_REM = $BR_REM / $TOT_MCP * 100;
									?>
								</div>
								<div class="row" style="padding-top: 10px;">
					                <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="small-box bg-teal-gradient">
											<div style="padding-top: 2px; padding-left: 6px; padding-bottom: 2px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															if($MC_PROG == 100)
																echo number_format($MC_PROG)."%";
															else
																echo number_format($MC_PROG,2)."%";
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Progres MC</span>
											</div>
										</div>
									</div>
					                <div class="col-md-8 col-sm-8 col-xs-12" style="padding-bottom: 0px;">
										<div class="small-box bg-teal-gradient">
											<div style="padding-top: 2px; padding-left: 6px; padding-bottom: 1px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															echo number_format($TOT_MC,2,",",".");
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Nilai Progres MC</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
					                <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="small-box bg-aqua-gradient">
											<div style="padding-top: 1px; padding-left: 6px; padding-bottom: 1px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															if($BR_PERC == 100)
																echo number_format($BR_PERC)."%";
															else
																echo number_format($BR_PERC,2)."%";
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Terbayar</span>
											</div>
										</div>
									</div>
					                <div class="col-md-8 col-sm-6 col-xs-12">
										<div class="small-box bg-aqua-gradient">
											<div style="padding-top: 1px; padding-left: 6px; padding-bottom: 1px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															echo number_format($TOT_BR,2,",",".");
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Nilai Terbayar</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
					                <div class="col-md-4 col-sm-6 col-xs-12">
										<div class="small-box bg-maroon-gradient">
											<div style="padding-top: 1px; padding-left: 6px; padding-bottom: 1px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															if($BRPERC_REM == 100)
																echo number_format($BRPERC_REM)."%";
															else
																echo number_format($BRPERC_REM,2)."%";
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Belum Terbayar</span>
											</div>
										</div>
									</div>
					                <div class="col-md-8 col-sm-6 col-xs-12">
										<div class="small-box bg-maroon-gradient">
											<div style="padding-top: 1px; padding-left: 6px; padding-bottom: 1px;">
												<h3 style="font-size: 25px">
													<?php
														if($DS_PROGVAL == 1)
														{
															echo number_format($BR_REM,2,",",".");
														}
														else
														{
															?>
													        	<i class="fa fa-eye-slash"></i>
															<?php
														}
													?>
												</h3>
												<span>Nilai Belum Terbayar</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="<?php if($DS_MC == 1){ ?>col-md-5<?php } else { ?>col-md-12<?php } ?> col-sm-12 col-xs-12" style="padding-bottom: 1px;">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Catatan progres proyek </h3><span class="direct-chat-timestamp pull-right"><?=$PROG_UPDAT?></span>
							</div>
							<div class="box-body">
				                <div class="timeline-body">
				                  	<?=$PROG_NOTES?>
				                </div>
				                <div class="timeline-footer" style="display: none;">
				                  	<a class="btn btn-primary btn-xs">Read more</a>
				                </div>
							</div>
						</div>
					</div>
					<?php if($DS_MC == 1){ ?>
						<div class="col-md-7 col-sm-12 col-xs-12" style="padding-bottom: 1px;">
							<div class="box box-success">
								<div class="box-header with-border" style="padding-left: 0px;">
									<h3 class="box-title">MC Progress </h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse" style="display: none;"><i class="fa fa-minus"></i></button>
									<button type="button" class="btn btn-box-tool" data-widget="remove" style="display: none;"><i class="fa fa-times"></i></button>
								</div>
								</div>
								<div class="box-body no-padding">
					                <div class="timeline-body" style="padding-bottom: 0px">
					                  	<div class="search-table-outter">
								            <table id="example" class="table table-bordered table-striped" width="100%">
								                <thead>
								                    <tr>
								                        <th style="vertical-align:middle; text-align:center" width="2%">&nbsp;</th>
								                        <th style="vertical-align:middle; text-align:center" width="18%" nowrap>Nomor</th>
								                        <th width="10%" nowrap="nowrap" style="vertical-align:middle; text-align:center">Tgl.</th>
								                        <th width="5%" style="vertical-align:middle; text-align:center">Tahap</th>
								                        <th style="vertical-align:middle; text-align:center" width="15%" nowrap>Nilai Tagih</th>
								                        <th style="vertical-align:middle; text-align:center" width="15%" nowrap>Dibayar</th>
								                        <th style="vertical-align:middle; text-align:center" width="35%" nowrap>Keterangan</th>
								                  	</tr>
								                </thead>
								                <tbody>
								                	<?php
								                		$noMC 	= 0;
														$s_MC	= "SELECT MC_MANNO, MC_STEP, MC_DATE, MC_ENDDATE, MC_TOTVAL, MC_TOTVAL_PPn, MC_TOTVAL_PPh, MC_NOTES
																		FROM tbl_mcheader WHERE PRJCODE = '$PRJCODEX'
																		ORDER BY MC_STEP ASC";
														$r_MC	= $this->db->query($s_MC)->result();
														foreach($r_MC as $rw_MC):
															$noMC 		= $noMC+1;
															$MC_MANNO	= $rw_MC->MC_MANNO;
															$MC_STEP	= $rw_MC->MC_STEP;
															$MC_DATE	= $rw_MC->MC_DATE;
															$MC_ENDDATE	= $rw_MC->MC_ENDDATE;
															$MC_TOTVAL	= $rw_MC->MC_TOTVAL;
															$MC_NOTES	= $rw_MC->MC_NOTES;
															?>
																<tr>
											                        <td><?=$noMC?>.</td>
											                        <td nowrap><?=$MC_MANNO?></td>
											                        <td nowrap><?=$MC_DATE?></td>
											                        <td nowrap><?=$MC_STEP?></td>
											                        <td nowrap><?=$MC_TOTVAL?></td>
											                        <td nowrap><?=$MC_TOTVAL?></td>
											                        <td ><?=$MC_NOTES?></td>
											                    </tr>
															<?php
														endforeach;
													?>
								                </tbody>
								            </table>
								        </div>
					                </div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			<!-- END 	: PROJECT INFORMATION AND REALISATION -->

		    <!--- START : PURCHASING GRAPH 									: UPDATED ON 19-03-2023 -->
				<?php
					$GRF_PURCH = 1;
					if($GRF_PURCH == 1) { ?>
			      	<div class="row">
				        <section class="col-lg-12">
				        	<div class="box box-warning">
				        		<div class="box-body">
									<!-- START 	: PURCHASING GRAPH / DOCUMENT PROGRESS (CONTR.) -->
										<div class="nav-tabs-custom">
											<div class="col-md-8">
												<div class="tab-content">
									               	<canvas class="chart tab-pane" id="purchase-chart" style="position: relative; height: 300px;"></canvas>
												</div>
											</div>
							                <?php //if($PRJSCATEG == 1 && $GRF_GOALC == 1) {
												$PR_VAL 	= 0;
												$PR_TOT		= 0;
												$PR_TOTD	= 0;
												$PR_VOL 	= 0;
												$PR_TOT		= 0;
												$PR_TOTD	= 0;

												$PO_VAL 	= 0;
												$PO_TOT		= 0;
												$PO_TOTD	= 0;

												$IR_VAL 	= 0;
												$IR_TOT		= 0;
												$IR_TOTD	= 0;

												$WO_VAL 	= 0;
												$WO_TOT		= 0;
												$WO_TOTD	= 0;

												$OPN_VAL 	= 0;
												$OPN_TOT	= 0;
												$OPN_TOTD	= 0;

												if($PRJSCATEG == 1)
													$thePRJ	= "A.PRJCODE IN ($myPRJCODE)";
												else
													$thePRJ	= "";

												// START : GET APPROVED STATUS
													$TOT_PR_VOL = $M_PR_VOL + $U_PR_VOL + $S_PR_VOL + $T_PR_VOL + $I_PR_VOL + $R_PR_VOL + $O_PR_VOL;
													$TOT_PO_VOL = $M_PO_VOL + $U_PO_VOL + $S_PO_VOL + $T_PO_VOL + $I_PO_VOL + $R_PO_VOL + $O_PO_VOL;
													$TOT_PO_VAL = $M_PO_VAL + $U_PO_VAL + $S_PO_VAL + $T_PO_VAL + $I_PO_VAL + $R_PO_VAL + $O_PO_VAL;
													$TOT_IR_VAL = $M_IR_VAL + $U_IR_VAL + $S_IR_VAL + $T_IR_VAL + $I_IR_VAL + $R_IR_VAL + $O_IR_VAL;
													$TOT_WO_VAL = $M_WO_VAL + $U_WO_VAL + $S_WO_VAL + $T_WO_VAL + $I_WO_VAL + $R_WO_VAL + $O_WO_VAL;
													$TOT_OPN_VAL= $M_OPN_VAL + $U_OPN_VAL + $S_OPN_VAL + $T_OPN_VAL + $I_OPN_VAL + $R_OPN_VAL + $O_OPN_VAL;

													$TOT_TTK_IR= 0;
													$s_TTK 		= "SELECT SUM(TTK_REF1_AM) AS TOT_TTK_IR
																	FROM tbl_ttk_detail
																	WHERE PRJCODE = '$PRJCODEX' AND TTK_REF1_NUM LIKE 'LPM%' AND TTKD_STAT NOT IN (5,9)";
													$r_TTK		= $this->db->query($s_TTK)->result();
													foreach($r_TTK as $rw_TTK):
														$TOT_TTK_IR 	= $rw_TTK->TOT_TTK_IR;
													endforeach;

													$TOT_TTK_OPN= 0;
													$s_TTK 		= "SELECT SUM(TTK_REF1_AM) AS TOT_TTK_OPN
																	FROM tbl_ttk_detail
																	WHERE PRJCODE = '$PRJCODEX' AND TTK_REF1_NUM LIKE 'OP%' AND TTKD_STAT NOT IN (5,9)";
													$r_TTK		= $this->db->query($s_TTK)->result();
													foreach($r_TTK as $rw_TTK):
														$TOT_TTK_OPN 	= $rw_TTK->TOT_TTK_OPN;
													endforeach;
												// END : GET APPROVED STATUS

												// PERCENTATION
													$PR_VOL_APP	= $TOT_PR_VOL;
													if($PR_VOL_APP == 0)
														$PR_VOL_APP = 1;

													$PO_VOL_APP	= $TOT_PO_VAL;
													if($PO_VOL_APP == 0)
														$PO_VOL_APP = 1;

													$IR_VAL_APP 	= $TOT_IR_VAL;
													if($IR_VAL_APP == 0)
														$IR_VAL_APP = 1;

													$TTK_IR_APP 	= $TOT_TTK_IR;
													if($TTK_IR_APP == 0)
														$TTK_IR_APP = 1;

													$WO_VAL_APP 	= $TOT_WO_VAL;
													if($WO_VAL_APP == 0)
														$WO_VAL_APP = 1;

													$OPN_VAL_APP 	= $TOT_OPN_VAL;
													if($OPN_VAL_APP == 0)
														$OPN_VAL_APP = 1;

													$TTK_OPN_APP 	= $TOT_TTK_OPN;
													if($TTK_OPN_APP == 0)
														$TTK_OPN_APP = 1;

													$PR_PER		= $TOT_PO_VOL 	/ $PR_VOL_APP * 100;
													$PO_PER		= $TOT_IR_VAL 	/ $PO_VOL_APP * 100;
													$IR_PER		= $TOT_TTK_IR 	/ $IR_VAL_APP * 100;
													$WO_PER		= $TOT_OPN_VAL 	/ $WO_VAL_APP * 100;
													$OPN_PER	= $TOT_TTK_OPN 	/ $OPN_VAL_APP * 100;

													if($PR_PER == 0) $PR_PER = 1;
													if($PO_PER == 0) $PO_PER = 1;
													if($IR_PER == 0) $IR_PER = 1;
													if($WO_PER == 0) $WO_PER = 1;
													if($OPN_PER == 0) $OPN_PER = 1;
											?>
											<div class="col-md-4">
												<p class="text-center">
													<strong><?php //echo $transL10; ?></strong><br><!-- (Juta Rupiah) -->
												</p>

												<div class="progress-group">
													<span class="progress-text"><?php echo "$PurchaseRequest"; ?> (SPP)</span>
													<span class="progress-number" title="Total Volume SPP">
														<b><span title="Volume PO"><?php echo number_format($TOT_PO_VOL,2); ?></span></b>/<span title="Total Volume SPP"><?php echo number_format($TOT_PR_VOL,2); ?> (<?php echo number_format($PR_PER); ?>%)</span>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-aqua" style="width: <?php echo $PR_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $Purchase; ?> (OP)</span>
													<span class="progress-number" title="dalam juta rupiah">
														<b><span title="Volume LPM (dalam juta)"><?php echo number_format($TOT_IR_VAL/1000000,2); ?></span></b>/<span title="Total PO (dalam juta)"><?php echo number_format($TOT_PO_VAL/1000000,2); ?> (<?php echo number_format($PO_PER); ?>%)</span>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-blue" style="width: <?php echo $PO_PER; ?>%"></div>
													</div>
												</div>
												<div class="progress-group">
													<span class="progress-text"><?php echo $MtrReceipt; ?> (LPM)</span>
													<span class="progress-number" title="dalam juta rupiah">
														<b><span title="Total TTK (dalam juta)"><?php echo number_format($TOT_TTK_IR/1000000,2); ?></span></b>/<span title="Total LPM (dalam juta)"><?php echo number_format($TOT_IR_VAL/1000000,2); ?> (<?php echo number_format($IR_PER); ?>%)</span>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-green" style="width: <?php echo $IR_PER; ?>%"></div>
													</div>
												</div>

												<div class="progress-group">
													<span class="progress-text"><?php echo $WorkOrder; ?></span>
													<span class="progress-number" title="dalam juta rupiah">
														<b><span title="Volume Opname (dalam juta)"><?php echo number_format($TOT_OPN_VAL/1000000,2); ?></span></b>/<span title="Total SPK (dalam juta)"><?php echo number_format($TOT_WO_VAL/1000000,2); ?> (<?php echo number_format($WO_PER); ?>%)</span>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-yellow" style="width: <?php echo $WO_PER; ?>%"></div>
													</div>
												</div>
												<div class="progress-group">
													<span class="progress-text"><?php echo $JobOpname; ?> (OPN)</span>
													<span class="progress-number" title="dalam juta rupiah">
														<b><span title="Total TTK (dalam juta)"><?php echo number_format($TOT_TTK_OPN/1000000,2); ?></span></b>/<span title="Total Opname (dalam juta)"><?php echo number_format($TOT_OPN_VAL/1000000,2); ?> (<?php echo number_format($OPN_PER); ?>%)</span>
													</span>
													<div class="progress sm">
														<div class="progress-bar progress-bar-red" style="width: <?php echo $OPN_PER; ?>%"></div>
													</div>
												</div>
											</div>
										</div>
									<!-- END 	: PURCHASING GRAPH / DOCUMENT PROGRESS (CONTR.) -->
								</div>
							</div>
						</section>
					</div>
				<?php } ?>
		    <!--- END : PURCHASING GRAPH -->

			<!-- START 	: SALES GRAPH-->
				<?php if($GRF_SALES == 1) { ?>
		        	<div class="box box-success">
		        		<div class="box-body">
							<div class="row">
								<div class="col-md-8">
									<div class="col-sm-12">
										<div class="tab-content">
							               	<canvas class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></canvas>
										</div>
									</div>
								</div>
								<?php //if($PRJSCATEG == 2 && $GRF_GOALC == 1) { 
									$SO_2_AMN 	= 0;
									$SO_3_AMN 	= 0;
									$SO_TOT		= 0;
									$SO_PER		= 0;

									$JO_2_AMN 	= 0;
									$JO_3_AMN 	= 0;
									$JO_TOT		= 0;
									$JO_PER		= 0;

									$STF_2_AMN 	= 0;
									$STF_3_AMN 	= 0;
									$STF_TOT	= 0;
									$STF_PER	= 0;

									$SN_2_AMN 	= 0;
									$SN_3_AMN 	= 0;
									$SN_TOT		= 0;
									$SN_PER		= 0;

									if($PRJSCATEG == 1)
										$thePRJ	= "PRJCODE IN ($myPRJCODE) AND";
									else
										$thePRJ	= "PRJCODE_HO IN ($myPRJCODE) AND";

				                	$sqlSOGC		= "SELECT 	SUM(SO_2_AMN) AS SO_2_AMN, SUM(SO_3_AMN) AS SO_3_AMN,
				                								SUM(JO_2_AMN) AS JO_2_AMN, SUM(JO_3_AMN) AS JO_3_AMN,
				                								SUM(STF_2_AMN) AS STF_2_AMN, SUM(STF_3_AMN) AS STF_3_AMN,
				                								SUM(SN_2_AMN) AS SN_2_AMN, SUM(SN_3_AMN) AS SN_3_AMN
				                						FROM tbl_qty_coll WHERE $thePRJ YEAR(PERIODE) = YEAR(NOW())";
									$resSOGC		= $this->db->query($sqlSOGC)->result();
									foreach($resSOGC as $rowSOGC):
										$SO_2_AMN 	= $rowSOGC->SO_2_AMN;
										$SO_3_AMN 	= $rowSOGC->SO_3_AMN;
										$SO_TOT		= $SO_2_AMN + $SO_3_AMN;
										$SO_TOTD	= $SO_TOT;
										if($SO_TOT == 0) $SO_TOTD = 1;

										$JO_2_AMN 	= $rowSOGC->JO_2_AMN;
										$JO_3_AMN 	= $rowSOGC->JO_3_AMN;
										$JO_TOT		= $JO_2_AMN + $JO_3_AMN;

										$JO_3_AMND	= $JO_3_AMN;
										if($JO_3_AMN == 0) $JO_3_AMND = 1;
										$JO_TOTD	= $JO_TOT;
										if($JO_TOT == 0) $JO_TOTD = 1;

										$STF_2_AMN 	= $rowSOGC->STF_2_AMN;
										$STF_3_AMN 	= $rowSOGC->STF_3_AMN;
										$STF_TOT	= $STF_2_AMN + $STF_3_AMN;
										$STF_TOTD	= $STF_TOT;
										if($STF_TOT == 0) $STF_TOTD = 1;

										$SN_2_AMN 	= $rowSOGC->SN_2_AMN;
										$SN_3_AMN 	= $rowSOGC->SN_3_AMN;
										$SN_TOT		= $SN_2_AMN + $SN_3_AMN;
										$SN_TOTD	= $SN_TOT;
										if($SN_TOT == 0) $SN_TOTD = 1;

										// PERCENTATION
											$SO_PER		= $SO_3_AMN / $SO_TOTD * 100;
											$JO_PER		= $JO_3_AMN / $JO_TOTD * 100;
											$STF_PER	= $STF_3_AMN / $JO_3_AMND * 100;
											$SN_PER		= $SN_3_AMN / $SN_TOTD * 100;
									endforeach;
									if($SO_PER == 0) $SO_PER = 1;
									if($JO_PER == 0) $JO_PER = 1;
									if($STF_PER == 0) $STF_PER = 1;
									if($SN_PER == 0) $SN_PER = 1;
				                ?>
				                <div class="col-md-4">
				                    <p class="text-center">
				                    	<strong><?php //echo $transL10; ?> <?php //echo date('Y'); ?> <br><font size="2px" style="font-style: italic;"> <!-- ( dalam Juta ) --></font></strong>
				                    </p>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $SalesOrder; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($SO_3_AMN/1000000,2); ?></b>/<?php echo number_format($SO_TOT/1000000,2); ?></span>
				                        
				                    	<div class="progress sm">
				                            <div class="progress-bar progress-bar-aqua" style="width: <?php echo $SO_PER; ?>%"></div>
				                        </div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text">Job Order</span>
				                        <span class="progress-number"><b><?php echo number_format($JO_3_AMN/1000000,2); ?></b>/<?php echo number_format($JO_TOT/1000000,2); ?></span>
				                        
				                        <div class="progress sm">
				                            <div class="progress-bar progress-bar-red" style="width: <?php echo $JO_PER; ?>%"></div>
				                        </div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $Production; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($STF_3_AMN/1000000,2); ?></b>/<?php echo number_format($JO_3_AMN/1000000,2); ?></span>
				                        
				                        <div class="progress sm">
				                        	<div class="progress-bar progress-bar-warning" style="width: <?php echo $STF_PER; ?>%"></div>
				                    	</div>
				                    </div>
				                    <div class="progress-group">
				                        <span class="progress-text"><?php echo $Shipment; ?></span>
				                        <span class="progress-number"><b><?php echo number_format($SN_3_AMN/1000000,2); ?></b>/<?php echo number_format($SN_TOT/1000000,2); ?></span>
				                        
				                    	<div class="progress sm">
				                            <div class="progress-bar progress-bar-green" style="width: <?php echo $SN_PER; ?>%"></div>
				                        </div>
				                    </div>
									<div class="box-footer text-left" style="display: none;">
										<a href="javascript:void(0)" style="font-style: italic; font-size: 12px">
											Progres persetujuan dokumen.
										</a>
									</div>
					            </div>
				            </div>
				            <div class="row">
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textSO" class="description-percentage text-primary"><i id="tot_sopicn" class="fa fa-angle-double-up"></i>
											<span id="tot_sop"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="tot_sov"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-star"></i> TOTAL SALES ORDER</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textJO" class="description-percentage text-red"><i id="tot_jopicn" class="fa fa-angle-double-up"></i>
											<span id="tot_jop"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="tot_jov"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-fire"></i> PRODUCTION COST</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textLR" class="description-percentage text-green"><i id="tot_shipment" class="fa fa-angle-double-up"></i>
											<span id="gross_profp"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="gross_prof"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-truck"></i> PENGIRIMAN</span>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="description-block border-right">
										<span id="textLR" class="description-percentage text-green"><i id="gross_profpicn" class="fa fa-angle-double-up"></i>
											<span id="gross_profp"><?php echo number_format(0,2); ?></span>%
										</span>
										<h5 class="description-header">IDR <span id="gross_prof"><?php echo number_format(0,2); ?></span></h5>
										<span class="description-text"><i class="fa fa-dollar"></i> GROSS PROFIT</span>
									</div>
								</div>
							</div>
		        		</div>
				    </div>
				<?php } ?>
			<!-- END 	: SALES GRAPH -->
			<!-- START 	: STOCK - SALES / PENGGUNAAN (CONTR.) - WIP / MC PROYEK (CONTR.) - PRODUCTION / BANK RECEIPT (CONTR.) -->
				<?php
					if($PRJSCATEG == 2 && $SSWIP_INFO == 1)
					{
						$STOCK_AMN		= 0;
						$sqlSTOCK		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
												SUM(Base_Debet - Base_Kredit)) AS STOCK_AMN
											FROM tbl_chartaccount WHERE Acc_Group = 'STOCK' AND PRJCODE IN ($myPRJCODE)";
						$resSTOCK		= $this->db->query($sqlSTOCK)->result();
						foreach($resSTOCK as $rowSTOCK):
							$STOCK_AMN 	= $rowSTOCK->STOCK_AMN;
						endforeach;

						$SALES_AMN		= 0;
						/*$sqlSALES		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0, 
												SUM(Base_Debet - Base_Kredit)) AS SALES_AMN
											FROM tbl_chartaccount WHERE Acc_Group = 'SALES' AND PRJCODE IN ($myPRJCODE)";*/
						$sqlSALES		= "SELECT IF(ISNULL(SUM(SO_COST)), 0, SUM(SO_COST)) AS SALES_AMN
											FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
												AND YEAR(B.SO_DATE) = YEAR(CURDATE()) AND B.SO_STAT = 3";
						$resSALES		= $this->db->query($sqlSALES)->result();
						foreach($resSALES as $rowSALES):
							$SALES_AMN 	= $rowSALES->SALES_AMN;
						endforeach;

						$WIP_AMN		= 0;
						$sqlWIP			= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
												SUM(Base_Debet - Base_Kredit)) AS WIP_AMN
											FROM tbl_chartaccount WHERE Acc_Group = 'WIP' AND PRJCODE IN ($myPRJCODE)";
						$resWIP			= $this->db->query($sqlWIP)->result();
						foreach($resWIP as $rowWIP):
							$WIP_AMN 	= $rowWIP->WIP_AMN;
						endforeach;

						$PROD_AMN		= 0;
						$sqlPROD		= "SELECT 	IF(ISNULL(SUM(Base_Debet - Base_Kredit)), 0,
												SUM(Base_Debet - Base_Kredit)) AS PROD_AMN
											FROM tbl_chartaccount WHERE Acc_Group = 'PROD' AND PRJCODE IN ($myPRJCODE)";
						$resPROD		= $this->db->query($sqlPROD)->result();
						foreach($resPROD as $rowPROD):
							$PROD_AMN 	= $rowPROD->PROD_AMN;
						endforeach;
						?>
				      	<div class="row">
					        <section class="col-lg-12 connectedSortable">
					        	<div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-blue">
				                        <span class="info-box-icon"><i class="ion ion-cube"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $Supply; ?></span>
				                            <span class="info-box-number"><?php echo number_format($STOCK_AMN); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL6; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-aqua">
				                        <span class="info-box-icon"><i class="ion ion-social-usd-outline"></i></span>
				                        
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $Sales; ?></span>
				                            <span class="info-box-number"><?php echo number_format($SALES_AMN); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL7; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-yellow">
				                        <span class="info-box-icon"><i class="ion ion-aperture"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $WIP; ?></span>
				                            <span class="info-box-number"><?php echo number_format($WIP_AMN); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL8; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-green">
				                        <span class="info-box-icon"><i class="glyphicon glyphicon-barcode"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $Production; ?></span>
				                            <span class="info-box-number"><?php echo number_format($PROD_AMN); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL9; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				            </section>
				        </div>
            			<?php 
            		}
            		else if($PRJSCATEG == 1 && $SSWIP_INFO == 1)
            		{
						?>
				      	<div class="row">
					        <section class="col-lg-12 connectedSortable">
					        	<div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-blue">
				                        <span class="info-box-icon"><i class="ion ion-cube"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $Supply; ?></span>
				                            <span class="info-box-number"><?php echo number_format(0); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL6; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12">
				                    <div class="info-box bg-aqua">
				                        <span class="info-box-icon"><i class="glyphicon glyphicon-refresh"></i></span>
				                        
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $mtrUsed; ?></span>
				                            <span class="info-box-number"><?php echo number_format(0); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL7; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12" <?php if($MC_PRJ == 0) { ?> style="display: none;" <?php } ?>>
				                    <div class="info-box bg-yellow">
				                        <span class="info-box-icon"><i class="fa fa-certificate"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $mcprj; ?></span>
				                            <span class="info-box-number"><?php echo number_format(0); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL8; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				                <div class="col-md-6 col-sm-6 col-xs-12" <?php if($BR_INFO == 0) { ?> style="display: none;" <?php } ?>>
				                    <div class="info-box bg-green">
				                        <span class="info-box-icon"><i class="ion ion-social-usd-outline"></i></span>
				                        <div class="info-box-content">
				                            <span class="info-box-text"><?php echo $InvoiceRealization; ?></span>
				                            <span class="info-box-number"><?php echo number_format(0); ?></span>
				                            
				                            <div class="progress">
				                                <div class="progress-bar" style="width: 100%"></div>
				                            </div>
				                            <span class="progress-description" style="font-style: italic; font-size: 12px">
				                            	<?php echo $transL9; ?>
				                            </span>
				                        </div>
				                    </div>
				                </div>
				            </section>
				        </div>
            			<?php 
            		}
            	?>
			<!-- END 	: STOCK - SALES / PENGGUNAAN (CONTR.) - WIP / MC PROYEK (CONTR.) - PRODUCTION / BANK RECEIPT (CONTR.) -->

	      	<!-- START 	: GENERAL INFORMATION -->
      			<div class="row">
      					<div class="col-lg-4 col-xs-12">
							<div class="box box-primary">
				            	<div class="box-header with-border">
				              		<i class="fa fa-bar-chart-o"></i>

				              		<h3 class="box-title">Informasi Trafik</h3>

					              	<div class="box-tools pull-right">
					                	Real time
						                <div class="btn-group" id="realtime" data-toggle="btn-toggle">
						                  	<button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button>
						                  	<button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button>
						                </div>
					              	</div>
				            	</div>
				            	<div class="box-body">
				              		<div id="interactive" style="height: 250px;"></div>
				            	</div>
				          	</div>
						</div>
						<div class="col-lg-5 col-xs-12">
				          	<div class="box box-solid bg-teal-gradient">
					            <div class="box-header">
			                    	<i class="fa fa-th"></i>
			                        <h3 class="box-title"><?php echo $transL5; ?></h3>
			                    
			                        <div class="box-tools pull-right">
			                            <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
			                            </button>
			                            <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
			                            </button>
			                        </div>
			                    </div>
				                <div class="box-body border-radius-none">
				                    <div class="chart" id="line-chart2" style="height: 250px;"></div>
				                </div>
								<div class="box-footer no-border" style="display: none;">
				                    <div class="row">
				                       	<div class="col-md-4 text-center" style="border-right: 1px solid #f4f4f4">
				                            <input type="text" class="knob" data-readonly="true" value="70" data-width="60" data-height="60" data-fgColor="#00BEEE">
				                            <div class="knob-label">Aktif</div>
				                        </div>
				                        <div class="col-md-4 text-center" style="border-right: 1px solid #f4f4f4">
				                            <input type="text" class="knob" data-readonly="true" value="80" data-width="60" data-height="60" data-fgColor="#00A65B">
				                            <div class="knob-label">Online</div>
				                        </div>
				                        <div class="col-md-4 text-center">
				                            <input type="text" class="knob" data-readonly="true" value="90" data-width="60" data-height="60" data-fgColor="#F39F12">
				                            <div class="knob-label">Offline</div>
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <!-- START : LATEST LOGIN -->
							<div class="col-md-3 col-sm-6 col-xs-12">
				          		<div class="box box-solid bg-blue-gradient">
									<div class="box-header">
				                    	<i class="fa fa-history"></i>
				                        <h3 class="box-title"><?php echo $transL12; ?></h3>
				                    
				                        <div class="box-tools pull-right">
				                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
											</button>
											<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
											</button>
				                        </div>
				                    </div>

									<div class="box-footer no-border">
					                    <div class="row">
					                    	<div class="box-body">
												<ul class="products-list product-list-in-box">
													<?php
														$sqlLLog	= "SELECT DISTINCT A.LOG_EMP, DATE_FORMAT(A.LOG_IND,'%d %M') AS LOG_D,
																			DATE_FORMAT(A.LOG_IND,'%H:%i') AS LOG_T,
																			CONCAT(B.First_Name,' ', B.Last_Name) AS compName, B.Pos_Code
																		FROM tbl_login_hist A INNER JOIN tbl_employee B
																			ON A.LOG_EMP = B.Emp_ID
																		ORDER BY LOG_IND DESC LIMIT 4";
														$resLLog	= $this->db->query($sqlLLog)->result();
														foreach($resLLog as $rowLLog):
															$EMPID	= $rowLLog->LOG_EMP;
															$LOG_D	= $rowLLog->LOG_D;
															$LOG_T	= $rowLLog->LOG_T;
															$EMPNM	= $rowLLog->compName;
															$POSCD	= $rowLLog->Pos_Code;

															$filenamex 	= "username.jpg";
															$sqlIMG	= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img
																				WHERE imgemp_empid = '$Emp_ID'";
															$resIMG = $this->db->query($sqlIMG)->result();
															foreach($resIMG as $rowIMG) :
																$filenamex 	= $rowIMG->imgemp_filename;
																$filenamex 	= $rowIMG->imgemp_filenameX;
															endforeach;

															if($POSCD == '')
															{
																$POSNM 	= "-";
															}
															else
															{
																$POSNM  = "-";
																$sqlDEP = "SELECT POSS_NAME FROM tbl_position_str WHERE POSS_CODE = '$POSCD'";
																$resDEP = $this->db->query($sqlDEP)->result();
																foreach($resDEP as $rowDEP) :
																	$POSNM 	= $rowDEP->POSS_NAME;
																endforeach;
															}

															$empIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/'.$EMPID.'/'.$filenamex);
															if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$EMPID.'/'.$filenamex))
															{
																$empIMG	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
															}
															?>
																<li class="item">
																	<div class="product-img">
																		<img class="direct-chat-img" src="<?=$empIMG?>" alt="Message User Image">
																	</div>
																	<div class="product-info">
																		<a href="javascript:void(0)" class="product-title"><?php echo $EMPNM; ?>
																		<span class="label label-warning pull-right"><?php echo "$LOG_D $LOG_T"; ?></span></a>
																		<span class="product-description" style="font-size: 12px">
																			<?php echo "$EMPID<br>Bagian : $POSNM"; ?>
																		</span>
																	</div>
																</li>
															<?php
														endforeach;
													?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
				        <!-- END : LATEST LOGIN -->
				    </section>
            	</div>
            <!-- END 	: GENERAL INFORMATION -->
		</section>
	</body>
</html>
<?php
	$g_DtGraph	= base_url().'index.php/__l1y/gDtGraph/?id=';
?>
<script src="<?php echo base_url('assets/AdminLTE-2.0.5/bower_components/Flot/jquery.flot.js'); ?>"></script>
<!-- Page script -->
<script>
	$(document).ready(function()
	{
    	var SRC     = "";
        var SPLC    = "";
        var PROJECT = "<?=$PRJCODE?>";
        var REFNO   = document.getElementById('PO_NUM').value;
        var DSTAT   = document.getElementById('PR_STAT').value;
        var SPPCAT  = document.getElementById('SPPCAT').value;
        
        $('#example').DataTable(
    	{
            "destroy": true,
            "processing": true,
            "serverSide": true,
            //"scrollX": false,
			"dom": "lfrt",
            "autoWidth": true,
            "filter": true,
	        "ajax": "<?php echo site_url('c_purchase/c_pr180d0c/get_AllDataPRGRP/?id='.$PRJCODE)?>"+'&SPLC='+SPLC+'&REFNO='+REFNO+'&DSTAT='+DSTAT+'&SRC='+SRC+'&PROJECT='+PROJECT+'&SPPCAT='+SPPCAT,
	        "type": "POST",
			"lengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
			// "lengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]],
			"columnDefs": [	{ targets: [0,2], className: 'dt-body-center' },
							{ "width": "100px", "targets": [1] }
						  ],
        	"order": [[ 1, "desc" ]],
			"language": {
	            "infoFiltered":"",
	            "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
	        },
		});
	});
	
	/*collID 	= "TEST";

	url 	= "<?=$g_DtGraph?>";
  	$(function () {
	    
	     	// * Flot Interactive Chart
	     	// * -----------------------
	    
	    // We use an inline data source in the example, usually data would
	    // be fetched from a server
	    var data = [], totalPoints = 100

    	function getRandomData()
    	{
	      	if (data.length > 0)
	        	data = data.slice(1)

	      	// Do a random walk
	      	while (data.length < totalPoints)
	      	{
				$.ajax({
			        type: 'POST',
			        url: url,
			        data: {collID: collID},
			        success: function(response)
			        {
			        	var arrGrp  = response.split("~");
		            	grpVal 	= arrGrp[0];
		            	grpQry 	= arrGrp[1];
			        	y = parseInt(grpVal) 
						data.push(y)
			        }
		    	});

	        	// var prev = data.length > 0 ? data[data.length - 1] : 50,
	            // 	y    = prev + Math.random() * 10 - 5
	        	var prev = data.length > 0 ? data[data.length - 1] : 50,
	            	y    = prev + Math.random() * 10 - 5

	        	if (y < 0) 
	        	{
	          		y = 0
	        	} else if (y > 15) 
	        	{
	          		y = 15
	        	}

	        	data.push(y)
	      	}

			// Zip the generated y values with the x values
			var res = []
			for (var i = 0; i < data.length; ++i) {
				res.push([i, data[i]])
			}

			//console.log(res)
			return res
		}

    	var interactive_plot = $.plot('#interactive', [getRandomData()], {
	  		grid  : {
	    		borderColor: '#f3f3f3',
	    		borderWidth: 1,
	    		tickColor  : '#f3f3f3'
	  		},
	      	series: {
	        	shadowSize: 0, // Drawing is faster without shadows
	        	color     : '#3c8dbc'
	      	},
	      	lines : {
	        	fill : true, //Converts the line chart to area chart
	        	color: '#3c8dbc'
	      	},
	      	yaxis : {
	       	 	min : 0,
	        	max : 15,
	        	show: true
	      	},
	      	xaxis : {
	       		show: true
	      	}
	    })

	    var updateInterval = 2000 //Fetch data ever x milliseconds
	    var realtime       = 'on' //If == to on then fetch data every x seconds. else stop fetching
	    function update(){
	      	interactive_plot.setData([getRandomData()])

	      	// Since the axes don't change, we don't need to call plot.setupGrid()
	      	interactive_plot.draw()
	      	if (realtime === 'on')
	        	setTimeout(update, updateInterval)
	    }

	    //INITIALIZE REALTIME DATA FETCHING
	    if (realtime === 'on') {
	      	update()
	    }
	    //REALTIME TOGGLE
	    $('#realtime .btn').click(function () {
	      	if ($(this).data('toggle') === 'on') {
	        	realtime = 'on'
	      	}
	      	else {
	       	 	realtime = 'off'
	      	}
	      	update()
	    })
		 // * END INTERACTIVE CHART 
	})*/
</script>


<script type="text/javascript">
	$(document).ready(function(){
		$(".preloader").fadeOut();
		var hasSv = $('#hasSv').val();
		//ar isEnd = $('#isEnd').val();
		if(hasSv == 0)
		{
			swal({
				title: "<?php echo $transL01; ?>",
				text: "<?php echo $transL02; ?>",
				icon: "success",
				//buttons: true,
				buttons: ["<?php echo $transL06; ?>", "<?php echo $transL05; ?>"],
				dangerMode: true,
			})
			.then((willDelete) => 
			{
				if (willDelete) 
				{
					var EMPID	= document.getElementById('EMPID').value;
					$.ajax({
                        type: 'POST',
                        url: '<?php echo $logURL; ?>',
                        data: "EMPID="+EMPID,
                        success: function(msg)
                        {
                            //
                        }
                    });
					swal("<?php echo $transL03; ?>", 
					{
						icon: "warning",
					});
				} 
				else 
				{
					swal("<?php echo $transL04; ?>", 
					{
						icon: "warning",
					});
				}
				versNotif0();
				versNotif1();
			});
		}

		/*var isEnd = $('#isEnd').val();
		if(isEnd == 1)
		{
			mntAlert1();
		}
		else if(isEnd == 2)
		{
			mntAlert1();
		}*/
	});

	function versNotif0()
	{
		title 	= "Template / Skins";
		message = "<?php echo $transL07; ?>";

		window.createNotification
		({
			closeOnClick: true,
			displayCloseButton: true,
			positionClass: 'nfc-bottom-right',
			showDuration: 600000, // 1K = 1s
			theme: 'warning' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}

	function versNotif1()
	{
		title 	= "<?php echo $transL08; ?>";
		message = "<?php echo $transL09; ?>";

		window.createNotification
		({
			closeOnClick: true,
			displayCloseButton: true,
			positionClass: 'nfc-bottom-right',
			showDuration: 600000, // 1K = 1s
			theme: 'success' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}

	/*function mntAlert1()
	{
		title 	= "<?php echo $mntWarn1; ?>";
		message = "<?php echo $mntWarn2; ?>";

		window.createNotification
		({
			closeOnClick: false,
			displayCloseButton: false,
			positionClass: 'nfc-bottom-right',
			showDuration: 6000000, // 1K = 1s
			theme: 'error' // success, warning, info, error, None
		})
		({
			title: title,
			message: message
		});
	}*/

	function chooseProject(thisVal)
	{
		sssss	= thisVal.value;
		projCode = document.getElementById('selPRJCODE').value;
		document.frmsrch.submitPRJ.click();
	}

	$(function () {
	$(".select2").select2();
	});

    $(function() {
        "use strict";

		// START : AREA CHART
			<?php if($PRJSCATEG == 2 && $GRF_SALES == 999999) { ?>
				var area = new Morris.Area({
					element: 'revenue-chart1',
					resize: true,
					data: [
						<?php
							/*$sqlSOMNTH	= "SELECT DISTINCT MONTH (B.SO_DATE) AS M_SO, YEAR (B.SO_DATE) AS Y_SO
											FROM
												tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)";*/
							$sqlC 		= "tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)";
							$resC 		= $this->db->count_all($sqlC);
							if($resC > 0)
							{
								$sqlSOMNTH	= "SELECT DISTINCT YEAR (B.SO_DATE) AS Y_SO, QUARTER(B.SO_DATE) AS QUART
												FROM
													tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM AND B.SO_STAT IN (2,3)
												ORDER BY YEAR(B.SO_DATE) DESC LIMIT 3";
								$resSOMNTH	= $this->db->query($sqlSOMNTH)->result();
								foreach($resSOMNTH as $rowSOMNTH):
									$Y_SO 		= $rowSOMNTH->Y_SO;
									$QUART 		= $rowSOMNTH->QUART;


									$M_SO2		= 0;
									$sqlSOQTY2	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
													FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
														AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 2";
									$resSOQTY2	= $this->db->query($sqlSOQTY2)->result();
									foreach($resSOQTY2 as $rowSOQTY2):
										$M_SO2 	= $rowSOQTY2->SOQTY;
									endforeach;

									$M_SO3		= 0;
									$sqlSOQTY3	= "SELECT IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY
													FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
														AND QUARTER(B.SO_DATE) = $QUART AND YEAR(B.SO_DATE) = $Y_SO AND B.SO_STAT = 3";
									$resSOQTY3	= $this->db->query($sqlSOQTY3)->result();
									foreach($resSOQTY3 as $rowSOQTY3):
										$M_SO3 	= $rowSOQTY3->SOQTY;
									endforeach;

									$QRT 	= "Q".$QUART;
									$year1 	= "'$Y_SO $QRT'";
									?>
										{y: <?php echo $year1; ?>, so2qty: <?php echo $M_SO2; ?>, so3qty: <?php echo $M_SO3; ?>},
									<?php
								endforeach;
							}
							else
							{
								$Y_SO 	= date('Y');
								$QRT 	= "Q1";
								$year1 	= "'$Y_SO $QRT'";
								$M_SO2 	= 0;
								$M_SO3 	= 0;
								?>
									{y: <?php echo $year1; ?>, so2qty: <?php echo $M_SO2; ?>, so3qty: <?php echo $M_SO3; ?>},
								<?php
							}
						?>
						/*{y: '2011 Q1', so2qty: 3750, so3qty: 3750},
						{y: '2011 Q2', so2qty: 2778, so3qty: 2294},
						{y: '2011 Q3', so2qty: 4912, so3qty: 1969},
						{y: '2011 Q4', so2qty: 3767, so3qty: 3597},
						{y: '2012 Q1', so2qty: 6810, so3qty: 1914},
						{y: '2012 Q2', so2qty: 5670, so3qty: 4293},
						{y: '2012 Q3', so2qty: 4820, so3qty: 3795},
						{y: '2012 Q4', so2qty: 15073, so3qty: 5967},
						{y: '2013 Q1', so2qty: 10687, so3qty: 4460},
						{y: '2013 Q2', so2qty: 8432, so3qty: 5713},*/
					],
					xkey: 'y',
					ykeys: ['so2qty', 'so3qty'],
					labels: ['Confirmed', 'Approved'],
					lineColors: ['#a0d0e0', '#3c8dbc'],
					hideHover: 'auto'
				});
			<?php } ?>

			<?php if($PRJSCATEG == 1) { ?>
				Highcharts.chart('chart-proj_progress', {
					chart: {
						type: 'line'
					},
					title: {
						text: ''
					},
					subtitle: {
						//text: 'Source: WorldClimate.com'
					},
					xAxis: {
						title: {
							text: 'Minggu ke-'
						},
						categories: [
						<?php
							$sqlProgC	= "tbl_projprogres WHERE proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText AND isShow = 1";
							$resProgC	= $this->db->count_all($sqlProgC);
							if($resProgC <= 20)
							{
								$pmbg	= 1;
							}
							else
							{
								$pmbg	= $resProgC / 20;
							}
							$ADDR	= 0;
							for($PC = 1; $PC <= $resProgC; $PC++)
							{
								$ADDRX	= $ADDR + $PC;
								echo "'$ADDRX',";
							}
						?>
						]
					},
					yAxis: {
						title: {
							text: 'Progress/Prosentasi (%)'
						}
					},
					plotOptions: {
						line: {
							dataLabels: {
								enabled: false
							},
							enableMouseTracking: true
						}
					},
					series: [{
						name: 'Plan',
						color: "#00F",
						data: [<?php
									$jumTotPlanAkuma 	= '';
									$GraphicTitleText	= 3;
									$sqla   	= "SELECT A.proj_Code, B.PRJNAME
													FROM tbl_projprogres A
													INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
													WHERE A.proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText
													GROUP BY proj_Code";
									$ressqla 	= $this->db->query($sqla)->result();
									
									foreach($ressqla as $rowa) :
										$proj_Code 	= $rowa->proj_Code;
										$projName 	= $rowa->PRJNAME;
										$proj_Name	= "Project : $proj_Code";
										
										$NoU 		= 0;
										$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
															MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
															MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
															isShowRA, isShowDev, Prg_Step
														FROM tbl_projprogres WHERE proj_Code IN ($myPRJCODEX)
															AND progress_Type = $GraphicTitleText AND isShow = 1 
														GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
										$ressql0 	= $this->db->query($sql0)->result();
										foreach($ressql0 as $row0) :
											$NoU 			= $NoU + 1;
											$DayAx 			= $row0->myDay;
											$myDate			= $row0->myDate;
											$Prg_PlanAkum	= $row0->Prg_PlanAkum;
											$Prg_RealAkum	= $row0->Prg_RealAkum;
											$Prg_Dev2 		= $Prg_PlanAkum - $Prg_RealAkum;
											$isShow			= $row0->isShow;
											$isShowRA		= $row0->isShowRA;
											$isShowDev		= $row0->isShowDev;
											if($isShow == 1)
											{
												echo "$Prg_PlanAkum,";
											}
										endforeach;
									endforeach;
								?>]
					}, {
						name: 'Real',
						color: "#390",
						data: [<?php
									$jumTotPlanAkuma 	= '';
									$GraphicTitleText	= 3;
									$sqla   	= "SELECT A.proj_Code, B.PRJNAME
													FROM tbl_projprogres A
													INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
													WHERE A.proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText
													GROUP BY proj_Code";
									$ressqla 	= $this->db->query($sqla)->result();
									
									foreach($ressqla as $rowa) :
										$proj_Code 	= $rowa->proj_Code;
										$projName 	= $rowa->PRJNAME;
										$proj_Name	= "Project : $proj_Code";
										
										$NoU 		= 0;
										$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
															MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
															MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
															isShowRA, isShowDev
														FROM tbl_projprogres WHERE proj_Code IN ($myPRJCODEX)
															AND progress_Type = $GraphicTitleText AND isShow = 1 
														GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
										$ressql0 	= $this->db->query($sql0)->result();
										foreach($ressql0 as $row0) :
											$NoU 			= $NoU + 1;
											$DayAx 			= $row0->myDay;
											$myDate			= $row0->myDate;
											$Prg_PlanAkum	= $row0->Prg_PlanAkum;
											$Prg_RealAkum	= $row0->Prg_RealAkum;
											$Prg_Dev2 		= $Prg_PlanAkum - $Prg_RealAkum;
											$isShow			= $row0->isShow;
											$isShowRA		= $row0->isShowRA;
											$isShowDev		= $row0->isShowDev;
											if($isShowRA == 1)
											{
												echo "$Prg_RealAkum,";
											}
										endforeach;
									endforeach;
								?>]
					}]
				});
			<?php } ?>

			<?php //if($GRF_LOGHIST == 1) { ?>
				var data = [];
				/*
				 * LINE CHART
				 * ----------
				 */
				//LINE randomly generated data
				var arr1 	= [];
				var arr1a	= [];
				var arr1b	= [];
				<?php
					$sqlTL   			= "SELECT DAY(TTR_DATE) AS THEDAY, MONTH (TTR_DATE) AS THEMONTH,
												COUNT(DATE(TTR_DATE)) AS TOTLOG
											FROM tbl_trail_tracker
											WHERE
												TTR_DATE >= '$WEEK_BEF' AND TTR_DATE <= '$WEEK_NEXT'
											GROUP BY
												DATE(TTR_DATE)
											ORDER BY
												YEAR(TTR_DATE), MONTH(TTR_DATE), DATE(TTR_DATE) ASC";
					$resTL 				= $this->db->query($sqlTL)->result();
					$therow1			= 0;
					foreach($resTL as $rowTL) :
						$therow1	= $therow1 + 1;
						$THEDAY 	= $rowTL->THEDAY;
						$len 		= strlen($THEDAY);
						if($len==1) 
							$nol	= "0";
						else
							$nol	= "";
						$THEDAY1 	= $nol.$THEDAY;
						//echo "THEDAY1 = $THEDAY1 === ";
							
						$THEMONTH 	= $rowTL->THEMONTH;
						$len1 		= strlen($THEMONTH);
						if($len1==1) 
							$nol1	= "0";
						else
							$nol1	= "";
						$THEMONTH1 	= $nol1.$THEMONTH;
						
						//echo "THEMONTH1 = $THEMONTH1<BR>";
						$THEMD		= "$THEMONTH1$THEDAY1";
						$therow2	= "$therow1.$THEMD";
						$therow		= $therow2;
						$TOTLOG 	= $rowTL->TOTLOG;
						
						?>
							arr1a.push([<?php echo $therow; ?>]);
							arr1b.push([<?php echo $THEDAY; ?>]);
							arr1.push([<?php echo $therow; ?>, <?php echo $TOTLOG; ?>]);
						<?php
					endforeach;
				?>
				var line_data1 = {
					label: "Log Act",	
					data: arr1,					
					color: "#3c8dbc"
				};
				/*var line_data2 = {
					label: "Real",	
					data: arr2,
					color: "#00c0ef"
				};
				var line_data3 = {
					label: "Deviation",	
					data: arr3,
					color: "#f56954"
				};*/
				
				$.plot("#line-chart2", [line_data1], {
					grid: {
						hoverable: true,
						borderColor: "#f3f3f3",
						borderWidth: 1,
						tickColor: "#f3f3f3"
					},
					series: {
						shadowSize: 0,
						lines: {
							show: true
						},
						points: {
							show: true
						}
					},
					lines: {
						fill: true,
						color: ["#3c8dbc", "#f56954"]
					},
					yaxis: {
						show: true,
					},
					xaxis: {
						show: true
					}
				});
				//Initialize tooltip on hover
				$("<div class='tooltip-inner' id='line-chart-tooltip'></div>").css({
					position: "absolute",
					display: "none",
					//opacity: 0.8
				}).appendTo("body");
				$("#line-chart2").bind("plothover", function(event, pos, item) {
		
					if (item) {
						var x = item.datapoint[0].toFixed(4),
							y = item.datapoint[1].toFixed(2);
		
						$("#line-chart-tooltip").html(item.series.label + " of " + x + " = " + y)
								.css({top: item.pageY + 10, left: item.pageX + 10})
								.fadeIn(200);
					} else {
						$("#line-chart-tooltip").hide();
					}
		
				});
			<?php //} ?>


			<?php if($GRF_PURCH == 1 || $GRF_SALES == 1) {
				if($PRJSCATEG == 1)
					$ADDQRY 	= "PRJCODE IN ($myPRJCODE) AND";
				else
					$ADDQRY 	= "";

			    $nMonth = date('m');
			    $tBef 	= date('Y-m-t', strtotime('-1 month', strtotime($tNow)));
			    $tBef2 	= date('m', strtotime($tBef));
			    $varMB	= "T".$tBef2."_SO";
			    $varM	= "T".$nMonth."_SO";
				$tYear	= date('Y');
				$sql_01 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '01'";
			    $res_01 = $this->db->query($sql_01)->result();
			    foreach($res_01 as $row_01) :
			        $T01_PR  = $row_01->TOT_PR / 1000000;
			        $T01_PO  = $row_01->TOT_PO / 1000000;
			        $T01_IR  = $row_01->TOT_IR / 1000000;
			        $T01_UM  = $row_01->TOT_UM / 1000000;
			        $T01_SO  = $row_01->TOT_SO / 1000000;
			        $T01_JO  = $row_01->TOT_JO / 1000000;
			        $T01_PRD = $row_01->TOT_PRD / 1000000;
			        $T01_WO  = $row_01->TOT_WO / 1000000;
			        $T01_OPN = $row_01->TOT_OPN / 1000000;
			    endforeach;
			    if($T01_PR == '') $T01_PR = 0;
			    if($T01_PO == '') $T01_PO = 0;
			    if($T01_IR == '') $T01_IR = 0;
			    if($T01_UM == '') $T01_UM = 0;
			    if($T01_SO == '') $T01_SO = 0;
			    if($T01_JO == '') $T01_JO = 0;
			    if($T01_PRD == '') $T01_PRD = 0;
			    if($T01_WO == '') $T01_WO = 0;
			    if($T01_OPN == '') $T01_OPN  = 0;

				$sql_02 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '02'";
			    $res_02 = $this->db->query($sql_02)->result();
			    foreach($res_02 as $row_02) :
			        $T02_PR  = $row_02->TOT_PR / 1000000;
			        $T02_PO  = $row_02->TOT_PO / 1000000;
			        $T02_IR  = $row_02->TOT_IR / 1000000;
			        $T02_UM  = $row_02->TOT_UM / 1000000;
			        $T02_SO  = $row_02->TOT_SO / 1000000;
			        $T02_JO  = $row_02->TOT_JO / 1000000;
			        $T02_PRD = $row_02->TOT_PRD / 1000000;
			        $T02_WO  = $row_02->TOT_WO / 1000000;
			        $T02_OPN = $row_02->TOT_OPN / 1000000;
			    endforeach;
			    if($T02_PR == '') $T02_PR = 0;
			    if($T02_PO == '') $T02_PO = 0;
			    if($T02_IR == '') $T02_IR = 0;
			    if($T02_UM == '') $T02_UM = 0;
			    if($T02_SO == '') $T02_SO = 0;
			    if($T02_JO == '') $T02_JO = 0;
			    if($T02_PRD == '') $T02_PRD = 0;
			    if($T02_WO == '') $T02_WO = 0;
			    if($T02_OPN == '') $T02_OPN  = 0;

				$sql_03 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '03'";
			    $res_03 = $this->db->query($sql_03)->result();
			    foreach($res_03 as $row_03) :
			        $T03_PR  = $row_03->TOT_PR / 1000000;
			        $T03_PO  = $row_03->TOT_PO / 1000000;
			        $T03_IR  = $row_03->TOT_IR / 1000000;
			        $T03_UM  = $row_03->TOT_UM / 1000000;
			        $T03_SO  = $row_03->TOT_SO / 1000000;
			        $T03_JO  = $row_03->TOT_JO / 1000000;
			        $T03_PRD = $row_03->TOT_PRD / 1000000;
			        $T03_WO  = $row_03->TOT_WO / 1000000;
			        $T03_OPN = $row_03->TOT_OPN / 1000000;
			    endforeach;
			    if($T03_PR == '') $T03_PR = 0;
			    if($T03_PO == '') $T03_PO = 0;
			    if($T03_IR == '') $T03_IR = 0;
			    if($T03_UM == '') $T03_UM = 0;
			    if($T03_SO == '') $T03_SO = 0;
			    if($T03_JO == '') $T03_JO = 0;
			    if($T03_PRD == '') $T03_PRD = 0;
			    if($T03_WO == '') $T03_WO = 0;
			    if($T03_OPN == '') $T03_OPN  = 0;

				$sql_04 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '04'";
			    $res_04 = $this->db->query($sql_04)->result();
			    foreach($res_04 as $row_04) :
			        $T04_PR  = $row_04->TOT_PR / 1000000;
			        $T04_PO  = $row_04->TOT_PO / 1000000;
			        $T04_IR  = $row_04->TOT_IR / 1000000;
			        $T04_UM  = $row_04->TOT_UM / 1000000;
			        $T04_SO  = $row_04->TOT_SO / 1000000;
			        $T04_JO  = $row_04->TOT_JO / 1000000;
			        $T04_PRD = $row_04->TOT_PRD / 1000000;
			        $T04_WO  = $row_04->TOT_WO / 1000000;
			        $T04_OPN = $row_04->TOT_OPN / 1000000;
			    endforeach;
			    if($T04_PR == '') $T04_PR = 0;
			    if($T04_PO == '') $T04_PO = 0;
			    if($T04_IR == '') $T04_IR = 0;
			    if($T04_UM == '') $T04_UM = 0;
			    if($T04_SO == '') $T04_SO = 0;
			    if($T04_JO == '') $T04_JO = 0;
			    if($T04_PRD == '') $T04_PRD = 0;
			    if($T04_WO == '') $T04_WO = 0;
			    if($T04_OPN == '') $T04_OPN  = 0;

				$sql_05 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '05'";
			    $res_05 = $this->db->query($sql_05)->result();
			    foreach($res_05 as $row_05) :
			        $T05_PR  = $row_05->TOT_PR / 1000000;
			        $T05_PO  = $row_05->TOT_PO / 1000000;
			        $T05_IR  = $row_05->TOT_IR / 1000000;
			        $T05_UM  = $row_05->TOT_UM / 1000000;
			        $T05_SO  = $row_05->TOT_SO / 1000000;
			        $T05_JO  = $row_05->TOT_JO / 1000000;
			        $T05_PRD = $row_05->TOT_PRD / 1000000;
			        $T05_WO  = $row_05->TOT_WO / 1000000;
			        $T05_OPN = $row_05->TOT_OPN / 1000000;
			    endforeach;
			    if($T05_PR == '') $T05_PR = 0;
			    if($T05_PO == '') $T05_PO = 0;
			    if($T05_IR == '') $T05_IR = 0;
			    if($T05_UM == '') $T05_UM = 0;
			    if($T05_SO == '') $T05_SO = 0;
			    if($T05_JO == '') $T05_JO = 0;
			    if($T05_PRD == '') $T05_PRD = 0;
			    if($T05_WO == '') $T05_WO = 0;
			    if($T05_OPN == '') $T05_OPN  = 0;

				$sql_06 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '06'";
			    $res_06 = $this->db->query($sql_06)->result();
			    foreach($res_06 as $row_06) :
			        $T06_PR  = $row_06->TOT_PR / 1000000;
			        $T06_PO  = $row_06->TOT_PO / 1000000;
			        $T06_IR  = $row_06->TOT_IR / 1000000;
			        $T06_UM  = $row_06->TOT_UM / 1000000;
			        $T06_SO  = $row_06->TOT_SO / 1000000;
			        $T06_JO  = $row_06->TOT_JO / 1000000;
			        $T06_PRD = $row_06->TOT_PRD / 1000000;
			        $T06_WO  = $row_06->TOT_WO / 1000000;
			        $T06_OPN = $row_06->TOT_OPN / 1000000;
			    endforeach;
			    if($T06_PR == '') $T06_PR = 0;
			    if($T06_PO == '') $T06_PO = 0;
			    if($T06_IR == '') $T06_IR = 0;
			    if($T06_UM == '') $T06_UM = 0;
			    if($T06_SO == '') $T06_SO = 0;
			    if($T06_JO == '') $T06_JO = 0;
			    if($T06_PRD == '') $T06_PRD = 0;
			    if($T06_WO == '') $T06_WO = 0;
			    if($T06_OPN == '') $T06_OPN  = 0;

				$sql_07 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '07'";
			    $res_07 = $this->db->query($sql_07)->result();
			    foreach($res_07 as $row_07) :
			        $T07_PR  = $row_07->TOT_PR / 1000000;
			        $T07_PO  = $row_07->TOT_PO / 1000000;
			        $T07_IR  = $row_07->TOT_IR / 1000000;
			        $T07_UM  = $row_07->TOT_UM / 1000000;
			        $T07_SO  = $row_07->TOT_SO / 1000000;
			        $T07_JO  = $row_07->TOT_JO / 1000000;
			        $T07_PRD = $row_07->TOT_PRD / 1000000;
			        $T07_WO  = $row_07->TOT_WO / 1000000;
			        $T07_OPN = $row_07->TOT_OPN / 1000000;
			    endforeach;
			    if($T07_PR == '') $T07_PR = 0;
			    if($T07_PO == '') $T07_PO = 0;
			    if($T07_IR == '') $T07_IR = 0;
			    if($T07_UM == '') $T07_UM = 0;
			    if($T07_SO == '') $T07_SO = 0;
			    if($T07_JO == '') $T07_JO = 0;
			    if($T07_PRD == '') $T07_PRD = 0;
			    if($T07_WO == '') $T07_WO = 0;
			    if($T07_OPN == '') $T07_OPN  = 0;

				$sql_08 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '08'";
			    $res_08 = $this->db->query($sql_08)->result();
			    foreach($res_08 as $row_08) :
			        $T08_PR  = $row_08->TOT_PR / 1000000;
			        $T08_PO  = $row_08->TOT_PO / 1000000;
			        $T08_IR  = $row_08->TOT_IR / 1000000;
			        $T08_UM  = $row_08->TOT_UM / 1000000;
			        $T08_SO  = $row_08->TOT_SO / 1000000;
			        $T08_JO  = $row_08->TOT_JO / 1000000;
			        $T08_PRD = $row_08->TOT_PRD / 1000000;
			        $T08_WO  = $row_08->TOT_WO / 1000000;
			        $T08_OPN = $row_08->TOT_OPN / 1000000;
			    endforeach;
			    if($T08_PR == '') $T08_PR = 0;
			    if($T08_PO == '') $T08_PO = 0;
			    if($T08_IR == '') $T08_IR = 0;
			    if($T08_UM == '') $T08_UM = 0;
			    if($T08_SO == '') $T08_SO = 0;
			    if($T08_JO == '') $T08_JO = 0;
			    if($T08_PRD == '') $T08_PRD = 0;
			    if($T08_WO == '') $T08_WO = 0;
			    if($T08_OPN == '') $T08_OPN  = 0;

				$sql_09 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '09'";
			    $res_09 = $this->db->query($sql_09)->result();
			    foreach($res_09 as $row_09) :
			        $T09_PR  = $row_09->TOT_PR / 1000000;
			        $T09_PO  = $row_09->TOT_PO / 1000000;
			        $T09_IR  = $row_09->TOT_IR / 1000000;
			        $T09_UM  = $row_09->TOT_UM / 1000000;
			        $T09_SO  = $row_09->TOT_SO / 1000000;
			        $T09_JO  = $row_09->TOT_JO / 1000000;
			        $T09_PRD = $row_09->TOT_PRD / 1000000;
			        $T09_WO  = $row_09->TOT_WO / 1000000;
			        $T09_OPN = $row_09->TOT_OPN / 1000000;
			    endforeach;
			    if($T09_PR == '') $T09_PR = 0;
			    if($T09_PO == '') $T09_PO = 0;
			    if($T09_IR == '') $T09_IR = 0;
			    if($T09_UM == '') $T09_UM = 0;
			    if($T09_SO == '') $T09_SO = 0;
			    if($T09_JO == '') $T09_JO = 0;
			    if($T09_PRD == '') $T09_PRD = 0;
			    if($T09_WO == '') $T09_WO = 0;
			    if($T09_OPN == '') $T09_OPN  = 0;

				$sql_10 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '10'";
			    $res_10 = $this->db->query($sql_10)->result();
			    foreach($res_10 as $row_10) :
			        $T10_PR  = $row_10->TOT_PR / 1000000;
			        $T10_PO  = $row_10->TOT_PO / 1000000;
			        $T10_IR  = $row_10->TOT_IR / 1000000;
			        $T10_UM  = $row_10->TOT_UM / 1000000;
			        $T10_SO  = $row_10->TOT_SO / 1000000;
			        $T10_JO  = $row_10->TOT_JO / 1000000;
			        $T10_PRD = $row_10->TOT_PRD / 1000000;
			        $T10_WO  = $row_10->TOT_WO / 1000000;
			        $T10_OPN = $row_10->TOT_OPN / 1000000;
			    endforeach;
			    if($T10_PR == '') $T10_PR = 0;
			    if($T10_PO == '') $T10_PO = 0;
			    if($T10_IR == '') $T10_IR = 0;
			    if($T10_UM == '') $T10_UM = 0;
			    if($T10_SO == '') $T10_SO = 0;
			    if($T10_JO == '') $T10_JO = 0;
			    if($T10_PRD == '') $T10_PRD = 0;
			    if($T10_WO == '') $T10_WO = 0;
			    if($T10_OPN == '') $T10_OPN  = 0;

				$sql_11 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '11'";
			    $res_11 = $this->db->query($sql_11)->result();
			    foreach($res_11 as $row_11) :
			        $T11_PR  = $row_11->TOT_PR / 1000000;
			        $T11_PO  = $row_11->TOT_PO / 1000000;
			        $T11_IR  = $row_11->TOT_IR / 1000000;
			        $T11_UM  = $row_11->TOT_UM / 1000000;
			        $T11_SO  = $row_11->TOT_SO / 1000000;
			        $T11_JO  = $row_11->TOT_JO / 1000000;
			        $T11_PRD = $row_11->TOT_PRD / 1000000;
			        $T11_WO  = $row_11->TOT_WO / 1000000;
			        $T11_OPN = $row_11->TOT_OPN / 1000000;
			    endforeach;
			    if($T11_PR == '') $T11_PR = 0;
			    if($T11_PO == '') $T11_PO = 0;
			    if($T11_IR == '') $T11_IR = 0;
			    if($T11_UM == '') $T11_UM = 0;
			    if($T11_SO == '') $T11_SO = 0;
			    if($T11_JO == '') $T11_JO = 0;
			    if($T11_PRD == '') $T11_PRD = 0;
			    if($T11_WO == '') $T11_WO = 0;
			    if($T11_OPN == '') $T11_OPN  = 0;

				$sql_12 = "SELECT SUM(PR_VAL-PR_CVAL) AS TOT_PR, SUM(PO_VAL-PO_CVAL) AS TOT_PO, SUM(IR_VAL) AS TOT_IR, SUM(UM_VAL) AS TOT_UM,
								SUM(WO_VAL-WO_CVAL) AS TOT_WO, SUM(OPN_VAL) AS TOT_OPN, SUM(0) AS TOT_SO, SUM(0) AS TOT_JO, SUM(0) AS TOT_PRD
							FROM tbl_item_logbook_$PRJCODEVW WHERE YEAR(DOC_DATE) = '$tYear' AND MONTH(DOC_DATE) = '12'";
			    $res_12 = $this->db->query($sql_12)->result();
			    foreach($res_12 as $row_12) :
			        $T12_PR  = $row_12->TOT_PR / 1000000;
			        $T12_PO  = $row_12->TOT_PO / 1000000;
			        $T12_IR  = $row_12->TOT_IR / 1000000;
			        $T12_UM  = $row_12->TOT_UM / 1000000;
			        $T12_SO  = $row_12->TOT_SO / 1000000;
			        $T12_JO  = $row_12->TOT_JO / 1000000;
			        $T12_PRD = $row_12->TOT_PRD / 1000000;
			        $T12_WO  = $row_12->TOT_WO / 1000000;
			        $T12_OPN = $row_12->TOT_OPN / 1000000;
			    endforeach;
			    if($T12_PR == '') $T12_PR = 0;
			    if($T12_PO == '') $T12_PO = 0;
			    if($T12_IR == '') $T12_IR = 0;
			    if($T12_UM == '') $T12_UM = 0;
			    if($T12_SO == '') $T12_SO = 0;
			    if($T12_JO == '') $T12_JO = 0;
			    if($T12_PRD == '') $T12_PRD = 0;
			    if($T12_WO == '') $T12_WO = 0;
			    if($T12_OPN == '') $T12_OPN  = 0;
			}
			
			if($GRF_SALES == 1) 
			{
				$nSOValB 	= 0;
				$nJOValB 	= 0;
				if($varMB == 'T01_SO') { $nSOValB	= $T01_SO; $nJOValB	= $T01_JO; }
				elseif($varMB == 'T02_SO') { $nSOValB	= $T02_SO; $nJOValB	= $T02_JO; }
				elseif($varMB == 'T03_SO') { $nSOValB	= $T03_SO; $nJOValB	= $T03_JO; }
				elseif($varMB == 'T04_SO') { $nSOValB	= $T04_SO; $nJOValB	= $T04_JO; }
				elseif($varMB == 'T05_SO') { $nSOValB	= $T05_SO; $nJOValB	= $T05_JO; }
				elseif($varMB == 'T06_SO') { $nSOValB	= $T06_SO; $nJOValB	= $T06_JO; }
				elseif($varMB == 'T07_SO') { $nSOValB	= $T07_SO; $nJOValB	= $T07_JO; }
				elseif($varMB == 'T08_SO') { $nSOValB	= $T08_SO; $nJOValB	= $T08_JO; }
				elseif($varMB == 'T09_SO') { $nSOValB	= $T09_SO; $nJOValB	= $T09_JO; }
				elseif($varMB == 'T10_SO') { $nSOValB	= $T10_SO; $nJOValB	= $T10_JO; }
				elseif($varMB == 'T11_SO') { $nSOValB	= $T11_SO; $nJOValB	= $T11_JO; }
				elseif($varMB == 'T12_SO') { $nSOValB	= $T12_SO; $nJOValB	= $T12_JO; }

				$nSOVal 	= 0;
				$nJOVal 	= 0;
				if($varM == 'T01_SO') { $nSOVal	= $T01_SO; $nJOVal	= $T01_JO; }
				elseif($varM == 'T02_SO') { $nSOVal	= $T02_SO; $nJOVal	= $T02_JO; }
				elseif($varM == 'T03_SO') { $nSOVal	= $T03_SO; $nJOVal	= $T03_JO; }
				elseif($varM == 'T04_SO') { $nSOVal	= $T04_SO; $nJOVal	= $T04_JO; }
				elseif($varM == 'T05_SO') { $nSOVal	= $T05_SO; $nJOVal	= $T05_JO; }
				elseif($varM == 'T06_SO') { $nSOVal	= $T06_SO; $nJOVal	= $T06_JO; }
				elseif($varM == 'T07_SO') { $nSOVal	= $T07_SO; $nJOVal	= $T07_JO; }
				elseif($varM == 'T08_SO') { $nSOVal	= $T08_SO; $nJOVal	= $T08_JO; }
				elseif($varM == 'T09_SO') { $nSOVal	= $T09_SO; $nJOVal	= $T09_JO; }
				elseif($varM == 'T10_SO') { $nSOVal	= $T10_SO; $nJOVal	= $T10_JO; }
				elseif($varM == 'T11_SO') { $nSOVal	= $T11_SO; $nJOVal	= $T11_JO; }
				elseif($varM == 'T12_SO') { $nSOVal	= $T12_SO; $nJOVal	= $T12_JO; }

				$nSOBal		= $nSOVal - $nSOValB;			// Selisih SO thd bulan sebelumnya
				$nSOValBP 	= $nSOValB;
					if($nSOValBP == 0) $nSOValBP = 1;
				$nSOPerc	= $nSOBal / $nSOValBP * 100;	// SO Percentation

				$nJOBal		= $nJOVal - $nJOValB;			// Selisih JO thd bulan sebelumnya
				$nJOValBP 	= $nJOValB;
					if($nJOValBP == 0) $nJOValBP = 1;
				$nJOPerc	= $nJOBal / $nJOValBP * 100;	// JO Percentation

				$nGPRFValB	= ($nSOValB * 1000000) - ($nJOValB * 1000000);	// Gross Grofit Before
				$nGPRFValC	= ($nSOVal * 1000000) - ($nJOVal * 1000000);	// Gross Grofit Current

				$nGPRFBal	= $nGPRFValC - $nGPRFValB;			// Selisih GPRF thd bulan sebelumnya
				$nGPRFValBP = $nGPRFValB;
					if($nGPRFValBP == 0) $nGPRFValBP = 1;
				$nGPRFPerc	= $nGPRFBal / $nGPRFValBP * 100;	// GPRF Percentation

				$nSOPercV	= number_format($nSOPerc, 2);
				$nJOPercV	= number_format($nJOPerc, 2);
				$nGPRFPercV	= number_format($nGPRFPerc, 2);

				$nSOValV	= number_format($nSOVal * 1000000, 2);
				$nJOValV	= number_format($nJOVal * 1000000, 2);
				$nGPRFValV	= number_format($nGPRFValC, 2);
			}
	    	?>
			<?php if($GRF_SALES == 1)  { ?>
				var nSOPerc 	= "<?php echo $nSOPercV; ?>";
				var nJOPerc 	= "<?php echo $nJOPerc; ?>";
				var nGPRFPerc 	= "<?php echo $nGPRFPerc; ?>";

	    		document.getElementById('tot_sop').innerHTML 		= "<?php echo $nSOPercV; ?>";
				if(nSOPerc >= 0)
				{
					document.getElementById('textSO').className		= "description-percentage text-green";
					document.getElementById('tot_sopicn').className	= "fa fa-angle-double-up";
				}
				else
				{
					document.getElementById('textSO').className			= "description-percentage text-red";
					document.getElementById('tot_sopicn').className		= "fa fa-angle-double-down";
				}
	    		

	    		document.getElementById('tot_jop').innerHTML 			= "<?php echo $nJOPercV; ?>";
				if(nJOPerc >= 0)
				{
					document.getElementById('textJO').className			= "description-percentage text-green";
					document.getElementById('tot_jopicn').className		= "fa fa-angle-double-up";
				}
				else
				{
					document.getElementById('textJO').className			= "description-percentage text-red";
					document.getElementById('tot_jopicn').className		= "fa fa-angle-double-down";
				}

	    		document.getElementById('gross_profp').innerHTML 		= "<?php echo $nGPRFPercV; ?>";
				if(nGPRFPerc >= 0)
				{
					document.getElementById('textLR').className			= "description-percentage text-green";
					document.getElementById('gross_profpicn').className	= "fa fa-angle-double-up";
				}
				else
				{
					document.getElementById('textLR').className			= "description-percentage text-red";
					document.getElementById('gross_profpicn').className	= "fa fa-angle-double-down";
				}

	    		document.getElementById('gross_profp').title 		= "<?php echo number_format($nGPRFValC, 2) ."-". number_format($nGPRFValB, 2); ?>";

	    		document.getElementById('tot_sov').innerHTML 		= "<?php echo $nSOValV; ?>";
	    		document.getElementById('tot_jov').innerHTML 		= "<?php echo $nJOValV; ?>";
	    		document.getElementById('gross_prof').innerHTML 	= "<?php echo $nGPRFValV; ?>";
	    	<?php } ?>

			<?php if($GRF_PURCH == 1) { ?>
				window.chartColors = {
					red: 'rgb(255, 99, 132)',
					orange: 'rgb(255, 159, 64)',
					yellow: 'rgb(255, 205, 86)',
					green: 'rgb(75, 192, 192)',
					blue: 'rgb(54, 162, 235)',
					purple: 'rgb(153, 102, 255)',
					grey: 'rgb(201, 203, 207)'
				};

				(function(global) {
					var MONTHS = [
						'Jan',
						'Feb',
						'Mar',
						'Apr',
						'Mei',
						'Jun',
						'Jul',
						'Agus',
						'Sept',
						'Okt',
						'Nop',
						'Des'
					];

					var COLORS = [
						'#4dc9f6',
						'#f67019',
						'#f53794',
						'#537bc4',
						'#acc236',
						'#166a8f',
						'#00a950',
						'#58595b',
						'#8549ba'
					];

					var Samples = global.Samples || (global.Samples = {});
					var Color = global.Color;

					Samples.utils = {
						// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
						srand: function(seed) {
							this._seed = seed;
						},

						rand: function(min, max) {
							var seed = this._seed;
							min = min === undefined ? 0 : min;
							max = max === undefined ? 1 : max;
							this._seed = (seed * 9301 + 49297) % 233280;
							return min + (this._seed / 233280) * (max - min);
						},

						numbers: function(config) {
							var cfg = config || {};
							var min = cfg.min || 0;
							var max = cfg.max || 1;
							var from = cfg.from || [];
							var count = cfg.count || 8;
							var decimals = cfg.decimals || 8;
							var continuity = cfg.continuity || 1;
							var dfactor = Math.pow(10, decimals) || 0;
							var data = [];
							var i, value;

							for (i = 0; i < count; ++i) {
								value = (from[i] || 0) + this.rand(min, max);
								if (this.rand() <= continuity) {
									data.push(Math.round(dfactor * value) / dfactor);
								} else {
									data.push(null);
								}
							}

							return data;
						},

						labels: function(config) {
							var cfg = config || {};
							var min = cfg.min || 0;
							var max = cfg.max || 100;
							var count = cfg.count || 8;
							var step = (max - min) / count;
							var decimals = cfg.decimals || 8;
							var dfactor = Math.pow(10, decimals) || 0;
							var prefix = cfg.prefix || '';
							var values = [];
							var i;

							for (i = min; i < max; i += step) {
								values.push(prefix + Math.round(dfactor * i) / dfactor);
							}

							return values;
						},

						months: function(config) {
							var cfg = config || {};
							var count = cfg.count || 12;
							var section = cfg.section;
							var values = [];
							var i, value;

							for (i = 0; i < count; ++i) {
								value = MONTHS[Math.ceil(i) % 12];
								values.push(value.substring(0, section));
							}

							return values;
						},

						color: function(index) {
							return COLORS[index % COLORS.length];
						},

						transparentize: function(color, opacity) {
							var alpha = opacity === undefined ? 0.5 : 1 - opacity;
							return Color(color).alpha(alpha).rgbString();
						}
					};

					// DEPRECATED
					window.randomScalingFactor = function() {
						return Math.round(Samples.utils.rand(-100, 100));
					};

					// INITIALIZATION

					Samples.utils.srand(Date.now());

					// Google Analytics
					/* eslint-disable */
					if (document.location.hostname.match(/^(www\.)?chartjs\.org$/)) {
						(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
						(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
						m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
						})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
						ga('create', 'UA-28909194-3', 'auto');
						ga('send', 'pageview');
					}
					/* eslint-enable */

				}(this));

				var MONTHS 		= ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'];
				var config_purc = {
					type: 'line',
					data: {
						labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'],
						datasets: [
						/*{
							label: 'SPP',
							backgroundColor: window.chartColors.red,
							borderColor: window.chartColors.red,
							data: [
								<?php echo $T01_PR; ?>,
								<?php echo $T02_PR; ?>,
								<?php echo $T03_PR; ?>,
								<?php echo $T04_PR; ?>,
								<?php echo $T05_PR; ?>,
								<?php echo $T06_PR; ?>,
								<?php echo $T07_PR; ?>,
								<?php echo $T08_PR; ?>,
								<?php echo $T09_PR; ?>,
								<?php echo $T10_PR; ?>,
								<?php echo $T11_PR; ?>,
								<?php echo $T12_PR; ?>
							],
							fill: false,
						},*/
						{
							label: 'OP',
							fill: false,
							backgroundColor: window.chartColors.blue,
							borderColor: window.chartColors.blue,
							data: [
								<?php echo $T01_PO; ?>,
								<?php echo $T02_PO; ?>,
								<?php echo $T03_PO; ?>,
								<?php echo $T04_PO; ?>,
								<?php echo $T05_PO; ?>,
								<?php echo $T06_PO; ?>,
								<?php echo $T07_PO; ?>,
								<?php echo $T08_PO; ?>,
								<?php echo $T09_PO; ?>,
								<?php echo $T10_PO; ?>,
								<?php echo $T11_PO; ?>,
								<?php echo $T12_PO; ?>
							],
						}, {
							label: 'LPM',
							fill: false,
							backgroundColor: window.chartColors.yellow,
							borderColor: window.chartColors.yellow,
							data: [
								<?php echo $T01_IR; ?>,
								<?php echo $T02_IR; ?>,
								<?php echo $T03_IR; ?>,
								<?php echo $T04_IR; ?>,
								<?php echo $T05_IR; ?>,
								<?php echo $T06_IR; ?>,
								<?php echo $T07_IR; ?>,
								<?php echo $T08_IR; ?>,
								<?php echo $T09_IR; ?>,
								<?php echo $T10_IR; ?>,
								<?php echo $T11_IR; ?>,
								<?php echo $T12_IR; ?>
							],
						},
						/*{
							label: 'UM',
							fill: false,
							backgroundColor: window.chartColors.green,
							borderColor: window.chartColors.green,
							data: [
								<?php echo $T01_UM; ?>,
								<?php echo $T02_UM; ?>,
								<?php echo $T03_UM; ?>,
								<?php echo $T04_UM; ?>,
								<?php echo $T05_UM; ?>,
								<?php echo $T06_UM; ?>,
								<?php echo $T07_UM; ?>,
								<?php echo $T08_UM; ?>,
								<?php echo $T09_UM; ?>,
								<?php echo $T10_UM; ?>,
								<?php echo $T11_UM; ?>,
								<?php echo $T12_UM; ?>
							],
						},*/
						{
							label: 'SPK',
							fill: false,
							backgroundColor: window.chartColors.green,
							borderColor: window.chartColors.green,
							data: [
								<?php echo $T01_WO; ?>,
								<?php echo $T02_WO; ?>,
								<?php echo $T03_WO; ?>,
								<?php echo $T04_WO; ?>,
								<?php echo $T05_WO; ?>,
								<?php echo $T06_WO; ?>,
								<?php echo $T07_WO; ?>,
								<?php echo $T08_WO; ?>,
								<?php echo $T09_WO; ?>,
								<?php echo $T10_WO; ?>,
								<?php echo $T11_WO; ?>,
								<?php echo $T12_WO; ?>
							],
						}, {
							label: 'OPN',
							fill: false,
							backgroundColor: window.chartColors.purple,
							borderColor: window.chartColors.purple,
							data: [
								<?php echo $T01_OPN; ?>,
								<?php echo $T02_OPN; ?>,
								<?php echo $T03_OPN; ?>,
								<?php echo $T04_OPN; ?>,
								<?php echo $T05_OPN; ?>,
								<?php echo $T06_OPN; ?>,
								<?php echo $T07_OPN; ?>,
								<?php echo $T08_OPN; ?>,
								<?php echo $T09_OPN; ?>,
								<?php echo $T10_OPN; ?>,
								<?php echo $T11_OPN; ?>,
								<?php echo $T12_OPN; ?>
							],
						}]
					},
					options: {
						responsive: true,
						title: {
							display: true,
							text: 'Grafik Pembelian'
						},
						tooltips: {
							mode: 'index',
							intersect: false,
						},
						hover: {
							mode: 'nearest',
							intersect: true
						},
						scales: {
							xAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Bulan'
								}
							}],
							yAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Nilai (dalam Juta Rp)'
								}
							}]
						}
					}
				};

				var config_sls 	= {
					type: 'line',
					data: {
						labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'],
						datasets: [
						{
							label: 'Sales Order',
							backgroundColor: window.chartColors.red,
							borderColor: window.chartColors.red,
							data: [
								<?php echo $T01_SO; ?>,
								<?php echo $T02_SO; ?>,
								<?php echo $T03_SO; ?>,
								<?php echo $T04_SO; ?>,
								<?php echo $T05_SO; ?>,
								<?php echo $T06_SO; ?>,
								<?php echo $T07_SO; ?>,
								<?php echo $T08_SO; ?>,
								<?php echo $T09_SO; ?>,
								<?php echo $T10_SO; ?>,
								<?php echo $T11_SO; ?>,
								<?php echo $T12_SO; ?>
							],
							fill: false,
						}, {
							label: 'Job Order',
							fill: false,
							backgroundColor: window.chartColors.blue,
							borderColor: window.chartColors.blue,
							data: [
								<?php echo $T01_JO; ?>,
								<?php echo $T02_JO; ?>,
								<?php echo $T03_JO; ?>,
								<?php echo $T04_JO; ?>,
								<?php echo $T05_JO; ?>,
								<?php echo $T06_JO; ?>,
								<?php echo $T07_JO; ?>,
								<?php echo $T08_JO; ?>,
								<?php echo $T09_JO; ?>,
								<?php echo $T10_JO; ?>,
								<?php echo $T11_JO; ?>,
								<?php echo $T12_JO; ?>
							],
						}, {
							label: 'Produksi',
							fill: false,
							backgroundColor: window.chartColors.yellow,
							borderColor: window.chartColors.yellow,
							data: [
								<?php echo $T01_PRD; ?>,
								<?php echo $T02_PRD; ?>,
								<?php echo $T03_PRD; ?>,
								<?php echo $T04_PRD; ?>,
								<?php echo $T05_PRD; ?>,
								<?php echo $T06_PRD; ?>,
								<?php echo $T07_PRD; ?>,
								<?php echo $T08_PRD; ?>,
								<?php echo $T09_PRD; ?>,
								<?php echo $T10_PRD; ?>,
								<?php echo $T11_PRD; ?>,
								<?php echo $T12_PRD; ?>
							],
						}]
					},
					options: {
						responsive: true,
						title: {
							display: true,
							text: 'Grafik Penjualan'
						},
						tooltips: {
							mode: 'index',
							intersect: false,
						},
						hover: {
							mode: 'nearest',
							intersect: true
						},
						scales: {
							xAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Bulan'
								}
							}],
							yAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Nilai (dalam Juta Rp)'
								}
							}]
						}
					}
				};

				window.onload = function() {
					<?php if($GRF_PURCH == 1) { ?>
						var ctx = document.getElementById('purchase-chart').getContext('2d');
						window.myLine = new Chart(ctx, config_purc);
					<?php } ?>
					<?php if($GRF_SALES == 1) { ?>
						var cty = document.getElementById('sales-chart').getContext('2d');
						window.myLine = new Chart(cty, config_sls);
					<?php } ?>
				};
			<?php } ?>
		// END : AREA CHART

	    // START : LINE CHART
		    var line = new Morris.Line({
		      	element: 'line-chart',
		      	resize: true,
		      	data: [<?php
						$jumTotPlanAkuma 	= '';
						$GraphicTitleText	= 3;
						$sqla   	= "SELECT A.proj_Code, B.PRJNAME
										FROM tbl_projprogres A
										INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
										WHERE A.proj_Code IN ($myPRJCODEX) AND progress_Type = $GraphicTitleText
										GROUP BY proj_Code";
						$ressqla 	= $this->db->query($sqla)->result();
						
						foreach($ressqla as $rowa) :
							$proj_Code 	= $rowa->proj_Code;
							$projName 	= $rowa->PRJNAME;
							$proj_Name	= "Project : $proj_Code";
							
							$NoU 		= 0;
							$sql0   	= "SELECT MAX(day(Prg_Date1)) AS myDay, MAX(MONTH(Prg_Date1)) AS myMonth, 
												MAX(YEAR(Prg_Date1)) AS myYear, MAX(Prg_Date1) AS myDate,
												MAX(Prg_PlanAkum) AS Prg_PlanAkum, MAX(Prg_RealAkum) AS Prg_RealAkum, isShow, 
												isShowRA, isShowDev, Prg_Step
											FROM tbl_projprogres WHERE proj_Code IN ($myPRJCODEX)
												AND progress_Type = $GraphicTitleText AND isShow = 1 
											GROUP BY day(Prg_Date1), month(Prg_Date1), year(Prg_Date1) ORDER BY Prg_Date1";
							$ressql0 	= $this->db->query($sql0)->result();
							foreach($ressql0 as $row0) :
								$NoU 			= $NoU + 1;
								$DayAx 			= $row0->myDay;
								$myDate			= $row0->myDate;
								$Prg_PlanAkum	= $row0->Prg_PlanAkum;
								$Prg_RealAkum	= $row0->Prg_RealAkum;
								$totFix1 		= ".toFixed(2)";
								$totFix2 		= ".toFixed(2)";
								if($Prg_PlanAkum == 0)
									$totFix1 	= "";

								if($Prg_RealAkum == 0)
									$totFix2 	= "";

								$Prg_Dev2 		= $Prg_PlanAkum - $Prg_RealAkum;
								$isShow			= $row0->isShow;
								$isShowRA		= $row0->isShowRA;
								$isShowDev		= $row0->isShowDev;
								$myMonth		= $row0->myMonth;
								$Prg_Step		= $row0->Prg_Step;
								if($isShow == 1)
								{
									?>
										{y: "<?=$Prg_Step?>", item1: <?=$Prg_PlanAkum?>, item2: <?=$Prg_RealAkum?>},
									<?php
								}
							endforeach;
						endforeach;
					?>
		      ],
		      xkey: 'y',
		      ykeys: ['item1', 'item2'],
		      labels: ['Rencana','Realisasi'],
		      lineColors: ['#3C8DBC', '#50C878'],
		      hideHover: 'auto',
		      axes:"y"
		    });
	    // END : LINE CHART
    });
</script>
<?php
	$sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,2) AND cssjs_vers IN ('$vers', 'All')";
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

<?php
	$addDt 		= 0;
	$getPRDQ 	= base_url().'index.php/__l1y/getPRDQ/?id=';
	$cIMG		= base_url('assets/AdminLTE-2.0.5/cust_image/');
	$secIMG 	= base_url().'index.php/__l1y/getCUSTIMG/?id=';
?>
<script>
	<?php if($GRF_DPROD == 1) { ?>
		am4core.ready(function()
		{
			// Themes begin
			am4core.useTheme(am4themes_material);
			am4core.useTheme(am4themes_animated);
			// Themes end

			// START : DAILY PRODUCTION
				// Create chart instance
				var chart = am4core.create("dailyProd", am4charts.XYChart);

				// Add data
				chart.data = generateChartData();

				// Create axes
				var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.minGridDistance = 50;

				var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

				// Create series
				var series = chart.series.push(new am4charts.LineSeries());
				series.dataFields.valueY = "visits";
				series.dataFields.dateX = "date";
				series.strokeWidth = 2;
				series.minBulletDistance = 10;
				series.tooltipText = "{valueY} KG";
				series.tooltip.pointerOrientation = "vertical";
				series.tooltip.background.cornerRadius = 20;
				series.tooltip.background.fillOpacity = 0.5;
				series.tooltip.label.padding(12,12,12,12)

				// Add scrollbar
				chart.scrollbarX = new am4charts.XYChartScrollbar();
				chart.scrollbarX.series.push(series);

				// Add cursor
				chart.cursor = new am4charts.XYCursor();
				chart.cursor.xAxis = dateAxis;
				chart.cursor.snapToSeries = series;

				function generateChartData()
				{
					var tYear   	= "<?php echo $tY; ?>";
				    var tMonth   	= "<?php echo $tM; ?>";
				    var chartData 	= [];
				    var firstDate 	= new Date(tYear, tMonth, 0, 0, 0, 0);
				    firstDate.setDate(firstDate.getDate());
				    //alert(firstDate)  // Tue Dec 31 2019 00:00:00 GMT+0700 (Indochina Time)
				    var visits = 0;
				    <?php
				    	for($i=0; $i< 360; $i++)
				    	{
		   					$newDt      = date('Y-m-d', strtotime('+'.$i.' day', strtotime($tNDate1)));

		   					$sql_01 	= "SELECT SUM(DOC_PRODQ) AS TOT_PRDQ, SUM(0) AS TOT_PRDV
											FROM tbl_doc_concl WHERE DOC_DATE = '$newDt'";
						    $res_01 	= $this->db->query($sql_01)->result();
						    foreach($res_01 as $row_01) :
						        $T_PRDQ  = $row_01->TOT_PRDQ;
						        $T_PRDV  = $row_01->TOT_PRDV;
						    endforeach;
						    /*if($T01_PR == '') $T01_PR = 0;
						    if($T01_PO == '') $T01_PO = 0;
						    if($T01_IR == '') $T01_IR = 0;
						    if($T01_UM == '') $T01_UM = 0;
						    if($T01_SO == '') $T01_SO = 0;
						    if($T01_JO == '') $T01_JO = 0;
						    if($T01_PRD == '') $T01_PRD = 0;*/
					    	?>
								visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

								chartData.push({
								    date: "<?php echo $newDt; ?>",
								    visits: "<?php echo $T_PRDQ; ?>"
								});
							<?php
						}
					?>
					return chartData;
				}
			// END : DAILY PRODUCTION

			// START : CUSTOMER SALES
				var data = {
			        <?php
						$sqlCUST  = "SELECT DISTINCT CUST_CODE, CUST_DESC FROM tbl_customer WHERE CUST_STAT = 1";
				      	$resCUST  = $this->db->query($sqlCUST)->result();
				      	foreach($resCUST as $rowCUST) :
							$CUST_CODE  = $rowCUST->CUST_CODE;
							$CUST_DESC  = $rowCUST->CUST_DESC;
							// GET IMG_CODE
								$CUST_IMG = "";
								$sqlCIMG  = "SELECT IMGC_FILENAMEX FROM tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE'";
						      	$resCIMG  = $this->db->query($sqlCIMG)->result();
						      	foreach($resCIMG as $rowCIMG) :
									$CUST_IMG  = $rowCIMG->IMGC_FILENAMEX;
									//$CUST_IMG  = "BMW";
								endforeach;
								$cDATA 	= "$CUST_CODE~$CUST_IMG~$CUST_DESC";
								//$cDATA 	= "bmw";
							?>
					        "<?=$cDATA?>": {
					        	<?php
					        		$CUST_SLS = 0;
									$sqlCSLS  = "SELECT SUM(A.SO_COST) AS TOT_SLS, A.ITM_CODE, B.ITM_NAME
													FROM tbl_so_detail A
														INNER JOIN tbl_so_header X ON X.SO_NUM = A.SO_NUM AND X.PRJCODE = A.PRJCODE
														INNER JOIN tbl_item B ON B.ITM_CODE = A.ITM_CODE AND B.PRJCODE = A.PRJCODE
													WHERE X.CUST_CODE = '$CUST_CODE'
													GROUP BY A.ITM_CODE";
							      	$resCSLS  = $this->db->query($sqlCSLS)->result();
							      	foreach($resCSLS as $rowCSLS) :
										$TOT_SLS  	= $rowCSLS->TOT_SLS;
										$ITM_NAME  	= $rowCSLS->ITM_NAME;
										?>
											"<?=$ITM_NAME?>": <?=$TOT_SLS?>,
										<?php
									endforeach;
								?>
					        },
						    <?php
						endforeach;
					?>
			        "Volvo": { "S60": 16825, "S80": 7, "S90": 11090, "XC60": 22516, "XC90": 30996 }
			    }

			    function processData(data) {
			        var treeData = [];

			        var smallBrands = { name: "Other", children: [] };

			        for (var brand in data) {
			        	var countDt	= brand.split("~").length;
			        	if(countDt > 1)
			        	{
			        		var cscode 	= brand.split("~")[0];
			        		var csimgnm = brand.split("~")[1];
				        	var csfname = brand.split("~")[2];
			        	}
			        	else
			        	{
			        		var cscode 	= "";
			        		var csimgnm = "username";
				        	var csfname = "Others";
			        	}

			            var brandData 	= { code: cscode, imgnm: csimgnm, name: csfname, children: [] }
			            var brandTotal 	= 0;
			            for (var model in data[brand]) {
			                brandTotal += data[brand][model];
			            }

			            for (var model in data[brand]) {
			                // do not add very small
			                if (data[brand][model] > 100) {
			                    brandData.children.push({ name: model, count: data[brand][model] });
			                }
			            }

			            // add to small brands if total number less than
			            if (brandTotal > 100000) {
			                treeData.push(brandData);
			            }
			            else {
			              smallBrands.children.push(brandData)
			            }
			        }
			        treeData.push(smallBrands);
			        return treeData;
			    }

			    // create chart
			        var chart = am4core.create("salesInfo", am4charts.TreeMap);
			        chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect

			    // only one level visible initially
			        chart.maxLevels = 1;
			        // define data fields
			        chart.dataFields.value 		= "count";
			        chart.dataFields.code 		= "code";
			        chart.dataFields.imgnm 		= "imgnm";
			        chart.dataFields.name 		= "name";
			        chart.dataFields.children 	= "children";

			    // enable navigation
			        chart.navigationBar = new am4charts.NavigationBar();

			    // level 0 series template
			        var level0SeriesTemplate = chart.seriesTemplates.create("0");
			        level0SeriesTemplate.strokeWidth = 2;

			    // by default only current level series bullets are visible, but as we need brand bullets to be visible all the time, we modify it's hidden state
			        level0SeriesTemplate.bulletsContainer.hiddenState.properties.opacity = 1;
			        level0SeriesTemplate.bulletsContainer.hiddenState.properties.visible = true;
			    // create hover state
			        var columnTemplate = level0SeriesTemplate.columns.template;
			        var hoverState = columnTemplate.states.create("hover");

			    // darken
			        hoverState.adapter.add("fill", function (fill, target) {
			            if (fill instanceof am4core.Color) {
			                return am4core.color(am4core.colors.brighten(fill.rgb, -0.2));
			            }
			            return fill;
			        })

			    // add logo
			        var image = columnTemplate.createChild(am4core.Image);
			        image.opacity = 0.15;
			        image.align = "center";
			        image.valign = "middle";
			        image.width = am4core.percent(80);
			        image.height = am4core.percent(80);

			    // add adapter for href to load correct image
			        image.adapter.add("href", function (href, target) {
			            var dataItem = target.parent.dataItem;
			            if (dataItem) {
			            	var cscode 	= dataItem.treeMapDataItem.code;
			            	var csimgnm = dataItem.treeMapDataItem.imgnm;
			            	var csname 	= dataItem.treeMapDataItem.name;

			                var tPath 	= "<?=$cIMG?>/" + cscode + "/"+ csimgnm;
				        	if(csname == 'Other')
				        	{
			                	var tPath 	= "<?=$cIMG?>/username.png";
				        	}
			                return tPath;
			            }
			        });

			    // level1 series template
			        var level1SeriesTemplate = chart.seriesTemplates.create("1");
			        level1SeriesTemplate.columns.template.fillOpacity = 0;

			        var bullet1 = level1SeriesTemplate.bullets.push(new am4charts.LabelBullet());
			        bullet1.locationX = 0.5;
			        bullet1.locationY = 0.5;
			        bullet1.label.text = "{name}";
			        bullet1.label.fill = am4core.color("#ffffff");

			    // level2 series template
			        var level2SeriesTemplate = chart.seriesTemplates.create("2");
			        level2SeriesTemplate.columns.template.fillOpacity = 0;

			        var bullet2 = level2SeriesTemplate.bullets.push(new am4charts.LabelBullet());
			        bullet2.locationX = 0.5;
			        bullet2.locationY = 0.5;
			        bullet2.label.text = "{name}";
			        bullet2.label.fill = am4core.color("#ffffff");

			        chart.data = processData(data);
			// END : CUSTOMER SALES
		});
	<?php } ?>
</script>

<script src="<?php echo base_url('assets/AdminLTE-2.0.5/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
<script type="text/javascript">
    /* SPARKLINE CHARTS
    	* ----------------
    	* Create a inline charts with spark line
    */

    // -----------------
    // - SPARKLINE BAR -
    // -----------------
    $('.sparkbar').each(function () {
	    var $this = $(this);
	    $this.sparkline('html', {
		    type    : 'bar',
		    height  : $this.data('height') ? $this.data('height') : '30',
		    barColor: $this.data('color')
	    });
    });

    // -----------------
    // - SPARKLINE PIE -
    // -----------------
    $('.sparkpie').each(function () {
	    var $this = $(this);
	    $this.sparkline('html', {
		    type       : 'pie',
		    height     : $this.data('height') ? $this.data('height') : '90',
		    sliceColors: $this.data('color')
	    });
    });

    // ------------------
    // - SPARKLINE LINE -
    // ------------------
    $('.sparkline').each(function () {
	    var $this = $(this);
	    $this.sparkline('html', {
		    type     : 'line',
		    height   : $this.data('height') ? $this.data('height') : '90',
		    width    : '100%',
		    lineColor: $this.data('linecolor'),
		    fillColor: $this.data('fillcolor'),
		    spotColor: $this.data('spotcolor')
	    });
    });
</script>