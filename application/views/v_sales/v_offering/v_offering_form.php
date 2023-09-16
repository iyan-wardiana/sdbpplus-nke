<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 28 November 2018
    * File Name	= v_offering_form.php
    * Location		= -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

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
$decFormat      = 2;

$FlagUSER       = $this->session->userdata['FlagUSER'];
$DefEmp_ID      = $this->session->userdata['Emp_ID'];
$DEPCODE        = $this->session->userdata['DEPCODE'];
$DEPCODEX       = $this->session->userdata['DEPCODE'];

$sql = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
$result = $this->db->query($sql)->result();
foreach($result as $row) :
    $PRJNAME = $row ->PRJNAME;
endforeach;

$tblName    = "tbl_offering_h";

$currentRow = 0;
if($task == 'add')
{
    $DefEmp_ID      = $this->session->userdata['Emp_ID'];
    
    $OFF_NUM        = '';
    $SO_NUM         = '';
    $OFF_CODE       = '';
    $OFF_DATE       = '';
    $CUST_CODE      = '';
    $CUST_DESC      = '';
    $CUST_ADD1      = '';
    $OFF_TOTCOST    = 0;
    $OFF_STAT       = 1;
    $OFF_SOSTAT     = 0;                    
    $OFF_NOTES      = '';
    $OFF_MEMO       = '';
    
    foreach($viewDocPattern as $row) :
        $Pattern_Code           = $row->Pattern_Code;
        $Pattern_Position       = $row->Pattern_Position;
        $Pattern_YearAktive     = $row->Pattern_YearAktive;
        $Pattern_MonthAktive    = $row->Pattern_MonthAktive;
        $Pattern_DateAktive     = $row->Pattern_DateAktive;
        $Pattern_Length         = $row->Pattern_Length;
        $useYear                = $row->useYear;
        $useMonth               = $row->useMonth;
        $useDate                = $row->useDate;
    endforeach;
    $LangID     = $this->session->userdata['LangID'];
    if(isset($Pattern_Position))
    {
        $isSetDocNo = 1;
        if($Pattern_Position == 'Especially')
        {
            $Pattern_YearAktive     = date('Y');
            $Pattern_MonthAktive    = date('m');
            $Pattern_DateAktive     = date('d');
        }
        $year                       = (int)$Pattern_YearAktive;
        $month                      = (int)$Pattern_MonthAktive;
        $date                       = (int)$Pattern_DateAktive;
    }
    else
    {
        $isSetDocNo = 0;
        $Pattern_Code           = "XXX";
        $Pattern_Length         = "5";
        $useYear                = 1;
        $useMonth               = 1;
        $useDate                = 1;
        
        $Pattern_YearAktive     = date('Y');
        $Pattern_MonthAktive    = date('m');
        $Pattern_DateAktive     = date('d');
        $year                   = (int)$Pattern_YearAktive;
        $month                  = (int)$Pattern_MonthAktive;
        $date                   = (int)$Pattern_DateAktive;
        
        if($LangID == 'IND')
        {
            $docalert1  = 'Peringatan';
            $docalert2  = 'Anda belum men-setting penomoran untuk dokumen ini. Sehingga, akan diberikan penomoran secara default dari sistem. Silahkan atur dari menu pengaturan. Silahkan atur  penomoran dokumen pada menu pengaturan.';
        }
        else
        {
            $docalert1  = 'Warning';
            $docalert2  = 'You have not set the numbering for this document. So, numbering will be given by default from the system. Please set document numbering in the Setting menu.';
        }
    }
    
    $yearC  = (int)$Pattern_YearAktive;
    $year   = substr($Pattern_YearAktive,2,2);
    $month  = (int)$Pattern_MonthAktive;
    $date   = (int)$Pattern_DateAktive;
    
    $sql    = "$tblName WHERE Patt_Year = $yearC AND PRJCODE = '$PRJCODE'";
    $myMax  = $this->db->count_all($sql);
    $myMax  = $myMax + 1;
    
    $thisMonth = $month;
    
    $lenMonth = strlen($thisMonth);
    if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
    $pattMonth = $nolMonth.$thisMonth;
    
    $thisDate = $date;
    $lenDate = strlen($thisDate);
    if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
    $pattDate = $nolDate.$thisDate;
    
    // group year, month and date
    if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
        $groupPattern = "$year$pattMonth$pattDate";
    elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
        $groupPattern = "$year$pattMonth";
    elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
        $groupPattern = "$year$pattDate";
    elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
        $groupPattern = "$pattMonth$pattDate";
    elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
        $groupPattern = "$year";
    elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
        $groupPattern = "$pattMonth";
    elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
        $groupPattern = "$pattDate";
    elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
        $groupPattern = "";
    
        
    $lastPattNumb   = $myMax;
    $lastPattNumb1  = $myMax;
    $len = strlen($lastPattNumb);
    
    if($Pattern_Length==2)
    {
        if($len==1) $nol="0";
    }
    elseif($Pattern_Length==3)
    {if($len==1) $nol="00";else if($len==2) $nol="0";
    }
    elseif($Pattern_Length==4)
    {if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
    }
    elseif($Pattern_Length==5)
    {if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
    }
    elseif($Pattern_Length==6)
    {if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
    }
    elseif($Pattern_Length==7)
    {if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
    }
    $lastPattNumb = $nol.$lastPattNumb;
    
    $year           = date('y');
    $month          = date('m');
    $days           = date('d');
    $DocNumber1     = "$Pattern_Code$PRJCODE$year$month$days-$lastPattNumb";
    //$DocNumber    = "$DocNumber1"."-D";
    $DocNumber      = "$DocNumber1";    
    //$DOCCODE      = substr($lastPattNumb, -4);
    $DOCCODE        = $lastPattNumb;    
    $DOCYEAR        = date('y');
    $DOCMONTH       = date('m');
    //$DOC_CODE     = "$Pattern_Code.$DOCCODE.$DOCYEAR.$DOCMONTH-D"; // MANUAL CODE
    $DOC_NUM        = $DocNumber;
    $DOC_CODE       = "$Pattern_Code.$DOCCODE.$DOCYEAR.$DOCMONTH"; // MANUAL CODE
    
    $OFF_NUM        = $DOC_NUM;
    $OFF_CODE       = $DOC_CODE;
    $OFF_DATE       = date('m/d/Y');    
    $Patt_Year      = date('Y');
    $Patt_Month     = date('m');
    $Patt_Date      = date('d');
    
    $dataColl       = "$PRJCODE~$Pattern_Code~tbl_offering_h~$Pattern_Length";
    $dataTarget     = "OFF_CODE";
    $CUST_ADDRESS   = '';
    $CCAL_NUM       = '';
    $CCAL_CODE      = '';
    $BOM_NUM        = '';
    $BOM_CODE       = '';
    $OFF_NOTES1     = '';

    $COLL_CCALNUM   = '';
    $COLL_CALCODE   = '';
    $COLL_BOMNUM    = '';
    $COLL_BOMCODE   = '';
}
else
{
    $isSetDocNo     = 1;
    $OFF_NUM        = $default['OFF_NUM'];
    $DocNumber      = $default['OFF_NUM'];
    $OFF_CODE       = $default['OFF_CODE'];
    $OFF_DATE       = $default['OFF_DATE'];
    $OFF_DATE       = date('m/d/Y', strtotime($OFF_DATE));
    $PRJCODE        = $default['PRJCODE'];
    $PRJCODE        = $default['PRJCODE'];
    $DEPCODE        = $default['DEPCODE'];
    if($DEPCODE == '')
        $DEPCODE    = $DEPCODEX;
    $CUST_CODE      = $default['CUST_CODE'];
    $CUST_ADDRESS   = $default['CUST_ADDRESS'];
    $CCAL_NUM       = $default['CCAL_NUM'];
    $CCAL_CODE      = $default['CCAL_CODE'];
    $BOM_NUM        = $default['BOM_NUM'];
    $BOM_CODE       = $default['BOM_CODE'];
    $SO_NUM         = $default['SO_NUM'];
    $OFF_TOTCOST    = $default['OFF_TOTCOST'];
    $OFF_TOTDISC    = $default['OFF_TOTDISC'];
    $OFF_TOTPPN     = $default['OFF_TOTPPN'];
    $OFF_NOTES      = $default['OFF_NOTES'];
    $OFF_NOTES1     = $default['OFF_NOTES1'];
    $OFF_MEMO       = $default['OFF_MEMO'];
    $PRJNAME        = $default['PRJNAME'];
    $OFF_STAT       = $default['OFF_STAT'];
    $OFF_SOSTAT     = $default['OFF_SOSTAT'];
    $Patt_Year      = $default['Patt_Year'];
    $Patt_Month     = $default['Patt_Month'];
    $Patt_Date      = $default['Patt_Date'];
    $Patt_Number    = $default['Patt_Number'];
    $lastPattNumb1  = $default['Patt_Number'];

    $expCCAL        = explode(';', $CCAL_NUM);

    $COLL_CCALNUM   = '';
    $COLL_CALCODE   = '';
    $COLL_BOMNUM    = '';
    $COLL_BOMCODE   = '';
    foreach($expCCAL as $i =>$key)
    {
        $CCALNUM    = $key;
        $sqlCCAL    = "SELECT CCAL_CODE, BOM_NUM, BOM_CODE FROM tbl_ccal_header WHERE CCAL_NUM = '$CCALNUM'";
        $resCCAL    = $this->db->query($sqlCCAL)->result();
        foreach($resCCAL as $rowCCAL) :
            $CCAL_CODE  = $rowCCAL->CCAL_CODE;
            $BOM_NUM    = $rowCCAL->BOM_NUM;
            $BOM_CODE   = $rowCCAL->BOM_CODE;
        endforeach;
        if($i == 0)
        {
            $COLL_CCALNUM   = $CCALNUM;
            $COLL_CALCODE   = $CCAL_CODE;
            $COLL_BOMNUM    = $BOM_NUM;
            $COLL_BOMCODE   = $BOM_CODE;
        }
        else
        {
            $COLL_CCALNUM   = $COLL_CCALNUM."','".$CCALNUM;
            $COLL_CALCODE   = $COLL_CALCODE.";".$CCAL_CODE;
            $COLL_BOMNUM    = $COLL_BOMNUM.";".$BOM_NUM;
            $COLL_BOMCODE   = $COLL_BOMCODE.";".$BOM_CODE;
        }
    }
}

$CCAL_NUMX      = '';
if(isset($_POST['CCAL_NUMX']))
{
    $COLL_CCALNUM   = '';
    $COLL_CALCODE   = '';
    $COLL_BOMNUM    = '';
    $COLL_BOMCODE   = '';
    $CCAL_NUMX      = $_POST['CCAL_NUMX'];
    $CUST_CODE      = $_POST['CUST_CODEX'];
    /*$CCAL_CODE    = $_POST['CCAL_CODEX'];
    $BOM_NUM        = $_POST['BOM_NUMX'];
    $BOM_CODE       = $_POST['BOM_CODEX'];*/
    $expCCAL        = explode('~', $CCAL_NUMX);

    foreach($expCCAL as $i =>$key)
    {
        $CCALNUM    = $key;
        $sqlCCAL    = "SELECT CCAL_CODE, BOM_NUM, BOM_CODE FROM tbl_ccal_header WHERE CCAL_NUM = '$CCALNUM'";
        $resCCAL    = $this->db->query($sqlCCAL)->result();
        foreach($resCCAL as $rowCCAL) :
            $CCAL_CODE  = $rowCCAL->CCAL_CODE;
            $BOM_NUM    = $rowCCAL->BOM_NUM;
            $BOM_CODE   = $rowCCAL->BOM_CODE;
        endforeach;
        if($i == 0)
        {
            $COLL_CCALNUM   = $CCALNUM;
            $COLL_CALCODE   = $CCAL_CODE;
            $COLL_BOMNUM    = $BOM_NUM;
            $COLL_BOMCODE   = $BOM_CODE;
        }
        else
        {
            $COLL_CCALNUM   = $COLL_CCALNUM."','".$CCALNUM;
            $COLL_CALCODE   = $COLL_CALCODE.";".$CCAL_CODE;
            $COLL_BOMNUM    = $COLL_BOMNUM.";".$BOM_NUM;
            $COLL_BOMCODE   = $COLL_BOMCODE.";".$BOM_CODE;
        }
    }
    $CUST_ADDRESS   = '';
    
    $sqlCUST        = "SELECT CUST_ADD1 FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE' LIMIT 1";
    $resultCUST     = $this->db->query($sqlCUST)->result();
    foreach($resultCUST as $rowCUST) :
        $CUST_ADDRESS   = $rowCUST->CUST_ADD1;
    endforeach;
}

$secGenCode = base_url().'index.php/c_sales/c_0ff3r1n9/genCode/'; // Generate Code
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $appName; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <?php
          $vers     = $this->session->userdata['vers'];

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
            if($TranslCode == 'PONumber')$PONumber = $LangTransl;
            if($TranslCode == 'ManualCode')$ManualCode = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'ContractNo')$ContractNo = $LangTransl;
            if($TranslCode == 'Project')$Project = $LangTransl;
            if($TranslCode == 'CustName')$CustName = $LangTransl;
            if($TranslCode == 'Currency')$Currency = $LangTransl;
            if($TranslCode == 'PaymentType')$PaymentType = $LangTransl;
            if($TranslCode == 'PaymentTerm')$PaymentTerm = $LangTransl;
            if($TranslCode == 'ProdPlan')$ProdPlan = $LangTransl;
            if($TranslCode == 'Notes')$Notes = $LangTransl;
            if($TranslCode == 'AdditAddress')$AdditAddress = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'RefNumber')$RefNumber = $LangTransl;
            if($TranslCode == 'Search')$Search = $LangTransl;
            if($TranslCode == 'BOMCode')$BOMCode = $LangTransl;
            if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
            if($TranslCode == 'ItemName')$ItemName = $LangTransl;
            if($TranslCode == 'ColorName')$ColorName = $LangTransl;
            if($TranslCode == 'Remain')$Remain = $LangTransl;
            if($TranslCode == 'Quantity')$Quantity = $LangTransl;
            if($TranslCode == 'Unit')$Unit = $LangTransl;
            if($TranslCode == 'Price')$Price = $LangTransl;
            if($TranslCode == 'Discount')$Discount = $LangTransl;
            if($TranslCode == 'UnitPrice')$UnitPrice = $LangTransl;
            if($TranslCode == 'Tax')$Tax = $LangTransl;
            if($TranslCode == 'Purchase')$Purchase = $LangTransl;
            if($TranslCode == 'Tax')$Tax = $LangTransl;
            if($TranslCode == 'BudgetQty')$BudgetQty = $LangTransl;
            if($TranslCode == 'ColorCode')$ColorCode = $LangTransl;
            if($TranslCode == 'Save')$Save = $LangTransl;
            if($TranslCode == 'Update')$Update = $LangTransl;
            if($TranslCode == 'Back')$Back = $LangTransl;
            if($TranslCode == 'ReceiptLoc')$ReceiptLoc = $LangTransl;
            if($TranslCode == 'SentRoles')$SentRoles = $LangTransl;
            if($TranslCode == 'ReferenceNumber')$ReferenceNumber = $LangTransl;
            if($TranslCode == 'rejected')$rejected = $LangTransl;
            if($TranslCode == 'ReceiptDate')$ReceiptDate = $LangTransl;
            if($TranslCode == 'ApproverNotes')$ApproverNotes = $LangTransl;
            if($TranslCode == 'Awaiting')$Awaiting = $LangTransl;
            if($TranslCode == 'sourceDoc')$sourceDoc = $LangTransl;
            if($TranslCode == 'revision')$revision = $LangTransl;

            if($TranslCode == 'Approve')$Approve = $LangTransl;
            if($TranslCode == 'Approver')$Approver = $LangTransl;
            if($TranslCode == 'Approved')$Approved = $LangTransl;
            if($TranslCode == 'Approval')$Approval = $LangTransl;
            if($TranslCode == 'NotYetApproved')$NotYetApproved = $LangTransl;
            if($TranslCode == 'InOthSett')$InOthSett = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
            $alert1     = 'Jumlah pemesanan tidak boleh kosong.';
            $alert2     = 'Silahkan pilih nama pelanggan.';
            $alert3     = 'Silahkan pilih kode warna.';
            $alert4     = 'Volume tidak boleh nol.';
            $alert5     = 'Harga tidak boleh nol.';
            $isManual   = "Centang untuk kode manual.";
            $qtyDetail  = 'Detail item tidak boleh kosong.';
            $volmAlert  = 'Qty order tidak boleh nol.';
        }
        else
        {
            $alert1     = 'Qty order can not be empty.';
            $alert2     = 'Please select a customer name.';
            $alert3     = 'Please select a color code.';
            $alert4     = 'Volume can not be zero.';
            $alert5     = 'Price can not be zero.';
            $isManual   = "Check to manual code.";
            $qtyDetail  = 'Item Detail can not be empty.';
            $volmAlert  = 'Order qty can not be zero.';
        }
        
        // START : APPROVE PROCEDURE
            if($APPLEV == 'HO')
                $PRJCODE_LEV    = $this->data['PRJCODE_HO'];
            else
                $PRJCODE_LEV    = $this->data['PRJCODE'];
            
            // DocNumber - OFF_TOTCOST
            $IS_LAST    = 0;
            $APP_LEVEL  = 0;
            $APPROVER_1 = '';
            $APPROVER_2 = '';
            $APPROVER_3 = '';
            $APPROVER_4 = '';
            $APPROVER_5 = '';   
            $disableAll = 1;
            $DOCAPP_TYPE= 1;
            $sqlCAPP    = "tbl_docstepapp WHERE MENU_CODE = '$MenuApp' AND PRJCODE = '$PRJCODE_LEV'";
            $resCAPP    = $this->db->count_all($sqlCAPP);
            if($resCAPP > 0)
            {
                $sqlAPP = "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
                            AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
                $resAPP = $this->db->query($sqlAPP)->result();
                foreach($resAPP as $rowAPP) :
                    $MAX_STEP       = $rowAPP->MAX_STEP;
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
                $sqlAPPT    = "SELECT DOCAPP_TYPE FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
                                AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
                $resAPPT    = $this->db->query($sqlAPP)->result();
                foreach($resAPPT as $rowAPPT) :
                    $DOCAPP_TYPE    = $rowAPPT->DOCAPP_TYPE;
                endforeach;
            }
            
            $sqlSTEPAPP = "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp' AND APPROVER_1 = '$DefEmp_ID'
                            AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
            $resSTEPAPP = $this->db->count_all($sqlSTEPAPP);
            
            if($resSTEPAPP > 0)
            {
                $canApprove = 1;
                $APPLIMIT_1 = 0;
                
                $sqlAPP = "SELECT APPLIMIT_1, APP_STEP, MAX_STEP FROM tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
                            AND APPROVER_1 = '$DefEmp_ID' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID' AND proj_Code = '$PRJCODE_LEV')";
                $resAPP = $this->db->query($sqlAPP)->result();
                foreach($resAPP as $rowAPP) :
                    $APPLIMIT_1 = $rowAPP->APPLIMIT_1;
                    $APP_STEP   = $rowAPP->APP_STEP;
                    $MAX_STEP   = $rowAPP->MAX_STEP;
                endforeach;
                $sqlC_App   = "tbl_approve_hist WHERE AH_CODE = '$DocNumber'";
                $resC_App   = $this->db->count_all($sqlC_App);
                
                $BefStepApp = $APP_STEP - 1;
                if($resC_App == $BefStepApp)
                {
                    $canApprove = 1;
                }
                elseif($resC_App == $APP_STEP)
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
                $APPROVE_AMOUNT     = $OFF_TOTCOST;
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

    // REJECT FUNCTION
        // CEK ACCESS OTORIZATION
            $resAPP = 0;
            $sqlAPP = "tbl_docstepapp_det WHERE MENU_CODE = '$MenuApp'
                        AND PRJCODE = '$PRJCODE' AND APPROVER_1 = '$DefEmp_ID'";
            $resAPP = $this->db->count_all($sqlAPP);
        // CEK SO
            $DOC_NO = '';
            $sqlIRC = "tbl_so_header WHERE OFF_NUM = '$OFF_NUM' AND SO_STAT != 5";
            $isUSED = $this->db->count_all($sqlIRC);
            if($isUSED > 0)
            {
                $sqlSO  = "SELECT SO_CODE FROM tbl_so_header WHERE OFF_NUM = '$OFF_NUM' AND SO_STAT != 5 LIMIT 1";
                $resSO  = $this->db->query($sqlSO)->result();
                foreach($resSO as $rowSO):
                    $DOC_NO = $rowSO->SO_CODE;
                endforeach;
            }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/salesorder.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small><?php echo $PRJNAME; ?></small>
            </h1>
        </section>

        <section class="content">   
            <div class="row">
                <!-- Mencari Kode Purchase Request Number -->
                    <form name="frmsrch" id="frmsrch" action="" method=POST style="display:none">
                        <input type="text" name="CCAL_NUMX" id="CCAL_NUMX" class="textbox" value="<?php echo $CCAL_NUM; ?>" />
                        <input type="text" name="CCAL_CODEX" id="CCAL_CODEX" class="textbox" value="<?php echo $CCAL_CODE; ?>" />
                        <input type="text" name="BOM_NUMX" id="BOM_NUMX" class="textbox" value="<?php echo $BOM_NUM; ?>" />
                        <input type="text" name="BOM_CODEX" id="BOM_CODEX" class="textbox" value="<?php echo $BOM_CODE; ?>" />
                        <input type="text" name="CUST_CODEX" id="CUST_CODEX" class="textbox" value="<?php echo $CUST_CODE; ?>" />
                        <input type="submit" class="button_css" name="submitSrch" id="submitSrch" value=" search " />
                    </form>
                <!-- End -->
                <form class="form-horizontal" name="frm" id="frm" method="post" action="<?php echo $form_action; ?>" onSubmit="return checkInp()">
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header with-border" style="display: none;">
                                <i class="fa fa-cloud-upload"></i>
                                <h3 class="box-title">&nbsp;</h3>
                            </div>
                            <div class="box-body">
                                <input type="hidden" name="DEPCODE" id="DEPCODE" value="<?php echo $DEPCODE; ?>">
                                <input type="hidden" name="rowCount" id="rowCount" value="0">
                                <input type="hidden" name="decFormat" id="decFormat" value="<?php echo $decFormat; ?>">
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
                                <div class="form-group" style="display:none"> <!-- OFF_NUM -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php //echo $PONumber ?> </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="max-width:195px" name="OFF_NUM1" id="OFF_NUM1" value="<?php echo $DocNumber; ?>" disabled >
                                        <input type="hidden" class="textbox" name="OFF_NUM" id="OFF_NUM" size="30" value="<?php echo $DocNumber; ?>" />
                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $lastPattNumb1; ?>">
                                    </div>
                                </div>
                                <div class="form-group"> <!-- OFF_CODE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
                                    <div class="col-sm-9">
                                        <input type="hidden" class="form-control" style="min-width:width:150px; max-width:150px" name="OFF_CODE" id="OFF_CODE" value="<?php echo $OFF_CODE; ?>" >
                                        <input type="text" class="form-control" name="OFF_CODEX" id="OFF_CODEX" value="<?php echo $OFF_CODE; ?>" disabled >
                                    </div>
                                </div>
                                <div class="form-group" style="display:none"> <!-- OFF_CODE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $ManualCode ?> </label>
                                    <div class="col-sm-9">
                                        <label>
                                            &nbsp;&nbsp;<input type="checkbox" name="isManual" id="isManual" checked>
                                        </label>
                                        <label style="font-style:italic">
                                            <?php echo $isManual; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group"> <!-- OFF_DATE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Date ?> </label>
                                    <div class="col-sm-9">
                                        <div class="input-group date">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i>&nbsp;</div>
                                            <input type="text" name="OFF_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $OFF_DATE; ?>" style="width:107px">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none"> <!-- PRJCODE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Project ?> </label>
                                    <div class="col-sm-9">
                                        <select name="PRJCODE" id="PRJCODE" class="form-control" style="max-width:350px" onChange="chooseProject()" disabled>
                                            <option value="none">--- None ---</option>
                                            <?php echo $i = 0;
                                                if($countPRJ > 0)
                                                {
                                                    foreach($vwPRJ as $row) :
                                                        $PRJCODE1   = $row->PRJCODE;
                                                        $PRJNAME    = $row->PRJNAME;
                                                        ?>
                                                      <option value="<?php echo $PRJCODE1; ?>" <?php if($PRJCODE1 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE - $PRJNAME"; ?></option>
                                                      <?php
                                                    endforeach;
                                                }
                                                else
                                                {
                                                    ?>
                                                      <option value="none">--- No Unit Found ---</option>
                                                  <?php
                                                }
                                            ?>
                                        </select>
                                        <input type="hidden" class="form-control" style="max-width:350px;text-align:right" id="PRJCODE" name="PRJCODE" size="20" value="<?php echo $PRJCODE; ?>" />
                                    </div>
                                </div>
                                <div class="form-group"> <!-- CUST_CODE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $CustName ?> </label>
                                    <div class="col-sm-9">
                                        <select name="CUST_CODE" id="CUST_CODE" class="form-control select2" >
                                            <option value="" > --- </option>
                                            <?php
                                            $i = 0;
                                            /*if($countCUST > 0)
                                            {*/
                                                foreach($vwCUST as $row) :
                                                    $CUST_CODE1 = $row->CUST_CODE;
                                                    $CUST_DESC1 = $row->CUST_DESC;
                                                    ?>
                                                        <option value="<?php echo $CUST_CODE1; ?>" <?php if($CUST_CODE1 == $CUST_CODE) { ?> selected <?php } ?>><?php echo "$CUST_DESC1"; ?></option>
                                                    <?php
                                                endforeach;
                                            //}
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group"> <!-- CUST_ADDRESS -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $AdditAddress ?> </label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="CUST_ADDRESS"  id="CUST_ADDRESS" style="height:70px" placeholder="<?php echo $AdditAddress; ?>"><?php echo $CUST_ADDRESS; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <i class="fa fa-tags"></i>
                                <h3 class="box-title"><?php echo $sourceDoc; ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group"> <!-- CCAL_NUM, CCAL_CODE, BOM_CODE -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $BOMCode ?> </label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary"><?php echo $Search ?> </button>
                                            </div>
                                            <input type="hidden" class="form-control" name="CCAL_NUM" id="CCAL_NUM" style="max-width:160px" value="<?php echo $CCAL_NUM; ?>" >
                                            <input type="hidden" class="form-control" name="CCAL_CODE" id="CCAL_CODE" style="max-width:160px" value="<?php echo $COLL_CALCODE; ?>" >
                                            <input type="hidden" class="form-control" name="BOM_NUM" id="BOM_NUM" style="max-width:160px" value="<?php echo $COLL_BOMNUM; ?>" >
                                            <input type="hidden" class="form-control" name="BOM_CODE" id="BOM_CODE" style="max-width:160px" value="<?php echo $COLL_BOMCODE; ?>" >
                                            <input type="text" class="form-control" name="BOM_CODE1" id="BOM_CODE1" value="<?php echo $COLL_BOMCODE; ?>" onClick="getCCALCODE();" <?php if($task != 'add') { ?> disabled <?php } ?>>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    $url_selCCAL    = site_url('c_sales/c_0ff3r1n9/s3l4llit3m/?id=');
                                ?>
                                <script>
                                    var url1 = "<?php echo $url_selCCAL;?>";
                                    function getCCALCODE()
                                    {
                                        PRJCODE     = document.getElementById('PRJCODE').value;
                                        CUST_CODE   = document.getElementById('CUST_CODE').value;
                                        if(CUST_CODE == '')
                                        {
                                            swal("<?php echo $alert2; ?>",
                                            {
                                                icon: "warning",
                                            });
                                            document.getElementById('CUST_CODE').focus();
                                            return false;
                                        }
                                        collDATA    = PRJCODE+'~'+CUST_CODE;
                                        
                                        title = 'Select Item';
                                        w = 1000;
                                        h = 550;
                                        //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                        var left = (screen.width/2)-(w/2);
                                        var top = (screen.height/2)-(h/2);
                                        return window.open(url1+collDATA, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                                    }
                                </script>
                                <div class="form-group"> <!-- NOTES -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Notes ?> </label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="OFF_NOTES"  id="OFF_NOTES" style="height:80px" placeholder="<?php echo $Notes; ?>"><?php echo $OFF_NOTES; ?></textarea>
                                        <input type="hidden" name="OFF_TOTCOST" id="OFF_TOTCOST" value="<?php echo $OFF_TOTCOST; ?>">
                                    </div>
                                </div>
                                <div class="form-group" > <!-- OFF_STAT -->
                                    <label for="inputName" class="col-sm-3 control-label"><?php echo $Status ?> </label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="STAT_BEFORE" id="STAT_BEFORE" value="<?php echo $OFF_STAT; ?>">
                                        <?php
                                            $isDisabled = 1;
                                            if($OFF_STAT == 1 || $OFF_STAT == 4)
                                            {
                                                $isDisabled = 0;
                                            }
                                        ?>
                                        <select name="OFF_STAT" id="OFF_STAT" class="form-control select2" onChange="selStat(this.value)">
                                            <?php
                                            if($OFF_STAT != 1 AND $OFF_STAT != 4) 
                                            {
                                                ?>
                                                    <option value="1"<?php if($OFF_STAT == 1) { ?> selected <?php } ?> disabled>New</option>
                                                    <option value="2"<?php if($OFF_STAT == 2) { ?> selected <?php } ?> disabled>Confirm</option>
                                                    <option value="3"<?php if($OFF_STAT == 3) { ?> selected <?php } ?> disabled>Approve</option>
                                                    <option value="4"<?php if($OFF_STAT == 4) { ?> selected <?php } ?> disabled>Revising</option>
                                                    <option value="5"<?php if($OFF_STAT == 5) { ?> selected <?php } ?> disabled>Rejected</option>
                                                    <option value="6"<?php if($OFF_STAT == 6) { ?> selected <?php } ?>>Closed</option>
                                                    <option value="7"<?php if($OFF_STAT == 7) { ?> selected <?php } ?> disabled>Waiting</option>
                                                    <?php if($OFF_STAT == 3 || $OFF_STAT == 9) { ?>
                                                        <option value="7"<?php if($OFF_STAT == 7) { ?> selected <?php } ?> >Void</option>
                                                    <?php } ?>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <option value="1"<?php if($OFF_STAT == 1) { ?> selected <?php } ?>>New</option>
                                                    <option value="2"<?php if($OFF_STAT == 2) { ?> selected <?php } ?>>Confirm</option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <script type="text/javascript">
                                    function selStat(statVal)
                                    {
                                        var STAT_BEFORE = document.getElementById('STAT_BEFORE').value;
                                        if(STAT_BEFORE == 3 && statVal == 6)
                                        {
                                            document.getElementById('tblClose').style.display = '';
                                        }
                                        else if(STAT_BEFORE == 5 || statVal == 6)
                                        {
                                            document.getElementById('tblClose').style.display = 'none';
                                        }
                                    }
                                </script>
                                <?php
                                    $url_AddItem    = site_url('c_sales/c_0ff3r1n9/s3l4llit3m/?id=');
                                    if($OFF_STAT == 1)
                                    {
                                    ?>
                                    <div class="form-group" style="display:none">
                                        <label for="inputName" class="col-sm-3 control-label">&nbsp;</label>
                                        <div class="col-sm-9">
                                            <script>
                                                var url = "<?php echo $url_AddItem;?>";
                                                function selectitem()
                                                {
                                                    PRJCODE     = document.getElementById('PRJCODE').value;
                                                    CUST_CODE   = document.getElementById('CUST_CODE').value;
                                                    if(CUST_CODE == '')
                                                    {
                                                        swal("<?php echo $alert2; ?>",
                                                        {
                                                            icon: "warning",
                                                        });
                                                        document.getElementById('CUST_CODE').focus();
                                                        return false;
                                                    }
                                                    collDATA    = PRJCODE+'~'+CUST_CODE;
                                                    
                                                    title = 'Select Item';
                                                    w = 1000;
                                                    h = 550;
                                                    //window.open(url,'window_baru','width=500','height=200','scrollbars=yes,resizable=yes,location=no,status=yes');
                                                    var left = (screen.width/2)-(w/2);
                                                    var top = (screen.height/2)-(h/2);
                                                    return window.open(url+collDATA, title, 'toolbar=yes, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                                                }
                                            </script>
                                            <button class="btn btn-success" type="button" onClick="selectitem();">
                                            <i class="fa fa-pie-chart"></i>&nbsp;&nbsp;<?php echo $ColorCode; ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" id="IRNOT2" <?php if($OFF_NOTES1 == '') { ?> style="display:none" <?php } ?>>
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <i class="fa fa-commenting"></i>
                                <h3 class="box-title"><?php echo $ApproverNotes." / ".$revision ?></h3>
                            </div>
                            <div class="box-body">
                                <textarea class="form-control" name="OFF_NOTES1"  id="OFF_NOTES1" style="height:50px" disabled><?php echo $OFF_NOTES1; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="search-table-outter">
                                <table id="tbl" class="table table-bordered table-striped" width="100%">
                                    <tr style="background:#CCCCCC">
                                        <th width="2%" height="25" style="text-align:center; vertical-align: middle;">No.</th>
                                        <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemCode; ?> </th>
                                        <th width="20%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ItemName; ?> </th>
                                        <th width="15%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $ColorName; ?> </th>
                                        <th width="8%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Quantity; ?> </th> 
                                        <!-- Input Manual -->
                                        <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Unit; ?> </th>
                                        <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $UnitPrice; ?> </th>
                                        <th width="5%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?><br>
                                        (%)</th>
                                        <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Discount; ?> </th>
                                        <th width="5%" style="text-align:center; display:none" nowrap><?php echo $Tax; ?></th>
                                        <th width="10%" style="text-align:center; vertical-align: middle;" nowrap><?php echo $Price; ?></th>
                                    </tr>
                                    <?php
                                        $resultC    = 0;
                                        if($task == 'edit')
                                        {
                                            $sqlDET     = "SELECT A.*, B.ITM_NAME
                                                            FROM tbl_offering_d A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                            WHERE 
                                                                A.OFF_NUM = '$OFF_NUM' 
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $result = $this->db->query($sqlDET)->result();
                                            // count data
                                            $sqlDETC    = "tbl_offering_d A
                                                                INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
                                                            WHERE 
                                                                A.OFF_NUM = '$OFF_NUM' 
                                                                AND B.PRJCODE = '$PRJCODE'";
                                            $resultC    = $this->db->count_all($sqlDETC);
                                        }
                                        else
                                        {
                                            $sqlDET     = "SELECT A.CCAL_NUM, A.CCAL_CODE, A.ITM_CODE, A.ITM_UNIT, 
                                                                A.ITM_QTY AS OFF_VOLM, A.ITM_PRICE AS OFF_PRICE, 0 AS OFF_DISCP,
                                                                0 AS OFF_DISC, A.ITM_TOTAL AS OFF_TOTCOST, A.ITM_NOTES AS OFF_DESC,
                                                                '' AS TAXCODE1, 0 AS TAXPRICE1,
                                                                B.BOM_CODE, B.CUST_CODE, C.ITM_NAME
                                                            FROM tbl_ccal_detail A
                                                            INNER JOIN tbl_ccal_header B ON A.CCAL_NUM = B.CCAL_NUM
                                                                AND B.CUST_CODE = '$CUST_CODE'
                                                                AND B.CCAL_STAT = 3
                                                            INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
                                                                AND C.PRJCODE = '$PRJCODE'
                                                            WHERE A.ITM_CATEG = 'FG' AND A.PRJCODE = '$PRJCODE'
                                                                AND B.CCAL_NUM IN ('$COLL_CCALNUM')";
                                            $result = $this->db->query($sqlDET)->result();
                                            // count data
                                            $sqlDETC    = "tbl_ccal_detail A
                                                            INNER JOIN tbl_ccal_header B ON A.CCAL_NUM = B.CCAL_NUM
                                                                AND B.CUST_CODE = '$CUST_CODE'
                                                                AND B.CCAL_STAT = 3
                                                            INNER JOIN tbl_item C ON A.ITM_CODE = C.ITM_CODE
                                                                AND C.PRJCODE = '$PRJCODE'
                                                            WHERE A.ITM_CATEG = 'FG' AND A.PRJCODE = '$PRJCODE'
                                                                AND B.CCAL_NUM IN ('$COLL_CCALNUM')";
                                            $resultC    = $this->db->count_all($sqlDETC);
                                        }
                                            
                                        $i          = 0;
                                        $j          = 0;
                                        $currentRow = 0;
                                        if($resultC > 0)
                                        {
                                            $GT_ITMPRICE    = 0;
                                            foreach($result as $row) :
                                                $currentRow     = ++$i;                                                             
                                                $OFF_NUM        = $OFF_NUM;
                                                $OFF_CODE       = $OFF_CODE;
                                                $PRJCODE        = $PRJCODE;
                                                $CCAL_NUM       = $row->CCAL_NUM;
                                                $CCAL_CODE      = $row->CCAL_CODE;
                                                $BOM_CODE       = $row->BOM_CODE;
                                                $ITM_CODE       = $row->ITM_CODE;
                                                $ITM_NAME       = $row->ITM_NAME;
                                                $ITM_UNIT       = $row->ITM_UNIT;
                                                $OFF_VOLM       = $row->OFF_VOLM;
                                                if($OFF_VOLM == '')
                                                    $OFF_VOLM   = 0;
                                                $OFF_PRICE      = $row->OFF_PRICE;
                                                if($OFF_PRICE == '')
                                                    $OFF_PRICE  = 0;
                                                $OFF_COST       = $OFF_VOLM * $OFF_PRICE;                               
                                                $OFF_DISCP      = $row->OFF_DISCP;
                                                if($OFF_DISCP == '')
                                                    $OFF_DISCP  = 0;
                                                $OFF_DISC       = $row->OFF_DISC;
                                                if($OFF_DISC == '')
                                                    $OFF_DISC   = 0;
                                                $OFF_TOTCOST    = $row->OFF_TOTCOST;
                                                if($OFF_TOTCOST == '')
                                                    $OFF_TOTCOST    = 0;
                                                $OFF_DESC       = $row->OFF_DESC;
                                                $TAXCODE1       = $row->TAXCODE1;
                                                $TAXPRICE1      = $row->TAXPRICE1;

                                                // COLOR NAME
                                                $CCAL_NAME      = '';
                                                $sqlCOLNM       = "SELECT CCAL_NAME FROM tbl_ccal_header WHERE CCAL_NUM = '$CCAL_NUM' LIMIT 1";
                                                $resCOLNM       = $this->db->query($sqlCOLNM)->result();
                                                foreach($resCOLNM as $rowCOLNM) :
                                                    $CCAL_NAME  = $rowCOLNM->CCAL_NAME;
                                                endforeach;
                                    
                                                /*if ($j==1) {
                                                    echo "<tr class=zebra1>";
                                                    $j++;
                                                } else {
                                                    echo "<tr class=zebra2>";
                                                    $j--;
                                                }*/
                                                ?>
                                                    <tr id="tr_<?php echo $currentRow; ?>">
                                                        <!-- NO URUT -->
                                                        <td style="text-align:center; vertical-align: middle;">
                                                            <?php
                                                                echo "$currentRow.";
                                                            ?>
                                                            <input style="display:none" type="Checkbox" id="data[<?php echo $currentRow; ?>][chk]" name="data[<?php echo $currentRow; ?>][chk]" value="<?php echo $currentRow; ?>" onClick="pickThis(this,<?php echo $currentRow; ?>)">
                                                            <input type="Checkbox" style="display:none" id="chk" name="chk" value="" >
                                                        </td>
                                                        
                                                        <!-- ITEM CODE -->
                                                        <td style="text-align:left; vertical-align: middle;">
                                                            <?php print $ITM_CODE; ?>
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>ITM_CODE" name="data[<?php echo $currentRow; ?>][ITM_CODE]" value="<?php echo $ITM_CODE; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>OFF_NUM" name="data[<?php echo $currentRow; ?>][OFF_NUM]" value="<?php echo $OFF_NUM; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>OFF_CODE" name="data[<?php echo $currentRow; ?>][OFF_CODE]" value="<?php echo $OFF_CODE; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>PRJCODE" name="data[<?php echo $currentRow; ?>][PRJCODE]" value="<?php echo $PRJCODE; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>CCAL_NUM" name="data[<?php echo $currentRow; ?>][CCAL_NUM]" value="<?php echo $CCAL_NUM; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>CCAL_CODE" name="data[<?php echo $currentRow; ?>][CCAL_CODE]" value="<?php echo $CCAL_CODE; ?>" width="10" size="15">
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>BOM_CODE" name="data[<?php echo $currentRow; ?>][BOM_CODE]" value="<?php echo $BOM_CODE; ?>" width="10" size="15">
                                                        </td>
                                                        
                                                        <!-- ITEM NAME -->
                                                        <td style="text-align:left; vertical-align: middle;"><?php echo $ITM_NAME; ?></td>
                                                        
                                                        <!-- COLOR NAME -->
                                                        <td style="text-align:left; vertical-align: middle;"><?php echo $CCAL_NAME; ?></td>
                                                        
                                                        <!-- ITEM QTY NOW -->  
                                                        <td style="text-align:right; vertical-align: middle;">
                                                            <?php if($OFF_STAT == 1 || $OFF_STAT == 4) { ?>
                                                                <input type="text" class="form-control" style="max-width:200px; text-align:right" name="OFF_VOLM<?php echo $currentRow; ?>" id="OFF_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($OFF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onChange="chgQty(this, <?php echo $currentRow; ?>);" >
                                                            <?php } else { ?>
                                                                <?php print number_format($OFF_VOLM, $decFormat); ?>
                                                                <input type="hidden" class="form-control" style="max-width:200px; text-align:right" name="OFF_VOLM<?php echo $currentRow; ?>" id="OFF_VOLM<?php echo $currentRow; ?>" value="<?php echo number_format($OFF_VOLM, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onChange="chgQty(this, <?php echo $currentRow; ?>);" >
                                                            <?php } ?>
                                                            
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>OFF_VOLM" name="data[<?php echo $currentRow; ?>][OFF_VOLM]" value="<?php echo $OFF_VOLM; ?>">
                                                        </td>
                                                            
                                                        <!-- ITEM UNIT -->
                                                        <td style="text-align:center; vertical-align: middle;">
                                                            <?php print $ITM_UNIT; ?>  
                                                            <input type="hidden" id="data<?php echo $currentRow; ?>ITM_UNIT" name="data[<?php echo $currentRow; ?>][ITM_UNIT]" value="<?php print $ITM_UNIT; ?>">
                                                        </td>
                                                        
                                                        <!-- ITEM PRICE -->
                                                        <td style="text-align:right; vertical-align: middle;">
                                                            <?php if($OFF_STAT == 1 || $OFF_STAT == 4) { ?>
                                                                <input type="text" class="form-control" style="text-align:right; min-width:100px" name="OFF_PRICE<?php echo $currentRow; ?>" id="OFF_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($OFF_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onChange="chgPrc(this, <?php echo $currentRow; ?>);">
                                                            <?php } else { ?>
                                                                <?php print number_format($OFF_PRICE, $decFormat); ?>
                                                                <input type="hidden" class="form-control" style="text-align:right; min-width:100px" name="OFF_PRICE<?php echo $currentRow; ?>" id="OFF_PRICE<?php echo $currentRow; ?>" size="10" value="<?php echo number_format($OFF_PRICE, 2); ?>" onKeyPress="return isIntOnlyNew(event);" onChange="chgPrc(this, <?php echo $currentRow; ?>);">
                                                            <?php } ?>
                                                            
                                                            <input type="hidden" style="text-align:right" name="data[<?php echo $currentRow; ?>][OFF_PRICE]" id="data<?php echo $currentRow; ?>OFF_PRICE" size="6" value="<?php echo $OFF_PRICE; ?>">
                                                            <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][OFF_COST]" id="data<?php echo $currentRow; ?>OFF_COST" value="<?php echo $OFF_COST; ?>">
                                                        </td>
                                                         
                                                        <!-- ITEM DISCOUNT PERCENTATION -->
                                                        <td style="text-align:right; vertical-align: middle;">
                                                            <?php if($OFF_STAT == 1 || $OFF_STAT == 4) { ?>
                                                                <input type="text" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="OFF_DISCP<?php echo $currentRow; ?>" id="OFF_DISCP<?php echo $currentRow; ?>" value="<?php print number_format($OFF_DISCP, $decFormat); ?>" onBlur="chgDiscP(this, <?php echo $currentRow; ?>);" >
                                                            <?php } else { ?>
                                                                <?php print number_format($OFF_DISCP, $decFormat); ?>
                                                                <input type="hidden" class="form-control" size="10"  style=" min-width:65px; max-width:150px; text-align:right" name="OFF_DISCP<?php echo $currentRow; ?>" id="OFF_DISCP<?php echo $currentRow; ?>" value="<?php print number_format($OFF_DISCP, $decFormat); ?>" onBlur="chgDiscP(this, <?php echo $currentRow; ?>);" >
                                                            <?php } ?>
                                                            
                                                            <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][OFF_DISCP]" id="data<?php echo $currentRow; ?>OFF_DISCP" value="<?php echo $OFF_DISCP; ?>">
                                                        </td>
                                                            
                                                        <!-- ITEM DISCOUNT -->
                                                        <td style="text-align:right; vertical-align: middle;">
                                                            <?php if($OFF_STAT == 1 || $OFF_STAT == 4) { ?>
                                                                <input type="text" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="OFF_DISC<?php echo $currentRow; ?>" id="OFF_DISC<?php echo $currentRow; ?>" value="<?php print number_format($OFF_DISC, $decFormat); ?>" onBlur="chgDisc(this, <?php echo $currentRow; ?>);" >
                                                            <?php } else { ?>
                                                                <?php print number_format($OFF_DISC, $decFormat); ?>
                                                                <input type="hidden" class="form-control" style="min-width:100px; max-width:350px; text-align:right" name="OFF_DISC<?php echo $currentRow; ?>" id="OFF_DISC<?php echo $currentRow; ?>" value="<?php print number_format($OFF_DISC, $decFormat); ?>" onBlur="chgDisc(this, <?php echo $currentRow; ?>);" >
                                                            <?php } ?>
                                                            
                                                            <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][OFF_DISC]" id="data<?php echo $currentRow; ?>OFF_DISC" value="<?php echo $OFF_DISC; ?>">
                                                        </td>
                                                            
                                                        <!-- ITEM TAX -->
                                                        <td style="text-align:left; display:none">
                                                            &nbsp;
                                                            <select name="data[<?php echo $currentRow; ?>][TAXCODE1]" class="form-control" id="data<?php echo $currentRow; ?>TAXCODE1" onChange="chgTax(this, <?php echo $currentRow; ?>);" style="max-width:150px; display:none">
                                                                <option value=""> --- </option>
                                                                <option value="TAX01" <?php if ($TAXCODE1 == "TAX01") { ?> selected <?php } ?>>PPn 10%</option>
                                                                <option value="TAX02" <?php if ($TAXCODE1 == "TAX02") { ?> selected <?php } ?> disabled>PPh</option>
                                                            </select>
                                                        </td>
                                                        
                                                        <!-- ITEM TOTAL COST -->
                                                        <td style="text-align:right; vertical-align: middle;">
                                                            <?php if($OFF_STAT == 1 || $OFF_STAT == 4) { ?>
                                                                <input type="text" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="OFF_TOTCOST<?php echo $currentRow; ?>" id="OFF_TOTCOST<?php echo $currentRow; ?>" value="<?php print number_format($OFF_TOTCOST, $decFormat); ?>" >
                                                            <?php } else { ?>
                                                                <?php print number_format($OFF_TOTCOST, $decFormat); ?>
                                                                <input type="hidden" class="form-control" style="min-width:130px; max-width:350px; text-align:right" name="OFF_TOTCOST<?php echo $currentRow; ?>" id="OFF_TOTCOST<?php echo $currentRow; ?>" value="<?php print number_format($OFF_TOTCOST, $decFormat); ?>" >
                                                            <?php } ?>

                                                            <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][TAXPRICE1]" id="data<?php echo $currentRow; ?>TAXPRICE1" value="<?php echo $TAXPRICE1; ?>">
                                                            <input style="text-align:right" type="hidden" name="data[<?php echo $currentRow; ?>][OFF_TOTCOST]" id="data<?php echo $currentRow; ?>OFF_TOTCOST" value="<?php echo $OFF_TOTCOST; ?>">
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
                        <br>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <?php
                                    if($task=='add')
                                    {
                                        if($ISCREATE == 1)
                                        {
                                            ?>
                                                <button class="btn btn-primary">
                                                <i class="fa fa-save"></i>
                                                </button>&nbsp;
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        if($OFF_STAT == 1 || $OFF_STAT == 4)
                                        {
                                            ?>
                                                <button class="btn btn-primary" >
                                                <i class="fa fa-save"></i>
                                                </button>&nbsp;
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <button class="btn btn-primary" style="display:none" id="tblClose">
                                                <i class="fa fa-save"></i>
                                                </button>&nbsp;
                                            <?php
                                        }
                                    }
                                    $backURL    = site_url('c_sales/c_0ff3r1n9/gl0ff3r1n9/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
                                    echo anchor("$backURL",'<button class="btn btn-danger" type="button"><i class="fa fa-reply"></i></button>');
                                ?>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-md-12">
                    <?php
                        $DOC_NUM    = $OFF_NUM;
                        $sqlCAPPH   = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM'";
                        $resCAPPH   = $this->db->count_all($sqlCAPPH);
                        $sqlAPP     = "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuApp'
                                        AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE proj_Code = '$PRJCODE_LEV')";
                        $resAPP     = $this->db->query($sqlAPP)->result();
                        foreach($resAPP as $rowAPP) :
                            $MAX_STEP       = $rowAPP->MAX_STEP;
                            $APPROVER_1     = $rowAPP->APPROVER_1;
                            $APPROVER_2     = $rowAPP->APPROVER_2;
                            $APPROVER_3     = $rowAPP->APPROVER_3;
                            $APPROVER_4     = $rowAPP->APPROVER_4;
                            $APPROVER_5     = $rowAPP->APPROVER_5;
                        endforeach;
                        
                        if($resCAPP == 0)
                        {
                            if($LangID == 'IND')
                            {
                                $zerSetApp  = "Belum ada pengaturan untuk persetujuan dokumen ini.";
                            }
                            else
                            {
                                $zerSetApp  = "There are no arrangements for the approval of this document.";
                            }
                            ?>
                                <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $zerSetApp; ?>
                                </div>
                            <?php
                        }
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
                                <?php
                                    $SHOWOTH        = 0;
                                    $AH_ISLAST      = 0;
                                    $APPROVER_1A    = 0;
                                    $APPROVER_2A    = 0;
                                    $APPROVER_3A    = 0;
                                    $APPROVER_4A    = 0;
                                    $APPROVER_5A    = 0;
                                    if($APPROVER_1 != '')
                                    {
                                        $boxCol_1   = "red";
                                        $sqlCAPPH_1 = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                        $resCAPPH_1 = $this->db->count_all($sqlCAPPH_1);
                                        if($resCAPPH_1 > 0)
                                        {
                                            $boxCol_1   = "green";
                                            $Approver   = $Approved;
                                            $class      = "glyphicon glyphicon-ok-sign";
                                            
                                            $sqlAPPH_1  = "SELECT AH_APPROVED, AH_ISLAST 
                                                            FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_1'";
                                            $resAPPH_1  = $this->db->query($sqlAPPH_1)->result();
                                            foreach($resAPPH_1 as $rowAPPH_1):
                                                $APPROVED_1 = $rowAPPH_1->AH_APPROVED;
                                                $AH_ISLAST  = $rowAPPH_1->AH_ISLAST;
                                            endforeach;
                                        }
                                        elseif($resCAPPH_1 == 0)
                                        {
                                            $Approver   = $NotYetApproved;
                                            $class      = "glyphicon glyphicon-remove-sign";
                                            $APPROVED_1 = "Not Set";
                                            
                                            $sqlCAPPH_1A    = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
                                            $resCAPPH_1A    = $this->db->count_all($sqlCAPPH_1A);
                                            if($resCAPPH_1A > 0)
                                            {
                                                $SHOWOTH    = 1;
                                                $APPROVER_1A= 1;
                                                $EMPN_1A    = '';
                                                $AH_ISLAST1A=0;
                                                $APPROVED_1A= '0000-00-00';
                                                $boxCol_1A  = "green";
                                                $Approver1A = $Approved;
                                                $class1A    = "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_1A = "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
                                                                    CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
                                                                FROM tbl_approve_hist A 
                                                                    INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 1";
                                                $resAPPH_1A = $this->db->query($sqlAPPH_1A)->result();
                                                foreach($resAPPH_1A as $rowAPPH_1A):
                                                    $EMPN_1A        = $rowAPPH_1A->COMPNAME;
                                                    $AH_ISLAST1A    = $rowAPPH_1A->AH_ISLAST;
                                                    $APPROVED_1A    = $rowAPPH_1A->AH_APPROVED;
                                                endforeach;
                                            }
                                        }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_1; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">

                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_1", 20); ?></span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 50%"></div>
                                                        </div>
                                                        <span class="progress-description">
                                                            <?php echo $APPROVED_1; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    if($APPROVER_2 != '' && $AH_ISLAST == 0)
                                    {
                                        $boxCol_2   = "red";
                                        $sqlCAPPH_2 = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
                                        $resCAPPH_2 = $this->db->count_all($sqlCAPPH_2);
                                        if($resCAPPH_2 > 0)
                                        {
                                            $boxCol_2   = "green";
                                            $class      = "glyphicon glyphicon-ok-sign";
                                            
                                            $sqlAPPH_2  = "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_2'";
                                            $resAPPH_2  = $this->db->query($sqlAPPH_2)->result();
                                            foreach($resAPPH_2 as $rowAPPH_2):
                                                $APPROVED_2 = $rowAPPH_2->AH_APPROVED;
                                            endforeach;
                                        }
                                        elseif($resCAPPH_2 == 0)
                                        {
                                            $Approver   = $NotYetApproved;
                                            $class      = "glyphicon glyphicon-remove-sign";
                                            $APPROVED_2 = "Not Set";
                                            
                                            $sqlCAPPH_2A    = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
                                            $resCAPPH_2A    = $this->db->count_all($sqlCAPPH_2A);
                                            if($resCAPPH_2A > 0)
                                            {
                                                $APPROVER_2A= 1;
                                                $EMPN_2A    = '';
                                                $AH_ISLAST2A=0;
                                                $APPROVED_2A= '0000-00-00';
                                                $boxCol_2A  = "green";
                                                $Approver2A = $Approved;
                                                $class2A    = "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_2A = "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
                                                                    CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
                                                                FROM tbl_approve_hist A 
                                                                    INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 2";
                                                $resAPPH_2A = $this->db->query($sqlAPPH_2A)->result();
                                                foreach($resAPPH_2A as $rowAPPH_2A):
                                                    $EMPN_2A        = $rowAPPH_2A->COMPNAME;
                                                    $AH_ISLAST2A    = $rowAPPH_2A->AH_ISLAST;
                                                    $APPROVED_2A    = $rowAPPH_2A->AH_APPROVED;
                                                endforeach;
                                            }
                                        }
                                        
                                        /*if($resCAPPH == 0)
                                        {
                                            $Approver   = $Awaiting;
                                            $boxCol_2   = "yellow";
                                            $class      = "glyphicon glyphicon-info-sign";
                                        }*/
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_2; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_2", 20); ?></span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 50%"></div>
                                                        </div>
                                                        <span class="progress-description">
                                                            <?php echo $APPROVED_2; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    if($APPROVER_3 != '' && $AH_ISLAST == 0)
                                    {
                                        $boxCol_3   = "red";
                                        $sqlCAPPH_3 = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
                                        $resCAPPH_3 = $this->db->count_all($sqlCAPPH_3);
                                        if($resCAPPH_3 > 0)
                                        {
                                            $boxCol_3   = "green";
                                            $class      = "glyphicon glyphicon-ok-sign";
                                            
                                            $sqlAPPH_3  = "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_3'";
                                            $resAPPH_3  = $this->db->query($sqlAPPH_3)->result();
                                            foreach($resAPPH_3 as $rowAPPH_3):
                                                $APPROVED_3 = $rowAPPH_3->AH_APPROVED;
                                            endforeach;
                                        }
                                        elseif($resCAPPH_3 == 0)
                                        {
                                            $Approver   = $NotYetApproved;
                                            $class      = "glyphicon glyphicon-remove-sign";
                                            $APPROVED_3 = "Not Set";
                                            
                                            $sqlCAPPH_3A    = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
                                            $resCAPPH_3A    = $this->db->count_all($sqlCAPPH_3A);
                                            if($resCAPPH_3A > 0)
                                            {
                                                $APPROVER_3A= 1;
                                                $EMPN_3A    = '';
                                                $AH_ISLAST3A=0;
                                                $APPROVED_3A= '0000-00-00';
                                                $boxCol_3A  = "green";
                                                $Approver3A = $Approved;
                                                $class3A    = "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_3A = "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
                                                                    CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
                                                                FROM tbl_approve_hist A 
                                                                    INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 3";
                                                $resAPPH_3A = $this->db->query($sqlAPPH_3A)->result();
                                                foreach($resAPPH_3A as $rowAPPH_3A):
                                                    $EMPN_3A        = $rowAPPH_3A->COMPNAME;
                                                    $AH_ISLAST3A    = $rowAPPH_3A->AH_ISLAST;
                                                    $APPROVED_3A    = $rowAPPH_3A->AH_APPROVED;
                                                endforeach;
                                            }
                                        }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_3; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_3", 20); ?></span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 50%"></div>
                                                        </div>
                                                        <span class="progress-description">
                                                            <?php echo $APPROVED_3; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    if($APPROVER_4 != '' && $AH_ISLAST == 0)
                                    {
                                        $boxCol_4   = "red";
                                        $sqlCAPPH_4 = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
                                        $resCAPPH_4 = $this->db->count_all($sqlCAPPH_4);
                                        if($resCAPPH_4 > 0)
                                        {
                                            $boxCol_4   = "green";
                                            $class      = "glyphicon glyphicon-ok-sign";
                                            
                                            $sqlAPPH_4  = "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_4'";
                                            $resAPPH_4  = $this->db->query($sqlAPPH_4)->result();
                                            foreach($resAPPH_4 as $rowAPPH_4):
                                                $APPROVED_4 = $rowAPPH_4->AH_APPROVED;
                                            endforeach;
                                        }
                                        elseif($resCAPPH_4 == 0)
                                        {
                                            $Approver   = $NotYetApproved;
                                            $class      = "glyphicon glyphicon-remove-sign";
                                            $APPROVED_4 = "Not Set";
                                            
                                            $sqlCAPPH_4A    = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
                                            $resCAPPH_4A    = $this->db->count_all($sqlCAPPH_4A);
                                            if($resCAPPH_4A > 0)
                                            {
                                                $APPROVER_4A= 1;
                                                $EMPN_4A    = '';
                                                $AH_ISLAST4A=0;
                                                $APPROVED_4A= '0000-00-00';
                                                $boxCol_4A  = "green";
                                                $Approver4A = $Approved;
                                                $class4A    = "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_4A = "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
                                                                    CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
                                                                FROM tbl_approve_hist A 
                                                                    INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 4";
                                                $resAPPH_4A = $this->db->query($sqlAPPH_4A)->result();
                                                foreach($resAPPH_4A as $rowAPPH_4A):
                                                    $EMPN_4A        = $rowAPPH_4A->COMPNAME;
                                                    $AH_ISLAST4A    = $rowAPPH_4A->AH_ISLAST;
                                                    $APPROVED_4A    = $rowAPPH_4A->AH_APPROVED;
                                                endforeach;
                                            }
                                        }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_4; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_4", 20); ?></span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 50%"></div>
                                                        </div>
                                                        <span class="progress-description">
                                                            <?php echo $APPROVED_4; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    if($APPROVER_5 != '' && $AH_ISLAST == 0)
                                    {
                                        $boxCol_5   = "red";
                                        $sqlCAPPH_5 = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
                                        $resCAPPH_5 = $this->db->count_all($sqlCAPPH_5);
                                        if($resCAPPH_5 > 0)
                                        {
                                            $boxCol_5   = "green";
                                            $class      = "glyphicon glyphicon-ok-sign";
                                            
                                            $sqlAPPH_5  = "SELECT AH_APPROVED FROM tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPROVER = '$APPROVER_5'";
                                            $resAPPH_5  = $this->db->query($sqlAPPH_5)->result();
                                            foreach($resAPPH_5 as $rowAPPH_5):
                                                $APPROVED_5 = $rowAPPH_5->AH_APPROVED;
                                            endforeach;
                                        }
                                        elseif($resCAPPH_5 == 0)
                                        {
                                            $Approver   = $NotYetApproved;
                                            $class      = "glyphicon glyphicon-remove-sign";
                                            $APPROVED_5 = "Not Set";
                                            
                                            $sqlCAPPH_5A    = "tbl_approve_hist WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
                                            $resCAPPH_5A    = $this->db->count_all($sqlCAPPH_5A);
                                            if($resCAPPH_5A > 0)
                                            {
                                                $APPROVER_5A= 1;
                                                $EMPN_5A    = '';
                                                $AH_ISLAST5A=0;
                                                $APPROVED_5A= '0000-00-00';
                                                $boxCol_5A  = "green";
                                                $Approver5A = $Approved;
                                                $class5A    = "glyphicon glyphicon-ok-sign";
                                                
                                                $sqlAPPH_5A = "SELECT A.AH_APPROVER, A.AH_APPROVED, A.AH_ISLAST,
                                                                    CONCAT(B.First_Name, ' ', B.Last_Name) AS COMPNAME
                                                                FROM tbl_approve_hist A 
                                                                    INNER JOIN tbl_employee B ON A.AH_APPROVER = B.Emp_ID
                                                                WHERE AH_CODE = '$DOC_NUM' AND AH_APPLEV = 5";
                                                $resAPPH_5A = $this->db->query($sqlAPPH_5A)->result();
                                                foreach($resAPPH_5A as $rowAPPH_5A):
                                                    $EMPN_5A        = $rowAPPH_5A->COMPNAME;
                                                    $AH_ISLAST5A    = $rowAPPH_5A->AH_ISLAST;
                                                    $APPROVED_5A    = $rowAPPH_5A->AH_APPROVED;
                                                endforeach;
                                            }
                                        }
                                        ?>
                                            <div class="col-md-3">
                                                <div class="info-box bg-<?php echo $boxCol_5; ?>">
                                                    <span class="info-box-icon"><i class="<?php echo $class; ?>"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><?php echo $Approver; ?></span>
                                                        <span class="info-box-number"><?php echo cut_text ("$EMPN_5", 20); ?></span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: 50%"></div>
                                                        </div>
                                                        <span class="progress-description">
                                                            <?php echo $APPROVED_5; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($SHOWOTH == 1) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-danger collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $InOthSett; ?></h3>
                                        <div class="box-tools pull-right">
                                            <span class="label label-danger"><?php echo "$Approved : $resCAPPH "; ?></span>
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                    <?php
                                        if($APPROVER_1A == 1)
                                        {
                                            ?>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-<?php echo $boxCol_1A; ?>">
                                                        <span class="info-box-icon"><i class="<?php echo $class1A; ?>"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text"><?php echo $Approver1A; ?></span>
                                                            <span class="info-box-number"><?php echo cut_text ("$EMPN_1A", 20); ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 50%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                <?php echo $APPROVED_1A; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        if($APPROVER_2A == 1)
                                        {
                                            ?>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-<?php echo $boxCol_2A; ?>">
                                                        <span class="info-box-icon"><i class="<?php echo $class2A; ?>"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text"><?php echo $Approver2A; ?></span>
                                                            <span class="info-box-number"><?php echo cut_text ("$EMPN_2A", 20); ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 50%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                <?php echo $APPROVED_2A; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        if($APPROVER_3A == 1)
                                        {
                                            ?>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-<?php echo $boxCol_3A; ?>">
                                                        <span class="info-box-icon"><i class="<?php echo $class3A; ?>"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text"><?php echo $Approver3A; ?></span>
                                                            <span class="info-box-number"><?php echo cut_text ("$EMPN_3A", 20); ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 50%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                <?php echo $APPROVED_3A; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        if($APPROVER_4A == 1)
                                        {
                                            ?>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-<?php echo $boxCol_4A; ?>">
                                                        <span class="info-box-icon"><i class="<?php echo $class4A; ?>"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text"><?php echo $Approver4A; ?></span>
                                                            <span class="info-box-number"><?php echo cut_text ("$EMPN_4A", 20); ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 50%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                <?php echo $APPROVED_4A; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        if($APPROVER_5A == 1)
                                        {
                                            ?>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-<?php echo $boxCol_5A; ?>">
                                                        <span class="info-box-icon"><i class="<?php echo $class5A; ?>"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text"><?php echo $Approver5A; ?></span>
                                                            <span class="info-box-number"><?php echo cut_text ("$EMPN_5A", 20); ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 50%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                <?php echo $APPROVED_5A; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                        $DefID      = $this->session->userdata['Emp_ID'];
                        $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                </div>
            </div>
        </section>
    </body>
</html>

<script>
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
    $('#datepicker1').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker2').datepicker({
      autoclose: true
    });

    //Date picker
    $('#datepicker3').datepicker({
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
</script>

<script>  
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
    ?>
  
    var decFormat       = 2;

    function add_header(strItem) 
    {
        arrItem     = strItem.split('|');
        CCAL_NUM    = arrItem[0];
        /*CCAL_CODE = arrItem[1];
        BOM_NUM     = arrItem[2];
        BOM_CODE    = arrItem[3];
        CUST_CODE   = arrItem[4];*/
        CUST_CODE   = document.getElementById("CUST_CODE").value;

        document.getElementById("CCAL_NUMX").value  = CCAL_NUM;
        /*document.getElementById("CCAL_CODEX").value = CCAL_CODE;
        document.getElementById("BOM_NUMX").value   = BOM_NUM;
        document.getElementById("BOM_CODEX").value  = BOM_CODE;*/
        document.getElementById("CUST_CODEX").value = CUST_CODE;
        document.frmsrch.submitSrch.click();
    }
    
    function chgQty(thisVal, theRow)
    {
        var OFF_VOLM    = eval(thisVal).value.split(",").join("");
        
        if(OFF_VOLM == 0)
        {
            swal("<?php echo $alert4; ?>",
            {
                icon: "warning",
            });
            var OFF_VOLM    = 1;
            document.getElementById('OFF_VOLM'+theRow).focus();
        }
        
        document.getElementById('data'+theRow+'OFF_VOLM').value         = parseFloat(Math.abs(OFF_VOLM));
        document.getElementById('OFF_VOLM'+theRow).value                = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_VOLM)),decFormat));
        
        var OFF_PRICE   = document.getElementById('data'+theRow+'OFF_PRICE').value;
        var OFF_DISC    = document.getElementById('OFF_DISC'+theRow).value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;
        var TAXPRICE1   = document.getElementById('data'+theRow+'TAXPRICE1').value;
        ITM_TOTAL1      = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL1;
        
        ITM_TOTAL       = 0;
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) +  parseFloat(TAXPRICE1);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) -  parseFloat(TAXPRICE1);
        }
        else
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC);
        }
        
        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
        
        CalDiscP(thisVal, theRow);
    }
    
    function chgPrc(thisVal, theRow)
    {
        var OFF_PRICE   = eval(thisVal).value.split(",").join("");

        if(OFF_PRICE == 0)
        {
            swal("<?php echo $alert5; ?>",
            {
                icon: "warning",
            });
            var OFF_PRICE   = 1;
            document.getElementById('OFF_PRICE'+theRow).focus();
        }
        
        document.getElementById('data'+theRow+'OFF_PRICE').value        = parseFloat(Math.abs(OFF_PRICE));
        document.getElementById('OFF_PRICE'+theRow).value               = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_PRICE)),decFormat));
        
        var OFF_VOLM    = document.getElementById('data'+theRow+'OFF_VOLM').value;
        var OFF_DISC    = document.getElementById('OFF_DISC'+theRow).value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;
        var TAXPRICE1   = document.getElementById('data'+theRow+'TAXPRICE1').value;
        ITM_TOTAL1      = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL1;
        
        ITM_TOTAL       = 0;
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) +  parseFloat(TAXPRICE1);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) -  parseFloat(TAXPRICE1);
        }
        else
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC);
        }
        
        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
        
        CalDiscP(thisVal, theRow);
    }
    
    function chgDiscP(thisVal, theRow)
    {
        var OFF_DISCP   = eval(thisVal).value.split(",").join("");
        
        document.getElementById('data'+theRow+'OFF_DISCP').value        = parseFloat(Math.abs(OFF_DISCP));
        document.getElementById('OFF_DISCP'+theRow).value               = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISCP)),decFormat));
        
        var OFF_VOLM    = document.getElementById('data'+theRow+'OFF_VOLM').value;
        var OFF_PRICE   = document.getElementById('data'+theRow+'OFF_PRICE').value;
        var OFF_DISC    = document.getElementById('OFF_DISC'+theRow).value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;
        var TAXPRICE1   = document.getElementById('data'+theRow+'TAXPRICE1').value;
        
        ITM_TOTAL1      = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        OFF_DISC        = parseFloat(OFF_DISCP * ITM_TOTAL1 / 100);
        
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL1;
        
        document.getElementById('data'+theRow+'OFF_DISC').value         = parseFloat(Math.abs(OFF_DISC));
        document.getElementById('OFF_DISC'+theRow).value                = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISC)),decFormat));
        
        ITM_TOTAL       = 0;
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) +  parseFloat(TAXPRICE1);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) -  parseFloat(TAXPRICE1);
        }
        else
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC);
        }
        
        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
    }
    
    function chgDisc(thisVal, theRow)
    {
        var OFF_DISC    = eval(thisVal).value.split(",").join("");
        
        document.getElementById('data'+theRow+'OFF_DISC').value         = parseFloat(Math.abs(OFF_DISC));
        document.getElementById('OFF_DISC'+theRow).value                = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISC)),decFormat));
        
        var OFF_VOLM    = document.getElementById('data'+theRow+'OFF_VOLM').value;
        var OFF_PRICE   = document.getElementById('data'+theRow+'OFF_PRICE').value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;
        var TAXPRICE1   = document.getElementById('data'+theRow+'TAXPRICE1').value;
        
        ITM_TOTAL1      = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        OFF_DISCP       = parseFloat(OFF_DISC) * 100 / parseFloat(ITM_TOTAL1);
        
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL1;
        
        document.getElementById('data'+theRow+'OFF_DISCP').value        = parseFloat(Math.abs(OFF_DISCP));
        document.getElementById('OFF_DISCP'+theRow).value               = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISCP)),decFormat));
        
        ITM_TOTAL       = 0;
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) +  parseFloat(TAXPRICE1);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) -  parseFloat(TAXPRICE1);
        }
        else
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC);
        }
        
        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
    }
    
    function CalDiscP(thisVal, theRow)
    {
        var OFF_DISCP   = document.getElementById('data'+theRow+'OFF_DISCP').value;     
        var OFF_VOLM    = document.getElementById('data'+theRow+'OFF_VOLM').value;
        var OFF_PRICE   = document.getElementById('data'+theRow+'OFF_PRICE').value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;
        var TAXPRICE1   = document.getElementById('data'+theRow+'TAXPRICE1').value;
        
        ITM_TOTAL1      = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        OFF_DISC        = parseFloat(OFF_DISCP * ITM_TOTAL1 / 100);
        
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL1;
        
        document.getElementById('data'+theRow+'OFF_DISC').value         = parseFloat(Math.abs(OFF_DISC));
        document.getElementById('OFF_DISC'+theRow).value                = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(OFF_DISC)),decFormat));
        
        ITM_TOTAL       = 0;
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) +  parseFloat(TAXPRICE1);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC) -  parseFloat(TAXPRICE1);
        }
        else
        {
            var ITM_TOTAL   = parseFloat(ITM_TOTAL1) -  parseFloat(OFF_DISC);
        }
        
        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
        
        chgTax(thisVal, theRow);
    }
    
    function chgTax(thisVal, theRow)
    {
        var OFF_VOLM    = document.getElementById('data'+theRow+'OFF_VOLM').value;
        var OFF_PRICE   = document.getElementById('data'+theRow+'OFF_PRICE').value;
        var OFF_DISC    = document.getElementById('data'+theRow+'OFF_DISC').value;
        var TAXCODE1    = document.getElementById('data'+theRow+'TAXCODE1').value;

        ITM_TOTAL       = parseFloat(OFF_PRICE) * parseFloat(OFF_VOLM);
        document.getElementById('data'+theRow+'OFF_COST').value         = ITM_TOTAL;
        
        if(TAXCODE1 == 'TAX01')
        {
            var ITM_TOTAL0  = parseFloat(ITM_TOTAL) -  parseFloat(OFF_DISC);
            var ITM_TAX     = 0.1 * parseFloat(ITM_TOTAL0);
            document.getElementById('data'+theRow+'TAXPRICE1').value    = ITM_TAX;
            
            var ITM_TOTAL   = parseFloat(ITM_TOTAL0) +  parseFloat(ITM_TAX);
        }
        else if(TAXCODE1 == 'TAX02')
        {
            var ITM_TOTAL0  = parseFloat(ITM_TOTAL) -  parseFloat(OFF_DISC);
            var ITM_TAX     = 0.03 * parseFloat(ITM_TOTAL0);
            document.getElementById('data'+theRow+'TAXPRICE1').value    = ITM_TAX;
            
            var ITM_TOTAL   = parseFloat(ITM_TOTAL0) -  parseFloat(ITM_TAX);
        }
        else
        {
            var ITM_TOTAL0  = parseFloat(ITM_TOTAL) -  parseFloat(OFF_DISC);
            var ITM_TOTAL   = parseFloat(ITM_TOTAL0);
        }

        document.getElementById('data'+theRow+'OFF_TOTCOST').value      = parseFloat(Math.abs(ITM_TOTAL));
        document.getElementById('OFF_TOTCOST'+theRow).value             = doDecimalFormat(RoundNDecimal(parseFloat(Math.abs(ITM_TOTAL)),decFormat));
    }
    
    function checkInp()
    {
        totRow  = document.getElementById('totalrow').value;
        CUST_CODE   = document.getElementById('CUST_CODE').value;
        
        if(CUST_CODE == '')
        {
            swal("<?php echo $alert2; ?>",
            {
                icon: "warning",
            });
            document.getElementById('CUST_CODE').focus();
            return false;
        }   
        
        BOM_CODE1   = document.getElementById('BOM_CODE1').value;
        
        if(BOM_CODE1 == '')
        {
            swal("<?php echo $alert3; ?>",
            {
                icon: "warning",
            });
            document.getElementById('BOM_CODE1').focus();
            return false;
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
    
    function deleteRow(btn)
    {
        var row = document.getElementById("tr_" + btn);
        row.remove();
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