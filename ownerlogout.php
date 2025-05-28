<?php
session_start();
session_destroy();
header('Location: ownerlogin.html');
exit;
?>
