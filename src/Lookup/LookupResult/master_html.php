<div class="d-flex align-items-center" style="justify-content:space-between;">
    <div class="d-flex align-items-center"><img style="width: 1.5em; height: 1.5em; margin-inline-end: .5em;" src="https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&size=64&url=http://<?php echo $_SESSION["lookup"]["host"]; ?>" alt="<?php echo $_SESSION["lookup"]["host"]; ?>'s icon">
        <h4><?php echo $_SESSION["lookup"]["host"]; ?></h4>
    </div>
    <div class="site-actions">
        <small>
            <a href="#">Feedback</a>
            -
            <a href="#">Share</a>
        </small>
    </div>
</div>
<hr>
<?php
require_once("html/overview.php")
?>