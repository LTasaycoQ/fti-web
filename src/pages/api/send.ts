import type { APIRoute } from 'astro';
import { Resend } from 'resend';

const resend = new Resend('re_QGBAu9X7_BnHWw71kcgGSaXgcez8tbU9H');

export const POST: APIRoute = async ({ request }) => {
  const data = await request.formData();
  
  const nombre = data.get('Nombre');
  const apellido = data.get('Apellido');
  const email = data.get('Email');
  const telefono = data.get('Telefono');
  const mensaje = data.get('Mensaje');

  try {
    await resend.emails.send({
      from: 'onboarding@resend.dev', 
      to: 'luistasayco3030@gmail.com',
      subject: `Consulta de ${nombre} sobre Terra Andina Colonial Mansion`,
      html: `
        <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;">
            <div style="background-color: #C08E1F; padding: 20px; text-align: center;">
                <h2 style="color: white; margin: 0; text-transform: uppercase; letter-spacing: 2px; font-size: 20px;">Nueva Reserva / Contacto</h2>
            </div>

            <div style="padding: 30px; background-color: #ffffff;">
                <p style="color: #333; font-size: 16px; margin-bottom: 25px;">Has recibido un nuevo mensaje desde el formulario web de <strong>Terra Andina Colonial Mansion</strong>:</p>
                
                <div style="background-color: #f9f9f9; padding: 20px; border-left: 4px solid #CEC061; border-radius: 5px;">
                    <p style="margin: 10px 0; color: #555;"><strong style="color: #C08E1F;">Nombre:</strong> ${nombre} ${apellido}</p>
                    <p style="margin: 10px 0; color: #555;"><strong style="color: #C08E1F;">Email:</strong> <a href="mailto:${email}" style="color: #333; text-decoration: none;">${email}</a></p>
                    <p style="margin: 10px 0; color: #555;"><strong style="color: #C08E1F;">Teléfono:</strong> ${telefono}</p>
                </div>

                <div style="margin-top: 25px;">
                    <h3 style="color: #333; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Mensaje:</h3>
                    <p style="color: #555; line-height: 1.6; font-style: italic; background-color: #fff9eb; padding: 15px; border-radius: 5px;">
                        "${mensaje}"
                    </p>
                </div>
            </div>

            <div style="background-color: #333; padding: 15px; text-align: center;">
                <p style="color: #999; font-size: 12px; margin: 0;">
                    Este es un correo automático generado por el sistema de Terra Andina.
                </p>
            </div>
        </div>
        `,
    });

    return new Response(JSON.stringify({ message: "¡Enviado!" }), { status: 200 });
  } catch (error) {
    return new Response(JSON.stringify({ error: "Fallo el envío" }), { status: 500 });
  }
};