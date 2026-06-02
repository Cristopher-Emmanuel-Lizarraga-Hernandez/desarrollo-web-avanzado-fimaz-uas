import cv2
from ultralytics import YOLO
from collections import defaultdict

# ── Modelo YOLOv8 preentrenado ─────────────────────────────
modelo = YOLO('modelo_basura.pt')  # se descarga automático la primera vez

# ── Clases en español y colores ───────────────────────────
CLASES_ES = {
    'cardboard': ('Carton',          (  0, 180, 255)),
    'glass':     ('Vidrio',          (  0, 220, 120)),
    'metal':     ('Metal',           (180, 180,   0)),
    'paper':     ('Papel',           ( 60, 160, 220)),
    'plastic':   ('Plastico',        (220,  80,  80)),
    'trash':     ('Riesgo Biologico',(  0,   0, 220)),
}

# Mapa de clases COCO que nos interesan → categoría de basura
COCO_A_BASURA = {
    'bottle':      'glass',
    'cup':         'plastic',
    'wine glass':  'glass',
    'can':         'metal',
    'scissors':    'metal',
    'cell phone':  'metal',
    'book':        'paper',
    'laptop':      'metal',
    'keyboard':    'metal',
    'remote':      'metal',
    'backpack':    'trash',
    'handbag':     'trash',
    'suitcase':    'cardboard',
    'box':         'cardboard',
    'banana':      'trash',
    'apple':       'trash',
    'orange':      'trash',
    'sandwich':    'trash',
    'fork':        'metal',
    'knife':       'metal',
    'spoon':       'metal',
    'bowl':        'plastic',
    'vase':        'glass',
    'toothbrush':  'plastic',
}

# ── Cámara ─────────────────────────────────────────────────
cap = cv2.VideoCapture(1)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

print("Detector iniciado. Presiona Q para salir.")

while True:
    ret, frame = cap.read()
    if not ret:
        break

    resultados = modelo(frame, conf=0.4, verbose=False)[0]

    for box in resultados.boxes:
        cls_id   = int(box.cls[0])
        cls_name = modelo.names[cls_id]
        confianza = float(box.conf[0]) * 100

        # Solo objetos mapeados a basura
        if cls_name not in COCO_A_BASURA:
            continue

        categoria = COCO_A_BASURA[cls_name]
        nombre_es, color = CLASES_ES[categoria]

        x1, y1, x2, y2 = map(int, box.xyxy[0])

        # Recuadro
        cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)

        # Etiqueta con fondo
        etiqueta = f"{nombre_es}  {confianza:.0f}%"
        (tw, th), _ = cv2.getTextSize(etiqueta, cv2.FONT_HERSHEY_SIMPLEX, 0.65, 2)
        cv2.rectangle(frame, (x1, y1 - th - 12), (x1 + tw + 10, y1), color, -1)
        cv2.putText(frame, etiqueta, (x1 + 5, y1 - 6),
                    cv2.FONT_HERSHEY_SIMPLEX, 0.65, (255, 255, 255), 2)

    # Instrucción
    h = frame.shape[0]
    cv2.putText(frame, "Detector de basura  |  Q = salir",
                (10, h - 12), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (200, 200, 200), 1)

    cv2.imshow("Clasificador de Basura", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()