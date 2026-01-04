<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavListRequest;
use App\Http\Requests\UpdateFavListRequest;
use App\Models\FavList;

class FavListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFavListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FavList $favList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavList $favList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavListRequest $request, FavList $favList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavList $favList)
    {
        //
    }
}
