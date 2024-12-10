<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Subscriptions",
 *     description="Operations about subscriptions"
 * )
 * 
 */
class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/php/api/subscriptions",
     *     tags={"Subscriptions"},
     *     summary="Get all subscriptions",
     *     @OA\Response(
     *         response=200,
     *         description="A list of subscriptions with pagination metadata",
     *     )
     * )
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(10);
        return response()->json([
            'data' => $subscriptions->items(),
            'meta' => [
                'current_page' => $subscriptions->currentPage(),
                'last_page' => $subscriptions->lastPage(),
                'per_page' => $subscriptions->perPage(),
                'total' => $subscriptions->total(),
            ],
            'links' => [
                'first' => $subscriptions->url(1),
                'last' => $subscriptions->url($subscriptions->lastPage()),
                'prev' => $subscriptions->previousPageUrl(),
                'next' => $subscriptions->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/php/api/subscriptions",
     *     tags={"Subscriptions"},
     *     summary="Create a new subscription",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The created subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     )
     * )
     */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'subscriber_id' => 'required|exists:subscribers,id',
			'service' => 'required|string|max:255',
			'topic' => 'required|string|max:255',
			'payload' => 'nullable|array',
			'expired_at' => 'nullable|date',
		]);
	
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}
	
		$data = $request->all();
	
		$subscription = Subscription::create($data);
		return response()->json($subscription, 201);
	}

    /**
     * @OA\Get(
     *     path="/php/api/subscriptions/{subscriptionId}",
     *     tags={"Subscriptions"},
     *     summary="Get a specific subscription",
     *     @OA\Parameter(
     *         name="subscriptionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A specific subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function show($id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        return response()->json($subscription, 200);
    }

    /**
     * @OA\Put(
     *     path="/php/api/subscriptions/{subscriptionId}",
     *     tags={"Subscriptions"},
     *     summary="Update a specific subscription",
     *     @OA\Parameter(
     *         name="subscriptionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated subscription",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'subscriber_id' => 'sometimes|required|exists:subscribers,id',
            'service' => 'sometimes|required|string|max:255',
            'topic' => 'sometimes|required|string|max:255',
            'payload' => 'nullable|array',
            'expired_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subscription->update($request->all());
        return response()->json($subscription, 200);
    }

    /**
     * @OA\Delete(
     *     path="/php/api/subscriptions/{subscriptionId}",
     *     tags={"Subscriptions"},
     *     summary="Delete a specific subscription",
     *     @OA\Parameter(
     *         name="subscriptionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Subscription deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $subscription->delete();
        return response()->json(null, 204);
    }
}
