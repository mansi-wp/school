@extends('layouts.admin')

@section('title', 'Institute Settings')
@section('page_heading', 'Institute & Academic System Configuration')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-indigo shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-cogs mr-1 text-indigo"></i> General & Financial Parameters
                </h3>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <h5 class="text-indigo font-weight-bold mb-3 border-bottom pb-2">
                        <i class="fas fa-school mr-1"></i> Institute Identity
                    </h5>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label font-weight-bold">Institute Name</label>
                            <input type="text" name="institute_name" class="form-control" value="{{ $settings['institute_name'] ?? 'Apex Coaching' }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Institute Code</label>
                            <input type="text" name="institute_code" class="form-control" value="{{ $settings['institute_code'] ?? 'APEX-EDU' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Official Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'contact@apexcoaching.edu' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Helpdesk Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '+91 98765 43210' }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">Campus Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $settings['address'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <h5 class="text-indigo font-weight-bold mt-3 mb-3 border-bottom pb-2">
                        <i class="fas fa-rupee-sign mr-1"></i> Fee & Financial Policies
                    </h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">GST Number</label>
                            <input type="text" name="gst_number" class="form-control" value="{{ $settings['gst_number'] ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '₹' }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Receipt Number Prefix</label>
                            <input type="text" name="receipt_prefix" class="form-control" value="{{ $settings['receipt_prefix'] ?? 'APEX/REC/2026/' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Default Late Fee / Day (₹)</label>
                            <input type="number" step="0.01" name="late_fee_per_day" class="form-control" value="{{ $settings['late_fee_per_day'] ?? '50.00' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Grace Days Allowed</label>
                            <input type="number" name="grace_days_allowed" class="form-control" value="{{ $settings['grace_days_allowed'] ?? '5' }}">
                        </div>
                    </div>

                    <h5 class="text-indigo font-weight-bold mt-3 mb-3 border-bottom pb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Academic Session Window
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Session Start Date</label>
                            <input type="date" name="academic_year_start" class="form-control" value="{{ $settings['academic_year_start'] ?? '2026-04-01' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Session End Date</label>
                            <input type="date" name="academic_year_end" class="form-control" value="{{ $settings['academic_year_end'] ?? '2027-03-31' }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-indigo font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-info-circle mr-1 text-info"></i> System Environment
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">Framework</td>
                            <td>Laravel 11.x</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">PHP Version</td>
                            <td>8.3+</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Database</td>
                            <td>MySQL 8.0 (InnoDB)</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Access Control</td>
                            <td>Spatie Permission</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cache / Session</td>
                            <td>Redis / Database</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
