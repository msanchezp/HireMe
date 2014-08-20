<div class="form-group">
    {{Form::label($name,$label)}}
    {{ $control }}
    @if ($error)
    	<p class="error_message">{{ $error }}</p>
    @endif
</div>

{{--
	<div class="form-group">
        {{Form::label('full_name','Nombre completo')}}
        {{Form::text('full_name',null,['class'=>'form-control'])}}
        {{$errors->first('full_name','<p class="error_message">:message</p>')}}
    </div>
--}}