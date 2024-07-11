<?php
$data = $_SESSION["lookup"];
?>
https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&exintro&explaintext&redirects=1&titles=<?php echo $_SESSION["lookup"]["host"]; ?>
<script>
    function copyToClipboard(elementId) {
        const textToCopy = document.getElementById(elementId).value;
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = textToCopy;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextArea);

        const button = document.querySelector(`#${elementId} + .copyb`);
        const originalButtonText = button.innerText;
        button.innerText = 'Copied';

        setTimeout(() => {
            button.innerText = originalButtonText;
        }, 2000);
    }
</script>
<style>
    .shadow-on-hover {
        box-shadow: 0px;
        transition: box-shadow 0.1s ease-in-out;
    }

    .shadow-on-hover:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        transition: box-shadow 0.1s ease-in-out;
    }
</style>
<small class="text-muted d-flex justify-content-between align-items-center">
    <span>Data returned in <?php echo $data["exec_time"]; ?> ms</span>
    <button class="btn btn-sm btn-outline-secondary d-none d-md-inline-block" data-bs-toggle="modal" data-bs-target="#qr_modal">Open in mobile</button>

</small>
<?php if (isset($_SESSION["lookup"]["headers"]["location"])) { ?>
    <hr>
    <div class="alert alert-primary" role="alert">
        Provided link redirects to <?php echo $_SESSION["lookup"]["headers"]["location"]; ?> - <a href="/?q=<?php echo $_SESSION["lookup"]["headers"]["location"]; ?>">Lookup</a>
    </div>
<?php } ?>

<hr>
<div class="position-relative p-2 shadow-on-hover">

    <h4>e-Registrar Trustscore</h4>
    <?php
    $total_score = $_SESSION["lookup"]["trustScore"]["total_score"];

    // Set the color to red if $total_score is 0, otherwise, map it between red and green
    $color = ($total_score === 0) ? 'rgb(255, 0, 0)' : 'rgb(' . (255 * (1 - $total_score / 100)) . ', ' . (255 * ($total_score / 100)) . ', 0)';
    // Set the width to 100% if $total_score is 0, otherwise use $total_score
    $width = ($total_score === 0) ? '100%' : max($total_score, 1) . '%';
    // Set aria-valuenow to 100 if $total_score is 0, otherwise use $total_score
    $ariaValueNow = ($total_score === 0) ? 100 : $total_score;

    // Generate HTML with a color gradient and a Bootstrap progress bar
    $html = "
<div class='d-flex align-items-center p-2 border rounded' style='justify-content: space-between; height: 100px;'>
    <h2 style='margin-right: 1em; color: $color; white-space: nowrap;'>$total_score%</h2>
    <div class='progress flex-grow-1' style='width: 100%;'>
        <div class='progress-bar' role='progressbar' style='width: $width; background-color: $color;' aria-valuenow='$ariaValueNow' aria-valuemin='0' aria-valuemax='100'></div>
    </div>
</div>";

    echo $html;

    if ($total_score === 0) {
        echo '<br><div class="alert alert-danger" role="alert">
    <strong>Watch out!</strong> We found a critical issue that requires immediate attention.
</div>';
    }
    ?>
    <center>
        <a href="/lookup?q=<?php echo $data["query"] ?>&tab=trustscore" class="fs-5 stretched-link">Open Trustscore</a>
    </center>
</div>
<hr>
<div class="position-relative p-2 shadow-on-hover">

    <h4>Registrar information</h4>
    <div class="p-2 border rounded">

        <a target="_blank" href="http://<?php echo $_SESSION["lookup"]["host"]; ?>">
            <h6 class="text-primary"><img style="width: 1.5em; height: 1.5em; margin-inline-end: .5em;" src="https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&size=64&url=http://<?php echo $_SESSION["lookup"]["host"]; ?>" alt="<?php echo $_SESSION["lookup"]["host"]; ?>'s icon"><u><?php echo $_SESSION["lookup"]["host"]; ?></u></h6>
        </a>
        <?php
        $whois = $_SESSION["lookup"]["whois"];
        if (!empty($whois["registrant"]["organization"])) {
            echo "<h6>" . $whois["registrant"]["organization"] . "</h6>";
        } elseif (!empty($whois["registrant"]["name"])) {
            echo "<h6>" . $whois["registrant"]["name"] . "</h6>";
        }
        echo "<h6>" . (!empty($_SESSION["lookup"]["whois"]["domain"]["created_date"]) ? date("d.m.Y.", strtotime($_SESSION["lookup"]["whois"]["domain"]["created_date"])) : "Date of registration not set") . "</h6>";
        echo "<h6><a href='#'>Contact</a></h6>";
        ?>
    </div>
    <center>
        <a href="/lookup?q=<?php echo $data["query"] ?>&tab=ownership" class="fs-5 stretched-link">View records</a>
    </center>
</div>
<hr>
<div class="position-relative p-2 shadow-on-hover">
    <h4>Nameservers</h4>
    <div class="p-2 border rounded">

        <?php
        $nameservers = $data["dns"]["NS"];

        if (!empty($nameservers)) {
            foreach ($nameservers as $index => $ns) {
                $nsId = 'ns_' . ($index + 1);
                echo '
        <div class="mb-3">
            <label for="' . $nsId . '" class="form-label">Nameserver ' . ($index + 1) . ':</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control disabled" id="' . $nsId . '" disabled value="' . $ns["target"] . '">
                <button class="btn btn-outline-secondary copyb" type="button" onclick="copyToClipboard(\'' . $nsId . '\')">Copy</button>
            </div>
        </div>
    ';
            }
        } else {
            echo '<p>No nameservers available for this record.</p>';
        }
        ?>
    </div>
    <center>
        <a href="/lookup?q=<?php echo $data["query"] ?>&tab=dns" class="fs-5 stretched-link">Open DNS</a>
    </center>
</div>
<hr>
<div class="position-relative p-2 shadow-on-hover">
    <h4>Server</h4>
    <div class="p-2 border rounded">

        <?php
        $nameservers = $data["dns"]["NS"];
        ?>
        <?php if (!empty($data["geo"]["ip"])) { ?>
            <label for="ip" class="form-label">IP address:</label>
            <div class="input-group input-group-sm mb-1">
                <input type="text" class="form-control disabled" id="ip" disabled value="<?php echo $data["geo"]["ip"]; ?>">
                <button class="btn btn-outline-secondary copyb" type="button" onclick="copyToClipboard('ip')">Copy</button>
            </div>
        <?php } ?>

        <?php if (!empty($data["geo"]["reverse"])) { ?>
            <label for="reverse" class="form-label">Reversed value:</label>
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="form-control disabled" id="reverse" disabled value="<?php echo $data["geo"]["reverse"]; ?>">
                <button class="btn btn-outline-secondary copyb" type="button" onclick="copyToClipboard('reverse')">Copy</button>
            </div>
        <?php } ?>

        <?php if (!empty($data["geo"]["country"]) && !empty($data["geo"]["city"])) { ?>
            <b>Server location: </b><?php echo "<a href='#' data-bs-toggle='modal' data-bs-target='#map_modal'class='text-body-secondary'><i class='bi bi-geo-alt'></i> " . $data["geo"]["country"] . ", " . $data["geo"]["city"] . "</a>"; ?><br>
        <?php } ?>
        <?php if (!empty($data["geo"]["isp"])) { ?>
            <b>ISP: </b><?php echo $data["geo"]["isp"]; ?><br>
        <?php } ?>
        <?php if (!empty($data["geo"]["org"])) { ?>
            <b>Org: </b><?php echo $data["geo"]["org"]; ?><br>
        <?php } ?>
        <?php if (!empty($data["geo"]["as"])) { ?>
            <b>AS: </b><?php echo $data["geo"]["as"]; ?>
        <?php } ?>

        <?php if (empty($data["geo"]["ip"]) && empty($data["geo"]["reverse"]) && empty($data["geo"]["country"]) && empty($data["geo"]["city"]) && empty($data["geo"]["isp"]) && empty($data["geo"]["org"]) && empty($data["geo"]["as"])) { ?>
            This domain is not on any publicly available server.
        <?php } ?>

    </div>
    <center>
        <a href="/lookup?q=<?php echo $data["query"] ?>&tab=server-map" class="fs-5 stretched-link">Open on map</a>
    </center>
</div>
<hr>
<div class="position-relative p-2 shadow-on-hover">
    <h4>Security certificate</h4>
    <div class="p-2 border rounded">

        <?php
        $ssl = $data["ssl"];

        if (!empty($ssl)) { ?>
            <?php if (!empty($ssl["subject"]["CN"])) { ?>
                <b>Issued for: </b><?php echo $ssl["subject"]["CN"]; ?><br>
            <?php } ?>
            <?php if (!empty($ssl["issuer"]["O"])) { ?>
                <b>Issuer: </b><?php echo $ssl["issuer"]["O"] . " (" . $ssl["issuer"]["CN"] . ")"; ?><br>
            <?php } ?>
            <br>
            <b>Validity: </b>
            <?php
            $validFrom = DateTime::createFromFormat('ymdHis\Z', $ssl["validFrom"]);
            $validTo = DateTime::createFromFormat('ymdHis\Z', $ssl["validTo"]);

            $validFromReadable = $validFrom ? $validFrom->format('d.m.Y.') : 'Invalid Date';
            $validToReadable = $validTo ? $validTo->format('d.m.Y.') : 'Invalid Date';
            ?>

            <br>
            <style>
                .progress-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .progress-bar-container {
                    width: 100%;
                    margin-top: 10px;
                }

                .progress-labels {
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                    margin-top: 10px;
                }
            </style>
            <div class="progress-container">
                <?php
                // Assuming you have the start and end dates in PHP variables
                $startDate = strtotime($validFromReadable);
                $endDate = strtotime($validToReadable);

                // Current date
                $currentDate = time();

                // Calculate the progress percentage
                $progressPercentage = min(100, max(0, (($currentDate - $startDate) / ($endDate - $startDate)) * 100));

                // Calculate the remaining days until expiration
                $remainingDays = max(0, round(($endDate - $currentDate) / (60 * 60 * 24)));

                // Set color based on remaining days
                $colorClass = '';
                if ($remainingDays > 14) {
                    $colorClass = 'bg-success';
                } elseif ($remainingDays > 7) {
                    $colorClass = 'bg-warning';
                } elseif ($remainingDays > 3) {
                    $colorClass = 'bg-danger';
                } else {
                    $colorClass = 'bg-danger';
                }
                ?>

                <!-- Progress bar container -->
                <div class="progress-bar-container">
                    <!-- Output the progress bar with color, text, and icon -->
                    <div class="progress">
                        <div class="progress-bar <?php echo $colorClass; ?>" role="progressbar" style="width: <?php echo $progressPercentage; ?>%;" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                            <span class="visually-hidden"><?php echo $progressPercentage; ?>% Complete</span>
                        </div>
                    </div>
                </div>

                <!-- Progress labels (start, end, current) -->
                <div class="progress-labels">
                    <p class="mb-0"><strong></strong> <?php echo date('d.m.Y', $startDate); ?></p>
                    <p class="mb-0"><strong></strong> <?php echo date('d.m.Y', $endDate); ?></p>
                </div>
            </div>
            <br>
        <?php } else {
            echo '<p>No SSL available for this record.</p>';
        }
        ?>


    </div>
    <center>
        <a href="/lookup?q=<?php echo $data["query"] ?>&tab=security" class="fs-5 stretched-link">Open security</a>
    </center>
</div>
<hr>
<small class="text-muted">Please be aware that any legal information, including company names and other legal details, provided here does not constitute legal advice.
    All company and product names mentioned may be trademarks of their respective owners.
    Uncuni Technologies is not the owner of the displayed data, and it assumes no responsibility for it. Additionally, Uncuni Technologies is not liable for any actions taken with the provided data. </small>
<br>
<br>
<div class="modal fade" id="qr_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog result_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Open in mobile</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <center>
                    <p>Scan this QR code with your device to open results for "<?php echo $data["host"]; ?>" on your mobile browser.</p>
                    <hr>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo "https://e-registrar.com/?q=" . urlencode($data["query"]); ?>">
                </center>
            </div>
        </div>
    </div>
</div>