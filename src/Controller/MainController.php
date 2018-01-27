<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    private $confirmationToken = 'b21de22c';

    /**
     * @Route("/main", name="main")
     */
    public function index(Request $request, LoggerInterface $logger)
    {
        $logger->log(100, 'TEST LOG');
        //Ключ доступа сообщества
        $token = 'b4eaa1d7b864626130169665508ba90ba63f22ce23501d8dfe02207766f9f359d9dc22273a132ac176a1b';

        // Secret key
        $secretKey = 'fdueNewmas;qYYnwn';

        //Получаем и декодируем уведомление
        $data = json_decode(file_get_contents('php://input'));

        // проверяем secretKey
        if (0 !== strcmp($data->secret, $secretKey) && 0 !== strcmp($data->type, 'confirmation')) {
            return;
        }

        $logger->log(100, $data->type);

        //Проверяем, что находится в поле "type"
        switch ($data->type) {
            //Если это уведомление для подтверждения адреса сервера...
            case 'confirmation':
                //...отправляем строку для подтверждения адреса
                echo $this->confirmationToken;
                break;

            //Если это уведомление о новом сообщении...
            case 'message_new':
                //...получаем id его автора
                $userId = $data->object->user_id;
                //затем с помощью users.get получаем данные об авторе
                $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&v=5.0"));

                //и извлекаем из ответа его имя
                $user_name = $userInfo->response[0]->first_name;

                //С помощью messages.send и токена сообщества отправляем ответное сообщение
                $request_params = array(
                    'message' => "{$user_name}, ваше сообщение зарегистрировано!<br>" .
                        "Мы постараемся ответить в ближайшее время.",
                    'user_id' => $userId,
                    'access_token' => $token,
                    'v' => '5.0'
                );

                $get_params = http_build_query($request_params);

                file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);

                //Возвращаем "ok" серверу Callback API
                echo('ok');

                break;

            // Если это уведомление о вступлении в группу
            case 'group_join':
                //...получаем id нового участника
                $userId = $data->object->user_id;

                //затем с помощью users.get получаем данные об авторе
                $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&v=5.0"));

                //и извлекаем из ответа его имя
                $user_name = $userInfo->response[0]->first_name;

                //С помощью messages.send и токена сообщества отправляем ответное сообщение
                $request_params = array(
                    'message' => "Добро пожаловать в наше сообщество МГТУ им. Баумана ИУ5 2016, {$user_name}!<br>" .
                        "Если у Вас возникнут вопросы, то вы всегда можете обратиться к администраторам сообщества.<br>" .
                        "Их контакты можно найти в соответсвующем разделе группы.<br>" .
                        "Успехов в учёбе!",
                    'user_id' => $userId,
                    'access_token' => $token,
                    'v' => '5.0'
                );

                $get_params = http_build_query($request_params);

                file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);

                //Возвращаем "ok" серверу Callback API
                echo('ok');

                break;
        }

        return new Response('');
    }
}
