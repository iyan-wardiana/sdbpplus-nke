<!-- Left side column. contains the sidebar -->
<?php
	$maxLimDf	= 50;
	$maxLimit	= 50;
	$sqlApp 	= "SELECT * FROM tappname";
	$resultaApp = $this->db->query($sqlApp)->result();
	foreach($resultaApp as $therow) :
		$appName 	= $therow->app_name;
		$maxLimDf 	= $therow->maxLimDf;
		$maxLimit 	= $therow->maxLimit;		
	endforeach;

	//$this->load->model('menu_model', '', TRUE);
	$appName 		= $this->session->userdata('appName');
	$vers     		= $this->session->userdata['vers'];
	$username 		= $this->session->userdata('username');
	$Emp_ID 		= $this->session->userdata('Emp_ID');
	$LangID			= $this->session->userdata['LangID'];
	$sysMode		= $this->session->userdata['sysMode'];
	$LastModeD		= $this->session->userdata['LastModeD'];
	$sysMnt			= $this->session->userdata['sysMnt'];
	$LastMntD		= $this->session->userdata['LastMntD'];
	$PRJSCATEG		= $this->session->userdata['PRJSCATEG'];

	$tgl1 			= new DateTime($LastMntD);
	$tgl2 			= new DateTime();
	$dif1 			= $tgl1->diff($tgl2);
	$dif2 			= $dif1->days;

	$cLogV			= "tbl_emp_vers WHERE EMP_ID = '$Emp_ID' AND VERS = '$vers'";
	$vLogV			= $this->db->count_all($cLogV);

	$isEnd 			= 0;
	if($sysMnt == 1 && $tgl2 >= $tgl1)
	{
		$isEnd 		= 2;
	}
	elseif($sysMnt == 1 && $dif2 < 6) 
	{
		$isEnd 		= 1;
	}

	$lasTDMnt		= date('d M Y', strtotime($LastMntD));
	$mntWarn1		= "";
	$mntWarn2		= "";
	$sqlTransl			= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
	$resTransl			= $this->db->query($sqlTransl)->result();
	foreach($resTransl as $rowTransl) :
		$TranslCode		= $rowTransl->MLANG_CODE;
		$LangTransl		= $rowTransl->LangTransl;	
		if($TranslCode == 'systemMode')$systemMode = $LangTransl;
		if($TranslCode == 'descMode')$descMode = $LangTransl;
		if($TranslCode == 'Expire')$Expire = $LangTransl;
		if($TranslCode == 'Warning')$Warning = $LangTransl;
	endforeach;
	$tgl1 = new DateTime($LastMntD);
	$tgl2 = new DateTime();
 
	$dif1 = $tgl1->diff($tgl2);
	$dif2 = $dif1->days;

	$imgemp_filename 	= '';
	$imgemp_filenameX	= '';
	$sqlGetIMG			= "SELECT imgemp_filename, imgemp_filenameX FROM tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
	$resGetIMG 			= $this->db->query($sqlGetIMG)->result();
	foreach($resGetIMG as $rowGIMG) :
		$imgemp_filename 	= $rowGIMG ->imgemp_filename;
		$imgemp_filenameX 	= $rowGIMG ->imgemp_filenameX;
	endforeach;

	$completeName		= $this->session->userdata('completeName');

	$imgLoc				= base_url('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID.'/'.$imgemp_filenameX);
	if (!file_exists('assets/AdminLTE-2.0.5/emp_image/'.$Emp_ID))
	{
		$imgLoc			= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
	}
	if (!isset($url_search))
	{
		$url_search	= '';
	}
	$imgWarn			= base_url('assets/AdminLTE-2.0.5/dist/img/1stweb/1stweb.png');
	$secMsgNtf			= site_url('c_msg_ntf/c_msg_ntf/?id='.$this->url_encryption_helper->encode_url($appName));

	if (!isset($MenuCode))
	{
		$MenuCode	= '';
	}

	$getparent    = $this->db->get_where('tbl_menu', array('menu_code' => $MenuCode));


	if($getparent->num_rows()>0)
	{
		foreach($getparent->result() as $r):
			$parent_code  = $r->parent_code;
			$level_menu   = $r->level_menu;
		endforeach;

		if($level_menu == 3){
		  //get Level 3
		  $getparent1   = $this->db->get_where('tbl_menu', array('menu_code' => $parent_code));
		  if($getparent1->num_rows()>0)
		  {
		  		foreach($getparent1->result() as $r1):
					$parent1_code  = $r1->parent_code;
				endforeach;	
		  }
		  else
		  {
		  		$parent1_code = '';	
		  }
		  
		  //Menu Active
		  $Lev1Menu = $parent1_code;
		  $Lev2Menu = $parent_code;
		  $Lev3Menu = $MenuCode;
		}elseif($level_menu == 2){
		  //Menu Active
		  $Lev1Menu = $parent_code;
		  $Lev2Menu = $MenuCode;
		  $Lev3Menu = '';
		}elseif($level_menu == 1){
		  //Menu Active
		  $Lev1Menu = $MenuCode;
		  $Lev2Menu = '';
		  $Lev3Menu = '';
		}	
	}
	else
	{
		$Lev1Menu = '';
		$Lev2Menu = '';
		$Lev3Menu = '';
	}
	$appBody    = $this->session->userdata['appBody'];
?>

<style type="text/css">
    html {
        overflow: hidden;
        ful
    }
</style>
    
<!-- <body class="<?php echo $appBody; ?>"> -->
<!-- <body class="hold-transition skin-blue sidebar-mini fixed" onLoad="App.handleFullScreen()"> -->
<body class="hold-transition skin-blue sidebar-mini fixed">
	<div class="wrapper">
	    <aside class="main-sidebar">
	        <div class="sidebar">
				<!-- Sidebar user panel -->
		        <?php if($sysMode == 1) { ?>
		        <div class="alert alert-warning alert-dismissible">
		            <h4><i class="icon fa fa-warning"></i> <?php echo $systemMode; ?></h4>
		            <?php echo "$descMode $Expire : ".date('d-M-Y', strtotime($LastModeD)); ?>
		        </div>
		        <?php } ?>
		        <div class="user-panel">
		            <div class="pull-left image">
		                <img src="<?php echo $imgLoc; ?>" class="img-circle" style="max-height:55px" alt="User Image" />
		            </div>
		            <div class="pull-left info">
		                <p><?php echo $this->session->userdata('completeName'); ?></p>

		                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		            </div>
		        </div>
		        <?php
					$appName 	= $this->session->userdata('appName');
					$collData	= "$Emp_ID~$username~$completeName~$appName";
				?>

		        <!-- search form -->
		        <form action="<?php echo $url_search; ?>" method="post" class="sidebar-form" onsubmit="return checkData();" style="display: none;">
		            <div class="input-group">
		                <input type="text" name="gEn5rcH" class="form-control" placeholder="Search..." />
		                <input type="hidden" name="maxLimDf" class="form-control" placeholder="Search..." value="<?php echo $maxLimDf; ?>" />
		                <input type="hidden" name="maxLimit" class="form-control" placeholder="Search..." value="<?php echo $maxLimit; ?>" />
		                <span class="input-group-btn">
		                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
		                </span>
		            </div>
		        </form>
		        <!-- /.search form -->
		        <!-- sidebar menu: : style can be found in sidebar.less -->
		        <?php
					//$urlDash1	= site_url('__180c2f/?id='.$this->url_encryption_helper->encode_url($appName));
					//$urlLock	= site_url('__l1y/lockSystem/?id='.$collData);
					$urlLock	= site_url('__l1y/lockSystem/?id='.$this->url_encryption_helper->encode_url($collData));
					$howtouse	= site_url('howtouse/?id='.$this->url_encryption_helper->encode_url($appName));
					$reservation= site_url('reservation/?id='.$this->url_encryption_helper->encode_url($appName));

					// NEW MENU
					/*$urlAbs		= site_url('c_tsemp/c_tsemp/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlAbsDR	= site_url('c_tsemp/c_tsemp/d41lYr3p?id='.$this->url_encryption_helper->encode_url($appName));
					$urlAssesm	= site_url('c_a553sm/c_a553sm/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlmnotes	= site_url('c_mnotes/c_mnotes/?id='.$this->url_encryption_helper->encode_url($appName));
					$urlreport	= site_url('c_report/c_report_emp/?id='.$this->url_encryption_helper->encode_url($appName));*/


					if($LangID == 'IND')
					{
						$Text1		= "NAVIGASI UTAMA";
						$dashboardT	= "Beranda";
						$LockScr	= "Kunci Layar";
						$LockTrx	= "Kunci Jurnal";
						$bcWA		= "Broadcash Pesan";
						$mnLKH		= "Lap. Kerja Harian";
						$HowToUse	= "Cara Penggunaan";
						$Reservat	= "Pemesanan";
						$mntWarn1	= "Layanan '1stWeb Assistance' akan segera berakhir pada tanggal : ";
						$mntWarn2	= "Silahkan hubungi kami agar tetap mendapatkan layanan '1stWeb Assistance'.";
						$mntWarn3	= "Layanan '1stWeb Assistance' sudah berakhir per ";
						$mntWarn4	= "Mengapa saya melihat ini?";

						if($isEnd == 1)
						{
							$mntWarna	= "Perawatan Sistem";
							$mntWarnb	= "Akan berakhir pada $lasTDMnt.";
						}
						else if($isEnd == 2)
						{
							$mntWarna	= "Perawatan Sistem";
							$mntWarnb	= "sudah berkahir pada $lasTDMnt.";
						}
					}
					else
					{
						$Text1		= "MAIN NAVIGATION";
						$dashboardT	= "Dashboard";
						$HowToUse	= "How To Use";
						$LockScr	= "Lock Screen";
						$LockTrx	= "Lock Journal";
						$bcWA		= "Broadcash";
						$mnLKH		= "Daily Work Report";
						$Reservat	= "Reservation";
						$mntWarn1	= "Sorry, '1stWeb Assistance' services will be finished on : ";
						$mntWarn2	= "Please contact us to get '1stWeb Assistance' services.";
						$mntWarn3	= "Sorry, we have finished '1stWeb Assistance' services per ";
						$mntWarn4	= "Why did I see this message?";

						if($isEnd == 1)
						{
							$mntWarna	= "System Maintenance";
							$mntWarnb	= "will expire on $lasTDMnt.";
						}
						else if($isEnd == 2)
						{
							$mntWarna	= "System Maintenance";
							$mntWarnb	= "has expired on $lasTDMnt.";
						}
					}

					if($MenuCode == '')
						$MenuCode == 'MN500';

				?>

				<input type="hidden" name="isEnd" id="isEnd" value="<?php echo $isEnd; ?>">

	            <!-- <ul class="sidebar-menu" data-widget="tree"> -->
	            <ul class="sidebar-menu">
	            </ul>
				<div class="sidebar-custom">
					<?php
						$sqlApp 		= "SELECT * FROM tappname";
						$resultaApp = $this->db->query($sqlApp)->result();
						foreach($resultaApp as $therow) :
							$vend_app	= $therow->vend_app;
							$vend_link	= $therow->vend_link;
							$app_link	= $therow->app_link;
							$foot_name	= $therow->foot_name;
							$version 	= $therow->version;
							$year 		= $therow->year;
						endforeach;
					?>
					<br>
				    <div class="text-center" style="display: none;">
				    	<div class="alert alert-success alert-dismissible">
				        <b><?php echo $vend_app; ?> v<?php echo $version; ?></b><br>
				        <div style="font-style: italic;"> Copyright &copy; <strong><?php echo $year; ?></strong>.<br>All rights reserved.
				        </div>
				    </div>
				</div>
	        </div>
	    </aside>

	    <div class="content-wrapper" id="content-wrapper" style="min-height: 421px;">
	        <div class="content-tabs">
	            <button class="roll-nav roll-left fullscreen" onclick="App.handleFullScreen()"><i class="fa fa-arrows-alt"></i></button>
	            <nav class="page-tabs menuTabs tab-ui-menu" id="tab-menu">
	                <div class="page-tabs-content" style="margin-left: 0px;"></div>
	            </nav>
	            <button class="roll-nav roll-right fullscreen" onclick="App.handleFullScreen()"><i class="fa fa-arrows-alt"></i></button>
	        </div>
	        <div class="content-iframe " style="background-color: #ffffff; ">
	            <div class="tab-content " id="tab-content"></div>
	        </div>
	    </div>
	</div>
</body>
<?php
	$baseURL	= base_url();
	$basePath	= site_url();
	$globImg	= site_url('assets/AdminLTE-2.0.5/dist/img/');

	$urlGL		= site_url('c_gl/c_ch1h0fbeart/id1h0fbx1');

	//$urlBlank	= site_url('__180c2f/dahsBoard/?id=');
	if(isset($defMn))
		$urlBlank	= site_url(''.$defMn.$DCode.'');
	else
		$urlBlank	= site_url('__180c2f/dahsBoard/?id=');

	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
?>
<script type="text/javascript">
	$(document).ready(function()
	{
		setInterval(function(){taskNotifApp()}, 1200000);
	});

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

	$(function () {
		// console.log(window.location);

        App.setbasePath("<?php echo $baseURL; ?>");
        App.setGlobalImgPath("<?php echo $globImg; ?>");

        addTabs({
            id: '9000',
            title: '<?php echo $defNm; ?>',
            close: false,
            url: "<?php echo $urlBlank;?>",
            targetType: "iframe-tab",
            icon: "fa fa-circle-o",
            urlType: 'abosulte'
        });

        App.fixIframeCotent();

        var menus = [
            {
                id: "9001",
                text: "NAVIGASI UTAMA",
                icon: "",
                isHeader: true
            },
            <?php
				$Emp_ID 	= $this->session->userdata('Emp_ID');
				//$isSDBP	= $this->session->userdata('isSDBP');
				$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				if($Emp_ID == 'D15040004221')
				{
					$addQuery	= " AND menu_user = 1";
				}
				else
				{
					//$addQuery	= " AND isActive = 1";
					$addQuery	= " AND menu_user = 1";
				}

				if($PRJSCATEG == 1)								
				{
					$addQuery2	= " AND menu_type IN (0,1) AND isActive = 1";			// CONTRACTOR
					if($Emp_ID == 'D15040004221')
						$addQuery2	= " AND menu_type IN (0,1,50)";
				}
				elseif($PRJSCATEG == 2)								
				{
					$addQuery2	= " AND menu_type IN (2,3) AND isActive = 1";		// MANUFACTURE
					if($Emp_ID == 'D15040004221')
						$addQuery2	= " AND menu_type IN (0,2,22,50)";
				}
				elseif($PRJSCATEG == 2 OR $PRJSCATEG == 3)								
				{
					$addQuery2	= " AND menu_type IN (0,2) AND isActive = 1";			// TRADING
					if($Emp_ID == 'D15040004221')
						$addQuery2	= " AND menu_type IN (0,2,50)";
				}
				
				// KHUSUS FBS DIPERMANENKAN KARENA MENUNYA BERBEDA (16 SEPT 2021)
				//$addQuery2	= " AND menu_type IN (2,3)";

				$this->load->database();

				$sql 	= 	"SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd,
								A.menu_name_$LangID AS menu_name, A.fa_icon, A.menu_user, A.isActive
							FROM tbl_menu A
								INNER JOIN tusermenu B ON A.menu_code = B.menu_code
									AND B.USRMN_CAT = $PRJSCATEG
							WHERE 
								A.menu_user =1
								AND A.level_menu =1
								AND emp_id = '$Emp_ID'
								$addQuery2
								$addQuery
							ORDER BY A.no_urut";
				$mymenu		= $this->db->query($sql)->result();

				$num_rows 	= $this->db->count_all('tbl_menu');
			
				if($num_rows > 0 || $Emp_ID == 'D15040004221')
				{
					foreach($mymenu as $row)
					{
						$mnCode_1 	= $row->menu_code;
						$mnLink_1 	= $row->link_alias;
						$mnName_1	= $row->menu_name;
						$mnIcon_1 	= $row->fa_icon;
						$mnAct_1 	= $row->isActive;
						$mnAct_1D	= '';
						if($mnAct_1 == 0)
						{
							$mnAct_1D	= 'O';
						}

						$sqlLev_2 	= "tbl_menu A
											INNER JOIN tusermenu B ON A.menu_code = B.menu_code
												AND B.USRMN_CAT = $PRJSCATEG
										WHERE 
										A.menu_user =1
										AND A.level_menu != 1
										AND emp_id = '$Emp_ID'
										AND parent_code = '$mnCode_1'
										$addQuery2
										$addQuery";
						$resLev_2	= $this->db->count_all($sqlLev_2);
						if($resLev_2 > 0) 
	                    {
							?>
								{
					                id: "<?=$mnCode_1?>",
					                text: "<?=$mnName_1?>",
					                icon: "<?=$mnIcon_1?>",
					                children:
					                [
					                    <?php
											$sql_2 	= 	"SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd,
															A.menu_name_$LangID AS menu_name, A.fa_icon, A.menu_user, A.isActive
														FROM tbl_menu A
															INNER JOIN tusermenu B ON A.menu_code = B.menu_code
																AND B.USRMN_CAT = $PRJSCATEG
														WHERE 
															A.menu_user =1
															AND A.level_menu != 1
															AND emp_id = '$Emp_ID'
															AND parent_code = '$mnCode_1'
															$addQuery2
															$addQuery
														ORDER BY A.no_urut";
											$res_2		= $this->db->query($sql_2)->result();
											foreach($res_2 as $row_2)
											{
												$mnCode_2 	= $row_2->menu_code;
												$mnLink_2 	= $row_2->link_alias;
												$mnName_2	= $row_2->menu_name;
												$mnIcon_2 	= $row_2->fa_icon;
												$mnAct_2 	= $row_2->isActive;
												$mnAct_2D	= '';
												if($mnAct_2 == 0)
												{
													$mnAct_2D	= 'O';
												}

												$sqlLev_3 	= "tbl_menu A
																	INNER JOIN tusermenu B ON A.menu_code = B.menu_code
																		AND B.USRMN_CAT = $PRJSCATEG
																WHERE 
																A.menu_user =1
																AND A.level_menu != 1
																AND emp_id = '$Emp_ID'
																AND parent_code = '$mnCode_2'
																$addQuery2
																$addQuery";
												$resLev_3	= $this->db->count_all($sqlLev_3);
												if($resLev_3 > 0) 
							                    {
													?>
									                    {
											                id: "<?=$mnCode_2?>",
											                text: "<?=$mnName_2?>",
											                icon: "fa fa-circle-o",
											                children:
											                [
											                    <?php
																	$sql_3 	= 	"SELECT A.menu_id, B.menu_code, A.isHeader, A.level_menu, A.link_alias, A.link_alias_sd,
																					A.menu_name_$LangID AS menu_name, A.fa_icon, A.menu_user, A.isActive
																				FROM tbl_menu A
																					INNER JOIN tusermenu B ON A.menu_code = B.menu_code
																						AND B.USRMN_CAT = $PRJSCATEG
																				WHERE 
																					A.menu_user =1
																					AND A.level_menu != 1
																					AND emp_id = '$Emp_ID'
																					AND parent_code = '$mnCode_2'
																					$addQuery2
																					$addQuery
																				ORDER BY A.no_urut";
																	$res_3		= $this->db->query($sql_3)->result();
																	foreach($res_3 as $row_3)
																	{
																		$mnCode_3 	= $row_3->menu_code;
																		$mnLink_3 	= $row_3->link_alias;
																		$mnName_3	= $row_3->menu_name;
																		$mnIcon_3 	= $row_3->fa_icon;
																		$mnAct_3 	= $row_3->isActive;
																		$mnAct_3D	= '';
																		if($mnAct_3 == 0)
																		{
																			$mnAct_3D	= 'O';
																		}
																		?>
																			{
																	            id: '<?=$mnCode_3?>',
																	            text: '<?=$mnName_3?>',
																	            url: "<?php echo site_url("$mnLink_3/?id=".$this->url_encryption_helper->encode_url($appName));?>",
																	            targetType: "iframe-tab",
																	            icon: "fa fa-circle-o",
																	            urlType: 'abosulte'
																            },
														                <?php
																	}
																?>
											                ]
									                    },
													<?php
												}
												else
												{
													?>
														{
												            id: '<?=$mnCode_2?>',
												            text: '<?=$mnName_2?>',
												            url: "<?php echo site_url("$mnLink_2/?id=".$this->url_encryption_helper->encode_url($appName));?>",
												            targetType: "iframe-tab",
												            icon: "fa fa-circle-o",
												            urlType: 'abosulte'
											            },
									                <?php
												}
											}
					                    ?>
					                ]
					            },
			                <?php
			            }
			            else
			            {
							?>
								{
						            id: 'MNLOCK',
						            text: '<?=$mnName_1?>',
						            url: "<?php echo site_url("$mnLink_1/?id=".$this->url_encryption_helper->encode_url($appName));?>",
						            targetType: "iframe-tab",
						            icon: "<?=$mnIcon_1?>",
						            urlType: 'abosulte'
					            },
			                <?php
			            }
			        }
			        if($Emp_ID == 'D15040004221' || $Emp_ID == 'L14030003372')
			        {
						?>
							{
					            id: '<?=$mnCode_1?>',
					            text: '<?=$LockTrx?>',
					            url: "<?php echo site_url("lck/?id=".$this->url_encryption_helper->encode_url($appName));?>",
					            targetType: "iframe-tab",
					            icon: "fa fa-lock",
					            urlType: 'abosulte'
				            },
		                <?php
			        }
			        if($Emp_ID == 'D15040004221' || $Emp_ID == 'L14030003372')
			        {
						?>
							{
					            id: 'BROADC',
					            text: '<?=$bcWA?>',
					            url: "<?php echo site_url("bcmsg/?id=".$this->url_encryption_helper->encode_url($appName));?>",
					            targetType: "iframe-tab",
					            icon: "fa fa-paper-plane",
					            urlType: 'abosulte'
				            },
		                <?php
			        }
					?>
						/*{
				            id: 'MNLKH',
				            text: '<?=$mnLKH?>',
				            url: "<?php echo site_url("__l1y/lstDWR/?id=".$this->url_encryption_helper->encode_url($appName));?>",
				            targetType: "iframe-tab",
				            icon: "fa fa-history",
				            urlType: 'abosulte'
			            },*/
	                <?php
			    }
			?>
        ];
        $('.sidebar-menu').sidebarMenu({data: menus});

        // 动态创建菜单后，可以重新计算 SlimScroll
        // $.AdminLTE.layout.fixSidebar();

        /*if ($.AdminLTE.options.sidebarSlimScroll) {
            if (typeof $.fn.slimScroll != 'undefined') {
                //Destroy if it exists
                var $sidebar = $(".sidebar");
                $sidebar.slimScroll({destroy: true}).height("auto");
                //Add slimscroll
                $sidebar.slimscroll({
                    height: ($(window).height() - $(".main-header").height()) + "px",
                    color: "rgba(0,0,0,0.2)",
                    size: "3px"
                });
            }
        }*/
    });
</script>
<?php
	$urlLock	= site_url('__l1y/logout/?id=');
?>
<script type="text/javascript">
	function logOut()
	{
		var logoutUrl 	= '<?php echo $urlLock; ?>';
		window.location = logoutUrl
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
	/*$this->load->view('template/aside');

	$this->load->view('template/foot');*/

	$this->load->view('template/js_data');
?>