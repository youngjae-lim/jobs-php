<?php if (isset($errors) && count($errors) > 0) { ?>
    <?php foreach ($errors as $error) { ?>
        <div class="message bg-red-100 p-3 my-3 text-red-700"><?php echo $error; ?></div>
    <?php } ?>
<?php } ?>
