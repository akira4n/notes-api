<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $data = Note::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'All notes retrieved successfully',
            'data' => NoteResource::collection($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {   
        $user = Auth::user();
        $validated = $request->validated();

        $data = Note::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if(isset($validated['tags'])){
            $tagIds = [];

            foreach($validated['tags'] as $tagName){
                $tag = Tag::firstOrCreate([
                    'name' => $tagName,
                    'user_id' => $user->id,
                    ]);
                $tagIds[] = $tag->id;
            }

            $data->tags()->sync($tagIds);
        }

        $data->load('tags');

        return response()->json([
            'success' => true,
            'message' => 'Note has been created',
            'data' => new NoteResource($data)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        $data = Auth::user()->notes()->find($id);

        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Note retrieved successfully',
            'data' => new NoteResource($data)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, string $id)
    {   
        $user = Auth::user();
        $data = $user->notes()->find($id);

        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Note not found',
            ], 404);
        }

        $validated = $request->validated();

        $data->update($validated);

        if(isset($validated['tags'])){
            $tagIds = [];

            foreach($validated['tags'] as $tagName){
                $tag = Tag::firstOrCreate([
                    'name' => $tagName,
                    'user_id' => $user->id
                ]);

                $tagIds[] = $tag->id;
            }

            $data->tags()->sync($tagIds);
        }

        $data->load('tags');

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully',
            'data' => new NoteResource($data)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        $data = Auth::user()->notes()->find($id);

        if(!$data){
            return response()->json([
                'success' => false,
                'message' => 'Note not found',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully',
            'data' => new NoteResource($data)
        ], 200);
    }
}
