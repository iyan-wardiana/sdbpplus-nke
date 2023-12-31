<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 30 Agustus 2017
 * File Name    = v_task_request_form.php
 * Location     = -
*/
date_default_timezone_set("Asia/Jakarta");

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$FlagUSER   = $this->session->userdata['FlagUSER'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$ISREAD     = $this->session->userdata['ISREAD'];
$ISCREATE   = $this->session->userdata['ISCREATE'];
$ISAPPROVE  = $this->session->userdata['ISAPPROVE'];

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat  = 2;

$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$comp_init  = $this->session->userdata('comp_init');
$DOCNKE     = strtoupper($comp_init);

if($task == 'add')
{
    $PATT_YEAR      = date('Y');
    $PATT_MONTH1    = date('m');
    $PATT_MONTH     = (int)$PATT_MONTH1;
    $PATT_DAYS      = date('d');
    $DOCTIME        = date('ymd');
    
    $sql = "SELECT MAX(Patt_Number) as maxNumber 
                FROM tbl_task_request WHERE Patt_Year = $PATT_YEAR";
    $result = $this->db->query($sql)->result();
    
    foreach($result as $row) :
        $myMax = $row->maxNumber;
        $myMax = $myMax+1;
    endforeach; 
        
    $lastPatternNumb = $myMax;
    $lastPatternNumb1 = $myMax;
    $len = strlen($lastPatternNumb);
    $nol = '';
    $Pattern_Length = 5;
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
    $lastPatternNumb    = $nol.$lastPatternNumb;
    
    $theTime        = date('ymdHis');
    //$DocNumber      = "$comp_init.$PATT_YEAR$PATT_MONTH$PATT_DAYS$lastPatternNumb";
    $DocNumber      = "$DOCNKE.TR-$DOCTIME.$lastPatternNumb";
    $TASK_CODE      = $DocNumber;
    $TASK_DATE      = date('m/d/Y');
    $TASK_TITLE     = '';
    $TASK_CATEG     = 0;
    $TASK_MENU      = '';
    $TASK_CONTENT   = '';
    $TASK_AUTHOR    = '';
    $TASK_REQUESTER = $DefEmp_ID;
    $TASK_STAT      = 1;
    $Patt_Number    = $myMax;

    $PRJCODE        = "";
    $TASK_NOREF     = "";
}
else
{
    $TASK_CODE      = $default['TASK_CODE'];
    $TASK_DATE      = $default['TASK_DATE'];
    $TASK_TITLE     = $default['TASK_TITLE'];
    $TASK_CATEG     = $default['TASK_CATEG'];
    $TASK_MENU      = $default['TASK_MENU'];
    $TASK_CONTENT   = $default['TASK_CONTENT'];
    $TASK_AUTHOR    = $default['TASK_AUTHOR'];      
    $TASK_REQUESTER = $default['TASK_REQUESTER'];
    $TASK_STAT      = $default['TASK_STAT'];
    $PRJCODE        = $default['PRJCODE'];
    $Patt_Number    = $default['Patt_Number'];
}

$isHelper       = 0;
$sqlOpen        = "SELECT isHelper FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
$sqlOpen        = $this->db->query($sqlOpen)->result();
foreach($sqlOpen as $rowOpen) :
    $isHelper   = $rowOpen->isHelper;
endforeach;
$appName        = $this->session->userdata('appName');
$urlLock        = site_url('Auth/lockSystem/?id='.$this->url_encryption_helper->encode_url($appName));
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
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css';?>">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
    <?php
        $this->load->view('template/mna');
        //______$this->load->view('template/topbar');
        //______$this->load->view('template/sidebar');
        
        $ISREAD     = $this->session->userdata['ISREAD'];
        $ISCREATE   = $this->session->userdata['ISCREATE'];
        $ISAPPROVE  = $this->session->userdata['ISAPPROVE'];
        $ISDWONL    = $this->session->userdata['ISDWONL'];

        $LangID         = $this->session->userdata['LangID'];
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
            if($TranslCode == 'Code')$Code = $LangTransl;
            if($TranslCode == 'Date')$Date = $LangTransl;
            if($TranslCode == 'Select')$Select = $LangTransl;
            if($TranslCode == 'Close')$Close = $LangTransl;
            if($TranslCode == 'Consultant')$Consultant = $LangTransl;
            if($TranslCode == 'Type')$Type = $LangTransl;
            if($TranslCode == 'Title')$Title = $LangTransl;
            if($TranslCode == 'Description')$Description = $LangTransl;
            if($TranslCode == 'Status')$Status = $LangTransl;
            if($TranslCode == 'Category')$Category = $LangTransl;
            if($TranslCode == 'catHelp')$catHelp = $LangTransl;
            if($TranslCode == 'consultEmpty')$consultEmpty = $LangTransl;
            if($TranslCode == 'titleEmpty')$titleEmpty = $LangTransl;
            if($TranslCode == 'contEmpty')$contEmpty = $LangTransl;
        endforeach;

        if($LangID == 'IND')
        {
            $menuHelp   = "Anda belum memilih menu yang ingin ditanyakan.";
            $selPRJCODE = "Anda belum memilih kode proyek.";
        }
        else
        {
            $menuHelp   = "You have not select a task request type menu";
            $selPRJCODE = "You have not select a project code";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $h1_title; ?>
                <small><?php echo $mnName; ?></small>
            </h1>
        </section>

        <section class="content">   
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border" style="display:none">               
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body chart-responsive">
                            <form class="form-horizontal" name="news_form" method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data" onSubmit="return validateData()">
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Code ?> </label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="TASK_CODE" id="TASK_CODE" value="<?php echo $TASK_CODE; ?>" style="display:none"/>
                                        <input type="text" class="form-control" name="TASK_CODE1" id="TASK_CODE1" value="<?php echo $TASK_CODE; ?>" disabled />
                                        <input type="hidden" name="TASK_REQUESTER" id="TASK_REQUESTER" value="<?php echo $TASK_REQUESTER; ?>" />
                                        <input type="hidden" name="Patt_Number" id="Patt_Number" value="<?php echo $Patt_Number; ?>" />
                                        <input type="hidden" name="TASK_DATE" class="form-control pull-left" id="datepicker1" value="<?php echo $TASK_DATE; ?>" style="width:150px">
                                        <input type="hidden" name="isTask" id="isTask" value="<?php echo $task; ?>" />
                                    </div>
                                    <label for="inputEmail" class="col-sm-1 control-label"><?php echo $Consultant ?> </label>
                                    <div class="col-sm-2">
                                        <select name="TASK_AUTHOR[]" id="TASK_AUTHOR" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;konsultan / teman">
                                            <?php
                                                $sqlSEND    = "SELECT A.Followings AS EMP_ID, B.First_Name, B.Last_Name
                                                                FROM tbl_employee_circle A
                                                                    INNER JOIN tbl_employee B ON A.Followings = B.Emp_ID
                                                                WHERE A.Emp_ID  = '$DefEmp_ID' ORDER BY First_Name";
                                                $resSEND    = $this->db->query($sqlSEND)->result();
                                                $theRow = 0;
                                                foreach($resSEND as $rowSEND) :
                                                    $Emp_ID         = $rowSEND->EMP_ID;
                                                    $First_Name1    = $rowSEND->First_Name;
                                                    $Last_Name1     = $rowSEND->Last_Name;
                                                    $Last_Name1     = " $Last_Name1";
                                                    $COMPLETE_NM1   = "$First_Name1$Last_Name1";
                                                    
                                                /*$sqlEmp   = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                            FROM tbl_employee 
                                                            WHERE isHelper = 1
                                                            ORDER BY First_Name";
                                                $sqlEmp = $this->db->query($sqlEmp)->result();
                                                foreach($sqlEmp as $row) :
                                                    $Emp_ID     = $row->Emp_ID;
                                                    $First_Name = $row->First_Name;
                                                    $Last_Name  = $row->Last_Name;
                                                    $Email      = $row->Email;*/
                                                    ?>
                                                        <option value="<?php echo "$Emp_ID";?>">
                                                            <?php echo $COMPLETE_NM1; ?>
                                                        </option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <label for="inputEmail" class="col-sm-1 control-label">Kategori</label>
                                    <div class="col-sm-3">
                                        <select name="TASK_CATEG" id="TASK_CATEG" class="form-control select2" >
                                            <option value="0"> --- </option>
                                            <option value="1" <?php if($TASK_CATEG == 1) { ?> selected <?php } ?>> Otorisasi </option>
                                            <option value="2" <?php if($TASK_CATEG == 2) { ?> selected <?php } ?>> Misinformasi </option>
                                            <option value="3" <?php if($TASK_CATEG == 3) { ?> selected <?php } ?>> Bug/Error </option>
                                            <option value="4" <?php if($TASK_CATEG == 4) { ?> selected <?php } ?>> Perubahan Data </option>
                                            <option value="5" <?php if($TASK_CATEG == 5) { ?> selected <?php } ?>> Penghapusan Data </option>
                                            <option value="6" <?php if($TASK_CATEG == 6) { ?> selected <?php } ?>> Perubahan Form / Laporan </option>
                                            <option value="7" <?php if($TASK_CATEG == 7) { ?> selected <?php } ?>> Penambahan Form / Laporan </option>
                                            <option value="8" <?php if($TASK_CATEG == 8) { ?> selected <?php } ?>> Pertanyaan </option>
                                            <option value="9" <?php if($TASK_CATEG == 9) { ?> selected <?php } ?>> Lainnya </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Menu</label>
                                    <div class="col-sm-3">
                                        <select name="TASK_MENU" id="TASK_MENU" class="form-control select2" onChange="chgDOKCAT()" >
                                            <option value="" > --- </option>
                                            <?php
                                            if($MenuParentC>0)
                                            {
                                                foreach($MenuParent as $rowMP1) :
                                                    $menu_code1     = $rowMP1->menu_code;
                                                    if($LangID == 'IND')
                                                    {
                                                        $menu_name1 = $rowMP1->menu_name_IND;
                                                    }
                                                    else
                                                    {
                                                        $menu_name1 = $rowMP1->menu_name_ENG;
                                                    }
                                                    $level_menu1    = $rowMP1->level_menu;
                                                    $parent_code1   = $rowMP1->parent_code;
                                                    $level_menuD1   = "";
                                                    
                                                    $sqlC1      = "tbl_menu WHERE parent_code = '$menu_code1'";
                                                    $ressqlC1   = $this->db->count_all($sqlC1);
                                                    ?>
                                                    <option value="<?php echo $menu_code1;?>" <?php if($menu_code1 == $TASK_MENU) { ?>selected<?php } if($ressqlC1>0) {?> disabled <?php } ?>><?php echo "$level_menuD1$menu_name1";?></option>
                                                    <?php
                                                        if($ressqlC1 > 0)
                                                        {
                                                            if($LangID == 'IND')
                                                            {
                                                                $sqlMP2     = "SELECT menu_code, menu_name_IND AS menu_name, level_menu, parent_code
                                                                                FROM tbl_menu
                                                                                WHERE parent_code = '$menu_code1' AND isActive = 1 AND menu_user = 1
                                                                                    AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefEmp_ID')
                                                                                ORDER BY no_urut";
                                                            }
                                                            else
                                                            {
                                                                $sqlMP2     = "SELECT menu_code, menu_name_ENG AS menu_name, level_menu, parent_code
                                                                                FROM tbl_menu
                                                                                WHERE parent_code = '$menu_code1' AND isActive = 1 AND menu_user = 1
                                                                                    AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefEmp_ID')
                                                                                ORDER BY no_urut";
                                                            }
                                                            $resMP2     = $this->db->query($sqlMP2)->result();
                                                            foreach($resMP2 as $rowMP2) :
                                                                $menu_code2     = $rowMP2->menu_code;
                                                                $menu_name2     = $rowMP2->menu_name;
                                                                $level_menu2    = $rowMP2->level_menu;
                                                                $parent_code2   = $rowMP2->parent_code;
                                                                $level_menuD2   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                            
                                                                $sqlC2          = "tbl_menu WHERE parent_code = '$menu_code2'";
                                                                $ressqlC2       = $this->db->count_all($sqlC2);
                                                                ?>
                                                                <option value="<?php echo $menu_code2;?>" <?php if($menu_code2 == $TASK_MENU) { ?>selected<?php } if($ressqlC2>0) {?> disabled <?php } ?>><?php echo "$level_menuD2$menu_name2";?></option>
                                                                <?php
                                                                    if($ressqlC2 > 0)
                                                                    {
                                                                        if($LangID == 'IND')
                                                                        {
                                                                            $sqlMP3     = "SELECT menu_code, menu_name_IND AS menu_name, level_menu, parent_code
                                                                                            FROM tbl_menu
                                                                                            WHERE parent_code = '$menu_code2' AND isActive = 1 AND menu_user = 1
                                                                                                AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefEmp_ID')
                                                                                            ORDER BY no_urut";
                                                                        }
                                                                        else
                                                                        {
                                                                            $sqlMP3     = "SELECT menu_code, menu_name_ENG AS menu_name, level_menu, parent_code
                                                                                            FROM tbl_menu
                                                                                            WHERE parent_code = '$menu_code2' AND isActive = 1 AND menu_user = 1
                                                                                                AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefEmp_ID')
                                                                                            ORDER BY no_urut";
                                                                        }
                                                                        $resMP3     = $this->db->query($sqlMP3)->result();
                                                                        foreach($resMP3 as $rowMP3) :
                                                                            $menu_code3     = $rowMP3->menu_code;
                                                                            $menu_name3     = $rowMP3->menu_name;
                                                                            $level_menu3    = $rowMP3->level_menu;
                                                                            $parent_code3   = $rowMP3->parent_code;
                                                                            $level_menuD3   = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                        
                                                                            $sqlC3          = "tbl_menu WHERE parent_code = '$menu_code3'";
                                                                            $ressqlC3       = $this->db->count_all($sqlC3);
                                                                        ?>
                                                                            <option value="<?php echo $menu_code3;?>" <?php if($menu_code3 == $TASK_MENU) { ?>selected<?php } ?>><?php echo "$level_menuD3$menu_name3";?></option>
                                                                        <?php
                                                                        endforeach;
                                                                    }
                                                            endforeach;
                                                        }
                                                endforeach;
                                            }
                                            ?>
                                            <option value="MNLKH" <?php if($TASK_MENU == 'MNLKH') { ?>selected<?php } ?>> Lap. Kerja Harian </option>
                                        </select>
                                    </div>
                                    <label for="inputName" class="col-sm-1 control-label">Proyek</label>
                                    <div class="col-sm-6">
                                        <select name="PRJCODE" id="PRJCODE" class="form-control select2" onChange="chgDOKCAT()" >
                                          <option value=""> --- </option>
                                            <?php
                                                $s_PRJ     = "SELECT PRJCODE, PRJNAME FROM tbl_project ORDER BY PRJCODE";
                                                $s_PRJ     = $this->db->query($s_PRJ)->result();
                                                foreach($s_PRJ as $rw_PRJ) :
                                                    $PRJCODE2 = $rw_PRJ->PRJCODE;
                                                    $PRJNAME2 = $rw_PRJ->PRJNAME;
                                                    ?>
                                                        <option value="<?php echo $PRJCODE2; ?>" <?php if($PRJCODE2 == $PRJCODE) { ?> selected <?php } ?>><?php echo "$PRJCODE2 - $PRJNAME2"; ?></option>
                                                  <?php
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                    if($FlagUSER == 'SUPERADMINX')
                                    {
                                        ?>
                                        <div class="form-group">
                                            <label for="inputName" class="col-sm-2 control-label"><?php echo $Type ?> </label>
                                            <div class="col-sm-10">
                                                <select name="TASK_TYPE" id="TASK_TYPE" class="form-control" onChange="selType(this.value)" >
                                                    <option value="0" style="font-style:italic" selected > --- type ---</option>
                                                    <option value="1" >All</option>
                                                    <option value="2" >By Employee</option>
                                                </select>
                                            </div>
                                        </div>
                                        <script>
                                            function selType(thisVal)
                                            {
                                                TASK_TYPE = document.getElementById('TASK_TYPE').value;
                                                if(thisVal == 1)
                                                    document.getElementById('UserReceiver').style.display = 'none';
                                                else
                                                    document.getElementById('UserReceiver').style.display = '';
                                            }
                                        </script>
                                        <div class="form-group" id="UserReceiver" style="display:none">
                                            <label for="inputName" class="col-sm-2 control-label">User (only for superadmin)</label>
                                            <div class="col-sm-10">
                                                <select name="TASK_FOR[]" id="TASK_FOR" class="form-control select2" multiple="multiple" data-placeholder="&nbsp;&nbsp;&nbsp;Information For" style="width: 100%;">
                                                    <?php
                                                        $sqlEmp = "SELECT Emp_ID, First_Name, Last_Name, Email
                                                                    FROM tbl_employee WHERE Emp_ID != '$DefEmp_ID' ORDER BY First_Name";
                                                        $sqlEmp = $this->db->query($sqlEmp)->result();
                                                        foreach($sqlEmp as $row) :
                                                            $Emp_ID     = $row->Emp_ID;
                                                            $First_Name = $row->First_Name;
                                                            $Last_Name  = $row->Last_Name;
                                                            $Email      = $row->Email;
                                                            ?>
                                                                <option value="<?php echo "$Emp_ID|$Email"; ?>">
                                                                    <?php echo "$First_Name $Last_Name"; ?>
                                                                </option>
                                                            <?php
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">No. Referensi</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="hidden" class="form-control" name="TASK_NOREF" id="TASK_NOREF" style="max-width:160px" value="<?php echo $TASK_NOREF; ?>" >
                                            <input type="text" class="form-control" name="TASK_NOREF1" id="TASK_NOREF1" data-toggle="tooltip" value="<?php echo $TASK_NOREF; ?>" data-toggle="modal" data-target="#mdl_addDOK" disabled>
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdl_addDOK" title="Cari Dok. Referensi"><i class="glyphicon glyphicon-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="inputName" class="col-sm-1 control-label"><?php echo $Title ?> </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="TASK_TITLE" id="TASK_TITLE" value="<?php echo $TASK_TITLE; ?>" maxlength="40" placeholder="max 40 char" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Description ?> </label>
                                    <div class="col-sm-10">
                                        <textarea name="TASK_CONTENT" id="compose-textarea" class="form-control" style="height: 150px"><?php echo $TASK_CONTENT; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Title Picture</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="userfile" id="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label for="inputName" class="col-sm-2 control-label">Attach. Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label"><?php echo $Status ?> </label>
                                    <div class="col-sm-10">
                                        <select name="TASK_STAT" id="TASK_STAT" class="form-control select2" style="max-width:100px">
                                            <option value="1" <?php if($TASK_STAT == 1) { ?> selected <?php } ?>>New</option>
                                            <option value="2" <?php if($isHelper == 0) { ?> disabled <?php } if($TASK_STAT == 2) { ?> selected <?php } ?>>Process</option>
                                            <option value="3" <?php if($TASK_STAT == 3) { ?> selected <?php } ?>>Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Upload </label>
                                    <div class="col-sm-10">
                                        <input type="file" name="userfile" class="filestyle" data-buttonName="btn-primary"/>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <?php
                                            //if($ISCREATE == 1)
                                            //{
                                                if($task=='add')
                                                {
                                                    ?>
                                                        <button class="btn btn-primary" id="btnSave">
                                                            <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Save; ?>
                                                        </button>&nbsp;
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                        <button class="btn btn-primary" id="btnSave">
                                                            <i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $Update; ?>
                                                        </button>&nbsp;
                                                    <?php
                                                }
                                            //}
                                        
                                            echo anchor("$backURL",'<button class="btn btn-danger" type="button" id="btnBack"><i class="fa fa-reply"></i>&nbsp;&nbsp;'.$Back.'</button>');
                                        ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ START MODAL =============== -->
                <style type="text/css">
                    .modal-dialog{
                        position: relative;
                        display: table; /* This is important */ 
                        overflow-y: auto;    
                        overflow-x: auto;
                        width: auto;
                        min-width: 600px;   
                    }

                    .datatable td span{
                        width: 80%;
                        display: block;
                        overflow: hidden;
                    }
                </style>
                <?php
                    $Active1        = "active";
                    $Active2        = "";
                    $Active1Cls     = "class='active'";
                    $Active2Cls     = "";
                ?>
                <div class="modal fade" id="mdl_addDOK" name='mdl_addDOK' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-header">
                                            <ul class="nav nav-tabs">
                                                <li id="li1" <?php echo $Active1Cls; ?>>
                                                    <a href="#" data-toggle="tab">Dok. Referensi</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <form method="post" name="frmSearch1" id="frmSearch1" action="">
                                                    <table id="example" class="table table-bordered table-striped table-responsive search-table inner">
                                                        <thead>
                                                            <tr>
                                                                <th width="2px">&nbsp;</th>
                                                                <th width="10px"><?php echo $Date; ?></th>
                                                                <th width="500px" nowrap style="vertical-align: middle; text-align:center"><?php echo $Description; ?></th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <input type="hidden" name="rowCheck" id="rowCheck" value="0">
                                                    <button type="button" id="idClose" class="btn btn-danger" data-dismiss="modal">
                                                        <i class="glyphicon glyphicon-remove"></i>&nbsp;&nbsp;<?php echo $Close; ?>
                                                    </button>
                                                    <button class="btn btn-warning" type="button" id="idRefresh" title="Refresh" >
                                                        <i class="glyphicon glyphicon-refresh"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- ============ END MODAL =============== -->
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                //if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
    $(function () {
    //Add text editor
       $("#compose-textarea").wysihtml5();
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
            startDate: '-3d',
            endDate: '+0d'
        });

        //Date picker
        $('#datepicker1').datepicker({
          autoclose: true,
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

    function chgDOKCAT()
    {
        var PRJCODE     = document.getElementById('PRJCODE').value;
        var mnCode      = document.getElementById('TASK_MENU').value;
        var collData    = PRJCODE+'~'+mnCode;

        if(mnCode == 'MN468' || mnCode == 'MN017' || mnCode == 'MN019' || mnCode == 'MN067' || mnCode == 'MN189' || mnCode == 'MN238' || mnCode == 'MN241' || mnCode == 'MN338' || mnCode == 'MN009' || mnCode == 'MN045' || mnCode == 'MN147' || mnCode == 'MN359' || mnCode == 'MN144' || mnCode == 'MN165' || mnCode == 'MN347' || mnCode == 'MN406' || mnCode == 'MN037' || mnCode == 'MN225' || mnCode == 'MN111')
        {
            $('#example').DataTable(
            {
                "destroy": true,
                "processing": true,
                "serverSide": true,
                //"scrollX": false,
                "autoWidth": true,
                "filter": true,
                "ajax": "<?php echo site_url('c_help/c_t180c2hr/getDoc/?id=')?>"+collData,
                "type": "POST",
                //"lengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
                "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
                "columnDefs": [ { targets: [0], className: 'dt-body-center' },
                                { "width": "5px", "targets": [0] }
                              ],
                "language": {
                    "infoFiltered":"",
                    "processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
                },
            });
        }

        $("#idRefresh").click(function()
        {
            $('#example').DataTable().ajax.reload();
        });
    }

    function add_doc(strItem) 
    {
        document.getElementById("TASK_NOREF").value     = strItem;
        document.getElementById("TASK_NOREF1").value    = strItem;
        document.getElementById("idClose").click();
    }
</script>

<script>
    function validateData()
    {
        var TASK_AUTHOR = document.getElementById('TASK_AUTHOR').value;
        var TASK_TITLE  = document.getElementById('TASK_TITLE').value;
        var TASK_CATEG  = document.getElementById('TASK_CATEG').value;
        var TASK_MENU   = document.getElementById('TASK_MENU').value;
        var PRJCODE     = document.getElementById('PRJCODE').value;
        var TASK_NOREF  = document.getElementById('TASK_NOREF').value;
        var TASK_CONTENT= document.getElementById('compose-textarea').value;
        
        if(TASK_AUTHOR == '')
        {
            swal('<?php echo $consultEmpty; ?>',
            {
                icon: "warning",
            });
            document.getElementById('TASK_AUTHOR').focus();
            return false;
        }
                
        if(TASK_CATEG == '0')
        {
            swal('<?php echo $catHelp; ?>',
            {
                icon: "warning",
            });
            document.getElementById('TASK_CATEG').focus();
            return false;
        }
                
        if(TASK_MENU == '')
        {
            swal('<?php echo $menuHelp; ?>',
            {
                icon: "warning",
            });
            document.getElementById('TASK_MENU').focus();
            return false;
        }
                
        if(PRJCODE == '')
        {
            swal('<?php echo $selPRJCODE; ?>',
            {
                icon: "warning",
            });
            document.getElementById('PRJCODE').focus();
            return false;
        }
                
        if(TASK_NOREF == '' && (TASK_CATEG == '4' || TASK_CATEG == '5'))
        {
            swal('No. referensi tidak boleh kosong',
            {
                icon: "warning",
            });
            document.getElementById('TASK_NOREF1').focus();
            return false;
        }
        
        if(TASK_TITLE == '')
        {
            swal('<?php echo $titleEmpty; ?>',
            {
                icon: "warning",
            });
            document.getElementById('TASK_TITLE').focus();
            return false;
        }
        
        if(TASK_CONTENT == '')
        {
            swal('<?php echo $contEmpty; ?>',
            {
                icon: "warning",
            });
            document.getElementById('compose-textarea').focus();
            return false;
        }

        var variable = document.getElementById('btnSave');
		if (typeof variable !== 'undefined' && variable !== null)
		{
			document.getElementById('btnSave').style.display 	= 'none';
			document.getElementById('btnBack').style.display 	= 'none';
		}
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
<script src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js';?>"></script>