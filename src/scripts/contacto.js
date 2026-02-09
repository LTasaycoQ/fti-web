// src/scripts/contacto.js
import Swal from 'sweetalert2';

export function setupForm() {
    const form = document.querySelector('.form-contacto');

    form?.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        const formData = new FormData(form);

        try {
            const response = await fetch('/api/send', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                Swal.fire({
                    title: "¡Enviado!",
                    text: "Tu mensaje ha sido enviado",
                    icon: "success",
                    confirmButtonColor: "#CEC061"
                });
                form.reset();
            } else {
                throw new Error();
            }
        } catch (error) {
            Swal.fire({
                title: "Error",
                text: "No pudimos enviar tu mensaje. Intenta de nuevo.",
                icon: "error"
            });
        }
    });
}

// Ejecutar la función
setupForm();