<header class="bg-white border-bottom">
    <style>
        .nav a { 
            text-decoration: none; 
            color: #00a8ff; }
        .nav a:hover, .nav a:focus { text-decoration: none; }
    </style>
    <div class="container">
        <div class="row align-items-center py-3">
            <div class="col-md-6">
                <h2 class="mb-0" style="color: #00a8ff; font-weight: bold;" >Tasks Manager</h2>
            </div>
            <div class="col-md-6">
                <nav>
                    <ul class="nav justify-content-end mb-0 mr-2 ">
                        <li class="me-4">
                            <a href="/tasks" >Tasks</a>
                        </li>
                        <li>
                            <a href="{{route('tasks.create')}}">Add New Task</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>