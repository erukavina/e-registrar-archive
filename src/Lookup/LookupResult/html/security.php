<h4>SSL / TLS & Headers</h4>
<a wiki-data="Transport_Layer_Security" data-bs-toggle="offcanvas" href="#wiki_canvas"><i class="bi bi-question-circle"></i> What is SSL / TLS?</a>
<?php
$ssl = $_SESSION["lookup"]["ssl"];
$headers = $_SESSION["lookup"]["headers"];

// Function to format "Array" values
function formatArray($array)
{
    if (is_array($array)) {
        return '<pre>' . htmlspecialchars(json_encode($array, JSON_PRETTY_PRINT)) . '</pre>';
    } else {
        return $array;
    }
}

// Helper function to format date and time
function formatDateTime($key, $value)
{
    if (in_array($key, ['validFrom', 'validTo'])) {
        // Assuming the value is in Zulu time format
        $date = DateTime::createFromFormat('ymdHis\Z', $value, new DateTimeZone('UTC'));
        return $date ? $date->format('Y-m-d H:i:s T') : $value;
    } elseif (in_array($key, ['validFrom_time_t', 'validTo_time_t'])) {
        // Assuming the value is a UNIX timestamp
        return date('Y-m-d H:i:s T', $value);
    }
    return $value; // Return the original value if no formatting is needed
}

?>
<div class="card my-3">
    <div class="card-body" style="overflow: auto;">
        <h5 class="card-title">SSL / TLS record</h5>
        <span>(TLS) is a cryptographic protocol designed to provide communications security over a computer network.</span>
        <br>
        <br>
        <table class="table table-bordered">
            <tbody>
                <?= displayTable($ssl) ?>
            </tbody>
        </table>
    </div>
</div>

<?php
function displayTable($array)
{
    $html = '';
    foreach ($array as $key => $value) {
        $html .= '<tr>';
        $html .= '<th>' . htmlspecialchars($key) . '</th>';
        $html .= '<td>';
        if (is_array($value)) {
            // Use formatArray function for arrays
            $html .= formatArray($value);
        } else {
            // Use formatDateTime for date/time values
            $formattedValue = formatDateTime($key, $value);
            $html .= htmlspecialchars($formattedValue);
        }
        $html .= '</td>';
        $html .= '</tr>';
    }
    return $html;
}

?>


<div class="card my-3">
    <div class="card-body">
        <h5 class="card-title" id="headers">Headers</h5>
        <span>HTTP headers are key-value pairs in web communications that guide data processing between browsers and servers.</span>
        <br>
        <br>
        <pre class="border rounded" style='background-color: var(--bs-body-bg);padding: 1em;overflow: auto;max-height: 20em;'>
<?php
if (!empty($headers)) {
    echo htmlspecialchars(json_encode($headers, JSON_PRETTY_PRINT));
} else {
    echo "Headers data not provided";
}
?></pre>


    </div>
</div>