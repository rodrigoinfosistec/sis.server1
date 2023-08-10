<div class="text-primary" style="font-size: 10pt;">
    <span class="@if(Str::length($comment) > 255) text-danger @endif">
        {{255 - Str::length($comment) }} caracteres.
    </span>
</div>
