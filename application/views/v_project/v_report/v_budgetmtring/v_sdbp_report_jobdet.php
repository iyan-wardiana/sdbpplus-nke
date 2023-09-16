<?php
	/* 
		* Author		   = Dian Hermanto
		* Create Date	= 20 April 2018
		* File Name	 = v_sdbp_report.php
		* Location		 = -
	*/

	if($viewType == 1)
	{
		$repDate 	= date('ymdHis');
		$fileNm 	= "RingkasanBudgetDet_".$repDate;
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$fileNm.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}

	$this->db->select('Display_Rows,decFormat');
	$resGlobal = $this->db->get('tglobalsetting')->result();
	foreach($resGlobal as $row) :
		$Display_Rows = $row->Display_Rows;
		$decFormat = $row->decFormat;
	endforeach;
	$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

    function cut_text($var, $len = 200, $txt_titik = "-") 
    {
        $var1   = explode("</p>",$var);
        $var    = $var1[0];
        if (strlen ($var) < $len) 
        { 
            return $var; 
        }
        if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
        {
            return $match [1] . $txt_titik;
        }
        else
        {
            return substr ($var, 0, $len) . $txt_titik;
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Laporan Pekerjaan Tambah/Kurang</title>
		<?php
            $vers   = $this->session->userdata['vers'];

            $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'jss' AND isAct = 1 AND cssjs_vers IN ('$vers', 'All')";
            $rescss = $this->db->query($sqlcss)->result();
            foreach($rescss as $rowcss) :
                $cssjs_lnk1  = $rowcss->cssjs_lnk;
                ?>
                    <script src="<?php echo base_url($cssjs_lnk1) ?>"></script>
                <?php
            endforeach;
        ?>
	    <style>
	        body { 
	            /*margin: 0;*/
	            padding: 0;
	            background-color: #FAFAFA;
	            font: 12pt Arial, Helvetica, sans-serif;
	        }

	        * {
	            box-sizing: border-box;
	            -moz-box-sizing: border-box;
	        }

	        .page {
	            width: 600mm;
	            /*min-height: 296mm;*/
	            padding-left: 1cm;
	            padding-right: 1cm;
	            padding-top: 1cm;
	            padding-bottom: 1cm;
	            margin: 0.5cm auto;
	            border: 1px #D3D3D3 solid;
	            border-radius: 5px;
	            background: white;
	           /* background-size: 400px 200px !important;*/
	            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
	        }

	        @page {
	            /*size: auto;*/
    			/*size: A3;*/
	            margin: 0;
	        }

	        @media print {

	            @page{size: landscape;}
	            .page {
	                margin: 0;
	                border: initial;
	                border-radius: initial;
	                width: initial;
	                min-height: initial;
	                box-shadow: initial;
	                background: initial;
	                page-break-after: always;
	            }
	        }

	        .print_content table, thead, th, table, tbody, td {
	        	padding: 3px;
	        }

			.dataTables_empty {
				text-align: center;
			}

			.dataTables_processing {
				text-align: center;
			}

			.dt-body-center {
				text-align: center;
			}

			.dt-body-right {
				text-align: right;
			}
	    </style>
	</head>

	<div class="page">
        <div id="Layer1">
            <a href="#" onClick="Layer1.style.visibility='hidden'; self.print(); self.close();">
            <img src="<?php echo base_url().'assets/AdminLTE-2.0.5/dist/img/print.gif';?>" border="0" alt="" align="absmiddle">Print</a>
            <a href="#" onClick="window.close();" class="button"> close </a>
        </div>
        <div class="print_title">
            <table width="100%" border="0" style="size:auto">
                <tr>
					<td width="50" height="50" valign="top" class="style2" style="text-align:left; font-weight:bold;"><img src="<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/compLog/LogoPrintOut.png'; ?>" width="180"></td>
                </tr>
            </table>
        </div>

        <div class="print_body" style="font-size: 14px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100">Nama Laporan</td>
                    <td width="10">:</td>
                    <td colspan="3"><?php echo "$h1_title"; ?> (Detil Pekerjaan)</td>
                </tr>
                <tr>
                    <td width="100">Periode</td>
                    <td width="10">:</td>
                    <td><?php echo date('d-m-Y', strtotime($Start_Date));?>  S/D <?php echo date('d-m-Y', strtotime($End_Date));?></td>
                </tr>
                <tr>
                    <td width="100">Kode Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJCODE"; ?></td>
                </tr>
                <tr>
                    <td width="100">Nama Proyek</td>
                    <td width="10">:</td>
                    <td><?php echo "$PRJNAME"; ?></td>
                </tr>
                <tr style="display: none;">
                    <td width="100">Kategori</td>
                    <td width="10">:</td>
                    <td><?php echo $ITMGRP_NM; ?></td>
                </tr>
                <tr>
                    <td width="100">Tgl. Cetak</td>
                    <td width="10">:</td>
                    <td><?php echo date('Y-m-d:H:i:s'); ?></td>
                </tr>
            </table>
        </div>
        <div class="print_content" style="padding-top: 5px; font-size: 12px;">
        	<table id="reportData" width="100%" border="1" rules="all" style="border-color: black;">
        		<thead>
        			<tr style="background:#CCCCCC">
						<th width="400" rowspan="3" style="text-align:center; font-weight:bold; border-bottom-color:#000">DESKRIPSI</th>
						<th rowspan="3" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">SAT.</th>
						<th rowspan="3" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">ISLASTH</th>
						<th rowspan="3" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">ISLAST</th>
						<th rowspan="3" width="30" style="text-align:center; font-weight:bold; border-bottom-color:#000">IS_LEVEL</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">BUDGET AWAL</th>
						<th colspan="4" style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000; border-left-color:#000;">PERUBAHAN</th>
						<th colspan="2" rowspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000">SETELAH<BR>PERUBAHAN</th>
						<th colspan="6"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">REALISASI</th>
	              	</tr>
	                <tr style="background:#CCCCCC">
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">PERIODE INI</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">KOMULATIF</th>
						<th colspan="2"  style="text-align:center; font-weight:bold; border-top-color:#000; border-right-color:#000;">SISA BUDGET THD REALISASI</th>
	                </tr>
	                <tr style="background:#CCCCCC">
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
						<th width="50" style="text-align:center; font-weight:bold; border-bottom-color:#000;">VOL.</th>
						<th width="100" style="text-align:center; font-weight:bold; border-bottom-color:#000; border-right-color:#000;">JUMLAH</th>
	                </tr>
	              	<tr style="line-height:1px; border-left:hidden; border-right:hidden">
						<td style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
						<td  style="text-align:center; border-right: none; border-left: none; border-bottom-color:#000;">&nbsp;</td>
	               	</tr>
        		</thead>
        		<tbody>
        		</tbody>
				<tfoot>
					<tr>
						<td colspan="6" style="text-align: right; font-weight:bold;">TOTAL</td>
						<td style="text-align: right; font-weight:bold;"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
						<td style="text-align: right; font-weight:bold;" colspan="2"></td>
					</tr>
				</tfoot>
        	</table>
        </div>
	</body>
</html>
<script type="text/javascript">
	$(document).ready(function()
	{
		let PRJCODE 	= '<?php echo $PRJCODE; ?>';
		let JOBPARENT 	= '<?php echo $JOBPARENT; ?>';
		let Start_Date 	= '<?php echo $Start_Date; ?>';
		let End_Date 	= '<?php echo $End_Date; ?>';
		$("#reportData").DataTable({
			"processing": true,
			"serverSide": true,
			"filter": false,
			"bLengthChange" : false,
			"bInfo":false,
			"paging": false,
			"ajax": {
				"url": "<?php echo site_url('c_project/c_r3p/get_AllReportData_jobdet');?>",
				"type": "POST",
				"async": true,
				"data": {PRJCODE:PRJCODE, JOBPARENT:JOBPARENT, Start_Date:Start_Date, End_Date:End_Date},
				"beforeSend": function(xhr) {
					$('tfoot > tr').hide();
				},
				"complete": function(result) {
					$('tfoot > tr').show();
				}
			},
			"columnDefs": [	
				{
					targets: [0],
					render: function(data, type, row, meta) {
						let ISLAST 		= row[3];
						let IS_LEVEL 	= row[4];
						let CELL_COL	= "font-weight:bold;";

						if(ISLAST == 1)
						{
							CELL_COL	= "font-weight:normal;";
						}

						let spaceLev 	= "";
						if(IS_LEVEL == 1)
							spaceLev	= 0;
						else if(IS_LEVEL == 2)
							spaceLev 	= 15;
						else if(IS_LEVEL == 3)
							spaceLev 	= 30;
						else if(IS_LEVEL == 4)
							spaceLev 	= 45;
						else if(IS_LEVEL == 5)
							spaceLev 	= 60;
						else if(IS_LEVEL == 6)
							spaceLev 	= 75;
						else if(IS_LEVEL == 7)
							spaceLev 	= 90;
						else if(IS_LEVEL == 8)
							spaceLev 	= 105;
						else if(IS_LEVEL == 9)
							spaceLev 	= 120;
						else if(IS_LEVEL == 10)
							spaceLev 	= 135;
						else if(IS_LEVEL == 11)
							spaceLev 	= 150;
						else if(IS_LEVEL == 12)
							spaceLev 	= 165;

						return '<span style="white-space:nowrap;'+CELL_COL+'"><div style="margin-left: '+spaceLev+'px;">'+data+'</div></span>';
					}
				},
				{ 
					targets: [1], 
					className: 'dt-body-center',
					render: function(data, type, row, meta) {
						let ISLAST 		= row[3];
						let CELL_COL	= "font-weight:bold;";

						if(ISLAST == 1)
						{
							CELL_COL	= "font-weight:normal;";
						}

						return '<span style="'+CELL_COL+'">'+data+'</span>';
					}
				},
				{ 
					targets: [2,3,4], 
					visible: false
				},
				{ 
					targets: [5,7,9,11,13,15,17], 
					className: 'dt-body-right',
					render: function(data, type, row, meta) {
						const formatter = new Intl.NumberFormat('en-NZ', {
							// style: 'currency',
							// currency: 'NZD',
							minimumFractionDigits: 2,
						});

						let ISLAST 		= row[3];
						let CELL_COL	= "font-weight:bold;";

						if(ISLAST == 1)
						{
							CELL_COL	= "font-weight:normal;";
						}
						
						// let dataVw 	= new Intl.NumberFormat().format(data);
						return '<span style="'+CELL_COL+'">'+formatter.format(data)+'</span>';
					}
				},
				{ 
					targets: [6,8,10,12,14,16,18], 
					className: 'dt-body-right',
					render: function(data, type, row, meta) {
						const formatter = new Intl.NumberFormat('en-NZ', {
							// style: 'currency',
							// currency: 'NZD',
							minimumFractionDigits: 2,
						});

						let ISLAST 		= row[3];
						let CELL_COL	= "font-weight:bold;";

						if(ISLAST == 1)
						{
							CELL_COL	= "font-weight:normal;";
						}
						
						// let dataVw 	= new Intl.NumberFormat().format(data);
						return '<span style="'+CELL_COL+'">'+formatter.format(data)+'</span>';
					}
				},
			],
			"footerCallback": function (row, data, start, end, display) {
				let api = this.api();
				const formatter = new Intl.NumberFormat('en-NZ', {
					// style: 'currency',
					// currency: 'NZD',
					minimumFractionDigits: 2,
				});

				// Remove the formatting to get integer data for summation
				let intVal = function (i) {
					return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
				};

				let lnRow 				= api.data().length;
				let getData				= api.data();
				let GTITM_BUDG			= 0;
				let GTADD_TOTAL 		= 0;
				let GTADD_TOTAL_KOM 	= 0;
				let GTITM_BUDG2 		= 0;
				let GTREQ_AMOUNT 		= 0;
				let GTREQ_AMOUNT_KOM 	= 0;
				let GTITM_USED_AM 		= 0;
				let GTITM_USED_AM_KOM	= 0;
				let GTREMREQ_AMOUNT 	= 0;
				let GTREMREALZ_AMOUNT 	= 0;
				console.log(lnRow);
				for(let i = 0; i < lnRow; i++) {
					let ISLASTH 	= getData[i][2];
					if(ISLASTH == 1)
					{
						GTITM_BUDG 			= parseFloat(GTITM_BUDG) + parseFloat(getData[i][6]);
						GTADD_TOTAL 		= parseFloat(GTADD_TOTAL) + parseFloat(getData[i][8]);
						GTADD_TOTAL_KOM 	= parseFloat(GTADD_TOTAL_KOM) + parseFloat(getData[i][10]);
						GTITM_BUDG2 		= parseFloat(GTITM_BUDG2) + parseFloat(getData[i][12]);
						GTREQ_AMOUNT 		= parseFloat(GTREQ_AMOUNT) + parseFloat(getData[i][14]);
						GTREQ_AMOUNT_KOM 	= parseFloat(GTREQ_AMOUNT_KOM) + parseFloat(getData[i][16]);
						GTREMREQ_AMOUNT 	= parseFloat(GTREMREQ_AMOUNT) + parseFloat(getData[i][18]);
					}
				}
				
				console.log('GTITM_BUDG = '+GTITM_BUDG);
				console.log(new Intl.NumberFormat().format(GTITM_BUDG));
				$(api.column(6).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTITM_BUDG)+'</div>');
				console.log('GTADD_TOTAL = '+GTADD_TOTAL);
				$(api.column(8).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTADD_TOTAL)+'</div>');
				console.log('GTADD_TOTAL_KOM = '+GTADD_TOTAL_KOM);
				$(api.column(10).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTADD_TOTAL_KOM)+'</div>');
				console.log('GTITM_BUDG2 = '+GTITM_BUDG2);
				$(api.column(12).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTITM_BUDG2)+'</div>');
				console.log('GTREQ_AMOUNT = '+GTREQ_AMOUNT);
				$(api.column(14).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTREQ_AMOUNT)+'</div>');
				console.log('GTREQ_AMOUNT_KOM = '+GTREQ_AMOUNT_KOM);
				$(api.column(16).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTREQ_AMOUNT_KOM)+'</div>');
				console.log('GTREMREQ_AMOUNT = '+GTREMREQ_AMOUNT);
				$(api.column(18).footer()).html('<div style="font-weight: bold;">'+formatter.format(GTREMREQ_AMOUNT)+'</div>');
			},
			"language": {
				"infoFiltered":"",
				"processing": "<img src='<?php echo base_url() . 'assets/AdminLTE-2.0.5/dist/img/loading/loading1.gif'; ?>' width='150' />"
			},
		});
	});

	function doDecimalFormat(angka) {
		var a, b, c, dec, i, j;
		angka = String(angka);
		if(angka.indexOf('.') > -1){ a = angka.split('.')[0] ; dec = angka.split('.')[1]
		} else { a = angka; dec = -1; }
		b = a.replace(/[^\d]/g,"");
		// b = a;
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
</script>
<?php
	$vers   = $this->session->userdata['vers'];
    $sqlcss = "SELECT cssjs_lnk FROM tbl_cssjs WHERE cssjs_typ = 'js' AND isAct IN (1,3,4) AND cssjs_vers IN ('$vers', 'All')";
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