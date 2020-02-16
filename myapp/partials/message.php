<?php
require_once 'init.php';
use App\Session;
?>
<style>
.alert-success {
    z-index: 10;
    position: absolute;
    top: 0;
    width: 50%;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
}
</style>
<?php if(Session::exists('success')): ?>
<div class="alert alert-success" role="alert">
    <?= Session::flash('success') ?>
</div>
<?php endif; ?>