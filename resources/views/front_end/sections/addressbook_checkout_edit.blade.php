<div class="row list_height_css d-none">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::text('billing_info[billing_address]', !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '') !!}
            {!! Form::text('billing_info[billing_company_name]', null) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Country') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[billing_country]', $country_option, !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '', ['class' => 'form-control country_change', 'placeholder' => 'Select Country', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Region') }} / {{ __('State') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[billing_state]', [], null, ['class' => 'form-control state_name state_chage','placeholder' => 'Select State','required' => true,'data-select' => !empty($DeliveryAddress->state) ? $DeliveryAddress->state : '']) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('City') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[billing_city]', [], null, ['class' => 'form-control city_change delivery_list','placeholder' => 'Select City','required' => true,'data-select' =>  !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '' ]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Post Code') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::text('billing_info[billing_postecode]', !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : ' ' , ["class" => "form-control getvalueforval", "placeholder" => "post code", "required" => true]) !!}
        </div>
    </div>
</div>

<div class="row list_height_css">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::text('billing_info[delivery_address]', !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '', ["class" => "form-control getvalueforval", "placeholder" => "address", "required" => true]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Country') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_country]', $country_option, !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '', ['class' => 'form-control country_change', 'placeholder' => 'Select Country', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Region') }} / {{ __('State') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_state]', [], null, ['class' => 'form-control state_name state_chage','placeholder' => 'Select State','required' => true,'data-select' => !empty($DeliveryAddress->state) ? $DeliveryAddress->state : '']) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('City') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_city]', [], null, ['class' => 'form-control city_change delivery_list','placeholder' => 'Select City','required' => true,'data-select' =>  !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '' ]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Post Code') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::text('billing_info[delivery_postcode]', !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : ' ' , ["class" => "form-control getvalueforval", "placeholder" => "post code", "required" => true]) !!}
        </div>
    </div>
</div>
