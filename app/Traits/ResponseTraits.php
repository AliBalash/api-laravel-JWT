<?php

namespace App\Traits;

trait ResponseTraits
{

    public function returnError($errNum , $msg="")
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg,
        ]);
    }

    public function returnSuccessMsg( $msg="")
    {
        return response()->json([
            'status' => false,
            'msg' => $msg,
        ]);
    }

    public function returnData($key , $value , $msg=null)
    {
        return response()->json([
            'status' => true,
            'msg' => $msg,
            $key => $value,
        ]);
    }

    public function returnNotFound ($errNum , $msg="")
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg,
        ]);
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());

        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == 'name'){
            return 'name is false';
        }elseif ($input == 'password'){
            return 'password is false';
        }elseif ($input == 'api_password'){
            return 'api_password is false';
        }elseif ($input == 'title'){
            return 'title is false';
        }elseif ($input == 'desc'){
            return 'desc is false';
        }elseif ($input == 'user_id'){
            return 'user_id is false';
        }elseif ($input == 'email'){
            return 'email is false';
        }else{
            return '';
        }
    }

    public function returnValidationError($code="",$validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }

}
