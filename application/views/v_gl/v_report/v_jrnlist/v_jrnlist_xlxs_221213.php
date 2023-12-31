<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=GL_".date('YmdHis').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>

<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Lap. Daftar Jurnal</title><meta charset="UTF-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	    <style> .str{ mso-number-format:\@; } </style>
    </head>
	<script src="<?php echo base_url('assets/js/jquery-1.10.1.min.js') ?>" type="text/javascript"></script>

	<?php
		$LangID 	= $this->session->userdata['LangID'];

		$sqlTransl		= "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
		$resTransl		= $this->db->query($sqlTransl)->result();
		foreach($resTransl as $rowTransl) :
			$TranslCode	= $rowTransl->MLANG_CODE;
			$LangTransl	= $rowTransl->LangTransl;
			
			if($TranslCode == 'Add')$Add = $LangTransl;
			if($TranslCode == 'Edit')$Edit = $LangTransl;
			if($TranslCode == 'JournalCode')$JournalCode = $LangTransl;
			if($TranslCode == 'JournalType')$JournalType = $LangTransl;
			if($TranslCode == 'Date')$Date = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Status')$Status = $LangTransl;
			if($TranslCode == 'ApprovalStatus')$ApprovalStatus = $LangTransl;
			if($TranslCode == 'GeneralJournal')$GeneralJournal = $LangTransl;
			if($TranslCode == 'AddNew')$AddNew = $LangTransl;
			if($TranslCode == 'Print')$Print = $LangTransl;
			if($TranslCode == 'Back')$Back = $LangTransl;
			if($TranslCode == 'TotalAmount')$TotalAmount = $LangTransl;
			if($TranslCode == 'Amount')$Amount = $LangTransl;
			if($TranslCode == 'CreatedBy')$CreatedBy = $LangTransl;
			if($TranslCode == 'sureDelete')$sureDelete = $LangTransl;
			if($TranslCode == 'sureDelJrn')$sureDelJrn = $LangTransl;
			if($TranslCode == 'yesDel')$yesDel = $LangTransl;
			if($TranslCode == 'cancDel')$cancDel = $LangTransl;
			if($TranslCode == 'sureVoid')$sureVoid = $LangTransl;
			if($TranslCode == 'Account')$Account = $LangTransl;
			if($TranslCode == 'AccountName')$AccountName = $LangTransl;
			if($TranslCode == 'Grouping')$Grouping = $LangTransl;
			if($TranslCode == 'All')$All = $LangTransl;
			if($TranslCode == 'ItemCode')$ItemCode = $LangTransl;
			if($TranslCode == 'ItemName')$ItemName = $LangTransl;
			if($TranslCode == 'accBefore')$accBefore = $LangTransl;
			if($TranslCode == 'accSubt')$accSubt = $LangTransl;
			if($TranslCode == 'Close')$Close = $LangTransl;
			if($TranslCode == 'Description')$Description = $LangTransl;
			if($TranslCode == 'Balance')$Balance = $LangTransl;
			if($TranslCode == 'Project')$Project = $LangTransl;
		endforeach;

		$LangID 	= $this->session->userdata['LangID'];
		if($LangID == 'IND')
		{
			$grpJrn	= "Pengelompokan Jurnal Transaksi";
		}
		else
		{
			$grpJrn = "Transaction List Grouping";
		}
	?>

	<body>
        <table width="100%">
            <thead>
                <tr>
                    <th style="text-align:center; width: 2%">No.</th>
	                <th style="text-align:center; width: 6%"><?php echo $JournalCode; ?></th>
	                <th style="text-align:center; width: 5%"><?php echo $Date; ?>  </th>
					<th style="text-align:center; width: 3%"><?php echo $JournalType; ?>  </th>
					<th style="text-align:center; width: 5%"><?php echo $Account; ?>  </th>
					<th style="text-align:center; width: 10%"><?php echo $AccountName; ?>  </th>
	                <th style="text-align:center; width: 10%"><?php echo $Description; ?> </th>
	                <th style="text-align:center; width: 5%" nowrap>Debet </th>
	                <th style="text-align:center; width: 5%" nowrap>Kredit </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo $Balance; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo $Project; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "No. Voucher"; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "Kode Pemasok"; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "Nama Pemasok"; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "Tgl. Kwit."; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "No. Kwit."; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "Tgl. Faktur"; ?> </th>
					<th style="text-align:center; width: 5%" nowrap><?php echo "No. Seri Faktur"; ?> </th>
	                <th style="text-align:center; width: 2%" nowrap>User</th>
	                <th style="text-align:center; width: 2%" nowrap>Tgl. Input</th>
                </tr>
            </thead>
            <tbody>
            	<?php
					$QRYPRJ 	= "AND A.proj_Code = '$PRJCODE'";
					if($PRJCODE == 'All')
						$QRYPRJ	 = "";

					$QRYSTAT = "";
					if($GEJSTAT == 3)
						$QRYSTAT = "A.GEJ_STAT = 3 AND";
					elseif($GEJSTAT == 9)
						$QRYSTAT = "A.GEJ_STAT = 9 AND";

            		/*$s_00 	= "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType, B.CREATERNM AS Creater, B.Created
								FROM tbl_journaldetail A
									INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
								WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') $QRYPRJ GROUP BY A.JournalH_Code
								ORDER BY B.JournalH_Date, A.JournalH_Code ASC";*/
            		$s_00 	= "SELECT DISTINCT A.JournalH_Code, A.proj_Code, A.JournalH_Date, A.isLock, A.JournalType, B.CREATERNM AS Creater, B.Created
								FROM tbl_journaldetail A
									INNER JOIN tbl_journalheader B ON B.JournalH_Code = A.JournalH_Code
								WHERE $QRYSTAT (A.JournalH_Date BETWEEN '$STARTD' AND '$ENDD') GROUP BY A.JournalH_Code
								ORDER BY B.JournalH_Date, A.JournalH_Code ASC";
					$r_00 	= $this->db->query($s_00)->result();
					$rowTd 	= 0;
					$balance 		= 0;
					$totD 			= 0;
					$totK 			= 0;
					$vtotD 			= number_format($totD,2);
					$vtotK 			= number_format($totK,2);
					foreach($r_00 as $rw_00):
						$JournalH_Code	= $rw_00->JournalH_Code;
						$PRJCODE		= $rw_00->proj_Code;
						$isLock			= $rw_00->isLock;
						$jrnType		= $rw_00->JournalType;
						$Creater		= $rw_00->Creater;
						$Created		= $rw_00->Created;
						$s_01 			= "SELECT A.JournalD_Id, A.JournalH_Code, A.Manual_No, A.JournalH_Date, A.JournalType,
												A.TAX_NO, A.Faktur_Code, A.TAX_DATE, A.Kwitansi_No, A.Kwitansi_Date,
												A.SPLCODE, A.SPLDESC, A.Other_Desc, A.Revise_Desc, A.GEJ_STAT, 
												A.Acc_Id, A.Acc_Name, A.JournalD_Debet AS JournalD_Debet, A.JournalD_Kredit AS JournalD_Kredit, A.Creater
											FROM tbl_journaldetail A
											WHERE  A.JournalH_Code = '$JournalH_Code'
												ORDER BY A.Base_Kredit";
						$r_01			= $this->db->query($s_01)->result();
						$JournalH_Code2 = "";
						foreach($r_01 as $rowJD):
							$rowTd 				= $rowTd+1;
							$JournalD_Id		= $rowJD->JournalD_Id;
							$JournalH_Code		= $rowJD->JournalH_Code;
							$Manual_No			= $rowJD->Manual_No;
							if($Manual_No == '')
								$Manual_No		= $JournalH_Code;	

							$JournalH_Date		= $rowJD->JournalH_Date;
							$JournalH_DateV		= date('d M Y', strtotime($JournalH_Date));
							$JournalType		= $rowJD->JournalType;
							$TAX_NO 			= $rowJD->TAX_NO;
							$Faktur_Code		= $rowJD->Faktur_Code;
							$TAX_DATE 		= $rowJD->TAX_DATE;
							if($TAX_DATE == '' || $TAX_DATE == '0000-00-00')
								$TAX_DATE 	= "";
							else
								$TAX_DATE 	= date('d M Y', strtotime($rowJD->TAX_DATE));

							$Kwitansi_No 		= $rowJD->Kwitansi_No;
							$Kwitansi_Date		= $rowJD->Kwitansi_Date;
							if($Kwitansi_No == '')
								$Kwitansi_Date 	= '';
							
							if($Kwitansi_Date == '')
								$Kwitansi_Date 	= "";
							else
								$Kwitansi_Date 	= date('d M Y', strtotime($rowJD->Kwitansi_Date));

							$SPLCODE 			= $rowJD->SPLCODE;
							$SPLDESC 			= $rowJD->SPLDESC;
							$Other_Desc			= $rowJD->Other_Desc;
							$Revise_Desc		= $rowJD->Revise_Desc;
							$GEJ_STAT			= $rowJD->GEJ_STAT;
					
							$Acc_Id				= $rowJD->Acc_Id;
							$AccNameId			= $rowJD->Acc_Name;
							if($AccNameId == '')
							{
								$sqlAccNm		= "SELECT Account_NameId, Account_NameEn FROM tbl_chartaccount
													WHERE Account_Number = '$Acc_Id'";
								$resAccNm 		= $this->db->query($sqlAccNm)->result();
								foreach($resAccNm as $rowAccNm) :
									$AccNameId = $rowAccNm->Account_NameId;
									$AccNameEn = $rowAccNm->Account_NameEn;
								endforeach;
								
								/*if($this->data['LangID'] == 'IND')
									$AccNameId = $AccNameId;
								else
									$AccNameId = $AccNameId;*/
							}

							$JournalD_Debet		= $rowJD->JournalD_Debet;
							$JournalD_Kredit	= $rowJD->JournalD_Kredit;
							$totD 				= $totD + $JournalD_Debet;
							$totK 				= $totK + $JournalD_Kredit;

							$empName			= $rowJD->Creater;
							//$empName			= wordwrap($CREATERNM, 15, "<br>", true);

							$VJournalD_Debet 	= number_format($JournalD_Debet,2);
							$VJournalD_Kredit 	= number_format($JournalD_Kredit,2);
							$balance 			= $JournalD_Debet - $JournalD_Kredit;
							$vbalance 	        = number_format($balance,2);
							$vtotD 	        	= number_format($totD,2);
							$vtotK 	        	= number_format($totK,2);

							$Other_Desc			= $rowJD->Other_Desc;
							?>
								<tr>
									<td style="text-align:center;"><?=$rowTd?></td>
								  	<td style="text-align:center;"><?=$Manual_No?></td>
								  	<td style="text-align:center;"><?=$JournalH_DateV?></td>
								  	<td style="text-align:center;"><?=$JournalType?></td>
									<td style="text-align:center;" class="str"><?php echo $Acc_Id; ?></td>
									<td><?=$AccNameId?></td>
									<td><?=$Other_Desc?></td>
								  	<td style="text-align:right;"><?=$VJournalD_Debet?></b></td>
								  	<td style="text-align:right;"><?=$VJournalD_Kredit?></b></td>
								  	<td style="text-align:right;"><?=$vbalance?></td>
									<td style="text-align:center;"><?=$PRJCODE?></td>
									<td style="text-align:center;"><?=$Faktur_Code?></td>
									<td style="text-align:center;"><?=$SPLCODE?></td>
									<td><?=$SPLDESC?></td>
									<td style="text-align:center;"><?=$Kwitansi_Date?></td>
									<td style="text-align:center;"><?=$Kwitansi_No?></td>
									<td style="text-align:center;"><?=$TAX_DATE?></td>
									<td style="text-align:center;" class="str"><?php echo $TAX_NO; ?></td>
									<td style="text-align:center;"><?=$empName?></td>
									<td style="text-align:center;"><?=$Created?></td>
								</tr>
							<?php
						endforeach;
					endforeach;
					$totBal 	= $totD - $totK;
					$vtotBal 	= number_format($totBal,2);
            	?>
				<tr>
					<td>&nbsp;</td>
				  	<td>&nbsp;</td>
				  	<td>&nbsp;</td>
				  	<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  	<td style='text-align:right;'><?=$vtotD?></td>
				  	<td style='text-align:right;'><?=$vtotK?></td>
				  	<td style='text-align:right;'><?=$vtotBal?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
            </tbody>
    	</table>
	</body>
</html>