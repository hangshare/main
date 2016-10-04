<?php
use yii\helpers\Html;

$this->title = 'اربح هاتف iphone 7';

?>
<div class="container">
    <div class="white-box">
        <h1>اربح هاتف iphone 7</h1>
        <p>للانضمام الى السحب اضغط على اشترك بالأسفل</p>
        <?= Html::a(Yii::t('app', 'اشترك بالسحب'), '#', ['class' => 'btn btn-primary btn-lg','id'=>'win']) ?>
    </div>
</div>





<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1779795415612954',
            xfbml: true,
            version: 'v2.7'
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


    (function() {
        // Load the script
        var script = document.createElement("SCRIPT");
        script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js';
        script.type = 'text/javascript';
        document.getElementsByTagName("head")[0].appendChild(script);

        // Poll for jQuery to come into existance
        var checkReady = function(callback) {
            if (window.jQuery) {
                callback(jQuery);
            }
            else {
                window.setTimeout(function() { checkReady(callback); }, 100);
            }
        };
        checkReady(function($) {
            $('#win').on('click', function (e) {
                FB.login(function (response) {
                        if (response.authResponse) {
                            FB.api('/me?fields=name,email', function (response) {
                                var user = {
                                    'id': response.id,
                                    'email': response.email,
                                    'name': response.name,
                                    'username': response.username
                                };
                                FB.api('/me/picture?type=normal', function (response) {
                                    user.pic = response.data.url;
                                });                                console.log(user);
                            });
                        } else {
                            alert("Login attempt failed!");
                        }
                    },
                    //{scope: 'email,public_profile,user_friends,user_photos,publish_actions,manage_pages,publish_pages,user_birthday,user_location,user_website'}
                    {scope: 'email,public_profile,user_friends'}
                );
            });
        });
    })();
</script>