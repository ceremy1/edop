<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 08.09.2016
 * Time: 01:41
 */
global $USER;
if(isset($_REQUEST['reset']) and is_siteadmin()) {
    if($_REQUEST['onay'] == 'evet'){
    $md5 = md5(fullname($USER));
        if($md5 == $_REQUEST['md5'] ){
        echo(block_nisan_rozet_resetallplugin());
            $log = new mylog(-10);
            $log->tarih= time();
            $log->expose = $USER->id;
            $log->content = getpersonlink($USER->id).' Tüm Sistemi SIFIRLADI';
            $log->trigger();

        }else{
            echo(block_nisan_rozet_mesajyaz('danger','güvenlik parametresi hatalı'));
        }
    }else{
        echo(block_nisan_rozet_mesajyaz('warning','Doğru kelimeyi yazmadığınız için Sıfırlama işlemi Başarısız oldu'));
    }
}

echo '<p class="headline headline-v1"><i class="fa fa-cogs" aria-hidden="true"></i> EKLENTİ AYARLARI</p>';
//panel başlangıç
if (is_siteadmin()) {
    echo '<div class="panel panel-danger">
      <div class="panel-heading">
        <h3 class="panel-title">Eklenti Sıfırlama
      </div>
      <div class="panel-body">
      <div class="row-fluid">
      ' . block_nisan_rozet_mesajyaz('warning', 'Bu ayar genellikle sene başında kulanılır. Bu ayarı kullanırken çok dikkatli olmalısınız .
       Lütfen aşağıdaki maddeleri okuduktan sonra onaylayın', null) . '
     <ul class="list-style-3 colored">
     <li class="text-danger">Bu ayar Bütün Nişan atamalarını silecek</li>
     <li class="text-danger">Bu ayar Bütün Rozet Kazanımlarını silecek</li>
     <li class="text-danger">Bu ayar Bütün Kriter atamalarını silecek</li>
     <li class="text-danger">Bu ayar Bütün Duyuruları silecek</li>
     <li class="text-danger">Bu ayar Bütün Log Kayıtlarını silecek</li>
     <li class="text-info"  >Eklediğiniz Nişanlar Silinmeyecek</li>
     <li class="text-info"  >Eklediğiniz Rozetler Silinmeyecek</li>
     </ul>
     </div>
     <div class="row-fluid text-center">
     <a  href="#reset" class="btn btn-success" data-toggle="modal">Okudum Anladım</a>
     </div>
     <!-- modal başlangıç -->
     <div id="reset" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Eklenti Sıfırlamayı Onaylayın</h3>
  </div>
  <div class="modal-body">
    <p>Resetleme İşlemini Onaylıyorsanız aşağıdaki kutucuğa evet yazın</p>
    <form action="" method="post" name="resetform">
    <input type="text" name="onay" />
    <input type="hidden" name="md5" value ="'.md5(fullname($USER)).'" />
    </div>
    <div class="modal-footer">
    <input type="button" class="btn btn-danger" data-dismiss="modal" aria-hidden="true" value="Kapat">
    <input type="submit" name="reset" value="Sıfırla" />
    </form>
  </div>
</div>
     <!-- modal bitiş -->
     </div>
     </div>
     ';
}
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
     </div>
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
        echo(block_nisan_rozet_mesajyaz('danger','Nişan Seçimi  Yapmadınız Eğer zaten Nişan atamalarını silmek istiyorsanız Bu uyarıyı Dikkate Almayın (Tüm Nişan yetkileri Silindi)','uyarimesaj',false));
        $N = null;
    } else{
        $N=count($nisanlar);
        echo (block_nisan_rozet_nisanyetkidbkayit($N,$nisanlar,$ogretmen_id));
    }



}
