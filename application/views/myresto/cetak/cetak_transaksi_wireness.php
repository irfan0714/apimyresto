<?php
$reset = chr(27) . '@';
$plength = chr(27) . 'C';
$lmargin = chr(27) . 'l';
$cond = chr(15);
$ncond = chr(18);
$dwidth = chr(27) . '!' . chr(24);
$ndwidth = chr(27) . '!' . chr(14);
$draft = chr(27) . 'x' . chr(48);
$nlq = chr(27) . 'x' . chr(49);
$bold = chr(27) . 'E';
$nbold = chr(27) . 'F';
$uline = chr(27) . '!' . chr(129);
$nuline = chr(27) . '!' . chr(1);
$dstrik = chr(27) . 'G';
$ndstrik = chr(27) . 'H';
$elite = '';
$pica = chr(27) . 'P';
$height = chr(27) . '!' . chr(16);
$nheight = chr(27) . '!' . chr(1);
$spasi05 = chr(27) . "3" . chr(16);
$spasi1 = chr(27) . "3" . chr(24);
$fcut = chr(10) . chr(10) . chr(10) . chr(10) . chr(10) . chr(13) . chr(27) . 'i';
$pcut = chr(10) . chr(10) . chr(10) . chr(10) . chr(10) . chr(13) . chr(27) . 'm';
$op_cash = chr(27) . 'p' . chr(0) . chr(50) . chr(20) . chr(20);
$ftext = '';

$ftext = printer_open($nm_printer);
printer_set_option($ftext, PRINTER_MODE, "raw");
printer_set_option($ftext, PRINTER_COPIES, "1");
$tgl = $header[0]['Tanggal'];
$tgl_1 = explode("-", $tgl);
$tgl_tampil = $tgl_1[2] . "/" . $tgl_1[1] . "/" . $tgl_1[0];

if($jenis==1)
	$namajenis='Kontrol Meja';
elseif($jenis==2)
	$namajenis='Minuman / Bar';	
elseif($jenis==3)
	$namajenis='Makanan / Kitchen';	
else
	$namajenis='Makanan / Livecooking';
	
printer_write($ftext, "Meja    : ".$dwidth.$header[0]['KdMeja'].$ndwidth."\r\n");
printer_write($ftext, $reset . $elite);
printer_write($ftext, "\r\n");
printer_write($ftext, "Tanggal : " . $tgl_tampil . " - " . $header[0]['Waktu'] . "\r\n");
printer_write($ftext, "Jenis   : " . $namajenis . "\r\n");
printer_write($ftext, "Waiters : " . $header[0]['Kasir'] . "\r\n");
printer_write($ftext, "Struk   : " . $header[0]['NoTrans'] . "\r\n");
if($jenis==1){
printer_write($ftext, "Pax     : " . $header[0]['TotalGuest'] . "\r\n");
}

printer_write($ftext, "========================================\r\n");
$bt = 0;
$totdisc = 0;
for ($a = 0; $a < count($detail); $a++) {
    printer_write($ftext, str_pad($detail[$a]['Qty'], 5, " ", STR_PAD_LEFT) . ' '.
    		str_pad(substr($detail[$a]['NamaStruk'], 0, 30), 30) . "\r\n");
    if($detail[$a]['Keterangan']!='')
   		printer_write($ftext,'          '. str_pad(substr($detail[$a]['Keterangan'], 0, 20), 20) . "\r\n");
        
}
printer_write($ftext, "----------------------------------------\r\n");
printer_write($ftext, "\r\n");
printer_write($ftext, $fcut);
printer_close($ftext);
?>