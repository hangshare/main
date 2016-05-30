$(function () {
    if ((location.hash == "#_=_" || location.href.slice(-1) == "#_=_")) {
        removeHash();
    }
    $('[data-toggle="tooltip"]').tooltip();
    function removeHash() {
        var scrollV, scrollH, loc = window.location;
        if ('replaceState' in history) {
            history.replaceState('', document.title, loc.pathname + loc.search);
        } else {
            // Prevent scrolling by storing the page's current scroll offset
            scrollV = document.body.scrollTop;
            scrollH = document.body.scrollLeft;

            loc.hash = '';

            // Restore the scroll offset, should be flicker free
            document.body.scrollTop = scrollV;
            document.body.scrollLeft = scrollH;
        }
    }

    var stickyHeaderTop = $('#w0').offset().top;
    var stiky = $('.stiky');
    var a1 = $('#1a');
    if (a1.length > 0) {
        var a1 = $('#1a').offset().top;
    }
    $(window).scroll(function () {
        if ($(window).scrollTop() > stickyHeaderTop + 150) {
            $('.scroll-header').fadeIn();
        } else {
            $('.scroll-header').fadeOut();
        }
        if (a1.length > 0) {
            if (stiky.length > 0 && $(window).scrollTop() > a1 + 92) {
                stiky.attr({'style': 'position:fixed;top:40px;'});
            } else if (stiky.length > 0 && $(window).scrollTop() < a1 + 85) {
                stiky.attr({'style': 'position:absolute;top:auto;'});
            }
        }
    });
    if ($.isFunction($.fn.editable)) {
        $('.froala-edit').editable({
            inlineMode: false,
            toolbarFixed: true,
            mediaManager: false,
            language: 'ar',
            direction: 'rtl',
            imageUploadURL: '/explore/upload/',
            minHeight: 200,
            maxHeight: 800
        });
    }
    input = document.getElementById("post-cover_file");
    if (input) {
        input.onchange = function () {
            textinpt = document.getElementById("uploadFile");
            if (textinpt)
                textinpt.value = this.value;

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#coveri').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        };
    }

    input = document.getElementById("goldtime");
    if (input) {
        input.onchange = function () {
            if (this.value === 'b') {
                $('#planb').show();
                $('#planc').hide();
            } else if (this.value === 'c') {
                $('#planb').hide();
                $('#planc').show();
            }
        };
    }
    fixad = $("#fixad, .fixad");
    if (fixad.length > 0) {
        $(window).scroll(function () {
            value = fixad.attr('rel');
            if ($(window).scrollTop() + $(window).height() > value) {
                fixad.css({'position': 'fixed', 'top': '0'});
            } else {
                fixad.css({'position': 'inherit', 'top': '0'});
            }
        });
    }
    var faqSearch = $('#faqsearch');
    if (faqSearch.length > 0) {
        $.getScript("https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.js", function () {
            faqSearch.ajaxForm({
                beforeSend: function () {
                    //$('#loading').fadeIn(); 
                },
                success: function (data) {
                    $('#content').html($(data).find('#content'));
                },
                complete: function (xhr) {
                }
            });
        });
    }

    function randomFolderName() {
        var text = "";
        var possible = "1234ABCDEFGHIJKLMNOPQRSTUVWXY567890Zabcdefghijklmnopqrstuvwxyz0123456789-";

        for (var i = 0; i < 6; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    //cover image to s3 upload
    var uploadform = $('#uploadform');
    if (uploadform.length > 0) {
        $(document).on('click', '#uploadtos3', function (e) {
            e.preventDefault();
            $('#files3').click();
        });
        var credData;
        $.ajax({
            url: '/explore/s3crd/',
            type: 'POST',
            dataType: 'JSON',
            data: {},
            success: function (data) {
                credData = data;
            }
        });
        var _URL = window.URL;
        $('#files3').change(function () {
            var file, img, widthCover, heightCover;
            var Type = $('#type').val();
            if (file = this.files[0]) {
                img = new Image();
                img.onload = function () {
                    widthCover = this.width;
                    heightCover = this.height;
                };
                img.src = _URL.createObjectURL(file);

                folderName = randomFolderName();
                formData = new FormData();
                formData.append("key", folderName + "/" + file.name);
                formData.append("acl", credData.inputs.acl);
                formData.append("success_action_status", credData.inputs.success_action_status);
                formData.append("Content-Type", file.type);
                formData.append("policy", credData.inputs.policy);
                formData.append("X-amz-credential", credData.inputs.X_amz_credential);
                formData.append("X-amz-algorithm", credData.inputs.X_amz_algorithm);
                formData.append("X-amz-date", credData.inputs.X_amz_date);
                formData.append("X-amz-expires", credData.inputs.X_amz_expires);
                formData.append("X-amz-signature", credData.inputs.X_amz_signature);

                formData.append("file", file);
                $("#prev").fadeIn();
                $.ajax({
                    url: credData.url,
                    type: 'POST',
                    data: formData,
                    crossDomain: true,
                    contentType: false,
                    processData: false,
                    dataType: 'XML',
                    success: function (response, status, xml) {
                        var Bucket = xml.responseXML.getElementsByTagName("PostResponse")[0].childNodes[1].firstChild.nodeValue;
                        var Key = xml.responseXML.getElementsByTagName("PostResponse")[0].childNodes[2].firstChild.nodeValue;
                        if (status === 'success') {
                            $("#cover_input").val('{"bucket":"' + Bucket + '", "key":"' + Key + '", "width": "' + widthCover + '", "height" : "' + heightCover + '"}');
                            $.ajax({
                                url: "/explore/resize/",
                                method: "POST",
                                dataType: "json",
                                data: {bucket: Bucket, key: Key, type : Type},
                                complete: function () {
                                    $("#prev").hide();
                                }
                            });
                        }
                    }
                });
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#coveri').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    // cover image to s3 upload

    if ($("#user-country").length) {
        rel_c = $("#user-country").attr('rel');
        if (rel_c === 'autoload') {
            $.ajax({
                url: "/user/getcountry/",
                method: "POST",
                dataType: "json",
                success: function (data) {
                    $('#user-country').val(data.id);
                }
            });
        }
    }


    $(document).on('click', '#main-post', function (e) {
        if ($('#post-ylink').val() === "" && $("#cover_input").val() === "" && $("#covercheck").val() === "") {
            e.preventDefault();
            var body = $("html, body");
            body.stop().animate({scrollTop: 0}, '500', 'swing', function () {
                $('#cover_error').fadeIn(1000);
            });
        }
    });


    $(document).on('submit', '#add-post', function (e) {
        if ($('.has-error').length > 0) {

        } else {
            $(this).prop('disabled', true);
            $('body').append("<div class='black_overlay'></div><div class='white_content'>الرجاء الإنتظار</div>");
        }
    });

    $(document).on('click', '.js_passch', function (e) {
        e.preventDefault();
        var old = $('#user-password_old').val();
        var password = $('#user-password_new').val();
        var confirm = $('#user-password_re').val();
        if (confirm !== password) {
            alert('تأكيد كلمة السر غير مطابق لكلمة السر.');
            return;
        }
        $.ajax({
            url: "changepass",
            method: "POST",
            dataType: "json",
            data: {password: password, old: old},
            success: function (result) {
                if (result.status === false) {
                    alert('كلمة السر الحالية غير صحيحة.');
                } else {
                    alert('تم تغيير كلمة المرور بنجاح.');
                    $(".modal").modal('hide');
                }
            }
        });
    });
    $(document).on('click', '.js-share', function (e) {
        e.preventDefault();
        var title = document.title;
        var url = document.URL;
        var urlpost = $(this).attr('post-url');
        if (urlpost.length) {
            url = urlpost;
        }

        if ($(this).hasClass('js-share-twitter')) {
            popup_url = 'http://twitter.com/intent/tweet?text=' + title + ' @hangshare ' + url;
        } else if ($(this).hasClass('js-share-fasebook')) {
//            popup_url = 'https://www.facebook.com/dialog/feed?app_id=1024611190883720&display=popup&link=' + url + '&redirect_uri=http://www.hangshare.com/';
            popup_url = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
        } else if ($(this).hasClass('js-share-gpuls')) {
            popup_url = 'https://plus.google.com/share?url="' + url + '"';
        }
        var popUp = window.open(popup_url,
            'popupwindow',
            'scrollbars=yes,width=600,height=400');
        popUp.focus();
    });

    $('#sharenew').modal('show');

    function random() {
        var random_number = Math.random();
        return Math.floor((random_number * 100000) + 200);
    }

    $(document).on('click', '.showclick', function (e) {
        e.preventDefault();
        console.log('asd');
        var Id = $(this).attr('id');
        $("[id$='_form']").hide();

        $(".showclick").removeClass("btn-warning");
        $(".showclick").addClass("btn-default");

        $(this).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $('#' + Id + '_form').show();
    });


    if ($('article').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: "/explore/countcheck/?qt=" + random(),
            data: Dat
        });
    }

    if ($('#related-posts').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: "/explore/related/?qt=" + random(),
            data: Dat,
            success: function (data, textStatus, jqXHR) {
                $('#related-posts').append(data);
            }
        });
    }

    inifi = $('.inifi');
    if (inifi.length) {
        inifi_page = 2;
        inifi_total = 2;
        inifi_called = false;
        htmlloading = '<div id="loadingi"><i style="display: block;font-size: 66px;left: 47%;margin: 0 auto 50px;position: relative;text-align: center;top: 18px;" class="fa fa-spinner fa-pulse"></i></div>';
//        getmoreSearch();
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                if (inifi_page > inifi_total) {
                    return false;
                } else {
                    getmoreSearch();
                }
            }
        });
    }
    function getmoreSearch() {
        var inifi_url = window.location.href;
        if (inifi_url.indexOf('?') === -1) {
            inifi_url += '?';
        } else {
            inifi_url += '&';
        }
        inifi_url += 'page=' + inifi_page;

        if (!inifi_called) {
            inifi_called = true;
            $(".inifi").append(htmlloading);

            $.ajax({
                url: inifi_url,
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    inifi_total = Math.ceil(data.total / data.PageSize);
                    $('#loadingi').fadeOut();
                    $(".inifi").append(data.html);
                    $('#loadingi').remove();
                    inifi_called = false;
                    inifi_page++;
                }
            });
        }
        return false;
    }

    var scrolltotop = {
        setting: {startline: 100, scrollto: 0, scrollduration: 1000, fadeduration: [500, 100]},
        controlHTML: '<i class="fa fa-chevron-up backtotop"></i>', //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
        controlattrs: {offsetx: 5, offsety: 5}, //offset of control relative to right/ bottom of window corner
        anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

        state: {isvisible: false, shouldvisible: false},
        scrollup: function () {
            if (!this.cssfixedsupport) //if control is positioned using JavaScript
                this.$control.css({opacity: 0}); //hide control immediately after clicking it
            var dest = isNaN(this.setting.scrollto) ? this.setting.scrollto : parseInt(this.setting.scrollto);
            if (typeof dest == "string" && jQuery('#' + dest).length == 1) //check element set by string exists
                dest = jQuery('#' + dest).offset().top;
            else
                dest = 0;
            this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
        },
        keepfixed: function () {
            var $window = jQuery(window);
            var controlx = $window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;
            var controly = $window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;
            this.$control.css({left: controlx + 'px', top: controly + 'px'})
        },
        togglecontrol: function () {
            var scrolltop = jQuery(window).scrollTop();
            if (!this.cssfixedsupport)
                this.keepfixed();
            this.state.shouldvisible = (scrolltop >= this.setting.startline) ? true : false;
            if (this.state.shouldvisible && !this.state.isvisible) {
                this.$control.stop().animate({opacity: 1}, this.setting.fadeduration[0]);
                this.state.isvisible = true
            }
            else if (this.state.shouldvisible == false && this.state.isvisible) {
                this.$control.stop().animate({opacity: 0}, this.setting.fadeduration[1]);
                this.state.isvisible = false
            }
        },
        init: function () {
            jQuery(document).ready(function ($) {
                var mainobj = scrolltotop;
                var iebrws = document.all;
                mainobj.cssfixedsupport = !iebrws || iebrws && document.compatMode == "CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
                mainobj.$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
                mainobj.$control = $('<div id="topcontrol">' + mainobj.controlHTML + '</div>')
                    .css({
                        position: mainobj.cssfixedsupport ? 'fixed' : 'absolute',
                        bottom: mainobj.controlattrs.offsety,
                        right: mainobj.controlattrs.offsetx,
                        opacity: 0,
                        cursor: 'pointer'
                    })
                    .attr({title: 'Scroll Back to Top'})
                    .click(function () {
                        mainobj.scrollup();
                        return false
                    }).appendTo('body');
                if (document.all && !window.XMLHttpRequest && mainobj.$control.text() != '') //loose check for IE6 and below, plus whether control contains any text
                    mainobj.$control.css({width: mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
                mainobj.togglecontrol();
                $('a[href="' + mainobj.anchorkeyword + '"]').click(function () {
                    mainobj.scrollup();
                    return false
                });
                $(window).bind('scroll resize', function (e) {
                    mainobj.togglecontrol()
                });
            });
        }
    };
    scrolltotop.init();


});

