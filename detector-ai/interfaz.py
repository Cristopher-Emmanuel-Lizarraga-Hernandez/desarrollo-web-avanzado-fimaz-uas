import cv2
import math
import numpy as np
from ultralytics import YOLO

# ── Modelo ─────────────────────────────────────────────────
model = YOLO('modelo_basura.pt')

# ── Clases e información ───────────────────────────────────
clsName = ['Metal', 'Glass', 'Plastic', 'Carton', 'Medical']

INFO = {
    'Metal':   ['Lata:        80-100 años', 'Aluminio:    200-500 años', 'Hierro:      100 años'],
    'Glass':   ['Botella:     4000 años',   'Vidrio:      No se degrada','Cristal:     1000 años'],
    'Plastic': ['Bolsa:       20-1000 años','Botella PET: 500 años',     'Unicel:      500 años'],
    'Carton':  ['Carton:      2-3 meses',   'Papel:       2-6 semanas',  'Revista:     1 año'],
    'Medical': ['Jeringa:     1000 años',   'Medicamento: 2m - 1 año',   'Insecticida: 2m - 1 año'],
}

COLORES = {
    'Metal':   (  0, 200, 255),
    'Glass':   (  0, 220, 120),
    'Plastic': (220,  80,  80),
    'Carton':  (  0, 180, 255),
    'Medical': (  0,   0, 220),
}

CONTENEDOR = {
    'Metal':   'RECICLABLE',
    'Glass':   'RECICLABLE',
    'Plastic': 'RECICLABLE',
    'Carton':  'RECICLABLE',
    'Medical': 'RIESGO BIOLOGICO',
}

# ── Canvas principal ───────────────────────────────────────
ANCHO, ALTO = 1100, 620
CAM_X, CAM_Y = 220, 80
CAM_W, CAM_H = 660, 480

cap = cv2.VideoCapture(0)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

deteccion_actual = None

def dibujar_texto_centrado(img, texto, cx, y, escala, color, grosor=1):
    (tw, _), _ = cv2.getTextSize(texto, cv2.FONT_HERSHEY_SIMPLEX, escala, grosor)
    cv2.putText(img, texto, (cx - tw // 2, y),
                cv2.FONT_HERSHEY_SIMPLEX, escala, color, grosor)

def dibujar_panel(canvas, x, y, w, h, color_borde, titulo):
    cv2.rectangle(canvas, (x, y), (x + w, y + h), (30, 30, 30), -1)
    cv2.rectangle(canvas, (x, y), (x + w, y + h), color_borde, 2)
    cv2.rectangle(canvas, (x, y), (x + w, y + 36), color_borde, -1)
    dibujar_texto_centrado(canvas, titulo, x + w // 2, y + 24, 0.65, (255, 255, 255), 2)

print("Interfaz iniciada. Presiona Q para salir.")

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # Canvas oscuro
    canvas = np.zeros((ALTO, ANCHO, 3), dtype=np.uint8)
    canvas[:] = (20, 25, 30)

    # ── Título ──────────────────────────────────────────
    cv2.rectangle(canvas, (0, 0), (ANCHO, 65), (15, 80, 70), -1)
    dibujar_texto_centrado(canvas, "Reciclaje Inteligente", ANCHO // 2, 38, 1.1, (255, 255, 255), 2)

    estado = f"Detectado: {deteccion_actual}" if deteccion_actual else "Escaneando..."
    dibujar_texto_centrado(canvas, estado, ANCHO // 2, 58, 0.45, (180, 220, 180))

    # ── Panel izquierdo: Clasificación ──────────────────
    dibujar_panel(canvas, 10, 80, 195, 480, (0, 180, 150), "Clasificacion")

    if deteccion_actual:
        color = COLORES.get(deteccion_actual, (255, 255, 255))
        etiq  = CONTENEDOR.get(deteccion_actual, '')
        # Ícono contenedor (rectángulo simulado)
        cv2.rectangle(canvas, (40, 140), (175, 260), color, -1)
        cv2.rectangle(canvas, (40, 140), (175, 260), (255, 255, 255), 2)
        # Símbolo reciclaje simulado
        dibujar_texto_centrado(canvas, "[R]", 107, 210, 1.2, (255, 255, 255), 3)
        # Nombre clase
        dibujar_texto_centrado(canvas, deteccion_actual.upper(), 107, 295, 0.7, color, 2)
        # Etiqueta contenedor
        for i, linea in enumerate(etiq.split()):
            pass
        dibujar_texto_centrado(canvas, etiq, 107, 320, 0.45, (200, 200, 200))
    else:
        dibujar_texto_centrado(canvas, "Sin objeto", 107, 220, 0.5, (120, 120, 120))

    # ── Panel derecho: Información ───────────────────────
    dibujar_panel(canvas, 895, 80, 195, 480, (220, 120, 0), "Informacion")

    if deteccion_actual and deteccion_actual in INFO:
        dibujar_texto_centrado(canvas, "Tiempo de", 992, 140, 0.45, (200, 200, 200))
        dibujar_texto_centrado(canvas, "descomposicion:", 992, 158, 0.45, (200, 200, 200))
        for i, linea in enumerate(INFO[deteccion_actual]):
            cv2.putText(canvas, linea, (905, 195 + i * 28),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.38, (180, 220, 180), 1)
        color = COLORES.get(deteccion_actual, (255, 255, 255))
        etiq  = CONTENEDOR.get(deteccion_actual, '')
        dibujar_texto_centrado(canvas, etiq, 992, 420, 0.45, color, 1)
    else:
        dibujar_texto_centrado(canvas, "Sin datos", 992, 220, 0.45, (120, 120, 120))

    # ── Cámara con detección ─────────────────────────────
    cam_resized = cv2.resize(frame, (CAM_W, CAM_H))
    resultados  = model(cam_resized, imgsz=320, verbose=False)[0]

    deteccion_actual = None
    mejor_conf = 0

    for box in resultados.boxes:
        cls      = int(box.cls[0])
        conf     = math.ceil(box.conf[0] * 100)
        if conf < 40:
            continue
        nombre   = clsName[cls] if cls < len(clsName) else 'Unknown'
        color    = COLORES.get(nombre, (255, 255, 255))

        x1, y1, x2, y2 = map(int, box.xyxy[0])
        x1, y1 = max(0, x1), max(0, y1)

        cv2.rectangle(cam_resized, (x1, y1), (x2, y2), color, 2)
        etiqueta = f"{nombre}  {conf}%"
        (tw, th), _ = cv2.getTextSize(etiqueta, cv2.FONT_HERSHEY_SIMPLEX, 0.65, 2)
        cv2.rectangle(cam_resized, (x1, y1 - th - 12), (x1 + tw + 10, y1), color, -1)
        cv2.putText(cam_resized, etiqueta, (x1 + 5, y1 - 6),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.65, (255, 255, 255), 2)

        if conf > mejor_conf:
            mejor_conf = conf
            deteccion_actual = nombre

    # Pegar cámara en canvas
    canvas[CAM_Y:CAM_Y + CAM_H, CAM_X:CAM_X + CAM_W] = cam_resized
    cv2.rectangle(canvas, (CAM_X, CAM_Y), (CAM_X + CAM_W, CAM_Y + CAM_H), (0, 180, 150), 2)

    cv2.imshow("Reciclaje Inteligente", canvas)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()