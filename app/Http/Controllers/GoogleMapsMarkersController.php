<?php

namespace App\Http\Controllers;

use App\Models\GoogleMapsMarker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleMapsMarkersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if($user->permissionsCheck(['admin','create_google_maps_markers'])){
            $markers = GoogleMapsMarker::with('user')->get();
            return response()->json([
                'error' => false,
                'markers' => $markers,
            ], 200);

        }else{
            return response()->json([
                'error' => true,
                'markers' => 'Permission denied',
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'title' => 'required'
        ]);
        $user = Auth::user();
        if($user->permissionsCheck(['admin','create_google_maps_markers'])){

            $marker_data = (object) $request->all();
            if(isset($marker_data->id)){
                $marker = GoogleMapsMarker::where('id',$marker_data->id)->first();
            }else{
                $marker = new GoogleMapsMarker();
            }

            $marker->title = $marker_data->title;
            $marker->lat = $marker_data->lat;
            $marker->long = $marker_data->long;

            if(isset($marker_data->user_id)){
                $marker->user_id = $marker_data->user_id;

            }

            $marker->saveOrFail();
            return response()->json([
                'error' => false,
                'message' => 'Successfully created',
            ], 200);

        }else{
            abort(403, 'Permission denied');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->permissionsCheck(['admin','create_google_maps_markers'])){

            $delete_marker= GoogleMapsMarker::where('id',$id)->first();
            $delete_marker->delete();

            return response()->json([
                'error' => false,
                'message' => 'Successfully deleted',
            ], 200);

        }else{
            abort(403, 'Permission denied');
        }

    }

    public function getMyMarkers()
    {
        $user = Auth::user();
        if($user->permissionsCheck(['create_google_maps_markers'])){
            $my_markers= GoogleMapsMarker::where('user_id',$user->id)->get();
            return response()->json([
                'error' => false,
                'markers' => $my_markers,
            ], 200);

        }else{
            abort(403, 'Permission denied');
        }

    }
}
