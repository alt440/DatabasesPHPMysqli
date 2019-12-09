<?php

  $password = 'aaa';
  $val_hashed = password_hash($password, PASSWORD_BCRYPT);
  var_dump($val_hashed);

?>
