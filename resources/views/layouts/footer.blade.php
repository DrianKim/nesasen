<!-- Bootstrap core JavaScript-->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

<!-- Utility scripts -->
<script src="{{ asset('js/custom.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dark mode toggle -->
{{-- <script>
    $(document).ready(function() {
        // Dark mode toggle
        $('#darkModeToggle').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('dark-mode');

            // Save preference to session
            $.ajax({
                url: "{{ route('toggle-dark-mode') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    dark_mode: $('body').hasClass('dark-mode')
                }
            });

            // Switch icon
            $(this).find('i').toggleClass('fa-moon fa-sun');
        });

        // Initialize dark mode icon
        if ($('body').hasClass('dark-mode')) {
            $('#darkModeToggle').find('i').removeClass('fa-moon').addClass('fa-sun');
        }

        // Todo list functionality
        $('#addTodoBtn').on('click', function() {
            const taskText = $('#newTodoInput').val().trim();
            if (taskText) {
                addTask(taskText);
                $('#newTodoInput').val('');
            }
        });

        $('#newTodoInput').on('keypress', function(e) {
            if (e.which === 13) {
                $('#addTodoBtn').click();
            }
        });

        $('#clearCompletedBtn').on('click', function() {
            $('.todo-item.completed').remove();
            saveTasks();
        });

        // Load tasks
        loadTasks();

        // Global search functionality
        $('.global-search-input').on('keyup', function(e) {
            if (e.which === 13) {
                const query = $(this).val().trim();
                if (query) {
                    window.location.href = "{{ route('search') }}?q=" + encodeURIComponent(query);
                }
            }
        });
    });

    // Todo list functions
    function addTask(text) {
        const taskId = 'task-' + Date.now();
        const taskHtml = `
            <li class="list-group-item todo-item" data-id="${taskId}">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <input type="checkbox" class="task-checkbox">
                    </div>
                    <div class="task-text flex-grow-1">${text}</div>
                    <div class="ml-2">
                        <button class="btn btn-sm btn-danger delete-task">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </li>
        `;
        $('#todoList').append(taskHtml);

        // Bind events to new task
        bindTaskEvents();

        // Save tasks
        saveTasks();
    }

    function bindTaskEvents() {
        $('.task-checkbox').off('change').on('change', function() {
            $(this).closest('.todo-item').toggleClass('completed');
            saveTasks();
        });

        $('.delete-task').off('click').on('click', function() {
            $(this).closest('.todo-item').remove();
            saveTasks();
        });
    }

    function saveTasks() {
        const tasks = [];
        $('.todo-item').each(function() {
            tasks.push({
                id: $(this).data('id'),
                text: $(this).find('.task-text').text(),
                completed: $(this).hasClass('completed')
            });
        });

        localStorage.setItem('userTasks', JSON.stringify(tasks));
    }

    function loadTasks() {
        const tasks = JSON.parse(localStorage.getItem('userTasks') || '[]');
        tasks.forEach(task => {
            const taskHtml = `
                <li class="list-group-item todo-item ${task.completed ? 'completed' : ''}" data-id="${task.id}">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <input type="checkbox" class="task-checkbox" ${task.completed ? 'checked' : ''}>
                        </div>
                        <div class="task-text flex-grow-1">${task.text}</div>
                        <div class="ml-2">
                            <button class="btn btn-sm btn-danger delete-task">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </li>
            `;
            $('#todoList').append(taskHtml);
        });

        bindTaskEvents();
    }
</script> --}}

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
        });
    </script>
@endif

<!-- Page specific scripts -->
@stack('scripts')
</body>

</html>
