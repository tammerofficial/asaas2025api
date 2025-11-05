@php
    $language = explode('_', get_default_language());
    $language = current($language);
@endphp

@if($language != 'en')
    <script src="//npmcdn.com/flatpickr/dist/l10n/{{$language ?? 'en'}}.js"></script>
    <script>
        // Only localize if flatpickr is loaded
        if (typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.{{$language}}) {
            flatpickr.localize(flatpickr.l10ns.{{$language}});
        }
    </script>
@endif
