@extends('layouts.dashboard')

@section('dashboard-section')

@can(App\Enums\Permissions\ShortUrlPermission::SHORT_URL_CREATE)
<div class="modal fade" id="generateShortUrlModal" tabindex="-1" aria-labelledby="generateShortUrlModalLabel" aria-hidden="true">
  <form class="modal-dialog" action="{{ route('handle.create.shorturl') }}" method="POST">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generateShortUrlModalLabel">Generate Short URL</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="original_url" class="form-label">URL</label>
          <input type="url" class="form-control" name="original_url" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>
@endcan


<section>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Short URL's</h2>
            <p>List of all short url.</p>
        </div>
        @can(App\Enums\Permissions\ShortUrlPermission::SHORT_URL_CREATE)
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateShortUrlModal">
                Generate Short URL
            </button>
        </div>
        @endcan
    </div>
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Hits</th>
                    <th>Company</th>
                    <th>Created By</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($short_urls as $short_url)
                    <tr>
                        <td>{{ $short_url->original_url }}</td>
                        <td><a target="_blank" href="{{ route('check.short-url',['short_url_code' => $short_url->short_url_code]) }}">{{ route('check.short-url',['short_url_code' => $short_url->short_url_code]) }}</a></td>
                        <td>{{ $short_url->hit_count }}</td>
                        <td>{{ $short_url->company->name }}</td>
                        <td>{{ $short_url->user->name }}</td>
                        <td>{{ $short_url->user->roles->pluck('name')->join(', ') }}</td>
                        <td>{{ date('D d M Y h:i A', strtotime($short_url->created_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $short_urls->links() }}
        </div>
    </div>
</section>
@endsection