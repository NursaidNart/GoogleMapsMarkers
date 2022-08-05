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
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
            && is_array($user_permissions)
            && in_array('create_google_maps_markers',$user_permissions)){
                $markers = GoogleMapsMarker::with('user')->get();
                $abort = false;
            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'markers' => $markers,
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
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
                && is_array($user_permissions)
                && in_array('create_google_maps_markers',$user_permissions)){
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
                $error = false;
                $message = 'Successfully created';



            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'message' => $message,
            ], 200);
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
        $is_admin = $user->role_type == 'admin'?1:0;
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if ($is_admin){
            if($user_permissions
                && is_array($user_permissions)
                && in_array('create_google_maps_markers',$user_permissions)){
                $delete_marker= GoogleMapsMarker::where('id',$id)->first();
                $delete_marker->delete();
                $error = false;
                $message = 'Successfully deleted';


            }else{
                $abort = true;
                $message = 'Permission denied';
            }
        }else{
            $abort = true;
            $message = 'Permission denied';
        }

        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'message' => $message,
            ], 200);
        }

    }

    public function getMyMarkers()
    {
        $user = Auth::user();
        $user_permissions = $user->getPermissions($user);
        $error = false;
        $abort = false;
        if($user_permissions
            && is_array($user_permissions)
            && in_array('show_my_markers',$user_permissions)){
            $my_markers= GoogleMapsMarker::where('user_id',$user->id)->get();
            $error = false;

        }else{
            $abort = true;
            $message = 'Permission denied';
        }
        if($abort){
            abort(403, $message);
        }else{
            return response()->json([
                'error' => $error,
                'markers' => $my_markers,
            ], 200);
        }

    }
}
