<?php
session_start();
session_destroy();
header("Location: index.php?succes=logout");
exit();
