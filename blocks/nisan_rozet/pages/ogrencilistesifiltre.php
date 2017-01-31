<?php
/**
 * Created by PhpStorm.
 * User: Çaglar
 * Date: 09.09.2016
 * Time: 23:32
 */
echo '  <div class="widget">
                <div class="widget-header">
                  <div class="title">
                    <span class="fs1" aria-hidden="true" ><i class="fa fa-filter fa-2x" aria-hidden="true"></i></span> ÖĞRENCi FİLTRELEME
                  </div>
                </div>
                <div class="widget-body">
 <ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#sinif" role="tab" data-toggle="tab">Sınıf Bazında</a></li>
  <li><a href="#not" role="tab" data-toggle="tab">Sınav Not Bazında</a></li>
  <li><a href="#ortalama" role="tab" data-toggle="tab">Bölüm Sınavları Ortalaması Bazında</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="sinif">
	'.block_nisan_rozet_selectmenu('sinifsec').'
		</div>
		<div class="tab-pane " id="not">
'.block_nisan_rozet_selectmenu('kurssec').'</br>
'.block_nisan_rozet_selectmenu('bolumsec').'</br>
'.block_nisan_rozet_selectmenu('sinavsec').'</br>
<input type="text" id="range_03" name="range_03" value="" /></br>
<div class="text-center"><button type="button" class="btn btn-default btn-large " id="listele_not" >Listele</button></div>
</div>
		
		<div class="tab-pane " id="ortalama">
		'.block_nisan_rozet_selectmenu('kurssec_ortalama').'</br>
		'.block_nisan_rozet_selectmenu('bolumsec_ortalama').'</br>
		<input type="text" id="range_04" name="range_04" value=""  /></br>
		<div class="text-center"><button type="button" class="btn btn-default btn-large " id="listele_ortalama" >Listele</button></div>
		</div>
		
		        
                </div>
                </div>
              </div>';