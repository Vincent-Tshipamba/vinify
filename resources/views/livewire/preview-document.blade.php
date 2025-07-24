<div>
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>        
    @endif

    @if($fileUrl)
        <h2 class="text-2xl font-bold mb-4">Nom du document : <span class="text-white">{{ $documentName }}</span></h2>
        <iframe src="{{ $fileUrl }}" class="w-full h-screen" frameborder="0"></iframe>
    @else
        <p>Aucun document disponible pour l'affichage.</p>
    @endif
</div>
