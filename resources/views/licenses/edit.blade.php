@extends('layouts/edit-form', [
    'createText' => trans('admin/licenses/form.create'),
    'updateText' => trans('admin/licenses/form.update'),
    'topSubmit' => true,
    'formAction' => ($item->id) ? route('licenses.update', ['license' => $item->id]) : route('licenses.store'),
])

{{-- Page content --}}
@section('inputFields')
@include ('partials.forms.edit.name', ['translated_name' => trans('admin/licenses/form.name')])
@include ('partials.forms.edit.category-select', ['translated_name' => trans('admin/categories/general.category_name'), 'fieldname' => 'category_id', 'required' => 'true', 'category_type' => 'license'])


<!-- Serial-->
@can('viewKeys', $item)
<div class="form-group {{ $errors->has('serial') ? ' has-error' : '' }}">
    <label for="serial" class="col-md-3 control-label">{{ trans('admin/licenses/form.license_key') }}</label>
    <div class="col-md-7{{  (Helper::checkIfRequired($item, 'serial')) ? ' required' : '' }}">
        <textarea class="form-control" type="text" name="serial" id="serial">{{ old('serial', $item->serial) }}</textarea>
        {!! $errors->first('serial', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>
@endcan

<!-- Seats -->
<div class="form-group {{ $errors->has('seats') ? ' has-error' : '' }}">
    <label for="seats" class="col-md-3 control-label">{{ trans('admin/licenses/form.seats') }}</label>
    <div class="col-md-7 col-sm-12 required">
        <div class="col-md-2" style="padding-left:0px">
            <input class="form-control" type="text" name="seats" id="seats" value="{{ Request::old('seats', $item->seats) }}" />
        </div>
            {{-- <input type="checkbox" name="hasSeatCodes" id="seatCodes" />{{trans('admin/licenses/form.has_codes')}} --}}
    </div>
    {!! $errors->first('seats', '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>') !!}
</div>


<!-- show/hide seat codes field -->
<div class="form-group {{ $errors->has('has_codes') ? ' has-error' : '' }}">
    <label for="has_codes" class="col-md-3 control-label">{{ trans('admin/licenses/form.has_codes') }}</label>
    <div class="col-md-7 input-group">
        {{ Form::Checkbox('has_codes', '1', old('has_codes', $item->id ? $item->has_codes : '1'),array('class' => 'minimal', 'aria-label'=>'has_seat_codes','id' => 'has_seat_codes')) }}
        {{ trans('general.yes') }}
       
    </div>
</div>

<!-- codes-->
@can('viewCodes', $item)
<div id="seat_codes_field">
    <div class="form-group {{ $errors->has('codes') ? ' has-error' : '' }}">
        <label for="codes" class="col-md-3 control-label">{{ trans('admin/licenses/form.codes') }}</label>
        <div class="col-md-7{{  (Helper::checkIfRequired($item, 'codes')) ? ' required' : '' }}">
            <textarea class="form-control" type="text" name="codes" id="codes">{{ old('codes', $item->codes) }}</textarea>
            {!! $errors->first('codes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
        </div>
    </div>
</div>
@endcan

@include ('partials.forms.edit.company-select', ['translated_name' => trans('general.company'), 'fieldname' => 'company_id'])
@include ('partials.forms.edit.manufacturer-select', ['translated_name' => trans('general.manufacturer'), 'fieldname' => 'manufacturer_id', 'required' => 'true'])

<!-- Licensed to name -->
<div class="form-group {{ $errors->has('license_name') ? ' has-error' : '' }}">
    <label for="license_name" class="col-md-3 control-label">{{ trans('admin/licenses/form.to_name') }}</label>
    <div class="col-md-7">
        <input class="form-control" type="text" name="license_name" id="license_name" value="{{ old('license_name', $item->license_name) }}" />
        {!! $errors->first('license_name', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

<!-- Licensed to email -->
<div class="form-group {{ $errors->has('license_email') ? ' has-error' : '' }}">
    <label for="license_email" class="col-md-3 control-label">{{ trans('admin/licenses/form.to_email') }}</label>
    <div class="col-md-7">
        <input class="form-control" type="text" name="license_email" id="license_email" value="{{ old('license_email', $item->license_email) }}" />
        {!! $errors->first('license_email', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

<!-- Reassignable -->
<div class="form-group {{ $errors->has('reassignable') ? ' has-error' : '' }}">
    <label for="reassignable" class="col-md-3 control-label">{{ trans('admin/licenses/form.reassignable') }}</label>
    <div class="col-md-7 input-group">
        {{ Form::Checkbox('reassignable', '1', old('reassignable', $item->id ? $item->reassignable : '1'),array('class' => 'minimal', 'aria-label'=>'reassignable')) }}
        {{ trans('general.yes') }}
    </div>
</div>


@include ('partials.forms.edit.supplier-select', ['translated_name' => trans('general.supplier'), 'fieldname' => 'supplier_id'])
@include ('partials.forms.edit.order_number')
@include ('partials.forms.edit.purchase_cost')
@include ('partials.forms.edit.purchase_date')

<!-- Expiration Date -->
<div class="form-group {{ $errors->has('expiration_date') ? ' has-error' : '' }}">
    <label for="expiration_date" class="col-md-3 control-label">{{ trans('admin/licenses/form.expiration') }}</label>

    <div class="input-group col-md-3">
        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
            <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $item->expiration_date) }}">
            <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
        </div>
        {!! $errors->first('expiration_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>

</div>

<!-- Termination Date -->
<div class="form-group {{ $errors->has('termination_date') ? ' has-error' : '' }}">
    <label for="termination_date" class="col-md-3 control-label">{{ trans('admin/licenses/form.termination_date') }}</label>

    <div class="input-group col-md-3">
        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd"  data-autoclose="true">
            <input type="text" class="form-control" placeholder="{{ trans('general.select_date') }}" name="termination_date" id="termination_date" value="{{ old('termination_date', $item->termination_date) }}">
            <span class="input-group-addon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
        </div>
        {!! $errors->first('termination_date', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

{{-- @TODO How does this differ from Order #? --}}
<!-- Purchase Order -->
<div class="form-group {{ $errors->has('purchase_order') ? ' has-error' : '' }}">
    <label for="purchase_order" class="col-md-3 control-label">{{ trans('admin/licenses/form.purchase_order') }}</label>
    <div class="col-md-3">
        <input class="form-control" type="text" name="purchase_order" id="purchase_order" value="{{ old('purchase_order', $item->purchase_order) }}" />
        {!! $errors->first('purchase_order', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>

@include ('partials.forms.edit.depreciation')

<!-- Maintained -->
<div class="form-group {{ $errors->has('maintained') ? ' has-error' : '' }}">
    <label for="maintained" class="col-md-3 control-label">{{ trans('admin/licenses/form.maintained') }}</label>
    <div class="checkbox col-md-7">
        {{ Form::Checkbox('maintained', '1', old('maintained', $item->maintained),array('class' => 'minimal', 'aria-label'=>'maintained')) }}
        {{ trans('general.yes') }}
    </div>
</div>

@include ('partials.forms.edit.notes')

@stop


@section('moar_scripts')

<script nonce="{{ csrf_token() }}">

$(document).ready(function() {
    const codesText = document.getElementById("codes");
    const seatCount = document.getElementById("seats");

    codesText.addEventListener('input', () => {
        var numSeats = codesText.value.split(/[,|.|\n|\r|\r\n]/).filter(Boolean);
        seatCount.value = numSeats.length;
    });

    $('input#has_seat_codes').on('ifChecked', function(){
        alert("yep");
    });


});

</script>
@stop