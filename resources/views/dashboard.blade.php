@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endpush

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div style="padding:20px">
        <form id="filterForm" class="form-inline">
            <div class="form-group mr-3">
                <label style="font-weight: 600;" for="dateRange">Date Range:</label>
                <input type="text" id="dateRange" class="form-control ml-2" style="width: 250px;" placeholder="Select date range">
            </div>

            <div class="form-group mr-3">
                <label style="font-weight: 600;" for="platformFilter">Platform:</label>
                <select id="platformFilter" class="form-control ml-2" style="width: 150px;">
                    <option value="">All Platforms</option>
                    @foreach($platforms as $platform)
                    <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="applyFilter" class="btn btn-primary">Apply Filters</button>
            <button type="button" id="resetFilter" class="btn btn-secondary ml-2">Reset</button>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Shares</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalShares">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-share fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Unique Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="uniqueUsers">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unique IPs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="uniqueIps">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="shadow" style="position: relative;height: 300px;margin-bottom: 30px;background: white;padding: 20px;border-radius: 4px;">
                <canvas id="platformChart"></canvas>
                <div class="loading" style="display: none;display: flex;justify-content: center;align-items: center;height: 100%;color: #999;">Loading...</div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="shadow" style="position: relative;height: 300px;margin-bottom: 30px;background: white;padding: 20px;border-radius: 4px;">
                <canvas id="dateChart"></canvas>
                <div class="loading" style="display: none;display: flex;justify-content: center;align-items: center;height: 100%;color: #999;">Loading...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>

<script>
    const colors = {
        facebook: '#3b5998',
        twitter: '#1da1f2',
        whatsapp: '#25d366',
        telegram: '#0088cc',
        email: '#666'
    };

    const platforms = @json($platforms).map(p => p.name);

    let platformChart, dateChart;

    $('#dateRange').daterangepicker({
        startDate: moment().subtract(30, 'days'),
        endDate: moment(),
        ranges: {
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last 3 Months': [moment().subtract(89, 'days'), moment()],
            'Last Year': [moment().subtract(365, 'days'), moment()],
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    function getFilterParams() {
        const dateRange = $('#dateRange').data('daterangepicker');
        const startDate = dateRange.startDate.format('YYYY-MM-DD');
        const endDate = dateRange.endDate.format('YYYY-MM-DD');
        const platformId = $('#platformFilter').val();

        const params = new URLSearchParams();
        params.append('start_date', startDate);
        params.append('end_date', endDate);
        if (platformId) params.append('platform_id', platformId);

        return params.toString();
    }

    function loadStats() {
        const params = getFilterParams();
        fetch(`/shares/stats?${params}`)
            .then(res => res.json())
            .then(data => {
                $('#totalShares').text(data.total_shares.toLocaleString());
                $('#uniqueUsers').text(data.unique_users.toLocaleString());
                $('#uniqueIps').text(data.unique_ips.toLocaleString());
            })
            .catch(err => console.error('Error loading stats:', err));
    }

    function loadPlatformChart() {
        const params = getFilterParams();
        const container = $('#platformChart').parent();
        container.find('.loading').show();

        fetch(`/shares/by-platform?${params}`)
            .then(res => res.json())
            .then(data => {
                container.find('.loading').hide();

                const labels = data.map(d => d.platform_name);
                const values = data.map(d => d.total);
                const chartColors = data.map(d => colors[d.platform_name.toLowerCase()]);

                if (platformChart) platformChart.destroy();

                platformChart = new Chart($('#platformChart')[0], {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: chartColors,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Shares by Platform'
                            }
                        }
                    }
                });
            })
            .catch(err => {
                container.find('.loading').hide();
                console.error('Error loading platform chart:', err);
            });
    }

    function loadDateChart() {
        const params = getFilterParams();
        const container = $('#dateChart').parent();
        container.find('.loading').show();

        fetch(`/shares/by-date?${params}`)
            .then(res => res.json())
            .then(data => {
                container.find('.loading').hide();

                const groupedByDate = {};
                data.forEach(d => {
                    if (!groupedByDate[d.date]) {
                        groupedByDate[d.date] = {};
                    }
                    groupedByDate[d.date][d.platform_name.toLowerCase()] = d.total;
                });

                const dates = Object.keys(groupedByDate).sort();

                const datasets = platforms.map(platform => ({
                    label: platform,
                    data: dates.map(date => groupedByDate[date][platform.toLowerCase()] || 0),
                    backgroundColor: colors[platform.toLowerCase()],
                    borderColor: colors[platform.toLowerCase()],
                    borderWidth: 2,
                    tension: 0.4,
                    fill: false
                }));

                if (dateChart) dateChart.destroy();

                dateChart = new Chart($('#dateChart')[0], {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            title: {
                                display: true,
                                text: 'Shares Over Time'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .catch(err => {
                container.find('.loading').hide();
                console.error('Error loading date chart:', err);
            });
    }

    $('#applyFilter').click(function() {
        loadStats();
        loadPlatformChart();
        loadDateChart();
    });

    $('#resetFilter').click(function() {
        $('#dateRange').data('daterangepicker').setStartDate(moment().subtract(30, 'days'));
        $('#dateRange').data('daterangepicker').setEndDate(moment());
        $('#platformFilter').val('');
        loadStats();
        loadPlatformChart();
        loadDateChart();
    });

    $(document).ready(function() {
        loadStats();
        loadPlatformChart();
        loadDateChart();
    });
</script>
@endpush
