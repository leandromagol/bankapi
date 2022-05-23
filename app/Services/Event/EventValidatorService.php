<?php

namespace App\Services\Event;

use Illuminate\Http\Request;
use Validator;
class EventValidatorService{
    public function validate(Request $request)
    {   if($request->type == 'transfer'){
          return $this->transferValidator($request);
        }
       return $this->defaultValidator($request);
    }
    public function transferValidator(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:transfer',
            'amount' => 'required|numeric',
            'destination' => 'required',
            'origin' => 'required',
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }
    public function defaultValidator(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric',
            'destination' => 'required',
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }   
}