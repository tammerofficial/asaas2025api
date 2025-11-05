@extends('landlord.admin-new.admin-master')

@section('title')
    {{__('Dashboard')}}
@endsection

@section('style')
    <link href="{{ global_asset('assets/landlord/admin/css/modern-dashboard-v2.css') }}" rel="stylesheet">
@endsection

@section('content')
    @inject('healthHelper', 'App\Helpers\SiteHealthHelper')

    @if(!tenant() && $healthHelper->getIssues() > 0)
        <div class="alert alert-danger bg-white rounded mb-4">
            <p><strong class="text-danger">{{$healthHelper->issueMessage()}}</strong></p>
            <p>{{ __('If all necessary server values are not configured correctly, it may affect the system\'s functionality.') }}
                <a class="text-decoration-none" href="{{$healthHelper->getReadMore()['route']}}">{{$healthHelper->getReadMore()['text']}}</a>
            </p>
        </div>
    @endif

    <!-- Stats Cards Section -->
    <div class="dashboard-stats mb-4">
        <div class="row g-4">
            <!-- Total Admins -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="las la-user-shield"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Admins')}}</h6>
                        <h2>{{$total_admin ?? 0}}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Users')}}</h6>
                        <h2>{{$total_user ?? 0}}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Shops (Tenants) -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="mdi mdi-store"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Shops')}}</h6>
                        <h2>{{$all_tenants ?? 0}}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Testimonial -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="mdi mdi-diamond"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Testimonial')}}</h6>
                        <h2>{{$total_testimonial ?? 0}}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Price Plan -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Price Plan')}}</h6>
                        <h2>{{$total_price_plan ?? 0}}</h2>
                    </div>
                </div>
            </div>

            <!-- Total Theme -->
            <div class="col-xl-4 col-md-6">
                <div class="modern-stat-card">
                    <div class="modern-stat-icon">
                        <i class="mdi mdi-palette"></i>
                    </div>
                    <div class="modern-stat-content">
                        <h6>{{__('Total Theme')}}</h6>
                        <h2>
                            @php
                                $themes = getAllThemeSlug();
                            @endphp
                            {{count($themes)}}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="dashboard-charts mb-4">
        <div class="chart-wrapper">
            <h2 class="chart-title">{{__("Amount Per Month In")}} {{date('Y')}}</h2>
            <canvas id="monthlyRaised"></canvas>
        </div>
        <div class="chart-wrapper">
            <h2 class="chart-title">{{__("Amount Per Day In Last 30Days")}}</h2>
            <canvas id="monthlyRaisedPerDay"></canvas>
        </div>
    </div>

    <!-- Recent Order Logs Table -->
    <div class="dashboard-table">
        <div class="p-3 border-bottom">
            <h3 class="mb-0">{{__('Recent Order Logs')}}</h3>
        </div>
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Order ID')}}</th>
                    <th>{{__('User Name')}}</th>
                    <th>{{__('Package Name')}}</th>
                    <th>{{__('Price')}}</th>
                    <th>{{__('Subdomain')}}</th>
                    <th>{{__('Payment Status')}}</th>
                    <th>{{__('Order Status')}}</th>
                    <th>{{__('Created At')}}</th>
                    <th>{{__('Renewed At')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_order_logs ?? [] as $key => $data)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$data->id ?? ''}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->package_name}}</td>
                        <td>{{amount_with_currency_symbol($data->package_price)}}</td>
                        <td>{{$data->tenant_id}}</td>
                        @php
                            $status = ['pending' => 'text-warning', 'complete' => 'text-success'];
                        @endphp
                        <td class="{{$status[$data->payment_status] ?? ''}} text-capitalize">{{$data->payment_status}}</td>
                        <td class="{{$data->status != 'trial' ? ($status[$data->payment_status] ?? '') : 'text-primary'}} text-capitalize">{{$data->status}}</td>
                        <td>{{$data->created_at?->diffForHumans()}}</td>
                        <td>{{$data->updated_at?->diffForHumans()}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">{{__('No orders found')}}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @includeWhen(count($update_info ?? []) > 0, 'landlord.admin.partials.update-info', $update_info ?? [])
@endsection

@section('scripts')
    @if(count($update_info ?? []) > 0)
        <script src="{{asset('assets/landlord/admin/js/update-info.js')}}" defer></script>
    @endif
    
    <script>
        // Monthly Chart
        $.ajax({
            url: '{{route('landlord.admin.home.chart.data.month')}}',
            type: 'POST',
            async: false,
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function (data) {
                new Chart(
                    document.getElementById('monthlyRaised'),
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: '{{__('Amount Received')}}',
                                backgroundColor: '#7f1625',
                                borderColor: '#7f1625',
                                data: data.data,
                                barThickness: 15,
                                hoverBackgroundColor: '#5a0f19',
                                borderRadius: 5,
                                hoverBorderColor: '#5a0f19',
                                minBarLength: 50,
                                indexAxis: "x",
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                        }
                    }
                );
            }
        });

        // Daily Chart
        $.ajax({
            url: '{{route('landlord.admin.home.chart.data.by.day')}}',
            type: 'POST',
            async: false,
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function (data) {
                new Chart(
                    document.getElementById('monthlyRaisedPerDay'),
                    {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: '{{__('Amount Received')}}',
                                backgroundColor: '#7f1625',
                                borderColor: '#7f1625',
                                data: data.data,
                                barThickness: 15,
                                hoverBackgroundColor: '#5a0f19',
                                borderRadius: 5,
                                hoverBorderColor: '#5a0f19',
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                        }
                    }
                );
            }
        });
    </script>
@endsection

