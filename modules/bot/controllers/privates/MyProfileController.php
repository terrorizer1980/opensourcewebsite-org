<?php

namespace app\modules\bot\controllers\privates;

use Yii;
use \app\modules\bot\components\response\SendMessageCommand;
use \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use app\modules\bot\components\Controller as Controller;
use app\models\Currency;
use app\models\Language;
use app\components\helpers\TimeHelper;

/**
 * Class MyProfileController
 *
 * @package app\modules\bot\controllers
 */
class MyProfileController extends Controller
{
    /**
     * @return array
     */
    public function actionIndex()
    {
        $telegramUser = $this->getTelegramUser();
        $user = $this->getUser();
        $timezones = TimeHelper::timezonesList();

        $currencyCode = $telegramUser->currency_code;
        $currencyName = Currency::findOne(['code' => $currencyCode])->name;

        $languageCode = $telegramUser->language_code;
        $languageName = Language::findOne(['code' => $languageCode])->name;
        $languageCode = strtoupper($languageCode);

        $params = [
            'firstName' => $telegramUser->provider_user_first_name,
            'lastName' => $telegramUser->provider_user_last_name,
            'username' => $telegramUser->provider_user_name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
            'currency' => "$currencyName ($currencyCode)",
            'language' => "$languageName ($languageCode)",
            'timezone' => $timezones[$user->timezone],
        ];

        return [
            new SendMessageCommand(
                $this->getTelegramChat()->chat_id,
                $this->render('index', $params),
                [
                    'parseMode' => $this->textFormat,
                    'replyMarkup' => new InlineKeyboardMarkup([
                        [
                            [
                                'callback_data' => MyLocationController::createRoute(),
                                'text' => Yii::t('bot', 'Location'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MyTimezoneController::createRoute(),
                                'text' => Yii::t('bot', 'Timezone'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MyGenderController::createRoute(),
                                'text' => Yii::t('bot', 'Gender'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MyBirthdayController::createRoute(),
                                'text' => Yii::t('bot', 'Birthday'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MyCurrencyController::createRoute(),
                                'text' => Yii::t('bot', 'Currency'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MyEmailController::createRoute(),
                                'text' => Yii::t('bot', 'Email'),
                            ],
                        ],
                        [
                            [
                                'callback_data' => MenuController::createRoute(),
                                'text' => '📱',
                            ],
                        ],
                    ]),
                ]
            ),
        ];
    }
}