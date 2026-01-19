<!DOCTYPE html>
<html>
    <body>
        <?php
        $url = gtbabel__('http://gtbabel.vielhuber.dev', null, null, 'slug|file|url');
        $title = gtbabel__('Test', null, null, 'title');
        echo '<ul>';
        echo '<li><a class="facebook" target="_blank" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=' .
            urlencode($url) .
            '">Facebook</a></li>';
        echo '<li><a class="xing" target="_blank" rel="nofollow" href="https://www.xing.com/app/user?op=share&amp;url=' .
            urlencode($url) .
            '">Xing</a></li>';
        echo '<li><a class="linkedin" target="_blank" rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&url=' .
            urlencode($url) .
            '&title=' .
            urlencode($title) .
            '&summary=&source=">LinkedIn</a></li>';
        echo '<li><a class="twitter" target="_blank" rel="nofollow" href="https://twitter.com/intent/tweet?url=&text=' .
            urlencode($title . ' - ' . $url) .
            '">Twitter</a></li>';
        echo '<li><a class="whatsapp" rel="nofollow" href="whatsapp://send?text=' .
            urlencode($title . ' - ' . $url) .
            '">WhatsApp</a></li>';
        echo '<li><a class="mail" rel="nofollow" href="mailto:?subject=' .
            rawurlencode($title) .
            '&body=' .
            rawurlencode($url) .
            '">E-Mail</a></li>';
        echo '</ul>';
        ?>
    </body>
</html>
