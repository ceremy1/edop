// JavaScript Document

M.block_sms = {};
M.block_sms.init = function(Y,param3) {

window.onbeforeunload = null;
    // değişkenleri çekelim
    var showuser= Y.one("#btnajax");
    var sms_send = Y.all('#smssend');
    var action = Y.one('#id_r_id');
    var action1 = Y.one('#id_m_id');
    var action2 = Y.one('#id_c_id');
	var action3 = Y.one('#id_header_id');
    var action4 = Y.one('#id_q_id');
    var action5 = Y.one('#id_ders_id');
    var dateday = Y.one('#id_gettarih_day');
    var datemount = Y.one('#id_gettarih_month');
    var dateyear = Y.one('#id_gettarih_year');
    var userlist= Y.one("#table-change");
    var img=Y.all('#load');
    var success = Y.all('#msgsuccess');
    var action6 = Y.one('#id_mesajlistele');
    var adminlistiner =Y.one("#admin_listener");
    var showbutton = Y.all("#showbutton");
    var kurssec =Y.one("#kurssec");
    var bolumsec =Y.one("#bolumsec");
    var sinavsec =Y.one("#sinavsec");
    var kurssec_not =Y.one("#kurssec_not");
    var bolumsec_not =Y.one("#bolumsec_not");
    var sinavsec_not =Y.one("#sinavsec_not");
    var listele_not=Y.one("#listele_not");
    var kurssec_ortalama =Y.one("#kurssec_ortalama");
    var bolumsec_ortalama =Y.one("#bolumsec_ortalama");
    var listele_ortalama=Y.one("#listele_ortalama");
    var ogretmen_sec= Y.one("#ogretmen_sec");
    var nisansec = Y.one("#nisansec");
    var rozetsec = Y.one("#rozetsec");
    $("#range_03").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0,
        max: 100,
        grid_num: 10,
        step: 5,
        grid_snap: true,
        keyboard: true,
        keyboard_step: 10,
        prefix: "Not: "

    });
    $("#range_04").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0,
        max: 100,
        grid_num: 10,
        step: 5,
        grid_snap: true,
        keyboard: true,
        keyboard_step: 10,
        prefix: "Not: "

    });
    /* $("#range_03").on("change", function () {
     var $this = $(this);
     var  from = $this.data("from");
     var  to = $this.data("to");


     });*/

    // sms gönderme sayfası
   /* if(param3 == 2 || param3 == 5 ) {


        /!*action.on('change', function() {
         var b=this.get('text');
         });*!/
        // mesaj şablonu seçmece
        action1.on('change', function() {
            var content = Y.one('#id_sms_body');
            var m_id=action1.get('value');
            Y.io('load_message.php?m_id='+m_id, {
                on: {
                    start: function(id, args) {
                        content.hide();
                        img.show();
                    },
                    complete: function(id, e) {
                        var json = e.responseText;
                        console.log(json);
                        img.hide();
                        content.show();
                        content.set('value', json);
                    }
                }
            });
        });
    }*/
    if (param3 == 5){
        // mesajı başlangıçta yüklesin
        var msg_body = Y.one('#id_sms_body');


        var m_id=action1.get('value');

        Y.io('load_message.php?m_id='+m_id, {
            on: {
                start: function(id, args) {
                    msg_body.hide();
                    img.show();

                },
                complete: function(id, e) {
                    var json = e.responseText;
                    console.log(json);
                    img.hide();
                    msg_body.show();
                    msg_body.set('value', json);
                }
            }
        });
        img.hide();
        sms_send.hide();

        action2.on('change',function() {
            var c_id = action2.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
                Y.io('class_list.php?class='+c_id+'&date='+date+'&ders='+ders+'&msg='+content+'&filtre=sinif', {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            if(c_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');

                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');


                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });



        });

        action4.on('change',function() {
            var q_id = action4.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&filtre=quiz',
                {
                on: {
                    start: function (id, args) {
                        userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                    },
                    complete: function (id, e) {
                        var json = e.responseText;
                        console.log(json);
                        userlist.set('innerHTML', json);
                        if (success != null) {
                            success.hide();
                        }
                        if(q_id == -1) {
                            sms_send.hide();
                            Y.one('#id_sms_body').set('disabled','');
                            Y.one('#id_gettarih_day').set('disabled','');
                            Y.one('#id_gettarih_month').set('disabled','');
                            Y.one('#id_gettarih_year').set('disabled','');
                            Y.one('#id_ders_id').set('disabled','');
                            Y.one('#id_m_id').set('disabled','');
                            Y.one('.visibleifjs').removeClass('hidden');
                        }else{
                            sms_send.show();
                            Y.one('#id_sms_body').set('disabled','disabled');
                            Y.one('#id_gettarih_day').set('disabled','disabled');
                            Y.one('#id_gettarih_month').set('disabled','disabled');
                            Y.one('#id_gettarih_year').set('disabled','disabled');
                            Y.one('#id_ders_id').set('disabled','disabled');
                            Y.one('#id_m_id').set('disabled','disabled');
                            Y.one('.visibleifjs').addClass('hidden');
                        }
                        Y.one('#selectall').on('click', function (e) {

                            if (e.target.get('checked')) {
                                Y.all('.usercheckbox').set('checked', 'checked');
                            } else {
                                Y.all('.usercheckbox').set('checked', '');
                            }
                        });

                    }
                }
            });



        });

  //Bölüme göre seçilim başlangıç

        kurssec.on('change',function () {
        var kursid=kurssec.get('value');
            if (kursid != -1 && kursid != -2){
         Y.io('ajaxdata.php?id='+kursid+'&filtre=kurs',{
             on: {
                 /*start: function (id, args) {
                     userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                 },*/
                 complete: function (id, e) {
                     var json = e.responseText;
                     console.log(json);
                     bolumsec.set('innerHTML', json);
                     sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                     userlist.set('innerHTML',' ');
                     sms_send.hide();
                     Y.one('#id_sms_body').set('disabled','');
                     Y.one('#id_gettarih_day').set('disabled','');
                     Y.one('#id_gettarih_month').set('disabled','');
                     Y.one('#id_gettarih_year').set('disabled','');
                     Y.one('#id_ders_id').set('disabled','');
                     Y.one('#id_m_id').set('disabled','');
                     Y.one('.visibleifjs').removeClass('hidden');
                 }
             }


         });
          }else{
                bolumsec.set('innerHTML', '<option value="-1">Bölüm Seçiniz</option>');
                sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                userlist.set('innerHTML',' ');
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');

            }


        });

        bolumsec.on('change',function () {
            var bolumid = bolumsec.get('value');
            if (bolumid != -1 && bolumid != -2){
                Y.io('ajaxdata.php?id='+bolumid+'&filtre=bolum',{
                    on: {
                        /*start: function (id, args) {
                         userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                         },*/
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            sinavsec.set('innerHTML', json);
                            userlist.set('innerHTML',' ');
                            sms_send.hide();
                            Y.one('#id_sms_body').set('disabled','');
                            Y.one('#id_gettarih_day').set('disabled','');
                            Y.one('#id_gettarih_month').set('disabled','');
                            Y.one('#id_gettarih_year').set('disabled','');
                            Y.one('#id_ders_id').set('disabled','');
                            Y.one('#id_m_id').set('disabled','');
                            Y.one('.visibleifjs').removeClass('hidden');
                        }
                    }


                });
            }else{
                sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                userlist.set('innerHTML',' ');
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');
            }


        });

        sinavsec.on('change',function() {
            var q_id = sinavsec.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&filtre=quiz',
                {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            if(q_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');
                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');
                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });



        });
//bölüm seç bitiş
        //not göre listeleme
        kurssec_not.on('change',function () {
            var kursid=kurssec_not.get('value');
            if (kursid != -1 && kursid != -2){
                Y.io('ajaxdata.php?id='+kursid+'&filtre=kurs',{
                    on: {
                        /*start: function (id, args) {
                         userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                         },*/
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            bolumsec_not.set('innerHTML', json);
                            sinavsec_not.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                            userlist.set('innerHTML',' ');
                            sms_send.hide();
                            Y.one('#id_sms_body').set('disabled','');
                            Y.one('#id_gettarih_day').set('disabled','');
                            Y.one('#id_gettarih_month').set('disabled','');
                            Y.one('#id_gettarih_year').set('disabled','');
                            Y.one('#id_ders_id').set('disabled','');
                            Y.one('#id_m_id').set('disabled','');
                            Y.one('.visibleifjs').removeClass('hidden');

                        }
                    }


                });
            }else{
                bolumsec_not.set('innerHTML', '<option value="-1">Bölüm Seçiniz</option>');
                sinavsec_not.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                userlist.set('innerHTML',' ');
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');

            }


        });

        bolumsec_not.on('change',function () {
            var bolumid = bolumsec_not.get('value');
            if (bolumid != -1 && bolumid != -2){
                Y.io('ajaxdata.php?id='+bolumid+'&filtre=bolum',{
                    on: {
                        /*start: function (id, args) {
                         userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                         },*/
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            sinavsec_not.set('innerHTML', json);
                            userlist.set('innerHTML',' ');
                            sms_send.hide();
                            Y.one('#id_sms_body').set('disabled','');
                            Y.one('#id_gettarih_day').set('disabled','');
                            Y.one('#id_gettarih_month').set('disabled','');
                            Y.one('#id_gettarih_year').set('disabled','');
                            Y.one('#id_ders_id').set('disabled','');
                            Y.one('#id_m_id').set('disabled','');
                            Y.one('.visibleifjs').removeClass('hidden');

                        }
                    }


                });
            }else{
                sinavsec_not.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                userlist.set('innerHTML',' ');
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');
            }


        });
        listele_not.on('click',function() {

            var  from = $("#range_03").data("from");
            var  to = $("#range_03").data("to");
            var q_id = sinavsec_not.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&from='+from+'&to='+to+'&filtre=quiz_not',
                {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            if(q_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');
                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');
                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });



        });
        sinavsec_not.on('change',function() {
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');
                userlist.set('innerHTML','');
            
        });
//ortalamaya göre seçilim başlangıç
        kurssec_ortalama.on('change',function () {
            var kursid=kurssec_ortalama.get('value');
            if (kursid != -1 && kursid != -2){
                Y.io('ajaxdata.php?id='+kursid+'&filtre=kurs',{
                    on: {
                        /*start: function (id, args) {
                         userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                         },*/
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            bolumsec_ortalama.set('innerHTML', json);
                            userlist.set('innerHTML',' ');
                            sms_send.hide();
                            Y.one('#id_sms_body').set('disabled','');
                            Y.one('#id_gettarih_day').set('disabled','');
                            Y.one('#id_gettarih_month').set('disabled','');
                            Y.one('#id_gettarih_year').set('disabled','');
                            Y.one('#id_ders_id').set('disabled','');
                            Y.one('#id_m_id').set('disabled','');
                            Y.one('.visibleifjs').removeClass('hidden');

                        }
                    }


                });
            }else{
                bolumsec_ortalama.set('innerHTML', '<option value="-1">Bölüm Seçiniz</option>');
                sinavsec_ortalama.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                userlist.set('innerHTML',' ');
                sms_send.hide();
                Y.one('#id_sms_body').set('disabled','');
                Y.one('#id_gettarih_day').set('disabled','');
                Y.one('#id_gettarih_month').set('disabled','');
                Y.one('#id_gettarih_year').set('disabled','');
                Y.one('#id_ders_id').set('disabled','');
                Y.one('#id_m_id').set('disabled','');
                Y.one('.visibleifjs').removeClass('hidden');

            }


        });
        bolumsec_ortalama.on('change',function () {
            sms_send.hide();
            Y.one('#id_sms_body').set('disabled','');
            Y.one('#id_gettarih_day').set('disabled','');
            Y.one('#id_gettarih_month').set('disabled','');
            Y.one('#id_gettarih_year').set('disabled','');
            Y.one('#id_ders_id').set('disabled','');
            Y.one('#id_m_id').set('disabled','');
            Y.one('.visibleifjs').removeClass('hidden');
            userlist.set('innerHTML','');
        });
        Y.one('#listele_ortalama').on('click',function() {

            var  from = $("#range_04").data("from");
            var  to = $("#range_04").data("to");
            var q_id = bolumsec_ortalama.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&from='+from+'&to='+to+'&filtre=ortalama',
                {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            if(q_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');
                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');
                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });



        });
//rozet
        nisansec.on('change',function() {
            var q_id = nisansec.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&filtre=nisan',
                {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            rozetsec.set('value',-1);
                            if(q_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');
                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');
                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });

        });
        rozetsec.on('change',function() {
            var q_id = rozetsec.get('value');
            //var c_text = Y.one("#id_c_id option:checked").get("text");
            //var h_id = action3.get('value');
            var d_day = dateday.get('value');
            var d_mount = datemount.get('value');
            var d_year = dateyear.get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ders = action5.get('value');
            var content = Y.one('#id_sms_body').get('value');
            Y.io('class_list.php?class='+q_id+'&date='+date+'&ders='+ders+'&msg='+content+'&filtre=rozet',
                {
                    on: {
                        start: function (id, args) {
                            userlist.set('innerHTML', '<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                        },
                        complete: function (id, e) {
                            var json = e.responseText;
                            console.log(json);
                            userlist.set('innerHTML', json);
                            if (success != null) {
                                success.hide();
                            }
                            nisansec.set('value',-1);
                            if(q_id == -1) {
                                sms_send.hide();
                                Y.one('#id_sms_body').set('disabled','');
                                Y.one('#id_gettarih_day').set('disabled','');
                                Y.one('#id_gettarih_month').set('disabled','');
                                Y.one('#id_gettarih_year').set('disabled','');
                                Y.one('#id_ders_id').set('disabled','');
                                Y.one('#id_m_id').set('disabled','');
                                Y.one('.visibleifjs').removeClass('hidden');
                            }else{
                                sms_send.show();
                                Y.one('#id_sms_body').set('disabled','disabled');
                                Y.one('#id_gettarih_day').set('disabled','disabled');
                                Y.one('#id_gettarih_month').set('disabled','disabled');
                                Y.one('#id_gettarih_year').set('disabled','disabled');
                                Y.one('#id_ders_id').set('disabled','disabled');
                                Y.one('#id_m_id').set('disabled','disabled');
                                Y.one('.visibleifjs').addClass('hidden');
                            }
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });

                        }
                    }
                });

        });
        //rozet bitiş

    }
    if (param3 == 6){

        img.hide();
        showbutton.hide();
        action6.on('click',function () {
            var d_day = Y.one('#id_admin_tarih_day').get('value');
            var d_mount = Y.one('#id_admin_tarih_month').get('value');
            var d_year = Y.one('#id_admin_tarih_year').get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var ilksablon =Y.one('#id_ilksablon').get('value');
            var sonsablon =Y.one('#id_sonsablon').get('value');
            var errormessage =Y.all('#erormessage');
            var header =action3.get('value');
            Y.io('admin_list.php?d_id='+date+'&ilk='+ilksablon+'&son='+sonsablon+'&header='+header, {
                on: {
                    start: function(id, args) {
                        
                        img.show();

                    },
                    complete: function(id, e) {
                        var json = e.responseText;
                        console.log(json);
                        img.hide();
                        errormessage.hide();
                        showbutton.show();
                       adminlistiner.set('innerHTML', json)
                        Y.one('#selectall').on('click', function (e) {

                            if (e.target.get('checked')) {
                                Y.all('.usercheckbox').set('checked', 'checked');
                            } else {
                                Y.all('.usercheckbox').set('checked', '');
                            }
                        });

                    }
                }
            });
            
        });

        // yonetim kullanıcı listele butonu tıklanınca
        Y.one('#btn_yonetimlistele').on('click',function () {
            var d_day = Y.one('#id_gettarih_day').get('value');
            var d_mount = Y.one('#id_gettarih_month').get('value');
            var d_year = Y.one('#id_gettarih_year').get('value');
            //var date =d_day+d_mount+d_year;
            var date = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var gonderen = Y.one('#id_gonderen').get('value');

            Y.io('admin_yonetim_list.php?date='+date+'&gonderen='+gonderen+'&filtre=yonetim', {
                on: {
                    start: function(id, args) {

                        img.show();

                    },
                    complete: function(id, e) {
                        var json = e.responseText;
                        console.log(json);
                        img.hide();
                         //showbutton.show();
                        Y.one("#listedoldur").set('innerHTML', json)

                    }
                }
            });


        });
        Y.one('#btn_yonetimlistelenetgsm').on('click',function () {
            var d_day = Y.one('#id_gettarihi_day').get('value');
            var d_mount = Y.one('#id_gettarihi_month').get('value');
            var d_year = Y.one('#id_gettarihi_year').get('value');
            var ilkdate = new Date(d_mount+"/"+d_day+"/"+d_year).getTime()/1000;
            var d_days = Y.one('#id_gettarihs_day').get('value');
            var d_mounts = Y.one('#id_gettarihs_month').get('value');
            var d_years = Y.one('#id_gettarihs_year').get('value');
            var sondate = new Date(d_mounts+"/"+d_days+"/"+d_years).getTime()/1000;

            Y.io('admin_yonetim_list.php?ilkdate='+ilkdate+'&sondate='+sondate+'&filtre=netgsm', {
                on: {
                    start: function(id, args) {

                        img.show();

                    },
                    complete: function(id, e) {
                        var json = e.responseText;
                        console.log(json);
                        img.hide();
                        //showbutton.show();
                        Y.one("#netgsmlistedoldur").set('innerHTML', json)

                    }
                }
            });


        });



    }
    if (param3 == 8) {
       
       ogretmen_sec.on('change', function () {
           if (success != null) {
               success.hide();
           }
            var ogretmen_id =ogretmen_sec.get('value');
            Y.io('admin_yonetim_yetki.php?id=' + ogretmen_id + '&filtre=ders', {
                on: {
                    start: function (id, args) {
                    },
                    complete: function (id, e) {
                        var json = e.responseText;
                        Y.one("#dersdoldur").set('innerHTML', json)

                    }
                }
            });


        });
        Y.one("#ogretmenheader_sec").on('change', function () {
            if (success != null) {
                success.hide();
            }
            var ogretmen_id = Y.one("#ogretmenheader_sec").get('value');
            Y.io('admin_yonetim_yetki.php?id=' + ogretmen_id + '&filtre=header', {
                on: {
                    start: function (id, args) {
                    },
                    complete: function (id, e) {
                        var json = e.responseText;
                        Y.one("#headerdoldur").set('innerHTML', json)

                    }
                }
            });


        });
    }


};

function onay() {
            var answer = confirm("Mesaj Kaydetmek istediğinize Emin misiniz ?")
               if (answer){
                  
                  return true;
               } else {
                 
                  return false;
               }
         };
function onay1() {
    var answer = confirm("Mesajı NetGsm ile göndermek istediğinize Emin misiniz ?")
    if (answer){

        return true;
    } else {

        return false;
    }
};
		 
 $(function(){
    var say = 0; // var olan değer
    $('textarea').bind('keydown keyup keypress change',function(){
        var thisValueLength = $(this).val().length;
        var saymax = (say)+(thisValueLength); // var olan değerin üzerine say
        $('#say').html(saymax);
 
        if(saymax > 130){ // karakter sayısı 130 tan fazla olursa kırmızı yaz
            $('#say').attr("class","label label-important ");
        } else { // karakter sayısı 130 tan az ise siyah yaz
            $('#say').attr("class","label label-info ");
        }
    });
    $(window).load(function(){
        $('.say').html(say); 
    });
});
 $('#rapor table').attr("class","table table-striped table-bordered");


