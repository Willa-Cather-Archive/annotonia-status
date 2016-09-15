<?php
  include "env/config.php";
  include "lib/helpers.php";
?>

<html>
  <?php include "layout/head.html"; ?>

  <body>
    <?php include "layout/navbar.php"; ?>
    <div class="container">
      <h4>Letters</h4>

      <div class="container-fluid">
      <?php 
        // GET a request to the flask url for the requested tag (or no tags if all annotations)
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $flask_url."/search?limit=2000");
        // Set user agent to not trigger mod_security rule for no user agent
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (curl; Linux x86_64; Annotonia Status)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl); 
        curl_close($curl);

        // organize the annotation responses by letter
        $annotations = json_decode($res, true);
        $anno_length = count($annotations["rows"]);

        $anno_letters = array();
        for ($i = 0; $i < $anno_length; $i++) {
          $anno = $annotations["rows"][$i];
          if (isset($anno_letters[$anno["pageID"]])) {
            array_push($anno_letters[$anno["pageID"]], $anno);
          } else {
            $anno_letters[$anno["pageID"]] = array($anno);
          }
        }

        $files = scandir($catherletters_dir);

        foreach ($files as $file) {
          $xml = simplexml_load_file($catherletters_dir . $file);
          $title = $xml->teiHeader->fileDesc->titleStmt->title;
          $id = $xml->teiHeader->fileDesc->publicationStmt->idno;
          if (preg_match("/cat\.let[0-9]{4}/i", $id)) {
            $id = str_replace("cat.", "", $id);

            echo <<<END
	<div class="row">
	  <h4 class="pull-left"><strong>$id:&nbsp;</strong></h4>
	  <h4><a href="$boilerplate_url$id.html">$title</a></h4>
END;

            $annotations = $anno_letters[$id];
            $anno_count = count($annotations);
            $tags = array();

            // TODO there must be a way to do an array_map but it's not working
            for ($i = 0; $i < $anno_count; $i++) {
              foreach ($annotations[$i]["tags"] as $tag) {
                array_push($tags, $tag);
              }
            }

            echo <<<END
	  <div class="col-md-2">
	    <a href="$catherletters_url$id.html">Cather View</a>
	  </div>
	  <div class="col-md-2">
	    $anno_count annotation(s)
	  </div>
	  <div class="col-md-8">
END;
    
            echo generate_tags(array_unique($tags));
            echo <<<END
	  </div>
	</div>
	<hr>
END;
          }
        }
      ?>
      </div>
    </div>
  </body>
</html>
