@extends('layouts.dashboard')

@section('dashboard-section')

<div class="modal fade" id="companyCreateModal" tabindex="-1" aria-labelledby="companyCreateModalLabel" aria-hidden="true">
  <form class="modal-dialog" action="{{ route('handle.create.company') }}" method="POST">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="companyCreateModalLabel">Add Company</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name" class="form-label">Company Name</label>
          <input type="text" class="form-control" name="name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>


<section>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2>Companies</h2>
            <p>List of all companies.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#companyCreateModal">
            Add Company
            </button>

        </div>
    </div>
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->created_at }}</td>
                        <td>{{ $company->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection