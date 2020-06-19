<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateModel extends CI_Model {

    public function updateDetail($notrans,$pcode,$status,$berat){
        
        if($berat!=''){
            $data = array('Status'=>$status,
                          'Berat'=>$berat);
        }else{
            $data = array('Status'=>$status);
        }
        

        $where = array('Notrans'=>$notrans,
                       'PCode'=>$pcode);

        $this->db->update('trans_order_detail',$data,$where);    
        
        //hitung servenya
        $sql = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalServe FROM trans_order_detail a WHERE a.`NoTrans`='$notrans' AND a.`Status`='1';";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        $totalserve = $row->TotalServe;

        //update totalserve di header
        $this->db->update('trans_order_header',array('TotalServe'=>$totalserve),array('Notrans'=>$notrans));
        
        return array('success'=>true,'totalserve'=>$totalserve);
    }

    public function checkListMenuOut($notrans,$pcode,$qty){
        
        $data = array('Status'=>'1');
        $where = array('Notrans'=>$notrans,
                       'PCode'=>$pcode,
                       'Qty'=>$qty);

        $this->db->update('trans_order_detail',$data,$where); 
        
        //hitung servenya
        $sql = "SELECT IF(SUM(a.Qty) IS NULL,0,SUM(a.Qty)) AS TotalServe FROM trans_order_detail a WHERE a.`NoTrans`='$notrans' AND a.`Status`='1';";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        $totalserve = $row->TotalServe;

        //update totalserve di header
        $this->db->update('trans_order_header',array('TotalServe'=>$totalserve),array('Notrans'=>$notrans));
        return array('success'=>true,'totalserve'=>$totalserve);
    }

    public function updateHeader($notrans,$kdmeja,$nosticker){
        
        if($kdmeja!=''){
            $data = array('KdMeja'=>$kdmeja);
        }else{
            $data = array('KdAgent'=>$nosticker);
        }
        
        $where = array('Notrans'=>$notrans);

        $this->db->update('trans_order_header',$data,$where);               
        return array('success'=>true);
    }

    public function changeTable($notrans,$table){
       
        $data = array('KdMeja'=>$table);
        $where = array('Notrans'=>$notrans);
        
        $this->db->update('trans_order_header',$data,$where);    
        $this->db->update('trans_order_detail',$data,$where);    
        
        return array('success'=>true);
    }

    public function voidTransaksi($notrans){
       
        $data = array('Status'=>'2');
        $where = array('Notrans'=>$notrans);

        $this->db->update('trans_order_header',$data,$where);    
        $this->db->update('trans_order_detail',$data,$where);    
        
        return array('success'=>true);
    }

    public function voidTable($table){
       
        $data = array('Status'=>'2');
        $where = array('Tanggal'=>date('Y-m-d'),'KdMeja'=>$table,'Status'=>'0');
        $this->db->update('trans_order_header',$data,$where);    
        $this->db->update('trans_order_detail',$data,$where);    
        
        return array('success'=>true);
    }


}
