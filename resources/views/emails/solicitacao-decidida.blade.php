<x-mail::message>
# Solicitação Decidida

Sua solicitação foi **{{ $status }}**.

@if(!empty($comentario))
**Comentário do aprovador:**  
{{ $comentario }}
@endif

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
