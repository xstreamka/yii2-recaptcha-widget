<?php
/**
 * @link https://github.com/xstreamka/yii2-recaptcha-widget
 * @copyright Copyright (c) 2020 XStream
 */

namespace xstreamka\recaptcha;

/**
 * Class ReCaptchaConfig
 * @package common\widgets
 */
class ReCaptchaConfig
{
	const NAME = 'reCaptcha';
	// Url подключения.
	const SITE_API = 'https://www.google.com/recaptcha/api.js';
	// Url проверки токена.
	const SITE_VERIFY = 'https://www.google.com/recaptcha/api/siteverify';

	// Ключ сайта.
	public $siteKeyV3;
	// Секретный ключ.
	public $secretV3;
}