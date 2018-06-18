<?php
  $anno_ref = (isset($anno["anno_ref_id"]) && $anno["anno_ref_id"] !== "") ? true : false;

  if ($anno_ref) {
    # Retrieve referenced annotation
    $ref_anno_json = get_annotation_by_id($anno["anno_ref_id"]);
    $ref_anno = json_decode($ref_anno_json, true);
  }

  if ($anno_ref) {
    $anno_class = (isset($_GET["type"]) && $_GET["type"] === "ref")
      ? "ref"
      : "ref collapsed"
    ;
  }
  else { $anno_class = ""; }
  $id_class = ($anno_ref) ? "de-emphasized" : "";
?>

<div class="row <?php echo $anno_class ?>">
  <!-- Identification and Links -->
  <div class="col-md-3">
    <h5>Letter: <?php echo $anno["pageID"] ?></h5>
    <div class="pull-right buffer-bottom">
      <form action="<?php echo $status_link_url?>/edit.php">
        <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
        <input class="form-control edit" type="submit"
          value="Edit<?php if ($anno_ref) echo " Ref." ?>"/>
      </form>
      <?php echo $tag_html ?>
    </div>

    <p class="<?php echo $id_class ?>">ID: <?php echo $anno["id"] ?></p>
    <?php if (isset($anno["pageID"])): ?>
      <p class="buffer-bottom">
        <a href="<?php echo $boilerplate_url?><?php echo $anno["pageID"]?>.html">Annotate</a>
        |
        <a href="<?php echo $catherletters_url ?>/<?php echo $anno["pageID"] ?>">Cather&nbsp;View</a>
      </p>
    <?php else: ?>
      No links available for nonexistent id
    <?php endif; ?>

    <?php if (!$anno_ref): ?>
      <p class="clearfix pull-right push-down">
        <a class="btn-sm btn-info"
          href="/annotonia_status/search.php?q=<?php
          echo $anno["id"] ?>&type=ref">
          References&nbsp;to&nbsp;this&nbsp;annotation
        </a>
      </p>
    <?php endif; ?>
  </div>

  <!-- Annotation content -->
  <div class="col-md-8">
    <h5>
      Highlight:
      <span class="quote"><?php echo $anno["quote"]?></span>
    </h5>
    <div class="well well-sm">
      <?php
        # Wrap image with link, limit size, display alt text
        echo preg_replace(
          '/<img src="(.+?)" alt="(.*?)"(.*)?>/'
          , <<<END
<div class="container-fluid">
  <a href="$1">
    <img class="img-responsive" src="$1" alt="$2" title="$2"$3>
  </a>
</div>
<span class="text">Alt Text "$2"</span>
END
          , ($anno_ref)
            ? <<<END
<strong>
  <u>Reference to Annotation $ref_anno[id]:</u>
</strong>
<a class="btn-sm btn-primary"
  href="/annotonia_status/edit.php?id=$ref_anno[id]">
  Edit Orig.
</a><br>
<br>
$ref_anno[text]
END
            : $anno["text"]
        );
      ?>
    </div>
  </div> <!-- /div col-md-8 -->

  <!-- Delete Annotation -->
  <div class="col-md-1">
    <form action="<?php echo $status_link_url?>/delete.php">
      <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
      <input class="form-control delete" type="submit" value="Delete"/>
    </form>
  </div>
</div>

<hr class="<?php echo $anno_class ?>">
