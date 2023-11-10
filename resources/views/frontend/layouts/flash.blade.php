@if ($errors->has('fail'))
    <div class="alert alert-danger " role="alert">
        <div>
            {{ $errors->first('fail') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">

        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
