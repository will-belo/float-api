<?php

namespace App\Http\Controllers;

use App\Models\Skin;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class SkinsController extends Controller
{
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $itensPerPage = $request->input('itensPerPage', 10);
        
        $paginate = Skin::paginate($itensPerPage);

        $skinsArray = $paginate->toArray();

        $removeKey = ['first_page_url', 'from', 'last_page', 'last_page_url', 'links', 'next_page_url', 'path', 'prev_page_url', 'to'];

        foreach ($removeKey as $key) {
            unset($skinsArray[$key]);
        }
        
        return $this->successResponse($skinsArray);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try{
            $skin = Skin::create([
                'weapon' => $request->weapon,
                'skin_name' => $request->skin_name,
                'float' => $request->float,
                'price' => $request->price,
            ]);

            return $this->successResponse($skin);
        } catch(\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $skin = Skin::find($id);

        return $this->successResponse($skin);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $skin = Skin::find($id);

        $skin->weapon = $request->weapon ? $request->weapon : $skin->weapon;
        $skin->skin_name = $request->skin_name ? $request->skin_name : $skin->skin_name;
        $skin->float = $request->float ? $request->float : $skin->float;
        $skin->price = $request->price ? $request->price : $skin->price;

        $skin->save();

        return $this->successResponse($skin);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skin = Skin::find($id);

        $skin->delete();

        return $this->successResponse('Registro deletado com sucesso');
    }
}
