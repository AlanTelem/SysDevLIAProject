<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title ?? 'Log In');
?>
<?= App\Helpers\FlashMessage::render() ?>
    
<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
