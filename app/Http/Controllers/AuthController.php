<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * 
     * @param Request $request
     * @return array
     * @throws ValidationException
     * 
    */
    public function isRegisterValid(Request $request)
    {
        return $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|App\Traits\Iluminate\Http\JsonResponse|void
     * @throws ValidationException
     * 
    */
    public function register(Request $request)
    {
        if($this->isRegisterValid($request)){
            try{
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'client_id' => $this->generateApiKey(),
                    'client_secret' => $this->generateApiKey(),
                ]);

                return $this->successResponse($user);
            } catch(\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
     * 
     * @param Request $request
     * @return array
     * @throws ValidationException
     * 
    */
    public function isLoginValid(Request $request)
    {
        return $this->validate($request, [
            'client_id' => 'required|string',
            'client_secret' => 'required|string'
        ]);
    }

    /**
     * 
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     * 
    */
    public function login(Request $request)
    {
        if($this->isLoginValid($request)){
            $user =  User::where('client_id', $request->client_id)->where('client_secret', $request->client_secret)->first();
            
            if($user){
                return $this->respondWithToken(auth()->setTTL(env('JWT_TTL','60'))->login($user));
            }else{
                return $this->errorResponse('Não encontrado', Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function generateApiKey()
    {
        $data = random_bytes(16);

        if ($data === false) {
            return false;
        }

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
