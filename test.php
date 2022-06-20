<?php
    echo gettype($_POST['productID']);
    if ($_POST['productID'] == "") {
        echo "no";
    }
    else {
        echo $_POST['productID'];
    }
?>