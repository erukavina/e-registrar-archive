<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
require_once '../src/ClassLoader.php';
$Document = new \Document\Document;

if (isset($_GET["q"]) && $_GET["q"] != "") {
    $host = parse_url((strpos($_GET["q"], 'http://') === false && strpos($_GET["q"], 'https://') === false ? 'https://' : '') . $_GET["q"], PHP_URL_HOST);

    $Document->setTitle($host);
    $Document->setDescription("Explore the 'behind the scenes' of " . $host . ". Check their TrustScore, domain information, security practices, and much more.");
} else {
    $Document->setTitle(null);
    $Document->setDescription("Explore the 'behind the scenes' of the internet. Check the TrustScore of a website, domain information, security practices, and much more.");
}
$Document->RenderHead(true);
$Document->RenderNavigation();
?>
<script async src="/AJAX/js/Lookup.js?v=<?php echo time(); ?>"></script>
<div class="container container-default">
    <?php
    // do not use Google Analytics if on localhost
    if (!isset($_SESSION['localhost'])) {
    ?>
        <h4>LOCALHOST / DEBUG SESSION (DEV)</h4>
        <a target="_blank" href="/localhost">localhost</a>
        -
        <a target="_blank" href="/localhost/s">session JSON</a>
        -
        <a target="_blank" href="/localhost/logs">logs</a>
        <br>
        <hr>
        <br>
    <?php } ?>

    <h3>Website to check:</h3>
    <?php
    $LookupForm = new Lookup\LookupForm\LookupForm;
    $LookupForm->GenerateLookupForm();
    ?>
    <br>
    <hr>
    <h3>The Biggest Database of Websites</h3>
    <p>Instant access to over 1.1 billion websites.</p>
</div>
<?php $Document->RenderFooter(); ?>