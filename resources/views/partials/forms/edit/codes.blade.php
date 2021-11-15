<!-- Codes -->
<div class="form-group {{ $errors->has('codes') ? ' has-error' : '' }}">
    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ trans('admin/hardware/form.codes') }} </label>
    <div class="col-md-7 col-sm-12{{  (Helper::checkIfRequired($item, 'codes')) ? ' required' : '' }}">
        <input class="form-control" type="text" name="{{ $fieldname }}" id="{{ $fieldname }}" value="{{ old('codes', $item->codes) }}" />
        {!! $errors->first('codes', '<span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span>') !!}
    </div>
</div>
