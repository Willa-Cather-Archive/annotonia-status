<div class="navbar navbar-default" role="navigation">
  <div class="container-fluid">

    <div class="navbar-header">
      <?php 
        if (preg_match(
          '/annotonia-status\/(?:|index\.php)$/'
          , $_SERVER['SCRIPT_FILENAME']
        ))
          echo '<a class="navbar-brand" href="#">';
        else
          echo '<a class="navbar-brand" href="/annotonia_status/">';
      ?>
        Annotonia
      </a>
    </div>

    <div id="navbar-annotonia">
      <ul class="nav navbar-nav">
        <?php
          if (preg_match(
            '/annotonia-status\/(?:|index\.php)$/'
            , $_SERVER['SCRIPT_FILENAME']
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
            , $_SERVER['SCRIPT_FILENAME']
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
    </div>

  </div>
</div>
