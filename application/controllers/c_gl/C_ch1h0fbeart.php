<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 November 2017
 * File Name	= C_ch1h0fbeart.php
 * Location		= -
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_ch1h0fbeart extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		$this->load->model('m_company/m_budget/m_budget', '', TRUE);
	}
	
 	public function index() // G
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$url			= site_url('c_gl/c_ch1h0fbeart/id1h0fbx1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
    function id1h0fbx1() // G
	{
		//$LinkAcc1	= explode("~", $LinkAcc);
		$collPRJ	= "AllPRJ";
		$LinkAcc	= 1;
		
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			$PRJCODE	= '';
			/*$sql 		= "SELECT A.proj_Code
								FROM tbl_employee_proj A
								LEFT JOIN tbl_project B ON B.PRJCODE = A.proj_Code
								WHERE A.Emp_ID = '$DefEmp_ID' LIMIT 1";
			$result 	= $this->db->query($sql)->result();
			foreach($result as $row) :
				$selPRJCODE 	= $row->proj_Code;
			endforeach;
			$collPRJ	= $selPRJCODE;*/

			// ADA PROSEDUR BARU, KARENA UNTUK COA HARUS MENAMPILKAN COA HO, MAKA HARUS BISA MENAMPILKAN COA HO WALAU TIDAK ADA DIS ETTING PROYEKNYA
			$sql 		= "SELECT A.PRJCODE FROM tbl_project A WHERE A.isHO = 1 LIMIT 1";
			$result 	= $this->db->query($sql)->result();
			foreach($result as $row) :
				$PRJCODE = $row->PRJCODE;
			endforeach;
			$collPRJ	= $PRJCODE;
			
			$data['viewCOA'] 	= $this->m_coa->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			$data["selPRJCODE"] = 'AllPRJ';
			$data["selPRJCODE"] = $collPRJ;
			$data["AccCateg"] 	= 1;
			
			$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }

	function get_all_ofCOA() // G
	{
		$collDATA	= $_GET['id'];
		$thePRJCODE	= $_GET['thePrj'];
		$theCateg	= $_GET['tC4t'];
		$LinkAcc1	= $this->url_encryption_helper->decode_url($collDATA);	
		$LinkAcc	= explode("~", $LinkAcc1);
		$collPRJ	= $LinkAcc[0];
		//$LinkAcc	= $LinkAcc[1];
		$LinkAcc	= $theCateg;
		
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $this->session->userdata['Emp_ID'];
			$PRJCODE	= '';

			// ADA PROSEDUR BARU, KARENA UNTUK COA HARUS MENAMPILKAN COA HO, MAKA HARUS BISA MENAMPILKAN COA HO WALAU TIDAK ADA DIS ETTING PROYEKNYA
			$sql 		= "SELECT A.PRJCODE FROM tbl_project A WHERE A.isHO = 1 LIMIT 1";
			$result 	= $this->db->query($sql)->result();
			foreach($result as $row) :
				$PRJCODE = $row->PRJCODE;
			endforeach;
			$collPRJ	= $thePRJCODE;
			
			$data['viewCOA'] 	= $this->m_coa->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			$data["selPRJCODE"] = 'AllPRJ';
			$data["selPRJCODE"] = $collPRJ;
			$data["AccCateg"] 	= $LinkAcc;
			
			$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK

			$this->load->view('v_gl/v_coa/coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

  	function get_AllDataCOA_221214() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$ACCOUNTID	= $this->input->post('ACCOUNTID');
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$accYr		= $_GET['tYr'];
		$theCateg	= $_GET['tC4t'];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$accYrV		= $accYr;
		if($accYrV == "All")
			$accYr 	= date('Y');

		$sqlPRJL 	= "SELECT PRJLEV FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJL 	= $this->db->query($sqlPRJL)->result();
		foreach($resPRJL as $rowPRJL) :
			$PRJLEV = $rowPRJL->PRJLEV;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("ORD_ID", 
									"Account_Number", 
									"Account_NameId",
									"Account_NameEn");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataCOAC($PRJCODE, $ACCOUNTID, $theCateg, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataCOAL($PRJCODE, $ACCOUNTID, $theCateg, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$Acc_ID					= $dataI['Acc_ID'];
                $Account_Category		= $dataI['Account_Category'];
                $Account_Number			= $dataI['Account_Number'];
                $Account_NameEn			= $dataI['Account_NameEn'];
                $Base_OpeningBalance	= $dataI['Base_OpeningBalance'];
                $COGSReportID			= $dataI['COGSReportID'];

                $AccView				= "$Account_Number $Account_NameEn";
                
                if($dataI['Default_Acc'] == "D") $DAName = "Debit"; else $DAName = "Credit";
				
				$Account_Class			= $dataI['Account_Class'];

				$CELLCOL 				= "";
                if($Account_Class == 1)
                {
                	$ACName = "Header";
                	$JDDesc	= "Header";
                	$isDisB	= 'disabled="disabled"';
                	$CELLCOL= "font-weight:bold; white-space:nowrap";
                }
                elseif($Account_Class == 2)
                { $ACName = "Detail"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 3)
                { $ACName = "Detail Cash"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 4)
                { $ACName = "Detail Bank"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 5)
                { $ACName = "Detail Cheque"; $JDDesc = ""; $isDisB = ""; }

                $Account_Level	= $dataI['Account_Level'];
				
                if($dataI['Account_Level'] == 0)
                { $LongSpace = ""; }
                elseif($dataI['Account_Level'] == 1)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 2)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 3)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 4)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 5)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 6)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }

            	$collID		= "$Acc_ID~$PRJCODE~$PRJCODE";
                $secUpd		= site_url('c_comprof/c_bUd93tL15t/up1h0fbt/?id='.$this->url_encryption_helper->encode_url($collID));

                // CHECK IN JOURNAL
                	$JDDesc 	= "-";
                	$sqlJDC1	= "tbl_journaldetail_$PRJCODEVW WHERE Acc_Id = '$Account_Number'";
            		$resJDC1	= intval($this->db->count_all($sqlJDC1));
            		if($resJDC1 > 0)
            			$JDDesc	= $JDDesc."Journal";

                	$sqlJDC2	= "tbl_item_$PRJCODEVW WHERE ACC_ID = '$Account_Number' OR ACC_ID_UM = '$Account_Number'";
            		$resJDC2	= intval($this->db->count_all($sqlJDC2));
            		if($resJDC2 > 0)
            			$JDDesc	= $JDDesc." - Master Material";

                	$sqlJDC3	= "tbl_vendcat WHERE VC_LA_PAYDP = '$Account_Number' OR VC_LA_PAYINV = '$Account_Number'
                						OR VC_LA_RET = '$Account_Number'";
            		$resJDC3	= intval($this->db->count_all($sqlJDC3));
            		if($resJDC3 > 0)
            			$JDDesc	= $JDDesc." - Supl. Cat";

                	$sqlJDC4	= "tbl_custcat WHERE CC_LA_CINV = '$Account_Number' OR CC_LA_RECDP = '$Account_Number'";
            		$resJDC4	= intval($this->db->count_all($sqlJDC4));
            		if($resJDC4 > 0)
            			$JDDesc	= $JDDesc." - Cust. Cat";

            		$AccisUsed	= $resJDC1 + $resJDC2 + $resJDC3 + $resJDC4;
            		if($AccisUsed > 0)
            		{
            			$isDisB = 'disabled="disabled"';
            		}

					if($PRJCODE == "AllPRJ")
					{
						$ADDQUERY	= "";
					}
					else
					{
						$ADDQUERY	= "AND PRJCODE = '$PRJCODE'";
					}
					
					if($PRJLEV == 1)
					{
						$TBL 		= $dataI['tot'];
					}
					else
					{
						/*$Base_Debet = $dataI['Base_Debet'];
						$Base_Kredit= $dataI['Base_Kredit'];*/
						$Base_Debet = $dataI['BaseD_'.$accYr];
						$Base_Kredit= $dataI['BaseK_'.$accYr];
						$TBL 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
					}
					$isLast			= $dataI['isLast'];
					$Account_Numb 	= $Account_Number;

					$ACCBAL 		= $TBL;

					// JIKA HEADER, CARI TURUNANNYA - 1
					if($isLast == 0)
					{
						/*$sql2A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit,
										BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
									FROM tbl_chartaccount_$PRJCODEVW
									WHERE Acc_DirParent = '$Account_Number' 
										$ADDQUERY
										AND Account_Category = '$Account_Category'";											
						$res2A	= $this->db->query($sql2A)->result();
						$TLEV2A	= 0;
						foreach($res2A as $row2A):
							$Acc2A	= $row2A->Account_Number;
							$OpB2A	= $row2A->Base_OpeningBalance;
							if($accYrV == "All")
							{
								$BD2A	= $row2A->Base_Debet;
								$BK2A	= $row2A->Base_Kredit;
							}
							else
							{
								$BD2A	= $row2A->BaseDYear;
								$BK2A	= $row2A->BaseKYear;
							}
							$Last2A	= $row2A->isLast;
							// JIKA HEADER, CARI TURUNANNYA - 2
							$TLEV3A	= 0;
							if($Last2A == 0)
							{
								$sql3A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit,
												BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
											FROM tbl_chartaccount_$PRJCODEVW
											WHERE Acc_DirParent = '$Acc2A' 
												$ADDQUERY
												AND Account_Category = '$Account_Category'";											
								$res3A	= $this->db->query($sql3A)->result();
								foreach($res3A as $row3A):
									$Acc3A	= $row3A->Account_Number;
									$OpB3A	= $row3A->Base_OpeningBalance;
									if($accYrV == "All")
									{
										$BD3A	= $row3A->Base_Debet;
										$BK3A	= $row3A->Base_Kredit;
									}
									else
									{
										$BD3A	= $row3A->BaseDYear;
										$BK3A	= $row3A->BaseKYear;
									}
									$Last3A	= $row3A->isLast;
									// JIKA HEADER, CARI TURUNANNYA - 3
									$TLEV4A	= 0;
									if($Last3A == 0)
									{
										$sql4A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit,
														BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
													FROM tbl_chartaccount_$PRJCODEVW
													WHERE Acc_DirParent = '$Acc3A' 
														$ADDQUERY
														AND Account_Category = '$Account_Category'";											
										$res4A	= $this->db->query($sql4A)->result();
										foreach($res4A as $row4A):
											$Acc4A	= $row4A->Account_Number;
											$OpB4A	= $row4A->Base_OpeningBalance;
											if($accYrV == "All")
											{
												$BD4A	= $row4A->Base_Debet;
												$BK4A	= $row4A->Base_Kredit;
											}
											else
											{
												$BD4A	= $row4A->BaseDYear;
												$BK4A	= $row4A->BaseKYear;
											}
											$Last4A	= $row4A->isLast;	
											// JIKA HEADER, CARI TURUNANNYA - 4
											$TLEV5A	= 0;
											if($Last4A == 0)
											{
												$sql5A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit,
																BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
															FROM tbl_chartaccount_$PRJCODEVW
															WHERE Acc_DirParent = '$Acc4A' 
																$ADDQUERY
																AND Account_Category = '$Account_Category'";											
												$res5A	= $this->db->query($sql5A)->result();
												foreach($res5A as $row5A):
													$Acc5A	= $row5A->Account_Number;
													$OpB5A	= $row5A->Base_OpeningBalance;
													if($accYrV == "All")
													{
														$BD5A	= $row5A->Base_Debet;
														$BK5A	= $row5A->Base_Kredit;
													}
													else
													{
														$BD5A	= $row5A->BaseDYear;
														$BK5A	= $row5A->BaseKYear;
													}
													$Last5A	= $row5A->isLast;
													$TLEV6A	= 0;
													if($Last5A == 0)
													{
														$sql6A	= "SELECT Account_Number, isLast, Base_OpeningBalance, Base_Debet, Base_Kredit,
																		BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
																	FROM tbl_chartaccount_$PRJCODEVW
																	WHERE Acc_DirParent = '$Acc5A' 
																		$ADDQUERY
																		AND Account_Category = '$Account_Category'";											
														$res6A	= $this->db->query($sql6A)->result();
														foreach($res6A as $row6A):
															$Acc6A	= $row6A->Account_Number;
															$OpB6A	= $row6A->Base_OpeningBalance;
															if($accYrV == "All")
															{
																$BD6A	= $row6A->Base_Debet;
																$BK6A	= $row6A->Base_Kredit;
															}
															else
															{
																$BD6A	= $row6A->BaseDYear;
																$BK6A	= $row6A->BaseKYear;
															}
															$Last6A	= $row6A->isLast;
															$TLEV7A	= 0;
															if($Last6A == 0)
															{
																$sql7A	= "SELECT Account_Number, isLast, 
																				Base_OpeningBalance, Base_Debet, Base_Kredit,
																				BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
																			FROM tbl_chartaccount_$PRJCODEVW
																			WHERE Acc_DirParent = '$Acc6A' 
																				$ADDQUERY
																				AND Account_Category = '$Account_Category'";											
																$res7A	= $this->db->query($sql7A)->result();
																foreach($res7A as $row7A):
																	$Acc7A	= $row7A->Account_Number;
																	$OpB7A	= $row7A->Base_OpeningBalance;
																	if($accYrV == "All")
																	{
																		$BD7A	= $row7A->Base_Debet;
																		$BK7A	= $row7A->Base_Kredit;
																	}
																	else
																	{
																		$BD7A	= $row7A->BaseDYear;
																		$BK7A	= $row7A->BaseKYear;
																	}
																	$Last7A	= $row7A->isLast;
																	$TLEV8A	= 0;
																	if($Last7A == 0)
																	{
																		$sql8A	= "SELECT Account_Number, isLast, 
																						Base_OpeningBalance, Base_Debet, Base_Kredit,
																						BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
																					FROM tbl_chartaccount_$PRJCODEVW
																					WHERE Acc_DirParent = '$Acc7A' 
																						$ADDQUERY
																						AND Account_Category = '$Account_Category'";											
																		$res8A	= $this->db->query($sql8A)->result();
																		foreach($res8A as $row8A):
																			$Acc8A	= $row8A->Account_Number;
																			$OpB8A	= $row8A->Base_OpeningBalance;
																			if($accYrV == "All")
																			{
																				$BD8A	= $row8A->Base_Debet;
																				$BK8A	= $row8A->Base_Kredit;
																			}
																			else
																			{
																				$BD8A	= $row8A->BaseDYear;
																				$BK8A	= $row8A->BaseKYear;
																			}
																			$Last8A	= $row8A->isLast;
																			$TLEV9A	= 0;
																			if($Last8A == 0)
																			{
																				$sql9A	= "SELECT Account_Number, isLast, 
																								Base_OpeningBalance, Base_Debet, Base_Kredit,
																								BaseD_$accYr AS BaseDYear, BaseK_$accYr AS BaseKYear
																							FROM tbl_chartaccount_$PRJCODEVW
																							WHERE Acc_DirParent = '$Acc8A' 
																								$ADDQUERY
																								AND Account_Category = '$Account_Category'";											
																				$res9A	= $this->db->query($sql9A)->result();
																				foreach($res9A as $row9A):
																					$Acc9A	= $row9A->Account_Number;
																					$OpB9A	= $row9A->Base_OpeningBalance;
																					if($accYrV == "All")
																					{
																						$BD9A	= $row9A->Base_Debet;
																						$BK9A	= $row9A->Base_Kredit;
																					}
																					else
																					{
																						$BD9A	= $row9A->BaseDYear;
																						$BK9A	= $row9A->BaseKYear;
																					}
																					$Last9A	= $row9A->isLast;
																					$TLEV10A= 0;
																					$TLEV9A	= $TLEV9A + $OpB9A + $BD9A - $BK9A + $TLEV10A;
																				endforeach;
																			}
																			$TLEV8A	= $TLEV8A + $OpB8A + $BD8A - $BK8A + $TLEV9A;
																		endforeach;
																	}
																	$TLEV7A	= $TLEV7A + $OpB7A + $BD7A - $BK7A + $TLEV8A;
																endforeach;
															}
															$TLEV6A	= $TLEV6A + $OpB6A + $BD6A - $BK6A + $TLEV7A;
														endforeach;
													}
													$TLEV5A	= $TLEV5A + $OpB5A + $BD5A - $BK5A + $TLEV6A;
												endforeach;
											}
											$TLEV4A	= $TLEV4A + $OpB4A + $BD4A - $BK4A + $TLEV5A;
										endforeach;
									}
									$TLEV3A	= $TLEV3A + $OpB3A + $BD3A - $BK3A + $TLEV4A;
								endforeach;
							}
							$TLEV2A	= $TLEV2A + $OpB2A + $BD2A - $BK2A + $TLEV3A;
						endforeach;
						$TBL	= $TBL + $TLEV2A;*/

						$s_ACCBAL	= "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOTBAL
										FROM tbl_chartaccount_$PRJCODEVW
										WHERE Account_Number LIKE '$Account_Number%' AND ISLAST = 1";											
						$r_ACCBAL	= $this->db->query($s_ACCBAL)->result();
						foreach($r_ACCBAL as $r_ACCBAL):
							$TOTBAL	= $r_ACCBAL->TOTBAL;
						endforeach;
						$ACCBAL 	= $TOTBAL;
					}

					$balanceValv 	= number_format($ACCBAL, 2);
					if($isLast == 1)
                    {
						$balanceValv1	= 	'<a href="javascript:void(null);" title="'.$JDDesc.'" onClick="showDetC(\''.$Account_Numb.'\')" style="cursor: pointer; color:#000" >
		                                        '.$balanceValv.'
		                                    </a>';
                    }
                    else
                    {
						$balanceValv1 	= $balanceValv;
                    }

					$secAction		= 	"<label style='white-space:nowrap'>
										   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
												<i class='glyphicon glyphicon-pencil'></i>
										   	</a>
											<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='".$JDDesc."' onClick='delCOA(".$noU.")' ".$isDisB.">
		                                        <i class='glyphicon glyphicon-trash'></i>
		                                    </a>
										</label>";

					/*$output['data'][] 	= array("<div style='text-align:left; ".$CELLCOL."''>".$LongSpace.$AccView."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$COGSReportID."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$ACName."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$DAName."</div>",
												"<div style='text-align:right; ".$CELLCOL."''>".$balanceValv1."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$secAction." v</div>");*/

					$output['data'][] 	= array("<div style='text-align:left; ".$CELLCOL."''>".$LongSpace.$AccView."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$COGSReportID."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$ACName."</div>",
												//"<div style='text-align:center; ".$CELLCOL."''>".$DAName."</div>",
												"<div style='text-align:right; ".$CELLCOL."''>".$balanceValv1."</div>");
				$noU			= $noU + 1;
			}
		// END : FOR SERVER-SIDE

		echo json_encode($output);
	}

  	function get_AllDataCOA() // GOOD
	{
		setlocale(LC_ALL, 'id-ID', 'id_ID');

		$ACCOUNTID	= $this->input->post('ACCOUNTID');
		$this->load->model('m_projectlist/m_projectlist', '', TRUE);
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$PRJCODE	= $_GET['id'];
		$accYr		= $_GET['tYr'];
		$theCateg	= $_GET['tC4t'];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$accYrV		= $accYr;
		if($accYrV == "All")
			$accYr 	= date('Y');

		$sqlPRJL 	= "SELECT PRJLEV FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJL 	= $this->db->query($sqlPRJL)->result();
		foreach($resPRJL as $rowPRJL) :
			$PRJLEV = $rowPRJL->PRJLEV;
		endforeach;

		$LangID     = $this->session->userdata['LangID'];
        
        $sqlTransl      = "SELECT MLANG_CODE, MLANG_$LangID AS LangTransl FROM tbl_translate";
        $resTransl      = $this->db->query($sqlTransl)->result();
        foreach($resTransl as $rowTransl) :
            $TranslCode = $rowTransl->MLANG_CODE;
            $LangTransl = $rowTransl->LangTransl;

    		if($TranslCode == 'Director')$Director = $LangTransl;
            if($TranslCode == 'Compailer')$Compailer = $LangTransl;
            if($TranslCode == 'Location')$Location = $LangTransl;
    		if($TranslCode == 'Active')$Active = $LangTransl;
    		if($TranslCode == 'Inactive')$Inactive = $LangTransl;
    		if($TranslCode == 'Contact')$Contact = $LangTransl;
    		if($TranslCode == 'CompanyName')$CompanyName = $LangTransl;
    		if($TranslCode == 'Budget')$Budget = $LangTransl;
    		if($TranslCode == 'Phone')$Phone = $LangTransl;
    		if($TranslCode == 'Notes')$Notes = $LangTransl;
        endforeach;
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("ORD_ID", 
									"Account_Number", 
									"Account_NameId",
									"Account_NameEn");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_budget->get_AllDataCOAC($PRJCODE, $ACCOUNTID, $theCateg, $length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			
			$query 			= $this->m_budget->get_AllDataCOAL($PRJCODE, $ACCOUNTID, $theCateg, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$Acc_ID					= $dataI['Acc_ID'];
                $Account_Category		= $dataI['Account_Category'];
                $Account_Number			= $dataI['Account_Number'];
                $Account_NameEn			= $dataI['Account_NameEn'];
                $Base_OpeningBalance	= $dataI['Base_OpeningBalance'];
                $COGSReportID			= $dataI['COGSReportID'];

                $AccView				= "$Account_Number $Account_NameEn";
                
                if($dataI['Default_Acc'] == "D") $DAName = "Debit"; else $DAName = "Credit";
				
				$Account_Class			= $dataI['Account_Class'];

				$CELLCOL 				= "";
                if($Account_Class == 1)
                {
                	$ACName = "Header";
                	$JDDesc	= "Header";
                	$isDisB	= 'disabled="disabled"';
                	$CELLCOL= "font-weight:bold; white-space:nowrap";
                }
                elseif($Account_Class == 2)
                { $ACName = "Detail"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 3)
                { $ACName = "Detail Cash"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 4)
                { $ACName = "Detail Bank"; $JDDesc = ""; $isDisB = ""; }
                elseif($Account_Class == 5)
                { $ACName = "Detail Cheque"; $JDDesc = ""; $isDisB = ""; }

                $Account_Level	= $dataI['Account_Level'];
				
                if($dataI['Account_Level'] == 0)
                { $LongSpace = ""; }
                elseif($dataI['Account_Level'] == 1)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 2)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 3)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 4)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 5)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }
                elseif($dataI['Account_Level'] == 6)
                { $LongSpace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; }

            	$collID		= "$Acc_ID~$PRJCODE~$PRJCODE";
                $secUpd		= site_url('c_comprof/c_bUd93tL15t/up1h0fbt/?id='.$this->url_encryption_helper->encode_url($collID));

                // CHECK IN JOURNAL
                	$resJDC1 	= 0;
                	$resJDC2 	= 0;
                	$resJDC3 	= 0;
                	$resJDC4 	= 0;

                	$JDDesc 	= "-";
                	/*$sqlJDC1	= "tbl_journaldetail_$PRJCODEVW WHERE Acc_Id = '$Account_Number'";
            		$resJDC1	= intval($this->db->count_all($sqlJDC1));
            		if($resJDC1 > 0)
            			$JDDesc	= $JDDesc."Journal";*/

                	/*$sqlJDC2	= "tbl_item_$PRJCODEVW WHERE ACC_ID = '$Account_Number' OR ACC_ID_UM = '$Account_Number'";
            		$resJDC2	= intval($this->db->count_all($sqlJDC2));
            		if($resJDC2 > 0)
            			$JDDesc	= $JDDesc." - Master Material";*/

                	/*$sqlJDC3	= "tbl_vendcat WHERE VC_LA_PAYDP = '$Account_Number' OR VC_LA_PAYINV = '$Account_Number'
                						OR VC_LA_RET = '$Account_Number'";
            		$resJDC3	= intval($this->db->count_all($sqlJDC3));
            		if($resJDC3 > 0)
            			$JDDesc	= $JDDesc." - Supl. Cat";*/

                	/*$sqlJDC4	= "tbl_custcat WHERE CC_LA_CINV = '$Account_Number' OR CC_LA_RECDP = '$Account_Number'";
            		$resJDC4	= intval($this->db->count_all($sqlJDC4));
            		if($resJDC4 > 0)
            			$JDDesc	= $JDDesc." - Cust. Cat";*/

            		$AccisUsed	= $resJDC1 + $resJDC2 + $resJDC3 + $resJDC4;
            		if($AccisUsed > 0)
            		{
            			$isDisB = 'disabled="disabled"';
            		}

					if($PRJCODE == "AllPRJ")
					{
						$ADDQUERY	= "";
					}
					else
					{
						$ADDQUERY	= "AND PRJCODE = '$PRJCODE'";
					}
					
					if($PRJLEV == 1)
					{
						$TBL 		= $dataI['tot'];
					}
					else
					{
						/*$Base_Debet = $dataI['Base_Debet'];
						$Base_Kredit= $dataI['Base_Kredit'];*/
						$Base_Debet = $dataI['BaseD_'.$accYr];
						$Base_Kredit= $dataI['BaseK_'.$accYr];
						$TBL 		= $Base_OpeningBalance + $Base_Debet - $Base_Kredit;
					}
					$isLast			= $dataI['isLast'];
					$Account_Numb 	= $Account_Number;

					$ACCBAL 		= $TBL;

					// JIKA HEADER, CARI TURUNANNYA - 1
					if($isLast == 0)
					{
						$s_ACCBAL	= "SELECT SUM(Base_OpeningBalance + Base_Debet - Base_Kredit) AS TOTBAL
										FROM tbl_chartaccount_$PRJCODEVW
										WHERE Account_Number LIKE '$Account_Number%' AND ISLAST = 1";											
						$r_ACCBAL	= $this->db->query($s_ACCBAL)->result();
						foreach($r_ACCBAL as $r_ACCBAL):
							$TOTBAL	= $r_ACCBAL->TOTBAL;
						endforeach;
						$ACCBAL 	= $TOTBAL;
					}

					$balanceValv 	= number_format($ACCBAL, 2);
					if($isLast == 1)
                    {
						$balanceValv1	= 	'<a href="javascript:void(null);" title="'.$JDDesc.'" onClick="showDetC(\''.$Account_Numb.'\')" style="cursor: pointer; color:#000" >
		                                        '.$balanceValv.'
		                                    </a>';
                    }
                    else
                    {
						$balanceValv1 	= $balanceValv;
                    }

					$secAction		= 	"<label style='white-space:nowrap'>
										   	<a href='".$secUpd."' class='btn btn-warning btn-xs' title='Update'>
												<i class='glyphicon glyphicon-pencil'></i>
										   	</a>
											<a href='javascript:void(null);' class='btn btn-danger btn-xs' title='".$JDDesc."' onClick='delCOA(".$noU.")' ".$isDisB.">
		                                        <i class='glyphicon glyphicon-trash'></i>
		                                    </a>
										</label>";

					/*$output['data'][] 	= array("<div style='text-align:left; ".$CELLCOL."''>".$LongSpace.$AccView."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$COGSReportID."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$ACName."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$DAName."</div>",
												"<div style='text-align:right; ".$CELLCOL."''>".$balanceValv1."</div>",
												"<div style='text-align:center; ".$CELLCOL."''>".$secAction." v</div>");*/

					$output['data'][] 	= array("<div style='text-align:left; ".$CELLCOL."''>".$LongSpace.$AccView."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$COGSReportID."</div>",
										  		"<div style='text-align:center; ".$CELLCOL."''>".$ACName."</div>",
												//"<div style='text-align:center; ".$CELLCOL."''>".$DAName."</div>",
												"<div style='text-align:right; ".$CELLCOL."''>".$balanceValv1."</div>");
				$noU			= $noU + 1;
			}
		// END : FOR SERVER-SIDE

		echo json_encode($output);
	}

	function get_all_ofCOAIDX() // G
	{
		$LinkAcc	= 1;
		$collPRJ	= $this->input->post('selPRJCODE');
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['viewCOA'] 	= $this->m_coa->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
			$data["selPRJCODE"] = $collPRJ;
			$MenuCode 			= 'MN105';
			$data["MenuCode"] 	= 'MN105';
			//$data["selPRJCODE"] = 'AllPRJ';
			$data["AccCateg"] 	= $LinkAcc;
			
			$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'L';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180e2edd() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
		
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_gl/c_ch1h0fbeart/add_process');
			$data['backURL'] 		= site_url('c_gl/c_ch1h0fbeart/?id='.$this->url_encryption_helper->encode_url($appName));
			$MenuCode 				= 'MN105';
			$data["MenuCode"] 		= 'MN105';
			$data['viewDocPattern'] = $this->m_coa->getDataDocPat($MenuCode)->result();
			
			$data['PRJCODE'] 		= $PRJCODE;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'A';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
		
	function getTheCode($ACC_NUM) // OK
	{ 	
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		$countMLCode 	= $this->m_coa->count_acc_num($ACC_NUM);
		echo $countMLCode;
	}
	
	function add_process() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$this->db->trans_begin();
		
		$comp_init 	= $this->session->userdata('comp_init');
		$collPRJ	= "AllPRJ";
		$LinkAcc	= 1;
		
		$Acc_ID			= $this->input->post('Acc_ID');
		$Account_Number	= $this->input->post('Account_Number');
		$collData		= $this->input->post('Acc_DirParent');
		$Account_Class	= $this->input->post('Account_Class');
		if($Account_Class == 1)
			$isLast	= 0;
		else
			$isLast	= 1;

		$splitCode 				= explode("~", $collData);
		$Acc_DirParent			= $splitCode[0];
		$Acc_ParentList1		= $splitCode[1];
		$Acc_ParentList			= "$Acc_ParentList1;$Acc_DirParent";
		$Account_NameEn			= $this->input->post('Account_NameEn');
		$Account_NameId			= $this->input->post('Account_NameId');
		$Account_Category	 	= $this->input->post('Account_Category');
		$Account_Class		 	= $this->input->post('Account_Class');
		$Default_Acc		 	= $this->input->post('Default_Acc');
		$Base_OpeningBalance 	= $this->input->post('Base_OpeningBalance');
		$COGSReportID		 	= $this->input->post('COGSReportID');
		$Acc_Group			 	= $this->input->post('Acc_Group');
		$PRJCODE				= $this->input->post('PRJCODE');
		$PRJCODEVW 				= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));

		$isHO					= $this->input->post('isHO');
		$isSync					= $this->input->post('isSync');
		
		// GET PROJECT PARENT
			$s_PRJPAR 	= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 LIMIT 1";
			$r_PRJPAR 	= $this->db->query($s_PRJPAR)->result();
			foreach($r_PRJPAR as $rw_PRJPAR) :
				$PRJ_HO = $rw_PRJPAR->PRJCODE;
			endforeach;
		
		// UPDATE HEADER
			$sqlUPDH 	= "UPDATE tbl_chartaccount SET Account_Class = 1, isLast = 0 WHERE Account_Number = '$Acc_DirParent'";
			$this->db->query($sqlUPDH);

		// CARI LEVEL PARENT
			$sqlACLEV 	= "SELECT Account_Level FROM tbl_chartaccount_$PRJCODEVW 
							WHERE Account_Number = '$Acc_DirParent' AND PRJCODE = '$PRJCODE'";
			$resACLEV 	= $this->db->query($sqlACLEV)->result();
			foreach($resACLEV as $rowLEV) :	
				$Account_Level1 = $rowLEV->Account_Level;	
			endforeach;
			$Account_Level	= $Account_Level1 + 1;
			
		// START : SAVE TO ALL PROJECT
			$sqlPRJ		= "SELECT PRJCODE, isHO FROM tbl_project";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJ_CODE 	= $rowPRJ->PRJCODE;
				$PRJ_CODEVW = strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJ_CODE));
				$isHO 		= $rowPRJ->isHO;

				// START : GET MAX ORD_ID IN COA
					$MAXORDID	= 0;
					$sqlMAXCOA 	= "SELECT IFNULL(MAX(ORD_ID),0) AS MAXORDID FROM tbl_chartaccount WHERE Acc_DirParent = '$Acc_DirParent' AND PRJCODE = '$PRJ_CODE'";
					$resMAXCOA 	= $this->db->query($sqlMAXCOA)->result();
					foreach($resMAXCOA as $rowMAXCOA) :	
						$MAXORDID 	= $rowMAXCOA->MAXORDID;	
					endforeach;

					if($MAXORDID == 0)
					{
							$sqlMAXCOA 	= "SELECT IFNULL(MAX(ORD_ID),0) AS MAXORDID 
											FROM tbl_chartaccount WHERE Account_Number = '$Acc_DirParent' AND PRJCODE = '$PRJ_CODE'";
							$resMAXCOA 	= $this->db->query($sqlMAXCOA)->result();
							foreach($resMAXCOA as $rowMAXCOA) :	
								$MAXORDID 	= $rowMAXCOA->MAXORDID;	
							endforeach;
					}

					$NEW_ORID	= $MAXORDID + 1;

					$upd_ordid 	= "UPDATE tbl_chartaccount SET ORD_ID = ORD_ID+1 WHERE ORD_ID > $MAXORDID AND PRJCODE = '$PRJ_CODE'";
					$this->db->query($upd_ordid);
				// END : GET MAX ORD_ID IN COA

				$SELPRJ_P 	= $PRJCODE;
				$PRJ_SYNC 	= $PRJCODE;
				$sqlPRJ_P 	= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJ_CODE' LIMIT 1";
				$resPRJ_P 	= $this->db->query($sqlPRJ_P)->result();
				foreach($resPRJ_P as $rowPRJ_P) :
					$SELPRJ_P = $rowPRJ_P->PRJCODE_HO;
					$s_PRJSYN 	= "SELECT PRJ_SYNC FROM tbl_project_h WHERE PRJCODE = '$SELPRJ_P' LIMIT 1";
					$r_PRJSYN 	= $this->db->query($s_PRJSYN)->result();
					foreach($r_PRJSYN as $rw_PRJSYN) :
						$PRJ_SYNC = $rw_PRJSYN->PRJ_SYNC;
					endforeach;
				endforeach;
					
				$coaIns = array('Acc_ID'				=> $Account_Number,
								'ORD_ID'				=> $NEW_ORID,
								'Account_Class'			=> $Account_Class,
								'PRJCODE'				=> $PRJ_CODE,
								'PRJCODE_HO'			=> $SELPRJ_P,
								'Account_Number'		=> $Account_Number,
								'Account_NameEn'		=> $Account_NameEn,
								'Account_NameId'		=> $Account_NameId,
								'Acc_DirParent'			=> $Acc_DirParent,
								'Company_ID'			=> $comp_init,
								'Account_Level'			=> $Account_Level,
								'Account_Category'		=> $Account_Category,
								'Account_Class'			=> $Account_Class,
								'Default_Acc'			=> $Default_Acc,
								'Base_OpeningBalance'	=> $Base_OpeningBalance,
								'COGSReportID'			=> $COGSReportID,
								'Acc_Group'				=> $Acc_Group,
								'isHO'					=> $isHO,
								'syncPRJ'				=> $PRJ_SYNC,
								'isLast'				=> $isLast);
				$this->m_coa->add($coaIns);
			endforeach;
		// END : SAVE TO ALL PROJECT
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN105';
			$TTR_CATEG		= 'UP';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$url			= site_url('c_gl/c_ch1h0fbeart/id1h0fbx1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function up1h0fbt() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collData	= $_GET['id'];
			$collData1	= $this->url_encryption_helper->decode_url($collData);
			$splitCode 	= explode("~", $collData1);
			$Acc_ID		= $splitCode[0];
			$PRJCODE	= $splitCode[1];
		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['form_action']	= site_url('c_gl/c_ch1h0fbeart/update_process');
			$data['backURL'] 		= site_url('c_gl/c_ch1h0fbeart/?id='.$this->url_encryption_helper->encode_url($appName));
			$MenuCode 				= 'MN105';
			$data["MenuCode"] 		= 'MN105';
			
			$data["PRJCODE"] 		= $PRJCODE;
			$getdepartment = $this->m_coa->get_coa_by_code($Acc_ID, $PRJCODE)->row();
			
			$data['default']['Acc_ID'] 				= $getdepartment->Acc_ID;
			$data['default']['ORD_ID'] 				= $getdepartment->ORD_ID;
			$data['default']['PRJCODE'] 			= $getdepartment->PRJCODE;
			$data['default']['Account_Class'] 		= $getdepartment->Account_Class;
			$data['default']['Account_Number'] 		= $getdepartment->Account_Number;
			$data['default']['Account_NameEn'] 		= $getdepartment->Account_NameEn;
			$data['default']['Account_NameId'] 		= $getdepartment->Account_NameId;
			$data['default']['Account_Category'] 	= $getdepartment->Account_Category;
			$data['default']['Account_Level'] 		= $getdepartment->Account_Level;
			$data['default']['Acc_DirParent'] 		= $getdepartment->Acc_DirParent;
			$data['default']['Acc_ParentList'] 		= $getdepartment->Acc_ParentList;
			$data['default']['Acc_StatusLinked'] 	= $getdepartment->Acc_StatusLinked;
			$data['default']['Default_Acc'] 		= $getdepartment->Default_Acc;
			$data['default']['COGSReportID'] 		= $getdepartment->COGSReportID;
			$data['default']['Acc_Group'] 			= $getdepartment->Acc_Group;
			$data['default']['Currency_id'] 		= $getdepartment->Currency_id;
			$data['default']['Base_Debet'] 			= $getdepartment->Base_Debet;
			$data['default']['Base_Kredit'] 		= $getdepartment->Base_Kredit;
			$data['default']['Base_Debet2'] 		= $getdepartment->Base_Debet2;
			$data['default']['Base_Kredit2'] 		= $getdepartment->Base_Kredit2;
			$data['default']['Base_OpeningBalance'] = $getdepartment->Base_OpeningBalance;
			$data['default']['isHO'] 				= $getdepartment->isHO;
			$data['default']['isSync'] 				= $getdepartment->isSync;
			$data['default']['syncPRJ'] 			= $getdepartment->syncPRJ;
			$data['default']['showCF'] 				= $getdepartment->showCF;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'U';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function update_process() // G
	{	
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);	
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
				
		$this->db->trans_begin();
		
		$comp_init 	= $this->session->userdata('comp_init');
		
		$collPRJ	= "AllPRJ";
		$LinkAcc	= 1;
		
		$Acc_ID			= $this->input->post('Acc_ID');
		$Account_Number	= $this->input->post('Account_Number');
		$collData		= $this->input->post('Acc_DirParent');
		echo "collData = $collData";
		$Account_Class	= $this->input->post('Account_Class');
		$PRJCODE		= $this->input->post('PRJCODE');
		
		$splitCode 		= explode("~", $collData);
		$Acc_DirParent	= $splitCode[0];
		$Acc_ParentList1= $splitCode[1];
		$Acc_ParentList	= "$Acc_ParentList1;$Acc_DirParent";
		$isHO			= $this->input->post('isHO');
		$isSync			= $this->input->post('isSync');
		
		// UPDATE TO BE HEADER
			$sqlUPDH 	= "UPDATE tbl_chartaccount SET Account_Class = 1, isLast = 0 WHERE Account_Number = '$Acc_DirParent'";
			$this->db->query($sqlUPDH);

		// CARI LEVEL PARENT
			$sqlACLEV 	= "SELECT Account_Level FROM tbl_chartaccount 
							WHERE Account_Number = '$Acc_DirParent' AND PRJCODE = '$PRJCODE'";
			$resACLEV 	= $this->db->query($sqlACLEV)->result();
			foreach($resACLEV as $rowLEV) :
				$Account_Level1 = $rowLEV->Account_Level;		
			endforeach;
			$Account_Level	= $Account_Level1 + 1;
		
			if($Account_Class == 1)
			{
				$isLast	= 0;

			}
			else
			{
				$isLast	= 1;
			}
		
		// COUNT ALL PROJECT RELATION
			$PRJCODE1	= '';
			if($isSync == 1)
			{
				$packageelements	= $_POST['packageelements'];
				$TOTPROJ			= count($packageelements);
				if (count($packageelements)>0)
				{
					$mySelected	= $_POST['packageelements'];
					$row		= 0;
					foreach ($mySelected as $projCode)
					{
						$row	= $row + 1;
						if($row == 1)
						{
							$PRJCODE1	= $projCode;
						}
						else
						{
							$PRJCODE1	= "$PRJCODE1~$projCode";
						}
					}
				}
			}
		
		// SAVE TO ALL PROJECT
			$sqlPRJ		= "SELECT PRJCODE, isHO FROM tbl_project";
			$resPRJ 	= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJCODE 	= $rowPRJ->PRJCODE;
				$isHO 		= $rowPRJ->isHO;
				$coaUpd 	= array('Acc_ID'				=> $Acc_ID,
									'ORD_ID'				=> $this->input->post('ORD_ID'),
									'Account_Class'			=> $Account_Class,
									'PRJCODE'				=> $PRJCODE,
									'Account_Number'		=> $Account_Number,
									'Account_NameEn'		=> $this->input->post('Account_NameEn'),
									'Account_NameId'		=> $this->input->post('Account_NameId'),
									'Acc_DirParent'			=> $Acc_DirParent,
									'Company_ID'			=> $comp_init,
									'Account_Level'			=> $Account_Level,
									'Account_Category'		=> $this->input->post('Account_Category'),
									'Account_Class'			=> $this->input->post('Account_Class'),
									'Default_Acc'			=> $this->input->post('Default_Acc'),
									'Base_OpeningBalance'	=> $this->input->post('Base_OpeningBalance'),
									'COGSReportID'			=> $this->input->post('COGSReportID'),
									'Acc_Group'				=> $this->input->post('Acc_Group'),
									'showCF'				=> $this->input->post('showCF'),
									'isHO'					=> $isHO,
									//'syncPRJ'				=> $PRJCODE1, // khusus untuk project yang dipilih
									'isLast'				=> $isLast);
				$this->m_coa->update($Acc_ID, $coaUpd, $PRJCODE);
			endforeach;
			
		// SAVE TO ALL SELECTED PROJECT
			$dataPecah 	= explode("~",$PRJCODE1);
			$jmD 		= count($dataPecah);
			
			for($i=0; $i < $jmD; $i++)
			{
				$SYNC_PRJ	= $dataPecah[$i];
				
				$coaUpd 	= array('Base_OpeningBalance'	=> $this->input->post('Base_OpeningBalance'),
									'syncPRJ'				=> $PRJCODE1);								
				$this->m_coa->update($Acc_ID, $coaUpd, $SYNC_PRJ);
			}		
			
		$data['viewCOA'] = $this->m_coa->get_all_ofCOADef($collPRJ, $LinkAcc)->result();
		$MenuCode 			= 'MN105';
		$data["MenuCode"] 	= 'MN105';
		$data["selPRJCODE"] = 'AllPRJ';
			
		$data['secAdd'] 	= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($collPRJ));
		
		// START : UPDATE TO T-TRACK
			date_default_timezone_set("Asia/Jakarta");
			$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			$TTR_PRJCODE	= '';
			$TTR_REFDOC		= '';
			$MenuCode 		= 'MN105';
			$TTR_CATEG		= 'UP';
			
			$this->load->model('m_updash/m_updash', '', TRUE);				
			$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
									'TTR_DATE' 		=> date('Y-m-d H:i:s'),
									'TTR_MNCODE'	=> $MenuCode,
									'TTR_CATEG'		=> $TTR_CATEG,
									'TTR_PRJCODE'	=> $TTR_PRJCODE,
									'TTR_REFDOC'	=> $TTR_REFDOC);
			$this->m_updash->updateTrack($paramTrack);
		// END : UPDATE TO T-TRACK
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
		
		$url			= site_url('c_gl/c_ch1h0fbeart/id1h0fbx1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ch1h0fbeart_upl() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$backURL	= site_url('c_gl/c_ch1h0fbeart/');
			
			$data['isProcess'] 		= 0;
			$data['message'] 		= '';
			$data['PRJCODE']		= $PRJCODE;
			$data['COAH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master COA';
			$data['form_action']	= site_url('c_gl/c_ch1h0fbeart/do_upload');
			$data['backURL'] 		= $backURL;
			
			$this->load->view('v_gl/v_coa/v_coa_upload_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function do_upload() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			date_default_timezone_set("Asia/Jakarta");
			$COAH_DATE		= date('Y-m-d H:i:s');
			$COAH_DATEY		= date('Y');
			$COAH_DATEM		= date('m');
			$COAH_DATED		= date('d');
			$COAH_DATEH		= date('H');
			$COAH_DATEm		= date('i');
			$COAH_DATES		= date('s');
			
			$COAH_CODE		= "COA$COAH_DATEY$COAH_DATEM$COAH_DATED-$COAH_DATEH$COAH_DATEm$COAH_DATES";
			$COAH_DATE		= date('Y-m-d H:i:s');
			$COAH_PRJCODE	= $this->input->post('PRJCODE');
			$COAH_DESC		= $this->input->post('COAH_DESC');
			$COAH_USER		= $DefEmp_ID;
			$COAH_STAT		= 1;
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
					
			$filename 	= $_FILES["userfile"]["name"];
			$source 	= $_FILES["userfile"]["tmp_name"];
			$type 		= $_FILES["userfile"]["type"];
			//echo "file_name = $file_name";
			$name 		= explode(".", $filename);
			$fileExt	= $name[1];
			
			$target_path = "application/xlsxfile/import_coa/".$filename;  // change this to the correct site path
				
			$myPath 	= "application/xlsxfile/import_coa/$filename";
			
			if (file_exists($myPath) == true)
			{
				unlink($myPath);
			}
			
			$data['isUploaded']	= 1;	
			if(move_uploaded_file($source, $target_path))
			{
				$message = "Your file was uploaded";
				$data['message'] 	= $message;
				$data['isSuccess']	= 1;
				$data['COAH_DESC']	= $COAH_DESC;
				
				//$this->m_itemlist->updateStat();
				
				$COAHist = array('COAH_CODE' 	=> $COAH_CODE,
								'COAH_DATE'		=> $COAH_DATE,
								'COAH_PRJCODE'	=> $COAH_PRJCODE,
								'COAH_DESC'		=> $COAH_DESC,
								'COAH_FN'		=> $filename,
								'COAH_USER'		=> $COAH_USER,
								'COAH_STAT'		=> $COAH_STAT);

				$this->m_coa->add_importcoa($COAHist);
			} 
			else 
			{	
				$message = "There was a problem with the upload. Please try again.";
				$data['message'] 	= $message;
				$data['isSuccess']	= 0;
				$data['COAH_DESC']	= $COAH_DESC;
			}
			
			$backURL				= site_url('c_gl/c_ch1h0fbeart/get_it180e2elst_lti/?id='.$this->url_encryption_helper->encode_url($COAH_PRJCODE));
			
			$data['isProcess'] 		= 1;
			$data['message'] 		= '';
			$data['PRJCODE']		= $COAH_PRJCODE;
			$data['COAH_DESC']		= '';
			$data['isUploaded']		= 0;
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Upload';
			$data['h3_title'] 		= 'master COA';
			$data['form_action']	= site_url('c_gl/c_ch1h0fbeart/do_upload');
			//$data['backURL'] 		= $backURL;
			
			//$this->load->view('v_inventory/v_itemlist/v_item_upload_form', $data);
			
			/*$url			= site_url('c_gl/c_ch1h0fbeart/ch1h0fbeart_upl/?id='.$this->url_encryption_helper->encode_url($COAH_PRJCODE));
			redirect($url);*/
		}
	}
	
	function view_coaup() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$COAH_CODE	= $_GET['id'];
			$COAH_CODE	= $this->url_encryption_helper->decode_url($COAH_CODE);
			
			$sqlPRJ		= "SELECT COAH_PRJCODE FROM tbl_coa_uphist WHERE COAH_CODE = '$COAH_CODE'";
			$sqlPRJR	= $this->db->query($sqlPRJ)->result();
			foreach($sqlPRJR as $rowPRJ) :
				$COAH_PRJCODE		= $rowPRJ->COAH_PRJCODE;
			endforeach;
	
			$data['PRJCODE']		= $COAH_PRJCODE;
			$data['COAH_CODE']		= $COAH_CODE;
			$data['title'] 			= $appName;
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'master coa';
			
			$this->load->view('v_gl/v_coa/v_coa_view_xl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function downloadFile() // G
	{
		$this->load->helper('download');

		$collLink	= $_GET['id'];
		$collLink	= $this->url_encryption_helper->decode_url($collLink);
		$collLink1	= explode('~', $collLink);
		$theLink	= $collLink1[0];
		$FileUpName	= $collLink1[1];
		$myPath 	= APPPATH."xlsxfile/import_coa/$FileUpName";
		/*header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);*/

		force_download($myPath, NULL);
	}

	function get_all_ofCOADef()
	{
		$getAppName = $this->m_coa->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$data['title'] = $appName;
		$data['h2_title'] = 'Chart of Account';
		$data['main_view'] = 'v_gl/v_coa/coa';
		
		$getGlobalSetting = $this->m_coa->globalSetting()->result();
		foreach($getGlobalSetting as $therow) :
			$Display_Rows = $therow->Display_Rows;		
		endforeach;
 
        $data['viewCOA'] = $this->m_coa->get_all_ofCOADef()->result();
        
		
		$this->load->view('template', $data);
	}
	
	function a180e25H0w() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collDATA	= $_GET['id'];
			$LinkAcc1	= $this->url_encryption_helper->decode_url($collDATA);	
			$LinkAcc	= explode("~", $LinkAcc1);
			$PRJCODE	= $LinkAcc[0];
			$LinkAcc	= $LinkAcc[1];
		
			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_gl/c_ch1h0fbeart/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['PRJCODE'] 	= $PRJCODE;
			$data["AccCateg"]	= $LinkAcc;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'Show Journal';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa_listjournal', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180e25H0wX() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collDATA	= $_GET['id'];
			$LinkAcc1	= $this->url_encryption_helper->decode_url($collDATA);	
			$LinkAcc	= explode("~", $LinkAcc1);
			$PRJCODE	= $LinkAcc[0];
			$LinkAcc	= $LinkAcc[1];
		
			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_gl/c_ch1h0fbeart/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['PRJCODE'] 	= $PRJCODE;
			$data["AccCateg"]	= $LinkAcc;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'Show Journal';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa_listjournal', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function a180e25H0w4nJD() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 		= $appName;
			
			$this->load->view('v_gl/v_coa/coa_listjournal_an', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
    function sH0wJD() 
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;
		
		$collData	= $_GET['id'];
		$collData	= $_GET['lC'];
		//$collData	= $this->url_encryption_helper->decode_url($collData);
		$data1		= explode("~", $collData);
		$PRJCODE	= $data1[0];
		$ACC_ID		= $data1[1];
		$PRJCODEVW 	= strtolower(preg_replace("/[^a-zA-Z0-9\s]/", "", $PRJCODE));
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$LangID 				= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Detil Jurnal';
			}
			else
			{
				$data['title'] 		= $appName;
				$data['h1_title'] 	= 'Journal Detail';
			}
			
			$TOTPROJ				= 1;
		
			$data['PRJCODE'] 		= $PRJCODE;
			$data['ACC_ID'] 		= $ACC_ID;
			
			$this->load->view('v_gl/v_coa/coa_journal_vd', $data);
		}
		else
		{
			redirect('__l1y');
		}
    }
	
	function sH0wJDS() // G
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$collDATA	= $_GET['id'];
			$LinkAcc1	= $this->url_encryption_helper->decode_url($collDATA);	
			$LinkAcc	= explode("~", $LinkAcc1);
			$PRJCODE	= $LinkAcc[0];
			$LinkAcc	= $LinkAcc[1];
		
			$data['title'] 		= $appName;
			$data['backURL'] 	= site_url('c_gl/c_ch1h0fbeart/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data['PRJCODE'] 	= $PRJCODE;
			$data["AccCateg"]	= $LinkAcc;
				
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= 'MN105';
				$TTR_CATEG		= 'Show Journal';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
			
			$this->load->view('v_gl/v_coa/coa_showall', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function view_coaxp() // OK
	{
		$this->load->model('m_project/m_project_progress/m_progress_up', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;
		endforeach;

		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
	
			$data['PRJCODE']		= $PRJCODE;
			$data['title'] 			= $appName;
			$data['h2_title']		= 'View';
			$data['h3_title'] 		= 'master COA';
			
			$this->load->view('v_gl/v_coa/v_coa_view_dlxl', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function getPercent() // OK
	{
		$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $PrjCode 	= $colExpl[1];
        $COAH_CODE 	= $colExpl[2];

       	// TOTAL ROW IMPORT
       		$TOTR 	= 0;
	        $sTOTR	= "SELECT TOTR FROM tbl_coa_uphist WHERE COAH_CODE = '$COAH_CODE'";
			$rTOTR	= $this->db->query($sTOTR)->result();
			foreach($rTOTR as $rowrTOTR) :
				$TOTR	= $rowrTOTR->TOTR;
			endforeach;
			$TOTR 	= $TOTR ?: 1;

       	// TOTAL ROW IMPORTED
	        $sIMP	= "tbl_chartaccount WHERE PRJCODE = '$PrjCode'";
			$rIMP	= $this->db->count_all($sIMP);

		// PERCENTATION
			$PERC 	= $rIMP / $TOTR * 100;
			$rIMPV 	= number_format($rIMP,0);
			$TOTRV 	= number_format($TOTR,0);
			$PERCV 	= number_format($PERC,2);

	        $sTOTR	= "SELECT ORD_ID FROM tbl_chartaccount";
			$rTOTR	= $this->db->query($sTOTR)->result();
			foreach($rTOTR as $rowrTOTR) :
				$ORD_ID	= $rowrTOTR->ORD_ID;
			endforeach;
			$vwOth 	= "$rIMPV / $TOTRV ( $PrjCode % )";

        echo "$PERC ~ $vwOth";
	}
	
	function impCOA() // OK
	{
		/*$collID 	= $_POST['collID'];
		$colExpl	= explode("~", $collID);
		$url 		= $colExpl[0];
        $PrjCode 	= $colExpl[1];
        $COAH_CODE 	= $colExpl[2];*/
		
		$this->load->view('v_gl/v_coa/sampleView');
	}

  	function get_AllData() // GOOD
	{
		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);

		$PRJCODE		= $_GET['id'];
		
		// START : FOR SERVER-SIDE
			$order 	= $this->input->get("order");

			$col 	= 0;
			$dir 	= "desc";
			if(!empty($order)) {
				foreach($order as $o) {
					$col 	= $o['column'];
					$dir	= $o['dir'];
				}
			}
			
			$dir 	= 'desc';
			if($dir != "asc" && $dir != "desc") 
			{
            	$dir = "asc";
        	}
			
			$columns_valid 	= array("COAH_DATE");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}
			
			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			/*$num_rows 	= $this->m_coa->get_AllDataC($PRJCODE, $search);
			$total			= $num_rows;*/
			$output			= array();
			$output['draw']	= $draw;
			//$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_coa->get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
                $COAH_CODE 		= $dataI['COAH_CODE'];
                $COAH_PRJCODE 	= $dataI['COAH_PRJCODE'];
                $COAH_DATE 		= $dataI['COAH_DATE'];
                $COAH_DESC	 	= $dataI['COAH_DESC'];
                $COAH_FN	 	= $dataI['COAH_FN'];
                $COAH_STAT	 	= $dataI['COAH_STAT'];
                $COAH_USER	 	= $dataI['COAH_USER'];
                
                if($COAH_STAT == 0)
                {
                    $COAH_STATD = 'fake';
                    $STATCOL	= 'danger';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($COAH_STAT == 1)
                {
                    $COAH_STATD = 'New';
                    $STATCOL	= 'warning';
                    $disabled1	= 0;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }
                elseif($COAH_STAT == 2)
                {
                    $COAH_STATD = 'success';
                    $STATCOL	= 'success';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 0;	// Icon Flag
                }
                elseif($COAH_STAT == 3)
                {
                    $COAH_STATD = 'changed';
                    $STATCOL	= 'primary';
                    $disabled1	= 1;	// Icon Process
                    $disabled2	= 1;	// Icon Flag
                }

                $disabled1d		= "";
                $disabled2d		= "";
                if($disabled1 == 1)
                	$disabled1d	= "disabled='disabled'";
                if($disabled2 == 1)
                	$disabled2d	= "disabled='disabled'";

                $FileName 	= $COAH_FN;
                $fileAttach	= base_url('application/xlsxfile/import_coa/'.urldecode($FileName));
                $collLink	= "$fileAttach~$FileName";
                $linkDL1 	= site_url('c_gl/c_ch1h0fbeart/downloadFile/?id='.$this->url_encryption_helper->encode_url($collLink));
                $secVWlURL	= site_url('c_gl/c_ch1h0fbeart/view_coaup/?id='.$this->url_encryption_helper->encode_url($COAH_CODE));

				$secAction	= 	"<input type='hidden' name='secVWlURL_".$noU."' id='secVWlURL_".$noU."' value='".$secVWlURL."'>
						   		<input type='hidden' name='secDWL".$noU."' id='secDWL".$noU."' value='".$linkDL1."'>
						   		<input type='hidden' name='COAH_CODE".$noU."' id='COAH_CODE".$noU."' value='".$COAH_CODE."'>
							   	<label style='white-space:nowrap'>
							   	<a href='javascript:void(null);' onClick='procIMP(".$noU.");' data-skin='skin-green' class='btn btn-warning btn-xs' title='Process' ".$disabled1d.">
                                    <i class='glyphicon glyphicon-refresh'></i>
                                </a>
							   	<a href='javascript:void(null);' onClick='viewcoa(".$noU.");' data-skin='skin-green' class='btn btn-success btn-xs' title='Tampilkan'>
                                    <i class='glyphicon glyphicon-flag'></i>
                                </a>
                                <a href='javascript:void(null);' onClick='dowL(".$noU.");' data-skin='skin-green'class='btn btn-primary btn-xs' title='Download'>
                                    <i class='glyphicon glyphicon-download-alt'></i>
                                </a>
                                <a href='javascript:void(null);' onClick='viewcoa(".$noU.");' data-skin='skin-green' class='btn btn-info btn-xs' title='View Document' style='display:none'>
                                    <i class='fa fa-eye'></i>
                                </a>
								</label>";

				$output['data'][] 	= array("<label style='white-space:nowrap'>".$COAH_DATE."</label>",
										  	$COAH_DESC,
										  	"<span class='label label-".$STATCOL."' style='font-size:12px'>".$COAH_STATD."</span>",
										  	$secAction);
				$noU		= $noU + 1;
			}

			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}
}