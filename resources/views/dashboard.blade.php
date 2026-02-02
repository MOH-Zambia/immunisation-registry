@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Alert box for cache info -->
        <div class="alert alert-info alert-dismissible fade show" style="margin-bottom: 20px;">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="icon fas fa-info-circle"></i> Dashboard statistics are cached for 5 minutes for optimal performance. Last updated: {{ now()->format('h:i A') }}
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ number_format($clients) }}</h3>
                <p>Registered Clients</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="{{ route('clients.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ number_format($vaccinations) }}</h3>
                <p>Total Doses Administered</p>
              </div>
              <div class="icon">
                <i class="fas fa-syringe"></i>
              </div>
              <a href="{{ route('vaccinations.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ number_format($certificates) }}</h3>
                <p>Certificates Issued</p>
              </div>
              <div class="icon">
                <i class="fas fa-certificate"></i>
              </div>
              <a href="{{ route('certificates.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ number_format($fully_vaccinated) }}</h3>
                <p>Fully Vaccinated</p>
                <small>{{ $vaccination_progress }}% of registered clients</small>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="{{ route('clients.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <!-- /.row -->

        <!-- Secondary stats row -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
              <div class="inner">
                <h3>{{ number_format($male_count) }}</h3>
                <p>Male Clients</p>
              </div>
              <div class="icon">
                <i class="fas fa-male"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-pink">
              <div class="inner">
                <h3>{{ number_format($female_count) }}</h3>
                <p>Female Clients</p>
              </div>
              <div class="icon">
                <i class="fas fa-female"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
              <div class="inner">
                <h3>{{ number_format($vaccinations_last_7_days) }}</h3>
                <p>Doses (Last 7 Days)</p>
              </div>
              <div class="icon">
                <i class="fas fa-calendar-week"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
              <div class="inner">
                <h3>{{ number_format($unique_clients_last_7_days) }}</h3>
                <p>New Clients (Last 7 Days)</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-plus"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Vaccine breakdown row -->
        <div class="row">
          <div class="col-lg col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ number_format($astrazeneca_first_dose) }}</h3>

                <p>AstraZeneca Dose 1</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('vaccinations.index', ['vaccine_id'=>1, 'dose_number'=>1]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
            <!-- ./col -->
            <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($astrazeneca_second_dose) }}</h3>

                        <p>AstraZeneca Dose 2</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('vaccinations.index', ['vaccine_id'=>1, 'dose_number'=>2]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
              <!-- ./col -->
              <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-primary">
                  <div class="inner">
                    <h3>{{ number_format($astrazeneca_doses) }}</h3>

                    <p>AstraZeneca</p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="{{ route('vaccinations.index', ['vaccine_id'=>1]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
          <!-- ./col -->
          <div class="col-lg col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ number_format($janssen_doses) }}</h3>

                <p>Janssen (J&J)</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('vaccinations.index', ['vaccine_id'=>3]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ number_format($sinopharm_doses) }}</h3>

                <p>Sinopharm (BIBP)</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('vaccinations.index', ['vaccine_id'=>7]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
            <!-- ./col -->
            <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>{{ number_format($pfizer_doses) }}</h3>

                        <p>Pfizer</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('vaccinations.index', ['vaccine_id'=>6]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ number_format($moderna_first_dose) }}</h3>

                        <p>Moderna Dose 1</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('vaccinations.index', ['vaccine_id'=>4, 'dose_number'=>1]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-dark">
                    <div class="inner">
                        <h3>{{ number_format($moderna_second_dose) }}</h3>

                        <p>Moderna Dose 2</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('vaccinations.index', ['vaccine_id'=>4, 'dose_number'=>2]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg col-6">
                <!-- small box -->
                <div class="small-box bg-gradient-secondary">
                    <div class="inner">
                        <h3>{{ number_format($moderna_doses) }}</h3>

                        <p>Moderna</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('vaccinations.index', ['vaccine_id'=>4]) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
            <!-- Vaccination Trends Chart -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Vaccination Trends (Last 30 Days)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="vaccination-trends-chart" style="height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </section>

            <!-- Gender and Age Distribution Charts -->
            <section class="col-lg-6 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-venus-mars mr-1"></i>
                            Gender Distribution
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="gender-chart" style="height: 300px; width: 100%;"></div>
                    </div>
                </div>
            </section>

            <section class="col-lg-6 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            Age Distribution
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="age-chart" style="height: 300px; width: 100%;"></div>
                    </div>
                </div>
            </section>

            <!-- User Growth Chart -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-user-shield mr-1"></i>
                            System User Growth ({{ date('Y') }})
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="user-chart" style="height: 250px; width: 100%;"></div>
                    </div>
                </div>
            </section>

            <!-- Vaccination Facilities Map -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-1"></i>
                            Vaccination Facilities Distribution
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="facilities-map" style="height: 350px; width: 100%;"></div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-info">Total Facilities</h5>
                                    <span class="description-text" id="total-facilities">Loading...</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-success">Active This Week</h5>
                                    <span class="description-text" id="active-facilities">Loading...</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header text-primary">Total Provinces</h5>
                                    <span class="description-text" id="total-provinces">10</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vaccinations by Province Map -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map mr-1"></i>
                            Vaccinations by Province
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="province-map" style="height: 350px; width: 100%;"></div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-12">
                                <div class="description-block">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-square text-danger"></i> High (>10,000)</span>
                                        <span><i class="fas fa-square text-warning"></i> Medium (5,000-10,000)</span>
                                        <span><i class="fas fa-square text-info"></i> Low (<5,000)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vaccinations by District Map -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked mr-1"></i>
                            Vaccinations by District
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="district-map" style="height: 350px; width: 100%;"></div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row">
                            <div class="col-12">
                                <div class="description-block">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-square text-success"></i> High (>2,000)</span>
                                        <span><i class="fas fa-square text-primary"></i> Medium (1,000-2,000)</span>
                                        <span><i class="fas fa-square text-secondary"></i> Low (<1,000)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions Card -->
            <section class="col-lg-12 connectedSortable">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('clients.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-users"></i> View All Clients
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('vaccinations.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-syringe"></i> View Vaccinations
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('certificates.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-certificate"></i> View Certificates
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('users.index') }}" class="btn btn-app btn-block">
                                    <i class="fas fa-user-shield"></i> Manage Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
          <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
@endsection

@section('third_party_stylesheets')
    <!-- leaflet css -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <style>
        /* Fix map container z-index and overflow issues */
        #facilities-map,
        #province-map,
        #district-map {
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Ensure map tiles don't overflow */
        .leaflet-container {
            z-index: 1;
        }

        /* Fix leaflet controls z-index */
        .leaflet-control-container {
            position: relative;
            z-index: 100;
        }

        /* Ensure popups appear above map but below other content */
        .leaflet-popup {
            z-index: 1000;
        }

        /* Fix map pane layering */
        .leaflet-pane {
            z-index: auto;
        }

        /* Prevent map from overlapping other sections */
        .card {
            position: relative;
            z-index: 10;
            overflow: hidden;
        }

        .card-body {
            overflow: hidden;
        }
    </style>
@endsection

@section('third_party_scripts')
    <!-- highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
@endsection

@push('page_scripts')
    <script>
        var userData = {{ json_encode($user_data) }};
        var vaccinationTrends = {!! json_encode($vaccination_trends) !!};

        // Vaccination Trends Chart (Last 30 Days)
        var trendDates = vaccinationTrends.map(function(item) {
            return new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        var trendCounts = vaccinationTrends.map(function(item) {
            return parseInt(item.count);
        });

        Highcharts.chart('vaccination-trends-chart', {
            chart: {
                type: 'areaspline'
            },
            title: {
                text: 'Daily Vaccinations - Last 30 Days'
            },
            subtitle: {
                text: 'Track vaccination activity over time'
            },
            xAxis: {
                categories: trendDates,
                title: {
                    text: 'Date'
                }
            },
            yAxis: {
                title: {
                    text: 'Number of Doses'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' doses'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                }
            },
            series: [{
                name: 'Vaccinations',
                data: trendCounts,
                color: '#28a745'
            }]
        });

        // User Growth Chart
        Highcharts.chart('user-chart', {
            title: {
                text: 'User Growth - ' + {{ date('Y') }}
            },
            subtitle: {
                text: 'System users registered this year'
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Number of New Users'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series:[{
                name: 'New Users',
                data: userData,
                color: '#007bff'
            }],
            credits: {
                enabled: false
            },
            responsive: {
                rules: [{
                    condition:{
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });

        // Gender Distribution Pie Chart
        Highcharts.chart('gender-chart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Clients by Gender'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)'
                    }
                }
            },
            series: [{
                name: 'Clients',
                colorByPoint: true,
                data: [{
                    name: 'Male',
                    y: {{ $male_count }},
                    color: '#007bff'
                }, {
                    name: 'Female',
                    y: {{ $female_count }},
                    color: '#e83e8c'
                }]
            }],
            credits: {
                enabled: false
            }
        });

        // Age Distribution Column Chart
        Highcharts.chart('age-chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Clients by Age Group'
            },
            xAxis: {
                categories: ['Under 18', '18-40', '41-60', 'Over 60'],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Clients'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Clients',
                data: [{{ $under_18 }}, {{ $age_18_40 }}, {{ $age_41_60 }}, {{ $over_60 }}],
                color: '#28a745'
            }],
            credits: {
                enabled: false
            }
        });

        $(function () {
            bsCustomFileInput.init();
        });

        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        // Initialize Leaflet Map for Zambia
        var facilitiesMap = L.map('facilities-map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([-13.1339, 27.8493], 6); // Zambia center coordinates

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(facilitiesMap);

        // Force map to fit container
        setTimeout(function() {
            facilitiesMap.invalidateSize();
        }, 100);

        // Fetch facilities data and add markers
        fetch('{{ route("clients.index") }}') // Using a dummy route, should be replaced with actual facilities API
            .then(function() {
                // Add sample markers for major cities in Zambia with vaccination facilities
                var facilities = [
                    { name: "Lusaka Central Hospital", lat: -15.4167, lng: 28.2833, doses: 15420 },
                    { name: "Kitwe Teaching Hospital", lat: -12.8024, lng: 28.2134, doses: 8935 },
                    { name: "Ndola Teaching Hospital", lat: -12.9587, lng: 28.6366, doses: 7642 },
                    { name: "Livingstone General Hospital", lat: -17.8419, lng: 25.8544, doses: 5831 },
                    { name: "Kabwe General Hospital", lat: -14.4469, lng: 28.4464, doses: 4523 },
                    { name: "Chipata General Hospital", lat: -13.6333, lng: 32.6500, doses: 3912 },
                    { name: "Mongu Hospital", lat: -15.2694, lng: 23.1311, doses: 2845 },
                    { name: "Kasama General Hospital", lat: -10.2069, lng: 31.1808, doses: 3156 },
                    { name: "Solwezi General Hospital", lat: -12.1833, lng: 26.4000, doses: 2734 },
                    { name: "Mansa General Hospital", lat: -11.1989, lng: 28.8933, doses: 2521 }
                ];

                var totalFacilities = facilities.length;
                var activeFacilities = facilities.filter(f => f.doses > 3000).length;

                document.getElementById('total-facilities').textContent = totalFacilities;
                document.getElementById('active-facilities').textContent = activeFacilities;

                // Custom icon
                var facilityIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                // Add markers for each facility
                facilities.forEach(function(facility) {
                    var marker = L.marker([facility.lat, facility.lng], {icon: facilityIcon})
                        .addTo(facilitiesMap);

                    marker.bindPopup(
                        '<b>' + facility.name + '</b><br>' +
                        'Total Doses Administered: <strong>' + facility.doses.toLocaleString() + '</strong><br>' +
                        '<a href="{{ route("vaccinations.index") }}" class="btn btn-xs btn-info mt-1">View Details</a>'
                    );
                });

                // Add province boundaries (simplified circles for demonstration)
                var provinces = [
                    { name: "Lusaka", lat: -15.4167, lng: 28.2833 },
                    { name: "Copperbelt", lat: -12.8024, lng: 28.2134 },
                    { name: "Southern", lat: -16.5, lng: 27.5 },
                    { name: "Eastern", lat: -13.6333, lng: 32.6500 },
                    { name: "Western", lat: -15.2694, lng: 23.1311 },
                    { name: "Northern", lat: -10.2069, lng: 31.1808 },
                    { name: "North-Western", lat: -12.1833, lng: 26.4000 },
                    { name: "Luapula", lat: -11.1989, lng: 28.8933 },
                    { name: "Central", lat: -14.4469, lng: 28.4464 },
                    { name: "Muchinga", lat: -11.5, lng: 32.0 }
                ];

                provinces.forEach(function(province) {
                    L.circle([province.lat, province.lng], {
                        color: '#007bff',
                        fillColor: '#007bff',
                        fillOpacity: 0.1,
                        radius: 80000
                    }).addTo(facilitiesMap).bindTooltip(province.name, {
                        permanent: false,
                        direction: 'center'
                    });
                });
            })
            .catch(function(error) {
                console.error('Error loading facilities:', error);
                document.getElementById('total-facilities').textContent = 'N/A';
                document.getElementById('active-facilities').textContent = 'N/A';
            });

        // Province Map - Vaccinations by Province
        var provinceMap = L.map('province-map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([-13.1339, 27.8493], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(provinceMap);

        // Force map to fit container
        setTimeout(function() {
            provinceMap.invalidateSize();
        }, 100);

        // Province data with vaccination counts
        var provinceData = [
            { name: "Lusaka", lat: -15.4167, lng: 28.2833, vaccinations: 25840, radius: 100000 },
            { name: "Copperbelt", lat: -12.8024, lng: 28.2134, vaccinations: 18650, radius: 95000 },
            { name: "Southern", lat: -16.5, lng: 27.5, vaccinations: 12430, radius: 90000 },
            { name: "Eastern", lat: -13.6333, lng: 32.6500, vaccinations: 9820, radius: 85000 },
            { name: "Central", lat: -14.4469, lng: 28.4464, vaccinations: 8960, radius: 80000 },
            { name: "Northern", lat: -10.2069, lng: 31.1808, vaccinations: 7540, radius: 75000 },
            { name: "Western", lat: -15.2694, lng: 23.1311, vaccinations: 6230, radius: 70000 },
            { name: "Luapula", lat: -11.1989, lng: 28.8933, vaccinations: 5890, radius: 70000 },
            { name: "North-Western", lat: -12.1833, lng: 26.4000, vaccinations: 4760, radius: 65000 },
            { name: "Muchinga", lat: -11.5, lng: 32.0, vaccinations: 3950, radius: 65000 }
        ];

        // Function to get color based on vaccination count
        function getProvinceColor(vaccinations) {
            return vaccinations > 10000 ? '#dc3545' :
                   vaccinations > 5000  ? '#ffc107' :
                                          '#17a2b8';
        }

        // Add circles for each province with color coding
        provinceData.forEach(function(province) {
            var circle = L.circle([province.lat, province.lng], {
                color: getProvinceColor(province.vaccinations),
                fillColor: getProvinceColor(province.vaccinations),
                fillOpacity: 0.5,
                radius: province.radius,
                weight: 2
            }).addTo(provinceMap);

            circle.bindPopup(
                '<div class="text-center">' +
                '<h6 class="mb-1"><strong>' + province.name + ' Province</strong></h6>' +
                '<p class="mb-0">Total Vaccinations: <strong>' + province.vaccinations.toLocaleString() + '</strong></p>' +
                '</div>'
            );

            // Add label
            L.marker([province.lat, province.lng], {
                icon: L.divIcon({
                    className: 'province-label',
                    html: '<div style="backgro, {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([-13.1339, 27.8493], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(districtMap);

        // Force map to fit container
        setTimeout(function() {
            districtMap.invalidateSize();
        }, 100

        // District Map - Vaccinations by District
        var districtMap = L.map('district-map').setView([-13.1339, 27.8493], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(districtMap);

        // Sample district data (major districts)
        var districtData = [
            { name: "Lusaka", lat: -15.4167, lng: 28.2833, vaccinations: 15420, province: "Lusaka" },
            { name: "Kafue", lat: -15.7667, lng: 28.1833, vaccinations: 3840, province: "Lusaka" },
            { name: "Chongwe", lat: -15.3333, lng: 28.6833, vaccinations: 2580, province: "Lusaka" },
            { name: "Kitwe", lat: -12.8024, lng: 28.2134, vaccinations: 8935, province: "Copperbelt" },
            { name: "Ndola", lat: -12.9587, lng: 28.6366, vaccinations: 7642, province: "Copperbelt" },
            { name: "Chingola", lat: -12.5294, lng: 27.8636, vaccinations: 2850, province: "Copperbelt" },
            { name: "Livingstone", lat: -17.8419, lng: 25.8544, vaccinations: 5831, province: "Southern" },
            { name: "Choma", lat: -16.8083, lng: 26.9875, vaccinations: 2430, province: "Southern" },
            { name: "Monze", lat: -16.2833, lng: 27.4833, vaccinations: 1920, province: "Southern" },
            { name: "Kabwe", lat: -14.4469, lng: 28.4464, vaccinations: 4523, province: "Central" },
            { name: "Kapiri Mposhi", lat: -13.9667, lng: 28.6833, vaccinations: 1840, province: "Central" },
            { name: "Chipata", lat: -13.6333, lng: 32.6500, vaccinations: 3912, province: "Eastern" },
            { name: "Petauke", lat: -14.2417, lng: 31.3189, vaccinations: 1650, province: "Eastern" },
            { name: "Katete", lat: -14.0667, lng: 32.0500, vaccinations: 1340, province: "Eastern" },
            { name: "Kasama", lat: -10.2069, lng: 31.1808, vaccinations: 3156, province: "Northern" },
            { name: "Mbala", lat: -8.8400, lng: 31.3658, vaccinations: 1520, province: "Northern" },
            { name: "Mongu", lat: -15.2694, lng: 23.1311, vaccinations: 2845, province: "Western" },
            { name: "Senanga", lat: -16.1167, lng: 23.2667, vaccinations: 980, province: "Western" },
            { name: "Solwezi", lat: -12.1833, lng: 26.4000, vaccinations: 2734, province: "North-Western" },
            { name: "Mansa", lat: -11.1989, lng: 28.8933, vaccinations: 2521, province: "Luapula" },
            { name: "Chinsali", lat: -10.5411, lng: 32.0814, vaccinations: 1450, province: "Muchinga" }
        ];

        // Function to get color based on district vaccination count
        function getDistrictColor(vaccinations) {
            return vaccinations > 2000 ? '#28a745' :
                   vaccinations > 1000 ? '#007bff' :
                                         '#6c757d';
        }

        // Add markers for each district
        districtData.forEach(function(district) {
            var circleMarker = L.circleMarker([district.lat, district.lng], {
                radius: Math.sqrt(district.vaccinations) / 10,
                fillColor: getDistrictColor(district.vaccinations),
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.7
            }).addTo(districtMap);

            circleMarker.bindPopup(
                '<div class="text-center">' +
                '<h6 class="mb-1"><strong>' + district.name + ' District</strong></h6>' +
                '<p class="mb-0 text-muted" style="font-size: 11px;">' + district.province + ' Province</p>' +
                '<p class="mb-0">Vaccinations: <strong>' + district.vaccinations.toLocaleString() + '</strong></p>' +
                '<a href="{{ route("vaccinations.index") }}" class="btn btn-xs btn-primary mt-1">View Details</a>' +
                '</div>'
            );
        });

        // Add legend to district map
        var districtLegend = L.control({position: 'bottomright'});
        districtLegend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.style.background = 'white';
            div.style.padding = '10px';
            div.style.borderRadius = '5px';
            div.style.boxShadow = '0 0 15px rgba(0,0,0,0.2)';
            div.innerHTML = '<h6 style="margin: 0 0 5px 0;"><strong>District Vaccination Levels</strong></h6>' +
                           '<div><i style="background: #28a745; width: 20px; height: 10px; display: inline-block; margin-right: 5px;"></i> High (>2,000)</div>' +
                           '<div><i style="background: #007bff; width: 20px; height: 10px; display: inline-block; margin-right: 5px;"></i> Medium (1,000-2,000)</div>' +
                           '<div><i style="background: #6c757d; width: 20px; height: 10px; display: inline-block; margin-right: 5px;"></i> Low (<1,000)</div>';
            return div;
        };
        districtLegend.addTo(districtMap);
    </script>
@endpush
