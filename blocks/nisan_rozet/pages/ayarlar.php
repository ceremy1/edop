<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 08.09.2016
 * Time: 01:41
 */
echo '<p class="headline headline-v1"><i class="fa fa-cogs" aria-hidden="true"></i> EKLENTİ AYARLARI</p>';
//panel başlangıç
echo'<div class="panel panel-warning">
      <div class="panel-heading">
        <h3 class="panel-title">Öğretmene Nişan Atama Yetkisi 
      </div>
      <div class="panel-body">
      
     <div class="row-fluid">
     <div class="span4">';
//öğretmen seçim select
echo '<select id="ogretmen_sec">
      '.block_nisan_rozet_ogretmensec().'
</select>';
echo '</div>
     <div class="span8">
     <form action=""  method="post" name="yetkiform" id="yetkiformid">
     <div id="nisandoldur"></div>
</div>
    </div>
     <div class="row-fluid">
     <input type="hidden" name="viewpage" id="viewpage" value="'.$viewpage.'"/>
     <input id="btn_yetkikaydet" type="submit" name="submit" class="btn btn-success pull-right" value="Kaydet"/>
     </form>
     </div>
     ';
if(isset($_REQUEST['submit'])) {
    if(isset($_REQUEST['ogretmen_id'])){
        global $DB;
        $ogretmen_id = $_REQUEST['ogretmen_id'];
        $DB->delete_records('block_nisan_rozet_settings',array('ogretmen_id'=>$ogretmen_id,'yetki_tur'=>1));
    }else{
        $ogretmen_id="";
    }
    if(isset($_REQUEST['nisanlar'])){
        $nisanlar = $_REQUEST['nisanlar']; // dersler alındı
    }else{
        $nisanlar="";
    }
    if(empty( $ogretmen_id)){
        echo(block_nisan_rozet_mesajyaz('danger','Lütfen Öğretmen Seçiniz','uyarimesaj',false));
    }
    if(empty($nisanlar)) {
        echo(block_nisan_rozet_mesajyaz('danger','Ders İşaretlemesi Yapmadınız Eğer zaten Ders atamalarını silmek istiyorsanız Bu uyarıyı Dikkate Almayın (Tüm Nişan yetkileri Silindi)','uyarimesaj',false));
        $N = null;
    } else{
        $N=count($nisanlar);
    }
  echo (block_nisan_rozet_nisanyetkidbkayit($N,$nisanlar,$ogretmen_id));


}
