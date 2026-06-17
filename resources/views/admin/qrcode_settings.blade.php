@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Storage Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Storage Settings</li>
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
                        Storage credentials and bucket configuration remain in the environment and filesystem config. This page controls storage behavior for QR codes and generated PDF certificates.
                    </div>

                    <h5 class="mb-3">QR Code Storage</h5>

                    <div class="form-group">
                        <label for="qrcode_storage_disk">Storage Disk</label>
                        <select id="qrcode_storage_disk" name="qrcode_storage_disk" class="form-control @error('qrcode_storage_disk') is-invalid @enderror" required>
                            @foreach($availableDisks as $disk)
                                <option value="{{ $disk }}" {{ old('qrcode_storage_disk', $settings['qrcode_storage_disk']) === $disk ? 'selected' : '' }}>{{ $disk }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Choose one of the disks already defined in the application filesystem config.</small>
                        @error('qrcode_storage_disk')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="qrcode_storage_path">Storage Folder</label>
                        <input id="qrcode_storage_path" name="qrcode_storage_path" type="text" class="form-control @error('qrcode_storage_path') is-invalid @enderror" value="{{ old('qrcode_storage_path', $settings['qrcode_storage_path']) }}" required>
                        <small class="form-text text-muted">Example: <strong>qrcodes</strong> or <strong>certificates/qrcodes</strong>.</small>
                        @error('qrcode_storage_path')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="qrcode_storage_visibility">Visibility</label>
                        <select id="qrcode_storage_visibility" name="qrcode_storage_visibility" class="form-control @error('qrcode_storage_visibility') is-invalid @enderror" required>
                            <option value="public" {{ old('qrcode_storage_visibility', $settings['qrcode_storage_visibility']) === 'public' ? 'selected' : '' }}>public</option>
                            <option value="private" {{ old('qrcode_storage_visibility', $settings['qrcode_storage_visibility']) === 'private' ? 'selected' : '' }}>private</option>
                        </select>
                        <small class="form-text text-muted">Use <strong>public</strong> for directly exposed storage; use <strong>private</strong> when QR images should remain behind application-controlled access.</small>
                        @error('qrcode_storage_visibility')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="qrcode_public_base_url">Public Base URL</label>
                        <input id="qrcode_public_base_url" name="qrcode_public_base_url" type="url" class="form-control @error('qrcode_public_base_url') is-invalid @enderror" value="{{ old('qrcode_public_base_url', $settings['qrcode_public_base_url']) }}" placeholder="https://cdn.example.com/qrcodes">
                        <small class="form-text text-muted">Optional. Override the disk URL when QR files are served from a CDN or a custom object storage domain.</small>
                        @error('qrcode_public_base_url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">Certificate PDF Storage</h5>

                    <div class="form-group">
                        <label for="certificate_storage_disk">Storage Disk</label>
                        <select id="certificate_storage_disk" name="certificate_storage_disk" class="form-control @error('certificate_storage_disk') is-invalid @enderror" required>
                            @foreach($availableDisks as $disk)
                                <option value="{{ $disk }}" {{ old('certificate_storage_disk', $settings['certificate_storage_disk']) === $disk ? 'selected' : '' }}>{{ $disk }}</option>
                            @endforeach
                        </select>
                        @error('certificate_storage_disk')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="certificate_storage_path">Storage Folder</label>
                        <input id="certificate_storage_path" name="certificate_storage_path" type="text" class="form-control @error('certificate_storage_path') is-invalid @enderror" value="{{ old('certificate_storage_path', $settings['certificate_storage_path']) }}" required>
                        <small class="form-text text-muted">Example: <strong>certificates</strong> or <strong>documents/certificates</strong>.</small>
                        @error('certificate_storage_path')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="certificate_storage_visibility">Visibility</label>
                        <select id="certificate_storage_visibility" name="certificate_storage_visibility" class="form-control @error('certificate_storage_visibility') is-invalid @enderror" required>
                            <option value="private" {{ old('certificate_storage_visibility', $settings['certificate_storage_visibility']) === 'private' ? 'selected' : '' }}>private</option>
                            <option value="public" {{ old('certificate_storage_visibility', $settings['certificate_storage_visibility']) === 'public' ? 'selected' : '' }}>public</option>
                        </select>
                        <small class="form-text text-muted">For certificate downloads, <strong>private</strong> is recommended.</small>
                        @error('certificate_storage_visibility')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Storage Settings
                    </button>
                    <a href="{{ route('admin.system-tools') }}" class="btn btn-default">Back to System Tools</a>
                </div>
            </form>
        </div>
    </div>
@endsection
