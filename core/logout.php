<?php
if($account){
 session_destroy();
 if(isset($_COOKIE[$system->data('Universal_Session') . '_email'])){
 unset($_COOKIE[$system->data('Universal_Session') . '_email']);
 setcookie($system->data('Universal_Session') . '_email', null, time()-(30*24*60*60));}
 if(isset($_COOKIE[$system->data('Universal_Session') . '_lpip'])){
 unset($_COOKIE[$system->data('Universal_Session') . '_lpip']);
 setcookie($system->data('Universal_Session') . '_lpip', null, time()-(30*24*60*60));
 }
}
$system ->redirect('./');
?>
