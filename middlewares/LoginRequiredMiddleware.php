<?php

class LoginRequiredMiddleware extends BaseMiddleware{
	public function apply(BaseController $controller, array $context)
    {
				$pdo=$controller->pdo;
        // берем значения которые введет пользователь
        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

				$query = $pdo->prepare("SELECT username, password FROM users WHERE username =:username");
				$query->bindValue("username", $user);
				$query->execute();

				$user_auth_data = $query->fetch();
				$valid_user = $user_auth_data['username'] ?? '';
				$valid_password = $user_auth_data['password'] ?? '';

        // сверяем с корректными
        if ($user_auth_data || ($valid_user != $user || $valid_password != $password)) {
            // если не совпали, надо указать такой заголовок
            // именно по нему браузер поймет что надо показать окно для ввода юзера/пароля
            header('WWW-Authenticate: Basic realm="Space objects"');
            http_response_code(401); // ну и статус 401 -- Unauthorized, то есть неавторизован
            exit; // прерываем выполнение скрипта
        }
    }
}