<?php
require_once "../vendor/autoload.php";

if ($_SERVER['REQUEST_URI']=='/'){
    include_once "../app/controllers/postsController.php";
}
if ($_SERVER['REQUEST_URI']=='/user'){
    include_once "../app/controllers/userController.php";
}

