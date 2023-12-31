<?php
    /*
        * Author        = Dian Hermanto
        * Create Date   = 1 Februari 2018
        * File Name     = opname_inb_form.php
        * Location      = -
    */

    $this->load->view('template/head');

    $appName    = $this->session->userdata('appName');
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
    $decFormat  = 2;

    $FlagUSER       = $this->session->userdata['FlagUSER'];
    $DefEmp_ID      = $this->session->userdata['Emp_ID'];

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

    $currentRow = 0;
    $isSetDocNo     = 1;
    $OPNH_NUM       = $default['OPNH_NUM'];
    $DocNumber      = $default['OPNH_NUM'];
    $OPNH_CODE      = $default['OPNH_CODE'];

    
    $JournalY           = date('Y', strtotime($default['OPNH_DATE']));
    $JournalM           = date('n', strtotime($default['OPNH_DATE']));

    $OPNH_DATE      = date('d/m/Y', strtotime($default['OPNH_DATE']));
    $OPNH_DATE      = date('d/m/Y', strtotime($default['OPNH_DATE']));
    $OPNH_DATESP    = date('d/m/Y', strtotime($default['OPNH_DATESP']));
    $OPNH_DATEEP    = date('d/m/Y', strtotime($default['OPNH_DATEEP']));
    $PRJCODE        = $default['PRJCODE'];
    $SPLCODE        = $default['SPLCODE'];
    $WO_NUM         = $default['WO_NUM'];
    $WO_NUMX        = $WO_NUM;
    $JOBCODEID      = $default['JOBCODEID'];
    $OPNH_NOTE      = $default['OPNH_NOTE'];
    $OPNH_NOTE2     = $default['OPNH_NOTE2'];
    $OPNH_STAT      = $default['OPNH_STAT'];
    $OPNH_MEMO      = $default['OPNH_MEMO'];
    $PRJNAME        = $default['PRJNAME'];
    $OPNH_AMOUNT    = $default['OPNH_AMOUNT'];
    $OPNH_AMOUNTPPNP= $default['OPNH_AMOUNTPPNP'];
    $OPNH_AMOUNTPPN = $default['OPNH_AMOUNTPPN'];
    $OPNH_AMOUNTPPHP= $default['OPNH_AMOUNTPPHP'];
    $OPNH_AMOUNTPPH = $default['OPNH_AMOUNTPPH'];
    $OPNH_RETPERC   = $default['OPNH_RETPERC'];
    $OPNH_RETAMN    = $default['OPNH_RETAMN'];
    $OPNH_DPPER     = $default['OPNH_DPPER'];
    $OPNH_DPVAL     = $default['OPNH_DPVAL'];
    $OPNH_POT       = $default['OPNH_POT'];
    $OPNH_POTREF    = $default['OPNH_POTREF'];
    $OPNH_POTREF1   = $default['OPNH_POTREF1'];
    $OPNH_POTACCID  = $default['OPNH_POTACCID'];
    $Patt_Year      = $default['Patt_Year'];
    $Patt_Month     = $default['Patt_Month'];
    $Patt_Date      = $default['Patt_Date'];
    $Patt_Number    = $default['Patt_Number'];

    $WO_VALUE   = 0;
    $WO_VALPPN  = 0;
    /*$sqlWOH     = "SELECT WO_CODE, WO_CATEG, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_VALUE, WO_VALPPN
                    FROM tbl_wo_header
                    WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE' AND WO_STAT = 3";*/
    $sqlWOH     = "SELECT WO_CODE, WO_CATEG, WO_DATE, WO_STARTD, WO_ENDD, JOBCODEID, SPLCODE, WO_NOTE, WO_VALUE, WO_VALPPN
                    FROM tbl_wo_header
                    WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
    $resWOH     = $this->db->query($sqlWOH)->result();
    foreach($resWOH as $rosWOH):
        $WO_CODE    = $rosWOH->WO_CODE;
        $WO_CATEG   = $rosWOH->WO_CATEG;
        $WO_DATE    = $rosWOH->WO_DATE;
        $WO_STARTD  = $rosWOH->WO_STARTD;
        $WO_ENDD    = $rosWOH->WO_ENDD;
        $JOBCODEID  = $rosWOH->JOBCODEID;
        $SPLCODE    = $rosWOH->SPLCODE;
        $WO_NOTE    = $rosWOH->WO_NOTE;
        $WO_VALUE   = $rosWOH->WO_VALUE;
        $WO_VALPPN  = $rosWOH->WO_VALPPN;
    endforeach;

    // DATA NILAI SPK : WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN, WO_VALUET
        $WO_DPPER   = 0;
        $WO_DPVAL   = 0;
        $WO_VALUE   = 0;
        $WO_VALPPN  = 0;
        $WO_VALUET  = 0;
        $sqlGTWO    = "SELECT WO_DPPER, WO_DPVAL, WO_VALUE, WO_VALPPN
                        FROM tbl_wo_header WHERE WO_NUM = '$WO_NUMX' AND PRJCODE = '$PRJCODE'";
        $resGTWO    = $this->db->query($sqlGTWO)->result();
        foreach($resGTWO as $rowGTWO) :
            $WO_DPPER   = $rowGTWO->WO_DPPER;
            $WO_DPVAL   = $rowGTWO->WO_DPVAL;               
            $WO_VALUE   = $rowGTWO->WO_VALUE;
            $WO_VALPPN  = $rowGTWO->WO_VALPPN;
            $WO_VALUET  = $WO_VALUE + $WO_VALPPN;
        endforeach;

    // CARI SISA OPNAME UNTUK MENGHITUNG PENGEMBALIAN DP : TOTWO_AMN, TOTWO_VOL, TOTOPN_AMN, TOTOPN_VOL, REMOPN_AMN = OPNH_AMOUNT
        $TOTWO_AMN  = 0;
        $TOTWO_VOL  = 0;
        $sqlTOT_WO  = "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWO_AMN, SUM(A.WO_VOLM) AS TOTWO_VOL
                        FROM tbl_wo_detail A
                        INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
                            AND B.PRJCODE = '$PRJCODE'
                        WHERE A.WO_NUM = '$WO_NUMX' AND A.PRJCODE = '$PRJCODE'";
        $resTOT_WO      = $this->db->query($sqlTOT_WO)->result();
        foreach($resTOT_WO as $rowTOT_WO) :
            $TOTWO_AMN  = $rowTOT_WO->TOTWO_AMN;
            $TOTWO_VOL  = $rowTOT_WO->TOTWO_VOL;
        endforeach;

        $TOTOPN_AMN = 0;
        $TOTOPN_VOL = 0;
        $sqlTOT_OPN = "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPN_AMN,
                            SUM(A.OPND_VOLM) AS TOTOPN_VOL
                        FROM tbl_opn_detail A
                        INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
                            AND B.PRJCODE = '$PRJCODE'
                        WHERE B.WO_NUM = '$WO_NUMX'
                            AND A.PRJCODE = '$PRJCODE' AND B.OPNH_STAT = '3' AND A.OPNH_NUM != '$OPNH_NUM'";
        $resTOT_OPN     = $this->db->query($sqlTOT_OPN)->result();
        foreach($resTOT_OPN as $rowTOT_OPN) :
            $TOTOPN_AMN = $rowTOT_OPN->TOTOPN_AMN;
            $TOTOPN_VOL = $rowTOT_OPN->TOTOPN_VOL;
            if($TOTOPN_AMN == '')
                $TOTOPN_AMN = 0;
            if($TOTOPN_VOL == '')
                $TOTOPN_VOL = 0;
        endforeach;

        $REMOPN_AMN     = $TOTWO_AMN - $TOTOPN_AMN;
        //$OPNH_AMOUNT  = $REMOPN_AMN;

    // CARI DOKUMEN DP UNTUK SPK INI JIKA ADA : WO_DPVAL, WO_DPVALUS, OPNH_DPPER, OPNH_DPVAL
        /*$OPNH_DPPER = $WO_DPPER;                            // PERSENTASE DP
        $OPNH_DPVAL = $WO_DPPER * $WO_DPVAL / 100;          // NILAI DP DARI SISA OPNAME (DP BACK SAAT INI)*/

    // CARI TOTAL PENGEMBALIAN DP : OPNH_TDPVAL
        $OPNH_TDPVAL    = 0;
        $sqlGTOPN       = "SELECT SUM(OPNH_DPVAL) AS TOT_DPVAL
                            FROM tbl_opn_header WHERE WO_NUM = '$WO_NUMX'
                            AND PRJCODE = '$PRJCODE' AND OPNH_STAT IN (3,6) AND OPNH_NUM != '$OPNH_NUM'";
        $resGTOPN       = $this->db->query($sqlGTOPN)->result();
        foreach($resGTOPN as $rowGTOPN) :
            $OPNH_TDPVAL    = $rowGTOPN->TOT_DPVAL;         // NILAI TOTAL PENGEMBALIAN DP
        endforeach;

    // CARI SISA PENGEMBALIAN DP : OPNH_DPVAL
        $OPNH_REMP  = $WO_DPVAL - $OPNH_TDPVAL;             // SISA DP
        if($OPNH_REMP > $OPNH_DPVAL)
        {
            $OPNH_DPVAL = $OPNH_DPVAL;
        }
        else
        {
            $OPNH_DPVAL = $OPNH_REMP;
        }

        if($WO_DPPER == 0)
            $OPNH_DPVAL = 0;

    // CONCLUSION
        $WO_VALUE           = $WO_VALUE;
        $WO_VALPPNP         = $WO_VALPPN / $WO_VALUE * 100;
        $TOTOPN_AMN         = $TOTOPN_AMN;
        $OPNH_AMOUNT        = $OPNH_AMOUNT;
        $OPNH_AMOUNTPPNP    = $OPNH_AMOUNTPPNP;
        $OPNH_AMOUNTPPN     = $OPNH_AMOUNTPPN;
        $DPVAL_WO           = $WO_DPVAL;
        $DPVAL_REM          = $OPNH_REMP;
        //$OPNH_DPVAL         = $WO_DPPER * $OPNH_AMOUNT / 100;
        if($DPVAL_REM <= 0)
            $OPNH_DPVAL     = 0;

    $isDisabled = 0;

    $TOT_PPH    = 0;
    $sqlWOD     = "SELECT SUM(A.TAXPRICE2) AS TOT_PPH
                    FROM tbl_wo_detail A
                    INNER JOIN tbl_wo_header B ON B.WO_NUM = A.WO_NUM
                    WHERE B.WO_NUM = '$WO_NUMX' AND B.PRJCODE = '$PRJCODE' AND B.WO_STAT = 3";
    $resWOD     = $this->db->query($sqlWOD)->result();
    foreach($resWOD as $rosWOD):
        $TOT_PPH    = $rosWOD->TOT_PPH;
    endforeach;

    $sqlWOH1    = "SELECT WO_CODE FROM tbl_wo_header
                    WHERE WO_NUM = '$WO_NUMX'";
    $resWOH1    = $this->db->query($sqlWOH1)->result();
    foreach($resWOH1 as $rosWOH1):
        $WO_CODE    = $rosWOH1->WO_CODE;
    endforeach;

    $OPNH_TOTAMOUNT     = $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH - $OPNH_RETAMN - $OPNH_DPVAL - $OPNH_POT;
    $OPNH_TOTAMOUNTX    = $OPNH_AMOUNT + $OPNH_AMOUNTPPN - $OPNH_AMOUNTPPH - $OPNH_RETAMN - $OPNH_DPVAL - $OPNH_POT;

    // GET Journal Lock
        $disabled   = 0;
        $getJLock   = "SELECT * FROM tbl_journal_lock 
                        WHERE LockY = $JournalY AND LockM = $JournalM AND isLock = 1 AND UserLock != '$DefEmp_ID'";
        $resJLock   = $this->db->query($getJLock);
        $countJLock = $resJLock->num_rows();
        if($countJLock == 1) $disabled = 1;
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

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <?php
        $this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');

        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];
        $LangID     = $this->session->userdata['LangID'];

        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

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
            if($TranslCode == 'StartDate')$StartDate = $LangTransl;
            if($TranslCode == 'EndDate')$EndDate = $LangTransl;
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
            if($TranslCode == 'Supplier')$Supplier = $LangTransl;
            if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
            if($TranslCode == 'reviseNotes')$reviseNotes = $LangTransl;
            if($TranslCode == 'Approval')$Approval = $LangTransl;
            if($TranslCode == 'Approved')$Approved = $LangTransl;
            if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
            if($TranslCode == 'rejected')$rejected = $LangTransl;
            if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
            if($TranslCode == 'Price')$Price = $LangTransl;
            if($TranslCode == 'DPValue')$DPValue = $LangTransl;
            if($TranslCode == 'Remain')$Remain = $LangTransl;
            if($TranslCode == 'SPKCost')$SPKCost = $LangTransl;
            if($TranslCode == 'DPRem')$DPRem = $LangTransl;
            if($TranslCode == 'UploadDoc')$UploadDoc = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
            $Month 		= ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
	        $MonthVw 	= $Month[$JournalM-1];
            $subTitleH  = "Tambah Opname";
            $subTitleD  = "opname proyek";
            $isManual   = "Centang untuk kode manual.";
            $alert1     = "Masukan alasan mengapa dokumen ini dibatalkan/ditolak.";
            $alert2     = "Silahkan pilih nama supplier.";
            $alert3     = "Nilai yang Anda inputkan lebih besar dari sisa.";
            $alert4     = "Nilai yang Anda inputkan lebih besar dari total opname.";
            $SPeriode   = "Periode Mulai";
            $docalert1  = 'Peringatan';
            $docalert2  = 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
            $alertVOID  = "Tidak dapat dibatalkan. Sudah digunakan oleh Dokumen No.: ";

            $subTitleH  = "Opname";
            $subTitleD  = "persetujuan";
            $alert5     = 'Silahkan pilih status persetujuan.';
            $SPeriode   = "Periode Mulai";
            $alertAcc   = "Belum diset kode akun penggunaan.";

            $docalert4	= "Jurnal bulan $MonthVw $JournalY sudah terkunci, silahkan hubungi departemen terkait.";
        }
        else
        {
            $Month 		= ["January","February","March","April","May","June","July","Agust","September","October","November","December"];
	        $MonthVw 	= $Month[$JournalM-1];
            $subTitleH  = "Add Opname";
            $subTitleD  = "project opname";
            $isManual   = "Check to manual code.";
            $alert1     = "Input the reason why this document is revised/rejected.";
            $alert2     = "Please select a supplier.";
            $alert3     = "Amount you inputed is greater than remaining.";
            $alert4     = "Amount you inputed is greater than total opname.";
            $SPeriode   = "Start Periode";
            $docalert1  = 'Warning';
            $docalert2  = 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
            $alertVOID  = "Can not be void. Used by document No.: ";

            $subTitleH  = "Add Opname";
            $subTitleD  = "approval";
            $alert5     = 'Please select approval status.';
            $SPeriode   = "Start Periode";
            $alertAcc   = "Not set account material usage.";

            $docalert4	= "journal month $MonthVw $JournalY locked, please contact the relevant department.";
        }

        if($PRJHO == 'KTR')
		{
			if($WO_CATEG == 'U') $MenuApp 	= "MN503";
			elseif($WO_CATEG == 'S') $MenuApp 	= "MN504";
			elseif($WO_CATEG == 'O') $MenuApp 	= "MN505";
			elseif($WO_CATEG == 'T') $MenuApp 	= "MN472";
			else $MenuApp 	= "MN513";
		}
        
        // START : APPROVE PROCEDURE
            if($APPLEV == 'HO')
                $PRJCODE_LEV    = $this->data['PRJCODE_HO'];
            else
                $PRJCODE_LEV    = $this->data['PRJCODE'];
            
            $IS_LAST    = 0;
            $APP_LEVEL  = 0;
            $APPROVER_1 = '';
            $APPROVER_2 = '';
            $APPROVER_3 = '';
            $APPROVER_4 = '';
            $APPROVER_5 = '';   
            $disableAll = 1;
            $DOCAPP_TYPE= 1;
            $sqlCAPP    = "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND PRJCODE = '$PRJCODE'";
            $resCAPP    = $this->db->count_all($sqlCAPP);
            if($resCAPP > 0)
            {
                $sqlAPP = "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
                            AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
                $resAPP = $this->db->query($sqlAPP)->result();
                foreach($resAPP as $rowAPP) :
                    $MAX_STEP   = $rowAPP->MAX_STEP;
                    $APPROVER_1     = $rowAPP->APPROVER_1;
                    if($APPROVER_1 != '')
                    {
                        $EMPN_1     = '';
                        $sqlEMPC_1  = "tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1'";
                        $resEMPC_1  = $this->db->count_all($sqlEMPC_1);
                        if($resEMPC_1 > 0)
                        {
                            $sqlEMP_1   = "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_1' AND Emp_Status = '1' LIMIT 1";
                            $resEMP_1   = $this->db->query($sqlEMP_1)->result();
                            foreach($resEMP_1 as $rowEMP) :
                                $FN_1   = $rowEMP->First_Name;
                                $LN_1   = $rowEMP->Last_Name;
                            endforeach;
                            $EMPN_1     = "$FN_1 $LN_1";
                        }
                    }
                    $APPROVER_2 = $rowAPP->APPROVER_2;
                    if($APPROVER_2 != '')
                    {
                        $EMPN_2     = '';
                        $sqlEMPC_2  = "tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1'";
                        $resEMPC_2  = $this->db->count_all($sqlEMPC_2);
                        if($resEMPC_2 > 0)
                        {
                            $sqlEMP_2   = "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_2' AND Emp_Status = '1' LIMIT 1";
                            $resEMP_2   = $this->db->query($sqlEMP_2)->result();
                            foreach($resEMP_2 as $rowEMP) :
                                $FN_2   = $rowEMP->First_Name;
                                $LN_2   = $rowEMP->Last_Name;
                            endforeach;
                            $EMPN_2     = "$FN_2 $LN_2";
                        }
                    }
                    $APPROVER_3 = $rowAPP->APPROVER_3;
                    if($APPROVER_3 != '')
                    {
                        $EMPN_3     = '';
                        $sqlEMPC_3  = "tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1'";
                        $resEMPC_3  = $this->db->count_all($sqlEMPC_3);
                        if($resEMPC_3 > 0)
                        {
                            $sqlEMP_3   = "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_3' AND Emp_Status = '1' LIMIT 1";
                            $resEMP_3   = $this->db->query($sqlEMP_3)->result();
                            foreach($resEMP_3 as $rowEMP) :
                                $FN_3   = $rowEMP->First_Name;
                                $LN_3   = $rowEMP->Last_Name;
                            endforeach;
                            $EMPN_3     = "$FN_3 $LN_3";
                        }
                    }
                    $APPROVER_4 = $rowAPP->APPROVER_4;
                    if($APPROVER_4 != '')
                    {
                        $EMPN_4     = '';
                        $sqlEMPC_4  = "tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1'";
                        $resEMPC_4  = $this->db->count_all($sqlEMPC_4);
                        if($resEMPC_4 > 0)
                        {
                            $sqlEMP_4   = "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_4' AND Emp_Status = '1' LIMIT 1";
                            $resEMP_4   = $this->db->query($sqlEMP_4)->result();
                            foreach($resEMP_4 as $rowEMP) :
                                $FN_4   = $rowEMP->First_Name;
                                $LN_4   = $rowEMP->Last_Name;
                            endforeach;
                            $EMPN_4     = "$FN_4 $LN_4";
                        }
                    }
                    $APPROVER_5 = $rowAPP->APPROVER_5;
                    if($APPROVER_5 != '')
                    {
                        $EMPN_5     = '';
                        $sqlEMPC_5  = "tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1'";
                        $resEMPC_5  = $this->db->count_all($sqlEMPC_5);
                        if($resEMPC_5 > 0)
                        {
                            $sqlEMP_5   = "SELECT First_Name, Last_Name FROM tbl_employee WHERE Emp_ID = '$APPROVER_5' AND Emp_Status = '1' LIMIT 1";
                            $resEMP_5   = $this->db->query($sqlEMP_5)->result();
                            foreach($resEMP_5 as $rowEMP) :
                                $FN_5   = $rowEMP->First_Name;
                                $LN_5   = $rowEMP->Last_Name;
                            endforeach;
                            $EMPN_5     = "$FN_5 $LN_5";
                        }
                    }
                endforeach;
                $disableAll = 0;
            
                // CHECK AUTH APPROVE TYPE
                $sqlAPPT    = "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'
                                AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
                $resAPPT    = $this->db->query($sqlAPP)->result();
                foreach($resAPPT as $rowAPPT) :
                    $DOCAPP_TYPE    = $rowAPPT->DOCAPP_TYPE;
                endforeach;
            }
            
            $sqlSTEPAPP = "tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'
                            AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
            $resSTEPAPP = $this->db->count_all($sqlSTEPAPP);
            if($resSTEPAPP > 0)
            {
                $canApprove = 1;
                $APPLIMIT_1 = 0;
                
                $sqlAPP = "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuCode'
                            AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE')";
                $resAPP = $this->db->query($sqlAPP)->result();
                foreach($resAPP as $rowAPP) :
                    $APPLIMIT_1 = $rowAPP->APPLIMIT_1;
                    $APP_STEP   = $rowAPP->APP_STEP;
                    $MAX_STEP   = $rowAPP->MAX_STEP;
                endforeach;
                
                $sqlC_App   = "tbl_approve_hist WHERE AH_CODE = '$OPNH_NUM'";
                $resC_App   = $this->db->count_all($sqlC_App);
                $BefStepApp = $APP_STEP - 1;
                
                if($resC_App == $BefStepApp)
                {
                    $canApprove = 1;
                }
                elseif($resC_App >= $APP_STEP)
                {
                    $canApprove = 0;
                    $descApp    = "You have Approved";
                    $statcoloer = "success";
                }
                else
                {
                    $canApprove = 0;
                    $descApp    = "Awaiting";
                    $statcoloer = "warning";
                }
                             
                if($APP_STEP == $MAX_STEP)
                    $IS_LAST        = 1;
                else
                    $IS_LAST        = 0;
                
                // Mungkin dengan tahapan approval lolos, check kembali total nilai jika dan HANYA JIKA Type Approval Step is 1 = Ammount
                // This roles are for All Approval. Except PR and Receipt
                // NOTES
                // $APPLIMIT_1      = Maximum Limit to Approve
                // $APPROVE_AMOUNT  = Amount must be Approved
                $APPROVE_AMOUNT = $OPNH_AMOUNT;
                //$APPROVE_AMOUNT   = 10000000000;
                //$DOCAPP_TYPE  = 1;
                if($DOCAPP_TYPE == 1)
                {
                    if($APPLIMIT_1 < $APPROVE_AMOUNT)
                    {
                        $canApprove = 0;
                        $descApp    = "You can not approve caused of the max limit.";
                        $statcoloer = "danger";
                    }
                }
            }
            else
            {
                $canApprove = 0;
                $descApp    = "You can not approve this document.";
                $statcoloer = "danger";
                $IS_LAST    = 0;
                $APP_STEP   = 0;
            }
            $APP_LEVEL  = $APP_STEP;
        // END : APPROVE PROCEDURE
        
        $secAddURL  = site_url('c_project/c_spk/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
        $secGenCode = base_url().'index.php/c_project/c_spk/genCode/'; // Generate Code

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
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/opname.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <form method="post" name="sendDate" id="sendDate" class="form-user" action="<?php echo $secGenCode; ?>" style="display:none">
                    <table>
                        <tr>
                            <td>
                                <input type="hidden" name="PRJCODEX" id="PRJCODEX" value="<?php echo $PRJCODE; ?>">
                                <input type="hidden" name="Pattern_Code" id="Pattern_Code" value="<?php echo $Pattern_Code; ?>">
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
                    <input type="hidden" name="IS_LAST" id="IS_LAST" value="<?php echo $IS_LAST; ?>">
                    <input type="hidden" name="APP_LEVEL" id="APP_LEVEL" value="<?php echo $APP_LEVEL; ?>">
                    <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>" />
                    <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
                    <input type="hidden" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" />
                    <input type="Hidden" name="rowCount" id="rowCount" value="0">
                    <input type="hidden" name="OPNH_NUM" id="OPNH_NUM" value="<?php echo $DocNumber; ?>" />
                    <input type="hidden" name="PRJCODE" id="PRJCODE"  value="<?php echo $PRJCODE; ?>" />
                    <input type="hidden" name="JOBCODEID" id="JOBCODEID" value="<?php echo $JOBCODEID; ?>">
                    <?php
                        // START : LOCK PROCEDURE
                            $app_stat   = $this->session->userdata['app_stat'];
                            if($LangID == 'IND')
                            {
                                $appAlert1  = 'Terkunci!';
                                $appAlert2  = 'Mohon maaf, saat ini transaksi penjurnalan sedang terkunci.';
                            }
                            else
                            {
                                $appAlert1  = 'Locked!';
                                $appAlert2  = 'Sorry, the journalizing transaction is currently locked.';
                            }
                            ?>
                                <input type="hidden" name="app_stat" id="app_stat" value="<?php echo $app_stat; ?>">
                                <div class="col-sm-12" id="divAlert" style="display: none;">
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
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $OpnNo; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" style="text-align:left" name="OPNH_NUM1" id="OPNH_NUM1" size="30" value="<?php echo $DocNumber; ?>" disabled />
                                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" name="OPNH_NUM" id="OPNH_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                                    </div>
                                </div>

                                <!-- OPNH_CODE -->
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code; ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" style="text-align:left" id="OPNH_CODE" name="OPNH_CODE" size="5" value="<?php echo $OPNH_CODE; ?>" readonly />
                                        <label style="display:none;">
                                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual">
                                        </label>
                                        <label style="font-style:italic;display:none;">
                                            <?php echo $isManual; ?>
                                        </label>
                                    </div>
                                </div>

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
                                                <button type="button" class="btn btn-primary" onClick="pleaseCheck();" disabled><?php echo "Cari SPK"; ?> </button>
                                            </div>
                                            <input type="hidden" class="form-control" name="WO_NUM" id="WO_NUM" style="max-width:160px" value="<?php echo $WO_NUMX; ?>" >
                                            <input type="hidden" class="form-control" name="WO_CODE" id="WO_CODE" style="max-width:160px" value="<?php echo $WO_CODE; ?>" >
                                            <input type="hidden" class="form-control" name="WO_CATEG" id="WO_CATEG" style="max-width:160px" value="<?php echo $WO_CATEG; ?>" >
                                            <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo $WO_CODE; ?>" onClick="pleaseCheck();" <?php if($task != 'add') { ?> disabled <?php } ?> style="display: none;">

                                            <input type="text" class="form-control" name="WO_NUM1" id="WO_NUM1" value="<?php echo "$WO_CODE"; ?>" onClick="pleaseCheck();" readonly>
                                            <input type="hidden" class="form-control" name="WO_NUM2" id="WO_NUM2" value="<?php echo $WO_CODE; ?>" data-toggle="modal" data-target="#mdl_addJList">
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $selSPKNo   = site_url('c_project/c_o180d0bpnm/popupallOPNH/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                ?>
                                <script>
                                    var url1 = "<?php echo $selSPKNo;?>";
                                    function pleaseCheck()
                                    {
                                        document.getElementById('WO_NUM2').click();
                                    }
                                </script>

                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $StartDate; ?></label>
                                    <div class="col-sm-4">
                                        <div class="input-group date">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATESP" class="form-control pull-left" id="datepicker1" value="<?php echo $OPNH_DATESP; ?>" readonly></div>
                                    </div>
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $EndDate; ?></label>
                                    <div class="col-sm-4">
                                        <div class="input-group date">
                                        <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>&nbsp;</div><input type="text" name="OPNH_DATEEP" class="form-control pull-left" id="datepicker2" value="<?php echo $OPNH_DATEEP; ?>" readonly></div>
                                    </div>
                                </div>

                                <!-- SPLCODE -->
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Supplier; ?></label>
                                    <div class="col-sm-10">
                                        <select name="SPLCODEX" id="SPLCODEX" class="form-control select2" disabled>
                                          <option value="none">---</option>
                                          <?php
                                                $sqlSpl = "SELECT SPLCODE, SPLDESC FROM tbl_supplier WHERE SPLSTAT = '1' ORDER BY SPLDESC ASC";
                                                $sqlSpl = $this->db->query($sqlSpl)->result();
                                                foreach($sqlSpl as $row) :
                                                    $SPLCODE1   = $row->SPLCODE;
                                                    $SPLDESC1   = $row->SPLDESC;
                                                    ?>
                                                        <option value="<?php echo "$SPLCODE1"; ?>" <?php if($SPLCODE1 == $SPLCODE) { ?> selected <?php } ?>>
                                                            <?php echo "$SPLDESC1 - $SPLCODE1"; ?>
                                                        </option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                        <input type="hidden" class="form-control" name="SPLCODE" id="SPLCODE" value="<?php echo $SPLCODE; ?>" >
                                    </div>
                                </div>

                                <!-- OPNH_NOTE -->
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Notes; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="OPNH_NOTE" id="OPNH_NOTE" class="form-control" readonly><?php echo $OPNH_NOTE; ?></textarea>
                                    </div>
                                </div>

                                <!-- OPNH_NOTE2 -->
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $ApproverNotes; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="OPNH_NOTE2" class="form-control" id="OPNH_NOTE2"><?php echo $OPNH_NOTE2; ?></textarea>
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

                                <div class="row">
                                    <div class="col-xs-4">
                                        <label for="exampleInputEmail1"><?php echo $SPKCost; ?></label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="exampleInputEmail1">Diopname</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="exampleInputEmail1">Opname saat ini</label>
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
                                        <label for="exampleInputEmail1">Retensi</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="exampleInputEmail1"><?=$DPRem?></label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label for="exampleInputEmail1">Pengemb. DP</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- OPNH_RETPERC, OPNH_RETAMN -->
                                    <div class="col-xs-4">
                                        <label>
                                            <input type="hidden" class="form-control" style="text-align:right" name="OPNH_RETPERC" id="OPNH_RETPERC" value="<?php echo $OPNH_RETPERC; ?>" >
                                            <input type="text" class="form-control" style="max-width: 60px; text-align:right" name="OPNH_RETPERCX" id="OPNH_RETPERCX" value="<?php echo number_format($OPNH_RETPERC, 2); ?>" onBlur="getAmountRet1()" onKeyPress="return isIntOnlyNew(event);" >
                                        </label>
                                        <label>
                                            <input type="hidden" class="form-control" style="text-align:right" name="OPNH_RETAMN" id="OPNH_RETAMN" value="<?php echo $OPNH_RETAMN; ?>" >
                                            <input type="text" class="form-control" style="max-width: 125px; text-align:right" name="OPNH_RETAMNX" id="OPNH_RETAMNX" value="<?php echo number_format($OPNH_RETAMN, 2); ?>" onKeyPress="return isIntOnlyNew(event);" disabled >
                                        </label>
                                    </div>
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
                                            <input type="text" class="form-control" style="max-width:60px; text-align:right;" name="OPNH_DPPERX" id="OPNH_DPPERX" value="<?php echo number_format($OPNH_DPPER, $decFormat); ?>" onBlur="getDPer()" >
                                        </label>
                                        <label>
                                            <input type="hidden" class="form-control" style="text-align:right;" name="OPNH_DPVAL" id="OPNH_DPVAL" value="<?php echo $OPNH_DPVAL; ?>">
                                            <input type="text" class="form-control" style="max-width:125px; text-align:right;" name="OPNH_DPVALX" id="OPNH_DPVALX" value="<?php echo number_format($OPNH_DPVAL, $decFormat); ?>" onBlur="getDPVal(this)" >
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
                                    $sqlLO  = "SELECT A.Base_Debet FROM tbl_journaldetail A
                                                WHERE A.Journal_DK = 'D' AND A.JournalH_Code = '$OPNH_POTREF' LIMIT 1";
                                    $resLO  = $this->db->query($sqlLO)->result();
                                    $totRow = 0;
                                    $REM_AMOUNT = 0;
                                    foreach($resLO as $row) :
                                        $Base_Debet     = $row->Base_Debet;

                                        // CARI SUDAH TEROPNAME
                                            $TOT_PAID   = 0;
                                            $sqlTOT = "SELECT SUM(OPNH_POT) AS TOT_PAID FROM tbl_opn_header
                                                        WHERE OPNH_POTREF = '$OPNH_POTREF'
                                                            AND OPNH_STAT IN (3,6)";
                                            $resTOT = $this->db->query($sqlTOT)->result();
                                            foreach($resTOT as $row) :
                                                $TOT_PAID   = $row->TOT_PAID;   // 0
                                            endforeach;
                                        $REM_AMOUNT = $Base_Debet - $TOT_PAID;
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
                                    <div class="col-sm-10">
                                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $OPNH_STAT; ?>">
                                        <input type="hidden" name="OPNH_AMOUNT" id="OPNH_AMOUNT" value="<?php echo $OPNH_AMOUNT; ?>">
                                        <?php
                                            // START : FOR ALL APPROVAL FUNCTION
                                                if($disableAll == 0)
                                                {
                                                    if($canApprove == 1)
                                                    {
                                                        $disButton  = 0;
                                                        $sqlCAPPHE  = "tbl_approve_hist WHERE AH_CODE = '$OPNH_NUM' AND AH_APPROVER = '$DefEmp_ID'";
                                                        $resCAPPHE  = $this->db->count_all($sqlCAPPHE);
                                                        if($resCAPPHE > 0)
                                                            $disButton  = 1;
                                                        ?>
                                                            <select name="OPNH_STAT" id="OPNH_STAT" class="form-control select2" <?php if($disButton == 1) { ?> disabled <?php } ?> >
                                                                <option value="0"> --- </option>
                                                                <option value="3"<?php if($OPNH_STAT == 3) { ?> selected <?php } ?> <?php if($disabled == 1) { ?> disabled <?php } ?>>Approved</option>
                                                                <option value="4"<?php if($OPNH_STAT == 4) { ?> selected <?php } ?>>Revised</option>
                                                                <option value="5"<?php if($OPNH_STAT == 5) { ?> selected <?php } ?>>Rejected</option>
                                                                <!-- <option value="6"<?php if($OPNH_STAT == 6) { ?> selected <?php } ?>>Closed</option> -->
                                                                <!-- <option value="7"<?php if($OPNH_STAT == 7) { ?> selected <?php } ?> style="display:none">Awaiting</option> -->
                                                            </select>
                                                        <?php
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <a href="" class="btn btn-<?php echo $statcoloer; ?> btn-xs">
                                                                <?php echo $descApp; ?>
                                                            </a>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>
                                                        <a href="" class="btn btn-danger btn-xs">
                                                            Step approval not set;
                                                        </a>
                                                    <?php
                                                }
                                            // END : FOR ALL APPROVAL FUNCTION
                                            $theProjCode    = $PRJCODE;
                                            $url_AddItem    = site_url('c_project/c_spk/popupallitem/?id='.$this->url_encryption_helper->encode_url($theProjCode));
                                        ?>
                                    </div>
                                </div>
                                <br>

                                <script>
                                    function getAmountRet1()
                                    {
                                        OPNH_RETPERC1   = document.getElementById('OPNH_RETPERCX');
                                        OPNH_RETPERC    = parseFloat(eval(OPNH_RETPERC1).value.split(",").join(""));

                                        var totOPN1 = 0;
                                        var totRow = document.getElementById('totalrow').value;
                                        for(i=1;i<=totRow;i++)
                                        {
                                            var OPND_ITMTOTAL   = parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
                                            var OPND_TOTTAX     = parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
                                            totOPN1             = parseFloat(totOPN1 + OPND_ITMTOTAL);
                                        };

                                        OPNH_AMOUNTPPN  = parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);
                                        OPNH_DPVAL      = parseFloat(document.getElementById('OPNH_DPVAL').value);
                                        OPNH_POT        = parseFloat(document.getElementById('OPNH_POT').value);


                                        //OPNH_AMOUNT1  = parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
                                        // OPNH_AMOUNT1 = parseFloat(totOPN1) - parseFloat(OPNH_POT);
                                        // INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
                                        OPNH_AMOUNT1    = parseFloat(totOPN1);

                                        // RETENSI dikalikan terhadap nilai setelah dikurangi potongan
                                        //OPNH_RETAMN       = parseFloat(OPNH_RETPERC * totOPN1 / 100);
                                        OPNH_RETAMN     = parseFloat(OPNH_RETPERC * OPNH_AMOUNT1 / 100);

                                        document.getElementById('OPNH_AMOUNT').value    = OPNH_AMOUNT1;
                                        document.getElementById('OPNH_AMOUNTX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

                                        document.getElementById('OPNH_RETPERC').value   = OPNH_RETPERC;
                                        document.getElementById('OPNH_RETPERCX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETPERC)), 2));

                                        document.getElementById('OPNH_RETAMN').value    = OPNH_RETAMN;
                                        document.getElementById('OPNH_RETAMNX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_RETAMN)), 2));

                                        // AKUMULASI RETENSI
                                        OPNH_RETAMNB    = parseFloat(document.getElementById('OPNH_RETAMNB').value);
                                        //swal(OPNH_RETAMNB)
                                        AKUM_RETAMN     = parseFloat(OPNH_RETAMN) + parseFloat(OPNH_RETAMNB);
                                        /*if(OPNH_RETAMN > 0)
                                            AKUM_RETAMN = 0.05 * parseFloat(OPNH_AMOUNTB);  // DIAMBIL 5% TOTAL AKUMULASI PEMBAYARAN
                                        else
                                            AKUM_RETAMN = 0;    // DIAMBIL 5% TOTAL AKUMULASI PEMBAYARAN*/

                                        document.getElementById('AKUM_RETAMN').value    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_RETAMN)), 2));

                                        // AKUMULASI TOTAL AWAL
                                        PROG_BEF    = parseFloat(document.getElementById('PROG_BEF').value);
                                        AKUM_PROG   = parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
                                        document.getElementById('AKUM_PROG').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

                                        countGTotal();
                                    }

                                    //function getPOT(thisVal)
                                    function getPOT()
                                    {
                                        OPNH_POT1   = document.getElementById('OPNH_POTX');
                                        OPNH_POT    = parseFloat(eval(OPNH_POT1).value.split(",").join(""));
                                        OPNH_REMAIN = parseFloat(document.getElementById('OPNH_REMAIN').value);
                                        if(OPNH_POT > OPNH_REMAIN)
                                        {
                                            swal('<?php echo $alert3; ?>');
                                            document.getElementById("OPNH_POT").focus();

                                            document.getElementById('OPNH_POT').value   = OPNH_REMAIN;
                                            document.getElementById('OPNH_POTX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_REMAIN)), 2));
                                            OPNH_POT    = parseFloat(document.getElementById('OPNH_POT').value);
                                        }

                                        var totOPN1 = 0;
                                        var totRow = document.getElementById('totalrow').value;
                                        for(i=1;i<=totRow;i++)
                                        {
                                            var OPND_ITMTOTAL   = parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
                                            var OPND_TOTTAX     = parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
                                            totOPN1             = parseFloat(totOPN1 + OPND_ITMTOTAL);
                                        }

                                        //OPNH_AMOUNTX  = parseFloat(document.getElementById('OPNH_AMOUNT').value);
                                        OPNH_AMOUNTX    = parseFloat(totOPN1);
                                        if(OPNH_POT > OPNH_AMOUNTX)
                                        {
                                            swal('<?php echo $alert4; ?>');
                                            document.getElementById("OPNH_POT").focus();

                                            document.getElementById('OPNH_POT').value   = OPNH_AMOUNTX;
                                            document.getElementById('OPNH_POTX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNTX)), 2));
                                            OPNH_POT    = parseFloat(document.getElementById('OPNH_POT').value);
                                        }

                                        OPNH_RETAMN     = parseFloat(document.getElementById('OPNH_RETAMN').value);
                                        OPNH_DPVAL      = parseFloat(document.getElementById('OPNH_DPVAL').value);
                                        OPNH_AMOUNTPPN  = parseFloat(document.getElementById('OPNH_AMOUNTPPN').value);

                                        //OPNH_AMOUNT1  = parseFloat(totOPN1) - parseFloat(OPNH_DPVAL) - parseFloat(OPNH_RETAMN) - parseFloat(OPNH_POT) + parseFloat(OPNH_AMOUNTPPN);
                                        // OPNH_AMOUNT1 = parseFloat(totOPN1) - parseFloat(OPNH_POT);
                                        // INFO BU AN @07 JAN 2019 TIDAK MEMOTONG HANYA INFO SAJA
                                        OPNH_AMOUNT1    = parseFloat(totOPN1);

                                        document.getElementById('OPNH_AMOUNT').value    = OPNH_AMOUNT1;
                                        document.getElementById('OPNH_AMOUNTX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_AMOUNT1)), 2));

                                        document.getElementById('OPNH_POT').value   = OPNH_POT;
                                        document.getElementById('OPNH_POTX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPNH_POT)), 2));

                                        // AKUMULASI TOTAL AWAL
                                        PROG_BEF    = parseFloat(document.getElementById('PROG_BEF').value);
                                        AKUM_PROG   = parseFloat(OPNH_AMOUNT1) + parseFloat(PROG_BEF);
                                        document.getElementById('AKUM_PROG').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

                                        getPPnPer();
                                        getDPer();
                                        getAmountRet1();
                                    }
                                </script>

                                <?php
                                    $url_popdp  = site_url('c_project/c_o180d0bpnm/ll_4p/?id=');
                                ?>
                                <script>
                                    var urlDP = "<?php echo "$url_popdp";?>";
                                    function getPOTREF()
                                    {
                                        PRJCODE = document.getElementById("PRJCODE").value;
                                        SPLCODE = document.getElementById("SPLCODE").value;
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

                                    function add_POT(strItem)
                                    {
                                        arrItem = strItem.split('|');
                                        POT_NUM     = arrItem[0];
                                        POT_CODE    = arrItem[1];
                                        POT_REMAM   = arrItem[2];
                                        Acc_Id      = arrItem[3];
                                        document.getElementById('OPNH_POTREF').value    = POT_NUM;
                                        document.getElementById('OPNH_POTREF1').value   = POT_CODE;
                                        document.getElementById('OPNH_REMAIN').value    = POT_REMAM;
                                        document.getElementById('OPNH_POTACCID').value  = Acc_Id;

                                        document.getElementById('OPNH_POT').value       = POT_REMAM;
                                        document.getElementById('OPNH_POTX').value      = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(POT_REMAM)), 2));

                                        getPOT();
                                    }
                                </script>
                                <div id="revMemo" class="form-group" <?php if($OPNH_MEMO == '') { ?> style="display:none" <?php } ?>>
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $reviseNotes; ?></label>
                                    <div class="col-sm-9">
                                        <textarea name="OPNH_MEMO" class="form-control" style="max-width:350px;" id="OPNH_MEMO" cols="30" disabled><?php echo $OPNH_MEMO; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12" id="alrtLockJ" style="display: none;">
						<div class="alert alert-warning alert-dismissible col-md-12">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-warning"></i> <?php echo $docalert1; ?>!</h4>
							<?php echo $docalert4; ?>
						</div>
					</div>

                    <div class="col-md-12">
                        <div class="box box-default collapsed-box">
                            <div class="box-header with-border">
                                <label for="inputName"><?php echo $UploadDoc; ?></label>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
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
                                                        $newRow         = $newRow + 1;
                                                        $UPL_NUM        = $rDOC->UPL_NUM;
                                                        $REF_NUM        = $rDOC->REF_NUM;
                                                        $REF_CODE       = $rDOC->REF_CODE;
                                                        $UPL_PRJCODE    = $rDOC->PRJCODE;
                                                        $UPL_DATE       = $rDOC->UPL_DATE;
                                                        $UPL_FILENAME   = $rDOC->UPL_FILENAME;
                                                        $UPL_FILESIZE   = $rDOC->UPL_FILESIZE;
                                                        $UPL_FILETYPE   = $rDOC->UPL_FILETYPE;

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
                                            $sqlDETC    = "tbl_wo_detail A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                    AND B.PRJCODE = '$PRJCODE'
                                                            WHERE WO_NUM = '$WO_NUMX'
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $resultC    = $this->db->count_all($sqlDETC);
                                        }
                                        elseif($task == 'add' && $WO_NUMX != '')
                                        {
                                            $sqlDETWO   = "SELECT '' AS OPND_ID, A.WO_ID, A.WO_NUM, A.PRJCODE, A.JOBCODEDET, A.JOBCODEID, A.ITM_CODE, A.ITM_UNIT,
                                                                A.WO_VOLM AS OPND_VOLM,
                                                                A.WO_VOLM2 AS OPND_VOLM2,
                                                                A.ITM_PRICE AS OPND_ITMPRICE, A.WO_TOTAL AS OPND_ITMTOTAL,
                                                                A.WO_DESC AS OPND_DESC, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2
                                                            FROM tbl_wo_detail A
                                                            WHERE WO_NUM = '$WO_NUMX'
                                                                AND A.PRJCODE = '$PRJCODE'";
                                            $resDETWO   = $this->db->query($sqlDETWO)->result();

                                            $sqlDETC    = "tbl_wo_detail A
                                                            WHERE WO_NUM = '$WO_NUMX'
                                                                AND A.PRJCODE = '$PRJCODE'";
                                            $resultC    = $this->db->count_all($sqlDETC);
                                        }
                                        else
                                        {
                                            if($task == 'edit')
                                            {
                                                //*from data
                                                $sqlDET     = "SELECT A.OPND_ID, A.JOBCODEDET, A.JOBCODEID, A.WO_ID, A.ITM_CODE, A.ITM_UNIT, A.ITM_GROUP,
                                                                    A.OPND_PERC, A.OPND_VOLM, A.OPND_VOLM2, A.OPND_ITMPRICE, A.ACC_ID_UM,
                                                                    A.OPND_ITMTOTAL, A.OPND_DESC, A.TAXCODE1, A.TAXCODE2, A.TAXPRICE1, A.TAXPRICE2,
                                                                    B.WO_NUM, B.PRJCODE
                                                                FROM tbl_opn_detail A
                                                                    INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
                                                                WHERE
                                                                    A.OPNH_NUM = '$OPNH_NUM'
                                                                    AND B.OPNH_TYPE = 0
                                                                    AND B.PRJCODE = '$PRJCODE'";
                                                $resDETWO = $this->db->query($sqlDET)->result();
                                                // count data
                                                $sqlDETC    = "tbl_opn_detail A
                                                                    INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                                WHERE
                                                                    A.OPNH_NUM = '$OPNH_NUM'
                                                                    AND B.PRJCODE = '$PRJCODE'";
                                                $resultC    = $this->db->count_all($sqlDETC);
                                            }
                                        }

                                        if($resultC > 0)
                                        {
                                            $i      = 0;
                                            $j      = 0;

                                            foreach($resDETWO as $row) :
                                                $currentRow     = ++$i;
                                                $OPND_ID        = $row->OPND_ID;
                                                $WO_ID          = $row->WO_ID;
                                                $WO_NUM         = $row->WO_NUM;
                                                $PRJCODE        = $row->PRJCODE;
                                                $JOBCODEDET     = $row->JOBCODEDET;
                                                $JOBCODEID      = $row->JOBCODEID;
                                                $ITM_CODE       = $row->ITM_CODE;
                                                $ACC_ID_UM      = $row->ACC_ID_UM;
                                                $ACCIDUM        = $row->ACC_ID_UM;
                                                $ITM_GROUP      = $row->ITM_GROUP;

                                                $ITM_NAME       = '';
                                                $sqlDETITM      = "SELECT A.ITM_NAME, A.ACC_ID_UM, A.ITM_GROUP
                                                                    FROM tbl_item A
                                                                    WHERE A.ITM_CODE = '$ITM_CODE'
                                                                        AND A.PRJCODE = '$PRJCODE'";
                                                $resDETITM      = $this->db->query($sqlDETITM)->result();
                                                foreach($resDETITM as $detITM) :
                                                    $ITM_NAME       = $detITM->ITM_NAME;
                                                    $ACCIDUM        = $detITM->ACC_ID_UM;
                                                    $ITM_GROUP      = $detITM->ITM_GROUP;
                                                endforeach;

                                                if($ACC_ID_UM == '')
                                                    $ACC_ID_UM  = $ACCIDUM;

                                                //$ITM_NAME         = $row->ITM_NAME;
                                                $ITM_UNIT       = $row->ITM_UNIT;
                                                $OPND_PERC      = $row->OPND_PERC;
                                                $OPND_VOLM      = $row->OPND_VOLM;
                                                $OPND_VOLM2     = $row->OPND_VOLM2;
                                                $ITM_PRICE      = $row->OPND_ITMPRICE;
                                                $OPND_ITMTOTAL  = $row->OPND_ITMTOTAL;
                                                $TAXCODE1       = $row->TAXCODE1;
                                                $TAXPRICE1      = $row->TAXPRICE1;
                                                $TAXCODE2       = $row->TAXCODE2;
                                                $TAXPRICE2      = $row->TAXPRICE2;
                                                $OPND_DESC      = $row->OPND_DESC;
                                                $itemConvertion = 1;

                                                $TAXCODE_PPN    = "";
                                                if($TAXCODE1 != "")
                                                    $TAXCODE_PPN = $TAXCODE1;

                                                $TAXCODE_PPH    = "";
                                                if($TAXCODE2 != "")
                                                    $TAXCODE_PPH = $TAXCODE2;

                                                // TOTAL SPK YANG DIPIIH
                                                    $TOTWOAMOUNT    = 0;
                                                    $TOTWOQTY       = 0;
                                                    $sqlTOTWO       = "SELECT SUM(A.WO_VOLM * A.ITM_PRICE) AS TOTWOAMOUNT, 
                                                                        SUM(A.WO_VOLM) AS TOTWOQTY
                                                                        FROM tbl_wo_detail A
                                                                        INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
                                                                            AND B.PRJCODE = '$PRJCODE'
                                                                        WHERE A.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE' AND A.PRJCODE = '$PRJCODE'
                                                                            AND A.JOBCODEDET = '$JOBCODEDET' AND A.WO_ID = $WO_ID";
                                                    $resTOTWO       = $this->db->query($sqlTOTWO)->result();
                                                    foreach($resTOTWO as $rowTOTWO) :
                                                        $TOTWOAMOUNT    = $rowTOTWO->TOTWOAMOUNT;
                                                        $TOTWOQTY       = $rowTOTWO->TOTWOQTY;
                                                    endforeach;

                                                // TOTAL OPN APPROVED
                                                    $TOTOPNAMOUNT   = 0;
                                                    $TOTOPNQTY      = 0;
                                                    $sqlTOTOPN      = "SELECT SUM(A.OPND_VOLM * A.OPND_ITMPRICE) AS TOTOPNAMOUNT,
                                                                            SUM(A.OPND_VOLM) AS TOTOPNQTY
                                                                        FROM tbl_opn_detail A
                                                                        INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
                                                                            AND B.PRJCODE = '$PRJCODE'
                                                                        WHERE B.WO_NUM = '$WO_NUMX' AND A.ITM_CODE = '$ITM_CODE'
                                                                            AND A.PRJCODE = '$PRJCODE'
                                                                            AND A.JOBCODEDET = '$JOBCODEDET' AND B.OPNH_STAT IN (2,3,6)
                                                                            AND B.OPNH_TYPE = 0
                                                                            AND A.OPNH_NUM != '$OPNH_NUM' AND A.WO_ID = $WO_ID";
                                                    $resTOTOPN      = $this->db->query($sqlTOTOPN)->result();
                                                    foreach($resTOTOPN as $rowTOTOPN) :
                                                        $TOTOPNAMOUNT   = $rowTOTOPN->TOTOPNAMOUNT;
                                                        $TOTOPNQTY      = $rowTOTOPN->TOTOPNQTY;
                                                        if($TOTOPNAMOUNT == '')
                                                            $TOTOPNAMOUNT   = 0;
                                                        if($TOTOPNQTY == '')
                                                            $TOTOPNQTY  = 0;
                                                    endforeach;

                                                    // SISA QTY PR
                                                    if($task == 'add')
                                                        $REMOPNQTY      = $TOTWOQTY - $TOTOPNQTY;
                                                    else
                                                        $REMOPNQTY      = $OPND_VOLM;

                                                    $OPND_ITMTOTAL      = $REMOPNQTY * $ITM_PRICE;

                                                    $disableInp     = 0;
                                                    if($TOTOPNQTY >= $TOTWOQTY)
                                                    {
                                                        $disableInp = 1;
                                                        $REMOPNQTY  = 0;
                                                    }

                                                // GET HEADER JOB
                                                    $JOBHDESC       = "";
                                                    $sqlHDESC       = "SELECT A.JOBPARENT, B.JOBDESC FROM tbl_joblist_detail A
                                                                        INNER JOIN tbl_joblist B ON A.JOBPARENT = B.JOBCODEID
                                                                            AND B.PRJCODE = '$PRJCODE'
                                                                        WHERE A.JOBCODEID = '$JOBCODEID' AND A.PRJCODE = '$PRJCODE' LIMIT 1";
                                                    $resHDESC       = $this->db->query($sqlHDESC)->result();
                                                    foreach($resHDESC as $rowHDESC) :
                                                        $JOBHDESC   = $rowHDESC->JOBDESC;
                                                    endforeach;

                                                $disRow             = 1;
                                                if($OPNH_STAT == 1 || $OPNH_STAT == 4)
                                                {
                                                    $disRow         = 0;
                                                }

                                                $disBtn     = 0;
                                                $ItmCol0    = '';
                                                $ItmCol1    = '';
                                                $ItmCol2    = '';
                                                $ttl        = '';
                                                $divDesc    = '';
                                                $isDisabled = 0;
                                                if($ACC_ID_UM == '')
                                                {
                                                    $disBtn     = 1;
                                                    $ItmCol0    = '<span class="label label-danger" style="font-size:12px; font-style: italic;">';
                                                    $ItmCol1    = '<br><span class="label label-danger" style="font-size:12px; font-style: italic;">';
                                                    $ItmCol2    = '</span>';
                                                    $ttl        = 'Belum disetting kode akun penggunaan';
                                                    $divDesc    = "<i class='fa fa-info'></i>&nbsp;&nbsp;".$alertAcc."";
                                                    $isDisabled = 1;
                                                }

                                                ?>
                                                <tr id="tr_<?php echo $currentRow; ?>">
                                                    <td height="25" style="text-align:left">
                                                        <?php
                                                            if($OPNH_STAT == 1)
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
                                                            <strong><i class='fa fa-cube margin-r-5'></i> <?=$ITM_CODE?> </strong>
                                                            <div>
                                                                <p class='text-muted' style='margin-left: 20px'>
                                                                    <?=$ITM_NAME."<br>"?>
                                                                    <?=$JobName." : ".$JOBHDESC?>
                                                                    <?php echo "$ItmCol1$divDesc$ItmCol2"; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][WO_ID]" id="data<?php echo $currentRow; ?>WO_ID" value="<?php echo $WO_ID; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_NUM]" id="data<?php echo $currentRow; ?>OPNH_NUM" value="<?php echo $OPNH_NUM; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][OPNH_CODE]" id="data<?php echo $currentRow; ?>OPNH_CODE" value="<?php echo $OPNH_CODE; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][PRJCODE]" id="data<?php echo $currentRow; ?>PRJCODE" value="<?php echo $PRJCODE; ?>" class="form-control" >
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEDET]" id="data<?php echo $currentRow; ?>JOBCODEDET" value="<?php echo $JOBCODEDET; ?>" class="form-control" >
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][JOBCODEID]" id="data<?php echo $currentRow; ?>JOBCODEID" value="<?php echo $JOBCODEID; ?>" class="form-control" >
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ACC_ID_UM" name="data[<?php echo $currentRow; ?>][ACC_ID_UM]" value="<?php echo $ACC_ID_UM; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php echo $ITM_UNIT; ?>" class="form-control" style="max-width:300px;" >
                                                        <input type="hidden" id="data<?php echo $currentRow; ?>ITM_GROUP" name="data[<?php echo $currentRow; ?>][ITM_GROUP]" value="<?php echo $ITM_GROUP; ?>" class="form-control" style="max-width:300px;" >
                                                        <!-- Item Name -->
                                                    </td>
                                                    
                                                    <td style="text-align:right" nowrap> <!-- SPK Qty -->
                                                        <span class='label label-success' style='font-size:12px'>
                                                            <?php echo number_format($TOTWOQTY, $decFormat); ?>
                                                        </span>&nbsp;
                                                        <span class='label label-warning' style='font-size:12px'>
                                                            <?php echo number_format($TOTWOAMOUNT, $decFormat); ?>
                                                        </span>
                                                        <input type="hidden" class="form-control" style="min-width:100px; max-width:300px; text-align:right" name="TOTWOQTYx<?php echo $currentRow; ?>" id="TOTWOQTYx<?php echo $currentRow; ?>" value="<?php echo number_format($TOTWOQTY, $decFormat); ?>" disabled >
                                                        <input type="hidden" style="text-align:right" name="TOTWOQTY<?php echo $currentRow; ?>" id="TOTWOQTY<?php echo $currentRow; ?>" value="<?php echo $TOTWOQTY; ?>" >
                                                        <input type="hidden" style="text-align:right" name="TOTWOAMOUNT<?php echo $currentRow; ?>" id="TOTWOAMOUNT<?php echo $currentRow; ?>" value="<?php echo $TOTWOAMOUNT; ?>" >
                                                    </td>

                                                    <td style="text-align:right" nowrap> <!-- Opname Approved Qty-->
                                                        <?php
                                                            $TOTWO_VOLP = $TOTWO_VOL;
                                                            if($TOTWO_VOL == 0)
                                                                $TOTWO_VOLP = 1;
                                                            $OPNPER     = $TOTOPN_VOL / $TOTWO_VOLP * 100;
                                                            
                                                            $PERCOL = "success";
                                                            if($OPNPER > 100)
                                                                $PERCOL = "danger";
                                                        ?>
                                                        <span class='label label-primary' style='font-size:12px'>
                                                            <?php echo number_format($TOTOPN_VOL, $decFormat); ?>
                                                        </span>&nbsp;
                                                        <span class='label label-warning' style='font-size:12px'>
                                                            <?php echo number_format($TOTOPN_AMN, $decFormat); ?>
                                                        </span>&nbsp;
                                                        <span class='label label-<?php echo $PERCOL; ?>' style='font-size:12px'>
                                                            <?php echo number_format($OPNPER, $decFormat); ?> %
                                                        </span>

                                                        <input type="hidden" class="form-control" style="text-align:right" name="TOTOPNQTY<?php echo $currentRow; ?>" id="TOTOPNQTY<?php echo $currentRow; ?>" value="<?php print $TOTOPNQTY; ?>" >
                                                        <input type="hidden" class="form-control" style="text-align:right" name="TOTOPNAMOUNT<?php echo $currentRow; ?>" id="TOTOPNAMOUNT<?php echo $currentRow; ?>" value="<?php print $TOTOPNAMOUNT; ?>" >
                                                        <input type="hidden" class="form-control" style="min-width:110px; max-width:300px; text-align:right" name="TOTOPNQTYx<?php echo $currentRow; ?>" id="TOTOPNQTYx<?php echo $currentRow; ?>" value="<?php print number_format($TOTOPNQTY, $decFormat); ?>" disabled >
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
                                                            <input type="text" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($REMOPNQTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_AMN(this,<?php echo $currentRow; ?>);">
                                                        <?php } else { ?>
                                                            <?php echo number_format($REMOPNQTY, 2); ?>
                                                            <input type="hidden" name="OPND_VOLM<?php echo $currentRow; ?>" id="OPND_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($REMOPNQTY, 2); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_AMN(this,<?php echo $currentRow; ?>);">
                                                        <?php } ?>

                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_VOLM]" id="data<?php echo $currentRow; ?>OPND_VOLM" value="<?php echo $REMOPNQTY; ?>" class="form-control" style="max-width:300px;" >
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMPRICE]" id="data<?php echo $currentRow; ?>OPND_ITMPRICE" value="<?php echo $ITM_PRICE; ?>" class="form-control" style="max-width:300px;" >
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE1]" id="data<?php echo $currentRow; ?>TAXCODE1" value="<?php echo $TAXCODE1; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXCODE2]" id="data<?php echo $currentRow; ?>TAXCODE2" value="<?php echo $TAXCODE2; ?>" class="form-control" style="max-width:300px;">
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE2]" id="data<?php echo $currentRow; ?>TAXPRICE2" value="<?php echo $TAXPRICE2; ?>" class="form-control" style="max-width:300px;">
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
                                                            <input type="text" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_VOLM(this.value,<?php echo $currentRow; ?>);" <?php if($disableInp == 1) { ?> disabled <?php } ?>>
                                                        <?php } else { ?>
                                                            <?php echo number_format($OPND_ITMTOTAL, 2); ?>
                                                            <input type="hidden" name="OPND_ITMTOTAL<?php echo $currentRow; ?>" id="OPND_ITMTOTAL<?php echo $currentRow; ?>" value="<?php echo number_format($OPND_ITMTOTAL, $decFormat); ?>" class="form-control" style="min-width:110px; max-width:300px; text-align:right" onKeyPress="return isIntOnlyNew(event);" onBlur="getOPN_VOLM(this.value,<?php echo $currentRow; ?>);" <?php if($disableInp == 1) { ?> disabled <?php } ?>>
                                                        <?php } ?>
                                                        
                                                        <input type="hidden" name="data[<?php echo $currentRow; ?>][OPND_ITMTOTAL]" id="data<?php echo $currentRow; ?>OPND_ITMTOTAL" value="<?php echo $OPND_ITMTOTAL; ?>" class="form-control" style="max-width:300px;" onKeyPress="return isIntOnlyNew(event);" >
                                                    </td>

                                                    <td style="text-align:center" nowrap>
                                                        <?php echo $ITM_UNIT; ?>
                                                        <!-- Item Unit Type -- ITM_UNIT -->
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
                                    <input type="hidden" name="TAXCODE_PPN" id="TAXCODE_PPN" value="<?php echo $TAXCODE_PPN; ?>">
                                    <input type="hidden" name="TAXCODE_PPH" id="TAXCODE_PPH" value="<?php echo $TAXCODE_PPH; ?>">
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                            <div class="col-sm-9">
                                <?php
                                    $btnShow    = 0;
                                    if($disableAll == 0 && $disBtn == 0)
                                    {
                                        if(($OPNH_STAT == 2 || $OPNH_STAT == 7) && $ISAPPROVE == 1)
                                        {
                                            if($canApprove == 1)
                                                $btnShow    = 1;
                                        }
                                    }
                                ?>
                                <input type="hidden" name="btnShow" id="btnShow" value="<?php echo $btnShow; ?>">
                                <button class="btn btn-primary" id="btnSave" <?php if($btnShow == 0) { ?> style="display: none;" <?php } ?> >
                                <i class="fa fa-save"></i></button>&nbsp;
                                <?php
                                    $backURL    = site_url('c_project/c_o180d0bpnm/inb1a1/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                    echo anchor("$backURL",'<button class="btn btn-danger" id="btnBack" type="button"><i class="fa fa-reply"></i></button>');
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-md-12">
                    <?php
                        $DOC_NUM    = $OPNH_NUM;
                        $sqlCAPPH   = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH   = $this->db->count_all($sqlCAPPH);
                        $sqlAPP = "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
                                    AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
                        $resAPP = $this->db->query($sqlAPP)->result();
                        foreach($resAPP as $rowAPP) :
                            $MAX_STEP       = $rowAPP->MAX_STEP;
                            $APPROVER_1     = $rowAPP->APPROVER_1;
                            $APPROVER_2     = $rowAPP->APPROVER_2;
                            $APPROVER_3     = $rowAPP->APPROVER_3;
                            $APPROVER_4     = $rowAPP->APPROVER_4;
                            $APPROVER_5     = $rowAPP->APPROVER_5;
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
                                                    $s_STEP     = "SELECT DISTINCT APP_STEP FROM tbl_docstepapp_det
                                                                    WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' ORDER BY APP_STEP";
                                                    $r_STEP     = $this->db->query($s_STEP)->result();
                                                    foreach($r_STEP as $rw_STEP) :
                                                        $STEP   = $rw_STEP->APP_STEP;
                                                        $HIDE   = 0;
                                                        ?>
                                                            <tr>
                                                                <td style="width: 10%" nowrap>Tahap <?=$STEP?></td>
                                                                <?php
                                                                    $s_APPH_1   = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP'";
                                                                    $r_APPH_1   = $this->db->count_all($s_APPH_1);
                                                                    if($r_APPH_1 > 0)
                                                                    {
                                                                        $s_00   = "SELECT DISTINCT A.AH_APPROVER, A.AH_APPROVED,
                                                                                        CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
                                                                                    FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                                    WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = $STEP";
                                                                        $r_00   = $this->db->query($s_00)->result();
                                                                        foreach($r_00 as $rw_00) :
                                                                            $APP_EMP_1  = $rw_00->AH_APPROVER;
                                                                            $APP_NME_1  = $rw_00->complName;
                                                                            $APP_DAT_1  = $rw_00->AH_APPROVED;

                                                                            $APPCOL     = "success";
                                                                            $APPIC      = "check";
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
                                                                        $s_00   = "SELECT DISTINCT A.APPROVER_1,
                                                                                        CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS complName
                                                                                    FROM tbl_docstepapp_det A INNER JOIN tbl_employee B ON A.APPROVER_1 = B.Emp_ID
                                                                                    WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE' AND APP_STEP = $STEP";
                                                                        $r_00   = $this->db->query($s_00)->result();
                                                                        foreach($r_00 as $rw_00) :
                                                                            $APP_EMP_1  = $rw_00->APPROVER_1;
                                                                            $APP_NME_1  = $rw_00->complName;
                                                                            $OTHAPP     = 0;
                                                                            $s_APPH_1   = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
                                                                            $r_APPH_1   = $this->db->count_all($s_APPH_1);
                                                                            if($r_APPH_1 > 0)
                                                                            {
                                                                                $HIDE   = 1;
                                                                                $s_01   = "SELECT AH_APPROVED FROM tbl_approve_hist
                                                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APP_EMP_1'";
                                                                                $r_01   = $this->db->query($s_01)->result();
                                                                                foreach($r_01 as $rw_01):
                                                                                    $APPDT  = $rw_01->AH_APPROVED;
                                                                                endforeach;

                                                                                $APPCOL     = "success";
                                                                                $APPIC      = "check";
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
                                                                                $APPCOL     = "danger";
                                                                                $APPIC      = "close";
                                                                                $APPDT      = "-";
                                                                                $s_APPH_O   = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = '$STEP' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
                                                                                $r_APPH_O   = $this->db->count_all($s_APPH_O);
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
                                                                                $APPDT_OTH  = "-";
                                                                                $APPNM_OTH  = "-";
                                                                                $s_01   = "SELECT A.AH_APPROVED, A.AH_APPLEV,
                                                                                                CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME
                                                                                            FROM tbl_approve_hist A INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER NOT IN (SELECT DISTINCT APPROVER_1 FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE')";
                                                                                $r_01   = $this->db->query($s_01)->result();
                                                                                foreach($r_01 as $rw_01):
                                                                                    $APPDT_LEV  = $rw_01->AH_APPLEV;
                                                                                    $APPDT_OTH  = $rw_01->AH_APPROVED;
                                                                                    $APPNM_OTH  = $rw_01->COMPLNAME;

                                                                                    $APPCOL     = "success";
                                                                                    $APPIC      = "check";
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
            <?php
                $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefEmp_ID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
    $(function ()
    {
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
        $('#datepicker').datepicker({
            autoclose: true
        });

        //Date picker
        $('#datepicker1').datepicker({
            autoclose: true
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
            console.log(DOC_DATE);
            
                
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
                    console.log("isLockT ="+isLockT+" isLockJ = "+isLockJ+" LockCateg = "+LockCateg);

                    if(isLockJ == 1)
                    {
                        $('#alrtLockJ').css('display','');
                        document.getElementById('divAlert').style.display   = 'none';
                        $('#OPNH_STAT>option[value="3"]').attr('disabled','disabled');
                        // document.getElementById('btnSave').style.display    = '';
                    }
                    else
                    {
                        $('#alrtLockJ').css('display','none');
                        document.getElementById('divAlert').style.display   = 'none';
                        $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
                        // document.getElementById('btnSave').style.display    = '';
                    }

                    if(isLockT == 1)
                    {
                        if(LockCateg == 1)
                        {
                            $('#alrtLockJ').css('display','');
                            document.getElementById('divAlert').style.display   = 'none';
                            $('#OPNH_STAT').removeAttr('disabled','disabled');
                            $('#OPNH_STAT>option[value="3"]').attr('disabled','disabled');
                            // document.getElementById('btnSave').style.display    = '';
                        }
                        else
                        {
                            $('#alrtLockJ').css('display','none');
                            document.getElementById('divAlert').style.display   = '';
                            $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
                            $('#OPNH_STAT').attr('disabled','disabled');
                            document.getElementById('btnSave').style.display    = 'none';
                        }
                    }
                    else
                    {
                        if(LockCateg == 1)
                        {
                            $('#alrtLockJ').css('display','none');
                            document.getElementById('divAlert').style.display   = 'none';
                            $('#OPNH_STAT').removeAttr('disabled','disabled');
                            $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
                            // document.getElementById('btnSave').style.display    = '';
                        }
                        else
                        {
                            $('#alrtLockJ').css('display','none');
                            document.getElementById('divAlert').style.display   = 'none';
                            $('#OPNH_STAT>option[value="3"]').removeAttr('disabled','disabled');
                            $('#OPNH_STAT').removeAttr('disabled','disabled');
                            // document.getElementById('btnSave').style.display    = '';
                        }
                    }


                    // if(arrStat == 1 && app_stat == 0 && LockCateg == 2)
                    // {
                    //     $('#app_stat').val(arrStat);
                    //     swal(arrAlert, 
                    //     {
                    //         icon: "success",
                    //     })
                    //     .then(function()
                    //     {
                    //         swal.close();
                    //         document.getElementById('btnSave').style.display    = 'none';
                    //         document.getElementById('divAlert').style.display   = '';
                    // 		$('#INV_STAT').attr('disabled','disabled');
                    // 		console.log('tes');
                    //     })
                    // }
                    // else if(arrStat == 1 && app_stat == 1 && LockCateg == 2)
                    // {
                    //     $('#app_stat').val(arrStat);
                    //     document.getElementById('btnSave').style.display    = 'none';
                    //     document.getElementById('divAlert').style.display   = '';
                    // 	$('#INV_STAT').attr('disabled','disabled');
                    // 	console.log('tes1');
                    // }
                    // else
                    // {
                    //     $('#app_stat').val(arrStat);
                    //     document.getElementById('btnSave').style.display    = '';
                    //     document.getElementById('divAlert').style.display   = 'none';
                    // 	$('#INV_STAT').removeAttr('disabled','disabled');
                    // 	console.log('tes1');
                    // }
                }
            });
        }
    // END : LOCK PROCEDURE

    <?php
    if($task == 'add')
    {
        ?>
        $(document).ready(function()
        {
            setInterval(function(){getNewCode()}, 1000);
        });

        function getNewCode()
        {
                var task                        = "<?=$task?>";
                var JournalType         = "";
                var dataTarget          = "OPNH_CODE";
                var myProj_code         = "PRJCODE";
                var PRJCODE                 = "<?=$PRJCODE?>";
                var Pattern_Code        = "<?=$Pattern_Code?>";
                var PattTable           = "tbl_opn_header";
                var Pattern_Length  = "<?=$Pattern_Length?>";
                var TRXDATE                 = "OPNH_DATE";
                var DATE                        = $('#datepicker').val();
                var isManual                = $('#isManual').prop('checked');

                if(isManual == true){
                    $('#'+dataTarget).focus();
                }else{
                    $.ajax({
                        url: "<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>",
                        type: "POST",
                        data: {task:task, JournalType:JournalType, myProj_code:myProj_code, PRJCODE:PRJCODE, Pattern_Code:Pattern_Code, PattTable:PattTable, Pattern_Length:Pattern_Length, TRXDATE:TRXDATE, DATE:DATE},
                        success: function(data){
                            $('#'+dataTarget).val(data);
                        }
                    });
                }

        }

        function getNewCode1()
        {
            var PRJCODE     = '<?php echo $dataColl; ?>';
            var isManual    = document.getElementById('isManual').checked;

            if(window.XMLHttpRequest)
            {
                //code for IE7+,Firefox,Chrome,Opera,Safari
                xmlhttpTask=new XMLHttpRequest();
            }
            else
            {
                xmlhttpTask=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttpTask.onreadystatechange=function()
            {
                if(xmlhttpTask.readyState==4&&xmlhttpTask.status==200)
                {
                    if(xmlhttpTask.responseText != '')
                    {
                        if(isManual == false)
                            document.getElementById('<?php echo $dataTarget; ?>').value  = xmlhttpTask.responseText;
                    }
                    else
                    {
                        if(isManual == false)
                            document.getElementById('<?php echo $dataTarget; ?>').value  = '';
                    }
                }
            }
            xmlhttpTask.open("GET","<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>"+PRJCODE,true);
            xmlhttpTask.send();
        }
        <?php
    }
    else
    {
            $OPNH_STAT      = $default['OPNH_STAT'];
            $OPNH_CODE      = $default['OPNH_CODE'];
            if($OPNH_STAT == 1)
            {
                ?>
                $(document).ready(function()
                {
                    setInterval(function(){getNewCode()}, 1000);
                });

                function getNewCode()
                {
                        var task                        = "<?=$task?>";
                        var dataTarget          = "OPNH_CODE";
                        var Manual_Code         = "<?=$OPNH_CODE?>";
                        var isManual                = $('#isManual').prop('checked');

                        if(isManual == true){
                            $('#'+dataTarget).focus();
                        }else{
                            $.ajax({
                                url: "<?php echo base_url().'index.php/__l1y/GetCodeDoc/';?>",
                                type: "POST",
                                data: {task:task, Manual_Code:Manual_Code},
                                success: function(data){
                                    $('#'+dataTarget).val(data);
                                }
                            });
                        }

                }
                <?php
            }
    }
    ?>

    function add_header(strItem)
    {
        arrItem     = strItem.split('|');
        ilvl        = arrItem[1];

        WO_NUM      = arrItem[0];

        document.getElementById("WO_NUMX").value = WO_NUM;
        document.frmsrch.submitSrch.click();
    }

    function getOPN_AMN(thisVal1, row)
    {
        var decFormat       = document.getElementById('decFormat').value;

        OPND_VOLM           = parseFloat(Math.abs(thisVal1.value));
        itemConvertion      = document.getElementById('itemConvertion'+row).value;
        ITM_PRICE           = parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);// Item Price
        TOTWOQTY            = document.getElementById('TOTWOQTY'+row).value;
        TOTWOAMOUNT         = document.getElementById('TOTWOAMOUNT'+row).value;                     // Total SPK Amount
        TOTOPNQTY           = parseFloat(document.getElementById('TOTOPNQTY'+row).value);           // Total Opname Approved Qty
        TOTOPNAMOUNT        = parseFloat(document.getElementById('TOTOPNAMOUNT'+row).value);        // Total Opname Approved Amount

        // OPNAME NOW
            OPND_VOLM       = eval(thisVal1).value.split(",").join("");                             // Opname Now
            OPND_ITMTOTAL   = parseFloat(OPND_VOLM) * parseFloat(ITM_PRICE);                        // Total Opname Now

            document.getElementById('data'+row+'OPND_ITMTOTAL').value   = OPND_ITMTOTAL;
            document.getElementById('OPND_ITMTOTAL'+row).value          = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)),decFormat));
            
        // TAX
            TAXCODE1            = document.getElementById('data'+row+'TAXCODE1').value;                 // Tax Code
            TAXPRICE1           = parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);
            if(TAXCODE1 != '')
            {
                TAXPRICE1   = parseFloat(OPND_ITMTOTAL) * 0.1;                      // Total Opname Tax Now
            }

        REM_OPN_QTY         = parseFloat(TOTWOQTY) - parseFloat(TOTOPNQTY);
        REM_OPN_AMOUNT      = parseFloat(TOTWOAMOUNT) - parseFloat(TOTOPNAMOUNT);

        if(OPND_VOLM > REM_OPN_QTY)
        {
            REM_OPN_QTYV    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)),decFormat));
            swal('Opname Qty is Greater than SPK Qty. Maximum Qty is '+REM_OPN_QTYV);

            document.getElementById('data'+row+'OPND_VOLM').value       = REM_OPN_QTY;
            document.getElementById('OPND_VOLM'+row).value              = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)), 2));

            REM_OPN_AMOUNT  = parseFloat(REM_OPN_QTY) * parseFloat(ITM_PRICE);
            document.getElementById('data'+row+'OPND_ITMTOTAL').value   = REM_OPN_AMOUNT;
            document.getElementById('data'+row+'TAXPRICE1').value       = TAXPRICE1;
            //return false;
        }

        document.getElementById('data'+row+'OPND_VOLM').value       = OPND_VOLM;
        document.getElementById('data'+row+'OPND_ITMTOTAL').value   = OPND_ITMTOTAL;
        document.getElementById('data'+row+'TAXPRICE1').value       = TAXPRICE1;

        document.getElementById('OPND_VOLM'+row).value              = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

        var totOPN1 = 0;
        var totRow = document.getElementById('totalrow').value;
        for(i=1;i<=totRow;i++)
        {
            var OPND_ITMTOTAL   = parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
            var OPND_TOTTAX     = parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
            totOPN1             = parseFloat(totOPN1 + OPND_ITMTOTAL);
        }

        OPNH_DPVAL  = parseFloat(document.getElementById('OPNH_DPVAL').value);
        totOPN      = parseFloat(totOPN1) - parseFloat(OPNH_DPVAL);

        // HASIL MEETING 27 DES 18 DI MS, DP HANYA INFORMASI KECUALI POTONGAN
        OPNH_POTX   = document.getElementById('OPNH_POT').value;
        totOPN      = parseFloat(totOPN1) - parseFloat(OPNH_POTX);
        
        document.getElementById('OPNH_AMOUNT').value    = totOPN;
        document.getElementById('OPNH_AMOUNTX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOPN)), decFormat));
        document.getElementById('OPNH_AMOUNTPPN').value = OPND_TOTTAX;
        document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAX)), decFormat));

        /*document.getElementById('OPNH_POT').value         = 0;
        document.getElementById('OPNH_RETPERC').value   = 0;
        document.getElementById('OPNH_RETAMN').value    = 0;
        document.getElementById('OPNH_DPPER').value     = 0;
        document.getElementById('OPNH_DPVAL').value     = 0;
        document.getElementById('OPNH_AMOUNTPPN').value = 0;
        document.getElementById('OPNH_POTX').value      = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_RETPERCX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_RETAMNX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_DPPERX').value    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_DPVALX').value    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));

        var OPNH_RETPERC    = parseFloat(document.getElementById('OPNH_RETPERC').value);
        if(OPNH_RETPERC > 0)
        {
            TOTRETAMN       = parseFloat(OPNH_RETPERC * totOPN) / 100;
        }
        document.getElementById('OPNH_RETAMN').value    = TOTRETAMN;
        document.getElementById('OPNH_RETAMNX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTRETAMN)), decFormat));*/

        // AKUMULASI TOTAL AWAL
        PROG_BEF    = parseFloat(document.getElementById('PROG_BEF').value);

        if(isNaN(PROG_BEF) == true)
            PROG_BEF= 0;

        AKUM_PROG   = parseFloat(totOPN) + parseFloat(PROG_BEF);
        document.getElementById('AKUM_PROG').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

        countGTotal();
    }

    function getOPN_VOLM(thisVal1, row)
    {
        var decFormat       = document.getElementById('decFormat').value;

        OPND_AMN            = parseFloat(eval(thisVal1).value.split(",").join(""));
        itemConvertion      = document.getElementById('itemConvertion'+row).value;
        ITM_PRICE           = parseFloat(document.getElementById('data'+row+'OPND_ITMPRICE').value);// Item Price
        TOTWOQTY            = document.getElementById('TOTWOQTY'+row).value;
        TOTWOAMOUNT         = document.getElementById('TOTWOAMOUNT'+row).value;                     // Total SPK Amount
        TOTOPNQTY           = parseFloat(document.getElementById('TOTOPNQTY'+row).value);           // Total Opname Approved Qty
        TOTOPNAMOUNT        = parseFloat(document.getElementById('TOTOPNAMOUNT'+row).value);        // Total Opname Approved Amount

        // OPNAME NOW
            //OPND_VOLM     = eval(thisVal1).value.split(",").join("");                             // Opname Now
            OPND_VOLM       = parseFloat(OPND_AMN) / parseFloat(ITM_PRICE);                         // Opname Now
            OPND_ITMTOTAL   = parseFloat(OPND_AMN);                                                 // Total Opname Now

            document.getElementById('data'+row+'OPND_ITMTOTAL').value   = OPND_ITMTOTAL;
            document.getElementById('OPND_ITMTOTAL'+row).value          = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_ITMTOTAL)),decFormat));
            
        // TAX
            TAXCODE1            = document.getElementById('data'+row+'TAXCODE1').value;                 // Tax Code
            TAXPRICE1           = parseFloat(document.getElementById('data'+row+'TAXPRICE1').value);
            if(TAXCODE1 != '')
            {
                TAXPRICE1   = parseFloat(OPND_ITMTOTAL) * 0.1;                      // Total Opname Tax Now
            }

        REM_OPN_QTY         = parseFloat(TOTWOQTY) - parseFloat(TOTOPNQTY);
        REM_OPN_AMOUNT      = parseFloat(TOTWOAMOUNT) - parseFloat(TOTOPNAMOUNT);

        if(OPND_VOLM > REM_OPN_QTY)
        {
            REM_OPN_QTYV    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)),decFormat));
            swal('Opname Qty is Greater than SPK Qty. Maximum Qty is '+REM_OPN_QTYV);

            document.getElementById('data'+row+'OPND_VOLM').value       = REM_OPN_QTY;
            document.getElementById('OPND_VOLM'+row).value              = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(REM_OPN_QTY)), 2));

            REM_OPN_AMOUNT  = parseFloat(REM_OPN_QTY) * parseFloat(ITM_PRICE);
            document.getElementById('data'+row+'OPND_ITMTOTAL').value   = REM_OPN_AMOUNT;
            document.getElementById('data'+row+'TAXPRICE1').value       = TAXPRICE1;
            //return false;
        }

        document.getElementById('data'+row+'OPND_VOLM').value       = OPND_VOLM;
        document.getElementById('data'+row+'OPND_ITMTOTAL').value   = OPND_ITMTOTAL;
        document.getElementById('data'+row+'TAXPRICE1').value       = TAXPRICE1;

        document.getElementById('OPND_VOLM'+row).value              = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_VOLM)), 2));

        var totOPN1 = 0;
        var totRow = document.getElementById('totalrow').value;
        for(i=1;i<=totRow;i++)
        {
            var OPND_ITMTOTAL   = parseFloat(document.getElementById('data'+i+'OPND_ITMTOTAL').value);
            var OPND_TOTTAX     = parseFloat(document.getElementById('data'+i+'TAXPRICE1').value);
            totOPN1             = parseFloat(totOPN1 + OPND_ITMTOTAL);
        }

        OPNH_DPVAL  = parseFloat(document.getElementById('OPNH_DPVAL').value);
        totOPN      = parseFloat(totOPN1) - parseFloat(OPNH_DPVAL);

        // HASIL MEETING 27 DES 18 DI MS, DP HANYA INFORMASI KECUALI POTONGAN
        OPNH_POTX   = document.getElementById('OPNH_POT').value;
        totOPN      = parseFloat(totOPN1) - parseFloat(OPNH_POTX);
        
        document.getElementById('OPNH_AMOUNT').value    = totOPN;
        document.getElementById('OPNH_AMOUNTX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(totOPN)), decFormat));
        document.getElementById('OPNH_AMOUNTPPN').value = OPND_TOTTAX;
        document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OPND_TOTTAX)), decFormat));

        /*document.getElementById('OPNH_POT').value         = 0;
        document.getElementById('OPNH_RETPERC').value   = 0;
        document.getElementById('OPNH_RETAMN').value    = 0;
        document.getElementById('OPNH_DPPER').value     = 0;
        document.getElementById('OPNH_DPVAL').value     = 0;
        document.getElementById('OPNH_AMOUNTPPN').value = 0;
        document.getElementById('OPNH_POTX').value      = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_RETPERCX').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_RETAMNX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_DPPERX').value    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_DPVALX').value    = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));
        document.getElementById('OPNH_AMOUNTPPNX').value= doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(0)), decFormat));

        var OPNH_RETPERC    = parseFloat(document.getElementById('OPNH_RETPERC').value);
        if(OPNH_RETPERC > 0)
        {
            TOTRETAMN       = parseFloat(OPNH_RETPERC * totOPN) / 100;
        }
        document.getElementById('OPNH_RETAMN').value    = TOTRETAMN;
        document.getElementById('OPNH_RETAMNX').value   = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(TOTRETAMN)), decFormat));*/

        // AKUMULASI TOTAL AWAL
        PROG_BEF    = parseFloat(document.getElementById('PROG_BEF').value);

        if(isNaN(PROG_BEF) == true)
            PROG_BEF= 0;

        AKUM_PROG   = parseFloat(totOPN) + parseFloat(PROG_BEF);
        document.getElementById('AKUM_PROG').value  = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(AKUM_PROG)), 2));

        countGTotal();
    }

    function validateInData()
    {
        var totrow  = document.getElementById('totalrow').value;

        OPNH_STAT   = document.getElementById("OPNH_STAT").value;
        OPNH_NOTE2  = document.getElementById("OPNH_NOTE2").value;

        if(OPNH_STAT == 9 || OPNH_STAT == 4 || OPNH_STAT == 5)
        {
            OPNH_MEMO       = document.getElementById('OPNH_MEMO').value;
            // if(OPNH_MEMO == '')
            // {
            //     swal('<?php // echo $alert1; ?>',
            //     {
            //         icon:"warning",
            //     })
            //     .then(function()
            //     {
            //         swal.close();
            //         document.getElementById('OPNH_NOTE2').focus();
            //     });
            //     return false;
            // }
        }
        else if(OPNH_STAT == 0)
        {
            swal('<?php echo $alert5; ?>',
            {
              icon:"warning",
            });
            document.getElementById('OPNH_STAT').focus();
            return false;
        }
        // document.getElementById('btnSave').style.display        = 'none';
        // document.getElementById('btnBack').style.display        = 'none';

        let frm = document.getElementById('frm');
		frm.addEventListener('submit', (e) => {
			console.log(e)
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

    function viewFile(fileName)
    {
        const url       = "<?php echo base_url() . 'assets/AdminLTE-2.0.5/doc_center/webpdf/web/viewer.php?FileUpName='; ?>";
        const urlOpen   = "<?php echo base_url(); ?>";
        let PRJCODE     = "<?php echo $PRJCODE; ?>";
        let path        = "OPN_Document/"+PRJCODE+"/"+fileName+"";
        let FileUpName  = ''+path+'&base_url='+urlOpen;
        title = 'Select Item';
        w = 850;
        h = 550;
        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
        let left = (screen.width/2)-(w/2);
        let top = (screen.height/2)-(h/2);
        return window.open(url+FileUpName, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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