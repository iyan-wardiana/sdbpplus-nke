<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class __180c2f extends CI_Controller
{
  	function __construct() // GOOD
	{
		parent::__construct();

		$this->load->model('m_gl/m_coa/m_coa', '', TRUE);
	}
	
	public function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$Emp_ID 	= $this->session->userdata['Emp_ID'];
			
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 	= 'Dashboard';
			$data['h3_title'] 	= 'Dashboard';

			$LangID 			= $this->session->userdata['LangID'];
			$data['defNm'] 		= "Dashboard";
			$data['defMn'] 		= "";
			$data['DCode'] 		= "";
			if(isset($_GET['urlID']))
			{
				$urlID	= $_GET['urlID'];
				$urlID	= $this->url_encryption_helper->decode_url($urlID);
				$urlIDx	= explode('~', $urlID);	
				$curlID	= count($urlIDx);

				if($curlID == 2)
				{
					$urlID	= $urlIDx[0];
					$DCode	= $urlIDx[1];	// Doc. Code
					
					if($urlID == 'thR3q')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Tinjau Permintaan";
						}
						else
						{
							$data['defNm'] 	= "Request View";
						}

						$data['defMn'] 	= "c_help/c_t180c2hr/t180c2htread/?id=".$DCode;
						$data['DCode'] 	= $DCode;
					}
				}
				else
				{
					if($urlID == 'pr0fVw')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Profil";
						}
						else
						{
							$data['defNm'] 	= "Profile";
						}

						$data['defMn'] 	= "c_setting/c_profile/viewMyProfile/?id=";
					}
					elseif($urlID == 'fRnLst')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Daftar Pengguna";
						}
						else
						{
							$data['defNm'] 	= "User List";
						}

						$data['defMn'] 	= "c_setting/c_profile/index1/?id=";
					}
					elseif($urlID == 't45kLst')
					{
						if($LangID == 'IND')
						{
							$data['defNm'] 	= "Pertolongan";
						}
						else
						{
							$data['defNm'] 	= "Request List";
						}

						$data['defMn'] 	= "c_help/c_t180c2hr/?id=";
					}
					else
					{
						$data['defNm'] 	= "Dashboard";
						$data['defMn'] 	= "__180c2f/dahsBoard/?id=";
						
						// START : TEMPORARY
							/*if($Emp_ID != 'D15040004221')
								$data['defMn'] 	= "__180c2f/underconstruction/?id=";*/
						// END : TEMPORARY
					}
				}
			}
			else
			{
				$data['defNm'] 	= "Dashboard";
				$data['defMn'] 	= "__180c2f/dahsBoard/?id=";
						
				// START : TEMPORARY
					/*if($Emp_ID != 'D15040004221')
						$data['defMn'] 	= "__180c2f/underconstruction/?id=";*/
				// END : TEMPORARY
			}

			//$this->load->view('dashboard1', $data);
			$this->load->view('dashboard_iframe', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function underconstruction()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
			$username 				= $this->session->userdata('username');
			
			$data['title'] 			= $appName;
			$data['username'] 		= $username;
			$data['appName'] 		= $appName;
			$data['h2_title'] 		= 'Page Not Found';
			
			//$this->load->view('blank', $data);
			$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('login');
		}
	}

	function dahsBoard()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 		= 'Dashboard';
			$data['h3_title'] 		= 'Dashboard';
				
			$this->load->view('dashboard1', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}

	function dahsBoard_uc()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('menu_model', '', TRUE);
			$data['h2_title'] 		= 'Dashboard';
			$data['h3_title'] 		= 'Dashboard';
				
			$this->load->view('dashboard1_uc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function prjlist($offset=0)
	{
		$this->load->model('m_finance/m_outapprove/m_outapprove', '', TRUE);
		
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['h2_title'] 		= 'Project List';
			$data['h3_title'] 		= 'Dashboard';
			$data['moffset'] 		= $offset;
			$data['perpage'] 		= 20;
			$data['theoffset']		= 0;
			
			$num_rows 				= $this->m_outapprove->count_all_project($DefEmp_ID);
			$data["recordcount"] 	= $num_rows;
			$config 				= array();
			$config["total_rows"] 	= $num_rows;
			$config["per_page"] 	= 20;
			$data['vewproject'] 	= $this->m_outapprove->get_last_ten_project($config["per_page"], $offset, $DefEmp_ID)->result();
			
			$this->load->view('v_finance/v_outapprove/project_list', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function aboutcomp()
	{
		$DefEmp_ID 					= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$comp_name	= '';
			$sqlApp 	= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$comp_name = $therow->comp_name;		
			endforeach;
		
			$data['h2_title'] 		= 'About Company';
			$data['h3_title'] 		= $comp_name;
			
			$this->load->view('aboutcomp', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function crtLogV()
	{
		$vers   = $this->session->userdata['vers'];
		$DNOW	= date('Y-m-d H:i:s');
		$Emp_ID	= $_GET['id'];
		$Emp_ID	= $this->url_encryption_helper->decode_url($Emp_ID);
		
		$comp_name	= '';
		$insCLogV 	= "INSERT INTO tbl_emp_vers (EMP_ID, VERS, DATE, STATUS) VALUES ('$Emp_ID', '$vers', '$DNOW', 1)";
		$this->db->query($insCLogV);
	}
	
	function shwLstDoc() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['prjC'] 	= $_GET['prjC'];
			$collData1 		= $_GET['theTbl'];
			$collData		= explode('~', $collData1);
			$data['theTbl'] = $collData[0];
			$data['fldCd'] 	= $collData[1];
			$data['fldDt']	= $collData[2];
			$data['fldNt'] 	= $collData[3];
			$data['theStt'] = $collData[4];
			$data['theTyp'] = $collData[5];
					
			$this->load->view('shwLstDoc', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function sendALERT_220904() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 	= $_POST['EMPID'];

		$s_00	= "SELECT A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE, A.AS_APPEMP,
						CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME, B.Mobile_Phone
					FROM tbl_alert_schedzule A
					INNER JOIN tbl_employee B ON A.AS_APPEMP = B.Emp_ID ORDER BY A.AS_DOCDATE";
		$r_00 	= $this->db->query($s_00)->result();
		foreach($r_00 as $rw_00) :
			$AS_NUM 	= $rw_00->AS_NUM;
			$PRJCODE 	= $rw_00->PRJCODE;
			$AS_CATEG 	= $rw_00->AS_CATEG;
			$AS_DOCNUM 	= $rw_00->AS_DOCNUM;
			$AS_DOCCODE = $rw_00->AS_DOCCODE;
			$AS_DOCDATE = $rw_00->AS_DOCDATE;
			$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));
			$AS_APPEMP 	= $rw_00->AS_APPEMP;
			$AS_EMPNAME	= $rw_00->COMPLNAME;
			$AS_MPHONE	= $rw_00->Mobile_Phone;

			$DOC_DESC 	= "-";
			if($AS_CATEG == 'PO')
			{
				$dNo 	= 0;
				$s_PO	= "SELECT DISTINCT PO_NOTES AS PO_DESC FROM tbl_po_header WHERE PO_NUM = '$AS_DOCNUM'";
				$r_PO 	= $this->db->query($s_PO)->result();
				foreach($r_PO as $rw_PO) :
					$dNo 		= $dNo+1;
					$PO_DESC 	= $rw_PO->PO_DESC;
					if($dNo == 1)
						$DOC_DESC 	= $PO_DESC;
					else
						$DOC_DESC 	= $DOC_DESC.", ".$PO_DESC;
				endforeach;
			}
			if($AS_MPHONE == '')
				$AS_MPHONE = "6285722980308";
			
			if($AS_MPHONE != '')
			{
				$WA_DATE 	= date('Y-m-d H:i:s');
				$s_01 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
								VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_DESC')";
				$this->db->query($s_01);

				// START : ALERT WA PROCEDURE
					$JSON_DATA = '{"token":"1fc6c5d52253693c8bff0f588676fd2ffb78d43d","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen PO yang harus diapprove:\n*'.$AS_DOCCODE.'*\n*'.$AS_DOCDATV.'*\nPembelian *_'.$DOC_DESC.'_*\nURL :  \nMohon segera untuk ditindaklanjuti.\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
	  

				    //--CURL FUNCTION TO CALL THE API--
				    $url = 'https://pickyassist.com/app/api/v2/push';

				    $ch = curl_init($url);                                                                      
				    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				        'Content-Type: application/json',                                                                                
				        'Content-Length: ' . strlen($JSON_DATA))                                                                       
				    );                                                                                                                   
				                                                                                                                            
				    $result = curl_exec($ch);

				    //--API RESPONSE--
				    //print_r( json_decode($result,true) );
				// START : ALERT WA PROCEDURE
			}
		endforeach;
	}
	
	function sendALERT_220905() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 	= $_POST['EMPID'];

		// START : DAFTAR SELURUH USER YANG AKAN DIKIRIM WA
			$s_00	= "SELECT DISTINCT A.AS_APPEMP, CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME, TRIM(B.Mobile_Phone) AS AS_MPHONE
						FROM tbl_alert_schedzule A
						INNER JOIN tbl_employee B ON A.AS_APPEMP = B.Emp_ID";
			$r_00 	= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00) :
				$AS_APPEMP 	= $rw_00->AS_APPEMP;
				$AS_EMPNAME	= $rw_00->COMPLNAME;
				$AS_MPHONE 	= $rw_00->AS_MPHONE;

				if($AS_MPHONE != '')
				{
					// START : PENGELOMPOKAN KATEGORI
						$s_01	= "SELECT DISTINCT AS_CATEG FROM tbl_alert_schedzule WHERE AS_APPEMP = '$AS_APPEMP'";
						$r_01 	= $this->db->query($s_01)->result();
						foreach($r_01 as $rw_01) :
							$AS_CATEG 	= $rw_01->AS_CATEG;

							if($AS_CATEG == 'PO')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'PO' ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT PO_NOTES AS DOC_DESC FROM tbl_po_header WHERE PO_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen PO yang harus diapprove:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}
							elseif($AS_CATEG == 'WO')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'WO' ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT WO_NOTE AS DOC_DESC FROM tbl_wo_header WHERE WO_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen SPK yang harus diapprove:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}
							elseif($AS_CATEG == 'OPN')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'OPN' ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT OPNH_NOTE AS DOC_DESC FROM tbl_opn_header WHERE OPNH_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_ Desk: ".$DOC_DESC.'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen Opname yang harus diapprove:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}

						endforeach;
					// END : PENGELOMPOKAN KATEGORI
				}
			endforeach;
		// END : DAFTAR SELURUH USER YANG AKAN DIKIRIM WA
	}
	
	function sendALERT() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 			= $_POST['EMPID'];

		$DATEN 			= date('Y-m-d');
		$AS_SENTDATE    = date('Y-m-d', strtotime('+1 days', strtotime($DATEN)));

		// START : DAFTAR SELURUH USER YANG AKAN DIKIRIM WA
			$s_00	= "SELECT DISTINCT A.AS_APPEMP, CONCAT(TRIM(B.First_Name),IF(B.Last_Name = '','',' '),TRIM(B.Last_Name)) AS COMPLNAME, TRIM(B.Mobile_Phone) AS AS_MPHONE
						FROM tbl_alert_schedzule A
						INNER JOIN tbl_employee B ON A.AS_APPEMP = B.Emp_ID";
			$r_00 	= $this->db->query($s_00)->result();
			foreach($r_00 as $rw_00) :
				$AS_APPEMP 	= $rw_00->AS_APPEMP;
				$AS_EMPNAME	= $rw_00->COMPLNAME;
				$AS_MPHONE 	= $rw_00->AS_MPHONE;

				//$AS_MPHONE 	= "6285722980308";

				if($AS_MPHONE != '')
				{
					// START : PENGELOMPOKAN KATEGORI
						$s_01	= "SELECT DISTINCT AS_CATEG FROM tbl_alert_schedzule WHERE AS_APPEMP = '$AS_APPEMP' AND AS_SENTDATE = '$DATEN'";
						$r_01 	= $this->db->query($s_01)->result();
						foreach($r_01 as $rw_01) :
							$AS_CATEG 	= $rw_01->AS_CATEG;

							if($AS_CATEG == 'PO')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_ID, A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'PO' AND A.AS_SENTDATE = '$DATEN'
											ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_ID 		= $rw_02->AS_ID;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT PO_NOTES AS DOC_DESC FROM tbl_po_header WHERE PO_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									$s_02a 		= "UPDATE tbl_alert_schedzule SET AS_SENTDATE = '$AS_SENTDATE' WHERE AS_ID = $AS_ID";
									$this->db->query($s_02a);

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen PO yang harus disetujui:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}
							elseif($AS_CATEG == 'WO')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_ID, A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'WO' AND A.AS_SENTDATE = '$DATEN'
											ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_ID 		= $rw_02->AS_ID;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT WO_NOTE AS DOC_DESC FROM tbl_wo_header WHERE WO_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									$s_02a 		= "UPDATE tbl_alert_schedzule SET AS_SENTDATE = '$AS_SENTDATE' WHERE AS_ID = $AS_ID";
									$this->db->query($s_02a);

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen SPK yang harus disetujui:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}
							elseif($AS_CATEG == 'OPN')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_ID, A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'OPN' AND A.AS_SENTDATE = '$DATEN'
											ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_ID 		= $rw_02->AS_ID;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT OPNH_NOTE AS DOC_DESC FROM tbl_opn_header WHERE OPNH_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									$s_02a 		= "UPDATE tbl_alert_schedzule SET AS_SENTDATE = '$AS_SENTDATE' WHERE AS_ID = $AS_ID";
									$this->db->query($s_02a);

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen Opname yang harus disetujui:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}
							elseif($AS_CATEG == 'INV')
							{
								$NO_U = 0;
								$s_02	= "SELECT A.AS_ID, A.AS_NUM, A.PRJCODE, A.AS_CATEG, A.AS_DOCNUM, A.AS_DOCCODE, A.AS_DOCDATE
											FROM tbl_alert_schedzule A WHERE A.AS_APPEMP = '$AS_APPEMP' AND A.AS_CATEG = 'INV' AND A.AS_SENTDATE = '$DATEN'
											ORDER BY A.AS_DOCDATE";
								$r_02 	= $this->db->query($s_02)->result();
								foreach($r_02 as $rw_02) :
									$NO_U 		= $NO_U+1;
									$AS_ID 		= $rw_02->AS_ID;
									$AS_NUM 	= $rw_02->AS_NUM;
									$PRJCODE 	= $rw_02->PRJCODE;
									$AS_CATEG 	= $rw_02->AS_CATEG;
									$AS_DOCNUM 	= $rw_02->AS_DOCNUM;
									$AS_DOCCODE = $rw_02->AS_DOCCODE;
									$AS_DOCDATE = $rw_02->AS_DOCDATE;
									$AS_DOCDATV = date('d/m/Y', strtotime($AS_DOCDATE));

									$DOC_DESC 	= "-";
									$s_DOC		= "SELECT DISTINCT INV_NOTES AS DOC_DESC FROM tbl_pinv_header WHERE INV_NUM = '$AS_DOCNUM' LIMIT 1";
									$r_DOC 		= $this->db->query($s_DOC)->result();
									foreach($r_DOC as $rw_DOC) :
										$DOC_DESC 	= $rw_DOC->DOC_DESC;
										if($DOC_DESC == '')
											$DOC_DESC 	= "-";
									endforeach;

									$s_02a 		= "UPDATE tbl_alert_schedzule SET AS_SENTDATE = '$AS_SENTDATE' WHERE AS_ID = $AS_ID";
									$this->db->query($s_02a);

									if($NO_U == 1)
										$DOC_LIST 	= "1. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
									else
										$DOC_LIST 	= $DOC_LIST. "$NO_U. ".$AS_DOCCODE." _(".$AS_DOCDATV.")_".'\n';
								endforeach;

								$WA_DATE 	= date('Y-m-d H:i:s');
								$s_03 		= "INSERT INTO tbl_wa_sent (AS_NUM, WA_CATEG, WA_DATE, WA_EMPID, WA_MPHONE, WA_MESSAGE)
												VALUES ('$AS_NUM', '$AS_CATEG', '$WA_DATE', '$AS_APPEMP', '$AS_MPHONE', '$DOC_LIST')";
								$this->db->query($s_03);

								// START : ALERT WA PROCEDURE
									$JSON_DATA = '{"token":"1f0e316f043fce1b5a52242463d88ae08a8aafe1","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$AS_MPHONE.'","message":"Halo Bapak/Ibu *'.$AS_EMPNAME.'*, Anda memiliki dokumen Voucher Pembayaran yang harus disetujui:\n'.$DOC_LIST.'\nMohon segera untuk ditindaklanjuti.\nhttps://sdbpplus.nke.co.id/\n\n Terimakasih. \n *_NKE Smart System_*"}]}';
					  

								    //--CURL FUNCTION TO CALL THE API--
								    $url = 'https://pickyassist.com/app/api/v2/push';

								    $ch = curl_init($url);                                                                      
								    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
								    curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);                                                                  
								    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
								    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
								        'Content-Type: application/json',                                                                                
								        'Content-Length: ' . strlen($JSON_DATA))                                                                       
								    );                                                                                                                   
								                                                                                                                            
								    $result = curl_exec($ch);

								    //--API RESPONSE--
								    //print_r( json_decode($result,true) );
								// START : ALERT WA PROCEDURE
							}

						endforeach;
					// END : PENGELOMPOKAN KATEGORI
				}
			endforeach;
		// END : DAFTAR SELURUH USER YANG AKAN DIKIRIM WA
	}
	
	function getTaskNotifOnce() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 		= $_POST['EMPID'];

		$sqlCHATH 	= "SELECT DISTINCT TASKD_ID, TASKD_PARENT, TASKD_TITLE, TASKD_CONTENT, TASKD_CREATED, 
                            TASKD_EMPID, TASKD_EMPID2
                        FROM tbl_task_request_detail
                        WHERE TASKD_RSTAT = 1 AND TASKD_SNOTIF = 0 AND TASKD_EMPID2 = '$EMPID'
                        ORDER BY TASKD_CREATED DESC";
        $resCHATH 	= $this->db->query($sqlCHATH)->result();
        
		$DTot 			= 0;
        $sendData 		= "";
        $TASKD_PARENT 	= "TASKD_PARENT";
        $TASKD_TITLE 	= "TASKD_TITLE";
        foreach($resCHATH as $rowCHATH) :
        	$DTot 			= $DTot+1;
            // RECEIVER IS NON ACTIVE USER, WHO SENDED BY USER ACTIVE
            $TASKD_ID 		= $rowCHATH->TASKD_ID;
            $TASKD_PARENT 	= $rowCHATH->TASKD_PARENT;
            $TASKD_PARENTV 	= substr($TASKD_PARENT, 8, 19);
            $TASKD_TITLE	= $rowCHATH->TASKD_TITLE;
            $TASKD_CONTENT	= $rowCHATH->TASKD_CONTENT;
            $TASKD_CREATED	= $rowCHATH->TASKD_CREATED;
            $TASKD_EMPID	= $rowCHATH->TASKD_EMPID;
            $TASKD_EMPID2	= $rowCHATH->TASKD_EMPID2;
            
            $CompName	= '';
            $First_Name	= '';
            $Last_Name	= '';
            $sqlEmp 	= "SELECT CONCAT(First_Name,' ', Last_Name) AS complName FROM tbl_employee WHERE Emp_ID = '$TASKD_EMPID'";
            $resEmp		= $this->db->query($sqlEmp)->result();
            foreach($resEmp as $rowEmp) :
                $CompName = $rowEmp->complName;
            endforeach;
            
            $CHAT_SENDNAME1	= $CompName;
            $CHAT_SENDNAME2	= strtolower($CHAT_SENDNAME1);
            $CHAT_SENDNAMEA	= ucwords($CHAT_SENDNAME2);
            
            // GET RECEIVE DATA
            $imgemp_fnRec 	= '';
            $imgemp_fnRecX 	= '';
            $getIMGRec		= "SELECT imgemp_filename, imgemp_filenameX 
                                FROM tbl_employee_img WHERE imgemp_empid = '$TASKD_EMPID'";
            $resIMGRec 		= $this->db->query($getIMGRec)->result();
            foreach($resIMGRec as $rowIMGRec) :
                $imgemp_fnRec 	= $rowIMGRec ->imgemp_filename;
                $imgemp_fnRecX 	= $rowIMGRec ->imgemp_filenameX;
            endforeach;
                
            $imgReqer		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASKD_EMPID.'/'.$imgemp_fnRecX);
            if($imgemp_fnRecX == 'username.jpg')
                $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
            else
                $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');

	        $MSG_T 			= "dari $CompName";
	        if($DTot == 1)
	        	$sendData 	= "$MSG_T~$TASKD_TITLE";
	        else
	        	$sendData 	= $sendData."|$MSG_T~$TASKD_TITLE";

	        $s_UPD 			= "UPDATE tbl_task_request_detail SET TASKD_SNOTIF = 1 WHERE TASKD_ID = $TASKD_ID";
	        $this->db->query($s_UPD);
        endforeach;
		echo $sendData;
	}
	
	function getTaskNotif() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 		= $_POST['EMPID'];

		$sqlCHATH 	= "SELECT DISTINCT TASKD_ID, TASKD_PARENT, TASKD_TITLE, TASKD_CONTENT, TASKD_CREATED, 
                            TASKD_EMPID, TASKD_EMPID2
                        FROM tbl_task_request_detail
                        WHERE TASKD_RSTAT = 1 AND TASKD_EMPID2 = '$EMPID'
                        ORDER BY TASKD_CREATED DESC";
        $resCHATH 	= $this->db->query($sqlCHATH)->result();
        
		$DTot 			= 0;
        $sendData 		= "";
        $TASKD_PARENT 	= "TASKD_PARENT";
        $TASKD_TITLE 	= "TASKD_TITLE";
        foreach($resCHATH as $rowCHATH) :
        	$DTot 			= $DTot+1;
            // RECEIVER IS NON ACTIVE USER, WHO SENDED BY USER ACTIVE
            $TASKD_ID 		= $rowCHATH->TASKD_ID;
            $TASKD_PARENT 	= $rowCHATH->TASKD_PARENT;
            $TASKD_PARENTV 	= substr($TASKD_PARENT, 8, 19);
            $TASKD_TITLE	= $rowCHATH->TASKD_TITLE;
            $TASKD_CONTENT	= $rowCHATH->TASKD_CONTENT;
            $TASKD_CREATED	= $rowCHATH->TASKD_CREATED;
            $TASKD_EMPID	= $rowCHATH->TASKD_EMPID;
            $TASKD_EMPID2	= $rowCHATH->TASKD_EMPID2;
            
            $CompName	= '';
            $First_Name	= '';
            $Last_Name	= '';
            $sqlEmp 	= "SELECT CONCAT(First_Name,' ', Last_Name) AS complName FROM tbl_employee WHERE Emp_ID = '$TASKD_EMPID'";
            $resEmp		= $this->db->query($sqlEmp)->result();
            foreach($resEmp as $rowEmp) :
                $CompName = $rowEmp->complName;
            endforeach;
            
            $CHAT_SENDNAME1	= $CompName;
            $CHAT_SENDNAME2	= strtolower($CHAT_SENDNAME1);
            $CHAT_SENDNAMEA	= ucwords($CHAT_SENDNAME2);
            
            // GET RECEIVE DATA
            $imgemp_fnRec 	= '';
            $imgemp_fnRecX 	= '';
            $getIMGRec		= "SELECT imgemp_filename, imgemp_filenameX 
                                FROM tbl_employee_img WHERE imgemp_empid = '$TASKD_EMPID'";
            $resIMGRec 		= $this->db->query($getIMGRec)->result();
            foreach($resIMGRec as $rowIMGRec) :
                $imgemp_fnRec 	= $rowIMGRec ->imgemp_filename;
                $imgemp_fnRecX 	= $rowIMGRec ->imgemp_filenameX;
            endforeach;
                
            $imgReqer		= base_url('assets/AdminLTE-2.0.5/emp_image/'.$TASKD_EMPID.'/'.$imgemp_fnRecX);
            if($imgemp_fnRecX == 'username.jpg')
                $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');
            else
                $imgReqer	= base_url('assets/AdminLTE-2.0.5/emp_image/username.jpg');

	        $MSG_T 			= "dari $CompName";
	        if($DTot == 1)
	        	$sendData 	= "$MSG_T~$TASKD_TITLE";
	        else
	        	$sendData 	= $sendData."|$MSG_T~$TASKD_TITLE";
        endforeach;
		echo $sendData;
	}
	
	function getTaskNotifAPP() // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		$EMPID 		= $_POST['EMPID'];

		$DTot 		= 0;
		$ASCATEG 	= "";
		$s_00 		= "SELECT DISTINCT AS_CATEG FROM tbl_alert_schedzule WHERE AS_APPEMP = '$EMPID'";
        $r_00 		= $this->db->query($s_00)->result();
        foreach($r_00 as $rw_00) :
        	$DTot 		= $DTot+1;
            $ASCATEG 	= $rw_00->AS_CATEG;
	        	
        	if($ASCATEG == 'PR')
        		$ASCATEGD 	= "SPP";
        	elseif($ASCATEG == 'PO')
        		$ASCATEGD 	= "OP";
        	elseif($ASCATEG == 'WO')
        		$ASCATEGD 	= "SPK";
        	elseif($ASCATEG == 'INV')
        		$ASCATEGD 	= "Voucher";
        	elseif($ASCATEG == 'VCASH')
        		$ASCATEGD 	= "Voucher Cash";
        	elseif($ASCATEG == 'OPN')
        		$ASCATEGD 	= "Opname";
        	else
        		$ASCATEGD 	= "Lainnya";

			$DOCAPP 	= "";
			$DOCAPPA 	= "";
			$sendData 	= "";
			$MSG_T 		= "";
			$MSG_C 		= "";
            $i 			= 0;
			$s_DOC 		= "SELECT PRJCODE, AS_DOCCODE FROM tbl_alert_schedzule WHERE AS_APPEMP = '$EMPID' AND AS_CATEG = '$ASCATEG'
							ORDER BY PRJCODE, AS_DOCCODE ASC";
	        $r_DOC 		= $this->db->query($s_DOC)->result();
	        foreach($r_DOC as $rw_DOC) :
	        	$i 			= $i+1;
	            $PRJCODE 	= $rw_DOC->PRJCODE;
	            $ASDOCCODE 	= $rw_DOC->AS_DOCCODE;

	        	if($i == 1)
	        		$DOCAPPA = $ASDOCCODE;
	        	else
	        		$DOCAPPA = $DOCAPP.",".$ASDOCCODE;

	        	$DOCAPP 	= wordwrap($DOCAPPA, 34, "\n", TRUE);
	        endforeach;

	        $MSG_T 			= "Dok. $ASCATEGD outstanding :";
	        if($DTot == 1)
	        	$sendData 	= "$MSG_T~$DOCAPP~$ASCATEG";
	        else
	        	$sendData 	= $sendData."|$MSG_T~$DOCAPP~$ASCATEG";

	        echo $sendData;
	    endforeach;

	    // START : ALERT BERKAS JAMINAN
			$s_01 		= "SELECT GF_NUM, GF_CODE, GF_NAME, GF_DATES, GF_DATEE, GF_PENJAMIN, GF_NILAI_JAMINAN, SPLCODE, DATEDIFF(GF_DATEE, NOW()) AS REM_DAY, SPLDESC, WO_CODE
							FROM tbl_gfile_alert7";
	        $r_01 		= $this->db->query($s_01);
	        if($r_01->num_rows() > 0)
	        {
	        	$TXT01 		= "";
		        foreach($r_01->result() as $rw_01) :
		        	$GF_NUM 	= $rw_01->GF_NUM;
		        	$GF_CODE 	= $rw_01->GF_CODE;
		        	$GF_NAME 	= $rw_01->GF_NAME;
		        	$GF_DATES 	= $rw_01->GF_DATES;
		        	$GF_DATEE 	= $rw_01->GF_DATEE;
		        	$GF_PENJ 	= $rw_01->GF_PENJAMIN;
		        	$GF_VALUE 	= $rw_01->GF_NILAI_JAMINAN;
		        	$REM_DAY 	= $rw_01->REM_DAY;
		        	$SPLDESC 	= $rw_01->SPLDESC;
		        	$WO_CODE 	= $rw_01->WO_CODE;

		        	$TXT01 		= $TXT01."Kode Jaminan : *$GF_CODE*\nNama Jaminan : $GF_NAME\nPenjamin : $GF_PENJ\nSupplier : *$SPLDESC*\nOP/SPK No. : *$WO_CODE*\nBerakhir Pada : *$GF_DATEE*\n\n";

		        	$s_UPD 		= "UPDATE tbl_gfile SET ALERTSTAT7 = 1 WHERE GF_NUM = '$GF_NUM'";
		        	$this->db->query($s_UPD);
		        endforeach;

				/* ------------------------------ Maxhat.id -------------------------------------- */
					// $url 		= "https://user.maxchat.id/nke-official-center/api/messages?direct=true";
					$url 		= "https://core.maxchat.id/nke-official-center/api/messages";
					$token 		= "Pzdt3uJuftCaXivWuxn3Tt";
					$BC_CONTX	= $TXT01;
					
					$s_1a		= "SELECT EMP_NAME, TRIM(EMP_PHONE) AS EMP_PHONE FROM tbl_gfile_alerts_emp ORDER BY EMP_NAME";
					$r_1a 		= $this->db->query($s_1a)->result();
					foreach($r_1a as $rw_1a) :
						$EMP_NAME 	= $rw_1a->EMP_NAME;
						$EMP_PHONE 	= $rw_1a->EMP_PHONE;

						$BC_CONT1 	= "Dear Bpk/Ibu *$EMP_NAME*,\n\nFYI, berikut daftar Berkas Jaminan Bank / Asuransi yang akan berakhir 7 hari mendatang:";
						$BC_CONT2 	= $BC_CONTX;
						$BC_CONT3 	= "Demikian informasi ini kami sampaikan, agar menjadi perhatian.\n\nTerimakasih\n*NKE Smart System*";
						$BC_CONT 	= "$BC_CONT1\n\n$BC_CONT2$BC_CONT3";
						
						$JSON_DATA	= array("to" => $EMP_PHONE, "type" => "text", "text" => "$BC_CONT", "useTyping" => false);
						$curl 		= curl_init();

						curl_setopt_array($curl, array(
							CURLOPT_URL => $url,
							CURLOPT_SSL_VERIFYHOST => false,
							CURLOPT_SSL_VERIFYPEER => false,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 500,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => json_encode($JSON_DATA),
							CURLOPT_HTTPHEADER => array(
								"Authorization: Bearer " . $token,
								"Content-Type: application/json",
								"cache-control: no-cache"
							),
						));

						$response = curl_exec($curl);
						$err = curl_error($curl);

						curl_close($curl);
					endforeach;
				/*-------------------------------- Maxhat.id ---------------------------------- */
		    }

		    $BC_CONTX 	= "";
			$s_01 		= "SELECT GF_NUM, GF_CODE, GF_NAME, GF_DATES, GF_DATEE, GF_PENJAMIN, GF_NILAI_JAMINAN, SPLCODE, DATEDIFF(GF_DATEE, NOW()) AS REM_DAY, SPLDESC, WO_CODE
							FROM tbl_gfile_alert3";
	        $r_01 		= $this->db->query($s_01);
	        if($r_01->num_rows() > 0)
	        {
	        	$TXT01 		= "";
		        foreach($r_01->result() as $rw_01) :
		        	$GF_NUM 	= $rw_01->GF_NUM;
		        	$GF_CODE 	= $rw_01->GF_CODE;
		        	$GF_NAME 	= $rw_01->GF_NAME;
		        	$GF_DATES 	= $rw_01->GF_DATES;
		        	$GF_DATEE 	= $rw_01->GF_DATEE;
		        	$GF_PENJ 	= $rw_01->GF_PENJAMIN;
		        	$GF_VALUE 	= $rw_01->GF_NILAI_JAMINAN;
		        	$REM_DAY 	= $rw_01->REM_DAY;
		        	$SPLDESC 	= $rw_01->SPLDESC;
		        	$WO_CODE 	= $rw_01->WO_CODE;

		        	$TXT01 		= $TXT01."Kode Jaminan : *$GF_CODE*\nNama Jaminan : $GF_NAME\nPenjamin : $GF_PENJ\nSupplier : *$SPLDESC*\nOP/SPK No. : *$WO_CODE*\nBerakhir Pada : *$GF_DATEE*\n\n";

		        	$s_UPD 		= "UPDATE tbl_gfile SET ALERTSTAT3 = 1 WHERE GF_NUM = '$GF_NUM'";
		        	$this->db->query($s_UPD);
		        endforeach;

				/* ------------------------------ Maxhat.id -------------------------------------- */
					// $url 		= "https://user.maxchat.id/nke-official-center/api/messages?direct=true";
					$url 		= "https://core.maxchat.id/nke-official-center/api/messages";
					$token 		= "Pzdt3uJuftCaXivWuxn3Tt";
					$BC_CONTX	= $TXT01;
					
					$s_1a		= "SELECT EMP_NAME, TRIM(EMP_PHONE) AS EMP_PHONE FROM tbl_gfile_alerts_emp ORDER BY EMP_NAME";
					$r_1a 		= $this->db->query($s_1a)->result();
					foreach($r_1a as $rw_1a) :
						$EMP_NAME 	= $rw_1a->EMP_NAME;
						$EMP_PHONE 	= $rw_1a->EMP_PHONE;

						$BC_CONT1 	= "Dear Bpk/Ibu *$EMP_NAME*,\n\nFYI, berikut daftar Berkas Jaminan Bank / Asuransi yang akan berakhir 3 hari mendatang:";
						$BC_CONT2 	= $BC_CONTX;
						$BC_CONT3 	= "Demikian informasi ini kami sampaikan, agar menjadi perhatian.\n\nTerimakasih\n*NKE Smart System*";
						$BC_CONT 	= "$BC_CONT1\n\n$BC_CONT2$BC_CONT3";
						
						$JSON_DATA	= array("to" => $EMP_PHONE, "type" => "text", "text" => "$BC_CONT", "useTyping" => false);
						$curl 		= curl_init();

						curl_setopt_array($curl, array(
							CURLOPT_URL => $url,
							CURLOPT_SSL_VERIFYHOST => false,
							CURLOPT_SSL_VERIFYPEER => false,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 500,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => json_encode($JSON_DATA),
							CURLOPT_HTTPHEADER => array(
								"Authorization: Bearer " . $token,
								"Content-Type: application/json",
								"cache-control: no-cache"
							),
						));

						$response = curl_exec($curl);
						$err = curl_error($curl);

						curl_close($curl);
					endforeach;
				/*-------------------------------- Maxhat.id ---------------------------------- */
		    }
	    // START : ALERT BERKAS JAMINAN
	}
}