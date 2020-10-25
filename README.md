Google reCAPTCHA v3 widget for Yii2
===================
Spam check widget for Yii2, based on Google reCAPTCHA API v3.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xstreamka/yii2-recaptcha-widget "*"
```

or add

```
"xstreamka/yii2-recaptcha-widget": "*"
```

to the require section of your `composer.json` file.

Further:
* [Sign up for an reCAPTCHA API keys.](https://www.google.com/recaptcha/admin/create)
* Configure the component in your configuration file (`frontend/config/main.php`):
```php
'components' => [
    ...
    'reCaptcha' => [
        'class' => 'xstreamka\recaptcha\ReCaptchaConfig',
        'siteKeyV3' => 'your siteKey v3',
        'secretV3' => 'your secret key v3',
    ],
    ...
]
```

Usage
-----

Once the extension is installed, simply use it in your code by:

```php
<?php $form = ActiveForm::begin(); ?>
...
<?= \xstreamka\recaptcha\ReCaptcha::widget(); // added hidden input ?>
...
<?php ActiveForm::end(); ?>
```
this will add a hidden field to your form.

Validate
-----

For form validation use:

```php
<?= \xstreamka\recaptcha\ReCaptcha::validate(); ?>
```
this is called after POST request.

Example
-----

```php
$model = new QuestionForm();
if ($model->load(Yii::$app->request->post()) && $model->validate() && \xstreamka\recaptcha\ReCaptcha::validate()) {
...
}
```