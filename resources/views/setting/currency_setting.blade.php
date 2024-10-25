<!--currency settings-->
    <div class="card" id="currency-setting-sidenav">
        <div class="card-header">
            <h5 class="small-title">{{ __('Currency Settings') }}</h5>
        </div>
        {{ Form::open(['route' => ['currency.settings'], 'method' => 'post', 'id' => 'setting-currency-form']) }}
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-6">
                    <div class="form-group col switch-width">
                        {{ Form::label('currency_format', __('Decimal Format'), ['class' => ' col-form-label']) }}
                        <select class="form-control currency_note" data-trigger name="currency_format"
                            id="currency_format" placeholder="{{ __('This is a search placeholder')}}">
                            <option value="0"
                                {{ isset($setting['currency_format']) && $setting['currency_format'] == '0' ? 'selected' : '' }}>
                                1</option>
                            <option value="1"
                                {{ isset($setting['currency_format']) && $setting['currency_format'] == '1' ? 'selected' : '' }}>
                                1.0</option>
                            <option value="2"
                                {{ isset($setting['currency_format']) && $setting['currency_format'] == '2' ? 'selected' : '' }}>
                                1.00</option>
                            <option value="3"
                                {{ isset($setting['currency_format']) && $setting['currency_format'] == '3' ? 'selected' : '' }}>
                                1.000</option>
                            <option value="4"
                                {{ isset($setting['currency_format']) && $setting['currency_format'] == '4' ? 'selected' : '' }}>
                                1.0000</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group col switch-width">
                        {{ Form::label('defult_currancy', __('Default Currancy'), ['class' => ' col-form-label']) }}
                        <select class="form-control currency_note" data-trigger name="defult_currancy"
                            id="defult_currancy" placeholder="{{ __('This is a search placeholder')}}">
                            @foreach (currency() as $c)
                                <option value="{{ $c->symbol }}-{{ $c->code }}"
                                    data-symbol="{{ $c->symbol }}"
                                    {{ isset($setting['defult_currancy']) && $setting['defult_currancy'] == $c->code ? 'selected' : '' }}>
                                    {{ $c->symbol }} - {{ $c->code }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="decimal_separator" class="form-label">{{ __('Decimal Separator') }}</label>
                    <select type="text" name="decimal_separator" class="form-control selectric currency_note"
                        id="decimal_separator">
                        <option value="dot" @if (@$setting['decimal_separator'] == 'dot') selected="selected" @endif>
                            {{ __('Dot') }}</option>
                        <option value="comma" @if (@$setting['decimal_separator'] == 'comma') selected="selected" @endif>
                            {{ __('Comma') }}</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="thousand_separator" class="form-label">{{ __('Thousands Separator') }}</label>
                    <select type="text" name="thousand_separator"
                        class="form-control selectric currency_note" id="thousand_separator">
                        <option value="dot" @if (@$setting['thousand_separator'] == 'dot') selected="selected" @endif>
                            {{ __('Dot') }}</option>
                        <option value="comma" @if (@$setting['thousand_separator'] == 'comma') selected="selected" @endif>
                            {{ __('Comma') }}</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    {{ Form::label('currency_space', __('Currency Symbol Space'), ['class' => 'form-label']) }}
                    <div class="row ms-1">
                        <div class="form-check col-md-6">
                            <input class="form-check-input currency_note" type="radio"
                                name="currency_space" value="withspace"
                                @if (!isset($setting['currency_space']) || $setting['currency_space'] == 'withspace') checked @endif id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                {{ __('With space') }}
                            </label>
                        </div>
                        <div class="form-check col-6">
                            <input class="form-check-input currency_note" type="radio"
                                name="currency_space" value="withoutspace"
                                @if (!isset($setting['currency_space']) || $setting['currency_space'] == 'withoutspace') checked @endif id="flexCheckChecked">
                            <label class="form-check-label" for="flexCheckChecked">
                                {{ __('Without space') }}
                            </label>
                        </div>
                    </div>
                    @error('currency_space')
                        <span class="invalid-currency_space" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label"
                            for="example3cols3Input">{{ __('Currency Symbol Position') }}</label>
                        <div class="row ms-1">
                            <div class="form-check col-md-6">
                                <input class="form-check-input currency_note" type="radio"
                                    name="site_currency_symbol_position" value="pre"
                                    @if (!isset($setting['site_currency_symbol_position']) || $setting['site_currency_symbol_position'] == 'pre') checked @endif
                                    id="currencySymbolPosition">
                                <label class="form-check-label" for="currencySymbolPosition">
                                    {{ __('Pre') }}
                                </label>
                            </div>
                            <div class="form-check col-md-6">
                                <input class="form-check-input currency_note" type="radio"
                                    name="site_currency_symbol_position" value="post"
                                    @if (isset($setting['site_currency_symbol_position']) && $setting['site_currency_symbol_position'] == 'post') checked @endif id="currencySymbolPost">
                                <label class="form-check-label" for="currencySymbolPost">
                                    {{ __('Post') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label"
                            for="example3cols3Input">{{ __('Currency Symbol & Name') }}</label>
                        <div class="row ms-1">
                            <div class="form-check col-md-6">
                                <input class="form-check-input currency_note" type="radio"
                                    name="site_currency_symbol_name" value="symbol"
                                    @if (!isset($setting['site_currency_symbol_name']) || $setting['site_currency_symbol_name'] == 'symbol') checked @endif id="currencySymbol">
                                <label class="form-check-label" for="currencySymbol">
                                    {{ __('With Currency Symbol') }}
                                </label>
                            </div>
                            <div class="form-check col-md-6">
                                <input class="form-check-input currency_note" type="radio"
                                    name="site_currency_symbol_name" value="symbolname"
                                    @if (isset($setting['site_currency_symbol_name']) && $setting['site_currency_symbol_name'] == 'symbolname') checked @endif id="currencySymbolName">
                                <label class="form-check-label" for="currencySymbolName">
                                    {{ __('With Currency Name') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label" for="new_note_value">{{ __('Preview :') }}</label>
                        <span id="formatted_price_span"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{ __('Save Changes') }}">
        </div>
        {{ Form::close() }}
    </div>
<!--currency settings end -->