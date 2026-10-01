<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participation;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $participations = Participation::with(['user', 'course'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $participations,
                'message' => __('messages.participations_retrieved')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participations_retrieval_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'course_id' => ['required', 'integer', 'exists:courses,id'],
                'status' => ['nullable', 'in:registered,attended,absent'],
                'participated_at' => ['nullable', 'date'],
            ]);

            // Vérifier si la participation existe déjà
            $existingParticipation = Participation::where('user_id', $data['user_id'])
                ->where('course_id', $data['course_id'])
                ->first();

            if ($existingParticipation) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.participation_exists')
                ], 409);
            }

            $course = Course::withCount('participations')->findOrFail($data['course_id']);
            if ($course->capacity && $course->participations_count >= $course->capacity) {
                return response()->json([
                    'success' => false,
                    'message' => "La capacité maximale du cours ({$course->capacity}) a été atteinte."
                ], 422);
            }

            $participation = Participation::create($data);
            $participation->load(['user', 'course']);
            
            return response()->json([
                'success' => true,
                'data' => $participation,
                'message' => __('messages.participation_created')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_creation_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $participation = Participation::with(['user', 'course'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $participation,
                'message' => __('messages.participation_retrieved')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_not_found')
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participations_retrieval_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $participation = Participation::findOrFail($id);
            
            $data = $request->validate([
                'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
                'course_id' => ['sometimes', 'required', 'integer', 'exists:courses,id'],
                'status' => ['nullable', 'in:registered,attended,absent'],
                'participated_at' => ['nullable', 'date'],
            ]);
            
            // Vérifier les doublons si user_id ou course_id sont modifiés
            if (isset($data['user_id']) || isset($data['course_id'])) {
                $userId = $data['user_id'] ?? $participation->user_id;
                $courseId = $data['course_id'] ?? $participation->course_id;
                
                $existingParticipation = Participation::where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->where('id', '!=', $id)
                    ->first();
                    
                if ($existingParticipation) {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.participation_exists')
                    ], 409);
                }
            }

            if (isset($data['course_id']) && $data['course_id'] != $participation->course_id) {
                $course = Course::withCount('participations')->findOrFail($data['course_id']);
                if ($course->capacity && $course->participations_count >= $course->capacity) {
                    return response()->json([
                        'success' => false,
                        'message' => "La capacité maximale du cours ({$course->capacity}) a été atteinte."
                    ], 422);
                }
            }
            
            $participation->update($data);
            $participation->load(['user', 'course']);
            
            return response()->json([
                'success' => true,
                'data' => $participation,
                'message' => __('messages.participation_update_success')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_not_found')
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_update_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $participation = Participation::findOrFail($id);
            $participation->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('messages.participation_delete_success')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_not_found')
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.participation_delete_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get participations for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByUser($userId): JsonResponse
    {
        try {
            $participations = Participation::with(['course'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $participations,
                'message' => __('messages.user_participations_retrieved')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.user_participations_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get participations for a specific course.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCourse($courseId): JsonResponse
    {
        try {
            $participations = Participation::with(['user'])
                ->where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $participations,
                'message' => __('messages.course_participations_retrieved')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.course_participations_error'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
