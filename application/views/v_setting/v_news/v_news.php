<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= v_news.php
 * Location		= -
*/

$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$Emp_ID 	= $this->session->userdata['Emp_ID'];
$DefEmp_ID  = $this->session->userdata['Emp_ID'];
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

    <script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>
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
    		if($TranslCode == 'Back')$Back = $LangTransl;
    		
    		if($TranslCode == 'ID')$ID = $LangTransl;
    		if($TranslCode == 'Date')$Date = $LangTransl;
    		if($TranslCode == 'Title')$Title = $LangTransl;
    		if($TranslCode == 'Content')$Content = $LangTransl;
    		if($TranslCode == 'Status')$Status = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
                <img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/icon/list.png'; ?>" style="max-width:40px; max-height:40px" >&nbsp;&nbsp;<?php echo $mnName; ?>
                <small>&nbsp;</small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped" width="100%">
                        <thead>
                        <tr>
                            <th width="5%" nowrap>No</th>
                            <th width="10%"><?php echo $ID ?></th>
                            <th width="9%"><?php echo $Date ?></th>
                            <th width="16%"><?php echo $Title ?></th>
                            <th width="56%"><?php echo $Content ?></th>
                            <th width="4%"><?php echo $Status ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $noUrut = 0;
                        $j = 0;
                        if($countNews >0)
                        {
                            foreach($vwNews as $row) : 
                                $noUrut			= $noUrut + 1;
                                $NEWSH_CODE 	= $row->NEWSH_CODE;
                                $NEWSH_DATE 	= $row->NEWSH_DATE;
                                $NEWSH_TITLE	= $row->NEWSH_TITLE;
                                $NEWSH_CONTENT	= $row->NEWSH_CONTENT;
                                $NEWSH_STAT		= $row->NEWSH_STAT;
                                if($NEWSH_STAT == 1)
                                    $NEWSH_STATD	= "Aktive";
                                else
                                    $NEWSH_STATD	= "Non Aktive";
                                
        						if($NEWSH_STAT == 1)
        						{
        							$isActDesc	= 'Active';
        							$STATCOL	= 'success';
        						}
        						else
        						{
        							$isActDesc	= 'In Active';
        							$STATCOL	= 'danger';
        						}
        						
                                $secUpd		= site_url('c_setting/c_news/update/?id='.$this->url_encryption_helper->encode_url($NEWSH_CODE));
                                    
                                if ($j==1) {
                                    echo "<tr class=zebra1>";
                                    $j++;
                                } else {
                                    echo "<tr class=zebra2>";
                                    $j--;
                                }
                                ?>
                                    <td style="text-align:center"> <?php echo $noUrut; ?>.</td>
                                    <td><?php echo anchor($secUpd,$NEWSH_CODE);?></td>
                                    <td style="text-align:center">
        								<?php print date('d M Y', strtotime($NEWSH_DATE)); ?>
                                    </td>
                                    <td><?php echo "$NEWSH_TITLE"; ?></td>
                                    <td>
        								<?php
                                            echo cut_text("$NEWSH_CONTENT", 50);
                                        ?>
        							</td>
                                    <td>
                                        <span class="label label-<?php echo $STATCOL; ?>" style="font-size:12px">
                                            <?php 
                                                echo "&nbsp;&nbsp;$isActDesc&nbsp;&nbsp;";
                                             ?>
                                        </span>
        							</td>
                                </tr>
                                <?php
                            endforeach; 
                        }
                        $secAddURL = site_url('c_setting/c_news/add/?id='.$this->url_encryption_helper->encode_url($appName));
                        ?>
                        </tbody>
                        <?php if($ISCREATE == 1) { ?>
                            <tfoot>
                                <tr>
                                    <td style="text-align:left" colspan="6">
                                        <?php
                                            echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="fa fa-plus"></i></button>&nbsp;');
                                        ?>
                                    </td>
                                </tr>
                            </tfoot>
                        <?php } ?>
                    </table>
                    <?php
                        $act_lnk = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if($DefEmp_ID == 'D15040004221')
                            echo "<font size='1'><i>$act_lnk</i></font>";
                    ?>
                </div>
            </div>
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