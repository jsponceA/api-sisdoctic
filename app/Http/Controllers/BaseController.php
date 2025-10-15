<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    public function index(Request $request)
    {
        $search = str($request->input("sarch"))->lower();
        $perPage = $request->input("per_page", 15);
        $currentPage = $request->input("current_page", 1);

        $bases = Base::query()
            ->orderBy("id", "DESC")
            ->paginate($perPage, "*", "page", $currentPage);

        return response()->json(["bases" => $bases], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BaseRequest $request)
    {
        $base = new Base();
        $base->fill($request->all());
        if ($request->hasFile("imagen")) {
            $base->imagen = basename(Storage::putFile("dashboard/bases", $request->file("imagen")));
        } else {
            unset($base->imagen);
        }
        $base->save();

        return response()->json(["message" => "Base creado"], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(["base" => Base::query()->findOrFail($id)], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BaseRequest $request, $id)
    {
        $base = Base::query()->findOrFail($id);
        $base->fill($request->all());
        if ($request->hasFile("imagen")) {
            Storage::delete("dashboard/bases/{$base->imagen}");
            $base->imagen = basename(Storage::putFile("dashboard/bases", $request->file("imagen")));
        } else {
            unset($base->imagen);
        }
        $base->update();
        return response()->json(["message" => "Base creado"], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Base::query()->findOrFail($id)->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
