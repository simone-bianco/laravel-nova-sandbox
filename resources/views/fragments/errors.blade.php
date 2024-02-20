@if (isset($error) && !empty($error))
    <div>
        @php
            $decodedError = json_decode($error, true);
        @endphp
        @if ($decodedError === JSON_ERROR_NONE || !is_array($decodedError))
            <p>{{ $error }}</p>
        @else
            <ul>
                @foreach ($decodedError as $key => $errorMessages)
                    @if (is_array($errorMessages))
                        @foreach ($errorMessages as $errorMessage)
                            <li>
                                <strong class="text-danger">{{ $key }}</strong>: {{ $errorMessage }}
                            </li>
                        @endforeach
                    @else
                        <li>{{ $errorMessages }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
@endif
