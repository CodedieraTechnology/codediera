@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <div>
            <div class="text-uppercase small text-muted" style="letter-spacing:.12em">Overview</div>
            <h1 class="h3 mb-0">Dashboard</h1>
        </div>
        <a class="btn btn-outline-primary btn-sm" href="{{ route('home') }}" target="_blank" rel="noopener">View Website</a>
    </div>

    <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-4 mb-3">
        <div class="col">
            <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.jobs.index') }}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="text-muted small">Active Jobs</div>
                        <div class="h4 mb-0">{{ $stats['active_jobs'] ?? 0 }}</div>
                    </div>
                    <span class="badge text-bg-primary rounded-pill align-self-start">Live</span>
                </div>
                <div class="text-muted small mt-2">Total jobs: {{ $stats['total_jobs'] ?? 0 }}</div>
            </a>
        </div>
        <div class="col">
            <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.job-applications.index') }}">
                <div class="text-muted small">New Job Applications</div>
                <div class="h4 mb-0">{{ $stats['new_job_applications'] ?? 0 }}</div>
                <div class="text-muted small mt-2">Needs review</div>
            </a>
        </div>
        <div class="col">
            <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.it-intakes.index') }}">
                <div class="text-muted small">Pending IT Intake</div>
                <div class="h4 mb-0">{{ $stats['pending_intakes'] ?? 0 }}</div>
                <div class="text-muted small mt-2">Approval queue</div>
            </a>
        </div>
        <div class="col">
            <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.contact-messages.index') }}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="text-muted small">New Messages</div>
                        <div class="h4 mb-0">{{ $stats['new_contact_messages'] ?? 0 }}</div>
                    </div>
                    @if(($stats['notifications_total'] ?? 0) > 0)
                        <span class="badge text-bg-dark rounded-pill">{{ $stats['notifications_total'] }}</span>
                    @endif
                </div>
                <div class="text-muted small mt-2">Inbox updates</div>
            </a>
        </div>
        <div class="col">
            <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.service-subscriptions.index') }}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <div class="text-muted small">Service Subscriptions</div>
                        <div class="h4 mb-0">{{ $stats['service_paid'] ?? 0 }}</div>
                    </div>
                    @if(($stats['service_expiring_soon'] ?? 0) > 0)
                        <span class="badge text-bg-warning rounded-pill">{{ $stats['service_expiring_soon'] }}</span>
                    @else
                        <span class="badge text-bg-success rounded-pill">OK</span>
                    @endif
                </div>
                <div class="text-muted small mt-2">Active: {{ $stats['service_active'] ?? 0 }}</div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                        <div>
                            <div class="text-uppercase small text-muted" style="letter-spacing:.12em">Activity</div>
                            <div class="fw-semibold">Last 7 days</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 small">
                            <span class="badge rounded-pill" style="background:rgba(13,110,253,.15);color:#0b5ed7">Applications</span>
                            <span class="badge rounded-pill" style="background:rgba(25,135,84,.15);color:#146c43">Messages</span>
                            <span class="badge rounded-pill" style="background:rgba(255,193,7,.25);color:#856404">IT Intake</span>
                        </div>
                    </div>
                    <div style="height: 320px">
                        <canvas id="adminActivityChart" aria-label="Admin activity chart" role="img"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-end gap-2 mb-3">
                        <div>
                            <div class="text-uppercase small text-muted" style="letter-spacing:.12em">Notifications</div>
                            <div class="fw-semibold">What needs attention</div>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.dashboard') }}">Refresh</a>
                    </div>

                    @forelse($notifications ?? [] as $n)
                        <a class="d-flex gap-2 text-decoration-none text-dark py-2" href="{{ $n['url'] }}">
                            <span class="rounded-circle flex-shrink-0 mt-1"
                                  style="width:10px;height:10px;
                                  background: {{ ($n['tone'] ?? '') === 'primary' ? '#0d6efd' : ((($n['tone'] ?? '') === 'success') ? '#198754' : '#ffc107') }};">
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $n['title'] }}</div>
                                <div class="text-muted small">{{ $n['text'] }}</div>
                                <div class="text-muted small">{{ $n['time']?->diffForHumans() }}</div>
                            </div>
                        </a>
                        <div class="border-top" style="opacity:.08"></div>
                    @empty
                        <div class="text-muted">All caught up. No new notifications.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-2">
                <div>
                    <div class="text-uppercase small text-muted" style="letter-spacing:.12em">Shortcuts</div>
                    <div class="fw-semibold">Manage website & operations</div>
                </div>
            </div>

            <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-3">
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.sliders.index') }}">
                        <div class="fw-semibold">Slider</div>
                        <div class="text-muted small">Manage homepage slides, captions and buttons.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.services.index') }}">
                        <div class="fw-semibold">Services</div>
                        <div class="text-muted small">Add and organize services you render.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.service-subscriptions.index') }}">
                        <div class="fw-semibold">Service Subscriptions</div>
                        <div class="text-muted small">View purchased users, status, duration and expiration.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.projects.index') }}">
                        <div class="fw-semibold">Projects</div>
                        <div class="text-muted small">Publish projects and highlight your work.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.team.index') }}">
                        <div class="fw-semibold">Team</div>
                        <div class="text-muted small">Manage team members shown on the website.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.ctas.index') }}">
                        <div class="fw-semibold">Home CTAs</div>
                        <div class="text-muted small">Control homepage call-to-action blocks.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.digital-skills.index') }}">
                        <div class="fw-semibold">Digital Skills</div>
                        <div class="text-muted small">Manage the digital skills offerings list.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.site-settings.edit') }}">
                        <div class="fw-semibold">Site Settings</div>
                        <div class="text-muted small">Set colors, logo, favicon and metadata.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.contact-settings.edit') }}">
                        <div class="fw-semibold">Contact Settings</div>
                        <div class="text-muted small">Update contact details and map embed.</div>
                    </a>
                </div>
                <div class="col">
                    <a class="card card-body text-decoration-none text-dark h-100" href="{{ route('admin.mail-settings.edit') }}">
                        <div class="fw-semibold">Email Settings</div>
                        <div class="text-muted small">Configure SMTP used for outgoing emails.</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('adminActivityChart');
            if (!el || !window.Chart) return;

            var data = @json($chart ?? []);
            var ctx = el.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [
                        {
                            label: 'Applications',
                            data: data.applications || [],
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.12)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Messages',
                            data: data.messages || [],
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.12)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'IT Intake',
                            data: data.intakes || [],
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255, 193, 7, 0.18)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2,
                            pointHoverRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
@endsection
