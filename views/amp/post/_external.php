<?php

if ($data->vidType == 'youtube') : ?>
    <amp-youtube width="480"
                 height="270"
                 layout="responsive"
                 data-videoid="<?= $data->vidId ?>"
                 autoplay>
    </amp-youtube>
<?php endif; ?>