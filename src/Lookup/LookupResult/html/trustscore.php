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
</div>

  ';
}
?>
<br>
<style>
    .card {
        margin-bottom: 20px;
    }

    .card-header {
        --bs-bg-opacity: .6;
    }
</style>
<?php
// Retrieve trust score from session
$trustScore = $_SESSION["lookup"]["trustScore"];

// Recursive function to generate Bootstrap cards
function generateCards($data)
{
    // Initialize arrays for different score colors
    $goodScoreCategories = array();
    $fairScoreCategories = array();
    $badScoreCategories = array();
    $criticalScoreCategories = array();

    // Sort categories by score
    foreach ($data as $categoryName => $category) {
        $categoryScore = $category['category_score'];

        // Calculate the maxScore
        $maxScoreArray = array_column($category['criterions'], 'maxScore');

        // Check if $maxScoreArray is empty or contains zeros
        if (empty($maxScoreArray) || min($maxScoreArray) == 0) {
            // Handle the error condition, perhaps by skipping this category
            continue;
        }

        $maxScore = array_sum($maxScoreArray);
        $scorePercentage = ($categoryScore / $maxScore) * 100;

        if ($scorePercentage >= 80) {
            $goodScoreCategories[$categoryName] = $category;
        } elseif ($scorePercentage >= 14) {
            $fairScoreCategories[$categoryName] = $category;
        } elseif ($scorePercentage >= 0) {
            $badScoreCategories[$categoryName] = $category;
        } else {
            $criticalScoreCategories[$categoryName] = $category;
        }
    }

    // Display good score categories
    if (!empty($goodScoreCategories)) {
        displayCardGroup($goodScoreCategories, 'Good Score', 'success');
    }

    // Display fair score categories
    if (!empty($fairScoreCategories)) {
        displayCardGroup($fairScoreCategories, 'Fair Score', 'warning');
    }

    // Display bad score categories
    if (!empty($badScoreCategories)) {
        displayCardGroup($badScoreCategories, 'Bad Score', 'danger');
    }

    // Display critical score categories
    if (!empty($criticalScoreCategories)) {
        displayCardGroup($criticalScoreCategories, 'Critical Score', 'danger', true);
    }
}

// Function to display a group of categories as a card
function displayCardGroup($categories, $title, $colorClass, $isCritical = false)
{
    echo '<div class="card">';
    echo '<div class="card-header bg-' . $colorClass . ' text-white">' . $title . '</div>';
    echo '<div class="card-body">';

    // Display criteria for each category in the group
    foreach ($categories as $categoryName => $category) {
        foreach ($category['criterions'] as $criterion => $info) {
            $criterionScore = $info['score'];
            // If score is negative, make it very visible
            if ($criterionScore < 0) {
                echo '<p><strong style="color: red;">' . ucwords(str_replace('_', ' ', $criterion)) . ':</strong> ' . $info['premiumExplanationDetail'] . '</p>';
            } else {
                echo '<p><strong>' . ucwords(str_replace('_', ' ', $criterion)) . ':</strong> ' . $info['premiumExplanationDetail'] . '</p>';
            }
        }
    }

    echo '</div>';
    echo '</div>';
}

// Call the function to generate cards
generateCards($trustScore['category_scores']);
?>