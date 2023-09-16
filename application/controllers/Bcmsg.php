<?php
/*  
	* Author		= Dian Hermanto
	* Create Date	= 07 Agustus 2023
	* File Name		= Bcmsg.php
	* Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bcmsg extends CI_Controller  
{
  	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_updash/m_updash', '', TRUE);
		$this->load->model('m_bc/m_bc', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
	}

 	public function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('bcmsg/bcmsgIdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function bcmsgIdx($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
			
		// GET MENU DESC
			if($this->data['LangID'] == 'IND')
				$data["mnName"] = "Daftar Broadcast";
			else
				$data["mnName"] = "Broadcast List";
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;

			$this->load->view('v_bc/v_bc', $data); 
		}
		else
		{
			redirect('__I1y');
		}
	}

	function get_AllData()
	{
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
			
			$columns_valid 	= array("ID",
									"BC_ID",
									"BC_TITLE",
									"BC_CONTENT",
									"");
	
			if(!isset($columns_valid[$col])) {
				$order = null;
			} else {
				$order = $columns_valid[$col];
			}

			$draw			= $_REQUEST['draw'];
			$length			= $_REQUEST['length'];
			$start			= $_REQUEST['start'];
			$search			= $_REQUEST['search']["value"];
			$num_rows 		= $this->m_bc->get_AllDataC($length, $start);
			$total			= $num_rows;
			$output			= array();
			$output['draw']	= $draw;
			$output['recordsTotal'] = $output['recordsFiltered']= $total;
			$output['data']	= array();
			$query 			= $this->m_bc->get_AllDataL($search, $length, $start, $order, $dir);
								
			$noU			= $start + 1;
			foreach ($query->result_array() as $dataI) 
			{
				$BC_ID		= $dataI['BC_ID'];
				$BC_TITLE	= $dataI['BC_TITLE'];
				$BC_CONTENT	= $dataI['BC_CONTENT'];
				$BC_SENDER	= $dataI['BC_SENDER'];
				$complName	= $dataI['complName'];
				$BC_CREATED	= date('Y-m-d', strtotime($dataI['BC_CREATED']));

				$strLEN 	= strlen($BC_CONTENT);
				$BCCONTENT	= substr("$BC_CONTENT", 0, 60);
				$BC_CONTENT = $BCCONTENT;
				if($strLEN > 60)
					$BC_CONTENT 	= $BCCONTENT."...";

				$output['data'][] 	= [	$noU,
										$BC_CREATED,
										$BC_TITLE,
										$BC_CONTENT,
										"<div style='white-space:nowrap'>$complName</div>",
										""];
				$noU		= $noU + 1;
			}

			//$output['data'][] 	= ["A", "B", "C", "D", "E", "F"];
			echo json_encode($output);
		// END : FOR SERVER-SIDE
	}

	function saveBC()
	{
		date_default_timezone_set("Asia/Jakarta");

		$this->db->trans_begin();

		$BC_ID 		= date('ymdHis');
		$BC_TITLE	= $_POST['BC_TITLE'];
		$BC_CONTENT	= $_POST['BC_CONTENT'];
		$BC_SENDER 	= $this->session->userdata['Emp_ID'];
		$BC_CREATED	= date('Y-m-d H:i:s');

		$d['BC_ID'] 		= $BC_ID;
		$d['BC_TITLE'] 		= $BC_TITLE;
		$d['BC_CONTENT'] 	= $BC_CONTENT;
		$d['BC_SENDER'] 	= $BC_SENDER;
		$d['BC_CREATED'] 	= $BC_CREATED;

		$BC_CTN_A 			= str_replace("<p>", 	"", 	$BC_CONTENT);
		$BC_CTN_B 			= str_replace("</p>", 	"", 	$BC_CTN_A);
		$BC_CTN_C 			= str_replace("<br>", 	"\n", 	$BC_CTN_B);
		$BC_CTN_D 			= str_replace("<b>", 	"*", 	$BC_CTN_C);
		$BC_CTN_E 			= str_replace("</b>", 	"*", 	$BC_CTN_D);
		$BC_CTN_F 			= str_replace("<i>", 	"_", 	$BC_CTN_E);
		$BC_CTN_G 			= str_replace("</i>", 	"_", 	$BC_CTN_F);

		$this->db->insert('tbl_bc',$d);

		/* ------------------------------ Maxhat.id -------------------------------------- */
			// $url 		= "https://user.maxchat.id/nke-official-center/api/messages?direct=true";
			$url 		= "https://core.maxchat.id/nke-official-center/api/messages";
			$token 		= "Pzdt3uJuftCaXivWuxn3Tt";
			$BC_CONT	= $BC_CTN_G;
			
			/*$s_BCWA 	= "SELECT Mobile_Phone, CONCAT(First_Name,' ',Last_Name) AS complName FROM tbl_employee
							WHERE Mobile_Phone != '' AND Emp_Status = 1 AND Employee_status = 1 ORDER BY First_Name";*/
			$s_BCWA 	= "SELECT TRIM(Mobile_Phone) AS Mobile_Phone, CONCAT(TRIM(First_Name),' ',TRIM(Last_Name)) AS complName FROM tbl_employee
			                WHERE Mobile_Phone != '' AND Emp_Status = 1 AND Employee_status = 1 ORDER BY First_Name";
			$r_BCWA 	= $this->db->query($s_BCWA)->result();
			foreach($r_BCWA as $rw_BCWA):
				$AS_MPHONE 	= $rw_BCWA->Mobile_Phone;
				$complName 	= $rw_BCWA->complName;

				// $BC_CONT1 	= "";
				$BC_CONT2 	= $BC_CONT;
				$BC_CONT3 	= "Dear *$complName*,\n\n$BC_CONT2";
				
				// $JSON_DATA	= array("to" => $AS_MPHONE, "text" => "$BC_CONT3");
				$JSON_DATA	= array("to" => $AS_MPHONE, "type" => "text", "text" => "$BC_CONT3", "useTyping" => false);
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

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}

		echo "Pesan broadcast berhasil dikirim ke semua pengguna";
	}
}