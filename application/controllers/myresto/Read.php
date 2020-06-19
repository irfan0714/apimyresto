<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Read extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('myresto/ReadModel');
    }

	public function index()
	{
        $response['status'] =200;
        $ipaddress		    = $_SERVER['REMOTE_ADDR'];
        $res_kassa	        = $this->ReadModel->getKassa($ipaddress);
        $kassa		        = $res_kassa->id_kassa;
        
		$postjson = json_decode(file_get_contents('php://input'),true);
        $action = $postjson['action'];
        if($action=="getUpdateData"){

             $header = $this->ReadModel->getAllDataHeader();
             if(!empty($header)){
             foreach($header AS $val){
                 $data_header[] = array(
                                    'NoTrans'=>$val['NoTrans'],
                                    'NoKassa'=>$val['NoKassa'],
                                    'Tanggal'=>$val['Tanggal'],
                                    'Waktu'=>$val['Waktu'],
                                    'Kasir'=>$val['Kasir'],
                                    'KdStore'=>$val['KdStore'],
                                    'TotalItem'=>$val['TotalItem'],
                                    'TotalQty'=>$val['TotalQty'],
                                    'TotalServe'=>$val['TotalServe'],
                                    'Status'=>$val['Status'],
                                    'KdPersonal'=>$val['KdPersonal'],
                                    'KdMeja'=>$val['KdMeja'],
                                    'KdContact'=>$val['KdContact'],
                                    'nokasst'=>$val['nokasst'],
                                    'nostruk'=>$val['nostruk'],
                                    'TotalGuest'=>$val['TotalGuest'],
                                    'AddDate'=>$val['AddDate'],
                                    'keterangan'=>$val['keterangan'],
                                    'KdAgent'=>$val['KdAgent'],
                                    'IsCommit'=>$val['IsCommit']
                                    );

             $detail = $this->ReadModel->getAllDataDetail($val['NoTrans']);
                foreach($detail AS $res){
                                $data_detail[] = array(
                                                    'NoTrans'=>$res['NoTrans'],
                                                    'NoUrut'=>$res['NoUrut'],
                                                    'NoKassa'=>$res['NoKassa'],
                                                    'Tanggal'=>$res['Tanggal'],
                                                    'Waktu'=>$res['Waktu'],
                                                    'Kasir'=>$res['Kasir'],
                                                    'KdStore'=>$res['KdStore'],
                                                    'PCode'=>$res['PCode'],
                                                    'Qty'=>$res['Qty'],
                                                    'Berat'=>$res['Berat'],
                                                    'Satuan'=>$res['Satuan'],
                                                    'Keterangan'=>$res['Keterangan'],
                                                    'Note_split'=>$res['Note_split'],
                                                    'Status'=>$res['Status'],
                                                    'KdPersonal'=>$res['KdPersonal'],
                                                    'KdMeja'=>$res['KdMeja'],
                                                    'KdContact'=>$res['KdContact'],
                                                    'MenuBaru'=>$res['MenuBaru'],
                                                    'Tambahan'=>$res['Tambahan']
                                                    );
                            };
             };

                $dataHeader = $data_header;
                $dataDetail = $data_detail;

                $resp= array('success'=>true,'header'=>$dataHeader,'detail'=>$dataDetail);

            }else{
                $resp= array('success'=>false);
            }
        }



        //get Table
        if($action=="getTableTakingOrder"){
                $tableAll = $this->ReadModel->getTableAll();

                //all
                foreach($tableAll AS $res){
                    $arr_curr["data_table_all"][$res["KdLokasi"]] = $res["Keterangan"];
                }

                foreach ($arr_curr["data_table_all"] as $KdLokasi=>$val) {
                    $tableActive = $this->ReadModel->getTableActiveTakingOrder($KdLokasi);

                    if(empty($tableActive)){
                        $a='';
                        $b='';
                        $c='';
                        $d='light2';
                        $e='0';
                        $f='';
                    }else{
                        $a=$tableActive->Kasir;
                        $b=$tableActive->TotalGuest;
                        $c=$tableActive->KdAgent;
                        $d='danger';
                        $e='1';
                        $f=$tableActive->NoTrans;
                    }

                     $data_table[] = array('KdMeja'=>$KdLokasi,'Kasir'=>$a,'TotalGuest'=>$b,'KdAgent'=>$c,'warna'=>$d,'kosong'=>$e,'NoTrans'=>$f);
                }
                
                $dataTable = $data_table;
                $resp= array('success'=>true,'tables'=>$dataTable);
        }

        if($action=="getTable"){
                $tableAll = $this->ReadModel->getTableAll();

                //all
                foreach($tableAll AS $res){
                    $arr_curr["data_table_all"][$res["KdLokasi"]] = $res["Keterangan"];
                }

                foreach ($arr_curr["data_table_all"] as $KdLokasi=>$val) {
                    $tableActive = $this->ReadModel->getTableActive($KdLokasi);
                    if(empty($tableActive)){
                        $data_table[] = array('KdMeja'=>$KdLokasi,'Kasir'=>'','TotalGuest'=>'','KdAgent'=>'','warna'=>'light2','kosong'=>'0','NoTrans'=>'');
                    }else{
                        $data_table[] = array('KdMeja'=>$KdLokasi,'Kasir'=>$tableActive->Kasir,'TotalGuest'=>$tableActive->TotalGuest,'KdAgent'=>$tableActive->KdAgent,'warna'=>'danger','kosong'=>'1','NoTrans'=>$tableActive->NoTrans);
                    }
                }
                $dataTable = $data_table;
                $resp= array('success'=>true,'tables'=>$dataTable);
        }

        //last header menu order
        if($action=="getMenuLastOrder"){
                $noTrans = $postjson['noTrans'];
                $lastOrderHeader = $this->ReadModel->getlastOrderHeader($noTrans);
                
                //last menu order header
                if(!empty($lastOrderHeader)){
                $nosticker = $lastOrderHeader->KdAgent;
                $guest = $lastOrderHeader->TotalGuest;
                $resp  = array('success'=>true,'pax'=>$guest,'sticker'=>$nosticker);
                }else{
                $resp  = array('success'=>false);
                }
        }

        //get Table
        if($action=="getTableListOrder"){
                $timer = $postjson['timer']*1;
                $TableAllListOrder = $this->ReadModel->getTableAllListOrder();

                if(!empty($TableAllListOrder)){
                //all
                foreach($TableAllListOrder AS $res){
                   $TableAllListOrderDetail = $this->ReadModel->getTableAllListOrderDetail($res['KdMeja']); 

                   if(!empty($TableAllListOrderDetail)){
                   $waktu = $TableAllListOrderDetail->Selisih;
                   $pisah_waktu = explode(':',$waktu);
                   $jam_dalam_menit = ($pisah_waktu[0]*1)*60;
                   $menit = ($pisah_waktu[1]*1)+$jam_dalam_menit;

                   //hitung servenya
                    $sql = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalServe FROM trans_order_detail a WHERE a.`KdMeja`='".$res['KdMeja']."' AND a.`Tanggal`=CURDATE() AND a.`Status`='0';";
                    $qry = $this->db->query($sql);
                    $row = $qry->row();
                    $qry->free_result();
                    $totalserve = $row->TotalServe*1;
                    
                   if($totalserve>0){
                   if($menit<$timer){
                    $warna = "green_";
                   }else if($menit>$timer and $menit<(2*$timer)){
                    $warna = "yellow";
                   }else{
                    $warna = "danger";   
                   }

                   }else{
                     $warna = "primary";   
                   }

                    //belek berdasarkan NoTrans dan meja
                    $a = "SELECT * FROM trans_order_header a WHERE a.KdMeja='".$res['KdMeja']."' AND a.Tanggal=CURDATE() AND a.Status='0'";
                    $hsl = $this->getArrayResult($a);

                    foreach($hsl AS $v){
                        $sql2 = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalQty FROM trans_order_detail a WHERE a.`KdMeja`='".$res['KdMeja']."' AND a.`NoTrans`='".$v['NoTrans']."' AND a.`Tanggal`=CURDATE()  AND a.`Status`<>'2';";
                        $qry2 = $this->db->query($sql2);
                        $row2 = $qry2->row();
                        $qry2->free_result();
                        $TotalQty = $row2->TotalQty*1;

                        $this->db->update('trans_order_header',array('TotalQty'=>$TotalQty),array('NoTrans'=>$v['NoTrans']));
                    }

                    //belek berdasarkan NoTrans dan meja
                    foreach($hsl AS $k){
                        $sql3 = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalServe2 FROM trans_order_detail a WHERE a.`KdMeja`='".$res['KdMeja']."' AND a.`NoTrans`='".$k['NoTrans']."' AND a.`Tanggal`=CURDATE()  AND a.`Status`='1';";
                        $qry3 = $this->db->query($sql3);
                        $row3 = $qry3->row();
                        $qry3->free_result();
                        $TotalServe2 = $row3->TotalServe2*1;

                        $this->db->update('trans_order_header',array('TotalServe'=>$TotalServe2),array('NoTrans'=>$res['NoTrans']));
                    }

                        //hitung Quantity
                        $sql4 = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalQty FROM trans_order_detail a WHERE a.`KdMeja`='".$res['KdMeja']."' AND a.`Tanggal`=CURDATE()  AND a.`Status`<>'2';";
                        $qry4 = $this->db->query($sql4);
                        $row4 = $qry4->row();
                        $qry4->free_result();
                        $TotalQty4 = $row4->TotalQty*1;

                        //hitung serve
                        $sql5 = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalServe2 FROM trans_order_detail a WHERE a.`KdMeja`='".$res['KdMeja']."' AND a.`Tanggal`=CURDATE()  AND a.`Status`='1';";
                        $qry5 = $this->db->query($sql5);
                        $row5 = $qry5->row();
                        $qry5->free_result();
                        $TotalServe5 = $row5->TotalServe2*1;

                        $data_list_order[] = array('NoTrans'=>$res['NoTrans'],'KdMeja'=>$res['KdMeja'],'warna'=>$warna,'TotalQty'=>$TotalQty4,'TotalServe'=>$TotalServe5,'waktu'=>$TableAllListOrderDetail->Waktu);
                
                        $resp= array('success'=>true,'tableListOrder'=>$data_list_order);
                }else{
                    if(!empty($TableAllListOrder)==true AND  !empty($TableAllListOrderDetail)==false){
$resp= array('success'=>true,'tableListOrder'=>'');
                    }else{$resp= array('success'=>false,);}
                
                } 
                } 
            }else{
                $resp= array('success'=>false,);
            }

                
        }


        //get Table lite
        if($action=="getTableListOrderLite"){
                $TableAllListOrder = $this->ReadModel->getTableAllListOrder();

                if(!empty($TableAllListOrder)){
                //all
                foreach($TableAllListOrder AS $res){
                    $data_list_order[] = array('NoTrans'=>$res['NoTrans'],'KdMeja'=>$res['KdMeja']);
                    $resp= array('success'=>true,'tableListOrder'=>$data_list_order);
                } 
            }else{
                $resp= array('success'=>false,);
            }
        }

        //get Order Table Detail
        if($action=="getTableListOrderDetail"){
                $notrans = $postjson['notrans'];
                $table = $postjson['table'];
                $TableAllListOrderDetailHeader = $this->ReadModel->getTableAllListOrderDetailHeader($table);
                $TableAllListOrderDetailDetail= $this->ReadModel->getTableAllListOrderDetailDetail($table);

                $data_header_detail_list_order=array('Tanggal'=>$TableAllListOrderDetailHeader->Tanggal,'TotalQty'=>$TableAllListOrderDetailHeader->TotalQty,'TotalItem'=>$TableAllListOrderDetailHeader->TotalItem,'TotalGuest'=>$TableAllListOrderDetailHeader->TotalGuest,'Sticker'=>$TableAllListOrderDetailHeader->KdAgent,'TotalServe'=>$TableAllListOrderDetailHeader->TotalServe,'KdMeja'=>$TableAllListOrderDetailHeader->KdMeja);
                
                //all
                foreach($TableAllListOrderDetailDetail AS $res){
                   $data_detail_list_order[] = array('NoTrans'=>$res['NoTranz'],'NamaLengkap'=>$res['NamaLengkap'],'PCode'=>$res['PCode'],'Qty'=>$res['Qty'],'Status'=>$res['Statuz'],'Berat'=>$res['Berat'],'Keterangan'=>$res['Keterangan'],'Waktu'=>$res['Waktu']);
                }
                $data_lengkap_list_order = $data_detail_list_order;
                $resp= array('success'=>true,'dataListOrderHeader'=>$data_header_detail_list_order,'dataListOrderDetail'=>$data_lengkap_list_order);
        }

        //get Order Table Detail versi lite
        if($action=="getTableListOrderDetailLite"){
                $notrans = $postjson['notrans'];
                $table = $postjson['table'];
                $TableAllListOrderDetailHeader = $this->ReadModel->getTableAllListOrderDetailHeaderLite($table);
                $TableAllListOrderDetailDetail= $this->ReadModel->getTableAllListOrderDetailDetail($table);

                if(!empty($TableAllListOrderDetailHeader) or !empty($TableAllListOrderDetailDetail)){
                    $data_header_detail_list_order=array('Sticker'=>$TableAllListOrderDetailHeader->KdAgent,'KdMeja'=>$TableAllListOrderDetailHeader->KdMeja);
                    
                    //all
                    foreach($TableAllListOrderDetailDetail AS $res){
                    $data_detail_list_order[] = array('NoTrans'=>$res['NoTranz'],'NamaLengkap'=>$res['NamaLengkap'],'PCode'=>$res['PCode'],'Qty'=>$res['Qty'],'Status'=>$res['Statuz'],'Berat'=>$res['Berat'],'Keterangan'=>$res['Keterangan'],'Waktu'=>$res['Waktu']);
                    }
                    $data_lengkap_list_order = $data_detail_list_order;
                    $resp= array('success'=>true,'dataListOrderHeader'=>$data_header_detail_list_order,'dataListOrderDetail'=>$data_lengkap_list_order);
                }else{
                     $resp= array('success'=>false,'msg'=>'');
                }
        }

        if($action=="NoOrderInTheTable"){
           $table = $postjson['table'];
           $NoOrderInTheTable = $this->ReadModel->getNoOrderInTheTable($table);
            
            foreach($NoOrderInTheTable AS $res){
                   $data_no_order_in_the_table[] = array('NoTrans'=>$res['NoTrans'],'Waktu'=>$res['Waktu']);
            }
            $data_no_order_all = $data_no_order_in_the_table;
            $resp= array('success'=>true,'dataNoTransInTheTable'=>$data_no_order_all);
        }

        if($action=="getListOrderDetailForSplit"){
                $table = $postjson['table'];
                $TableAllListOrderDetailDetail= $this->ReadModel->getTableAllListOrderDetailDetailForSplit($table);
                //all
                foreach($TableAllListOrderDetailDetail AS $res){
                   $data_detail_list_order[] = array('NamaLengkap'=>$res['NamaLengkap'],'PCode'=>$res['PCode'],'Qty'=>$res['Qty'],'Status'=>$res['Statuz'],'Berat'=>$res['Berat'],'Keterangan'=>$res['Keterangan'],'Waktu'=>$res['Waktu']);
                }
                $data_lengkap_list_order = $data_detail_list_order;
                $resp= array('success'=>true,'dataListOrderDetail'=>$data_lengkap_list_order);
        }

        if($action=="getListOrderDetailForSplitTry"){
                $table = $postjson['table'];
                $TableAllListOrderDetailHeaderCek = $this->ReadModel->getTableAllListOrderDetailHeaderForSplitTry($table);
                //cek apakah record lebih dari 1
                if(count($TableAllListOrderDetailHeaderCek)>1){
                $this->insertNewRows($table); 
                
                //update status 2 di notrans
                foreach($TableAllListOrderDetailHeaderCek AS $valk){
                $wehere =array('NoTrans'=>$valk['NoTrans']);
                $datad = array('Status'=>'2');
                $this->db->update('trans_order_header',$datad,$wehere);
                $this->db->update('trans_order_detail',$datad,$wehere);
                }
                }
                
               
                $TableAllListOrderDetailHeader = $this->ReadModel->getTableAllListOrderDetailHeaderForSplitTry($table);
                $TableAllListOrderDetailDetail= $this->ReadModel->getTableAllListOrderDetailDetailForSplitTry($table);

                foreach($TableAllListOrderDetailHeader AS $val){
                $data_header_detail_list_order[]=array(
                                                    'NoTrans'=>$val['NoTrans'],
                                                    'NoKassa'=>$val['NoKassa'],
                                                    'Tanggal'=>$val['Tanggal'],
                                                    'Waktu'=>$val['Waktu'],
                                                    'Kasir'=>$val['Kasir'],
                                                    'TotalItem'=>$val['TotalItem'],
                                                    'TotalQty'=>$val['TotalQty'],
                                                    'TotalServe'=>$val['TotalServe'],
                                                    'Status'=>$val['Status'],
                                                    'KdMeja'=>$val['KdMeja'],
                                                    'TotalGuest'=>$val['TotalGuest'],
                                                    'KdAgent'=>$val['KdAgent']);
                }
                //all
                foreach($TableAllListOrderDetailDetail AS $res){
                   $data_detail_list_order[] = array(
                                                    'NoTrans'=>$res['NoTrans'],
                                                    'NoUrut'=>$res['NoUrut'],
                                                    'NoKassa'=>$res['NoKassa'],
                                                    'Tanggal'=>$res['Tanggal'],
                                                    'Waktu'=>$res['Waktu'],
                                                    'Kasir'=>$res['Kasir'],
                                                    'NamaLengkap'=>$res['NamaLengkap'],
                                                    'PCode'=>$res['PCode'],
                                                    'Qty'=>$res['Qty'],
                                                    'Berat'=>$res['Berat'],
                                                    'Keterangan'=>$res['Keterangan'],
                                                    'Status'=>$res['Status'],
                                                    'KdMeja'=>$res['KdMeja'],
                                                    'MenuBaru'=>$res['MenuBaru'],
                                                    'Tambahan'=>$res['Tambahan']);
                }
                $data_lengkap_list_order = $data_detail_list_order;
                $resp= array('success'=>true,'dataListOrderHeader'=>$data_header_detail_list_order,'dataListOrderDetail'=>$data_lengkap_list_order);
        }

        json_output($response['status'],$resp);
		        
    }


    public function insertNewRows($table){
        
                    //masuk proses di generetkan menjadi 1 No order
                    $TableAllListOrderDetailHeaderGen = $this->ReadModel->getTableAllListOrderDetailHeaderForSplitGenTry($table);
                    foreach($TableAllListOrderDetailHeaderGen AS $valx){
                        $datax =array(
                                    'NoTransApps'=>$valx['NoTransApps'].'00',
                                    'NoKassa'=>$valx['NoKassa'],
                                    'Tanggal'=>$valx['Tanggal'],
                                    'Waktu'=>$valx['Waktu'],
                                    'Kasir'=>$valx['Kasir'],
                                    'KdStore'=>$valx['KdStore'],
                                    'TotalItem'=>$valx['TotalItem'],
                                    'TotalQty'=>$valx['TotalQty'],
                                    'TotalServe'=>$valx['TotalServe'],
                                    'Status'=>$valx['Status'],
                                    'KdPersonal'=>$valx['KdPersonal'],
                                    'KdMeja'=>$valx['KdMeja'],
                                    'KdContact'=>$valx['KdContact'],
                                    'nokasst'=>$valx['nokasst'],
                                    'nostruk'=>$valx['nostruk'],
                                    'TotalGuest'=>$valx['TotalGuest'],
                                    'AddDate'=>$valx['AddDate'],
                                    'keterangan'=>$valx['keterangan'],
                                    'KdAgent'=>$valx['KdAgent'],
                                    'IsCommit'=>$valx['IsCommit']
                        );

                        $NoOrderNew = $this->db->insert('trans_order_header',$datax);

                        $NoApps = $valx['NoTransApps'].'00';
                        $sql = "SELECT NoTrans FROM trans_order_header WHERE NoTransApps='$NoApps'";
                        $qry = $this->db->query($sql);
                        $row = $qry->row();
                        $qry->free_result();
                        $NoNewOrder = $row->NoTrans;
                        //echo $NoNewOrder;die();

                        $TableAllListOrderDetailHeaderGenDet = $this->ReadModel->getTableAllListOrderDetailDetailForSplitDetTry($table);

                        $noo=1;
                        foreach($TableAllListOrderDetailHeaderGenDet AS $resx){
                        $dhata = array(
                                        'NoTrans'=>$NoNewOrder,
                                        'NoUrut'=>$noo,
                                        'NoKassa'=>$resx['NoKassa'],
                                        'Tanggal'=>$resx['Tanggal'],
                                        'Waktu'=>$resx['Waktu'],
                                        'Kasir'=>$resx['Kasir'],
                                        'KdStore'=>$resx['KdStore'],
                                        'PCode'=>$resx['PCode'],
                                        'Qty'=>$resx['Qty'],
                                        'Berat'=>$resx['Berat'],
                                        'Satuan'=>$resx['Satuan'],
                                        'Keterangan'=>$resx['Keterangan'],
                                        'Note_split'=>$resx['Note_split'],
                                        'Status'=>$resx['Status'],
                                        'KdPersonal'=>$resx['KdPersonal'],
                                        'KdMeja'=>$resx['KdMeja'],
                                        'KdContact'=>$resx['KdContact'],
                                        'MenuBaru'=>$resx['MenuBaru'],
                                        'Tambahan'=>$resx['Tambahan']
                                    );

                        $this->db->insert('trans_order_detail',$dhata);
                                $noo++;

                        }

                        $sqlk = "SELECT COUNT(PCode) AS items FROM trans_order_detail WHERE NoTrans='$NoNewOrder'";
                        $qryk = $this->db->query($sqlk);
                        $rowk = $qryk->row();
                        $qryk->free_result();
                        $ttlitem = $rowk->items;

                        $this->db->update('trans_order_header',array('TotalItem'=>$ttlitem),array('NoTrans'=>$NoNewOrder));
                        $this->db->query("UPDATE trans_order_header SET TotalServe=TotalQty WHERE NoTrans='$NoNewOrder'");

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
