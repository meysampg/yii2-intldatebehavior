<?php

namespace meysampg\behaviors;

use meysampg\intldate\IntlDateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FormatConverter;

class IntlDateBehavior extends AttributeBehavior
{
    use IntlDateTrait;

    /**
     * @var array|string The array or string contains attributes that are timestamp values and wanna
     * show as a localized date. If nothing set, `created_at` and `updated_at` will be picked.
     */
    public $timestampAttributes = ['created_at', 'updated_at'];

    /**
     * @var string desired calendar for representing date. It can be set directly or with `dateTimeCalendar`
     * of `params` (e.g. `Yii::$app->params['dateTimeCalendar'])`. If none of them is set `gregorian` will be
     * picked.
     */
    public $calendar;

    /**
     * @var string Format for showing date and time. It can be set either with
     *  [ICU formats](http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details)
     * or with traditional php format. For the last, use `php` at start of string (a.e. `'php:d F Y، H:m:i'`). Default value is `'yyyy/MM/dd, HH:mm:ss'`.
     */
    public $format;

    /**
     * @var string The locale for showing i18ned date and time on it. Default value is `en`.
     */
    public $locale;

    /**
     * @var string Timezone for showing date and time.
     */
    public $tz;

    private $calendars = [
        'persian' => 'toPersian',
        'japanese' => 'toJapanese',
        'buddhist' => 'toBuddhist',
        'chinese' => 'toChinese',
        'indian' => 'toIndian',
        'islamic' => 'toIslamic',
        'hebrew' => 'toHebrew',
        'coptic' => 'toCoptic',
        'ethiopic' => 'toEthiopic',
        'gregorian' => 'toGregorian',
    ];

    public function init()
    {
        parent::init();

        $this->initilizeParams();

        if (!is_array($this->timestampAttributes) && !is_string($this->timestampAttributes)) {
            throw new InvalidConfigException('timestampAttributes must be an array or a string');
        }

        if (!is_string($this->calendar) && !array_key_exists($this->calendar, $this->calendars)) {
            throw new InvalidConfigException(
                'calendar value must be a string and be one of "' .
                rtrim(implode(", ", array_keys($this->calendars))) .
                '"'
            );
        }

        $this->attributes = [
            ActiveRecord::EVENT_AFTER_FIND => $this->timestampAttributes,
        ];
    }

    public function getValue($event)
    {
        return $this->fromTimestamp($this->owner->{$event->data})
            ->{$this->calendars[$this->calendar]}($this->locale)
            ->setFinalTimeZone($this->tz)
            ->asDateTime($this->format);
    }

    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param Event $event
     */
    public function evaluateAttributes($event)
    {
        if ($this->owner->isNewRecord) {
            return;
        }

        if (!empty($this->attributes[$event->name])) {
            $attributes = (array) $this->attributes[$event->name];

            foreach ($attributes as $attribute) {
                $event->data = $attribute;
                $value = $this->getValue($event);

                if (is_string($attribute)) {
                    $this->owner->$attribute = $value;
                    $this->owner->setOldAttribute($attribute, $value);
                }
            }
        }
    }

    private function initilizeParams()
    {
        // Initialize date and time format
        if (!strlen($this->format)) {
            if (isset(Yii::$app->params['dateTimeFormat'])) {
                $this->format = Yii::$app->params['dateTimeFormat'];
            } else {
                $this->format = 'yyyy/MM/dd, HH:mm:ss';
            }
        }

        if ('php:' == substr($this->format, 0, 4)) {
            $this->format = FormatConverter::convertDatePhpToIcu(substr($this->format, 4));
        }

        // Initialize calendar
        if (!strlen($this->calendar)) {
            if (isset(Yii::$app->params['dateTimeCalendar'])) {
                $this->calendar = Yii::$app->params['dateTimeCalendar'];
            } else {
                $this->calendar = 'gregorian';
            }
        }

        // Initialize locale
        if (!strlen($this->locale)) {
            if (isset(Yii::$app->params['dateTimeLocale'])) {
                $this->locale = Yii::$app->params['dateTimeLocale'];
            } elseif (!is_null(Yii::$app->language)) {
                $this->locale = Yii::$app->language;
            } else {
                $this->locale = 'en';
            }
        }

        // Initialize timezone
        if (!strlen($this->tz)) {
            if (isset(Yii::$app->params['dateTimeZone'])) {
                $this->tz = Yii::$app->params['dateTimeZone'];
            } else {
                $this->tz = 'UTC';
            }
        }
    }
}
