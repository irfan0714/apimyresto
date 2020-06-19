<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ping extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $response['status'] =200;
        $postjson = json_decode(file_get_contents('php://input'),true);
        $action = $postjson['action'];

        if($action=="ping"){
            
            $ipaddress		    = $_SERVER['REMOTE_ADDR'];
            //check ip devicea
            $check 				= $this->db->select('*')->from('kassa')->where('ip',$ipaddress)->order_by('','')->get()->row();
            if(empty($check)){
				$registed = " But I am Sorry, Your IP Devices ".$ipaddress." is Not Registed in API Server, Please contact your IT Support. Thank You.";
				$sts = "N";
			}else{
				$registed = " Congratulations ! Your IP Devices ".$ipaddress." is Registed in API Server";
				$sts ="Y";
			}
			
			//get Store
			$sql = "SELECT * FROM store WHERE isRestoCafe='1'";
			$res = $this->getArrayResult($sql);
			
			foreach($res AS $row){
				$store[] = array('KodeStore' => $row['KodeStore'],'NamaStore'=> $row['NamaStore']);
			}
			
			$datastore = $store;
            
            $resp= array('success'=>true,'ip'=>$ipaddress,'registed'=>$registed,'statusRegisted'=>$sts,'result' =>$datastore);
            json_output($response['status'],$resp); 
        
				}
				
				if($action=="sync"){
        	//meja
			$sql_meja = "SELECT
						  *
						FROM
						  lokasipos a
						WHERE a.`KdDivisi` = '01'
						ORDER BY a.`KdLokasi` ASC;";
			$res_meja = $this->getArrayResult($sql_meja);
			
			foreach($res_meja AS $val){
				$meja[] = array('KdLokasi' => $val['KdLokasi'],'KdTipeLokasi' => $val['KdTipeLokasi']);
			}
			
			//menu
			$sql_menu = "SELECT
					  b.`PCode`,
					  b.`NamaLengkap`,
						ROUND(a.`Harga1c`) AS Harga1c,
					  a.`KdGroupBarang`
					FROM
					  masterbarang a
					  INNER JOIN masterbarang_touch b
					    ON a.`PCode` = b.`PCode`
					WHERE a.`Status` = 'A'
					  AND a.`KdDivisi` = '12'
					  AND a.`KdGroupBarang` IN ('0', '1', '2','8')";
			$res_menu = $this->getArrayResult($sql_menu);
			
			foreach($res_menu AS $val){
				$menu[] = array('PCode' => $val['PCode'],'NamaLengkap'=> $val['NamaLengkap'],'Harga'=> $val['Harga1c'],'GroupMenu'=> $val['KdGroupBarang'],'KdGroupBarang'=> $val['KdGroupBarang']);
			}

			//kurs
			$sql_kurs = "SELECT * FROM kurs";
			$res_kurs = $this->getArrayResult($sql_kurs);
			
			foreach($res_kurs AS $vals){
				$kurs[] = array('RMB' => $vals['RMB'],'USD'=> $vals['USD']);
			}
			
			//user
			$sql_user = "SELECT
						  a.`Id`,
						  a.`UserName`,
						  IF(
						    a.`UserName` = 'rbt1010'
						    OR a.`UserName` = 'kdk0605'
						    OR a.`UserName` = 'luwus0202',
						    '123456',
						    '123'
						  ) AS `Password`,
						  IF(b.`Tipe` IS NOT NULL, 'Y', 'N') otorisasi
						FROM
						  `user` a
						  LEFT JOIN `otorisasi_user` b
						    ON a.`UserName` = b.`UserName`
						WHERE a.`UserLevel` = '20'";
			$res_user = $this->getArrayResult($sql_user);
			
			foreach($res_user AS $row){
				$user[] = array('Id' => $row['Id'],'UserName'=> $row['UserName'],'Password'=> $row['Password'],'otorisasi'=>$row['otorisasi']);
			}
			
			$menus = $menu;
			$users = $user;
			$mejas = $meja;
			$kurss = $kurs;
			
			$resp= array('success'=>true,'meja'=>$mejas,'menu'=>$menus,'kurs'=>$kurss,'user' =>$users);
            json_output($response['status'],$resp); 
		}

		if($action=="countListOrder"){
     $sql = "SELECT COUNT(DISTINCT(a.KdMeja)) AS CountTable FROM trans_order_header a WHERE a.Tanggal=CURDATE() AND a.Status='0';";
                    $qry = $this->db->query($sql);
                    $row = $qry->row();
                    $qry->free_result();
										$count = $row->CountTable;
										$resp = array('success'=>true,'counttable'=>$count);
										json_output($response['status'],$resp); 
		}
		  
	}
	
	function getArrayResult($sql)
		{
			$qry = $this->db->query($sql);
	        $row = $qry->result_array();
	        $qry->free_result();
	        return $row;
		}
}
