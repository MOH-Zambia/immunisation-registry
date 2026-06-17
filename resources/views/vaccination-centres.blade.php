@extends('layouts.public')

@section('title', 'Vaccination Centres')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <style>
        #centres-map {
            height: 450px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #e8e8e8;
        }
        .province-card {
            background-color: #f3f7fb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }
        .province-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .centre-item {
            padding: 8px 0;
            border-bottom: 1px solid #e8e8e8;
        }
        .centre-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('content')
    <section class="features-overview">
        <div class="content-header">
            <h2>Vaccination Centres</h2>
            <h6 class="section-subtitle text-muted">Find a COVID-19 vaccination centre near you. All centres provide free vaccinations.</h6>
        </div>

        <!-- Map Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div id="centres-map"></div>
                <p class="text-muted text-center mt-2" style="font-size: 12px;">
                    <i class="fas fa-info-circle mr-1"></i>Click on a marker to see centre details. Scroll to zoom in/out.
                </p>
            </div>
        </div>

        <!-- Province Listing -->
        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="font-weight-semibold">Vaccination Centres by Province</h4>
                <p class="text-muted">Below is a list of major vaccination centres across Zambia's 10 provinces.</p>
            </div>

            <!-- Lusaka Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Lusaka Province</h5>
                    <div class="centre-item">
                        <strong>University Teaching Hospital (UTH)</strong><br>
                        <small class="text-muted">Nationalist Road, Lusaka</small>
                    </div>
                    <div class="centre-item">
                        <strong>Levy Mwanawasa Hospital</strong><br>
                        <small class="text-muted">Great East Road, Lusaka</small>
                    </div>
                    <div class="centre-item">
                        <strong>Chilenje Level 1 Hospital</strong><br>
                        <small class="text-muted">Chilenje, Lusaka</small>
                    </div>
                    <div class="centre-item">
                        <strong>Matero Level 1 Hospital</strong><br>
                        <small class="text-muted">Matero, Lusaka</small>
                    </div>
                    <div class="centre-item">
                        <strong>Kafue District Hospital</strong><br>
                        <small class="text-muted">Kafue Town</small>
                    </div>
                </div>
            </div>

            <!-- Copperbelt Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Copperbelt Province</h5>
                    <div class="centre-item">
                        <strong>Kitwe Teaching Hospital</strong><br>
                        <small class="text-muted">Kitwe</small>
                    </div>
                    <div class="centre-item">
                        <strong>Ndola Teaching Hospital</strong><br>
                        <small class="text-muted">Ndola</small>
                    </div>
                    <div class="centre-item">
                        <strong>Ronald Ross Hospital</strong><br>
                        <small class="text-muted">Mufulira</small>
                    </div>
                    <div class="centre-item">
                        <strong>Nchanga North Hospital</strong><br>
                        <small class="text-muted">Chingola</small>
                    </div>
                    <div class="centre-item">
                        <strong>Konkola Mine Hospital</strong><br>
                        <small class="text-muted">Chililabombwe</small>
                    </div>
                </div>
            </div>

            <!-- Southern Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Southern Province</h5>
                    <div class="centre-item">
                        <strong>Livingstone General Hospital</strong><br>
                        <small class="text-muted">Livingstone</small>
                    </div>
                    <div class="centre-item">
                        <strong>Choma General Hospital</strong><br>
                        <small class="text-muted">Choma</small>
                    </div>
                    <div class="centre-item">
                        <strong>Monze Mission Hospital</strong><br>
                        <small class="text-muted">Monze</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mazabuka General Hospital</strong><br>
                        <small class="text-muted">Mazabuka</small>
                    </div>
                </div>
            </div>

            <!-- Eastern Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Eastern Province</h5>
                    <div class="centre-item">
                        <strong>Chipata General Hospital</strong><br>
                        <small class="text-muted">Chipata</small>
                    </div>
                    <div class="centre-item">
                        <strong>Petauke District Hospital</strong><br>
                        <small class="text-muted">Petauke</small>
                    </div>
                    <div class="centre-item">
                        <strong>Katete District Hospital</strong><br>
                        <small class="text-muted">Katete</small>
                    </div>
                    <div class="centre-item">
                        <strong>Lundazi District Hospital</strong><br>
                        <small class="text-muted">Lundazi</small>
                    </div>
                </div>
            </div>

            <!-- Central Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Central Province</h5>
                    <div class="centre-item">
                        <strong>Kabwe General Hospital</strong><br>
                        <small class="text-muted">Kabwe</small>
                    </div>
                    <div class="centre-item">
                        <strong>Kapiri Mposhi District Hospital</strong><br>
                        <small class="text-muted">Kapiri Mposhi</small>
                    </div>
                    <div class="centre-item">
                        <strong>Serenje District Hospital</strong><br>
                        <small class="text-muted">Serenje</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mkushi District Hospital</strong><br>
                        <small class="text-muted">Mkushi</small>
                    </div>
                </div>
            </div>

            <!-- Northern Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Northern Province</h5>
                    <div class="centre-item">
                        <strong>Kasama General Hospital</strong><br>
                        <small class="text-muted">Kasama</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mbala General Hospital</strong><br>
                        <small class="text-muted">Mbala</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mpulungu District Hospital</strong><br>
                        <small class="text-muted">Mpulungu</small>
                    </div>
                </div>
            </div>

            <!-- Western Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Western Province</h5>
                    <div class="centre-item">
                        <strong>Lewanika General Hospital</strong><br>
                        <small class="text-muted">Mongu</small>
                    </div>
                    <div class="centre-item">
                        <strong>Senanga District Hospital</strong><br>
                        <small class="text-muted">Senanga</small>
                    </div>
                    <div class="centre-item">
                        <strong>Kalabo District Hospital</strong><br>
                        <small class="text-muted">Kalabo</small>
                    </div>
                </div>
            </div>

            <!-- North-Western Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>North-Western Province</h5>
                    <div class="centre-item">
                        <strong>Solwezi General Hospital</strong><br>
                        <small class="text-muted">Solwezi</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mwinilunga District Hospital</strong><br>
                        <small class="text-muted">Mwinilunga</small>
                    </div>
                    <div class="centre-item">
                        <strong>Kasempa District Hospital</strong><br>
                        <small class="text-muted">Kasempa</small>
                    </div>
                </div>
            </div>

            <!-- Luapula Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Luapula Province</h5>
                    <div class="centre-item">
                        <strong>Mansa General Hospital</strong><br>
                        <small class="text-muted">Mansa</small>
                    </div>
                    <div class="centre-item">
                        <strong>Nchelenge District Hospital</strong><br>
                        <small class="text-muted">Nchelenge</small>
                    </div>
                    <div class="centre-item">
                        <strong>Samfya District Hospital</strong><br>
                        <small class="text-muted">Samfya</small>
                    </div>
                </div>
            </div>

            <!-- Muchinga Province -->
            <div class="col-md-6 col-lg-4">
                <div class="province-card">
                    <h5 class="font-weight-semibold"><i class="fas fa-map-marker-alt text-danger mr-2"></i>Muchinga Province</h5>
                    <div class="centre-item">
                        <strong>Chinsali District Hospital</strong><br>
                        <small class="text-muted">Chinsali</small>
                    </div>
                    <div class="centre-item">
                        <strong>Mpika District Hospital</strong><br>
                        <small class="text-muted">Mpika</small>
                    </div>
                    <div class="centre-item">
                        <strong>Isoka District Hospital</strong><br>
                        <small class="text-muted">Isoka</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="p-4" style="background-color: #e8f5e9; border-radius: 10px;">
                    <h5 class="font-weight-semibold"><i class="fas fa-info-circle text-success mr-2"></i>Important Information</h5>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <p class="text-muted" style="font-size: 13px;">
                                <i class="fas fa-check text-success mr-1"></i>
                                <strong>No appointment needed</strong> - Walk-in vaccinations available at most centres.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted" style="font-size: 13px;">
                                <i class="fas fa-check text-success mr-1"></i>
                                <strong>Free of charge</strong> - All COVID-19 vaccines are provided at no cost.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted" style="font-size: 13px;">
                                <i class="fas fa-check text-success mr-1"></i>
                                <strong>Bring your ID</strong> - NRC, Passport, or Driver's License required.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script>
        // Initialize map centred on Zambia
        var map = L.map('centres-map').setView([-13.1339, 28.2833], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Vaccination centres with coordinates
        var centres = [
            // Lusaka Province
            { name: "University Teaching Hospital", province: "Lusaka", lat: -15.4009, lng: 28.3228 },
            { name: "Levy Mwanawasa Hospital", province: "Lusaka", lat: -15.3744, lng: 28.3527 },
            { name: "Chilenje Level 1 Hospital", province: "Lusaka", lat: -15.4250, lng: 28.2750 },
            { name: "Kafue District Hospital", province: "Lusaka", lat: -15.7667, lng: 28.1833 },

            // Copperbelt Province
            { name: "Kitwe Teaching Hospital", province: "Copperbelt", lat: -12.8024, lng: 28.2134 },
            { name: "Ndola Teaching Hospital", province: "Copperbelt", lat: -12.9587, lng: 28.6366 },
            { name: "Ronald Ross Hospital", province: "Copperbelt", lat: -12.5294, lng: 28.2403 },
            { name: "Nchanga North Hospital", province: "Copperbelt", lat: -12.5294, lng: 27.8636 },

            // Southern Province
            { name: "Livingstone General Hospital", province: "Southern", lat: -17.8419, lng: 25.8544 },
            { name: "Choma General Hospital", province: "Southern", lat: -16.8083, lng: 26.9875 },
            { name: "Monze Mission Hospital", province: "Southern", lat: -16.2833, lng: 27.4833 },
            { name: "Mazabuka General Hospital", province: "Southern", lat: -15.8562, lng: 27.7481 },

            // Eastern Province
            { name: "Chipata General Hospital", province: "Eastern", lat: -13.6333, lng: 32.6500 },
            { name: "Petauke District Hospital", province: "Eastern", lat: -14.2417, lng: 31.3189 },
            { name: "Katete District Hospital", province: "Eastern", lat: -14.0667, lng: 32.0500 },

            // Central Province
            { name: "Kabwe General Hospital", province: "Central", lat: -14.4469, lng: 28.4464 },
            { name: "Kapiri Mposhi District Hospital", province: "Central", lat: -13.9667, lng: 28.6833 },

            // Northern Province
            { name: "Kasama General Hospital", province: "Northern", lat: -10.2069, lng: 31.1808 },
            { name: "Mbala General Hospital", province: "Northern", lat: -8.8400, lng: 31.3658 },

            // Western Province
            { name: "Lewanika General Hospital", province: "Western", lat: -15.2694, lng: 23.1311 },
            { name: "Senanga District Hospital", province: "Western", lat: -16.1167, lng: 23.2667 },

            // North-Western Province
            { name: "Solwezi General Hospital", province: "North-Western", lat: -12.1833, lng: 26.4000 },
            { name: "Mwinilunga District Hospital", province: "North-Western", lat: -11.7356, lng: 25.2694 },

            // Luapula Province
            { name: "Mansa General Hospital", province: "Luapula", lat: -11.1989, lng: 28.8933 },
            { name: "Nchelenge District Hospital", province: "Luapula", lat: -9.3458, lng: 28.7336 },

            // Muchinga Province
            { name: "Chinsali District Hospital", province: "Muchinga", lat: -10.5411, lng: 32.0814 },
            { name: "Mpika District Hospital", province: "Muchinga", lat: -11.8333, lng: 31.4667 }
        ];

        // Add markers
        centres.forEach(function(centre) {
            var marker = L.marker([centre.lat, centre.lng]).addTo(map);
            marker.bindPopup(
                '<strong>' + centre.name + '</strong><br>' +
                '<span style="color: #666;">' + centre.province + ' Province</span><br>' +
                '<small><i class="fas fa-syringe"></i> COVID-19 Vaccination Available</small>'
            );
        });
    </script>
@endsection
