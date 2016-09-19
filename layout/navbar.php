<div class="navbar navbar-default" role="navigation">
  <div class="container-fluid">

    <div class="navbar-header">
      <?php 
        if (preg_match(
          '/annotonia-status\/(?:|index\.php)$/'
          , $_SERVER["SCRIPT_FILENAME"]
        ))
          echo '<a class="navbar-brand" href="#">';
        else
          echo '<a class="navbar-brand" href="/annotonia_status/">';
      ?>
        Annotonia
      </a>
    </div>

    <div id="navbar-annotonia" class="navbar-collapse">
      <ul class="nav navbar-nav">
        <?php
          if (preg_match(
            '/annotonia-status\/(?:|index\.php)$/'
            , $_SERVER["SCRIPT_FILENAME"]
          ))
            echo <<<END
        <li class="active">
          <a href="#">
END;
          else
            echo <<<END
        <li>
          <a href="/annotonia_status/">
END;
        ?>
            Annotations by Status
          </a>
        </li>
        <?php
          if (preg_match(
            '/annotonia-status\/letters\.php$/'
            , $_SERVER["SCRIPT_FILENAME"]
          ))
            echo <<<END
        <li class="active">
          <a href="#">
END;
          else
            echo <<<END
        <li>
          <a href="/annotonia_status/letters.php">
END;
        ?>
            Letters
          </a>
        </li>
      </ul>
      <form class="navbar-form" action="/annotonia_status/search.php">
        <div class="form-group">
            <input type="text" class="form-control" name="q" placeholder="Annotation or Highlight Text" autofocus="autofocus"
              <?php if (isset($_GET["q"])) echo ' value="'. htmlentities($_GET["q"]) .'"' ?>
            >
            <button type="submit" class="btn btn-default">Search</button>
        </div>
      </form>
    </div>

  </div>
</div>
