<?php

session_start();
require_once '../src/ClassLoader.php';

// Redirect if necessary
handleSessionQueryRedirect();

// Setup document properties
$Document = new \Document\Document;
setupDocumentProperties($Document);

// Render the page
$Document->RenderHead();
$Document->RenderNavigation();

$csrfToken = new \Document\CSRF();
$csrfToken = $csrfToken->GenerateToken();

?>
<script async type="application/javascript" src="https://a.magsrv.com/ad-provider.js"></script>
<ins class="eas6a97888e17" data-zoneid="5250602"></ins>
<script>
    (AdProvider = window.AdProvider || []).push({
        "serve": {}
    });
</script>
<style>
    .result-data {
        background-color: rgba(var(--bs-tertiary-bg-rgb)) !important;
        border: 1px solid var(--bs-border-color);
        border-radius: .4em;
        padding: 1em .4em;
    }
</style>
<div class="container container-default">
    <form action="/" method="get">
        <div class="input-group">
            <input type="text" class="form-control" id="q" name="q" value="<?php echo htmlspecialchars(urldecode($_SESSION["lookup"]["query"] ?? ''), ENT_QUOTES); ?>" aria-describedby="button-check" required>
            <button class="btn btn-primary" type="submit" id="button-check">Lookup</button>
        </div>
    </form>

    <br>

    <div class="result-data">
        <?php
        require_once("../src/Lookup/LookupResult/master_html.php");
        ?>
    </div>
</div>
<!--
<div class="container">
    <hr>
    <a href="#" data-bs-toggle="modal" data-bs-target="#FeedbackModal">Feedback</a>
</div>
-->
<?php
$Document->RenderFooter();

function handleSessionQueryRedirect()
{
    $sessionQuery = $_SESSION["lookup"]["query"] ?? '';
    $currentQuery = $_GET["q"] ?? '';

    if (urldecode($sessionQuery) != $currentQuery) {
        $redirectUrl = "/?q=" . urlencode($currentQuery);
        header("Location: " . $redirectUrl);
        exit;
    }
}
function setupDocumentProperties($document)
{
    $host = parse_url($_GET["q"], PHP_URL_HOST);

    $document->setTitle($host);
    $document->setDescription("Explore the 'behind the scenes' of " . $host . ". Check their TrustScore, domain information, security practices, and much more.");
}
