@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="m-0 h4 fw-bold">
            <i class="fas fa-plus">
            </i>
            {{ $title }}
        </h1>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="mb-1 mr-2">
                    <a href="{{ route('admin_murid') }}" class="btn btn-success btn-sm">
                        <i class="mr-2 fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label">
                            <input type="text" name="nama" id="">
                        </label>
                    </div>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
