@extends('layouts.admin')

@section('title', 'Notification & Messaging Center')
@section('page_heading', 'Notification Templates & Bulk Broadcast')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Notifications</li>
@endsection

@section('content')
<div class="row">
    <!-- Templates List & Form -->
    <div class="col-lg-7">
        <div class="card card-outline card-indigo shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-bell mr-1 text-indigo"></i> Configured Automated Notification Templates
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Template Name</th>
                                <th>Channel</th>
                                <th>Trigger Event</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $tmpl)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $tmpl->name }}</div>
                                        <div class="text-xs text-muted font-monospace mt-1">{{ Str::limit($tmpl->body, 70) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $tmpl->channel === 'whatsapp' ? 'success' : ($tmpl->channel === 'sms' ? 'primary' : 'info') }}">
                                            {{ strtoupper($tmpl->channel) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-monospace">{{ $tmpl->trigger_event }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $tmpl->is_active ? 'success' : 'secondary' }}">
                                            {{ $tmpl->is_active ? 'Active' : 'Disabled' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No notification templates configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Template Card -->
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-plus-circle mr-1 text-primary"></i> Add New Notification Template
                </h3>
            </div>
            <form action="{{ route('admin.notifications.templates.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Template Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Exam Schedule Alert" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Channel</label>
                            <select name="channel" class="form-control" required>
                                <option value="sms">SMS</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="email">Email</option>
                                <option value="push">Push</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Event Trigger</label>
                            <select name="trigger_event" class="form-control" required>
                                <option value="fee_due">fee_due</option>
                                <option value="fee_received">fee_received</option>
                                <option value="absent">absent</option>
                                <option value="test_result">test_result</option>
                                <option value="admission">admission</option>
                                <option value="general">general</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Subject (Optional)</label>
                            <input type="text" name="subject" class="form-control" placeholder="Email / Push Subject">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Template Body (Variables: @{{student_name}}, @{{due_amount}}, etc.)</label>
                            <textarea name="body" class="form-control" rows="3" placeholder="Dear Parent, ..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Bulk Announcement Broadcast -->
    <div class="col-lg-5">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-bullhorn mr-1 text-warning"></i> Quick Broadcast Announcement
                </h3>
            </div>
            <form action="{{ route('admin.notifications.announce') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Broadcast Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Diwali Holiday Notice" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Target Audience</label>
                        <select name="audience" class="form-control" required>
                            <option value="all">All Active Students & Parents</option>
                            <option value="by_course">By Course Aspirants</option>
                            <option value="by_batch">By Specific Batch</option>
                            <option value="fee_defaulters">Overdue Fee Defaulters Only</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Dispatch Channels</label>
                        <div class="d-flex gap-3 mt-1">
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" type="checkbox" id="ch_sms" name="channels[]" value="sms" checked>
                                <label for="ch_sms" class="custom-control-label">SMS</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" type="checkbox" id="ch_wa" name="channels[]" value="whatsapp" checked>
                                <label for="ch_wa" class="custom-control-label">WhatsApp</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="ch_email" name="channels[]" value="email">
                                <label for="ch_email" class="custom-control-label">Email</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Message Content</label>
                        <textarea name="message" class="form-control" rows="4" maxlength="500" placeholder="Type your broadcast message here..." required></textarea>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-paper-plane mr-1"></i> Send Broadcast
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
