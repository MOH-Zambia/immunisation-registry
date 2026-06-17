@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>QR Code Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">QR Code Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Storage Configuration</h3>
            </div>

            <form method="POST" action="{{ route('admin.qrcode-settings.update') }}">
                @csrf

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Storage credentials and bucket configuration remain in the environment and filesystem config. This page only controls QR-specific storage behavior.
                    </div>

                    <div class="form-group">
                        <label for="storage_disk">Storage Disk</label>
                        <select id="storage_disk" name="storage_disk" class="form-control @error('storage_disk') is-invalid @enderror" required>
                            @foreach($availableDisks as $disk)
                                <option value="{{ $disk }}" {{ old('storage_disk', $settings['storage_disk']) === $disk ? 'selected' : '' }}>{{ $disk }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Choose one of the disks already defined in the application filesystem config.</small>
                        @error('storage_disk')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="storage_path">Storage Folder</label>
                        <input id="storage_path" name="storage_path" type="text" class="form-control @error('storage_path') is-invalid @enderror" value="{{ old('storage_path', $settings['storage_path']) }}" required>
                        <small class="form-text text-muted">Example: <strong>qrcodes</strong> or <strong>certificates/qrcodes</strong>.</small>
                        @error('storage_path')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="storage_visibility">Visibility</label>
                        <select id="storage_visibility" name="storage_visibility" class="form-control @error('storage_visibility') is-invalid @enderror" required>
                            <option value="public" {{ old('storage_visibility', $settings['storage_visibility']) === 'public' ? 'selected' : '' }}>public</option>
                            <option value="private" {{ old('storage_visibility', $settings['storage_visibility']) === 'private' ? 'selected' : '' }}>private</option>
                        </select>
                        <small class="form-text text-muted">Use <strong>public</strong> for directly exposed storage; use <strong>private</strong> when QR images should remain behind application-controlled access.</small>
                        @error('storage_visibility')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="public_base_url">Public Base URL</label>
                        <input id="public_base_url" name="public_base_url" type="url" class="form-control @error('public_base_url') is-invalid @enderror" value="{{ old('public_base_url', $settings['public_base_url']) }}" placeholder="https://cdn.example.com/qrcodes">
                        <small class="form-text text-muted">Optional. Override the disk URL when QR files are served from a CDN or a custom object storage domain.</small>
                        @error('public_base_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save QR Code Settings
                    </button>
                    <a href="{{ route('admin.system-tools') }}" class="btn btn-default">Back to System Tools</a>
                </div>
            </form>
        </div>
    </div>
@endsection
