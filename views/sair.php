<?php

session_start();

$_SESSION["codigo_adm"] = '';

unset($_SESSION["codigo_adm"]);

header("Location: /Login");