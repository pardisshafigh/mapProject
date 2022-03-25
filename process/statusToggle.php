<?php 
include "../bootstrap/init.php";

// if (!isAjaxRequest()) {
//     diePage("Invalid Request!");
// }
// request is Ajax and Ok
// var_dump($_POST);


// echo "Ok!";
// dd($_POST);

if (is_null($_POST['loc']) or !is_numeric($_POST['loc'])) {
    echo "Invalid Location!";
    die();
}

echo toggleStatus($_POST['loc']);

