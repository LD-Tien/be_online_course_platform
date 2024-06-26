<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Module\ModuleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'thumbnail' => $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null,
            'course_name' => $this->course_name,
            'description' => $this->description ?? '',
            'price' => $this->price,
            'is_progress_limited' => (boolean) $this->is_progress_limited,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'status' => (int) $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        /** Set detail information */
        if ($request->route()->getActionMethod() === 'show') {
            $data['modules'] = ModuleResource::collection($this->modules);
        }

        return $data;
    }
}
