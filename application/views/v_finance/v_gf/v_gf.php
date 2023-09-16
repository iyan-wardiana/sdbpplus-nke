<?php
/* 
    * Author		= Dian Hermanto
    * Create Date  = 21 Agustus 2023
    * File Name    = v_gf.php
    * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

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
        	if($TranslCode == 'Code')$Code = $LangTransl;
        	if($TranslCode == 'Description')$Description = $LangTransl;
        	if($TranslCode == 'whName')$whName = $LangTransl;
        	if($TranslCode == 'ProjectName')$ProjectName = $LangTransl;
        endforeach;
        if($LangID == 'IND')
        {
        	$sureDelete	= "Anda yakin akan menghapus data ini?";
        }
        else
        {
        	$sureDelete	= "Are your sure want to delete?";
        }

        $comp_color = $this->session->userdata('comp_color');
    ?>

    <style>
        .search-table, td, th {
            border-collapse: collapse;
        }
        .search-table-outter { overflow-x: scroll; }
    </style>

    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <?php echo $mnName; ?>
                <div class="pull-right">
                    <?php
                        $secAddURL = site_url('c_finance/c_grntf1l3/gfile_l44d/?id='.$this->url_encryption_helper->encode_url($appName));   
                        if($ISCREATE == 1)
                            echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
                    ?>
                </div>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example1" class="table table-bordered table-striped table-responsive search-table inner" width="100%">
                            <thead>
                            <tr>
                                <th style="text-align: center; vertical-align: middle;" width="3%" nowrap>No</th>
                                <th style="text-align: center; vertical-align: middle;" width="10%"><?php echo $Code; ?></th>
                                <th style="text-align: center; vertical-align: middle;" width="20%" nowrap>Nama Jaminan</th>
                                <th style="text-align: center; vertical-align: middle;" width="20%" nowrap>Badan Penjamin</th>
                                <th style="text-align: center; vertical-align: middle;" width="10%" nowrap>Nilai Jaminan</th>
                                <th style="text-align: center; vertical-align: middle;" width="25%">Proyek</th>
                                <th style="text-align: center; vertical-align: middle;" width="10%" nowrap>Status Dok.</th>
                                <th style="text-align: center; vertical-align: middle;" width="2%">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $noUrut = 0;
                                $j = 0;
                                if($cData >0)
                                {
                                    foreach($vData as $row) : 
                                        $noUrut		        = $noUrut + 1;
                                        $GF_NUM		        = $row->GF_NUM;
                                        $GF_CODE	        = $row->GF_CODE;
                                        $GF_NAME 	        = $row->GF_NAME;
                                        $GF_DATES 	        = $row->GF_DATES;
                                        $GF_DATEE 	        = $row->GF_DATEE;
                                        $GF_PENJAMIN        = $row->GF_PENJAMIN;
                                        $GF_NILAI_JAMINAN   = $row->GF_NILAI_JAMINAN;
                                        $GF_FILENAME        = $row->GF_FILENAME;
                                        $PRJCODE            = $row->PRJCODE;
                                        $SPLCODE            = $row->SPLCODE;
                                        $GF_STATDOC	        = $row->GF_STATDOC;
                                        $GF_STATUS	        = $row->GF_STATUS;
                                        $STATDESC	        = $row->STATDESC;
                                        $STATCOL	        = $row->STATCOL;

                                        $PRJNAME            = "-";
                                        $s_PRJ              = "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
                                        $r_PRJ              = $this->db->query($s_PRJ);
                                        foreach($r_PRJ->result() as $rw_PRJ):
                                            $PRJNAME        = $rw_PRJ->PRJNAME;
                                        endforeach;
                                        
                                        if($GF_STATDOC == 1)
                                        {
                                            $DOCSTATCOL       = "success";
                                            $DOCSTATDESC      = "On Going";
                                        }
                                        elseif($GF_STATDOC == 2)
                                        {
                                            $DOCSTATCOL       = "info";
                                            $DOCSTATDESC      = "Closed";
                                        }
                                        elseif($GF_STATDOC == 3)
                                        {
                                            $DOCSTATCOL       = "warning";
                                            $DOCSTATDESC      = "Extended";
                                        }
                                        elseif($GF_STATDOC == 4)
                                        {
                                            $DOCSTATCOL       = "danger";
                                            $DOCSTATDESC      = "Expired";
                                        }
                                        else
                                        {
                                            $DOCSTATCOL       = "";
                                            $DOCSTATDESC      = "";
                                        }

                                        $SPLDESC            = "-";
                                        $s_SPL              = "SELECT SPLDESC FROM tbl_supplier WHERE SPLCODE = '$SPLCODE' LIMIT 1";
                                        $r_SPL              = $this->db->query($s_SPL);
                                        foreach($r_SPL->result() as $rw_SPL):
                                            $SPLDESC        = $rw_SPL->SPLDESC;
                                        endforeach;
                												
                                        $secUpd	= site_url('c_finance/c_grntf1l3/update/?id='.$this->url_encryption_helper->encode_url($GF_NUM));

                                        $dokPer         =   "<div style='white-space:nowrap'>
                                                                <strong>".$GF_CODE."</strong>
                                                            </div>
                                                            <div style='white-space:nowrap'>
                                                                <i class='fa fa-calendar margin-r-5'></i><i> ".date('d/m/Y', strtotime($GF_DATES))." - ".date('d/m/Y', strtotime($GF_DATEE))."</i>
                                                            </div>";

                                        $prjDesc        =   "<div style='white-space:nowrap'>
                                                                <strong><i class='fa fa-building margin-r-5'></i> ".$PRJCODE." : ".$PRJNAME."</strong>
                                                            </div>
                                                            <div style='margin-left: 18px; font-style: italic'>
                                                                ".$SPLCODE." : ".$SPLDESC."
                                                            </div>";
                                            
                                        if ($j==1) {
                                            echo "<tr class=zebra1>";
                                            $j++;
                                        } else {
                                            echo "<tr class=zebra2>";
                                            $j--;
                                        }
                                        ?>
                                            <td style="text-align:center" nowrap> <?php echo $noUrut; ?>.</td>
                                            <td><?php echo $dokPer;?></td>
                                            <td><?php echo $GF_NAME; ?></td>
                                            <td><?php echo $GF_PENJAMIN; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($GF_NILAI_JAMINAN,2); ?></td>
                                            <td><?php echo $prjDesc; ?></td>
                                            <td style="text-align: center;" nowrap>
                                                <div><span class="label label-<?=$STATCOL?>" style="font-size:12px"><?=$STATDESC?></span></div>
                                                <div><span class="label label-<?=$DOCSTATCOL?>" style="font-size:12px"><?=$DOCSTATDESC?></span></div>
                                            </td>
                                            <td width="2%" nowrap>
                                                <a href="<?php echo $secUpd; ?>" class="btn btn-warning btn-xs" title="Update">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                                <a href="" class="btn btn-danger btn-xs" title="In Active Project" onclick="return confirm('<?php echo $sureDelete; ?>')" disabled="disabled">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                        	</td>
                                        </tr>
                                        <?php
                                    endforeach; 
                                }
                            ?>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php
                $DefID      = $this->session->userdata['Emp_ID'];
                $act_lnk    = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if($DefID == 'D15040004221')
                    echo "<font size='1'><i>$act_lnk</i></font>";
            ?>
        </section>
    </body>
</html>

<script>
  $(function () 
  {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
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