<?php
use Framework\Session;

?>

<?php if (Session::hasFlashMessage('success_message')) { ?>
    <div class="message bg-green-100 p-3 my-3">
        <?= Session::getFlashMessage('success_message') ?>
    </div>
<?php } ?>
    
<?php if (Session::hasFlashMessage('error_message')) { ?>
    <div class="message bg-red-100 p-3 my-3">
        <?= Session::getFlashMessage('error_message') ?>
    </div>
<?php } ?>
