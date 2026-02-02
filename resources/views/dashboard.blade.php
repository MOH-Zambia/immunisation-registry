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
    </script>
@endpush
