<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReadModel extends CI_Model {

    public function getKassa($ip){
      return $this->db->select('*')->from('kassa')->where('ip',$ip)->order_by('','')->get()->row();
    }

    function getAllDataHeader() {
        $sql = "SELECT * FROM trans_order_header a WHERE a.Tanggal=CURDATE() AND a.Status='0'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetailHeaderLite($KdMeja) {
        $sql = "SELECT a.`KdAgent`,a.`KdMeja` FROM trans_order_header a WHERE a.`KdMeja`='$KdMeja' AND a.Status='0' AND a.`Tanggal`=CURDATE() GROUP BY a.`KdMeja`;";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getAllDataDetail($NoTrans) {
        $sql = "SELECT * FROM trans_order_detail a WHERE a.NoTrans='$NoTrans'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAll() {
        $sql = "SELECT * FROM lokasipos a WHERE a.KdDivisi='01' ORDER BY a.Keterangan ASC";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableActive($KdLokasi) {
        $sql = "SELECT
                *
                FROM
                trans_order_header a
                INNER JOIN trans_order_detail b
                    ON a.`KdMeja` = b.`KdMeja`
                    AND a.`NoTrans` = b.`NoTrans`
                WHERE a.Tanggal = CURDATE()
                AND a.KdMeja = '$KdLokasi'
                AND a.Status = '0'
                AND b.`Status`='1'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }


    function getTableActiveTakingOrder($KdLokasi) {
        $sql = "SELECT
                *
                FROM
                trans_order_header a
                WHERE a.Tanggal = CURDATE()
                AND a.KdMeja = '$KdLokasi'
                AND a.Status = '0'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getlastOrderHeader($NoTrans) {
        $sql = "SELECT * FROM trans_order_header a WHERE a.NoTrans='$NoTrans'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function getTableAllListOrder() {
        $sql = "SELECT * FROM trans_order_header a WHERE a.`Tanggal`=CURDATE() AND a.`Status`='0' GROUP BY a.`KdMeja`  ORDER BY a.`KdMeja` ASC;";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetail($KdMeja) {
        $sql = "SELECT a.NoTrans,a.`KdMeja`,a.`Waktu`,TIMEDIFF(TIME(NOW()),a.`Waktu`) AS Selisih FROM trans_order_detail a WHERE a.`Tanggal`=CURDATE() AND a.`KdMeja`='$KdMeja' ORDER BY a.`Waktu` ASC LIMIT 0,1;";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getTableAllListOrderDetailHeader($KdMeja) {
        $sql = "SELECT a.`Tanggal`,SUM(a.`TotalItem`) AS TotalItem,SUM(a.`TotalQty`) AS TotalQty,SUM(a.`TotalServe`) AS TotalServe,SUM(a.`TotalGuest`) AS TotalGuest,a.`KdAgent`,a.`KdMeja` FROM trans_order_header a WHERE a.`KdMeja`='$KdMeja' AND a.`Tanggal`=CURDATE() GROUP BY a.`KdMeja`;";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getTableAllListOrderDetailHeaderForSplit($KdMeja) {
        $sql = "SELECT * FROM trans_order_header a WHERE a.`KdMeja`='$KdMeja' AND a.`Tanggal`=CURDATE();";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getTableAllListOrderDetailDetail($KdMeja) {
        $sql = "SELECT
                a.*,
                a.NoTrans AS NoTranz,
                c.NamaLengkap,
                a.Status AS Statuz
                FROM
                trans_order_detail a
                INNER JOIN trans_order_header b
                ON a.`NoTrans` = b.`NoTrans`
                INNER JOIN masterbarang c
                    ON a.PCode = c.PCode
                WHERE a.`KdMeja` = '$KdMeja'
                AND b.`Status` ='0'
                AND b.`Tanggal`=CURDATE();
                ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetailDetailForSplit($KdMeja) {
        $sql = "SELECT
                a.*,
                c.NamaLengkap,
                a.Status AS Statuz
                FROM
                trans_order_detail a
                INNER JOIN trans_order_header b
                ON a.`NoTrans` = b.`NoTrans`
                INNER JOIN masterbarang c
                    ON a.PCode = c.PCode
                WHERE a.`KdMeja` = '$KdMeja'
                AND b.`Status` ='0'
                AND b.`Tanggal`=CURDATE();
                ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }
    
    function getTableAllListOrderDetailDetailForSplitTry($KdMeja) {
        $sql = "SELECT
                a.*,
                c.NamaLengkap
                FROM
                trans_order_detail a
                INNER JOIN trans_order_header b
                ON a.`NoTrans` = b.`NoTrans`
                INNER JOIN masterbarang c
                    ON a.PCode = c.PCode
                WHERE a.`KdMeja` = '$KdMeja'
                AND b.`Status` ='0'
                AND b.`Tanggal`=CURDATE() 
                ORDER BY a.`PCode` ASC;
                ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetailDetailForSplitDetTry($KdMeja) {
        $sql = "SELECT
                a.`NoKassa`,
                a.`Tanggal`,
                a.`Waktu`,
                a.`Kasir`,
                a.`KdStore`,
                a.`PCode`,
                SUM(a.`Qty`) AS Qty,
                SUM(a.`Berat`) AS Berat,
                a.`Satuan`,
                a.`Keterangan`,
                a.`Note_split`,
                a.`Status`,
                a.`KdPersonal`,
                a.`KdMeja`,
                a.`KdContact`,
                a.`MenuBaru`,
                a.`Tambahan`,
                c.NamaLengkap
                FROM
                trans_order_detail a
                INNER JOIN trans_order_header b
                    ON a.`NoTrans` = b.`NoTrans`
                INNER JOIN masterbarang c
                    ON a.PCode = c.PCode
                WHERE a.`KdMeja` = '$KdMeja'
                AND b.`Status` = '0'
                AND b.`Tanggal` = CURDATE()
                GROUP BY a.`PCode`
                ORDER BY c.`NamaLengkap` ASC ;
                ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetailHeaderForSplitTry($KdMeja) {
        $sql = "SELECT * FROM trans_order_header a WHERE a.`KdMeja`='$KdMeja' AND a.`Status`='0' AND a.`Tanggal`=CURDATE();";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getTableAllListOrderDetailHeaderForSplitGenTry($KdMeja) {
        $sql = "SELECT
                `NoTransApps`,
                `NoKassa`,
                `Tanggal`,
                `Waktu`,
                `Kasir`,
                `KdStore`,
                SUM(TotalItem) AS `TotalItem`,
                SUM(TotalQty) AS `TotalQty`,
                SUM(TotalServe) AS `TotalServe`,
                `Status`,
                `KdPersonal`,
                `KdMeja`,
                `KdContact`,
                `nokasst`,
                `nostruk`,
                `TotalGuest`,
                `AddDate`,
                `keterangan`,
                `KdAgent`,
                `IsCommit`
                FROM
                `trans_order_header`
                WHERE `KdMeja` = '$KdMeja'
                AND `Status` = '0'
                AND `Tanggal` = CURDATE();";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getNoOrderInTheTable($KdMeja) {
        $sql = "SELECT * FROM trans_order_header a WHERE a.`Tanggal`=CURDATE() AND a.`KdMeja`='$KdMeja' AND a.`Status`='0';;
                ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

}
