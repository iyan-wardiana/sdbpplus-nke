<?php
/* 
    * Author		= Dian Hermanto
    * Create Date	= 4 April 2017
    * File Name	= item_category.php
    * Location		= -
*/
?>
<?php 
$this->load->view('template/head');

$appName 	= $this->session->userdata('appName');
$appBody    = $this->session->userdata['appBody'];

//$this->load->view('template/topbar');
//$this->load->view('template/sidebar');

$DefEmp_ID 			= $this->session->userdata['Emp_ID'];
$Emp_DeptCode		= $this->session->userdata['Emp_DeptCode'];
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
    	$ISDWONL 	= $this->session->userdata['ISDWONL'];$LangID 	= $this->session->userdata['LangID'];

    	$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
    	$resTransl		= $this->db->query($sqlTransl)->result();
    	foreach($resTransl as $rowTransl) :
    		$TranslCode	= $rowTransl->MLANG_CODE;
    		$LangTransl	= $rowTransl->LangTransl;
    		
    		if($TranslCode == 'Add')$Add = $LangTransl;
    		if($TranslCode == 'Edit')$Edit = $LangTransl;
    		if($TranslCode == 'Code')$Code = $LangTransl;
    		if($TranslCode == 'ItemCategoryName')$ItemCategoryName = $LangTransl;
    		if($TranslCode == 'ItemCategory')$ItemCategory = $LangTransl;
    		if($TranslCode == 'Inventory')$Inventory = $LangTransl;
    		if($TranslCode == 'ItemGroup')$ItemGroup = $LangTransl;
    	endforeach;

        $comp_color = $this->session->userdata('comp_color');
    ?>
    <body class="<?php echo $appBody; ?>" style="background-color: <?=$comp_color?>">
        <section class="content-header">
            <h1>
            <?php echo $ItemCategory; ?>
            <small><?php echo $Inventory; ?></small>
            </h1>
            <?php /*?><ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
            </ol><?php */?>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-body">
                    <div class="search-table-outter">
                        <table id="example1" class="table table-bordered table-striped" width="100%">
                        	<thead>
                                <tr>
                                    <th width="3%">No.</th>
                                    <th width="7%"><?php echo $Code ?> </th>
                                    <th width="18%"><?php echo $ItemGroup; ?></th>
                                    <th width="72%"><?php echo $ItemCategoryName ?> </th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php 
                                $i = 0;
            					$j = 0;
                                if($countIC > 0)
                                {
                                    foreach($viewitemcat as $row) :
            							$i			= $i + 1;
            							$IG_Code 	= $row->IG_Code;
            							$IG_Name	= '-';
            							$sqlG		= "SELECT IG_Name FROM tbl_itemgroup WHERE IG_Code = '$IG_Code' LIMIT 1";
            							$resG		= $this->db->query($sqlG)->result();
            							foreach($resG as $rowG) :
            								$IG_Name	= $rowG->IG_Name;
            							endforeach;
            							$IC_CODE 	= $row->IC_Code;
            							$IC_Name 	= $row->IC_Name;								
            								
            							$secUpd		= site_url('c_inventory/c_item_category/update/?id='.$this->url_encryption_helper->encode_url($IC_CODE));
            							
            							if ($j==1) {
            								echo "<tr class=zebra1>";
            								$j++;
            							} else {
            								echo "<tr class=zebra2>";
            								$j--;
            							}
            							?>
                                                <td style="text-align: center;"> <?php print $i; ?> </td>
                                                <td> <?php print anchor("$secUpd",$IC_CODE,array('class' => 'update')).' '; ?> </td>
                                                <td> <?php print $IG_Name; ?> </td>
                                                <td> <?php print $IC_Name; ?> </td>
                                            </tr>
                                            <?php 
                                    endforeach;
                                }
            					$secAddURL = site_url('c_inventory/c_item_category/add/?id='.$this->url_encryption_helper->encode_url($appName));
                                ?> 
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <?php 
                        echo anchor("$secAddURL",'<button class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button>&nbsp;');
                    ?>
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