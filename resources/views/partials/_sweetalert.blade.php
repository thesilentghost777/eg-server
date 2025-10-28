{{-- resources/views/partials/_sweetalert.blade.php --}}
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: "{{ session('success') }}",
            timer: 3000, // L'alerte disparaît après 3 secondes
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur !',
            text: "{{ session('error') }}",
        });
    @endif
    
    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Attention !',
            text: "{{ session('warning') }}",
        });
    @endif

    {{-- Ceci gère les erreurs de validation de formulaire --}}
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops... Il y a des erreurs de validation',
            html: `
                <ul style="text-align: left; list-style-position: inside;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    @endif
</script>