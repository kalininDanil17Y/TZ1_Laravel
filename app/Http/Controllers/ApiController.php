<?php


namespace App\Http\Controllers;


use App\Models\User;
use App\Models\UserTrophie;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    /**
     * Получить всех пользователей
     * @return Application|ResponseFactory|Response
     */
    public function getUsers()
    {
        // Простой
        //return $this->response( User::all() );

        // Сложный
        return $this->response( DB::select("SELECT id,name,last_seen FROM users") );
    }

    /**
     * Получить пользователей которые онлайн
     * @return Application|ResponseFactory|Response
     */
    public function getUsersLogged()
    {
        // Простой способ
        //return $this->response( User::where('last_seen', '>=', now())->get() );

        // Сложный
        return $this->success( DB::select("SELECT id,name,last_seen FROM users WHERE last_seen >= ?", [now()]) );
    }

    /**
     * Получить пользователей у которых нет трофеев
     * @return Application|ResponseFactory|Response
     */
    public function getUsersNoTrophy()
    {
        return $this->success( DB::select("SELECT id,name,last_seen FROM users WHERE id IN ( SELECT user_id FROM user_trophies WHERE (SELECT SUM(count) FROM user_trophies WHERE user_id = users.id) > 0 )") );
    }

    /**
     * @param Request $request
     * @return bool|Application|ResponseFactory|Response
     */
    public function getSumTrophy(Request $request)
    {
        // Проверяем, указан ли ID
        $id = $request->request->getInt('id', 0);

        // Проверяем ID из POST
        $checkId = $this->checkId($id);
        if($checkId !== true)
            return $checkId;

        // Выводим
        return $this->success( DB::select("SELECT SUM(count) as count FROM user_trophies WHERE user_id = :id", ['id' => $id]) );
    }

    /**
     * @param Request $request
     * @return bool|Application|ResponseFactory|Response
     */
    public function addTrophy(Request $request)
    {
        // Проверяем, указан ли ID
        $id = $request->request->getInt('id', 0);

        // Проверяем ID из POST
        $checkId = $this->checkId($id);
        if($checkId !== true)
            return $checkId;

        if (! DB::insert("INSERT INTO user_trophies (user_id, count) VALUES (:id, :count)", ['id' => $id, 'count' => 1]) )
            return $this->error();

        return $this->success();
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function delTrophy(Request $request)
    {
        // Проверяем, указан ли ID
        $id = $request->request->getInt('id', 0);

        // Проверяем ID из POST
        if (!$id)
            return $this->error('Empty ID');

        // Существует ли пользователь?
        if (!User::where('id', $id)->first())
            return $this->error('Transaction not found');

        if (! DB::delete("DELETE FROM user_trophies WHERE id = :id", ['id' => $id]) )
            return $this->error();

        return $this->success();
    }

    /**
     * // Проверяем ID из POST
     * @param int $id
     * @return bool|Application|ResponseFactory|Response
     */
    private function checkId(int $id)
    {
        if (!$id)
            return $this->error('Empty ID');

        // Существует ли пользователь?
        if (!User::where('id', $id)->first())
            return $this->error('User not found');

        return true;
    }

    /**
     * @param $message
     * @return Application|ResponseFactory|Response
     */
    private function error($message = null)
    {
        $data = [];
        if ($message != null)
            $data['message'] = $message;

        return $this->response('error', $data);
    }

    /**
     * @param $data
     * @return Application|ResponseFactory|Response
     */
    private function success($data = null)
    {
        if ($data == null)
            $data = [];

        return $this->response('success', $data);
    }


    /**
     * Создание JSON ответа
     * @param $data
     * @return Application|ResponseFactory|Response
     */
    private function response($status, $data)
    {
        return response(json_encode(['status' => $status, 'data' => $data], JSON_UNESCAPED_UNICODE))->header('Content-Type', 'application/json');
    }
}
