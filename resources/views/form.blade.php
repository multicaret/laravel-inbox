<div class="form-group{{ $errors->has('recipients') ? ' has-error' : '' }}">
	<label for="recipients" class="control-label">@lang('inbox::strings.form.to')</label>
	<select class="selectpicker show-tick" type="text" id="recipients" name="recipients[]"
	        value="{{ old('recipients') }}"
	        data-live-search="true" data-size="10" data-show-subtext="true" data-style="btn-info" data-width="100%"
	        required multiple>
		<option></option>
		@foreach($recipients as $user)
			<option value="{{ $user->id }}"
			        data-subtext="{{ $user->username }}" {{ in_array($user->id, old('recipients') ?? []) ? 'selected' : '' }}>
				{{ $user->name }}
			</option>
		@endforeach
	</select>
	@if ($errors->has('recipients'))
		<span class="help-block">
            <b>{{ $errors->first('recipients') }}</b>
        </span>
	@endif
</div>

<div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
	<label for="subject" class="control-label">@lang('inbox::strings.form.subject')</label>
	<input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}" required>
	@if ($errors->has('subject'))
		<span class="help-block">
            <b>{{ $errors->first('subject') }}</b>
        </span>
	@endif
</div>

<div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
	<label for="body" class="control-label">@lang('inbox::strings.form.body')</label>
	<textarea id="body" name="body" class="form-control" rows="6" required>{{ old('body') }}</textarea>
	@if ($errors->has('body'))
		<span class="help-block">
            <b>{{ $errors->first('body') }}</b>
        </span>
	@endif
</div>