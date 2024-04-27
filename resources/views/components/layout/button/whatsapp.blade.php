<a type="button" @if(App\Models\Support::getWhatsApp() != '#') target="_blank" @endif href="{{ App\Models\Support::getWhatsApp() }}"
 class="btn btn-link position-fixed opacity-75 rounded-circle" style="background-color: #25D366; padding: 0px 2px 0px 2px; right: 20px; bottom: 25px;" title="Suporte">
    <i class="bi-whatsapp text-white" style="font-size: 20pt; padding: 0 4px 0 4px;"></i>
</a>
