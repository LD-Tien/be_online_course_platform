<?php

namespace App\Http\Resources\Module;

use App\Http\Resources\Lesson\LessonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ordinal_number' => $this->ordinal_number,
            'course_id' => $this->course_id,
            'lessons' => LessonResource::collection($this->lessons),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
