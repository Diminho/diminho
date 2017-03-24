# Instagram PHP SDK

Test task for application on a job

## Installation

You can download SDK via `composer`
```
$ composer require diminho/diminho=dev-master
```
### Config
There are several templates of a config file (config_default) in config folder. Rename it to config.[ext]. Only one config file should be active at a time. Config may be of PHP or JSON fomat. Access token can be set via config file or programmatically via SDK\Auth class.

### Usage

Autoloading. Import in your script
```
require __DIR__ . './vendor/autoload.php';
```

Initialization

```
$sdk = new SDK\InstaSDK();
```
Get a user by ID 

```
$response = $sdk->getUserById(1190990766);
```
`$response` it is an object of a Response class

### API List

USER section:
* getUserById($userID); -- GET
* getMediaByShortcode($shortcode); --GET
* getSearchUserByUsername($$username, [ $params] ); --GET

MEDIA Section
* getMediaById($mediaId) -- GET
* getUserById($userID); -- GET
* getMediaByArea($params); -- GET (Here $params must be "lat" and "lng")

LIKES Section
* postSetLikeMedia($mediaId) -- POST
* getLikesByMedia($mediaId); -- GET
* delUnsetLikesByMedia($mediaId); -- DELETE


