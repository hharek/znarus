<?php
Cache::delete(["module" => "menu"]);

Menu_Item::order($_POST['id'], $_POST['order']);
?>