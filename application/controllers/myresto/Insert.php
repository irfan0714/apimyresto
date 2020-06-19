<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('myresto/InsertModel');
    }

	public function index()
	{
        $response['status'] =200;
        $ipaddress		    = $_SERVER['REMOTE_ADDR'];
        $res_kassa	        = $this->InsertModel->getKassa($ipaddress);
        $kassa		        = $res_kassa->id_kassa;
        
		$postjson = json_decode(file_get_contents('php://input'),true);
        $action = $postjson['action'];

        if($action=="insertHeader"){

             $NoTrans= $postjson['NoTrans'];
             $NoKassa= $postjson['NoKassa'];
             $Tanggal= $postjson['Tanggal'];
             $Waktu= $postjson['Waktu'];
             $Kasir= $postjson['Kasir'];
             $KdStore= $postjson['KdStore'];
             $TotalItem= $postjson['TotalItem'];
             $TotalQty= $postjson['TotalQty'];
             $TotalServe= $postjson['TotalServe'];
             $Status= $postjson['Status'];
             $KdPersonal= $postjson['KdPersonal'];
             $KdMeja= $postjson['KdMeja'];
             $KdContact= $postjson['KdContact'];
             $nostruk= $postjson['nostruk'];
             $TotalGuest= $postjson['TotalGuest'];
             $AddDate= $postjson['AddDate'];
             $keterangan= $postjson['keterangan'];
             $KdAgent= $postjson['KdAgent'];
             $IsCommit= $postjson['IsCommit'];
             $cetak= 'N';

             $adddates     = date('y-m-d');
			 $Jamsekarangs = date("H:i:s");

             $resp = $this->InsertModel->insertHeader(  $NoTrans,
                                                        $kassa,
                                                        $adddates,
                                                        $Jamsekarangs,
                                                        $Kasir,
                                                        $KdStore,
                                                        $TotalItem,
                                                        $TotalQty,
                                                        $TotalServe,
                                                        $Status,
                                                        $KdPersonal,
                                                        $KdMeja,
                                                        $KdContact,
                                                        $nostruk,
                                                        $TotalGuest,
                                                        $adddates,
                                                        $keterangan,
                                                        $KdAgent,
                                                        $IsCommit);

        }

        if($action=="insertHeaderSplit"){

             $NoTrans= $postjson['NoTrans'];
             $NoKassa= $postjson['NoKassa'];
             $Tanggal= $postjson['Tanggal'];
             $Waktu= $postjson['Waktu'];
             $Kasir= $postjson['Kasir'];
             $KdStore= $postjson['KdStore'];
             $TotalItem= $postjson['TotalItem'];
             $TotalQty= $postjson['TotalQty'];
             $TotalServe= $postjson['TotalServe'];
             //  $Status= $postjson['Status'];
             $Status= 0;
             $KdPersonal= $postjson['KdPersonal'];
             $KdMeja= $postjson['KdMeja'];
             $KdContact= $postjson['KdContact'];
             $nostruk= $postjson['nostruk'];
             $TotalGuest= $postjson['TotalGuest'];
             $AddDate= $postjson['AddDate'];
             $keterangan= $postjson['keterangan'];
             $KdAgent= $postjson['KdAgent'];
             $IsCommit= $postjson['IsCommit'];
             $cetak= 'N';

             $adddates     = date('y-m-d');
			 $Jamsekarangs = date("H:i:s");

             $resp = $this->InsertModel->insertHeader(  $NoTrans,
                                                        $kassa,
                                                        $adddates,
                                                        $Jamsekarangs,
                                                        $Kasir,
                                                        $KdStore,
                                                        $TotalItem,
                                                        $TotalQty,
                                                        $TotalServe,
                                                        $Status,
                                                        $KdPersonal,
                                                        $KdMeja,
                                                        $KdContact,
                                                        $nostruk,
                                                        $TotalGuest,
                                                        $adddates,
                                                        $keterangan,
                                                        $KdAgent,
                                                        $IsCommit);

        }
        
        if($action=="insertDetail") {
              
	for($i=0;$i < count($postjson['data_order']); $i++){

              $NoTrans= $postjson['data_order'][$i]['NoTrans'];
              $NoUrut= $postjson['data_order'][$i]['NoUrut'];
              $NoKassa= $postjson['data_order'][$i]['NoKassa'];
              $Tanggal= $postjson['data_order'][$i]['Tanggal'];
              $Waktu= $postjson['data_order'][$i]['Waktu'];
              $Kasir= $postjson['data_order'][$i]['Kasir'];
              $KdStore= $postjson['data_order'][$i]['KdStore'];
              $PCode= $postjson['data_order'][$i]['PCode'];
              $Name= $postjson['data_order'][$i]['Name'];
              $Qty= $postjson['data_order'][$i]['Qty'];
              $Berat= $postjson['data_order'][$i]['Berat'];
              $Satuan= $postjson['data_order'][$i]['Satuan'];
              $Keterangan= $postjson['data_order'][$i]['Keterangan'];
              $Note_split= $postjson['data_order'][$i]['Note_split'];
              $Status= $postjson['data_order'][$i]['Status'];
              $KdPersonal= $postjson['data_order'][$i]['KdPersonal'];
              $KdMeja= $postjson['data_order'][$i]['KdMeja'];
              $KdContact= $postjson['data_order'][$i]['KdContact'];
              $MenuBaru= $postjson['data_order'][$i]['MenuBaru'];
              $Tambahan= $postjson['data_order'][$i]['Tambahan'];
              $cetak= $postjson['data_order'][$i]['cetak'];

              $adddates     = date('y-m-d');
			  $Jamsekarangs = date("H:i:s");

                    $this->db->delete('trans_order_detail',array('NoTrans'=>$NoTrans,'NoUrut'=>$NoUrut,'PCode'=>$PCode,'Qty'=>$Qty));
            $resp = $this->InsertModel->insertDetail($NoTrans,
              $NoUrut,
              $kassa,
              $adddates,
              $Jamsekarangs,
              $Kasir,
              $KdStore,
              $PCode,
              $Name,
              $Qty,
              $Berat,
              $Satuan,
              $Keterangan,
              $Note_split,
              $Status,
              $KdPersonal,
              $KdMeja,
              $KdContact,
              $MenuBaru,
              $Tambahan);

		}
              
        }

        if($action=="insertDetailSplit") {
              
              $NoTrans= $postjson['NoTrans'];
              $NoUrut= $postjson['NoUrut'];
              $NoKassa= $postjson['NoKassa'];
              $Tanggal= $postjson['Tanggal'];
              $Waktu= $postjson['Waktu'];
              $Kasir= $postjson['Kasir'];
              $KdStore= $postjson['KdStore'];
              $PCode= $postjson['PCode'];
              $Name= $postjson['Name'];
              $Qty= $postjson['Qty'];
              $Berat= $postjson['Berat'];
              $Satuan= $postjson['Satuan'];
              $Keterangan= $postjson['Keterangan'];
              $Note_split= $postjson['Note_split'];
              $Status= $postjson['Status'];
              $KdPersonal= $postjson['KdPersonal'];
              $KdMeja= $postjson['KdMeja'];
              $KdContact= $postjson['KdContact'];
              $MenuBaru= $postjson['MenuBaru'];
              $Tambahan= $postjson['Tambahan'];
              $cetak= 'N';

              $adddates     = date('y-m-d');
			  $Jamsekarangs = date("H:i:s");

                    $this->db->delete('trans_order_detail',array('NoTrans'=>$NoTrans,'NoUrut'=>$NoUrut,'PCode'=>$PCode,'Qty'=>$Qty));
            $resp = $this->InsertModel->insertDetail($NoTrans,
              $NoUrut,
              $kassa,
              $adddates,
              $Jamsekarangs,
              $Kasir,
              $KdStore,
              $PCode,
              $Name,
              $Qty,
              $Berat,
              $Satuan,
              $Keterangan,
              $Note_split,
              $Status,
              $KdPersonal,
              $KdMeja,
              $KdContact,
              $MenuBaru,
              $Tambahan);
              
        }
    

        if($action=="cetakUlang"){
              $NoTrans= $postjson['notrans'];
              $Table= $postjson['table'];
              $Tipe= $postjson['tipe'];

              if($Tipe=="toAll"){
                 $this->cetakall($NoTrans);
              }else if($Tipe=="toTableControl"){
                  $this->cetakUlangMeja($NoTrans);
              }else if($Tipe=="toKitchen"){
                  $this->cetakUlangMakanan($NoTrans);
              }else if($Tipe=="toLivecooking"){
                  $this->cetakUlangLiveCooking($NoTrans);
              }else if($Tipe=="toGrill"){
                  $this->cetakUlangLiveCooking($NoTrans);
              }else if($Tipe=="toBar"){
                  $this->cetakUlangMinuman($NoTrans);
              }

              $resp  = array('success'=>true);
        }

         if($cetak=='Y'){
                $this->cetakall($NoTrans);
         }
	    
		 json_output($response['status'],$resp);
		        
    }
    
    function cetakall($notrans){
		// Meja
		$store = $this->InsertModel->aplikasi();
        $ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_order'];
		$data['nm_printer'] = $printer[0]['nm_printer_order'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,1);
		$data['jenis'] = 1;
		$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
		//$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
		
		//Minuman
		$store = $this->InsertModel->aplikasi();
		$ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_bar'];
		$data['nm_printer'] = $printer[0]['nm_printer_bar'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,2);
        $data['jenis'] = 2;
        if(!empty($data['detail'])){
		$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
        }
        
		//Makanan
		$store = $this->InsertModel->aplikasi();
		$ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_kitchen'];
		$data['nm_printer'] = $printer[0]['nm_printer_kitchen'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,3);
        $data['jenis'] = 3;
        if(!empty($data['detail'])){
          //ditutup karena ga perlu atas masukan pak sandro
		//$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
        }

        //LIVE COOKING 
		$store = $this->InsertModel->aplikasi();
		$ipaddres   = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_cooking'];
		$data['nm_printer'] = $printer[0]['nm_printer_cooking'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,4);
		$data['jenis'] = 4;
		

			if(!empty($data['detail'][0]['PCode'])){
			$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
			}else{
				// echo "Kosong";
			}

    }

    function cetakUlangMeja($notrans){
        // Meja
		$store = $this->InsertModel->aplikasi();
        $ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_order'];
		$data['nm_printer'] = $printer[0]['nm_printer_order'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,1);
		$data['jenis'] = 1;
		$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
		//$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
    }

    function cetakUlangMinuman($notrans){
        //Minuman
		$store = $this->InsertModel->aplikasi();
		$ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_bar'];
		$data['nm_printer'] = $printer[0]['nm_printer_bar'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,2);
        $data['jenis'] = 2;
        if(!empty($data['detail'])){
		$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
        }
    }

    function cetakUlangMakanan($notrans){
        //Makanan
		$store = $this->InsertModel->aplikasi();
		$ipaddres	    = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_kitchen'];
		$data['nm_printer'] = $printer[0]['nm_printer_kitchen'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,3);
        $data['jenis'] = 3;
        if(!empty($data['detail'])){
		$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
        }
    }

    function cetakUlangLiveCooking($notrans){
        //live cooking
        $store = $this->InsertModel->aplikasi();
		$ipaddres   = $_SERVER['REMOTE_ADDR'];
		$printer = $this->InsertModel->NamaPrinter($ipaddres);
		$data['store'] = $store;
		$data['ip'] = $printer[0]['ipprinter_cooking'];
		$data['nm_printer'] = $printer[0]['nm_printer_cooking'];
		$data['kdkategori'] = $printer[0]['KdKategori'];
		$data['header'] = $this->InsertModel->all_trans($notrans);
		$data['detail'] = $this->InsertModel->det_trans($notrans,4);
		$data['jenis'] = 4;
		

			if(!empty($data['detail'][0]['PCode'])){
			$this->load->view('myresto/cetak/cetak_transaksi_wireness', $data);
			}else{
				// echo "Kosong";
			}
    }
}
