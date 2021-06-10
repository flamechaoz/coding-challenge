<?php

namespace App\Http\Controllers\Api;

use App\Events;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Log;

class EventController extends Controller
{
    //
    public function index()
    {
        $events = Events::all();

        return response()->json($events->toArray(), 200);
    }

    public function saveEvent(Request $request)
    {

        $req = $request->only(
            'dates',
            'name'
        );

        $validation = \Validator::make(
            $req,
            [
                'dates' => 'required',
                'name' => 'required'
            ]
        );

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $validation->errors(),
            ], 422);
        }

        try {

            DB::beginTransaction();

            $data = array();

            foreach($req['dates'] as $date) {
                $temp = array(
                    'event_name' => $req['name'],
                    'event_date' => $date
                );
                array_push($data, $temp);
            }

            Events::insert($data);

            DB::commit();

        } catch (\Exception $e) {

            Log::critical($e);

            DB::rollback();

            return response()->json(
                [
                    'success'  => false,
                    'message' => 'Server Error'
                ],
                500
            );
        }

        $events = Events::all();

        return response()->json(
            [
                'success'  => true,
                'message' => 'Success',
                'events' => $events->toArray()
            ],
            200
        );

    }

}
