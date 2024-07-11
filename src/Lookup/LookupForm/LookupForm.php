<?php

namespace Lookup\LookupForm;

/**
 * The LookupForm class provides functionality to render a lookup form with CSRF protection.
 */
class LookupForm
{
  /**
   * @var string|null $FormValue The value of the form input field.
   */
  public ?string $FormValue = "";

  /**
   * @var string $token CSRF token for form submission protection.
   */
  private $token = "";
  private $conn;
  private $history;
  private $recent_lookups;

  /**
   * Constructor method for initializing the LookupForm object.
   */
  public function __construct()
  {
    // Check if the query parameter is set in the GET request and assign its value to $this->FormValue
    if (isset($_GET["q"]) && !empty($_GET["q"])) {
      $this->FormValue = urldecode($_GET["q"]);
    }

    // Generate a CSRF token using the CSRF class from the Auth namespace
    $csrfToken = new \Document\CSRF();
    $this->token = $csrfToken->GenerateToken();

    // Create an instance of the Database class
    $database = new \Document\Database();

    // Obtain a database connection
    $this->conn = $database->Connect();
/*

    $archive_select = $this->conn->prepare("SELECT * FROM (SELECT * FROM lookups ORDER BY RAND()) AS lookups_archive GROUP BY host ORDER BY RAND() LIMIT 10");
    $archive_select->execute();
    $archive_result = $archive_select->get_result();
    $archive_rows = $archive_result->fetch_all(MYSQLI_ASSOC);

    if ($archive_result->num_rows > 0) {
      $country = strtoupper($_SESSION["document"]["geo"]["country"]);

      // Check if the country is UNKNOWN
      if ($country === "UNKNOWN") {
        $country = "AROUND THE WORLD";
      }

      $recent_lookups = '<small>RECENT LOOKUPS FROM ' . $country . '</small><br>';

      foreach ($archive_rows as $archive_row) {
        $host = $archive_row['host'];
        $recent_lookups .= "<a class='btn btn-sm btn-outline-secondary' style='margin: .5em;' href='/?q=$host'><img style='width: 1.1em; height: 1.1em; margin-inline: 0em;' src='https://t1.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&size=64&url=http://$host'> $host</a>";
      }

      $this->recent_lookups = $recent_lookups;
    }

    $archive_select->close();
*/
    $this->conn->close();
  }

  /**
   * Render method to generate and display the HTML markup for the lookup form.
   */
  public function GenerateLookupForm()
  {

    // Output the HTML markup for the form
    echo "<form id='LookupForm'class='' method='post'>
        <div class='input-group mb-3'>
          <input type='hidden' id='token' value='$this->token'></input>
          <input type='text' class='form-control' id='query' value='$this->FormValue' placeholder='https://example.com' aria-label='https://example.com' aria-describedby='button-check'>
          <button class='btn btn-primary' type='submit' id='button-check'>Lookup</button>
        </div>
      
        <div id='StatusDataLookup'></div>
      </form> $this->history<br>$this->recent_lookups<br><br>
    
      ";
  }
}
