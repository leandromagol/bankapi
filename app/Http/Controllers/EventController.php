<?php 

namespace App\Http\Controllers;

use App\Services\Event\EventValidatorService;
use App\Services\Event\EventService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Js;

class EventController{
    public function __construct(public EventValidatorService $eventValidatorService, public EventService $eventService){
    }
       
    public function post(Request $request){
        if(!$this->eventValidatorService->validate($request)){
            return (new Response(0,404))->header('Content-Type', 'application/json');
        }
        if($request->type == 'deposit'){
          return $this->eventService->depostit($request->destination,$request->amount);
        }
        if($request->type == 'withdraw'){
           return $this->eventService->withdraw($request->origin,$request->amount);
        }
        if($request->type == 'transfer'){
           return $this->eventService->transfer($request->origin,$request->destination,$request->amount);
        }
        return (new Response)->setContent(json_encode(['destination'=>  Cache::get('account_id_'.$request->destination)]))->setStatusCode(201)->header('Content-Type', 'application/json');
    }
}