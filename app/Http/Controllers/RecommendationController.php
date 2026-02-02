<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecomendationRequest;
use App\Http\Requests\UpdateRecommendationRequest;
use App\Models\Recommendation;

class RecommendationController extends Controller
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
    public function store(StoreRecomendationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Recommendation $recomendation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recommendation $recomendation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecommendationRequest $request, Recommendation $recomendation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recommendation $recomendation)
    {
        //
    }
}
