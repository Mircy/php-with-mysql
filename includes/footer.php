<?php
//Creating the footer
$foot = 'templates/footer.html';
$tplFooter = file_get_contents($foot);
$footer = str_replace('[+footer+]', 'Copyright © Photo Gallery, 2016', $tplFooter);
?>