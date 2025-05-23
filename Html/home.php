<?php 
    session_start();
    if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'visitante') {
        header("Location: ../html/Login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Papelo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Estilos/home.css">
</head>
<body>
    <?php include '../Includes/header.php'; ?>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a Papelo</h1>
            <p class="lead mb-5">Tu solución integral para materiales de oficina y papelería de alta calidad</p>
        </div>
    </section>

    <main class="container my-5">
    <section class="mb-5">
        <h2 class="text-center mb-5">Cómo funciona</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-cart-plus feature-icon"></i>
                    <h3>Selecciona tus productos</h3>
                    <p>Explora nuestro catálogo y agrega los artículos a tu carrito de compras.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-person-check feature-icon"></i>
                    <h3>2. Solo di tu nombre</h3>
                    <p>Al finalizar, tu pedido quedará asociado a tu nombre de usuario.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-cash feature-icon"></i>
                    <h3>3. Paga en tienda</h3>
                    <p>Acude a la papelería, menciona tu nombre y realiza el pago.</p>
                </div>
            </div>
        
        <section class="cta-section text-center">
            <div class="container">
                <h2 class="mb-4">¿Listo para comenzar?</h2>
                <p class="lead mb-4">Explora nuestro catálogo y descubre todo lo que tenemos para ofrecerte</p>
                <a href="../Controladores/controlCatalogo.php" class="btn btn-primary btn-lg px-4 me-2" style="background-color: #2c3e50; border-color: #2c3e50;">Ver prouctos</a>
            </div>
        </section>
    </main>
    
    <?php include '../Includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <div id="chatbot" style="position: fixed; bottom: 20px; right: 20px; width: 320px; background: #fff; border: none; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); transform: translateY(20px); opacity: 0; transition: all 0.3s ease-out; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div style="background:  #2c3e50; color: white; padding: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; font-size: 16px;">
                <i class="bi bi-robot" style="margin-right: 8px;"></i> Asistente Papelo
            </div>
            <button id="minimizeChat" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                <i class="bi bi-dash"></i>
            </button>
        </div>
        <div id="chatMensajes" style="height: 250px; overflow-y: auto; padding: 15px; font-size: 14px; background: #f9f9f9; display: flex; flex-direction: column; gap: 12px;">
            <div style="align-self: flex-start; max-width: 80%; background: #3498db; padding: 10px 12px; border-radius: 12px 12px 12px 0; color: #333; line-height: 1.4;">
                <p style ="color: #fff;">¡Hola! Soy tu asistente de Papelo. ¿En qué puedo ayudarte hoy?</p>
            </div>
        </div>
        <div style="padding: 12px; border-top: 1px solid #eee; background: white; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; display: flex; gap: 8px;">
            <input type="text" id="inputMensaje" placeholder="Escribe tu mensaje..." style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 20px; outline: none; transition: border 0.3s; font-size: 14px;" onfocus="this.style.borderColor='#3498db'">
            <button onclick="enviarMensaje()" style="background: #3498db; color: white; border: none; border-radius: 20px; padding: 0 16px; cursor: pointer; transition: background 0.3s; display: flex; align-items: center; gap: 5px;">
                <i class="bi bi-send-fill" style="font-size: 12px;"></i> Enviar
            </button>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('chatbot').style.transform = 'translateY(0)';
            document.getElementById('chatbot').style.opacity = '1';
        }, 500);

        document.getElementById('minimizeChat').addEventListener('click', function() {
            const chat = document.getElementById('chatbot');
            const mensajes = document.getElementById('chatMensajes');
            
            if (mensajes.style.display !== 'none') {
                mensajes.style.display = 'none';
                chat.style.height = 'auto';
                this.innerHTML = '<i class="bi bi-plus"></i>';
            } else {
                mensajes.style.display = 'flex';
                chat.style.height = '';
                this.innerHTML = '<i class="bi bi-dash"></i>';
            }
        });

        function enviarMensaje() {
            const input = document.getElementById('inputMensaje');
            const mensaje = input.value.trim();
            
            if (mensaje) {
                const chat = document.getElementById('chatMensajes');
                chat.innerHTML += `
                    <div style="align-self: flex-end; max-width: 80%; background: #3498db; color: white; padding: 10px 12px; border-radius: 12px 12px 0 12px; line-height: 1.4;">
                        ${mensaje}
                    </div>
                `;
                input.value = '';
                setTimeout(() => {
                    chat.innerHTML += `
                        <div style="align-self: flex-start; max-width: 80%; background: #e3f2fd; padding: 10px 12px; border-radius: 12px 12px 12px 0; color: #333; line-height: 1.4;">
                            Gracias por tu mensaje. Un agente te responderá pronto.
                        </div>
                    `;
                    chat.scrollTop = chat.scrollHeight;
                }, 800);
                
                chat.scrollTop = chat.scrollHeight;
            }
        }
        document.getElementById('inputMensaje').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                enviarMensaje();
            }
        });
    </script>

    
    <script>
        function enviarMensaje() {
            const input = document.getElementById('inputMensaje');
            const mensaje = input.value.trim();
            if (!mensaje) return;

            const contenedor = document.getElementById('chatMensajes');

            contenedor.innerHTML += `
                <div style="align-self: flex-end; max-width: 80%; background: #e3f2fd; color: #333; padding: 10px 12px; border-radius: 12px 12px 0 12px; line-height: 1.4;">
                    ${mensaje}
                </div>
            `;
            contenedor.scrollTop = contenedor.scrollHeight;
            input.value = '';

            fetch('../Controladores/controlChatBot.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({mensaje})
            })
            .then(res => res.json())
            .then(data => {
                contenedor.innerHTML += `
                    <div style="align-self: flex-start; max-width: 80%; background: #3498db; padding: 10px 12px; border-radius: 12px 12px 12px 0; color: white; line-height: 1.4;">
                        ${data.respuesta}
                    </div>
                `;
                contenedor.scrollTop = contenedor.scrollHeight;
            })
            .catch(error => {
                contenedor.innerHTML += `
                    <div style="align-self: flex-start; max-width: 80%; background: #f8d7da; padding: 10px 12px; border-radius: 12px 12px 12px 0; color: #721c24; line-height: 1.4;">
                        Hubo un error al procesar tu mensaje. Intenta nuevamente.
                    </div>
                `;
                contenedor.scrollTop = contenedor.scrollHeight;
            });
        }

        document.getElementById('inputMensaje').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                enviarMensaje();
            }
        });
    </script>



</body>
</html>