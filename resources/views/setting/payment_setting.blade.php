<!-- Payment Setting -->
<div class="card" id="Payment_Setting">
    <div class="card-header">
        <div class="float-end">
            <div class="badge bg-success p-2 px-3 rounded"></div>
        </div>
        <h5>{{ __('Payment Settings') }}</h5>
    </div>
    <div class="card-body">
        {{ Form::open(['route' => 'payment.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
        <div class="faq" id="accordionExample">
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="payment-gateways">
                        <!-- COD -->
                        @if (\Auth::user()->type == 'admin')
                            <div class="accordion-item card">
                                <h2 class="accordion-header" id="COD">
                                    <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseone_COD"
                                        aria-expanded="false" aria-controls="collapseone_COD">
                                        <span class="d-flex align-items-center">
                                            <i class="ti ti-credit-card me-2"></i>{{ __('COD') }}
                                        </span>
                                    </button>
                                </h2>

                                <div id="collapseone_COD" class="accordion-collapse collapse"
                                    aria-labelledby="COD" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mt-2">
                                            <div class="col-12 d-flex justify-content-between">
                                                <small class="">
                                                    {{ __('Note') }}:
                                                    {{ __('This detail will use for make checkout of product') }}.
                                                </small>

                                                <div class="form-check form-switch d-inline-block">
                                                    {!! Form::checkbox(
                                                        'is_cod_enabled',
                                                        'on',
                                                        isset($setting['is_cod_enabled']) && $setting['is_cod_enabled'] === 'on',
                                                        [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_cod_enabled',
                                                        ],
                                                    ) !!}
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_cod_enabled"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="cod_info"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('cod_info', empty($setting['cod_info']) ? 'Cash on Delivery' : $setting['cod_info'], [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                    <label for="upload_image"
                                                        class="image-upload bg-primary pointer">
                                                        <i class="ti ti-upload px-1"></i>
                                                        {{ __('Choose file here') }}
                                                    </label>
                                                    <input type="file" name="cod_image"
                                                        id="upload_image" class="d-none">
                                                    <img alt="Image placeholder"
                                                        src="{{ empty($setting['cod_image']) ? asset(Storage::url('uploads/payment/cod.png')) : get_file($setting['cod_image'], APP_THEME()) }}"
                                                        class="img-center img-fluid"
                                                        style="max-height: 100px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- End COD -->

                            <!-- Bank Transfer -->
                            <div class="accordion-item card">
                            <h2 class="accordion-header" id="Bank_transfer">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseone_Bank_transfer" aria-expanded="false"
                                    aria-controls="collapseone_Bank_transfer">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Bank Transfer') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_Bank_transfer" class="accordion-collapse collapse"
                                aria-labelledby="Bank_transfer" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_bank_transfer_enabled',
                                                    'on',
                                                    isset($setting['is_bank_transfer_enabled']) && $setting['is_bank_transfer_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_bank_transfer_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_bank_transfer_enabled">{{__('Bank Transfer
                                                    Enabled')}}</label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Bank_transfer_info"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea(
                                                    'bank_transfer',
                                                    isset($setting['bank_transfer']) ? $setting['bank_transfer'] : 'Bank Transfer add bank details here',
                                                    [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'Bank_transfer_info',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="bank_transfer_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="bank_transfer_image"
                                                    id="bank_transfer_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ empty($setting['bank_transfer_image']) ? asset(Storage::url('uploads/payment/bank.png')) : get_file($setting['bank_transfer_image']) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Bank Transfer -->

                        <!-- Stripe -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="Stripe">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseone_Stripe" aria-expanded="false"
                                    aria-controls="collapseone_Stripe">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Stripe') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_Stripe" class="accordion-collapse collapse"
                                aria-labelledby="Stripe" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_stripe_enabled',
                                                    'on',
                                                    isset($setting['is_stripe_enabled']) && $setting['is_stripe_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_stripe_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_stripe_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="stripe_key"
                                                    class="form-label">{{ __('Stripe Publishable Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Stripe Publishable Key"
                                                    name="stripe_publishable_key" type="text"
                                                    value="{{ $setting['stripe_publishable_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="stripe_secret"
                                                    class="form-label">{{ __('Stripe Secret Key') }}</label>
                                                <input class="form-control" placeholder="Stripe Secret"
                                                    name="stripe_secret_key" type="text"
                                                    value="{{ $setting['stripe_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="Stripe_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="stripe_image"
                                                    id="Stripe_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['stripe_image'] ?? Storage::url('uploads/payment/stripe.png')) ?? asset(Storage::url('uploads/payment/stripe.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="stripe_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('stripe_unfo', $setting['stripe_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'stripe_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Stripe -->

                        <!-- Paystack -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paystack">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseone_paystack" aria-expanded="false"
                                    aria-controls="collapseone_paystack">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paystack') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paystack" class="accordion-collapse collapse"
                                aria-labelledby="paystack" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paystack_enabled',
                                                    'on',
                                                    isset($setting['is_paystack_enabled']) && $setting['is_paystack_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paystack_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paystack_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paystack_public_key"
                                                    class="form-label">{{ __('Paystack Public Key') }}</label>
                                                <input class="form-control" placeholder="Public Key"
                                                    name="paystack_public_key" type="text"
                                                    value="{{ $setting['paystack_public_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="paystack_secret_key"
                                                    class="form-label">{{ __('Paystack Secret Key') }}</label>
                                                <input class="form-control" placeholder="paystack Secret"
                                                    name="paystack_secret_key" type="text"
                                                    value="{{ $setting['paystack_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paystack_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paystack_image"
                                                    id="paystack_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paystack_image'] ?? Storage::url('uploads/payment/paystack.png')) ?? asset(Storage::url('uploads/payment/paystack.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paystack_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paystack_unfo', $setting['paystack_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paystack_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Paystack -->

                        <!-- Razorpay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="razorpay">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseone_razorpay" aria-expanded="false"
                                    aria-controls="collapseone_razorpay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Razorpay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_razorpay" class="accordion-collapse collapse"
                                aria-labelledby="razorpay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_razorpay_enabled',
                                                    'on',
                                                    isset($setting['is_razorpay_enabled']) && $setting['is_razorpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_razorpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_razorpay_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="razorpay_public_key"
                                                    class="form-label">{{ __('Razorpay Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Razorpay Public Key"
                                                    name="razorpay_public_key" type="text"
                                                    value="{{ $setting['razorpay_public_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="razorpay_secret_key"
                                                    class="form-label">{{ __('Razorpay Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Razorpay Secret Key"
                                                    name="razorpay_secret_key" type="text"
                                                    value="{{ $setting['razorpay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="razorpay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="razorpay_image"
                                                    id="razorpay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['razorpay_image'] ?? Storage::url('uploads/payment/razorpay.png')) ?? asset(Storage::url('uploads/payment/razorpay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="razorpay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('razorpay_unfo', $setting['razorpay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'razorpay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Razorpay -->

                        <!-- Mercado  -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="mercado">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseone_mercado" aria-expanded="false"
                                    aria-controls="collapseone_mercado">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Mercado Pago') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_mercado" class="accordion-collapse collapse"
                                aria-labelledby="mercado" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_mercado_enabled',
                                                    'on',
                                                    isset($setting['is_mercado_enabled']) && $setting['is_mercado_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_mercado_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_mercado_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="mercado_mode">{{ __('Mercado Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="mercado_mode" value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['mercado_mode']) && $setting['mercado_mode'] == '') || (isset($setting['mercado_mode']) && $setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="mercado_mode" value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['mercado_mode']) && $setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="mercado_access_token"
                                                    class="col-form-label pt-0">{{ __('Mercado Access Token') }}</label>
                                                <input type="text" name="mercado_access_token"
                                                    id="mercado_access_token" class="form-control"
                                                    value="{{ $setting['mercado_access_token'] ?? '' }}"
                                                    placeholder="{{ __('Mercado Access Token') }}" />
                                                @if ($errors->has('mercado_secret_key'))
                                                    <span class="invalid-feedback d-block">
                                                        {{ $errors->first('mercado_access_token') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="mercado_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="mercado_image"
                                                    id="mercado_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['mercado_image'] ?? Storage::url('uploads/payment/mercado.png')) ?? asset(Storage::url('uploads/payment/mercado.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="mercado_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('mercado_unfo', $setting['mercado_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'mercado_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Mercado -->

                        <!-- Skrill  -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="skrill">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_skrill"
                                    aria-expanded="false" aria-controls="collapseone_skrill">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Skrill') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_skrill" class="accordion-collapse collapse"
                                aria-labelledby="skrill" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_skrill_enabled',
                                                    'on',
                                                    isset($setting['is_skrill_enabled']) && $setting['is_skrill_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_skrill_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_skrill_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="skrill_email"
                                                    class="col-form-label pt-0">{{ __('Skrill Email') }}</label>
                                                <input type="text" name="skrill_email"
                                                    id="skrill_email" class="form-control"
                                                    value="{{ $setting['skrill_email'] ?? '' }}"
                                                    placeholder="{{ __('Access Token') }}" />
                                                @if ($errors->has('skrill_secret_key'))
                                                    <span class="invalid-feedback d-block">
                                                        {{ $errors->first('skrill_email') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="skrill_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="skrill_image"
                                                    id="skrill_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['skrill_image'] ?? Storage::url('uploads/payment/skrill.png')) ?? asset(Storage::url('uploads/payment/skrill.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="skrill_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('skrill_unfo', $setting['skrill_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'skrill_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Skrill -->

                        <!-- PaymentWall -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paymentwall">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paymentwall"
                                    aria-expanded="false" aria-controls="collapseone_paymentwall">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PaymentWall') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paymentwall" class="accordion-collapse collapse"
                                aria-labelledby="paymentwall" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paymentwall_enabled',
                                                    'on',
                                                    isset($setting['is_paymentwall_enabled']) && $setting['is_paymentwall_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paymentwall_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paymentwall_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paymentwall_public_key"
                                                    class="form-label">{{ __('PaymentWall Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="PaymentWall Public Key"
                                                    name="paymentwall_public_key" type="text"
                                                    value="{{ $setting['paymentwall_public_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="paymentwall_private_key"
                                                    class="form-label">{{ __('PaymentWall Private Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="PaymentWalld Private Key"
                                                    name="paymentwall_private_key" type="text"
                                                    value="{{ $setting['paymentwall_private_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paymentwall_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paymentwall_image"
                                                    id="paymentwall_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paymentwall_image'] ?? Storage::url('uploads/payment/paymentwall.png')) ?? asset(Storage::url('uploads/payment/paymentwall.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paymentwall_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paymentwall_unfo', $setting['paymentwall_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paymentwall_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PaymentWall -->

                        <!-- Paypal -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="Paypal">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_Paypal"
                                    aria-expanded="false" aria-controls="collapseone_Paypal">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paypal') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_Paypal" class="accordion-collapse collapse"
                                aria-labelledby="Paypal" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <small class="">
                                            {{ __('Note') }}:
                                            {{ __('This detail will use for make checkout of product') }}.
                                        </small>
                                        <div class="col-md-6">
                                            <div class="col-lg-12">
                                                <label class="paypal-label col-form-label"
                                                    for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                <br>
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-2" style="margin-right: 15px;">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="paypal_mode"
                                                                        value="sandbox"
                                                                        class="form-check-input"
                                                                        {{ !isset($setting['paypal_mode']) || $setting['paypal_mode'] == '' || $setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Sandbox') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-2">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="paypal_mode"
                                                                        value="live"
                                                                        class="form-check-input"
                                                                        {{ isset($setting['paypal_mode']) && $setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Live') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paypal_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paypal_enabled',
                                                    'on',
                                                    isset($setting['is_paypal_enabled']) && $setting['is_paypal_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paypal_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paypal_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paypal_client_id"
                                                    class="form-label">{{ __('Paypal Client Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Paypal Client Key"
                                                    name="paypal_client_id" type="text"
                                                    value="{{ $setting['paypal_client_id'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="paypal_secret"
                                                    class="form-label">{{ __('Paypal Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Paypal Secret Key"
                                                    name="paypal_secret_key" type="text"
                                                    value="{{ $setting['paypal_secret_key'] ?? '' }}">
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paypal_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paypal_image"
                                                    id="paypal_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paypal_image'] ?? Storage::url('uploads/payment/paypal.png')) ?? asset(Storage::url('uploads/payment/paypal.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paypal_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paypal_unfo', $setting['paypal_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paypal_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Paypal -->

                        <!-- flutterwave -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="flutterwave">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_flutterwave"
                                    aria-expanded="false" aria-controls="collapseone_flutterwave">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Flutterwave') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_flutterwave" class="accordion-collapse collapse"
                                aria-labelledby="Paypal" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_flutterwave_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_flutterwave_enabled',
                                                    'on',
                                                    isset($setting['is_flutterwave_enabled']) && $setting['is_flutterwave_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_flutterwave_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_flutterwave_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="public_key"
                                                    class="form-label">{{ __('Flutterwave Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Flutterwave Public Key"
                                                    name="flutterwave_public_key" type="text"
                                                    value="{{ $setting['flutterwave_public_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="flutterwave_secret"
                                                    class="form-label">{{ __('Flutterwave Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Flutterwave Secret"
                                                    name="flutterwave_secret_key" type="text"
                                                    value="{{ $setting['flutterwave_secret_key'] ?? '' }}">
                                            </div>

                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="flutterwave_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="flutterwave_image"
                                                    id="flutterwave_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['flutterwave_image'] ?? Storage::url('uploads/payment/flutterwave.png')) ?? asset(Storage::url('uploads/payment/flutterwave.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="flutterwave_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('flutterwave_unfo', $setting['flutterwave_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'flutterwave_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End flutterwave -->

                        <!-- Paytm -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paytm">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paytm"
                                    aria-expanded="false" aria-controls="collapseone_paytm">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paytm') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paytm" class="accordion-collapse collapse"
                                aria-labelledby="paytm" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                            <div class="col-lg-12">
                                                <label class="paypal-label col-form-label"
                                                    for="paytm_mode">{{ __('Paytm Mode') }}</label>
                                                <br>
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-2" style="margin-right: 15px;">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="paytm_mode" value="local"
                                                                        class="form-check-input"
                                                                        {{ !isset($setting['paytm_mode']) || $setting['paytm_mode'] == '' || $setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Local') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-2">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="paytm_mode"
                                                                        value="production"
                                                                        class="form-check-input"
                                                                        {{ isset($setting['paytm_mode']) && $setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Production') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paytm_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paytm_enabled',
                                                    'on',
                                                    isset($setting['is_paytm_enabled']) && $setting['is_paytm_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paytm_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paytm_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paytm_public_key"
                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                <input class="form-control" placeholder="Merchant ID"
                                                    name="paytm_merchant_id" type="text"
                                                    value="{{ $setting['paytm_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paytm_secret_key"
                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                <input class="form-control" placeholder="Merchant Key"
                                                    name="paytm_merchant_key" type="text"
                                                    value="{{ $setting['paytm_merchant_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paytm_industry_type"
                                                    class="col-form-label">{{ __('Industry Type') }}</label>
                                                <input class="form-control" placeholder="Industry Type"
                                                    name="paytm_industry_type" type="text"
                                                    value="{{ $setting['paytm_industry_type'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paytm_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paytm_image"
                                                    id="paytm_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paytm_image'] ?? Storage::url('uploads/payment/paytm.png')) ?? asset(Storage::url('uploads/payment/paytm.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paytm_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paytm_unfo', $setting['paytm_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paytm_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Paytm -->

                        <!-- mollie -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="mollie">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_mollie"
                                    aria-expanded="false" aria-controls="collapseone_mollie">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Mollie') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_mollie" class="accordion-collapse collapse"
                                aria-labelledby="mollie" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_mollie_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_mollie_enabled',
                                                    'on',
                                                    isset($setting['is_mollie_enabled']) && $setting['is_mollie_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_mollie_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_mollie_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="mollie_api_key"
                                                    class="form-label">{{ __('Mollie Api Key') }}</label>
                                                <input class="form-control" placeholder="Mollie Api Key"
                                                    name="mollie_api_key" type="text"
                                                    value="{{ $setting['mollie_api_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="mollie_profile_id"
                                                    class="form-label">{{ __('Mollie Profile Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Mollie Profile Secret"
                                                    name="mollie_profile_id" type="text"
                                                    value="{{ $setting['mollie_profile_id'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="mollie_partner_id"
                                                    class="form-label">{{ __('Mollie Partner Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Mollie Partner Secret"
                                                    name="mollie_partner_id" type="text"
                                                    value="{{ $setting['mollie_partner_id'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="mollie_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="mollie_image"
                                                    id="mollie_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['mollie_image'] ?? Storage::url('uploads/payment/mollie.png')) ?? asset(Storage::url('uploads/payment/mollie.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="mollie_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('mollie_unfo', $setting['mollie_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'mollie_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End mollie -->

                        <!-- coingate -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="coingate">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_coingate"
                                    aria-expanded="false" aria-controls="collapseone_coingate">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Coingate') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_coingate" class="accordion-collapse collapse"
                                aria-labelledby="coingate" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                            <div class="col-lg-12">
                                                <label class="paypal-label col-form-label"
                                                    for="coingate_mode">{{ __('Coingate Mode') }}</label>
                                                <br>
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-2" style="margin-right: 15px;">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="coingate_mode"
                                                                        value="sandbox"
                                                                        class="form-check-input"
                                                                        {{ !isset($setting['coingate_mode']) || $setting['coingate_mode'] == '' || $setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Sandbox') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-2">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="coingate_mode"
                                                                        value="live"
                                                                        class="form-check-input"
                                                                        {{ isset($setting['coingate_mode']) && $setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Live') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_coingate_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_coingate_enabled',
                                                    'on',
                                                    isset($setting['is_coingate_enabled']) && $setting['is_coingate_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_coingate_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_coingate_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="coingate_auth_token"
                                                    class="form-label">{{ __('CoinGate Auth Token') }}</label>
                                                <input class="form-control"
                                                    placeholder="CoinGate Auth Token"
                                                    name="coingate_auth_token" type="text"
                                                    value="{{ $setting['coingate_auth_token'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="coingate_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="coingate_image"
                                                    id="coingate_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['coingate_image'] ?? Storage::url('uploads/payment/coingate.png')) ?? asset(Storage::url('uploads/payment/coingate.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="coingate_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('coingate_unfo', $setting['coingate_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'coingate_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End coingate -->

                        <!-- sspay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="sspay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_sspay"
                                    aria-expanded="false" aria-controls="collapseone_sspay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Sspay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_sspay" class="accordion-collapse collapse"
                                aria-labelledby="sspay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_sspay_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_sspay_enabled',
                                                    'on',
                                                    isset($setting['is_sspay_enabled']) && $setting['is_sspay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_sspay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_sspay_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="sspay_secret_key"
                                                    class="form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('sspay secret key') }}"
                                                    name="sspay_secret_key" type="text"
                                                    value="{{ $setting['sspay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="sspay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="sspay_image"
                                                    id="sspay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['sspay_image'] ?? Storage::url('uploads/payment/sspay.png')) ?? asset(Storage::url('uploads/payment/sspay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="sspay_category_code"
                                                    class="form-label">{{ __('Category Code') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('category code') }}"
                                                    name="sspay_category_code" type="text"
                                                    value="{{ $setting['sspay_category_code'] ?? '' }}">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="sspay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('sspay_unfo', $setting['sspay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'sspay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end sspay -->

                        <!-- Toyyibpay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="toyyibpay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_toyyibpay"
                                    aria-expanded="false" aria-controls="collapseone_toyyibpay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Toyyibpay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_toyyibpay" class="accordion-collapse collapse"
                                aria-labelledby="toyyibpay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_toyyibpay_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_toyyibpay_enabled',
                                                    'on',
                                                    isset($setting['is_toyyibpay_enabled']) && $setting['is_toyyibpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_toyyibpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_toyyibpay_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="toyyibpay_secret_key"
                                                    class="form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('toyyibpay secret key') }}"
                                                    name="toyyibpay_secret_key" type="text"
                                                    value="{{ $setting['toyyibpay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="toyyibpay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="toyyibpay_image"
                                                    id="toyyibpay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['toyyibpay_image'] ?? Storage::url('uploads/payment/toyyibpay.png')) ?? asset(Storage::url('uploads/payment/toyyibpay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="toyyibpay_category_code"
                                                    class="form-label">{{ __('Category Code') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('category code') }}"
                                                    name="toyyibpay_category_code" type="text"
                                                    value="{{ $setting['toyyibpay_category_code'] ?? '' }}">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="toyyibpay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('toyyibpay_unfo', $setting['toyyibpay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'toyyibpay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Toyyibpay -->

                        <!-- paytab -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paytabs">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paytabs"
                                    aria-expanded="false" aria-controls="collapseone_paytabs">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paytab') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paytabs" class="accordion-collapse collapse"
                                aria-labelledby="paytabs" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paytabs_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paytabs_enabled',
                                                    'on',
                                                    isset($setting['is_paytabs_enabled']) && $setting['is_paytabs_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paytabs_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paytabs_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paytabs_profile_id"
                                                    class="form-label">{{ __('Profile Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('paytab Profile Id') }}"
                                                    name="paytabs_profile_id" type="text"
                                                    value="{{ $setting['paytabs_profile_id'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paytabs_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paytabs_image"
                                                    id="paytabs_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paytabs_image'] ?? Storage::url('uploads/payment/paytabs.png')) ?? asset(Storage::url('uploads/payment/paytabs.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paytabs_server_key"
                                                    class="form-label">{{ __('Paytabs Server Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('Paytabs Server Key') }}"
                                                    name="paytabs_server_key" type="text"
                                                    value="{{ $setting['paytabs_server_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paytabs_region"
                                                    class="form-label">{{ __('Paytabs Region') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('Paytabs Region') }}"
                                                    name="paytabs_region" type="text"
                                                    value="{{ $setting['paytabs_region'] ?? '' }}">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paytabs_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paytabs_unfo', $setting['paytabs_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paytabs_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End paytab -->

                        <!-- IyziPay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="iyzipay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_iyzipay"
                                    aria-expanded="false" aria-controls="collapseone_iyzipay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('IyziPay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_iyzipay" class="accordion-collapse collapse"
                                aria-labelledby="iyzipay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_iyzipay_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_iyzipay_enabled',
                                                    'on',
                                                    isset($setting['is_iyzipay_enabled']) && $setting['is_iyzipay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_iyzipay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_iyzipay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="iyzipay_mode">{{ __('IyziPay Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="iyzipay_mode" value="sandbox"
                                                                    checked="checked"
                                                                    class="form-check-input"
                                                                    {{ (isset($setting['iyzipay_mode']) && $setting['iyzipay_mode'] == '') || (isset($setting['iyzipay_mode']) && $setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="iyzipay_mode" value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['iyzipay_mode']) && $setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="iyzipay_private_key"
                                                    class="col-form-label">{{ __('Private Key') }}</label>
                                                <input class="form-control" placeholder="Private Key"
                                                    name="iyzipay_private_key" type="text"
                                                    value="{{ $setting['iyzipay_private_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="iyzipay_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control" placeholder="Secret Key"
                                                    name="iyzipay_secret_key" type="text"
                                                    value="{{ $setting['iyzipay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="iyzipay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="iyzipay_image"
                                                    id="iyzipay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['iyzipay_image'] ?? Storage::url('uploads/payment/iyzipay.png')) ?? asset(Storage::url('uploads/payment/iyzipay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="iyzipay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('iyzipay_unfo', $setting['iyzipay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'iyzipay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End IyziPay -->

                        <!-- PayFast -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="payfast">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_payfast"
                                    aria-expanded="false" aria-controls="collapseone_payfast">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PayFast') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_payfast" class="accordion-collapse collapse"
                                aria-labelledby="payfast" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                            <div class="col-lg-12">
                                                <label class="paypal-label col-form-label"
                                                    for="payfast_mode">{{ __('PayFast Mode') }}</label>
                                                <br>
                                                <div class="d-flex flex-wrap">
                                                    <div class="mr-2" style="margin-right: 15px;">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="payfast_mode"
                                                                        value="sandbox"
                                                                        checked="checked"
                                                                        class="form-check-input"
                                                                        {{ (isset($setting['payfast_mode']) && $setting['payfast_mode'] == '') || (isset($setting['payfast_mode']) && $setting['payfast_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                    {{ __('Sandbox') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mr-2">
                                                        <div class="border card p-3">
                                                            <div class="form-check">
                                                                <label class="form-check-labe text-dark">
                                                                    <input type="radio"
                                                                        name="payfast_mode"
                                                                        value="live"
                                                                        class="form-check-input"
                                                                        {{ isset($setting['payfast_mode']) && $setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                    {{ __('Live') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_payfast_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_payfast_enabled',
                                                    'on',
                                                    isset($setting['is_payfast_enabled']) && $setting['is_payfast_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_payfast_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_payfast_enabled"></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payfast_public_key"
                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                <input class="form-control" placeholder="Merchant ID"
                                                    name="payfast_merchant_id" type="text"
                                                    value="{{ $setting['payfast_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payfast_secret_key"
                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                <input class="form-control" placeholder="Merchant Key"
                                                    name="payfast_merchant_key" type="text"
                                                    value="{{ $setting['payfast_merchant_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="payfast_salt_passphrase"
                                                    class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                <input class="form-control"
                                                    placeholder="Salt Passphrase"
                                                    name="payfast_salt_passphrase" type="text"
                                                    value="{{ $setting['payfast_salt_passphrase'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="payfast_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="payfast_image"
                                                    id="payfast_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['payfast_image'] ?? Storage::url('uploads/payment/payfast.png')) ?? asset(Storage::url('uploads/payment/payfast.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>

                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="payfast_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('payfast_unfo', $setting['payfast_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'payfast_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PayFast -->

                        <!-- Benefit -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="benefit">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_benefit"
                                    aria-expanded="false" aria-controls="collapseone_benefit">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Benefit') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_benefit" class="accordion-collapse collapse"
                                aria-labelledby="benefit" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_benefit_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_benefit_enabled',
                                                    'on',
                                                    isset($setting['is_benefit_enabled']) && $setting['is_benefit_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_benefit_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_benefit_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="benefit_private_key"
                                                    class="col-form-label">{{ __('Benefit Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Benefit Key"
                                                    name="benefit_private_key" type="text"
                                                    value="{{ $setting['benefit_private_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="benefit_secret_key"
                                                    class="col-form-label">{{ __('Benefit Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="En ter Benefit Secret Key"
                                                    name="benefit_secret_key" type="text"
                                                    value="{{ $setting['benefit_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="benefit_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="benefit_image"
                                                    id="benefit_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['benefit_image'] ?? Storage::url('uploads/payment/benefit.png')) ?? asset(Storage::url('uploads/payment/benefit.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="benefit_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('benefit_unfo', $setting['benefit_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'benefit_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Benefit -->

                        <!-- Cashfree -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="cashfree">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_cashfree"
                                    aria-expanded="false" aria-controls="collapseone_cashfree">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Cashfree') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_cashfree" class="accordion-collapse collapse"
                                aria-labelledby="cashfree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_cashfree_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_cashfree_enabled',
                                                    'on',
                                                    isset($setting['is_cashfree_enabled']) && $setting['is_cashfree_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_cashfree_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_cashfree_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="cashfree_private_key"
                                                    class="col-form-label">{{ __('Cashfree Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Cashfree Key" name="cashfree_key"
                                                    type="text"
                                                    value="{{ $setting['cashfree_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="cashfree_secret_key"
                                                    class="col-form-label">{{ __('Cashfree Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Secret Key"
                                                    name="cashfree_secret_key" type="text"
                                                    value="{{ $setting['cashfree_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="cashfree_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="cashfree_image"
                                                    id="cashfree_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['cashfree_image'] ?? Storage::url('uploads/payment/cashfree.png')) ?? asset(Storage::url('uploads/payment/cashfree.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="cashfree_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('cashfree_unfo', $setting['cashfree_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'cashfree_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Cashfree -->

                        <!-- Aamarpay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="aamarpay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_aamarpay"
                                    aria-expanded="false" aria-controls="collapseone_aamarpay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Aamarpay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_aamarpay" class="accordion-collapse collapse"
                                aria-labelledby="aamarpay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_aamarpay_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_aamarpay_enabled',
                                                    'on',
                                                    isset($setting['is_aamarpay_enabled']) && $setting['is_aamarpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_aamarpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_aamarpay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="aamarpay_store_id"
                                                    class="col-form-label">{{ __('Store Id') }}</label>
                                                <input class="form-control" placeholder="Enter Store Id"
                                                    name="aamarpay_store_id" type="text"
                                                    value="{{ $setting['aamarpay_store_id'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="aamarpay_signature_key"
                                                    class="col-form-label">{{ __('Signature Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Signature Key"
                                                    name="aamarpay_signature_key" type="text"
                                                    value="{{ $setting['aamarpay_signature_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="aamarpay_description"
                                                    class="col-form-label">{{ __('Aamarpay Description') }}</label>
                                                <input class="form-control"
                                                    placeholder="Aamarpay Description"
                                                    name="aamarpay_description" type="text"
                                                    value="{{ $setting['aamarpay_description'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="aamarpay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="aamarpay_image"
                                                    id="aamarpay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['aamarpay_image'] ?? Storage::url('uploads/payment/aamarpay.png')) ?? asset(Storage::url('uploads/payment/aamarpay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="aamarpay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('aamarpay_unfo', $setting['aamarpay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'aamarpay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Aamarpay -->

                        <!-- Telegram -->
                        @if (\Auth::user()->type == 'admin')
                            <div class="accordion-item card">
                                <h2 class="accordion-header" id="telegram">
                                    <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseone_telegram"
                                        aria-expanded="false" aria-controls="collapseone_telegram">
                                        <span class="d-flex align-items-center">
                                            <i class="ti ti-credit-card me-2"></i>
                                            {{ __('Telegram') }}</span>
                                    </button>
                                </h2>
                                <div id="collapseone_telegram" class="accordion-collapse collapse"
                                    aria-labelledby="telegram" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <small class="">
                                                    {{ __('Note') }}:
                                                    {{ __('This detail will use for make checkout of product') }}.
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                {!! Form::hidden('is_telegram_enabled', 'off') !!}
                                                <div class="form-check form-switch d-inline-block">
                                                    {!! Form::checkbox(
                                                        'is_telegram_enabled',
                                                        'on',
                                                        isset($setting['is_telegram_enabled']) && $setting['is_telegram_enabled'] === 'on',
                                                        [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_telegram_enabled',
                                                        ],
                                                    ) !!}
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_telegram_enabled"></label>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="telegram_access_token"
                                                        class="form-label">{{ __('Telegram Access Token') }}</label>
                                                    <input class="form-control"
                                                        placeholder="1234567890:AAbbbbccccddddxvGENZCi8Hd4B15M8xHV0"
                                                        name="telegram_access_token" type="text"
                                                        value="{{ $setting['telegram_access_token'] ?? '' }}">
                                                    <p>{{ __('Get Chat ID') }} :
                                                        https://api.telegram.org/bot-TOKEN-/getUpdates
                                                    </p>
                                                </div>

                                                <div class="form-group">
                                                    <label for="telegram_chat_id"
                                                        class="form-label">{{ __('Telegram Chat Id') }}</label>
                                                    <input class="form-control" placeholder="123456789"
                                                        name="telegram_chat_id" type="text"
                                                        value="{{ $setting['telegram_chat_id'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                    <label for="telegram_image"
                                                        class="image-upload bg-primary pointer">
                                                        <i class="ti ti-upload px-1"></i>
                                                        {{ __('Choose file here') }}
                                                    </label>
                                                    <input type="file" name="telegram_image"
                                                        id="telegram_image" class="d-none">

                                                    <img alt="Image placeholder"
                                                        src="{{ get_file($setting['telegram_image'] ?? Storage::url('uploads/payment/telegram.png')) ?? asset(Storage::url('uploads/payment/telegram.png')) }}"
                                                        class="img-center img-fluid"
                                                        style="max-height: 100px;">
                                                </div>
                                            </div>
                                            @if (\Auth::user()->type == 'admin')
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="telegram_unfo"
                                                            class="form-label">{{ __('Description') }}</label>
                                                        {!! Form::textarea('telegram_unfo', $setting['telegram_unfo'] ?? '', [
                                                            'class' => 'autogrow form-control',
                                                            'rows' => '5',
                                                            'id' => 'telegram_unfo',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- End Telegram -->

                        <!-- Whatsapp -->
                        @if (\Auth::user()->type == 'admin')
                            <div class="accordion-item card">
                                <h2 class="accordion-header" id="whatsapp">
                                    <button class="accordion-button" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseone_whatsapp"
                                        aria-expanded="false" aria-controls="collapseone_whatsapp">
                                        <span class="d-flex align-items-center">
                                            <i class="ti ti-credit-card me-2"></i>
                                            {{ __('Whatsapp') }}</span>
                                    </button>
                                </h2>
                                <div id="collapseone_whatsapp" class="accordion-collapse collapse"
                                    aria-labelledby="whatsapp" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <small class="">
                                                    {{ __('Note') }}:
                                                    {{ __('This detail will use for make checkout of product') }}.
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                {!! Form::hidden('is_whatsapp_enabled', 'off') !!}
                                                <div class="form-check form-switch d-inline-block">
                                                    {!! Form::checkbox(
                                                        'is_whatsapp_enabled',
                                                        'on',
                                                        isset($setting['is_whatsapp_enabled']) && $setting['is_whatsapp_enabled'] === 'on',
                                                        [
                                                            'class' => 'form-check-input',
                                                            'id' => 'is_whatsapp_enabled',
                                                        ],
                                                    ) !!}
                                                    <label class="custom-control-label form-control-label"
                                                        for="is_whatsapp_enabled"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input type="text" name="whatsapp_number"
                                                        id="whatsapp_number"
                                                        class="form-control input-mask"
                                                        data-mask="+00 00000000000"
                                                        value="{{ $setting['whatsapp_number'] ?? '' }}"
                                                        placeholder="+00 00000000000" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                    <label for="whatsapp_image"
                                                        class="image-upload bg-primary pointer">
                                                        <i class="ti ti-upload px-1"></i>
                                                        {{ __('Choose file here') }}
                                                    </label>
                                                    <input type="file" name="whatsapp_image"
                                                        id="whatsapp_image" class="d-none">

                                                    <img alt="Image placeholder"
                                                        src="{{ get_file($setting['whatsapp_image'] ?? Storage::url('uploads/payment/whatsapp.png')) ?? asset(Storage::url('uploads/payment/whatsapp.png')) }}"
                                                        class="img-center img-fluid"
                                                        style="max-height: 100px;">
                                                </div>
                                            </div>
                                            @if (\Auth::user()->type == 'admin')
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="whatsapp_unfo"
                                                            class="form-label">{{ __('Description') }}</label>
                                                        {!! Form::textarea('whatsapp_unfo', $setting['whatsapp_unfo'] ?? '', [
                                                            'class' => 'autogrow form-control',
                                                            'rows' => '5',
                                                            'id' => 'whatsapp_unfo',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- End Whatsapp -->

                        <!-- PayTR -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paytr">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paytr"
                                    aria-expanded="false" aria-controls="collapseone_paytr">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Pay TR') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paytr" class="accordion-collapse collapse"
                                aria-labelledby="paytr" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paytr_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paytr_enabled',
                                                    'on',
                                                    isset($setting['is_paytr_enabled']) && $setting['is_paytr_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paytr_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paytr_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="paytr_merchant_id"
                                                    class="col-form-label">{{ __('Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Id"
                                                    name="paytr_merchant_id" type="text"
                                                    value="{{ $setting['paytr_merchant_id'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="paytr_merchant_key"
                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Key"
                                                    name="paytr_merchant_key" type="text"
                                                    value="{{ $setting['paytr_merchant_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="paytr_salt_key"
                                                    class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                <input class="form-control"
                                                    placeholder="Salt Passphrase" name="paytr_salt_key"
                                                    type="text"
                                                    value="{{ $setting['paytr_salt_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paytr_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paytr_image"
                                                    id="paytr_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paytr_image'] ?? Storage::url('uploads/payment/paytr.png')) ?? asset(Storage::url('uploads/payment/paytr.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paytr_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paytr_unfo', $setting['paytr_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paytr_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PayTR -->

                        <!-- Yookassa -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="yookassa">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_yookassa"
                                    aria-expanded="false" aria-controls="collapseone_yookassa">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Yookassa') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_yookassa" class="accordion-collapse collapse"
                                aria-labelledby="yookassa" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_yookassa_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_yookassa_enabled',
                                                    'on',
                                                    isset($setting['is_yookassa_enabled']) && $setting['is_yookassa_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_yookassa_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_yookassa_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="yookassa_shop_id_key"
                                                    class="col-form-label">{{ __('Shop Id Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Shop Id Key"
                                                    name="yookassa_shop_id_key" type="text"
                                                    value="{{ $setting['yookassa_shop_id_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="yookassa_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter secret Key"
                                                    name="yookassa_secret_key" type="text"
                                                    value="{{ $setting['yookassa_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="yookassa_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="yookassa_image"
                                                    id="yookassa_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['yookassa_image'] ?? Storage::url('uploads/payment/yookassa.png')) ?? asset(Storage::url('uploads/payment/yookassa.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="yookassa_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('yookassa_unfo', $setting['yookassa_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'yookassa_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Yookassa -->

                        <!-- Xendit -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="Xendit">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_Xendit"
                                    aria-expanded="false" aria-controls="collapseone_Xendit">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Xendit') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_Xendit" class="accordion-collapse collapse"
                                aria-labelledby="Xendit" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_Xendit_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_Xendit_enabled',
                                                    'on',
                                                    isset($setting['is_Xendit_enabled']) && $setting['is_Xendit_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_Xendit_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_Xendit_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Xendit_api_key"
                                                    class="col-form-label">{{ __('Xendit Api key') }}</label>
                                                <input class="form-control" placeholder="Enter Api Key"
                                                    name="Xendit_api_key" type="text"
                                                    value="{{ $setting['Xendit_api_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="Xendit_secret_key"
                                                    class="col-form-label">{{ __('Token') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Token Key"
                                                    name="Xendit_token_key" type="text"
                                                    value="{{ $setting['yookassa_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="Xendit_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="Xendit_image"
                                                    id="Xendit_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['Xendit_image'] ?? Storage::url('uploads/payment/xendit.png')) ?? asset(Storage::url('uploads/payment/xendit.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="Xendit_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('Xendit_unfo', $setting['Xendit_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'Xendit_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Xendit -->

                        <!-- Midtrans -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="midtrans">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_midtrans"
                                    aria-expanded="false" aria-controls="collapseone_midtrans">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Midtrans') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_midtrans" class="accordion-collapse collapse"
                                aria-labelledby="midtrans" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_midtrans_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_midtrans_enabled',
                                                    'on',
                                                    isset($setting['is_midtrans_enabled']) && $setting['is_midtrans_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_midtrans_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_midtrans_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="midtrans_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter secret Key"
                                                    name="midtrans_secret_key" type="text"
                                                    value="{{ $setting['midtrans_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="midtrans_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="midtrans_image"
                                                    id="midtrans_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['midtrans_image'] ?? Storage::url('uploads/payment/midtrans.png')) ?? asset(Storage::url('uploads/payment/midtrans.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="midtrans_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('midtrans_unfo', $setting['midtrans_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'midtrans_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Midtrans -->

                        <!-- Nepalste -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="nepalste">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_nepalste"
                                    aria-expanded="false" aria-controls="collapseone_nepalste">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Nepalste') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_nepalste" class="accordion-collapse collapse"
                                aria-labelledby="nepalste" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_nepalste_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_nepalste_enabled',
                                                    'on',
                                                    isset($setting['is_nepalste_enabled']) && $setting['is_nepalste_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_nepalste_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_nepalste_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="nepalste_mode">{{ __('Nepalste Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="nepalste_mode" value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['nepalste_mode']) && $setting['nepalste_mode'] == '') || (isset($setting['nepalste_mode']) && $setting['nepalste_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="nepalste_mode" value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['nepalste_mode']) && $setting['nepalste_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nepalste_public_key"
                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Public Key"
                                                    name="nepalste_public_key" type="text"
                                                    value="{{ $setting['nepalste_public_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nepalste_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter secret Key"
                                                    name="nepalste_secret_key" type="text"
                                                    value="{{ $setting['nepalste_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="nepalste_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="nepalste_image"
                                                    id="nepalste_image" class="d-none">

                                                <img src="{{ get_file($setting['nepalste_image'] ?? Storage::url('uploads/payment/nepalste.png')) ?? asset(Storage::url('uploads/payment/nepalste.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nepalste_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('nepalste_unfo', $setting['nepalste_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'nepalste_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Nepalste -->

                        <!-- PayHere -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="payhere">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_payhere"
                                    aria-expanded="false" aria-controls="collapseone_payhere">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PayHere') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_payhere" class="accordion-collapse collapse"
                                aria-labelledby="payhere" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_payhere_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_payhere_enabled',
                                                    'on',
                                                    isset($setting['is_payhere_enabled']) && $setting['is_payhere_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_payhere_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_payhere_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="payhere_mode">{{ __('PayHere Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="payhere_mode" value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['payhere_mode']) && $setting['payhere_mode'] == '') || (isset($setting['payhere_mode']) && $setting['payhere_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="payhere_mode" value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['payhere_mode']) && $setting['payhere_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payhere_merchant_id"
                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant ID"
                                                    name="payhere_merchant_id" type="text"
                                                    value="{{ $setting['payhere_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payhere_merchant_secret"
                                                    class="col-form-label">{{ __('Merchant Secret') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Secret"
                                                    name="payhere_merchant_secret" type="text"
                                                    value="{{ $setting['payhere_merchant_secret'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payhere_app_id"
                                                    class="col-form-label">{{ __('App ID') }}</label>
                                                <input class="form-control" placeholder="Enter App ID"
                                                    name="payhere_app_id" type="text"
                                                    value="{{ $setting['payhere_app_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payhere_app_secret"
                                                    class="col-form-label">{{ __('App Secret') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter App Secret"
                                                    name="payhere_app_secret" type="text"
                                                    value="{{ $setting['payhere_app_secret'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="payhere_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="payhere_image"
                                                    id="payhere_image" class="d-none">

                                                <img src="{{ get_file($setting['payhere_image'] ?? Storage::url('uploads/payment/payhere.png')) ?? asset(Storage::url('uploads/payment/payhere.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="payhere_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('payhere_unfo', $setting['payhere_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'payhere_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PayHere -->

                        <!-- Khalti -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="khalti">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_khalti"
                                    aria-expanded="false" aria-controls="collapseone_khalti">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Khalti') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_khalti" class="accordion-collapse collapse"
                                aria-labelledby="khalti" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_khalti_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_khalti_enabled',
                                                    'on',
                                                    isset($setting['is_khalti_enabled']) && $setting['is_khalti_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_khalti_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_khalti_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="khalti_public_key"
                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Public Key"
                                                    name="khalti_public_key" type="text"
                                                    value="{{ $setting['khalti_public_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="khalti_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter secret Key"
                                                    name="khalti_secret_key" type="text"
                                                    value="{{ $setting['khalti_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="khalti_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="khalti_image"
                                                    id="khalti_image" class="d-none">

                                                <img src="{{ get_file($setting['khalti_image'] ?? Storage::url('uploads/payment/khalti.png')) ?? asset(Storage::url('uploads/payment/khalti.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="khalti_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('khalti_unfo', $setting['khalti_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'khalti_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Khalti -->

                        <!-- AuthorizeNet -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="authorizenet">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_authorizenet"
                                    aria-expanded="false" aria-controls="collapseone_authorizenet">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('AuthorizeNet') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_authorizenet" class="accordion-collapse collapse"
                                aria-labelledby="authorizenet" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_authorizenet_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_authorizenet_enabled',
                                                    'on',
                                                    isset($setting['is_authorizenet_enabled']) && $setting['is_authorizenet_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_authorizenet_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_authorizenet_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="authorizenet_mode">{{ __('AuthorizeNet Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="authorizenet_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['authorizenet_mode']) && $setting['authorizenet_mode'] == '') || (isset($setting['authorizenet_mode']) && $setting['authorizenet_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="authorizenet_mode"
                                                                    value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['authorizenet_mode']) && $setting['authorizenet_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="authorizenet_login_id"
                                                    class="col-form-label">{{ __('Merchant Login Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Login Id"
                                                    name="authorizenet_login_id" type="text"
                                                    value="{{ $setting['authorizenet_login_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="authorizenet_transaction_key"
                                                    class="col-form-label">{{ __('Merchant Transaction Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Transaction Key"
                                                    name="authorizenet_transaction_key" type="text"
                                                    value="{{ $setting['authorizenet_transaction_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="authorizenet_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="authorizenet_image"
                                                    id="authorizenet_image" class="d-none">

                                                <img src="{{ get_file($setting['authorizenet_image'] ?? Storage::url('uploads/payment/authorizenet.png')) ?? asset(Storage::url('uploads/payment/authorizenet.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="authorizenet_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('authorizenet_unfo', $setting['authorizenet_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'authorizenet_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End AuthorizeNet -->

                        <!-- Tap -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="tap">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_tap"
                                    aria-expanded="false" aria-controls="collapseone_tap">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Tap') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_tap" class="accordion-collapse collapse"
                                aria-labelledby="tap" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_tap_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_tap_enabled',
                                                    'on',
                                                    isset($setting['is_tap_enabled']) && $setting['is_tap_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_tap_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_tap_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="tap_secret_key"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Secret Key" name="tap_secret_key"
                                                    type="text"
                                                    value="{{ $setting['tap_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="tap_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="tap_image" id="tap_image"
                                                    class="d-none">

                                                <img src="{{ get_file($setting['tap_image'] ?? Storage::url('uploads/payment/tap.png')) ?? asset(Storage::url('uploads/payment/tap.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tap_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('tap_unfo', $setting['tap_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'tap_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Tap -->

                        <!-- PhonePe -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="phonepe">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_phonepe"
                                    aria-expanded="false" aria-controls="collapseone_phonepe">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PhonePe') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_phonepe" class="accordion-collapse collapse"
                                aria-labelledby="phonepe" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_phonepe_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_phonepe_enabled',
                                                    'on',
                                                    isset($setting['is_phonepe_enabled']) && $setting['is_phonepe_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_phonepe_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_phonepe_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="phonepe_mode">{{ __('PhonePe Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="phonepe_mode" value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['phonepe_mode']) && $setting['phonepe_mode'] == '') || (isset($setting['phonepe_mode']) && $setting['phonepe_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="phonepe_mode"
                                                                    value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['phonepe_mode']) && $setting['phonepe_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phonepe_merchant_key"
                                                    class="col-form-label">{{ __('Merchant ID') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant ID"
                                                    name="phonepe_merchant_key" type="text"
                                                    value="{{ $setting['phonepe_merchant_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phonepe_merchant_user_id"
                                                    class="col-form-label">{{ __('Merchant User ID') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant User ID"
                                                    name="phonepe_merchant_user_id" type="text"
                                                    value="{{ $setting['phonepe_merchant_user_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ayment"
                                                    class="col-form-label">{{ __('Salt Key') }}</label>
                                                <input class="form-control" placeholder="Enter Salt Key"
                                                    name="phonepe_salt_key" type="text"
                                                    value="{{ $setting['phonepe_salt_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="phonepe_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="phonepe_image"
                                                    id="phonepe_image" class="d-none">

                                                <img src="{{ get_file($setting['phonepe_image'] ?? Storage::url('uploads/payment/phonepe.png')) ?? asset(Storage::url('uploads/payment/phonepe.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="phonepe_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('phonepe_unfo', $setting['phonepe_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'phonepe_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PhonePe -->

                        <!-- Paddle -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paddle">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paddle"
                                    aria-expanded="false" aria-controls="collapseone_paddle">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paddle') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paddle" class="accordion-collapse collapse"
                                aria-labelledby="paddle" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paddle_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paddle_enabled',
                                                    'on',
                                                    isset($setting['is_paddle_enabled']) && $setting['is_paddle_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paddle_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paddle_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="paddle_mode">{{ __('Paddle Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="paddle_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['paddle_mode']) && $setting['paddle_mode'] == '') || (isset($setting['paddle_mode']) && $setting['paddle_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="paddle_mode"
                                                                    value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['paddle_mode']) && $setting['paddle_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paddle_vendor_id"
                                                    class="col-form-label">{{ __('Vendor ID') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Vendor ID"
                                                    name="paddle_vendor_id" type="text"
                                                    value="{{ $setting['paddle_vendor_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paddle_vendor_auth_code"
                                                    class="col-form-label">{{ __('Vendor Auth Code') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Vendor Auth Code"
                                                    name="paddle_vendor_auth_code" type="text"
                                                    value="{{ $setting['paddle_vendor_auth_code'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ayment"
                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Public Key"
                                                    name="paddle_public_key" type="text"
                                                    value="{{ $setting['paddle_public_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paddle_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paddle_image"
                                                    id="paddle_image" class="d-none">

                                                <img src="{{ get_file($setting['paddle_image'] ?? Storage::url('uploads/payment/paddle.png')) ?? asset(Storage::url('uploads/payment/paddle.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paddle_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paddle_unfo', $setting['paddle_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paddle_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Paddle -->

                        <!-- Paiement Pro -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paiementpro">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paiementpro"
                                    aria-expanded="false" aria-controls="collapseone_paiementpro">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paiement Pro') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paiementpro" class="accordion-collapse collapse"
                                aria-labelledby="paiementpro" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_paiementpro_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paiementpro_enabled',
                                                    'on',
                                                    isset($setting['is_paiementpro_enabled']) && $setting['is_paiementpro_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paiementpro_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paiementpro_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ayment"
                                                    class="col-form-label">{{ __('Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Merchant Id"
                                                    name="paiementpro_merchant_id" type="text"
                                                    value="{{ $setting['paiementpro_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paiementpro_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paiementpro_image"
                                                    id="paiementpro_image" class="d-none">

                                                <img src="{{ get_file($setting['paiementpro_image'] ?? Storage::url('uploads/payment/paiementpro.png')) ?? asset(Storage::url('uploads/payment/paiementpro.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paiementpro_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paiementpro_unfo', $setting['paiementpro_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paiementpro_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Paiement Pro -->

                        <!-- FedaPay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="fedpay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_fedpay"
                                    aria-expanded="false" aria-controls="collapseone_fedpay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('FedaPay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_fedpay" class="accordion-collapse collapse"
                                aria-labelledby="fedpay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_fedpay_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_fedpay_enabled',
                                                    'on',
                                                    isset($setting['is_fedpay_enabled']) && $setting['is_fedpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_fedpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_fedpay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="fedpay_mode">{{ __('FedaPay Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="fedpay_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['fedpay_mode']) && $setting['fedpay_mode'] == '') || (isset($setting['fedpay_mode']) && $setting['fedpay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="fedpay_mode"
                                                                    value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['fedpay_mode']) && $setting['fedpay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ayment"
                                                    class="col-form-label">{{ __('Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Public Key"
                                                    name="fedpay_public_key" type="text"
                                                    value="{{ $setting['fedpay_public_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ayment"
                                                    class="col-form-label">{{ __('Secret Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Secret Key"
                                                    name="fedpay_secret_key" type="text"
                                                    value="{{ $setting['fedpay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="fedpay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="fedpay_image"
                                                    id="fedpay_image" class="d-none">

                                                <img src="{{ get_file($setting['fedpay_image'] ?? Storage::url('uploads/payment/fedpay.png')) ?? asset(Storage::url('uploads/payment/fedpay.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="fedpay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('fedpay_unfo', $setting['fedpay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'fedpay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End FedaPay -->

                        <!-- CinetPay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="CinetPay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_CinetPay"
                                    aria-expanded="false" aria-controls="collapseone_CinetPay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('CinetPay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_CinetPay" class="accordion-collapse collapse"
                                aria-labelledby="CinetPay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_cinetpay_enabled',
                                                    'on',
                                                    isset($setting['is_cinetpay_enabled']) && $setting['is_cinetpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_cinetpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_cinetpay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="CinetPay_Site_id"
                                                    class="form-label">{{ __('CinetPay Site Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="CinetPay Site Id"
                                                    name="cinet_pay_site_id" type="text"
                                                    value="{{ $setting['cinet_pay_site_id'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="cinet_pay_api_key"
                                                    class="form-label">{{ __('CinetPay API KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="CinetPay API KEY"
                                                    name="cinet_pay_api_key" type="text"
                                                    value="{{ $setting['cinet_pay_api_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="cinet_pay_secret_key"
                                                    class="form-label">{{ __('CinetPay SECRET KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="CinetPay SECRET KEY"
                                                    name="cinet_pay_secret_key" type="text"
                                                    value="{{ $setting['cinet_pay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="cinet_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="cinet_pay_image"
                                                    id="cinet_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['cinet_pay_image'] ?? Storage::url('uploads/payment/cinet.png')) ?? asset(Storage::url('uploads/payment/cinet.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="stripe_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('cinet_pay_unfo', $setting['cinet_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'cinet_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End CinetPay -->

                        <!-- Senangpay -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="Senangpay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_Senangpay"
                                    aria-expanded="false" aria-controls="collapseone_Senangpay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('SenangPay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_Senangpay" class="accordion-collapse collapse"
                                aria-labelledby="Senangpay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_Senangpay_enabled',
                                                    'on',
                                                    isset($setting['is_Senangpay_enabled']) && $setting['is_Senangpay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_Senangpay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_Senangpay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="Senangpay_mode">{{ __('SenangPay Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="Senangpay_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['Senangpay_mode']) && $setting['Senangpay_mode'] == '') || (isset($setting['Senangpay_mode']) && $setting['Senangpay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="Senangpay_mode" value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['Senangpay_mode']) && $setting['Senangpay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="Senangpay_merchant_id"
                                                    class="form-label">{{ __('SenangPay Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="SenangPay Merchant Id"
                                                    name="senang_pay_merchant_id" type="text"
                                                    value="{{ $setting['senang_pay_merchant_id'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="senang_pay_secret_key"
                                                    class="form-label">{{ __('SenangPay SECRET KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="SenangPay SECRET KEY"
                                                    name="senang_pay_secret_key" type="text"
                                                    value="{{ $setting['senang_pay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="senang_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="senang_pay_image"
                                                    id="senang_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['senang_pay_image'] ?? Storage::url('uploads/payment/senang.png')) ?? asset(Storage::url('uploads/payment/senang.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="senang_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('senang_pay_unfo', $setting['senang_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'senang_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Senangpay -->

                        <!--CyberSource -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="cybersource">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_cybersource"
                                    aria-expanded="false" aria-controls="collapseone_cybersource">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('CyberSource') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_cybersource" class="accordion-collapse collapse"
                                aria-labelledby="cybersource" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_cybersource_enabled',
                                                    'on',
                                                    isset($setting['is_cybersource_enabled']) && $setting['is_cybersource_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_cybersource_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_cybersource_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cybersource_pay_merchant_id"
                                                    class="form-label">{{ __('CyberSource Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="CyberSource Merchant Id"
                                                    name="cybersource_pay_merchant_id" type="text"
                                                    value="{{ $setting['cybersource_pay_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cybersource_pay_secret_key"
                                                    class="form-label">{{ __('CyberSource SECRET KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="CyberSource SECRET KEY"
                                                    name="cybersource_pay_secret_key" type="text"
                                                    value="{{ $setting['cybersource_pay_secret_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="cybersource_pay_api_key"
                                                    class="form-label">{{ __('CyberSource API KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="CyberSource API KEY"
                                                    name="cybersource_pay_api_key" type="text"
                                                    value="{{ $setting['cybersource_pay_api_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="cybersource_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="cybersource_pay_image"
                                                    id="cybersource_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['cybersource_pay_image'] ?? Storage::url('uploads/payment/cybersource.png')) ?? asset(Storage::url('uploads/payment/cybersource.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="cybersource_pay_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('cybersource_pay_unfo', $setting['cybersource_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'cybersource_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End CyberSource -->

                        <!-- Ozow -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="ozow">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_ozow"
                                    aria-expanded="false" aria-controls="collapseone_ozow">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Ozow') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_ozow" class="accordion-collapse collapse"
                                aria-labelledby="ozow" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_ozow_enabled',
                                                    'on',
                                                    isset($setting['is_ozow_enabled']) && $setting['is_ozow_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_ozow_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_ozow_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="ozow_mode">{{ __('Ozow Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="ozow_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['ozow_mode']) && $setting['ozow_mode'] == '') || (isset($setting['ozow_mode']) && $setting['ozow_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="ozow_mode"
                                                                    value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['ozow_mode']) && $setting['ozow_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ozow_pay_site_key"
                                                    class="form-label">{{ __('Ozow Site KEY') }}</label>
                                                <input class="form-control" placeholder="Ozow Site KEY"
                                                    name="ozow_pay_Site_key" type="text"
                                                    value="{{ $setting['ozow_pay_Site_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ozow_pay_private_key"
                                                    class="form-label">{{ __('ozow Private KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="Ozow Private KEY"
                                                    name="ozow_pay_private_key" type="text"
                                                    value="{{ $setting['ozow_pay_private_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="ozow_pay_api_key"
                                                    class="form-label">{{ __('Ozow API KEY') }}</label>
                                                <input class="form-control" placeholder="Ozow API KEY"
                                                    name="ozow_pay_api_key" type="text"
                                                    value="{{ $setting['ozow_pay_api_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="ozow_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="ozow_pay_image"
                                                    id="ozow_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['ozow_pay_image'] ?? Storage::url('uploads/payment/ozow.png')) ?? asset(Storage::url('uploads/payment/ozow.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ozow_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('ozow_pay_unfo', $setting['ozow_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'ozow_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Ozow -->

                        <!-- myfatoorah -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="myfatoorah">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_myfatoorah"
                                    aria-expanded="false" aria-controls="collapseone_myfatoorah">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('MyFatoorah') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_myfatoorah" class="accordion-collapse collapse"
                                aria-labelledby="myfatoorah" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_myfatoorah_enabled',
                                                    'on',
                                                    isset($setting['is_myfatoorah_enabled']) && $setting['is_myfatoorah_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_myfatoorah_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_myfatoorah_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="myfatoorah_mode">{{ __('MyFatoorah Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="myfatoorah_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['myfatoorah_mode']) && $setting['myfatoorah_mode'] == '') || (isset($setting['myfatoorah_mode']) && $setting['myfatoorah_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="myfatoorah_mode"
                                                                    value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['myfatoorah_mode']) && $setting['myfatoorah_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="myfatoorah_pay_api_key"
                                                    class="form-label">{{ __('MyFatoorah API KEY') }}</label>
                                                <input class="form-control"
                                                    placeholder="MyFatoorah API KEY"
                                                    name="myfatoorah_pay_api_key" type="text"
                                                    value="{{ $setting['myfatoorah_pay_api_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="myfatoorah_pay_country_iso"
                                                    class="form-label">{{ __('MyFatoorah Country ISO') }}</label>
                                                <input class="form-control"
                                                    placeholder="MyFatoorah Country ISO"
                                                    name="myfatoorah_pay_country_iso" type="text"
                                                    value="{{ $setting['myfatoorah_pay_country_iso'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="myfatoorah_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="myfatoorah_pay_image"
                                                    id="myfatoorah_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['myfatoorah_pay_image'] ?? Storage::url('uploads/payment/myfatoorah.png')) ?? asset(Storage::url('uploads/payment/myfatoorah.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="myfatoorah_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('myfatoorah_pay_unfo', $setting['myfatoorah_pay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'myfatoorah_pay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End myfatoorah -->

                        <!-- Easebuzz -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="easebuzz">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_easebuzz"
                                    aria-expanded="false" aria-controls="collapseone_easebuzz">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Easebuzz') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_easebuzz" class="accordion-collapse collapse"
                                aria-labelledby="easebuzz" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_easebuzz_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_easebuzz_enabled',
                                                    'on',
                                                    isset($setting['is_easebuzz_enabled']) && $setting['is_easebuzz_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_easebuzz_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_easebuzz_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="easebuzz_merchant_key"
                                                    class="col-form-label">{{ __('Easebuzz Merchant Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Easebuzz Merchant Key"
                                                    name="easebuzz_merchant_key" type="text"
                                                    value="{{ $setting['easebuzz_merchant_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="easebuzz_salt_key"
                                                    class="col-form-label">{{ __('Easebuzz Salt Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Easebuzz Salt Key"
                                                    name="easebuzz_salt_key" type="text"
                                                    value="{{ $setting['easebuzz_salt_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="easebuzz_enviroment_name"
                                                    class="col-form-label">{{ __('Easebuzz Enviroment Name') }}</label>
                                                <input class="form-control"
                                                    placeholder="Enter Easebuzz Salt Key"
                                                    name="easebuzz_enviroment_name" type="text"
                                                    value="{{ $setting['easebuzz_enviroment_name'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="easebuzz_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="easebuzz_image"
                                                    id="easebuzz_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['easebuzz_image'] ?? Storage::url('uploads/payment/easebuzz.png')) ?? asset(Storage::url('uploads/payment/easebuzz.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="easebuzz_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('easebuzz_unfo', $setting['easebuzz_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'easebuzz_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Easebuzz -->

                        <!-- NMI -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="nmi">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_nmi"
                                    aria-expanded="false" aria-controls="collapseone_nmi">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('NMI') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_nmi" class="accordion-collapse collapse"
                                aria-labelledby="nmi" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            {!! Form::hidden('is_nmi_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_nmi_enabled',
                                                    'on',
                                                    isset($setting['is_nmi_enabled']) && $setting['is_nmi_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_nmi_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_nmi_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nmi_api_private_key"
                                                        class="col-form-label">{{ __('NMI Api Private Key') }}</label>
                                                    <input class="form-control"
                                                        placeholder="Enter NMI Api Private Key"
                                                        name="nmi_api_private_key" type="text"
                                                        value="{{ $setting['nmi_api_private_key'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                    <label for="nmi_image"
                                                        class="image-upload bg-primary pointer">
                                                        <i class="ti ti-upload px-1"></i>
                                                        {{ __('Choose file here') }}
                                                    </label>
                                                    <input type="file" name="nmi_image"
                                                        id="nmi_image" class="d-none">

                                                    <img alt="Image placeholder"
                                                        src="{{ get_file($setting['nmi_image'] ?? Storage::url('uploads/payment/nmi.png')) ?? asset(Storage::url('uploads/payment/nmi.png')) }}"
                                                        class="img-center img-fluid"
                                                        style="max-height: 100px;">
                                                </div>
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nmi_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('nmi_unfo', $setting['nmi_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'nmi_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End NMI -->

                        <!-- PayU -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="payu">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_payu"
                                    aria-expanded="false" aria-controls="collapseone_payu">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PayU') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_payu" class="accordion-collapse collapse"
                                aria-labelledby="payu" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_payu_enabled',
                                                    'on',
                                                    isset($setting['is_payu_enabled']) && $setting['is_payu_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_payu_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_payu_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="payu_mode">{{ __('PayU Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="payu_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['payu_mode']) && $setting['payu_mode'] == '') || (isset($setting['payu_mode']) && $setting['payu_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="payu_mode"
                                                                    value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['payu_mode']) && $setting['payu_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="payu_merchant_key"
                                                    class="col-form-label">{{ __('Merchant Key') }}</label>
                                                <input class="form-control" placeholder="Merchant Key"
                                                    name="payu_merchant_key" type="text"
                                                    value="{{ $setting['payu_merchant_key'] ?? '' }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="payu_salt_key"
                                                    class="col-form-label">{{ __('Salt Key') }}</label>
                                                <input class="form-control" placeholder="Salt Key"
                                                    name="payu_salt_key" type="text"
                                                    value="{{ $setting['payu_salt_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="payu_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="payu_image"
                                                    id="payu_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['payu_image'] ?? Storage::url('uploads/payment/payu.png')) ?? asset(Storage::url('uploads/payment/payu.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="payu_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('payu_unfo', $setting['payu_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'payu_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PayU -->

                        <!-- PayNow-->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="paynow">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_paynow"
                                    aria-expanded="false" aria-controls="collapseone_paynow">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Paynow') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_paynow" class="accordion-collapse collapse"
                                aria-labelledby="paynow" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_paynow_enabled',
                                                    'on',
                                                    isset($setting['is_paynow_enabled']) && $setting['is_paynow_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_paynow_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_paynow_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="paynow_mode">{{ __('paynow Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="paynow_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['paynow_mode']) && $setting['paynow_mode'] == '') || (isset($setting['paynow_mode']) && $setting['paynow_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="paynow_mode"
                                                                    value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['paynow_mode']) && $setting['paynow_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paynow_pay_integration_id"
                                                    class="form-label">{{ __('PayNow Integration ID') }}</label>
                                                <input class="form-control"
                                                    placeholder="PayNow Integration ID"
                                                    name="paynow_pay_integration_id" type="text"
                                                    value="{{ $setting['paynow_pay_integration_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="paynow_pay_integration_key"
                                                    class="form-label">{{ __('PayNow Integration Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="PayNow Integration Key"
                                                    name="paynow_pay_integration_key" type="text"
                                                    value="{{ $setting['paynow_pay_integration_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div id="paynow_pay_merchant_email" class="col-md-6"
                                            style="display: none;">
                                            <div class="form-group">
                                                <label for="paynow_pay_merchant_email"
                                                    class="form-label">{{ __('Paynow Merchant Email') }}</label>
                                                <input class="form-control"
                                                    placeholder="Paynow Merchant Email"
                                                    name="paynow_pay_merchant_email" type="text"
                                                    value="{{ $setting['paynow_pay_merchant_email'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="paynow_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="paynow_pay_image"
                                                    id="paynow_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['paynow_pay_image'] ?? Storage::url('uploads/payment/paynow.png')) ?? asset(Storage::url('uploads/payment/paynow.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paynow_pay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('paynow_pay_unfo', $setting['paynow_pay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'paynow_pay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PayNow -->

                        <!-- Sofort -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="sofort">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_sofort"
                                    aria-expanded="false" aria-controls="collapseone_sofort">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Sofort') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_sofort" class="accordion-collapse collapse"
                                aria-labelledby="sofort" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_sofort_enabled',
                                                    'on',
                                                    isset($setting['is_sofort_enabled']) && $setting['is_sofort_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_sofort_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_sofort_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="sofort_key"
                                                    class="form-label">{{ __('Sofort Publishable Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="sofort Publishable Key"
                                                    name="sofort_publishable_key" type="text"
                                                    value="{{ $setting['sofort_publishable_key'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="sofort_secret"
                                                    class="form-label">{{ __('Sofort Secret Key') }}</label>
                                                <input class="form-control" placeholder="sofort Secret"
                                                    name="sofort_secret_key" type="text"
                                                    value="{{ $setting['sofort_secret_key'] ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="sofort_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="sofort_image"
                                                    id="sofort_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['sofort_image'] ?? Storage::url('uploads/payment/sofort.png')) ?? asset(Storage::url('uploads/payment/sofort.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sofort_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('sofort_unfo', $setting['sofort_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'sofort_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Sofort -->

                        <!-- ESewa  -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="esewa">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_esewa"
                                    aria-expanded="false" aria-controls="collapseone_esewa">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('ESewa') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_esewa" class="accordion-collapse collapse"
                                aria-labelledby="esewa" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_esewa_enabled',
                                                    'on',
                                                    isset($setting['is_esewa_enabled']) && $setting['is_esewa_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_esewa_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_esewa_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="esewa_mode">{{ __('ESewa Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="esewa_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['esewa_mode']) && $setting['esewa_mode'] == '') || (isset($setting['esewa_mode']) && $setting['esewa_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio" name="esewa_mode"
                                                                    value="live"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['esewa_mode']) && $setting['esewa_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                {{ __('Live') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="esewa_merchant_key"
                                                    class="col-form-label pt-0">{{ __('ESewa Merchant Id') }}</label>
                                                <input type="text" name="esewa_merchant_key"
                                                    id="esewa_merchant_key" class="form-control"
                                                    value="{{ $setting['esewa_merchant_key'] ?? '' }}"
                                                    placeholder="{{ __('esewa Access Token') }}" />
                                                @if ($errors->has('esewa_secret_key'))
                                                    <span class="invalid-feedback d-block">
                                                        {{ $errors->first('esewa_merchant_key') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="esewa_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="esewa_image"
                                                    id="esewa_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['esewa_image'] ?? Storage::url('uploads/payment/esewa.png')) ?? asset(Storage::url('uploads/payment/esewa.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="esewa_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('esewa_unfo', $setting['esewa_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'esewa_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End ESewa -->

                        <!-- DPO -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="dpopay">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_dpopay"
                                    aria-expanded="false" aria-controls="collapseone_dpopay">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('DPO Pay') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_dpopay" class="accordion-collapse collapse"
                                aria-labelledby="dpopay" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="mt-2 row">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_dpopay_enabled',
                                                    'on',
                                                    isset($setting['is_dpopay_enabled']) && $setting['is_dpopay_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_dpopay_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_dpopay_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dpo_pay_Company_Token"
                                                    class="form-label">{{ __('Company Token') }}</label>
                                                <input class="form-control"
                                                    placeholder="dpo Company Token"
                                                    name="dpo_pay_Company_Token" type="text"
                                                    value="{{ $setting['dpo_pay_Company_Token'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dpo_pay_Service_Type"
                                                    class="form-label">{{ __('Service Type') }}</label>
                                                <input class="form-control"
                                                    placeholder="dpo Service Type"
                                                    name="dpo_pay_Service_Type" type="text"
                                                    value="{{ $setting['dpo_pay_Service_Type'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="dpo_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="px-1 ti ti-upload"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="dpo_pay_image"
                                                    id="dpo_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                    src="{{ get_file($setting['dpo_pay_image'] ?? Storage::url('uploads/payment/dpo.png')) ?? asset(Storage::url('uploads/payment/dpo.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="dpo_pay_unfo"
                                                        class="form-label">{{ __('Description') }}</label>
                                                    {!! Form::textarea('dpo_pay_unfo', $setting['dpo_pay_unfo'] ?? '', [
                                                        'class' => 'autogrow form-control',
                                                        'rows' => '5',
                                                        'id' => 'dpo_pay_unfo',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End DPO -->

                        <!-- Braintree -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="braintree">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_braintree"
                                    aria-expanded="false" aria-controls="collapseone_braintree">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('Braintree') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_braintree" class="accordion-collapse collapse"
                                aria-labelledby="braintree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_braintree_enabled',
                                                    'on',
                                                    isset($setting['is_braintree_enabled']) && $setting['is_braintree_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_braintree_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_braintree_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="braintree_mode">{{ __('Braintree Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="braintree_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['braintree_mode']) && $setting['braintree_mode'] == '') || (isset($setting['braintree_mode']) && $setting['braintree_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="braintree_mode" value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['braintree_mode']) && $setting['braintree_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="braintree_pay_merchant_id"
                                                    class="form-label">{{ __('Braintree Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="Braintree Merchant Id"
                                                    name="braintree_pay_merchant_id" type="text"
                                                    value="{{ $setting['braintree_pay_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="braintree_pay_public_key"
                                                    class="form-label">{{ __('Braintree Public Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Braintree Public Key"
                                                    name="braintree_pay_public_key" type="text"
                                                    value="{{ $setting['braintree_pay_public_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="braintree_pay_private_key"
                                                    class="form-label">{{ __('Braintree Private Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="Braintree Private Key"
                                                    name="braintree_pay_private_key" type="text"
                                                    value="{{ $setting['braintree_pay_private_key'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="braintree_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="braintree_pay_image"
                                                    id="braintree_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                src="{{ get_file($setting['braintree_pay_image'] ?? Storage::url('uploads/payment/braintree.png')) ?? asset(Storage::url('uploads/payment/braintree.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="braintree_pay_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('braintree_pay_unfo', $setting['braintree_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'braintree_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Braintree -->

                        <!-- PowerTranz -->
                        <div class="accordion-item card">
                            <h2 class="accordion-header" id="powertranz">
                                <button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseone_powertranz"
                                    aria-expanded="false" aria-controls="collapseone_powertranz">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-credit-card me-2"></i>
                                        {{ __('PowerTranz') }}</span>
                                </button>
                            </h2>
                            <div id="collapseone_powertranz" class="accordion-collapse collapse"
                                aria-labelledby="powertranz" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <small class="">
                                                {{ __('Note') }}:
                                                {{ __('This detail will use for make checkout of product') }}.
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox(
                                                    'is_powertranz_enabled',
                                                    'on',
                                                    isset($setting['is_powertranz_enabled']) && $setting['is_powertranz_enabled'] === 'on',
                                                    [
                                                        'class' => 'form-check-input',
                                                        'id' => 'is_powertranz_enabled',
                                                    ],
                                                ) !!}
                                                <label class="custom-control-label form-control-label"
                                                    for="is_powertranz_enabled"></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 pb-4">
                                            <label class="col-form-label"
                                                for="powertranz_mode">{{ __('PowerTranz Mode') }}</label>
                                            <br>
                                            <div class="d-flex flex-wrap">
                                                <div class="mr-2" style="margin-right: 15px;">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="powertranz_mode"
                                                                    value="sandbox"
                                                                    class="form-check-input"
                                                                    checked="checked"
                                                                    {{ (isset($setting['powertranz_mode']) && $setting['powertranz_mode'] == '') || (isset($setting['powertranz_mode']) && $setting['powertranz_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                {{ __('Sandbox') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mr-2">
                                                    <div class="border card p-3">
                                                        <div class="form-check">
                                                            <label class="form-check-labe text-dark">
                                                                <input type="radio"
                                                                    name="powertranz_mode" value="production"
                                                                    class="form-check-input"
                                                                    {{ isset($setting['powertranz_mode']) && $setting['powertranz_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                {{ __('Production') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="powertranz_pay_merchant_id"
                                                    class="form-label">{{ __('PowerTranz Merchant Id') }}</label>
                                                <input class="form-control"
                                                    placeholder="PowerTranz Merchant Id"
                                                    name="powertranz_pay_merchant_id" type="text"
                                                    value="{{ $setting['powertranz_pay_merchant_id'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="powertranz_pay_processing_password"
                                                    class="form-label">{{ __('PowerTranz Processing Password') }}</label>
                                                <input class="form-control"
                                                    placeholder="PowerTranz Processing Password"
                                                    name="powertranz_pay_processing_password" type="text"
                                                    value="{{ $setting['powertranz_pay_processing_password'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div id="powertranz_pay_production_url" class="col-md-6" style="display: none;">
                                            <div class="form-group">
                                                <label for="powertranz_pay_url"
                                                    class="form-label">{{ __('PowerTranz Production url') }}</label>
                                                <input class="form-control"
                                                    placeholder="PowerTranz Production url"
                                                    name="powertranz_pay_production_url" type="text"
                                                    value="{{ $setting['powertranz_pay_production_url'] ?? '' }}">
                                                    <small class="">
                                                        {{ __('Example') }}:
                                                        {{ __('https://TBD.ptranz.com') }}
                                                    </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
                                                <label for="powertranz_pay_image"
                                                    class="image-upload bg-primary pointer">
                                                    <i class="ti ti-upload px-1"></i>
                                                    {{ __('Choose file here') }}
                                                </label>
                                                <input type="file" name="powertranz_pay_image"
                                                    id="powertranz_pay_image" class="d-none">

                                                <img alt="Image placeholder"
                                                src="{{ get_file($setting['powertranz_pay_image'] ?? Storage::url('uploads/payment/powertranz.png')) ?? asset(Storage::url('uploads/payment/powertranz.png')) }}"
                                                    class="img-center img-fluid"
                                                    style="max-height: 100px;">
                                            </div>
                                        </div>
                                        @if (\Auth::user()->type == 'admin')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="powertranz_pay_unfo"
                                                    class="form-label">{{ __('Description') }}</label>
                                                {!! Form::textarea('powertranz_pay_unfo', $setting['powertranz_pay_unfo'] ?? '', [
                                                    'class' => 'autogrow form-control',
                                                    'rows' => '5',
                                                    'id' => 'powertranz_pay_unfo',
                                                ]) !!}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End PowerTranz -->

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}"
                    class="btn-submit btn btn-primary">
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- End Payment Setting -->
