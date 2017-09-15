<div class="row">

  <!-- Identification and Links -->
  <div class="col-md-3">
    <h5>Letter: <?php echo $anno["pageID"] ?></h5>
    <div class="pull-right">
      <form action="<?php echo $status_link_url?>/edit.php">
        <input type="hidden" name="id" value="<?php echo $anno['id']?>"/>
        <input class="form-control edit" type="submit" value="Edit"/>
      </form>
      <?php echo $tag_html ?>
    </div>
    <p>ID: <?php echo $anno["id"] ?></p>
    <?php if (isset($anno["pageID"])): ?>
      <a href="<?php echo $boilerplate_url?><?php echo $anno["pageID"]?>.html">Annotate</a>
      | 
      <a href="http://cather.unl.edu/<?php echo $catherletters_url?><?php echo $anno["pageID"]?>.html">Cather&nbsp;View</a>
    <?php else: ?>
      No links available for nonexistent id
    <?php endif; ?>
  </div>

  <!-- Annotation content -->
  <div class="col-md-8">
    <?php
      $anno_ref = (isset($anno["anno_ref_id"]) && $anno["anno_ref_id"] !== "") ? true : false;

      if ($anno_ref) {
        echo <<<END
    <h4 class="pull-right">Annotation Reference</h4>
END;

        # Retrieve referenced annotation
        $ref_anno_json = get_annotation_by_id($anno["anno_ref_id"]);
        $ref_anno = json_decode($ref_anno_json, true);
      }
    ?>
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
<h5>Annotation $ref_anno[id]:</h5>
$ref_anno[text]
<a href="/annotonia_status/edit.php?id=$ref_anno[id]">&gt;&gt; Edit Referenced Annotation</a>
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
