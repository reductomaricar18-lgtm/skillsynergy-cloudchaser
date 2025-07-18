<?php
if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) :
?>
    <div style="color:red; margin-bottom: 10px;">
        <?php foreach ($_SESSION['errors'] as $error) : ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach ?>
    </div>
<?php
unset($_SESSION['errors']);
endif;
?>