@extends('layouts.app')

@section('third_party_stylesheets')
    <!-- leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
@endsection

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
          <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box">
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
            <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
              <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
          <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box">
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
          <div class="col-lg-2 col-4">
            <!-- small box -->
            <div class="small-box">
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
            <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
            <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
            <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
            <div class="col-lg-2 col-4">
                <!-- small box -->
                <div class="small-box">
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
            <section class="col-lg-12 connectedSortable">
                <!-- Users card -->
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
                <!-- /.card -->
            </section>

            <section class="col-lg-12 connectedSortable">
                <!-- Users card -->
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-users mr-1"></i>
                            User Growth ({{ date('Y') }})
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="user-chart" style="height: 250px; width: 100%;"></div>
                    </div>
                </div>
                <!-- /.card-body-->
            </section>

            <section class="col-lg-12 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Vaccinations
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="vaccinations-bar-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>

            <section class="col-lg-12 connectedSortable">
                <!-- Map card -->
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Vaccine Doses
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                    <!-- /.card-body-->

                    <div class="card-footer bg-transparent">
                        <div class="row">
                            <div class="col-4 text-center">
                                <div id="sparkline-1"></div>
                                <div class="text-white">Visitors</div>
                            </div>
                            <!-- ./col -->
                            <div class="col-4 text-center">
                                <div id="sparkline-2"></div>
                                <div class="text-white">Online</div>
                            </div>
                            <!-- ./col -->
                            <div class="col-4 text-center">
                                <div id="sparkline-3"></div>
                                <div class="text-white">Sales</div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.card -->
            </section>
          <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
@endsection

@section('third_party_scripts')
    <!-- highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <!-- leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script type="text/javascript" src="https://leafletjs.com/examples/choropleth/us-states.js"></script>
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


        $(function () {
            bsCustomFileInput.init();
        });

        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        var map = L.map('map').setView([37.8, -96], 4);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/light-v9',
            tileSize: 512,
            zoomOffset: -1
        }).addTo(map);

        // get color depending on population density value
        function getColor(d) {
            return d > 1000 ? '#800026' :
                d > 500  ? '#BD0026' :
                    d > 200  ? '#E31A1C' :
                        d > 100  ? '#FC4E2A' :
                            d > 50   ? '#FD8D3C' :
                                d > 20   ? '#FEB24C' :
                                    d > 10   ? '#FED976' :
                                        '#FFEDA0';
        }

        function style(feature) {
            return {
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7,
                fillColor: getColor(feature.properties.density)
            };
        }

        var geojson = L.geoJson(statesData, {
            style: style,
        }).addTo(map);
    </script>
@endpush
