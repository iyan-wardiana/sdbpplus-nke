<?php
/* 
 * Author       = Dian Hermanto
 * Create Date  = 22 September 2022
 * File Name    = v_task_print.php
 * Location     = -
*/

$this->load->view('template/head');

$appName    = $this->session->userdata('appName');
$sysMnt     = $this->session->userdata['sysMnt'];
$LastMntD   = $this->session->userdata['LastMntD'];
$appBody    = $this->session->userdata['appBody'];

$tgl1 = new DateTime($LastMntD);
$tgl2 = new DateTime();
 
$dif1 = $tgl1->diff($tgl2);
$dif2 = $dif1->days;

$LangID     = $this->session->userdata['LangID'];

$sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
$resTransl      = $this->db->query($sqlTransl)->result();
foreach($resTransl as $rowTransl) :
    $TranslCode = $rowTransl->MLANG_CODE;
    $LangTransl = $rowTransl->LangTransl;
    
    if($TranslCode == 'Add')$Add = $LangTransl;
    if($TranslCode == 'Edit')$Edit = $LangTransl;
    if($TranslCode == 'Code')$Code = $LangTransl;
    if($TranslCode == 'Date')$Date = $LangTransl;
    if($TranslCode == 'Title')$Title = $LangTransl;
    if($TranslCode == 'Description')$Description = $LangTransl;
    if($TranslCode == 'Sender')$Sender = $LangTransl;
    if($TranslCode == 'Progress')$Progress = $LangTransl;
    if($TranslCode == 'Status')$Status = $LangTransl;
    if($TranslCode == 'Warning')$Warning = $LangTransl;
    if($TranslCode == 'Category')$Category = $LangTransl;
endforeach;

if($LangID == 'IND')
{
    $mntWarn1   = "Layanan '1stWeb Assistance' akan segera berakhir pada tanggal : ";
    $mntWarn2   = "Silahkan hubungi kami agar tetap mendapatkan layanan '1stWeb Assistance'.";
    $mntWarn3   = "Layanan '1stWeb Assistance' sudah berakhir per ";
    $mntWarn4   = "Mengapa saya melihat ini?";
}
else
{
    $mntWarn1   = "Sorry, '1stWeb Assistance' services will be finished on : ";
    $mntWarn2   = "Please contact us to get '1stWeb Assistance' services.";
    $mntWarn3   = "Sorry, we have finished '1stWeb Assistance' services per ";
    $mntWarn4   = "Why did I see this message?";
}
//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

// _global function
$this->db->select('Display_Rows,decFormat');
$resGlobal = $this->db->get('tglobalsetting')->result();
foreach($resGlobal as $row) :
    $Display_Rows = $row->Display_Rows;
    $decFormat = $row->decFormat;
endforeach;
$decFormat      = 2;

$TASK_REQUESTER = "";
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
$sqlMG      = "SELECT A.TASK_CODE, A.TASK_DATE, A.TASK_TITLE, A.TASK_AUTHOR, A.TASK_REQUESTER, A.TASK_STAT, A.TASK_CREATED,
                    B.First_Name, B.Last_Name
                FROM tbl_task_request A
                    INNER JOIN tbl_employee B ON A.TASK_REQUESTER = B.Emp_ID
                WHERE A.TASK_CODE = '$TASK_CODE'";
$sqlMG      = $this->db->query($sqlMG)->result();
foreach($sqlMG as $rowMG) :
    $TASK_CODE      = $rowMG->TASK_CODE;
    $TASK_DATE      = $rowMG->TASK_DATE;
    $TASK_TITLE     = $rowMG->TASK_TITLE;
    $TASK_AUTHOR    = $rowMG->TASK_AUTHOR;
    $TASK_REQUESTER = $rowMG->TASK_REQUESTER;
    $TASK_STAT      = $rowMG->TASK_STAT;
    $TASK_CREATED   = $rowMG->TASK_CREATED;
    $DATED          = date('F j, Y', strtotime($TASK_CREATED));
    $DATEDT         = date('g:i a', strtotime($TASK_CREATED));
    $First_Name     = ucfirst($rowMG->First_Name);
    $Last_Name      = ucfirst($rowMG->Last_Name);
    $compName1      = "$First_Name $Last_Name";
    $compName       = ucfirst($compName1);
endforeach;

if($DefEmp_ID == $TASK_REQUESTER)
{
    // Karena $TASK_AUTHOR = "All", maka cari salah  satu author dari detail
    $getC1  = "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER'";
    $resC1  = $this->db->count_all($getC1);
    if($resC1 > 0)
    {
        $getID1     = "SELECT TASKD_EMPID
                        FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER' LIMIT 1";
        $resID1     = $this->db->query($getID1)->result();
        foreach($resID1 as $rowID1) :
            $TASKD_EMPID2   = $rowID1->TASKD_EMPID;
        endforeach;
    }
    else
    {
        $getAuthID  = "SELECT Emp_ID FROM tbl_employee WHERE isHelper = 1";
        $resAuthID  = $this->db->query($getAuthID)->result();
        foreach($resAuthID as $rowAuthID) :
            $Emp_ID     = $rowAuthID->Emp_ID;
        endforeach;
        $TASKD_EMPID2   = $Emp_ID;
    }
}
else
{
    $TASKD_EMPID2   = $TASK_REQUESTER;
}
// START : GET USER AKTIF PHOTO
    $imgemp_fnReq   = 'username';
    $imgemp_fnReqX  = '';
    $getIMGReq      = "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$TASK_REQUESTER'";
    $resIMGReq      = $this->db->query($getIMGReq)->result();
    foreach($resIMGReq as $rowIMGReq) :
        $imgemp_fnReq   = $rowIMGReq ->imgemp_filename;
        $imgemp_fnReqX = $rowIMGReq ->imgemp_filenameX;
    endforeach;
    $imgReqer       = base_url('assets/AdminLTE-2.0.5/emp_image/'.$DefEmp_ID.'/'.$imgemp_fnReqX);
    if($imgemp_fnReq == 'username')
        $imgReqer   = base_url('assets/AdminLTE-2.0.5/emp_image/'.$imgemp_fnReqX);
// END : GET REQUESTER PHOTO
                            
function cleanURL($textURL)
{
    $URL = strtolower(preg_replace( array('/[^a-z0-9\- ]/i', '/[ \-]+/'), array('', '-'), $textURL));
    return $URL;
}

// UPDATE STATUS DETAIL
/*$getIDUPD = "SELECT TASKD_EMPID
                FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE'";
$resIDUPD   = $this->db->query($getIDUPD)->result();
foreach($resIDUPD as $rowIDUPD) :
    $TASKD_EMPID2   = $rowIDUPD->TASKD_EMPID;
endforeach;*/
$sql = "UPDATE tbl_task_request_detail SET TASKD_RSTAT = '2' WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID2 LIKE '%$DefEmp_ID%'";
$this->db->query($sql);

$secBack    = site_url('c_help/c_t180c2hr/?id='.$this->url_encryption_helper->encode_url($appName));
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

            if($TranslCode == 'Back')$Back = $LangTransl;
        endforeach;
    ?>
    
    <style type="text/css">
        a:link {
            color: #000000;
        }

        /* visited link */
        a:visited {
            color: green;
        }

        /* mouse over link */
        a:hover {
            color: yellow;
        }

        /* selected link */
        a:active {
            color: blue;
        }
    </style>
    <?php
        $TASK_T     = "";
        $s_00       = "SELECT TASKD_TITLE FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE'";
        $r_00       = $this->db->query($s_00)->result();
        foreach($r_00 as $rw_00):
            $TASK_T = $rw_00->TASKD_TITLE;
        endforeach;
        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content">
            <div id="Layer1">
                <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();" class="btn btn-xs btn-default"><i class="fa fa-print"></i> Print</a>
            </div>
            <h5>
                <?php echo "Kode  : $TASK_CODE<br>
                            Judul : $TASK_T"; ?>
            </h5>
            <div class="box box-success">
                <div class="box-body chat" id="chat-box">
                    <div class="item">
                        <div class="direct-chat-success">
                            <div class="box-body">
                                <div class="item">
                                    <?php
                                        $row        = 0;
                                        $sqlTaskDet = "SELECT A.TASKD_ID, A.TASKD_PARENT, A.TASKD_TITLE, A.TASKD_CONTENT, A.TASKD_DATE, A.TASKD_CREATED, 
                                                            A.TASKD_FILENAME, A.TASKD_EMPID, A.TASKD_RSTAT, B.First_Name, B.Last_Name
                                                        FROM tbl_task_request_detail A
                                                            INNER JOIN tbl_employee B ON A.TASKD_EMPID = B.Emp_ID
                                                        WHERE A.TASKD_PARENT = '$TASK_CODE' ORDER BY TASKD_CREATED ASC";
                                        $resTaskDet = $this->db->query($sqlTaskDet)->result();
                                        foreach($resTaskDet as $rowTDet) :
                                            $row            = $row + 1;
                                            $TASKD_ID       = $rowTDet->TASKD_ID;
                                            $TASKD_PARENT   = $rowTDet->TASKD_PARENT;
                                            $TASKD_TITLE    = $rowTDet->TASKD_TITLE;
                                            $TASKD_CONTENT  = $rowTDet->TASKD_CONTENT;
                                            $TASKD_CREATED  = $rowTDet->TASKD_CREATED;
                                            $DATED          = date('F j, Y', strtotime($TASKD_CREATED));
                                            $DATEDT         = date('G:i:s', strtotime($TASKD_CREATED));
                                            $TASKD_FILENAME = $rowTDet->TASKD_FILENAME;
                                            $TASKD_EMPID    = $rowTDet->TASKD_EMPID;
                                            $First_Name     = ucfirst($rowTDet->First_Name);
                                            $Last_Name      = ucfirst($rowTDet->Last_Name);
                                            $compName1      = "$First_Name $Last_Name";
                                            $compName       = ucfirst($compName1);
                                            $TASKD_RSTAT    = $rowTDet->TASKD_RSTAT;
                                            
                                            // START : GET USER AKTIF PHOTO
                                                $imgemp_fnReq   = '';
                                                $imgemp_fnReqX  = '';
                                                $getIMGReq      = "SELECT imgemp_filename, imgemp_filenameX 
                                                                    FROM tbl_employee_img WHERE imgemp_empid = '$TASKD_EMPID'";
                                                $resIMGReq      = $this->db->query($getIMGReq)->result();
                                                foreach($resIMGReq as $rowIMGReq) :
                                                    $imgemp_fnReq   = $rowIMGReq ->imgemp_filename;
                                                    $imgemp_fnReqX = $rowIMGReq ->imgemp_filenameX;
                                                endforeach;
                                                $imgReqer       = base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASKD_EMPID.'/'.$imgemp_fnReqX);
                                                if($imgemp_fnReq == 'username')
                                                    $imgReqer   = base_url('assets/AdminLTE-2.0.5/emp_image/'.$imgemp_fnReqX);
                                                else if($imgemp_fnReq == '')
                                                    $imgReqer   = base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
                                            // END : GET REQUESTER PHOTO
                                            $secDel     = base_url().'index.php/c_help/c_t180c2hr/upd_readstat/?id='.$TASKD_ID;

                                            $compName1  = strtolower($compName);
                                            $compName   = ucwords($compName1);

                                            $text1      = str_replace('<p>', '', $TASKD_CONTENT);
                                            $text2      = str_replace('</p>', '<br>', $text1);
                                            $text3      = "$text2";

                                            if($TASKD_EMPID == $TASK_REQUESTER)
                                            {
                                                ?>
                                                    <div class="direct-chat-msg">
                                                        <div class="direct-chat-info clearfix">
                                                            <span class="direct-chat-name pull-left"><?php echo $compName; ?></span>
                                                            <span class="direct-chat-timestamp pull-right"><?php echo "$DATED : $DATEDT"; ?></span>
                                                        </div>
                                                        <img class="direct-chat-img" src="<?php echo $imgReqer; ?>" alt="Message User Image"><!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text">
                                                            <?php 
                                                                echo $text3;
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <div class="direct-chat-msg right">
                                                        <div class="direct-chat-info clearfix">
                                                            <span class="direct-chat-name pull-right"><?php echo $compName; ?></span>
                                                            <span class="direct-chat-timestamp pull-left"><?php echo "$DATED : $DATEDT"; ?></span>
                                                        </div>
                                                        <img class="direct-chat-img" src="<?php echo $imgReqer; ?>" alt="Message User Image"><!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text">
                                                            <?php 
                                                                echo $text3;
                                                                if($TASKD_FILENAME != '') 
                                                                {
                                                                    $fileAttach = base_url('assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/'.urldecode($TASKD_FILENAME));
                                                                    $collLink   = "$fileAttach~$TASKD_FILENAME";
                                                                    $linkDL1    = site_url('c_help/c_t180c2hr/downloadFile/?id='.$this->url_encryption_helper->encode_url($collLink));
                                                                    $isShow     = "1_$row";
                                                                    
                                                                    $linkDLRAR  = '<a href="'.base_url().'assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/'.$TASKD_FILENAME.'" title="Download file" data-skin="skin-green" class="btn btn-primary btn-xs" id="isdl"><i class="fa fa-download"></i></a>';
                                                                    ?>
                                                                    <div class="attachment">
                                                                        <h4>Attachments:</h4>
                                                                        <p class="filename">
                                                                            <a href="<?php echo $linkDL1; ?>"><?php echo $TASKD_FILENAME; ?>&nbsp;&nbsp;</a>
                                                                        </p>
                                                                        <p class="filename">
                                                                            <button type="button" id="btnShow_<?php echo $row; ?>" class="btn btn-warning btn-sm btn-flat" onClick="showFile(1,<?php echo $row; ?>);">Show File</button>
                                                                            <button type="button" id="btnHide_<?php echo $row; ?>" class="btn btn-warning btn-sm btn-flat" onClick="showFile(0,<?php echo $row; ?>);" style="display:none">Hide File</button>
                                                                            <a href="<?php echo $fileAttach; ?>" target="_blank"><button type="button" class="btn btn-primary btn-sm btn-flat" >Download</button></a>
                                                                        </p>
                                                                        <p class="filename" id="showFile_<?php echo $row; ?>" style="display:none">
                                                                            <br><img src="<?php echo $fileAttach; ?>" alt="user image" class="online" style="max-width:800px; max-height:860px">
                                                                        </p>
                                                                    </div>
                                                                    <br>
                                                                <?php
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
<?php
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isTR = 1";
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