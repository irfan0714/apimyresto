<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('myresto/UpdateModel');
    }

	public function index()
	{
        $response['status'] =200;        
		$postjson = json_decode(file_get_contents('php://input'),true);
        $action = $postjson['action'];

        if($action=="updateHeader"){
            $notrans = $postjson['notrans'];
            $kdmeja = $postjson['meja'];
            $nosticker = $postjson['nosticker'];

            $resp = $this->UpdateModel->updateHeader($notrans,$kdmeja,$nosticker);
        
        }
        
        if($action=="updateDetail"){
            $notrans = $postjson['notrans'];
            $pcode = $postjson['pcode'];
            $status = $postjson['status'];
            $berat = $postjson['berat'];
       
            $resp = $this->UpdateModel->updateDetail($notrans,$pcode,$status,$berat);
        
        }

        if($action=="checkListMenuOut"){
            $notrans = $postjson['notrans'];
            $pcode = $postjson['pcode'];
            $qty= $postjson['qty'];

            $resp = $this->UpdateModel->checkListMenuOut($notrans,$pcode,$qty);
        }

        if($action=="changeTable"){
            $notrans = $postjson['notrans'];
            $table = $postjson['table'];

            $resp = $this->UpdateModel->changeTable($notrans,$table);
        }


        if($action=="voidReferensOrderSplit"){
            $notrans = $postjson['NoTransSemula'];

            $resp = $this->UpdateModel->voidTransaksi($notrans);
        }

        if($action=="voidOrder"){
            $table = $postjson['table'];
            $resp = $this->UpdateModel->voidTable($table);
        }
	    
		 json_output($response['status'],$resp);
		        
	}
}
