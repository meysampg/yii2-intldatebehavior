Yii2 IntlDateBehavior
==================
**Automatic Change Date System of an ActiveRecord After finding It.** Actually it converts date and time system just on representation and keep your model clean. So if you need a convertor for change time and save it on DB, you can use [IntlDate](https://github.com/meysampg/intldate) trait ;).

## Installation


The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require meysampg/yii2-intldatebehavior "*"
```

or add

```json
"meysampg/yii2-intldatebehavior": "*"
```

to the require section of your `composer.json` file.


## Usage


Once the extension is installed, simply use it in your code by attaching it as a behavior to your ActiveRecord model:

```php
public function behaviors()
{
    return [
        IntlDateBehavior::className(),
    ];
}
```
For default, behavior try to show `created_at` and `updated_at` in your desired manner which both of them are `timestamp`. If you wanna select other attributes, you can assign them to `timestampAttributes` property.

## Configuration

Configuration of this trait can be on two way. The first one is using Yii2 configuration array, for each model that `IntlDateBehavior` is attached, configure behavior, for example:

```php
public function behaviors()
{
    return [
        [
            'class' => IntlDateBehavior::className(),
            'timestampAttributes' => ['create_time', 'update_time', 'another_time'],
            'calendar' => 'persian',
            'format' => 'php:d F Y، H:m:i',
            'locale' => 'fa',
        ],
    ];
}
```

This is a local configuration. If you want to use this behavior in multiple model, you can add it as a behavior to model:

```php
public function behaviors()
{
    return [
        IntlDateBehavior::className(),
    ];
}
```

and put the configurations in `params.php` file. As an example, here is my `params`:

```php
<?php

return [
    // some params are here
    'dateTimeFormat' => 'yyyy/MM/dd, HH:mm:ss',
    'dateTimeCalendar' => 'persian',
    'dateTimeLocale' => 'fa',
];

```

In this way you must assign value to `'dateTimeFormat'` for date time format, `'dateTimeCalendar'` calendar system and `'dateTimeLocale'` for locale of showing date time information.

### Supported Calendar
Thanks to [intldate](https://github.com/meysampg/intldate) and `intl` extension of php, this behavior supports this calendar:

 - persian
 - gregorian
 - japanese
 - buddhist
 - chinese
 - indian
 - islamic
 - hebrew
 - coptic
 - ethiopic

## Contributions
Report bugs or do your modification and send a pull request!
