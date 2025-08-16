<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $tasks = Task::when($search, function($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
        })->get();
        
        return view('tasks.index', compact('tasks', 'search'));
    }

    public function create()
    {
        $takenOrders = Task::pluck('order')->toArray();
        return view('tasks.create', compact('takenOrders'));
    }
    
    public function store(Request $request)
    {
        try{
        $data = $request->all();
        $title = $data['title'];
        $description = $data['description'];
        $order = $data['order'];
        $status = $data['status'] ?? 'pending';

        $task = new Task();
        $task->title = $title;
        $task->description = $description;
        $task->order = $order;
        $task->status = $status;

        $task->save();
  
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');

        }catch(\Exception $e){
            return back()->withInput()->with('error', 'Task creation failed: ' . $e->getMessage());
       }

    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function edit(Request $request, $id)
    {
        $task = Task::find($id);
        $takenOrders = Task::where('id', '!=', $task->id)
                         ->pluck('order')
                         ->toArray();
    
        return view('tasks.edit', compact('task', 'takenOrders'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'required|string|max:255',
        ]);
    
        $task->update($validated);
    
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }
    

}
