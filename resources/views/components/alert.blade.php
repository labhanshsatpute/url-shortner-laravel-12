@if (session('message'))
    <div class="my-2">
        @switch (session('message')['status'])
            @case('success')
                <div class="alert alert-success">
                    {{ session('message')['message'] }}
                </div>
                @break
            @case('error')
                <div class="alert alert-danger">
                    {{ session('message')['message'] }}
                </div>
                @break
            @case('warning')
                <div class="alert alert-warning">
                    {{ session('message')['message'] }}
                </div>
                @break
        @endswitch
    </div>
@endif