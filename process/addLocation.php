<?php 
require "../bootstrap/init.php";

// if (!isAjaxRequest()) {
//     diePage("Invalid Request!");
// }
// request is Ajax and Ok
var_dump($_POST);

if (insertLocation($_POST)) {
    echo "مکان با موفقیت در پایگاه داده ثبت شد و منتظر تایید مدیر است.";
}else {
    echo "مشکلی در ثبت مکان پیش امده است";
}
