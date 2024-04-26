<a type="button" @if(App\Models\Support::getWhatsApp() != '#') target="_blank" @endif href="{{ App\Models\Support::getWhatsApp() }}"
 class="btn btn-link position-fixed opacity-75 bg-success rounded-circle" style="padding: 0px 2px 0px 2px; right: 20px; bottom: 10px;" title="Suporte">
    <i class="bi-whatsapp text-white" style="font-size: 25pt; padding: 0 6px 0 6px;"></i>
</a>
