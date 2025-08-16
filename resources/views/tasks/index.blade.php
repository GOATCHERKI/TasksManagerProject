@extends('layout.base')
@section('main_content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f6fa;
            color: #2f3542;
        }

        .main-container {
            max-width: 80%;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            border: 1px solid #dfe4ea;
            flex: 1;
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color:#0088cc;
        }

        .pending-stat { color: #ffa502; }
        .in-progress-stat { color:#3742fa; }
        .completed-stat { color: #2ed573; }

        .main-board {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .main-column {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            height: fit-content;
        }

        .column-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #dfe4ea;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafbfc;
        }

        .column-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #2f3542;
        }

        .column-body {
            padding: 1rem;
            min-height: 200px;
        }

        .task-card {
            background: white;
            border: 1px solid #dfe4ea;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .task-card:last-child {
            margin-bottom: 0;
        }

        .task-card:hover {
            border-color: #00a8ff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .task-card.pending { border-left: 4px solid  #ffa502; }
        .task-card.in_progress { border-left: 4px solid #3742fa; }
        .task-card.completed { border-left: 4px solid #2ed573; }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .task-title {
            font-size: 1rem;
            font-weight: 600;
            color: #2f3542;
            line-height: 1.4;
            margin: 0;
            flex: 1;
        }

        .task-order {
            background: #f5f6fa;
            color: #57606f;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 500;
            margin-left: 0.75rem;
            flex-shrink: 0;
        }

        .task-description {
            color: #57606f;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f1f2f6;
        }

        .task-status {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status-pending {
            background: rgba(255, 165, 2, 0.1);
            color:  #ffa502;
        }

        .status-in_progress {
            background: rgba(55, 66, 250, 0.1);
            color: #3742fa;
        }

        .status-completed {
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: none;
            border: none;
            color: #a4b0be;
            font-size: 0.9rem;
            padding: 0.4rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-btn.edit:hover {
            background: #f5f6fa;
            color: #00a8ff;
        }

        .action-btn.delete:hover {
            background: #f5f6fa;
            color: #ff3838;
        }

        .empty-column {
            text-align: center;
            color: #a4b0be;
            font-size: 0.9rem;
            padding: 2rem 1rem;
        }

        .empty-column i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container mt-5 ">
        <form method="GET" action="{{ route('tasks.index') }}" class="position-relative w-25 mb-4 ">
            <div class="position-relative">
                <input id="searchInput" type="text" class="form-control pe-5" name="search" placeholder="Search..." value="{{$search}}" autocomplete="off">
                <button id="searchBtn" class="btn btn-sm position-absolute end-0 translate-middle-y top-50 rounded z-10 me-2" style='background: #00a8ff; color:#ffffff;' type="submit">search</button>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        @php
            $pendingTasks = $tasks->where('status', 'pending');
            $inProgressTasks = $tasks->where('status', 'in_progress');
            $completedTasks = $tasks->where('status', 'completed');
        @endphp

        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-number pending-stat">{{ $pendingTasks->count() }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number in-progress-stat">{{ $inProgressTasks->count() }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-number completed-stat">{{ $completedTasks->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #57606f">{{ $tasks->count() }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>

        <!-- Pending tasks -->
        <div class="main-board">
            <div class="main-column">
                <div class="column-header">
                    <div class="column-title">
                        <i class="fas fa-clock" style="color:  #ffa502;"></i>
                        Pending
                    </div>
                </div>
                <div class="column-body">
                    @forelse($pendingTasks as $task)
                        <div class="task-card pending">
                            <div class="task-header">
                                <h5 class="task-title">{{ $task->title }}</h5>
                                <div class="task-order">#{{ $task->order }}</div>
                            </div>
                            <p class="task-description">
                                {{ $task->description }}
                            </p>
                            <div class="task-footer">
                                <div class="task-status status-pending">
                                    <i class="fas fa-clock"></i>
                                    Pending
                                </div>
                                <div class="task-actions">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn edit" title="Edit Task">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete Task"
                                                onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column">
                            <i class="fas fa-inbox"></i>
                            <p>No pending tasks</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- In Progress tasks -->
            <div class="main-column">
                <div class="column-header">
                    <div class="column-title">
                        <i class="fas fa-play" style="color: #3742fa;"></i>
                        In Progress
                    </div>
                </div>
                <div class="column-body">
                    @forelse($inProgressTasks as $task)
                        <div class="task-card in_progress">
                            <div class="task-header">
                                <h5 class="task-title">{{ $task->title }}</h5>
                                <div class="task-order">#{{ $task->order }}</div>
                            </div>
                            <p class="task-description">
                                {{ $task->description }}
                            </p>
                            <div class="task-footer">
                                <div class="task-status status-in_progress">
                                    <i class="fas fa-play"></i>
                                    In Progress
                                </div>
                                <div class="task-actions">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn edit" title="Edit Task">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete Task"
                                                onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column">
                            <i class="fas fa-play"></i>
                            <p>No tasks in progress</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Completed tasks -->
            <div class="main-column">
                <div class="column-header">
                    <div class="column-title">
                        <i class="fas fa-check" style="color: #2ed573;"></i>
                        Completed
                    </div>
                </div>
                <div class="column-body">
                    @forelse($completedTasks as $task)
                        <div class="task-card completed">
                            <div class="task-header">
                                <h5 class="task-title">{{ $task->title }}</h5>
                                <div class="task-order">#{{ $task->order }}</div>
                            </div>
                            <p class="task-description">
                                {{ $task->description }}
                            </p>
                            <div class="task-footer">
                                <div class="task-status status-completed">
                                    <i class="fas fa-check"></i>
                                    Completed
                                </div>
                                <div class="task-actions">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="action-btn edit" title="Edit Task">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete Task"
                                                onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-column">
                            <i class="fas fa-check-circle"></i>
                            <p>No completed tasks</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endSection
