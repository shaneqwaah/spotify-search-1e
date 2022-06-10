<?php

namespace App\Http\Controllers;

use app\Services\SpotifyBaseService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class SearchController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $name = $request->name ?? 'a';
        $type = $request->type ?? 'album';


        $spotify = new \App\Services\SpotifyBaseService();

        return $spotify->get('search?type='.$type.'&include_external=audio&q='.$name);


    }
}

