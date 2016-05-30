<?php if ($data->vidType == 'youtube') : ?>
<iframe class="you" src="https://www.youtube.com/embed/<?= $data->vidId ?>?html5=1&autoplay=0&controls=1&showinfo=0&rel=0&autohide=1"
            frameborder="0" allowfullscreen></iframe>    
        <?php elseif ($data->vidType == 'vimeo') : ?>
    <iframe id="vimeo_player" src="//player.vimeo.com/video/<?= $data->vidId ?>?api=1&player_id=vimeo_player&autoplay=0&loop=1&color=ffffff" width="100%" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php endif; ?>