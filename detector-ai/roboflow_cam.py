import cv2
import base64
import numpy as np
from inference_sdk import InferenceHTTPClient

# Conexión a Roboflow
client = InferenceHTTPClient(
    api_url="https://serverless.roboflow.com",
    api_key="1HMdZKyEkubztV1PEjg0"
)

# Colores por clase
COLORES = {
    'can':                 (180, 180,   0),
    'PET':                 (220,  80,  80),
    'Color Glass Bottles': (  0, 220, 120),
}

cap = cv2.VideoCapture(1)
cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

frame_count = 0
ultimo_resultado = []

print("Detector Roboflow iniciado. Presiona Q para salir.")

while True:
    ret, frame = cap.read()
    if not ret:
        break

    frame_count += 1

    # Inferencia cada 5 frames (API tiene límite de requests)
    if frame_count % 5 == 0:
        # Convertir frame a base64
        _, buffer = cv2.imencode('.jpg', frame)
        img_b64 = base64.b64encode(buffer).decode('utf-8')

        try:
            result = client.run_workflow(
                workspace_name="lizarraga-hernandez-cristopher-emmanuel",
                workflow_id="general-segmentation-api-8",
                images={"image": img_b64},
                parameters={"classes": "can, PET, Color Glass Bottles"},
                use_cache=True
            )
            ultimo_resultado = result
        except Exception as e:
            print(f"Error: {e}")

    # Dibujar resultados
    if ultimo_resultado:
        try:
            predictions = ultimo_resultado[0].get('predictions', {}).get('predictions', [])
            for pred in predictions:
                clase     = pred.get('class', '')
                confianza = pred.get('confidence', 0) * 100
                x         = int(pred.get('x', 0))
                y         = int(pred.get('y', 0))
                w         = int(pred.get('width', 0))
                h_box     = int(pred.get('height', 0))

                x1, y1 = x - w // 2, y - h_box // 2
                x2, y2 = x + w // 2, y + h_box // 2

                color = COLORES.get(clase, (255, 255, 255))
                cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)

                etiqueta = f"{clase}  {confianza:.0f}%"
                (tw, th), _ = cv2.getTextSize(etiqueta, cv2.FONT_HERSHEY_SIMPLEX, 0.65, 2)
                cv2.rectangle(frame, (x1, y1 - th - 12), (x1 + tw + 10, y1), color, -1)
                cv2.putText(frame, etiqueta, (x1 + 5, y1 - 6),
                            cv2.FONT_HERSHEY_SIMPLEX, 0.65, (255, 255, 255), 2)
        except Exception as e:
            print(f"Error dibujando: {e}")

    h = frame.shape[0]
    cv2.putText(frame, "Roboflow Waste Detector  |  Q = salir",
                (10, h - 12), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (200, 200, 200), 1)

    cv2.imshow("Waste Detector", frame)
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()