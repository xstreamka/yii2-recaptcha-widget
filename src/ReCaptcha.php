<?php
/**
 * @link https://github.com/xstreamka/yii2-recaptcha-widget
 * @copyright Copyright (c) 2020 XStream
 */

namespace xstreamka\recaptcha;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\httpclient\Client;

/**
 * Виджет для проверки спама.
 * Google reCAPTCHA v3.
 *
 * @property string $name
 * @property string $siteApi
 *
 * Class ReCaptcha
 * @package common\widgets
 */
class ReCaptcha extends Widget
{
	public $name;
	// Url подключения.
	public $siteApi;
	// Ключ сайта.
	public static $siteKeyV3;
	// Секретный ключ.
	public static $secretV3;

	public function init()
	{
		self::reCaptchaConfig();
		$this->name = ReCaptchaConfig::NAME;
		$this->siteApi = ReCaptchaConfig::SITE_API . '?render=' . self::$siteKeyV3;
	}

	public function run()
	{
		$view = Yii::$app->view;
		$name = $this->name;
		$siteKeyV3 = self::$siteKeyV3;
		// Подключение.
		$view->registerJsFile($this->siteApi);
		// Скрытие логотипа гугла.
		$view->registerCss('.grecaptcha-badge {visibility: hidden; opacity: 0;}');
		// Скрытое пустое поле, в котором будет токен.
		echo Html::hiddenInput($name);

		$js = <<<JS
// Каждой форме запрашиваем токен.
$('form').each(function (){
    let form = $(this);
    if ($('input[name={$name}]', form).val() === '' && (typeof grecaptcha !== 'undefined')) {
		grecaptcha.ready(function() {
    		grecaptcha.execute('{$siteKeyV3}', {action: 'submit'}).then(function(token) {
    			$('input[name={$name}]', form).val(token);
    		});
		});
	}
});
// Если что-то пошло не так, запрашиваем токен еще раз при работе с формой.
$(document).on('change focus', 'input, textarea', function (){
    let form = $(this).closest('form');
    if ($('input[name={$name}]', form).val() === '') {
		grecaptcha.ready(function() {
    		grecaptcha.execute('{$siteKeyV3}', {action: 'submit'}).then(function(token) {
    			$('input[name={$name}]', form).val(token);
    		});
		});
	}
});
JS;
		$view->registerJs($js);
	}

	/**
	 * Валидация пользовательского токена через гугл.
	 * @return bool
	 */
	public static function validate(): bool
	{
		self::reCaptchaConfig();

		if ($token = Yii::$app->request->post(ReCaptchaConfig::NAME)) {
			$client = new Client();
			$response = $client->createRequest()
				->setMethod('post')
				->setUrl(ReCaptchaConfig::SITE_VERIFY)
				->setData([
					'secret' => self::$secretV3,
					'response' => $token
				])
				->send();
			if ($response->isOk && ($response->data['success'] ?? false)) {
				return true;
			}
		}
		return false;
	}

	private static function reCaptchaConfig()
	{
		$reCaptchaConfig = Yii::$app->get(ReCaptchaConfig::NAME, false);
		self::$siteKeyV3 = $reCaptchaConfig->siteKeyV3;
		self::$secretV3 = $reCaptchaConfig->secretV3;
	}
}