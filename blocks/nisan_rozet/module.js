/**
 * Created by Çaglar on 05.09.2016.
 */
M.block_nisan_rozet = {};
M.block_nisan_rozet.init = function(Y,param1,param2) {
    window.onbeforeunload = null;
    var img = Y.all('#load');
    var kurssec =Y.one("#kurssec");
    var bolumsec =Y.one("#bolumsec");
    var sinavsec =Y.one("#sinavsec");
    var kurssec_ortalama =Y.one("#kurssec_ortalama");
    var bolumsec_ortalama =Y.one("#bolumsec_ortalama");
    var success = Y.all('#uyarimesaj');
    //var rozetselect  = Y.one("#rozetbotselect");
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
    if(Y.one('#notificationbar')) {
        $(document).ready(function () {
            var $container = $("#notificationbar");
            $container.load("pages/notificationbar.php");
            var notificationbar = setInterval(function () {
                $container.load('pages/notificationbar.php');
            }, 9000);
        });
    }
    if (param1 == 1 && param2 == 'ayarlar'){
    Y.one("#ogretmen_sec").on('change', function () {
        if (success != null) {
            success.hide();
        }
        var ogretmen_id =Y.one("#ogretmen_sec").get('value');
        Y.io('adminpages/yetkilistele.php?id=' + ogretmen_id + '&filtre=nisan', {
            on: {
                start: function (id, args) {
                },
                complete: function (id, e) {
                    var json = e.responseText;
                    Y.one("#nisandoldur").set('innerHTML', json)

                }
            }
        });


    });
        
    }
    if (param1 == 1 && param2 == 'Anasayfa'){

            $(document).ready(function(){

                var $container = $("#livecontent");
                var $container1 = $("#liverozetbot");
                    $container.load("adminpages/live.php?filtre=site");
                    $container1.load("adminpages/live.php?filtre=tumloglar");
                var setintervalsite = setInterval(function()
                {
                    $container.load('adminpages/live.php?filtre=site');
                }, 9000);
                var setintervalid = setInterval(function()
                {
                    $container1.load('adminpages/live.php?filtre=tumloglar');
                }, 9000);
                function mytimer()
                {
                    clearInterval(setintervalid);
                    var filtre = $("#rozetbotselect").val();
                    $container1.load('adminpages/live.php?filtre='+filtre);
                }
                $("#rozetbotselect").change(mytimer);
                setInterval(mytimer, 9000);

            });

    }
    if ( param2 == 'nisanatama'){
        img.hide();
        Y.one("#nisanselect").on('change', function () {
            if (success != null) {
                success.hide();
            }
            var nisanid = Y.one("#nisanselect").get('value');
            Y.io('pages/listele.php?nisanid='+nisanid+'&filtre=nisancontent',{
             on:{
                 complete:function(id,e){
                     var json = e.responseText;
                     Y.one("#selectnisancontent").set('innerHTML', json) 
                 }
             }


            });
        });
        Y.one("#sinifselect").on('change', function () {
            if (success != null) {
                success.hide();
            }
            var sinifid = Y.one("#sinifselect").get('value');
            Y.io('pages/listele.php?sinifid='+sinifid+'&filtre=sinif',{
                on:{
                    start: function(id, args) {
                        img.show();
                    },
                    complete:function(id,e){
                        img.hide();
                        var json = e.responseText;
                        Y.one("#ogrencilistesi").set('innerHTML', json)
                        $('#ogrlistesi').DataTable({
                            responsive: false,
                            select: false,
                            colReorder: true,
                            dom: 'lfrtip',
                            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Hepsi"]],
                            pageLength: -1,
                            "language": {
                                "sProcessing":   "İşleniyor...",
                                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                                "sInfoEmpty":    "Kayıt Yok",
                                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                                "sInfoPostFix":  "",
                                "sSearch":       "Bul:",
                                "sUrl":          "",
                                "oPaginate": {
                                    "sFirst":    "İlk",
                                    "sPrevious": "Önceki",
                                    "sNext":     "Sonraki",
                                    "sLast":     "Son"
                                },
                                "aria": {
                                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                                },
                                "loadingRecords": "Yükleniyor..."
                            }
                        });
                        if( Y.one('#selectall')) {
                            Y.one('#selectall').on('click', function (e) {

                                if (e.target.get('checked')) {
                                    Y.all('.usercheckbox').set('checked', 'checked');
                                } else {
                                    Y.all('.usercheckbox').set('checked', '');
                                }
                            });
                        }
                    }
                }


            });
        });
        kurssec.on('change',function () {
            if (success != null) {
                success.hide();
            }
            var kursid=kurssec.get('value');
            if (kursid != -1 && kursid != -2){
                Y.io('pages/ajaxdata.php?id='+kursid+'&filtre=kurs',{
                    on: {
                        complete: function (id, e) {
                            var json = e.responseText;
                            bolumsec.set('innerHTML', json);
                            sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                            Y.one("#ogrencilistesi").set('innerHTML',' ');
                           // sms_send.hide();

                        }
                    }
                });
            }else{
                bolumsec.set('innerHTML', '<option value="-1">Bölüm Seçiniz</option>');
                sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                Y.one("#ogrencilistesi").set('innerHTML',' ');
                //sms_send.hide();
            }


        });
        bolumsec.on('change',function () {
            if (success != null) {
                success.hide();
            }
            var bolumid = bolumsec.get('value');
            if (bolumid != -1 && bolumid != -2){
                Y.io('pages/ajaxdata.php?id='+bolumid+'&filtre=bolum',{
                    on: {
                        complete: function (id, e) {
                            var json = e.responseText;
                            sinavsec.set('innerHTML', json);
                            Y.one("#ogrencilistesi").set('innerHTML',' ');
                           // sms_send.hide();

                        }
                    }
                });
            }else{
                sinavsec.set('innerHTML', '<option value="-1">Sınav Seçiniz</option>');
                Y.one("#ogrencilistesi").set('innerHTML',' ');
               // sms_send.hide();

            }
        });
        sinavsec.on('change',function() {
            if (success != null) {
                success.hide();
            }
            Y.one("#ogrencilistesi").set('innerHTML',' ');
                       });
        kurssec_ortalama.on('change',function () {
            if (success != null) {
                success.hide();
            }
            var kursid=kurssec_ortalama.get('value');
            if (kursid != -1 && kursid != -2){
                Y.io('pages/ajaxdata.php?id='+kursid+'&filtre=kurs',{
                    on: {
                       
                        complete: function (id, e) {
                            var json = e.responseText;
                            bolumsec_ortalama.set('innerHTML', json);
                            Y.one("#ogrencilistesi").set('innerHTML',' ');
                           // sms_send.hide();
                        }
                    }
                });
            }else{
                bolumsec_ortalama.set('innerHTML', '<option value="-1">Bölüm Seçiniz</option>');
                Y.one("#ogrencilistesi").set('innerHTML',' ');
               // sms_send.hide();

            }
        });
        bolumsec_ortalama.on('change',function () {
            if (success != null) {
                success.hide();
            }
            Y.one("#ogrencilistesi").set('innerHTML','');
        });
        Y.one('#listele_not').on('click',function() {
            if (success != null) {
                success.hide();
            }
            var  from = $("#range_03").data("from");
            var  to = $("#range_03").data("to");
            var qid = sinavsec.get('value');
            Y.io('pages/listele.php?qid='+qid+'&from='+from+'&to='+to+'&filtre=not',
                {
                    on: {
                        start: function (id, args) {
                          img.show();
                        },
                        complete: function (id, e) {
                            img.hide();
                            var json = e.responseText;
                            console.log(json);
                            Y.one("#ogrencilistesi").set('innerHTML', json);
                            $('#ogrlistesi').DataTable({
                                responsive: false,
                                select: false,
                                colReorder: true,
                                dom: 'lfrtip',
                                lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Hepsi"]],
                                pageLength: -1,
                                "language": {
                                    "sProcessing":   "İşleniyor...",
                                    "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                                    "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                                    "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                                    "sInfoEmpty":    "Kayıt Yok",
                                    "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                                    "sInfoPostFix":  "",
                                    "sSearch":       "Bul:",
                                    "sUrl":          "",
                                    "oPaginate": {
                                        "sFirst":    "İlk",
                                        "sPrevious": "Önceki",
                                        "sNext":     "Sonraki",
                                        "sLast":     "Son"
                                    },
                                    "aria": {
                                        "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                                        "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                                    },
                                    "loadingRecords": "Yükleniyor..."
                                }
                            });
                             if( Y.one('#selectall')) {
                                 Y.one('#selectall').on('click', function (e) {

                                     if (e.target.get('checked')) {
                                         Y.all('.usercheckbox').set('checked', 'checked');
                                     } else {
                                         Y.all('.usercheckbox').set('checked', '');
                                     }
                                 });
                             }
                        }
                    }
                });
        });
        Y.one('#listele_ortalama').on('click',function() {
            if (success != null) {
                success.hide();
            }
            var  from = $("#range_04").data("from");
            var  to = $("#range_04").data("to");
            var bid = bolumsec_ortalama.get('value');
            Y.io('pages/listele.php?bid='+bid+'&from='+from+'&to='+to+'&filtre=ortalama',
                {
                    on: {
                        start: function (id, args) {
                            img.show();
                        },
                        complete: function (id, e) {
                            img.hide();
                            var json = e.responseText;
                            console.log(json);
                            Y.one("#ogrencilistesi").set('innerHTML', json);
                            $('#ogrlistesi').DataTable({
                                responsive: false,
                                select: false,
                                colReorder: true,
                                dom: 'lfrtip',
                                lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Hepsi"]],
                                pageLength: -1,
                                "language": {
                                    "sProcessing":   "İşleniyor...",
                                    "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                                    "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                                    "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                                    "sInfoEmpty":    "Kayıt Yok",
                                    "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                                    "sInfoPostFix":  "",
                                    "sSearch":       "Bul:",
                                    "sUrl":          "",
                                    "oPaginate": {
                                        "sFirst":    "İlk",
                                        "sPrevious": "Önceki",
                                        "sNext":     "Sonraki",
                                        "sLast":     "Son"
                                    },
                                    "aria": {
                                        "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                                        "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                                    },
                                    "loadingRecords": "Yükleniyor..."
                                }
                            });
                            if( Y.one('#selectall')) {
                                Y.one('#selectall').on('click', function (e) {

                                    if (e.target.get('checked')) {
                                        Y.all('.usercheckbox').set('checked', 'checked');
                                    } else {
                                        Y.all('.usercheckbox').set('checked', '');
                                    }
                                });
                            }
                        }
                    }
                });
        });
      
    }
    if (param2 == 'nisanyonetim'){
        if(Y.one("#nisanselect")) {
            var nisanid = Y.one("#nisanselect").get('value');
            Y.io('pages/listele.php?nisanid=' + nisanid + '&filtre=nisancontent', {
                on: {
                    complete: function (id, e) {
                        var json = e.responseText;
                        Y.one("#selectnisancontent").set('innerHTML', json)
                    }
                }


            });
            Y.one("#nisanselect").on('change', function () {
                if (success != null) {
                    success.hide();
                }
                var nisanid = Y.one("#nisanselect").get('value');
                Y.io('pages/listele.php?nisanid=' + nisanid + '&filtre=nisancontent', {
                    on: {
                        complete: function (id, e) {
                            var json = e.responseText;
                            Y.one("#selectnisancontent").set('innerHTML', json)
                        }
                    }


                });
            });

        }
        $(document).ready(function(){
            $('#atanmisnisanlistesi').DataTable({
                responsive: false,
                select: false,
                colReorder: true,
                dom: "Blfrtip",
                lengthMenu: [[50, 100, 200, -1], [50, 100, 200, "Hepsi"]],
                pageLength: 100,
                buttons: [
                    'colvis', {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }

                ],

                "language": {
                    "sProcessing":   "İşleniyor...",
                    "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                    "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                    "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                    "sInfoEmpty":    "Kayıt Yok",
                    "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Bul:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst":    "İlk",
                        "sPrevious": "Önceki",
                        "sNext":     "Sonraki",
                        "sLast":     "Son"
                    },
                    "aria": {
                        "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                        "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                    },
                    "loadingRecords": "Yükleniyor...",
                    buttons: {
                        print: 'YAZDIR',
                        colvis: 'Sutun Görünürlüğü'
                    }

                }



            });


        });
    }
    if (param1 == 3){
        $('#sahipnisanlistesi').DataTable({
            responsive: false,
            select: false,
            colReorder: true,
            dom: "B<'pull-right'l>frtip",
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Hepsi"]],
            pageLength: 50,
            buttons: [
                'colvis', {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ],
            "language": {
                "sProcessing":   "İşleniyor...",
                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                "sInfoEmpty":    "Kayıt Yok",
                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                "sInfoPostFix":  "",
                "sSearch":       "Bul:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sPrevious": "Önceki",
                    "sNext":     "Sonraki",
                    "sLast":     "Son"
                },
                "aria": {
                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                },
                "loadingRecords": "Yükleniyor...",
                buttons: {
                    print: 'YAZDIR',
                    colvis: 'Sutun Görünürlüğü'
                }
            }

        });


    }
    if (param2 == 'log'){
        $('#loglistesi').DataTable({
            order : [[ 0, "desc" ]],
            responsive: false,
            select: false,
            colReorder: true,
            dom: "Blfrtip",
            lengthMenu: [[100, 1000, 10000, -1], [100, 1000, 10000, "Hepsi"]],
            pageLength: 100,
            buttons: [
                'colvis', {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
               
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ],
            "language": {
                "sProcessing":   "İşleniyor...",
                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                "sInfoEmpty":    "Kayıt Yok",
                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                "sInfoPostFix":  "",
                "sSearch":       "Bul:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sPrevious": "Önceki",
                    "sNext":     "Sonraki",
                    "sLast":     "Son"
                },
                "aria": {
                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                },
                "loadingRecords": "Yükleniyor...",
                buttons: {
                    print: 'YAZDIR',
                    colvis: 'Sutun Görünürlüğü'
                }
            }

        });
    }
    if (param2 == 'rapor'){
        $('#raporlar').DataTable({
            order : [[ 0, "desc" ]],
            responsive: false,
            select: false,
            colReorder: true,
            dom: "Blfrtip",
            lengthMenu: [[100, 1000, 10000, -1], [100, 1000, 10000, "Hepsi"]],
            pageLength: 100,
            buttons: [
                'colvis', {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ],
            "language": {
                "sProcessing":   "İşleniyor...",
                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                "sInfoEmpty":    "Kayıt Yok",
                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                "sInfoPostFix":  "",
                "sSearch":       "Bul:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sPrevious": "Önceki",
                    "sNext":     "Sonraki",
                    "sLast":     "Son"
                },
                "aria": {
                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                },
                "loadingRecords": "Yükleniyor...",
                buttons: {
                    print: 'YAZDIR',
                    colvis: 'Sutun Görünürlüğü'
                }
            }

        });
    }
    if(param2 == 'rozetyonetim'){
        $('#rozetlistesi').DataTable({
            order : [[ 0, "desc" ]],
            responsive: false,
            select: false,
            colReorder: true,
            dom: "Blfrtip",
            lengthMenu: [[100, 1000, 10000, -1], [100, 1000, 10000, "Hepsi"]],
            pageLength: 100,
            buttons: [
                'colvis', {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ],
            "language": {
                "sProcessing":   "İşleniyor...",
                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                "sInfoEmpty":    "Kayıt Yok",
                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                "sInfoPostFix":  "",
                "sSearch":       "Bul:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sPrevious": "Önceki",
                    "sNext":     "Sonraki",
                    "sLast":     "Son"
                },
                "aria": {
                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                },
                "loadingRecords": "Yükleniyor...",
                buttons: {
                    print: 'YAZDIR',
                    colvis: 'Sutun Görünürlüğü'
                }
            }

        });

    }
    if (param2 == 'rozetlistesi'){
        $('#rozetlistesi').DataTable({
            responsive: false,
            select: false,
            colReorder: true,
            dom: "Blfrtip",
            lengthMenu: [[50, 100, 200, -1], [50, 100, 200, "Hepsi"]],
            pageLength: 100,
            buttons: [
                'colvis', {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ],

            "language": {
                "sProcessing":   "İşleniyor...",
                "sLengthMenu":   "Sayfada _MENU_ Kayıt Göster",
                "sZeroRecords":  "Eşleşen Kayıt Bulunmadı",
                "sInfo":         "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                "sInfoEmpty":    "Kayıt Yok",
                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                "sInfoPostFix":  "",
                "sSearch":       "Bul:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst":    "İlk",
                    "sPrevious": "Önceki",
                    "sNext":     "Sonraki",
                    "sLast":     "Son"
                },
                "aria": {
                    "sortAscending":  ": Artan Sütuna göre sıralama etkinleştirildi",
                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                },
                "loadingRecords": "Yükleniyor...",
                buttons: {
                    print: 'YAZDIR',
                    colvis: 'Sutun Görünürlüğü'
                }

            }



        });


    }
    };
    
    
    

