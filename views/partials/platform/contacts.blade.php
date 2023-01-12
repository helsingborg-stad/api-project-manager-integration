@if (!empty($contacts))
    <div class="grid-xs-12 grid-md-auto">
        <div class="box box-filled box--lightblue">
            <div class="box-content">
                <h3>{{ $labels['contacts'] }}</h3>
                <ul class="unordered-list">
                    @foreach ($contacts as $contact)
                        <li>
                            <h4>
                                {{ $contact['name'] }}
                                @if (!empty($contact['role']))
                                    <br><small>{{ $contact['role'] }}</small>
                                @endif
                            </h4>
                            <a href="mailto: {{ $contact['mail'] }}">{{ $contact['mail'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
