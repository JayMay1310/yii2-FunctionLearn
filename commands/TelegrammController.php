<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Functions;
use app\models\Category;
use yii\db\Expression;

use TelegramBot\Api\BotApi;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TelegrammController extends Controller
{

    public function actionFunctionCss()
    {
        $category = Category::findOne(['title' => 'CSS свойства']);
        $query = Functions::find()->orderBy(['rand()' => SORT_DESC, new \yii\db\Expression('last_update IS NULL ASC')])
                                                ->where(['is', 'last_update', new \yii\db\Expression('null')])
                                                ->orWhere(['=', 'category_id', $category->id])->addOrderBy([
                                                    'last_update' => SORT_ASC,
                                                  ])->limit(1)->asArray()->all();


        $messageText = '';
        foreach ($query as $model) 
        {
            $function_item = "{$model['function']} - {$model['value']}" . ";\n";
            $messageText = $messageText . $function_item;

            $model = Functions::findOne((int)$model['id']);//make a typecasting
            if ($model != null)
            {
                $model->last_update = new Expression('NOW()');
                $model->save(false); 
            }
        }

        echo $messageText;                                       
        $chatId = '-1001408119845';

        $bot = new BotApi('970747361:AAHo0ZxfAlAPwoBgE71lEX6YPq-j-6CyAfk');
            // Set webhook

        //$bot->setProxy('root:6zd4{k879B8$@195.161.41.150:3128');
        $bot->sendMessage($chatId, $messageText, 'HTML');       
    }  
}
