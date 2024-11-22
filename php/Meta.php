<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Playpen+Sans:wght@100..800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
<link href="css/footer.css" rel="stylesheet">
<link href="css/header.css" rel="stylesheet">
<link href="css/root.css" rel="stylesheet">
<link href="img/TechnoLabLogo.png" rel="icon">
<?php 
    function customErrorHandler($errno, $errstr, $errfile, $errline) {
        $logFile = 'error_log.txt';
        $errorMessage = date('Y-m-d H:i:s') . " - Error [$errno] in $errfile on line $errline: $errstr\n";
        error_log($errorMessage, 3, $logFile);
        echo "Something went wrong! Please try again later.";
        
    }
    set_error_handler('customErrorHandler');
    require 'php/MySQL-Commands.php';


?>