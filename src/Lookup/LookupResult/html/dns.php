<h4>Domain Name System</h4>
<a wiki-data="DNS" data-bs-toggle="offcanvas" href="#wiki_canvas"><i class="bi bi-question-circle"></i> What is DNS?</a>

<br>
<?php
$dns = $_SESSION["lookup"]["dns"];
?>
<hr>
<?php
if (!empty($_SESSION["lookup"]["geo"]["ip"]) && !empty($_SESSION["lookup"]["geo"]["reverse"])) {
    echo "IP reversed value:<br><span style='white-space: nowrap;
    overflow: auto;
    width: 100%;
    display: block;'><pre>" . $_SESSION["lookup"]["geo"]["ip"] . " <--> " . $_SESSION["lookup"]["geo"]["reverse"] . "</pre></span><hr>";
}
?>


<!-- Search Form -->
<div class="form-group mb-2">
    <label for="category">Filter DNS types:</label>
    <select name="category" id="category" class="form-control">
        <option value="">Show All</option>
        <?php
        // Loop through categories and add options for non-empty categories
        foreach ($dns as $category => $records) {
            // Exclude categories with empty records
            if (!empty($records) && !($category == "DMARC" && count($records[0]) == 0)) {
                echo "<option value='{$category}'>{$category} Records</option>";
            }
        }
        ?>
    </select>
</div>
<br>
<style>
    .dns-list {
        list-style-type: none;
        padding-left: 0;
        white-space: nowrap;
    }

    .dns-list .category {
        margin-bottom: 20px;
    }

    .dns-list h5 {
        margin-top: 0;
    }

    .dns-list ul {
        list-style-type: none;
        padding-left: 0;
    }

    .dns-list li {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid var(--bs-border-color);
        border-radius: 5px;
        background-color: var(--bs-body-bg);
        overflow: auto;
    }

    .dns-list .record-key {
        font-weight: bold;
    }

    .dns-list .record-value {
        margin-left: 10px;
    }

    .dns-list .txt-overflow {
        max-height: 100px;
        overflow: auto;
        padding: 5px;
        border: 1px solid var(--bs-border-color);
        border-radius: 5px;
        background-color: var(--bs-body-bg);
    }
</style>
<?php
// Preprocess the data to exclude "class" and "type" key-value pairs
$filteredDns = [];
foreach ($dns as $category => $records) {
    $filteredRecords = [];
    foreach ($records as $record) {
        $filteredRecord = [];
        foreach ($record as $key => $value) {
            if (is_string($value) && $key !== 'class' && $key !== 'type') {
                $filteredRecord[$key] = $value;
            }
        }
        if (!empty($filteredRecord)) {
            $filteredRecords[] = $filteredRecord;
        }
    }
    if (!empty($filteredRecords)) {
        $filteredDns[$category] = $filteredRecords;
    }
}
?>

<ul class="dns-list">
    <?php foreach ($filteredDns as $category => $records) : ?>
        <li class="category <?= $category ?>">
            <h5><?= $category ?> Records:</h5>
            <ul>
                <?php foreach ($records as $record) : ?>
                    <li>
                        <?php foreach ($record as $key => $value) : ?>
                            <span class="record-key"><?= ucfirst($key) ?>:</span>
                            <span class="record-value"><?= $value ?></span><br>
                        <?php endforeach; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>
<script>
    $(document).ready(function() {
        // Event listener for category selection
        $('#category').change(function() {
            var selectedCategory = $(this).val();

            // Show or hide categories based on selection
            if (selectedCategory === '') {
                $('.dns-list .category').show();
            } else {
                $('.dns-list .category').hide();
                $('.dns-list .' + selectedCategory).show();
            }
        });
    });
</script>