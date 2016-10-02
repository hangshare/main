<?php
$this->beginContent('@app/views/layouts/htmlhead.php');
$this->endContent();
$this->beginContent('@app/views/layouts/header.php');
$this->endContent();
?>
    <style type="text/css">
        .gm-style-pbc {
            transition: opacity ease-in-out;
            background-color: black;
            text-align: center
        }

        .overlay {
            color: #fff;
            position: absolute;
            text-align: center;
            top: 0;
            width: 100%;
            height: 91vh;
            z-index: 2;
            padding-top: 100px;
        }

        .fullscreen-bg {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
            z-index: 1;
        }
        .testimonial_item .author_thumb img {
            border-radius: 50%;
        }
        .testimonial_item .author_thumb {
            margin: 0 auto 25px;
            padding: 0;
            transition: all 400ms ease 0s;
            width: 100px;
        }
        .section-title{
            text-align: center;
        }
        .fullscreen-bg__video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .gm-style-pbt {
            font-size: 22px;
            color: white;
            font-family: Roboto, Arial, sans-serif;
            position: relative;
            margin: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%)
        }

        .gm-style .gm-style-cc span, .gm-style .gm-style-cc a, .gm-style .gm-style-mtc div {
            font-size: 10px
        }

        @media print {
            .gm-style .gmnoprint, .gmnoprint {
                display: none
            }
        }

        @media screen {
            .gm-style .gmnoscreen, .gmnoscreen {
                display: none
            }
        }

        .gm-style {
            font-family: Roboto, Arial, sans-serif;
            font-size: 11px;
            font-weight: 400;
            text-decoration: none
        }

        .gm-style img {
            max-width: none
        }

        header {
            position: absolute;
            z-index: 2147483647;
            width: 100%;
        }
    </style>


<!--    <link href="Volar_files/styles.css" rel="stylesheet">-->
<!--    <link href="Volar_files/light-green.css" rel="stylesheet" data-color="" id="theme">-->

        <?= $content ?>
        <?php
        $this->beginContent('@app/views/layouts/footer.php');
        $this->endContent();
        ?>
<?php $this->endPage() ?>