<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(title="My First API", version="0.1")
 * @OA\Tag(
 *     name="Subscribers",
 *     description="Operations about subscribers"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="OAuth2",
 *     type="oauth2",
 *     description="Use client credentials for OAuth2 authorization",
 *     in="header",
 *     scheme="bearer",
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="http://localhost:8080/realms/Sheiierman/protocol/openid-connect/token",
 *         scopes={
 *             "openid": "OpenID Connect",
 *             "profile": "Profile information",
 *             "email": "Access to email address"
 *         }
 *     )
 * )
 */
class SubscriberController extends Controller
{
    /**
     * @OA\Get(
     *     path="/php/api/subscribers",
     *     tags={"Subscribers"},
     *     summary="Get all subscribers",
     *     @OA\Response(
     *         response=200,
     *         description="A list of subscribers with pagination metadata",
     *     )
     * )
     */
    public function index()
    {
        $subscribers = Subscriber::with('subscriptions')->paginate(10);
        return response()->json([
            'data' => $subscribers->items(),
            'meta' => [
                'current_page' => $subscribers->currentPage(),
                'last_page' => $subscribers->lastPage(),
                'per_page' => $subscribers->perPage(),
                'total' => $subscribers->total(),
            ],
            'links' => [
                'first' => $subscribers->url(1),
                'last' => $subscribers->url($subscribers->lastPage()),
                'prev' => $subscribers->previousPageUrl(),
                'next' => $subscribers->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/php/api/subscribers",
     *     tags={"Subscribers"},
     *     summary="Create a new subscriber",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The created subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subscriber = Subscriber::create($request->only(['email', 'name']));
        return response()->json($subscriber, 201);
    }

    /**
     * @OA\Get(
     *     path="/php/api/subscribers/{subscriberId}",
     *     tags={"Subscribers"},
     *     summary="Get a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriberId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A specific subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function show($id)
    {
        $subscriber = Subscriber::with('subscriptions')->find($id);

        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        return response()->json($subscriber, 200);
    }

    /**
     * @OA\Put(
     *     path="/php/api/subscribers/{subscriberId}",
     *     tags={"Subscribers"},
     *     summary="Update a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriberId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated subscriber",
     *         @OA\JsonContent(ref="#/components/schemas/Subscriber")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $subscriber = Subscriber::find($id);

        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|email|unique:subscribers,email,' . $subscriber->id,
            'name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subscriber->update($request->only(['email', 'name']));
        return response()->json($subscriber, 200);
    }

    /**
     * @OA\Delete(
     *     path="/php/api/subscribers/{subscriberId}",
     *     tags={"Subscribers"},
     *     summary="Delete a specific subscriber",
     *     @OA\Parameter(
     *         name="subscriberId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Subscriber deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subscriber not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $subscriber = Subscriber::find($id);

        if (!$subscriber) {
            return response()->json(['message' => 'Subscriber not found'], 404);
        }

        $subscriber->delete();
        return response()->json(null, 204);
    }
}
