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
      <a href="<?php echo $catherletters_url?><?php echo $anno["pageID"]?>.html">Cather View</a>
    <?php else: ?>
      No links available for nonexistent id
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
          , $anno["text"]
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
