<div class="w-full flex items-center justify-center" style="height: 700px;">
    @if($type === 'pdf')
        <iframe src="{{ $url }}" class="w-full h-full border-0 rounded-lg"></iframe>
    @else
        <img src="{{ $url }}" class="max-w-full max-h-full object-contain rounded-lg" alt="Comprobante">
    @endif
</div>

