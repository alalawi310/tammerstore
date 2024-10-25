<!-- cache setting -->
<div class="card" id="Cache_Settings">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <h5 class="h6 md-0">{{ __('Cache Settings') }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <p>{{ __('This is a page meant for more advanced users, simply ignore it if you do not understand what cache is.') }}
                    </p>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group search-form">
                    <input type="text" value="{{ \App\Models\Utility::GetCacheSize() }}" class="form-control" disabled>
                    <span class="input-group-text bg-transparent">{{ __('MB') }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ url('config-cache') }}" class="btn btn-m btn-primary m-r-10 ">{{ __('Clear Cache') }}</a>
    </div>
</div>
<!-- end cache setting -->