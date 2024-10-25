<div class="card" id="Loyality_program">
    {{ Form::open(['route' => 'loyality.program.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="card-header d-flex justify-content-between ">
        <h5 class=""> {{ __('Loyality Program') }} </h5>
        {!! Form::hidden('loyality_program_enabled', 'off') !!}
        <div class="form-check form-switch d-inline-block">
            {!! Form::checkbox(
            'loyality_program_enabled',
            'on',
            isset($setting['loyality_program_enabled']) && $setting['loyality_program_enabled'] == 'on',
            [
            'class' => 'form-check-input',
            'id' => 'loyality_program_enabled',
            ],
            ) !!}
            <label class="custom-control-label form-control-label" for="loyality_program_enabled"></label>

        </div>
    </div>

    <div class="card-body p-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-4 form-group">
                {!! Form::label('', __('Reward points on orders of ' . (isset($setting['CURRENCY']) ? $setting['CURRENCY'] : '$') . '1000'), ['class' =>
                'form-label']) !!}
                {!! Form::number('reward_point', $setting['reward_point'] ?? '', [
                'class' => 'form-control',
                'placeholder' => 'Enter Point',
                'step' => 0.01,
                ]) !!}

            </div>
            <div class="col-lg-12 text-end">
                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>