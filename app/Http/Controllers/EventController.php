<?php 

namespace App\Http\Controllers;

use App\Services\Event\EventValidatorService;
use App\Services\Event\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class EventController{
    public function __construct(public EventValidatorService $eventValidatorService, public EventService $eventService){
    }
       
    public function post(Request $request){
        if(!$this->eventValidatorService->validate($request)){
            return (new Response([0],400))->header('Content-Type', 'application/json');
        }
        if($request->type == 'deposit'){
            if(Cache::has('account_id_'.$request->destination)){
                $this->eventService->deposit($request->destination,$request->amount);
            }
            if(!Cache::has('account_id_'.$request->destination)){
               $this->eventService->create($request->destination,$request->amount);
            }
        }
        if($request->type == 'withdraw'){
            if(Cache::has('account_id_'.$request->destination)){
                $this->eventService->withdraw($request->destination,$request->amount);
            }
            if(!Cache::has('account_id_'.$request->destination)){
                return (new Response)->setStatusCode(404)->header('Content-Type', 'application/json');
            }
        }
        if($request->type == 'transfer'){
            if(Cache::has('account_id_'.$request->origin)){
                $this->eventService->transfer($request->origin,$request->destination,$request->amount);
                return (new Response)->setStatusCode(201)->setContent([
                    'origin' => Cache::get('account_id_'.$request->origin),
                    'destination' => Cache::get('account_id_'.$request->destination),
                ])->header('Content-Type', 'application/json');
            }
            if(!Cache::has('account_id_'.$request->origin)){
                return (new Response)->setStatusCode(404)->header('Content-Type', 'application/json');
            }
           
        }
        return (new Response(['destination'=>[Cache::get('account_id_'.$request->destination)]]
        ,201))->header('Content-Type', 'application/json');
    }
}