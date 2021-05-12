<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;

class ImageController extends Controller
{
    public function store()
    {
        $task = new Task();
        $task->id = 0;
        $task->exists = true;
        $image = $task->addMediaFromRequest('upload')->toMediaCollection('images');

        return response()->json([
            'url' => $image->getUrl('thumb')
        ]);
    }
}
