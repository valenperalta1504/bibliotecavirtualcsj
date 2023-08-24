<?php
if (!defined('ACCESO_PERMITIDO')) {
    header("Location: home.php");
  }
  else {
$conn->close();
}
?>