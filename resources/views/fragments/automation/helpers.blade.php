@foreach($helpers as $key => $helper)
    - {{ $key }}: {{ __($helper) }}<br>
@endforeach
