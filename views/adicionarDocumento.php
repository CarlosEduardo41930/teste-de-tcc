<?php
require_once '../controllers/UserControll.php';
require_once './components/UserComponents.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p><?php echo $_SESSION['id_paciente']; ?></p>
    <p>tool</p>
</body>
</html>