Yii2 IntlDateBehavior
==================
**Automatic Change Date System of an ActiveRecord After finding It.** Actually it converts date and time system just on representation and keep your model clean. So if you need a converter for change time and save it on DB, you can use [IntlDate](https://github.com/meysampg/intldate) trait ;).

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


Once the extension is installed, simply use it in your class by `use meysampg\behaviors\IntlDateBehavior` and use it by attaching it as a behavior to your ActiveRecord model:

```php
public function behaviors()
{
    return [
        IntlDateBehavior::className(),
    ];
}
```
**Also in your model, you must change the rule of `timestamp` fields from `integer` to `safe`.**

For default, behavior try to show `created_at` and `updated_at` in your desired manner which both of them have `timestamp` type. If you wanna select other attributes, you can assign them as an array (a.e. `['time1', 'time2', 'time3']`) to `timestampAttributes` property.

## Configuration

Configuration of this behavior can be on two way. The first one is using Yii2 configuration array, for example:

```php
public function behaviors()
{
    return [
        [
            'class' => IntlDateBehavior::className(),
            'timestampAttributes' => ['create_time', 'update_time', 'another_time'],
            'calendar' => 'persian',
            'format' => 'php:d F Y، H:i:s',
            'locale' => 'fa',
            'tz' => 'Asia/Tehran'
        ],
    ];
}
```

This is a local configuration. The second way is when you want to use this behavior in multiple model with same configurations, so you should add it as a behavior to model:

```php
public function behaviors()
{
    return [
        IntlDateBehavior::className(),
    ];
}
```

and put the configurations in `params.php` file. As an example, here is my `params.php`:

```php
<?php

return [
    // some params are here
    'dateTimeFormat' => 'yyyy/MM/dd, HH:mm:ss',
    'dateTimeCalendar' => 'persian',
    'dateTimeLocale' => 'fa',
    'dateTimeZone' => 'Asia/Tehran',
];

```

In this way you must assign a value to `'dateTimeFormat'` for date time format, `'dateTimeCalendar'` calendar system, `'dateTimeLocale'` for locale of showing date time information and `'dateTimeZone'` for timezone of region where datetime must be shown.

### Supported Calendar
Thanks to [intldate](https://github.com/meysampg/intldate) and `intl` extension of php, this behavior supports these calendars:

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
