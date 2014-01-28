<?php
ZN_Html::inc_delete($_POST['html_id'], $_POST['inc_id']);
redirect("#html/edit?id={$_POST['html_id']}&rand=" . rand(0, 1000));
?>