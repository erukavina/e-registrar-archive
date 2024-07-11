<div class="container-fluid mt-5" style="background-color:rgba(var(--bs-tertiary-bg-rgb)) !important;border-top:var(--bs-border-width) solid var(--bs-border-color);">
    <div class="container">
        <footer class="py-3 mt-4">
            <center>
                <?php
                 if($_SESSION["document"]["geo"]["country"] == "UNKNOWN"){
                    $geo ="";
                }else{
                    $geo = ", ".$_SESSION["document"]["geo"]["country"];
                }
                if ($_SESSION["document"]["device_type"] == "computer") {
                   
                    echo '<i class="bi bi-laptop"></i> ' . $_SESSION["document"]["device_info"] . $geo;
                } else {
                    echo '<i class="bi bi-phone"></i> ' . $_SESSION["document"]["device_info"] . $geo;
                }
                ?>
            </center>
            <hr>
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="/" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a target="_blank" href="https://developers.e-registrar.com" class="nav-link px-2 text-body-secondary">Developers</a></li>
                <li class="nav-item"><a href="/changelog" class="nav-link px-2 text-body-secondary">Changelog</a></li>
                <li class="nav-item"><a href="#" id="open_preferences_center" class="nav-link px-2 text-body-secondary">Cookie preferences</a></li>
                <li class="nav-item"><a href="/privacy" class="nav-link px-2 text-body-secondary">Privacy</a></li>
                <li class="nav-item"><a href="/terms" class="nav-link px-2 text-body-secondary">Terms</a></li>
            </ul>
            <center> 
                <small class="text-center text-body-secondary">&copy; <?php echo date("Y") ?> e-Registrar.com</small>
            </center>
        </footer>
    </div>
</div>