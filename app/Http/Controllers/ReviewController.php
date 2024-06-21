<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Stmt\TryCatch;

use function Laravel\Prompts\select;

class ReviewController extends Controller
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
    public function store(ReviewRequest $request)
    {
        try {
            $data = $request->validated();
            $review = Review::create($data);

            return response()->json([
                "review" => $review,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(ReviewRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $review = Review::find($id);

            $reviewUpdated = $review->update($data);

        } catch (\Throwable $e) {
            return response()->json([
                "error" => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Review::destroy($id);
    }

    public function carReviews(string $id)
    {
        $car = Car::with('review.user')->find($id);
        $carReviews = $car->review->map(function ($review) {
            return [
                'Rating' => $review->Rating,
                'Comment' => $review->Comment,
                'DatePosted' => $review->DatePosted,
                'User' => [
                    'id' => $review->user->id,
                    'firstName' => $review->user->firstName,
                    'lastName' => $review->user->lastName,
                ],
                'CarID' => $review->CarID,
            ];
        });

        return response()->json([
            "carReviews" => $carReviews,
            "car" => $car,
        ]);
    }
}
