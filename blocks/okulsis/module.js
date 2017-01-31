/**
 * Created by Çaglar on 05.09.2016.
 */
M.block_okulsis = {};
M.block_okulsis.init = function (Y, param1, param2, param3) {
    window.onbeforeunload = null;
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };
    //menu için jquery
    $(document).ready(function () {
        var node = $("a[href='?module=" + param1 + "&viewpage=" + param2 + "']");
        node.parent().addClass('active');
        node.parents('ul').prev().addClass('active');
        node.parents('ul').addClass('in');

    });
    $(".nav-tabs a[data-toggle=tab]").on("click", function (e) {
        if ($(this).parent().hasClass("disabled")) {
            e.preventDefault();
            return false;
        }
    });
    $("input.checkbox, input.radio, input:file.input-file").uniform({
        radioClass: 'radios' // edited class - the original radio
    });
    if (param1 == 'sms' && param2 == 'send') {
        /*require(['jquery', 'jqueryui'], function ($, jqui) {

         });*/

        $("#datepicker").datetimepicker({
            format: 'd.m.Y H:i',
            formatTime: 'H:i',
            lang: 'tr',
            onChangeDateTime: function (dp, $input) {
                $('#datepickershow').val($input.val());

                //   var d= formatDate('dmYHi',);

            }

        });
        $.datetimepicker.setLocale('tr');

        $(function () {
            var say = 0; // var olan değer
            $('textarea').bind('keydown keyup keypress change', function () {
                var thisValueLength = $(this).val().length;
                var saymax = (say) + (thisValueLength); // var olan değerin üzerine say
                $('#say').html(saymax);

                if (saymax > 130) { // karakter sayısı 130 tan fazla olursa kırmızı yaz
                    $('#say').attr("class", "label label-important ");
                } else { // karakter sayısı 130 tan az ise siyah yaz
                    $('#say').attr("class", "label label-info ");
                }
            });
            $(window).on('load', function () {
                $('.say').html(say);
            });
        });
        function listele() {
            $("#basketlist").load('SMS/listele.php', function (responseText, statusText, xhr) {
                if (statusText == "success") {
                    $("#myContactWrapIn.scrollBox4").niceScroll({
                        cursoropacitymin: 0.01,
                        cursoropacitymax: 0.4,
                        cursorcolor: "#adafb5",
                        cursorwidth: "4px",
                        cursorborder: "",
                        cursorborderradius: "4px",
                        usetransition: 1000,
                        background: "",
                        railoffset: {top: 0, left: -4},
                        bouncescroll: true

                    });

                    $('#myContact li a, #myContactIn li a').click(function () {
                        $("a").parent().removeClass("active");
                        $(this).parent().addClass("active");
                    });
                    $('#myContactIn').listnav({
                        showCounts: true,
                        cookieName: null,
                        noMatchText: 'Bu filtrede Kayıt Bulunumadı',
                        includeOther: true,
                        onClick: function (letter) {
                            $("#myContactWrapIn.scrollBox4").getNiceScroll().resize();
                            $('#myContactIn li').addClass('animated fadeIn');
                        },
                        prefixes: []
                    });
                    $('.deleterow').on("click", function (e) {
                        var libox = $(this).parent().parent();
                        var id = $(this).attr("id");
                        $.ajax({
                            type: "POST",
                            url: "SMS/ajaxaktar.php",
                            data: {'filtre': 'sil', 'id': id},
                            async: true,
                            cache: false,
                            success: function () {
                                libox.slideUp('slow', function () {
                                    $(this).remove();
                                });
                                $("#myContactWrapIn.scrollBox4").getNiceScroll().resize();

                            }

                        });
                        e.preventDefault();
                        return false;
                    });

                }
            });

        }

        function listesil() {
            var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Siliniyor...</div>', '', {
                    "animate": false,
                    "onEscape": false
                }
            );
            $.ajax({
                url: 'SMS/ajaxaktar.php',
                type: "POST",
                dataType: 'HTML',
                async: true,
                data: {'filtre': 'sil'},
                beforeSend: function () {
                },
                success: function (cevap) {
                    if (cevap == 1) {

                        dialog.modal('hide');
                        bootbox.alert('<h5><img class="img-responsive" src="/blocks/okulsis/pic/questionhelp.png">&nbsp;&nbsp; Liste zaten Boş</h5>');
                    } else {
                        listele();

                        dialog.modal('hide');

                    }

                }
            });
        }

        listele();
        $('#basketsil').click(function (e) {
            e.preventDefault();
            listesil();
            return false;
        });
        $('#basketyenile').click(function (e) {
            e.preventDefault();
            var box = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Liste Yenileniyor...</div>', '', {
                    "animate": true,
                    "onEscape": false
                }
            );
            listele();
            setTimeout(function () {
                box.modal('hide');
            }, 2000);

            return false;
        });
        $('#basketkaydet').click(function (e) {
            e.preventDefault();
            $("#scrollboxmodal").niceScroll({
                cursoropacitymin: 0.01,
                cursoropacitymax: 0.4,
                cursorcolor: "#adafb5",
                cursorwidth: "4px",
                cursorborder: "",
                cursorborderradius: "4px",
                usetransition: 1000,
                background: "",
                railoffset: {top: 0, left: -4},
                bouncescroll: true

            });
            $.notyfy.clearQueue();
            $.notyfy.closeAll();
            $('#modalsepetkaydet').modal('toggle');
            $("#scrollboxmodal").getNiceScroll().resize();
            $('#modalsepetkaydet').on('hide', function () {
                $("#scrollboxmodal").getNiceScroll().hide();
            });

            return false;
        });
        $('#sepetadikaydet').click(function (e) {
            e.preventDefault();
            if ($("#sepetadi").val().trim().length > 0) {
                $.notyfy.clearQueue();
                $.notyfy.closeAll();
                var name = $("#sepetadi").val().trim();
                var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Kaydediliyor...</div>', '', {
                    "animate": false,
                    "onEscape": false
                });
                $.ajax({
                    url: 'SMS/ajaxsavelist.php',
                    type: "POST",
                    dataType: 'HTML',
                    async: true,
                    data: {'name': name},
                    success: function (cevap) {
                        dialog.modal('hide');
                        if (cevap == 1) {
                            $(".notyfy-block.modalnotyfy").notyfy({
                                text: "Zaten aynı isimde kayıt mevcut",
                                type: "error",
                                dismissQueue: true,
                                layout: "inline"

                            })
                        } else if (cevap == 2) {
                            $(".notyfy-block.modalnotyfy").notyfy({
                                text: "Kayıt edilecek liste Boş!",
                                type: "error",
                                dismissQueue: true,
                                layout: "inline"

                            })
                        } else if (cevap == 3) {
                            $(".notyfy-block.modalnotyfy").notyfy({
                                text: "Kayıt Başarısız!",
                                type: "error",
                                dismissQueue: true,
                                layout: "inline"

                            })
                        } else if (cevap == 4) {
                            $('#modalsepetkaydet').modal('hide');
                            bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/success.png"><h5>&nbsp;&nbsp;Kayıt Başarılı</h5></div>');
                            location.reload();
                        }


                    }

                });


            } else {
                $(".notyfy-block.modalnotyfy").notyfy({
                    text: "Lütfen Liste İsmi Giriniz",
                    type: "error",
                    dismissQueue: true,
                    layout: "inline"

                })
            }
            return false;
        });
        $('.kayitlismslistsil').on("click", function (e) {
            e.preventDefault();
            var trbox = $(this).parent().parent();
            var id = $(this).attr("id");

            $.ajax({
                type: "POST",
                url: "SMS/ajaxdellist.php",
                data: {'id': id},
                async: true,
                cache: false,
                success: function () {
                    trbox.slideUp('slow', function () {
                        $(this).remove();
                    });
                    $("#scrollboxmodal").getNiceScroll().resize();

                }

            });

            return false;
        })
        $('.kayitlismsyukle').on("click", function (e) {
            e.preventDefault();
            var box = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Liste Yükleniyor...</div>', '', {
                    "animate": true,
                    "onEscape": false
                }
            );
            var id = $(this).attr("id");

            $.ajax({
                type: "POST",
                url: "SMS/ajaxuploadlist.php",
                data: {'id': id},
                async: true,
                cache: false,
                success: function (cevap) {
                    listele();
                    $('#modalsepetkaydet').modal('hide');
                    box.modal('hide');
                    bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/success.png"><h5>&nbsp;&nbsp;Yükleme Başarılı</h5></div>');


                }

            });

            return false;
        });
        $('#userekle').on("click", function (e) {
            e.preventDefault();
            $('#modaluserekle').modal('toggle');
            return false;
        });
        $('#btn_telkaydet').click(function (e) {
            e.preventDefault();
            var telno = $('#telno').val();
            if (telno != "") {
                $.ajax({
                    type: "POST",
                    url: "SMS/ajaxtelnokaydet.php",
                    data: {'telno': telno},
                    dataType: 'HTML',
                    async: true,
                    success: function (cevap) {
                        if (cevap == 1) {
                            listele();
                            $('#modaluserekle').modal('hide');
                            bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/success.png"><h5>&nbsp;&nbsp;Sepete Ekleme Başarılı</h5></div>');
                        } else if (cevap == 2) {
                            listele();
                            $('#modaluserekle').modal('hide');
                            bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/fail.png"><h5>&nbsp;&nbsp;Aynı Telefon Numarası Zaten Var</h5></div>');
                        } else {
                            listele();
                            $('#modaluserekle').modal('hide');
                            bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/fail.png"><h5>&nbsp;&nbsp;Başarısız İşlem</h5></div>');
                        }

                    }

                });
            } else {
                bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/fail.png"><h5>&nbsp;&nbsp;Telefon Numarası Yazmadınız</h5></div>');

            }

            return false;
        });
        $('#sendsms').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: 'SMS/ajaxyetki.php',
                type: 'POST',
                dataType: 'HTML',
                data: {'filtre': 'yetkitesti'},
                success: function (cevap) {
                      if (cevap == 2) {
                        bootbox.alert('<div class="text-center"><img class="img-responsive" style="width:64px; height:64px;" src="/blocks/okulsis/pic/warning.png"><h5>&nbsp;&nbsp;SMS  Listesi Boş!</h5></div>');
                    } else if (cevap == 1) {
                        $('#modalsendsms').modal('toggle');
                        $('#elasticTextarea').elastic();
                        $('#elasticTextarea').trigger('update');
                        $('input[name=smsdate]').click(function () {
                            if ($(this).val() == 'now') {
                                $('#smstarihgizle').addClass('hidden');
                            } else {
                                $('#smstarihgizle').removeClass('hidden');
                            }
                        })
                    }
                }
            });
            return false;
        });
        $('#smspost').click(function () {

            var ilksablon = $('#ilksablon').val();
            var sonsablon = $('#sonsablon').val();
            var mesaj = $('#elasticTextarea').val();
            if ($("input[name='veliadi']").is(':checked')) {
                var veliadi = 'on';
            } else {
                var veliadi = 'off';
            }
            var msgheader = $('#msgheader').val();
            var tarih = $("input[name='smsdate']:checked").val();
            if (tarih == 'future') {
                if ($('#datepicker').datetimepicker('getValue') != null) {
                    var dd = $('#datepicker').datetimepicker('getValue');
                    var d = ("0" + dd.getDate()).slice(-2);
                    var m = ("0" + (dd.getMonth() + 1)).slice(-2);
                    var y = dd.getFullYear();
                    var H = ("0" + dd.getHours()).slice(-2);
                    //var M =dd.getMinutes()+"0";
                    var M = ("0" + dd.getMinutes()).slice(-2);
                    var datenetgsm = d + m + y + H + M;

                } else {
                    var datenetgsm = null;
                }
            } else {
                var datenetgsm = null;
            }
            $.ajax({
                url: 'SMS/smspost.php',
                type: 'GET',
                dataType: 'HTML',
                //data:{'filtre':'comfirm','ilksablon':ilksablon,'sonsablon':sonsablon,'mesaj':mesaj,'veliadi':veliadi,'msgheader':msgheader,'tarih':tarih,'datenetgsm':datenetgsm},
                data: {'filtre': 'confirm'},
                success: function (cevap) {
                    bootbox.confirm(cevap, "Hayır", "Evet", function (result) {
                        if (result) {
                            var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;SMS Gönderiliyor...</div>', '', {
                                    "animate": false,
                                    "onEscape": false
                                }
                            );
                            $.ajax({
                                url: 'SMS/smspost.php',
                                type: 'GET',
                                dataType: 'HTML',
                                data: {
                                    'filtre': 'post',
                                    'ilksablon': ilksablon,
                                    'sonsablon': sonsablon,
                                    'mesaj': mesaj,
                                    'veliadi': veliadi,
                                    'msgheader': msgheader,
                                    'tarih': tarih,
                                    'datenetgsm': datenetgsm
                                },
                                success: function (result) {
                                    $('#modalsendsms').modal('hide');
                                    dialog.modal('hide');
                                    bootbox.alert(result);
                                }
                            })
                        } else {
                            bootbox.hideAll();
                        }
                    });

                }

            })


        });
        //TODO:girişte listeleme olcakmı gerçekten 
        $("#kurumsec").select2();
        $("#kurssec").select2();
        $(document).ready(function () {
            $.ajaxSetup
            ({
                url: 'SMS/ajax.php',
                global: false,
                type: "POST",
                dataType: 'HTML'

            });
            $("#kurumsec").change(function () {
                var kurum = $("#kurumsec").val();
                if (kurum == -1) {
                    $("#btnlistele").addClass('hidden');
                }
                $.ajax({
                    data: {'filtre': 'bolum', 'kurum': kurum},
                    beforeSend: function () {
                        $("#loadingkurum").removeClass('hidden');
                    },
                    success: function (cevap) {
                        $("#loadingkurum").addClass('hidden');
                        $("#bolumdoldur").html(cevap);
                        $("#bolumsec").select2();
                        $("#bolumsec").change(function () {
                            var bolum = $("#bolumsec").val();
                            if (bolum != -1) {
                                $("#btnlistele").removeClass('hidden');
                            } else {
                                $("#btnlistele").addClass('hidden');
                            }

                        })
                        $("#btnlistele").click(function () {
                            var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Listeleniyor...</div>', '', {
                                    "animate": false,
                                    "onEscape": false
                                }
                            );
                            $("#containerlistele").html('');
                            var sinifdata = $("#bolumsec").val();
                            var kurum = $("#kurumsec").val();
                            $.ajax({
                                data: {'filtre': 'sinif', 'sinifdata': sinifdata, 'kurum': kurum},
                                //beforeSend :function() {$("#btnlistele").html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;&nbsp;LİSTELENİYOR...').addClass('disabled');},
                                success: function (cevap) {
                                    dialog.modal('hide');
                                    $("#containerlistele").html(cevap);
                                    //$("#btnlistele").html('<i class="fa fa-caret-square-o-down"></i>&nbsp;&nbsp;Listele').removeClass('disabled');
                                    $('#filtrelistele')
                                        .DataTable({
                                            order: [[0, "desc"]],
                                            responsive: false,
                                            select: false,
                                            colReorder: true,
                                            //  dom: "Blfrtip",
                                            sDom: "<'row-fluid'<'span12' B>><'row-fluid'<'span8 ' f><'span4 btnaktar'>> rt <'row-fluid'<'span8' i><'span4 btnaktar'>>",
                                            // lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "Hepsi"]],
                                            pageLength: -1,
                                            buttons: [
                                                'colvis',
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
                                                "sProcessing": "İşleniyor...",
                                                "sLengthMenu": "Sayfada _MENU_ Kayıt Göster",
                                                "sZeroRecords": "Eşleşen Kayıt Bulunmadı",
                                                "sInfo": "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                                                "sInfoEmpty": "Kayıt Yok",
                                                "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                                                "sInfoPostFix": "",
                                                "sSearch": "Bul:",
                                                "sUrl": "",
                                                "oPaginate": {
                                                    "sFirst": "İlk",
                                                    "sPrevious": "Önceki",
                                                    "sNext": "Sonraki",
                                                    "sLast": "Son"
                                                },
                                                "aria": {
                                                    "sortAscending": ": Artan Sütuna göre sıralama etkinleştirildi",
                                                    "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                                                },
                                                "loadingRecords": "Yükleniyor...",
                                                buttons: {
                                                    print: 'YAZDIR',
                                                    colvis: 'Görünürlük'
                                                }
                                            }

                                        });
                                    if (Y.one('.check-all')) {
                                        Y.one('.check-all').on('click', function (e) {

                                            if (e.target.get('checked')) {
                                                Y.all('.check-row').set('checked', 'checked');
                                            } else {
                                                Y.all('.check-row').set('checked', '');
                                            }
                                        });
                                    }
                                    $(".btnaktar").html('<button  id="sendbasket"  class="btn btn-success pull-right">Listeye Aktar&nbsp;&nbsp;<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></button>');
                                    Y.all("#sendbasket").on('click', function () {
                                        var dialog = bootbox.dialog('<div class="text-center">Gönderme Listesine Aktarılıyor&nbsp;&nbsp;<img src="/blocks/okulsis/pic/progressBar3d.gif"></div>', '', {
                                                "animate": false,
                                                "onEscape": false
                                            }
                                        );
                                        //ajax idleri postalayacaz
                                        var ids = [];
                                        $("input[name='checkRow[]']:checked").each(function () {
                                            ids.push(parseInt($(this).val()));
                                        });
                                        $.ajax({
                                            url: 'SMS/ajaxaktar.php',
                                            type: "POST",
                                            dataType: 'HTML',
                                            data: {'filtre': 'aktar', 'ids': ids},
                                            beforeSend: function () {
                                            },
                                            success: function (cevap) {
                                                if (cevap == 1) {
                                                    dialog.modal('hide');
                                                    bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/warninguser.png">&nbsp;&nbsp;Kullanıcı Seçmediniz ! </div>',
                                                        [{
                                                            "label": "Tamam",
                                                            "class": "btn-green"
                                                        }],
                                                        {
                                                            "animate": false,
                                                            "onEscape": false
                                                        }
                                                    );

                                                } else {
                                                    listele();
                                                    dialog.modal('hide');
                                                }

                                            }


                                        });
                                    });

                                }
                            })
                        })
                    }
                })
            });

            $("#kurssec").change(function () {
                var courseid = $("#kurssec").val();
                if (courseid == -1) {
                    $("#sinavdoldur").html('');
                    $("#altbolumdoldur").html('');
                }
                $.ajax({
                    data: {'filtre': 'altbolum', 'courseid': courseid},
                    beforeSend: function () {
                        $("#loading").removeClass('hidden');
                    },
                    success: function (cevap) {
                        $("#loading").addClass('hidden');
                        $("#altbolumdoldur").html(cevap);
                        $("#sinavdoldur").html('');
                        $("#altbolumsec").select2();
                        $("#altbolumsec").change(function () {
                            var bolumid = $("#altbolumsec").val();
                            $.ajax({
                                data: {'filtre': 'sinav', 'bolumid': bolumid},
                                beforeSend: function () {
                                    $("#loading").removeClass('hidden');
                                },
                                success: function (cevap) {
                                    $("#loading").addClass('hidden');
                                    $("#sinavdoldur").html(cevap);
                                    $("#sinavsec").select2();
                                    $("#slider").ionRangeSlider({
                                        type: "double",
                                        grid: false,
                                        min: 0,
                                        max: 100,
                                        grid_num: 10,
                                        step: 1,
                                        grid_snap: true,
                                        keyboard: true,
                                        keyboard_step: 10,
                                        prefix: "Not:"
                                    });
                                }
                            })
                        })
                    }

                })
            });
            $("input:radio[name=rdnfiltre]").click(function () {
                var value = $(this).val();
                if (value == 1) {
                    $("#notbaremi").addClass('hidden');
                }
                else if (value == 2) {
                    $("#notbaremi").removeClass('hidden');
                }
                else if (value == 3) {
                    $("#notbaremi").removeClass('hidden');
                }
                else if (value == 4) {
                    $("#notbaremi").addClass('hidden');
                }
            });
            $("#btngelismislistele").click(function () {
                var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Listeleniyor...</div>', '', {
                        "animate": false,
                        "onEscape": false
                    }
                );
                $("#containerlistele").html('');
                var kursid = $("#kurssec").val();
                var altbolum = $("#altbolumsec").val();
                var sinavid = $("#sinavsec").val();
                var rdnfiltre = $("input[name=rdnfiltre]:checked").val();
                var from = $("#slider").data("from");
                var to = $("#slider").data("to");
                $.ajax({
                    data: {'filtre': 'gelismisfiltreleme', 'kursid': kursid, 'altbolum': altbolum, 'sinavid': sinavid, 'rdnfiltre': rdnfiltre, 'from': from, 'to': to},
                    beforeSend: function () {
                    },
                    success: function (cevap) {
                        dialog.modal('hide');
                        if (cevap == 1) {
                            $(".notyfy-block.filtre").notyfy({
                                text: "Eksik veri girişi yaptınız! Lütfen tekrar deneyiniz",
                                type: "error",
                                dismissQueue: true,
                                layout: "inline"

                            })
                        } else {
                            $(".notyfy-block").html('');
                            $("#containerlistele").html(cevap);
                            $('#filtrelistele')
                                .DataTable({
                                    order: [[0, "desc"]],
                                    responsive: false,
                                    select: false,
                                    colReorder: true,
                                    //dom: "lfrtip",
                                    sDom: "<'row-fluid'<'span12' B>><'row-fluid'<'span8 ' f><'span4 btnaktar'>> rt <'row-fluid'<'span8' i><'span4 btnaktar'>>",
                                    // lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "Hepsi"]],
                                    pageLength: -1,
                                    buttons: [
                                        'colvis',
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
                                        "sProcessing": "İşleniyor...",
                                        "sLengthMenu": "Sayfada _MENU_ Kayıt Göster",
                                        "sZeroRecords": "Eşleşen Kayıt Bulunmadı",
                                        "sInfo": "  _TOTAL_ Kayıttan _START_ - _END_ Arası Kayıtlar",
                                        "sInfoEmpty": "Kayıt Yok",
                                        "sInfoFiltered": "( _MAX_ Kayıt İçerisinden Bulunan)",
                                        "sInfoPostFix": "",
                                        "sSearch": "Bul:",
                                        "sUrl": "",
                                        "oPaginate": {
                                            "sFirst": "İlk",
                                            "sPrevious": "Önceki",
                                            "sNext": "Sonraki",
                                            "sLast": "Son"
                                        },
                                        "aria": {
                                            "sortAscending": ": Artan Sütuna göre sıralama etkinleştirildi",
                                            "sortDescending": ":Azalan Sütuna göre sıralama etkinleştirildi"
                                        },
                                        "loadingRecords": "Yükleniyor...",
                                        buttons: {
                                            print: 'YAZDIR',
                                            colvis: 'Görünürlük'
                                        }
                                    }

                                });

                            if (Y.one('.check-all')) {
                                Y.one('.check-all').on('click', function (e) {

                                    if (e.target.get('checked')) {
                                        Y.all('.check-row').set('checked', 'checked');
                                    } else {
                                        Y.all('.check-row').set('checked', '');
                                    }
                                });
                            }
                            $(".btnaktar").html('<button  id="sendbasket"  class="btn btn-success pull-right">Listeye Aktar&nbsp;&nbsp;<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></button>');
                            Y.all("#sendbasket").on('click', function () {
                                var dialog = bootbox.dialog('<div class="text-center">Gönderme Listesine Aktarılıyor&nbsp;&nbsp;<img src="/blocks/okulsis/pic/progressBar3d.gif"></div>', '', {
                                        "animate": false,
                                        "onEscape": false
                                    }
                                );
                                //ajax idleri postalayacaz
                                var ids = [];
                                $("input[name='checkRow[]']:checked").each(function () {
                                    ids.push(parseInt($(this).val()));
                                });
                                $.ajax({
                                    url: 'SMS/ajaxaktar.php',
                                    type: "POST",
                                    dataType: 'HTML',
                                    data: {'filtre': 'aktar', 'ids': ids},
                                    beforeSend: function () {
                                    },
                                    success: function (cevap) {
                                        if (cevap == 1) {
                                            dialog.modal('hide');
                                            bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/warninguser.png">&nbsp;&nbsp;Kullanıcı Seçmediniz ! </div>',
                                                [{
                                                    "label": "Tamam",
                                                    "class": "btn-green"
                                                }],
                                                {
                                                    "animate": false,
                                                    "onEscape": false
                                                }
                                            );

                                        } else {
                                            listele();
                                            dialog.modal('hide');
                                        }

                                    }


                                });
                            });

                        }

                    }
                })
            });


        });


    }
    else if (param1 == 'sms' && param2 == 'settings') {
        $('#ogretmensec').select2();
        $("#kurumsec").select2();
        $("#ogretmensec").change(function () {
            $("#kurumsec").val("-1");
            $("#bolumdoldur").html('');
            $("#kurumsec").select2();
        });
        $("#kurumsec").change(function () {
            var dialog = bootbox.dialog('<div class="text-center"><img src="/blocks/okulsis/pic/squares.svg">&nbsp;&nbsp;Lütfen Bekleyiniz...</div>', '', {
                    "animate": false,
                    "onEscape": false
                }
            );
            var kurum = $("#kurumsec").val();
            var ogretmen_id = $("#ogretmensec").val();
            $.ajax({
                url: 'SMS/ajax.php',
                type: "POST",
                dataType: 'HTML',
                data: {'filtre': 'bolumsetting', 'kurum': kurum,'ogretmen_id':ogretmen_id},
                success: function (cevap) {
                    $("#bolumdoldur").html(cevap);
                    dialog.modal('hide');
                    $("input[name='siniflar[]']").click(function () {
                        var bolum = $.trim($(this).val());
                        var kurum = $.trim($("#kurumsec").val());
                        var ogretmen_id = parseInt($("#ogretmensec").val());
                        $.ajax({
                            url: "SMS/ajaxyetki.php",
                            type: "POST",
                            dataType: 'HTML',
                            data: {'filtre': 'yetkiveral', 'bolum': bolum,'kurum':kurum,'ogretmen_id':ogretmen_id},
                            beforeSend: function () {
                                $('#loadingyetki').removeClass('hidden');
                            },
                            success: function () {
                                $('#loadingyetki').addClass('hidden');
                            }

                        })
                    });
                }
            })
        });


    }
    else if (param1 == 'sms' && param2 == 'report') {
        if (param3 == 'main') {
            $(".scrollboxreportlist").niceScroll({
                cursoropacitymin: 0.01,
                cursoropacitymax: 0.4,
                cursorcolor: "#adafb5",
                cursorwidth: "4px",
                cursorborder: "",
                cursorborderradius: "4px",
                usetransition: 1000,
                background: "",
                railoffset: {top: 0, left: -4},
                bouncescroll: true

            });

            $('.reportsil').on("click", function (e) {
                e.preventDefault();
                var libox = $(this).closest('tr');
                var id = $(this).attr("id");
                $.ajax({
                    type: "POST",
                    url: "SMS/ajaxdellreport.php",
                    data: {'id': id},
                    async: true,
                    cache: false,
                    success: function () {
                        libox.slideUp('slow', function () {
                            $(this).remove();
                        });
                        $(".scrollboxreportlist").getNiceScroll().resize();


                    }

                });

                return false;
            });

        }
        else if (param3 == 'detay') {
            $(".scrollboxdetayreport").niceScroll({
                cursoropacitymin: 0.01,
                cursoropacitymax: 0.4,
                cursorcolor: "#adafb5",
                cursorwidth: "4px",
                cursorborder: "",
                cursorborderradius: "4px",
                usetransition: 1000,
                background: "",
                railoffset: {top: 0, left: -4},
                bouncescroll: true

            });
        }
    }
    else if (param1 == 'sms' && param2 == 'future') {
        $(".scrollboxfuture").niceScroll({
            cursoropacitymin: 0.01,
            cursoropacitymax: 0.4,
            cursorcolor: "#adafb5",
            cursorwidth: "4px",
            cursorborder: "",
            cursorborderradius: "4px",
            usetransition: 1000,
            background: "",
            railoffset: {top: 0, left: -4},
            bouncescroll: true

        });

        $('.cancelbulkid').on("click", function (e) {
            e.preventDefault();
            var libox = $(this).closest('tr');
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: "SMS/ajaxcancelbulkid.php",
                data: {'id': id},
                async: true,
                dataType: 'HTML',
                success: function ($cevap) {
                    bootbox.alert($cevap);
                    libox.slideUp('slow', function () {
                        $(this).remove();
                    });
                    $(".scrollboxfuture").getNiceScroll().resize();

                }

            });

            return false;
        });
    }


};
    

