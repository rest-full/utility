# Rest-full Utility

## About Rest-full Utility

Rest-full Utility is a small part of the Rest-Full framework.

You can find the application at: [rest-full/app](https://github.com/rest-full/app) and you can also see the framework skeleton at: [rest-full/rest-full](https://github.com/rest-full/rest-full).

## Installation

* Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
* Run `php composer.phar require rest-full/utility` or composer installed globally `compser require rest-full/utility` or composer.json `"rest-full/utility": "1.0.0"` and install or update.

## Usage

This Translator
```
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/pathServer.php';

use Restfull\Utility\Translator;

$translator = new Translator();
echo $translator->singular('dog');
```

or this Icons
```
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/pathServer.php';

use Restfull\Utility\Icon;

$icons = new Icon('favicon.png');
echo $icons->addIco();
```

## License

The rest-full framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
