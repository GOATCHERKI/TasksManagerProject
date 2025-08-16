@extends('layout.base')
@section('main_content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
            body {
            background: #f5f6fa;
            color: #2f3542;
        }
</style>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">Edit Task</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.update', $task->id) }}" id="taskForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title', $task->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Order</label>
                            <input type="number" id="order" name="order" class="form-control @error('order') is-invalid @enderror" 
                                   value="{{ old('order', $task->order) }}" min="1" step="1" required
                                   onchange="checkOrderAvailability(this.value, {{ $task->id }})">
                            <div id="orderFeedback" class="form-text"></div>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch taken orders (excluding current task's order)
    const takenOrders = @json($takenOrders ?? []);
    const currentOrder = {{ $task->order }};

    function checkOrderAvailability(value, taskId) {
        const feedback = document.getElementById('orderFeedback');
        const orderValue = parseInt(value);
        const submitBtn = document.getElementById('taskForm').querySelector('button[type="submit"]');
        
        if (orderValue === currentOrder) {
            feedback.textContent = 'This is the current order number.';
            feedback.style.color = 'blue';
            submitBtn.disabled = false;
        } else if (takenOrders.includes(orderValue)) {
            feedback.textContent = 'This order number is already taken. Please choose another.';
            feedback.style.color = 'red';
            submitBtn.disabled = true;
        } else {
            feedback.textContent = 'This order number is available.';
            feedback.style.color = 'green';
            submitBtn.disabled = false;
        }
    }
</script>
</body>
@endsection