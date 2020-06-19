<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InsertModel extends CI_Model {

    public function insertHeader(   $NoTrans,
                                    $NoKassa,
                                    $Tanggal,
                                    $Waktu,
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
                                    $AddDate,
                                    $keterangan,
                                    $KdAgent,
                                    $IsCommit){
                                        $data = array(
                                            'NoTransApps' => $NoTrans,
                                            'NoKassa' => $NoKassa,
                                            'Tanggal' => $Tanggal,
                                            'Waktu' => $Waktu,
                                            'Kasir' => $Kasir,
                                            'KdStore' => $KdStore,
                                            'TotalItem' => $TotalItem,
                                            'TotalQty' => $TotalQty,
                                            'TotalServe' => $TotalServe,
                                            'Status' => $Status,
                                            'KdPersonal' => $Kasir,
                                            'KdMeja' => $KdMeja,
                                            'KdContact' => $KdContact,
                                            'nostruk' => $nostruk,
                                            'TotalGuest' => $TotalGuest,
                                            'AddDate' => $AddDate,
                                            'keterangan' => $keterangan,
                                            'KdAgent' => $KdAgent,
                                            'IsCommit' => $IsCommit
                                        );

                                        $this->db->insert('trans_order_header',$data);

                                        $no_order = $this->db->select('NoTrans')->from('trans_order_header')->where('NoTransApps',$NoTrans)->order_by('','')->get()->row();

                                        return array('success'=>true,'no_order'=>$no_order->NoTrans);
                                    }


    public function insertDetail(   $NoTrans,
                                    $NoUrut,
                                    $NoKassa,
                                    $Tanggal,
                                    $Waktu,
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
                                    $Tambahan){
                                        $data = array(
                                            'NoTrans'=>$NoTrans,
                                            'NoUrut'=>$NoUrut,
                                            'NoKassa'=>$NoKassa,
                                            'Tanggal'=>$Tanggal,
                                            'Waktu'=>$Waktu,
                                            'Kasir'=>$Kasir,
                                            'KdStore'=>$KdStore,
                                            'PCode'=>$PCode,
                                            'Qty'=>$Qty,
                                            'Berat'=>$Berat,
                                            'Satuan'=>$Satuan,
                                            'Keterangan'=>$Keterangan,
                                            'Note_split'=>$Note_split,
                                            'Status'=>$Status,
                                            'KdPersonal'=>$Kasir,
                                            'KdMeja'=>$KdMeja,
                                            'KdContact'=>$KdContact,
                                            'MenuBaru'=>$MenuBaru,
                                            'Tambahan'=>$Tambahan
                                        );

                                        $this->db->insert('trans_order_detail',$data);

                                        $TotalItems = $this->db->select('COUNT(PCode) AS TotalItem')->from('trans_order_detail')->where('NoTrans',$NoTrans)->order_by('','')->get()->row();
                                        $this->db->update('trans_order_header',array('TotalItem'=>$TotalItems->TotalItem),array('NoTrans'=>$NoTrans));

                                        return array('success'=>true);
                                    }

    public function getKassa($ip){
      return $this->db->select('*')->from('kassa')->where('ip',$ip)->order_by('','')->get()->row();
    }

    function aplikasi()
	{
		$sql = "select * from aplikasi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        
        return $row;
    }
    
    function NamaPrinter($id) {
        $sql = "SELECT * from kassa where ip='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function all_trans($id) {
        $sql = "select h.* from trans_order_header h where h.NoTrans = '$id'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function det_trans($id, $jenis) {
    	if($jenis==1) // meja
    		$where = " ";
    	elseif($jenis==2) // minuman
    		$where = " and s.`Jenis`= 'B'";
    	elseif($jenis==3) //makanan
    		$where = " and s.`Jenis`= 'F'";
    	elseif($jenis==4) //live cooking
    		$where = " and s.`Jenis`= 'J'";
    		
        // $sql = "select a.PCode,b.NamaLengkap as NamaStruk,a.Qty, a.Keterangan
		// 	from trans_order_detail a inner join masterbarang_touch b on a.PCode=b.Pcode
		// 	LEFT JOIN subkategoripos s ON b.`KdSubKategori`=s.`KdSubKategori`
        //     where a.NoTrans='$id' $where order by s.`Jenis` desc, Waktu ASC";
            
        $sql = "SELECT a.PCode,b.NamaLengkap AS NamaStruk,z.Image,a.Berat,z.`NamaInitial`,a.Qty, a.Keterangan
			FROM trans_order_detail a INNER JOIN masterbarang_touch b ON a.PCode=b.Pcode
			INNER JOIN masterbarang z ON a.`PCode`=z.`PCode`
			LEFT JOIN subkategoripos s ON b.`KdSubKategori`=s.`KdSubKategori`
            where a.NoTrans='$id' $where order by s.`Jenis` desc, Waktu ASC";
    
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row;
    }

}
