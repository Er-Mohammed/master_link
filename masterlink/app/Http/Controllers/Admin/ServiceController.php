<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;

class ServiceController extends Controller
{

    /**
     * Display all services.
     */
    public function index()
    {
        $services = Service::with('media')
            ->orderBy('sort_order')
            ->get();


        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }



    /**
     * Store new service.
     */
    public function store(StoreServiceRequest $request)
    {

        $service = Service::create(
            $request->validated()
        );


        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }



    /**
     * Display one service.
     */
    public function show(Service $service)
    {

        $service->load('media');


        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }




    /**
     * Update service.
     */
    public function update(
        UpdateServiceRequest $request,
        Service $service
    )
    {

        $service->update(
            $request->validated()
        );


        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $service->fresh()
        ]);
    }





    /**
     * Delete service.
     */
    public function destroy(Service $service)
    {

        $service->delete();


        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }






    /**
     * Attach media files to service.
     */
    public function attachMedia(
        Request $request,
        Service $service
    )
    {

        $validated = $request->validate([

            'media' => [
                'required',
                'array'
            ],


            'media.*.id' => [
                'required',
                'exists:media,id'
            ],


            'media.*.sort_order' => [
                'nullable',
                'integer'
            ],

        ]);



        foreach($validated['media'] as $item)
        {

            $service->media()
                ->syncWithoutDetaching([

                    $item['id'] => [

                        'sort_order' =>
                            $item['sort_order'] ?? 0

                    ]

                ]);

        }



        return response()->json([
            'success'=>true,
            'message'=>'Media attached successfully',
            'data'=>$service->load('media')
        ]);
    }







    /**
     * Remove media from service.
     */
    public function detachMedia(
        Service $service,
        $media
    )
    {

        $service->media()
            ->detach($media);



        return response()->json([
            'success'=>true,
            'message'=>'Media detached successfully',
            'data'=>$service->load('media')
        ]);
    }


}