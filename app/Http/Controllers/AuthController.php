<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    use ResponseTraits;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userLogin(Request $request)
    {

//        $user = new User([
//            'name' => 'ali',
//            'email' => 'ali@ali.com',
//            'password' => bcrypt('qweqweqwe'),
//        ]);
//        $user->save();
//        return $user;


        try {

            //Check Validation Request
            $validation = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
                'password' => 'required',
            ], [
                'email.exists' => 'Email is not exist'
            ]);
            if ($validation->fails()) {
                //Explode Keys Error Validation
                $code = $this->returnCodeAccordingToInput($validation);
                //Send Response Error Validation Json
                return $this->returnValidationError($code, $validation);
            }

            //LOGIN USER
            $credentials = $request->only(['email', 'password']);
            //Create With Credentials And Get TOKEN
            $token = Auth::guard('user-api')->attempt($credentials);
            if (!$token) {
                return $this->returnError('404', 'TOKEN NOT CREAT WHIT CREDENTIALS');
            }
            $admin = Auth::guard('user-api')->user();

            $admin->api_token = $token;
            return $this->returnData('user', $admin);

        } catch (\Exception $ex) {
            $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

    public
    function login(Request $request)
    {


//                            $credentials = request(['email', 'password']);
//
//                            if (! $token = auth()->attempt($credentials)) {
//                                return response()->json(['error' => 'Unauthorized'], 401);
//                            }
//
//                            return $this->respondWithToken($token);


        try {

            //Check Validation Request
            $validation = Validator::make($request->all(), [
                'email' => 'required|exists:admins,email',
                'password' => 'required',
            ], [
                'email.exists' => 'Email is not exist'
            ]);
            if ($validation->fails()) {
                //Explode Keys Error Validation
                $code = $this->returnCodeAccordingToInput($validation);
                //Send Response Error Validation Json
                return $this->returnValidationError($code, $validation);
            }

            //LOGIN
            $credentials = $request->only(['email', 'password']);

            //Create With Credentials And Get TOKEN
            $token = Auth::guard('admin-api')->attempt($credentials);
            if (!$token) {
                return $this->returnError('404', 'TOKEN NOT CREAT WHIT CREDENTIALS');
            }
            $admin = Auth::guard('admin-api')->user();

            $admin->api_token = $token;
            return $this->returnData('_TOKEN', $admin);

        } catch (\Exception $ex) {
            $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function logout(Request $request)
    {
        $token = $request->header('auth-token');

        if ($token) {
            try {
                //Logout
                $logout = JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMsg('logout Successfully !');

            } catch (TokenInvalidException $ex) {
                return $this->returnError('401', $ex->getMessage());

            } catch (TokenExpiredException $ext) {
                return $this->returnError('401', $ext->getMessage());
            }


        } else {
            return $this->returnError('401', 'some thing went wrongs');
        }


//        auth()->logout();
//        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected
    function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
