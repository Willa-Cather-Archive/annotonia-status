<?php
  include "env/config.php";
  include "lib/helpers.php";
?>

<html>
  <?php include "layout/head.html"; ?>
  <body>
    <?php include "layout/navbar.php"; ?>
    <div class="container">
      <h4>Annotonia Search</h4>

      <div class="results">
        <?php 
          // GET a request to the flask url for the requested tag (or no tags if all annotations)
          $curl = curl_init();

          $url = (isset($_GET["q"]) && $_GET["q"] !== "")
            ? "$flask_url/search_raw?size=2000&q=ids_quote_and_text:". rawurlencode($_GET["q"])
            : "$flask_url/search_raw?size=2000&q=*"
          ;

          curl_setopt($curl, CURLOPT_URL, $url);
          // Set user agent to not trigger mod_security rule for no user agent
          curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $res = curl_exec($curl); 
          curl_close($curl);

          // Parse json and display results
          $annotations = json_decode($res, true);
        ?>
        <h5>
          <?php echo $annotations["hits"]["total"] ?>
          result(s) found for search:
          <?php echo $_GET["q"] ?>
        </h5>
        <hr>
        <div>
          <?php for ($i = 0; $i < $annotations["hits"]["total"]; $i++): ?>
            <?php
              $anno = $annotations["hits"]["hits"][$i]["_source"];
              $anno["id"] = $annotations["hits"]["hits"][$i]["_id"];
              $tag_html = generate_tags($row["tags"]);
              include "includes/annotation.php";
            ?>
            <hr>
          <?php endfor ?>
        </div>
      </div>
    </div>
  </body>
</html>
