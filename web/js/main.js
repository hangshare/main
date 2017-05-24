window.fbAsyncInit = function () {
    FB.init({
        appId: '1024611190883720', // '215567798868369',
        xfbml: true,
        version: 'v2.8'
    });
};
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


try {
    if (top.location.hostname != self.location.hostname) throw 1;
} catch (e) {
    top.location.href = self.location.href;
}

$(function () {
    var base_url = window.location.origin;
    var hrefLoc = window.location.href;

    if ((location.hash == "#_=_" || location.href.slice(-1) == "#_=_")) {
        removeHash();
    }
    var url_lang = '';
    if ($.Yii.getLang() == 'en') {
        url_lang = '/en';
    }

    if (base_url == 'http://localhost') {
        url_lang = 'http://localhost/hangshare/web' + url_lang;
    }

    var checkReady = function (callback) {
        if (window.jQuery) {
            callback(jQuery);
        } else {
            window.setTimeout(function () {
                checkReady(callback);
            }, 100);
        }
    };
    checkReady(function ($) {
        $('.fb-login').on('click', function (e) {
            FB.login(function (response) {
                    if (response.authResponse) {
                        var rec = FB.getAuthResponse();
                        FB.api(
                            '/me',
                            'GET',
                            //birthday, location
                            {"fields": "id,name,email,about,cover,gender,age_range"},
                            function (response) {
                                var access_token = FB.getAuthResponse()['accessToken'];
                                $.ajax({
                                    url: url_lang + '/site/fblogin/',
                                    type: 'POST',
                                    data: {data: response, t: access_token},
                                    dataType: 'JSON',
                                    success: function (data) {
                                        window.location.href = data.url
                                    }
                                });
                            }
                        );
                    }
                    //user_location,user_birthday
                }, {scope: 'email,public_profile,user_friends,user_about_me'}
            );
        });
    });

    $(".menu-category > li").on({
        mouseenter: function () {
            $(this).find('.supdropdown').addClass('submenu-active');
        },
        mouseleave: function () {
            $(this).find('.supdropdown').removeClass('submenu-active');
        }
    });

    var delete_cookie = function (name) {
        $.cookie(name, null, {path: "/", domain: ".hangshare.com"});
    };


    $('.showhide').on('click', function (e) {
        var da = $(this).data().element;

        $(".mainmenu").each(function (index, value) {
            if ($(value).data().element != da) {
                $("#" + $(value).data().element).hide();
            }
        });
        $("#" + da).toggle();
    });

    $(document).on('click', '#changeLang', function (e) {
        e.preventDefault();
        delete_cookie("userlanghangshare");
        window.location.href = $(this).attr("data-url");
    });


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

    // if ($.isFunction($.fn.editable)) {
    //     var options = {
    //         fontFamilySelection: true,
    //         buttons: ['paragraphStyle', 'paragraphFormat', 'align', 'outdent', 'indent', 'createLink', 'insertImage', 'insertVideo', 'undo', 'redo'],
    //         inlineMode: false,
    //         shortcutsHint: false,
    //         toolbarFixed: true,
    //         mediaManager: false,
    //         language: 'ar', // $.Yii.getLang(),
    //         imageUploadURL: url_lang + '/explore/upload/',
    //         minHeight: 200,
    //         maxHeight: 800
    //     };
    //     if ($.Yii.getLang() === 'ar')
    //         options['direction'] = 'rtl';
    //
    //     $('.froala-edit').editable(options);
    // }


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


        var editor = new MediumEditor('.editable', {
            buttonLabels: 'fontawesome',
            extensions: {
                spreadsheet: new MediumEditorSpreadsheet()
            }, toolbar: {
                buttons: [
                    'h3',
                    'anchor',
                    'bold',
                    'justifyCenter',
                    'italic',
                    'underline',
                    'unorderedlist',
                    'orderedlist',
                    'quote',
                    'removeFormat'
                ],
                static: true,
                sticky: true
            }
        });

        $('.editable').mediumInsert({
            editor: editor,
            addons: {
                images: {
                    fileUploadOptions: {
                        url: url_lang + '/explore/upload/',
                    }
                }
            }
        });


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
        var possible = "1234ABCDEFGHIJKLMNOPQRSTUVWXY567890Zabcdefghijklmnopqrstuvwxyz0123456789";

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
            url: url_lang + '/explore/s3crd/',
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
                                url: url_lang + "/explore/resize/",
                                method: "POST",
                                dataType: "json",
                                data: {bucket: Bucket, key: Key, type: Type},
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
                url: url_lang + "/user/getcountry/",
                method: "POST",
                dataType: "json",
                success: function (data) {
                    $('#user-country').val(data.id);
                }
            });
        }
    }


    $(document).on('click', '#main-post', function (e) {
        $('#cover_error').hide();
        if ($("#cover_input").val() === "") {
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
            var message = $.Yii.t('Please Wait');
            $('body').append("<div class='black_overlay'></div><div class='white_content'>" + message + "</div>");
        }
    });

    $(document).on('click', '.js_passch', function (e) {
        e.preventDefault();
        var old = $('#user-password_old').val();
        var password = $('#user-password_new').val();
        var confirm = $('#user-password_re').val();
        var message_repated_pass = $.Yii.t("Confirm password is identical to the password");
        if (confirm !== password) {
            alert(message_repated_pass);
            return;
        }
        $.ajax({
            url: url_lang + "changepass",
            method: "POST",
            dataType: "json",
            data: {password: password, old: old},
            success: function (result) {
                if (result.status === false) {
                    alert($.Yii.t("The current password is incorrect"));
                } else {
                    alert($.Yii.t("Password has been changed successfully"));
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
            popup_url = 'http://twitter.com/intent/tweet?text=' + title + ' @hang_share ' + url;
        } else if ($(this).hasClass('js-share-fasebook')) {
//            popup_url = 'https://www.facebook.com/dialog/feed?app_id=1024611190883720&display=popup&link=' + url + '&redirect_uri=http://www.hangshare.com/';
            popup_url = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
        } else if ($(this).hasClass('js-share-gpuls')) {
            popup_url = 'https://plus.google.com/share?url=' + url;
        }
        var popUp = window.open(popup_url,
            'popupwindow',
            'scrollbars=yes,width=600,height=400');
        popUp.focus();
    });

    //$('#sharenew').modal('show');

    function random() {
        var random_number = Math.random();
        return Math.floor((random_number * 100000) + 200);
    }

    $(document).on('click', '.showclick', function (e) {
        e.preventDefault();
        var Id = $(this).attr('id');
        $("[id$='_form']").hide();

        $(".showclick").removeClass("btn-warning");
        $(".showclick").addClass("btn-default");

        $(this).removeClass("btn-warning");
        $(this).addClass("btn-warning");
        $('#' + Id + '_form').show();
    });

    if ($('#scroll-fixed-top').length > 0) {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 150) {
                $("#scroll-fixed-top").css({'top': '60px'});
            } else {
                $("#scroll-fixed-top").css({'top': 'auto'});
            }
        });
    }


    if ($('#slide-signup').length > 0) {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 100) {
                $("#slide-signup").css({'bottom': '0px'});
            } else {
                $("#slide-signup").css({'bottom': '-300px'});
            }
        });
    }


    if ($('article').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: url_lang + "/explore/countcheck/?qt=" + random(),
            data: Dat
        });
    }

    if ($('#hot-posts').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: url_lang + "/explore/hot/?qt=" + random(),
            data: Dat,
            success: function (data, textStatus, jqXHR) {
                $('#hot-posts').append(data);
            }
        });
    }

    if ($('#comments').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: url_lang + "/explore/comments/?qt=" + random(),
            data: Dat,
            success: function (data, textStatus, jqXHR) {
                $('#comments').append(data);
            }
        });
    }

    if ($('#addcomment').length > 0) {
        $(document).on('click', '#addcomment', function (e) {
            e.preventDefault();

            var id = $('#comment-body').attr('date-id');
            var text = $('#comment-body').val();
            $('#comment-body').val('');
            if (text.trim() != '') {
                $.ajax({
                    method: "POST",
                    url: url_lang + "/explore/addcomment/",
                    data: {'text': text, 'id': id},
                    success: function (data, textStatus, jqXHR) {
                        $('#addcomment').unbind('click');
                        $('#commentsCont').prepend(data);
                        $('#commentsCont > li:first-child').hide().fadeIn();
                    }
                });
            }
        });
    }


    if ($('#related-posts').length > 0) {
        var Dat = $('article').data();
        $.ajax({
            method: "POST",
            url: url_lang + "/explore/related/?qt=" + random(),
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


    ////////////////////////////////////////////


    var data = [
        {
            label: $.Yii.t('Post Rating'),
            data: 60,
            color: '#EF7061'
        },
        {
            label: $.Yii.t('Traffic Source'),
            data: 30,
            color: '#2C82C9'
        },
        {
            label: $.Yii.t('Traffic Quality'),
            data: 10,
            color: '#FAC51C'
        }
    ];

    var placeholder = $("#pi1");
    if (placeholder.length > 0) {
        placeholder.unbind();
        $.plot(placeholder, data, {
            series: {
                pie: {
                    show: true,
                    label: {
                        show: true,
                        radius: 0.5,
                        formatter: function (label, series) {
                            var percent = Math.round(series.percent);
                            return ('&nbsp;<span style="color: #fff;">' + percent + '%</span><br/>');
                        }
                    }
                }
            },
        });
    }

    $(window).load(function () {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: true,
            live: true,
            callback: function (box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null
        });
        wow.init();
    });

    $('.counter').viewportChecker({
        classToAdd: 'visible',
        offset: 20,
        repeat: false,
        callbackFunction: function (elem, action) {
            ii = elem.data('content');

            animateNumbers(elem, ii);
        }
    });

    function animateNumbers(obj, end) {
        $({countNum: 0}).animate({countNum: end}, {
            duration: 4000,
            easing: 'easeOutExpo',
            step: function () {
                obj.text(commaSeparateNumber(Math.floor(this.countNum) + 1));
            }
        });
    }

    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return val;
    }

    $('#testimonials').owlCarousel({
        items: 1,
        rtl: true
    });


    var postq = $('#postq');
    if (postq.length > 0) {

        var titleq = 0;
        var tagsq = 0;
        var catq = 0;
        var imagesq = 0;
        var linksINq = 0;
        var linksEXq = 0;
        var textq = 0;
        var ptagq = 0;

        //title quality
        $('#post-title').keyup(function () {
            if ($('#post-title').val().length > 10) {
                titleq = 5;
            } else {
                titleq = 0;
            }
            if ($('#post-title').val().length >= 40) {
                titleq = 15;
            }
            qupdate();
        });

        //tags quality
        $('.select2-selection__rendered').on('bind', "DOMSubtreeModified", function () {
            alert('changed');
        });


        function qupdate() {
            var total = titleq;
            postq.html(total + '%');
        }
    }
    if ($.isFunction($.fn.editable)) {
        var options = {
            fontFamilySelection: true,
            buttons: ['paragraphStyle', 'paragraphFormat', 'align', 'outdent', 'indent', 'createLink', 'insertImage', 'insertVideo', 'undo', 'redo'],
            inlineMode: false,
            shortcutsHint: false,
            toolbarFixed: true,
            mediaManager: false,
            language: $.Yii.getLang(),
            imageUploadURL: url_lang + '/explore/upload/',
            minHeight: 200,
            maxHeight: 800
        };
        if ($.Yii.getLang() === 'ar')
            options['direction'] = 'rtl';

        $('.froala-edit').editable(options);
    }
});

