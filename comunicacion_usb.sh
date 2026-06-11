#!/bin/bash
# Script para enviar un comando y leer la respuesta del puerto USB

PUERTO="/dev/ttyUSB0"
COMANDO="P"

# Configurar el puerto serie con los parámetros correctos
stty -F $PUERTO 9600 cs8 -cstopb -parenb -ixon -ixoff -crtscts

# Envía el comando al puerto
echo -n "$COMANDO" > $PUERTO

# Pequeña pausa para esperar la respuesta del dispositivo
sleep 0.1

# Lee la respuesta del puerto
RESULTADO=$(timeout 1 cat $PUERTO | tr -d '\r' | grep -oE '[0-9]+(\.[0-9]+)?')
echo "$RESULTADO"

exit 0

