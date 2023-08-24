<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Nuevas Entradas</title>
</head>
<body>
    <h1>Notificaciones</h1>
    <div id="notification-container"></div>

    <script>
        function checkNewEntries() {
            // Realizar una solicitud AJAX al servidor para obtener información sobre nuevas entradas
            fetch('check_new_entries.php')
                .then(response => response.json())
                .then(data => {
                    // Comprueba si hay nuevas entradas
                    if (data && data.length > 0) {
                        const notificationContainer = document.getElementById('notification-container');
                        notificationContainer.innerHTML = '<p>Nueva entrada encontrada en la base de datos.</p>';
                        // Puedes agregar aquí el código para mostrar una notificación emergente más atractiva, como usar librerías o CSS personalizado.
                    }
                })
                .catch(error => console.error('Error al obtener nuevas entradas:', error));
        }

        // Verificar nuevas entradas cada 30 segundos (o el intervalo que prefieras)
        setInterval(checkNewEntries, 30000); // 30 segundos
    </script>
</body>
</html>
