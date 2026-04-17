<?php

declare(strict_types=1);

/**
 * logout.php
 *
 * Finaliza a sessão do usuário e redireciona para a página de login.
 */

session_start();
session_unset();
session_destroy();
header('Location: ../views/login.php');
exit();
