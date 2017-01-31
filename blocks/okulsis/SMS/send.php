<?php
/**
 * Created by PhpStorm.
 * User: SUAL
 * Date: 09.11.2016
 * Time: 14:06
 */
global $CFG, $DB;
require_once(__DIR__ . "/locallib.php");
sepetbosalt();
$context = context_system::instance();
require_capability('block/okulsis:sendsms', $context);
$smslistesi = $DB->get_records('block_okulsis_sms_savebasket', array());
echo '  <div class="row-fluid page-head">
                    <h2 class="page-title heading-icon"  aria-hidden="true"><i class="fa fa-phone-square" aria-hidden="true"></i>
     SMS <small>Kısa Mesaj Gönderme Modülü</small></h2>
               </div>
               ';
echo '<div id="page-content" class="page-content ">
        <section>  
                 </br>

<div class="row-fluid">
<div class="span5 well well-nice bg-blue-light">
<h5 class="simple-header"><i class="fa fa-cube" aria-hidden="true"></i>&nbsp;&nbsp;Kurum & Bölüme Göre Sıralama<img id ="loadingkurum" class="hidden pull-right" src="' .
        $CFG->wwwroot . '/blocks/okulsis/pic/squares.svg"></h5>
        ' . kurumsec() . '
         <div id="bolumdoldur"></div>
             </br>
            
         <button id="btnlistele" class="btn btn-success btn-block hidden"><i class="fa fa-caret-square-o-down"></i>&nbsp;&nbsp;Listele</button>    
         </div>
       <div class="span7 well well-nice bg-blue-light" >
         <h5 class="simple-header"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp;&nbsp;Gelişmiş Filtreleme <img id ="loading" class="hidden pull-right" src="' .
        $CFG->wwwroot . '/blocks/okulsis/pic/squares.svg"></h5>
         <div class="row-fluid">
         <div class="span4">' . kurssec() . '
              <div id="altbolumdoldur"></div>   
              <div id="sinavdoldur"></div></div>
     <div class="span8">
     <input  class="radio" type="radio" name="rdnfiltre" value="1" checked >Sınava Girmeyen Öğrenci </label></br>
     <input  class="radio" type="radio" name="rdnfiltre" value="2">Sınav Puanına Göre </label></br>
     <input  class="radio" type="radio" name="rdnfiltre" value="3">Alt Bölüm Not Ortalamasına Göre </label></br>
     <input  class="radio" type="radio" name="rdnfiltre" value="4">Alt Bölüm Notlarına Göre</label>    
         </div>
         </div>
               <div id ="notbaremi" class="row-fluid hidden"><div class="span12" id="slider"></div></div>
               <div class="span11 notyfy-block filtre"></div>
               <button id="btngelismislistele" class="btn btn-success btn-block"><i class="fa fa-caret-square-o-down"></i>&nbsp;&nbsp;Listele</button>    
          </div>                 
        </div>
                       <div class="row-fluid">
                       <div class="span8 well well-nice bg-gray-light" >
                       <h5 class="simple-header"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp;&nbsp;Filtrelenmiş Üyeler</h5>
                       <div id="containerlistele">' . okulsis_mesajyaz('warning', 'Lütfen Üyeleri Filtreleyiniz') . '</div>
                       </div>
                        <div class="span4 well well-nice well-small">
                        <div id="myContactTop-nav" class=""><i class="fa fa-address-card-o" aria-hidden="true"></i>&nbsp;&nbsp;SMS GÖNDERME LİSTESİ 
                        <div class="btn-toolbar ">
                         <div class="btn-group pull-left">
                        <a id="userekle" href="#" class="btn btn-succes "><i class="fa fa-user-plus" aria-hidden="true"></i></a>
                        
                        </div>
                        <div class="btn-group pull-right">
                        <a id="basketsil" href="#" class="btn btn-danger "><i class="fa fa-trash-o " aria-hidden="true"></i></a>
                        <a id="basketyenile" href="#" class="btn btn-info "><i class="fa fa-refresh" aria-hidden="true"></i></a>                    
                        <a id="basketkaydet" href="#" class="btn btn-success " ><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        </div>
                        </div>
                     </div>
                           <br><hr class="margin-sx">
                           <div id="basketlist"></div>
                           
                         </div>
                         <button id="sendsms" class="btn btn-success btn-block span4 pull-right">SMS GÖNDER&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                       </div>
                       
                           <!--modal sepet kayıt -->
                 <div id="modalsepetkaydet" class="modal hide fade" tabindex="-1" data-width="760" >              
                  <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                  <h4 style="color:#fff;">SMS LİSTESİ KAYIT EKRANI</h4>
                  </div>
                  <div class="modal-body" id="" >
                     <div class="modal-body">
                      <div class="page-header">
                    <p>Bu ekranda SMS listenizi kayıt edebilir.Varolan kayıtlarını silebilirsiniz ve Kayıtlı Sms listenizi geri yükleyebilirsiniz</p>
                       </div>
                        <fieldset>
                        <label>Liste İsmi:</label>
                            <input id="sepetadi" class="span3" style="height:27px;" type="text">
                            <span><a id="sepetadikaydet" href="#"><i class="fa fa-plus-square fa-2x " aria-hidden="true"></i></a></span>
                           <div class="row-fluid"><div class="span5 notyfy-block modalnotyfy"></div></div> 
                         <div id="scrollboxmodal"  style="height:300px;">
                         <div  class="table-wrapper">
                        
                                    <table class="table boo-table table-striped table-condensed table-content ">
                                        <colgroup>
                                            <col class="col5">
                                            <col class="col75">
                                            <col class="col20">
                                        </colgroup>
                                        <caption>
                                            KAYITLI SMS LİSTESİ <span></span>
                                        </caption>
                                        
                                        <tbody>
                                        
                                       ';
if ($smslistesi) {
    $html = '';
    $i = 0;
    foreach ($smslistesi as $log) {
        $html .= '<tr>';
        $html .= '<td class="bold">' . ++$i . '</td>';
        $html .= '<td>' . $log->name . '</td>';
        $html .= '<td><button  id="' . $log->id .
                '" class="kayitlismsyukle btn btn-success pull-left"><i class="fa fa-download" aria-hidden="true"></i></button><button id="' .
                $log->id .
                '" class="kayitlismslistsil btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
        $html .= '<tr>';
    }
    echo($html);
} else {
    echo '<tr><td><div class="alert-info"><i class="fa fa-info-circle pull-left fa-2x" aria-hidden="true"></i>Kayıtlı Sms Listesi Bulunamadı</div></td></tr>';
}
echo ' </tbody>
                                   </table>
                                   </div>
                                    <!-- // Table -->
                                </div>
                        </fieldset>
                      </div>
                      </div>
                  <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Kapat</button>
                </div>
                   
                 </div><!--modal bitti-->
                 <!--usereklemodal başlangıç-->
                 <div id="modaluserekle" class="modal hide fade" tabindex="-1" data-width="50%" >
                 <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                <h4 style="color:#fff;">SMS LİSTESİNE TEK KİŞİ EKLEME</h4>
            </div>
             <div class="modal-body">
            <div class="page-header">
                    <p>Bu ekranda SMS listenize  Telefon numarası ekleyebilirsiniz. Genellikle Sistemde kayıdı olmayan kullanıcılar içindir. </p>
                       </div>
                       <fieldset>
                        <label>Telefon:</label>
                            <input id="telno" class="input-medium"  type="tel"><span class="help-inline">Telefon numarasını yazınız </span>
                             </fieldset>
                              <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Kapat</button>
                <button type="button" id="btn_telkaydet" class="btn btn-success">Kaydet</button>
                </div>
                 </div>
                 </div>
                 <!--usereklemodal bitiş-->
                 <!--modalsms başlangıç-->
             <div id="modalsendsms" class="modal hide fade" tabindex="-1" data-width="80%" >  
               <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                <h4 style="color:#fff;">SMS GÖNDERME SAYFASI</h4>
            </div>
            <div class="modal-body">
            <div class="page-header">
            <div class="row-fluid">
            <div class="span8">
             <p>Bu ekran SMS Gönderme ekranıdır.</p>
            </div>
             <div class="span4">' . okulsis_bakiye() . '</div>
             </div>
             </div>
                 <div class="row-fluid">
                 <div class="span6 well well-nice bg-blue-light">
                 <h5 class="simple-header"><i class="fa fa-commenting" aria-hidden="true"></i>&nbsp;&nbsp;Mesaj Yazma Alanı</h5>
                 <label>Mesaj Başlangıç Şablonu Seç:</label>
                 ' . okulsis_sablonlar('ilk') . '
                 <label>Mesaj:</label>
                 <textarea id ="elasticTextarea" class="span6 auto" rows="3" ></textarea>
                 <span class="help-block">Karakter Sayısı:&nbsp;<span id="say">0</span></span>
                 <label>Mesaj Bitiş Şablonu Seç:</label>
                 ' . okulsis_sablonlar('son') . '
                 <label class="checkbox">
                  <div class="checker"><span class=""><input class="checkbox" type="checkbox" name="veliadi"></span></div>
                   Mesaj başında Veli adi olsun mu?
                   </label>
                   
                 </div>
                 <div class="span6 well well-nice bg-yellow-light">
                 <h5 class="simple-header"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;&nbsp;Mesaj Gönderim Seçenekleri</h5>
                  <label>Mesaj Başlığı Seç:</label>
                  ' . okulsis_getmsgheader() . '</br>
                      <label class="radio"><input  class="radio" type="radio" name="smsdate" value="now" style="margin-top:1px;" checked>Hemen Gönderilsin</label>
                      <label class="radio"><input  class="radio" type="radio" name="smsdate" value="future" style="margin-top:1px;">İleri Tarihli Gönderilsin </label>
                         <div id="smstarihgizle" class="input-append date hidden">
                            <input id="datepickershow" type="text" style="" readonly>
                             <span  id="datepicker" class="add-on"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          </div>
                 
                 </div>
                 <button id="smspost" class="btn btn-succes btn-block span6 pull-right">SMS YOLLA&nbsp;&nbsp;<i class="fa fa-paper-plane" aria-hidden="true"></i></button> 
                 </div>      
            
            </div>
              <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger">Kapat</button>
                </div>
                 </div><!--modalsms bitiş-->
                 
                       </section>
                </div>
            ';
