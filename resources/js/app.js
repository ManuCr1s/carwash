import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;
document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal', (data) => {
        Swal.fire(data[0]);
    });
});