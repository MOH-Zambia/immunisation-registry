@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Records</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Records</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="clearfix"></div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-folder-open"></i> Vaccination Records Management
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Coming Soon</h5>
                    This section will provide comprehensive vaccination records management including:
                    <ul class="mt-2">
                        <li>Combined client and vaccination history</li>
                        <li>Consolidated certificate management</li>
                        <li>Advanced reporting and analytics</li>
                        <li>Bulk record operations</li>
                        <li>Data import and export capabilities</li>
                    </ul>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Clients</span>
                                <span class="info-box-number">Manage client records</span>
                                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary mt-2">
                                    View Clients
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-syringe"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Vaccinations</span>
                                <span class="info-box-number">Manage vaccination records</span>
                                <a href="{{ route('vaccinations.index') }}" class="btn btn-sm btn-success mt-2">
                                    View Vaccinations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-certificate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Certificates</span>
                                <span class="info-box-number">Manage certificates</span>
                                <a href="{{ route('certificates.index') }}" class="btn btn-sm btn-warning mt-2">
                                    View Certificates
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Dashboard</span>
                                <span class="info-box-number">View statistics</span>
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-danger mt-2">
                                    View Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
