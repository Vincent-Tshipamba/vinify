<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if ($excerptedText)
        @foreach ($excerptedText as $excerpt)
            <p class="bg-gray-800 text-white p-2 mb-8 rounded overflow-auto whitespace-pre-wrap">{!! $excerpt['highlighted'] !!}</p>
        @endforeach
    @else
        <p>Aucun extrait de texte disponible.</p>
    @endif
</div>
