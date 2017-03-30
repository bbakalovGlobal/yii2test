Configuration
-------------

* Add current module to the modules folder
* Add this part of code to your application configuration:

```php
<?php
    ......
    'modules' => [
        'bintime' => [
            'class' => 'frontend\modules\bintime\Bintime',
        ],
    ],
    ......
```
