<?php
require_once '../src/ClassLoader.php';
$Document = new \Document\Document;
$Document->setTitle($_GET["error"]);
$Document->setDescription("");
$Document->RenderHead();
$Document->RenderNavigation();
?>
<div class="container container-default">
    <div class="p-3 rounded" style="background-color: rgba(var(--bs-tertiary-bg-rgb)) !important;">
        <h1><?php echo $_GET["error"]; ?></h1>
        <p>An error occured.</p>
        <?php echo $_SERVER['REQUEST_URI'];?>
        <hr>
        <div class="d-grid gap-4 d-md-flex justify-content-md-end">
            <a href="/">Go back</a>
        </div>
    </div>
</div>
<?php $Document->RenderFooter(); ?>