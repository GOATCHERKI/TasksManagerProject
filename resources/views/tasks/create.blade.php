@extends('layout.base')
@section('main_content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            color: #2f3542;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">Create Task</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store') }}" id="taskForm">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Order</label>
                            <input type="number" id="order" name="order" class="form-control @error('order') is-invalid @enderror" 
                                   value="{{ old('order') }}" min="1" step="1" required
                                   onchange="checkOrderAvailability(this.value)">
                            <div id="orderFeedback" class="form-text"></div>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
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
    // Fetch taken orders and show feedback
    const takenOrders = @json($takenOrders ?? []);

    function checkOrderAvailability(value) {
        const feedback = document.getElementById('orderFeedback');
        
        if (takenOrders.includes(parseInt(value))) {
            feedback.textContent = 'This order number is already taken. Please choose another.';
            feedback.style.color = 'red';
            document.getElementById('taskForm').querySelector('button[type="submit"]').disabled = true;
        } else {
            feedback.textContent = 'This order number is available.';
            feedback.style.color = 'green';
            document.getElementById('taskForm').querySelector('button[type="submit"]').disabled = false;
        }
    }

    // Initial check if there's an old value
    @if(old('order'))
        checkOrderAvailability({{ old('order') }});
    @endif
</script>
</body>
@endsection