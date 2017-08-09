# annotonia-status
PHP viewer for the status of [Annotonia](https://github.com/Willa-Cather-Archive/annotonia) annotations

## Config
Drop this repository into your web tree where PHP is executed

Copy the config file template `env/config_example.php` to `env/config.php` and modify as desired to set:

|Config Variable|Description|
|-|-|
|`$boilerplate_url`|URL-prefix to documents|
|`$catherletters_dir`|Filesystem path to documents|
|`$catherletters_url`|URL-prefix to documents in production view|
|`$flask_url`|URL to the Annotator-Store API|
|`$status_link_url`|URL to annotonia-status root|

Other variables' purposes are described in the comments
