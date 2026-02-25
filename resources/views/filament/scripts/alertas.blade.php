<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.addEventListener('alerta-ruta-detenida', event => {

    Swal.fire({
        icon: 'info',
        title: '🏁 Ruta Detenida',
        text: 'La ruta ' + event.detail.nombre + ' ha terminado.',
        timer: 3000,
        showConfirmButton: false,
        position: 'center'
    });

});
</script>