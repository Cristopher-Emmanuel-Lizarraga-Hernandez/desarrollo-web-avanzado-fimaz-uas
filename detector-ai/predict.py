import torch
from torchvision import transforms, models
from PIL import Image
from torch import nn
import sys

# Cargar modelo guardado
checkpoint = torch.load('modelo_basura.pth', map_location='cpu')
clases = checkpoint['clases']

modelo = models.efficientnet_b0(weights=None)
modelo.classifier[1] = nn.Linear(modelo.classifier[1].in_features, len(clases))
modelo.load_state_dict(checkpoint['modelo'])
modelo.eval()

# Transformar imagen
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize([0.485, 0.456, 0.406],
                         [0.229, 0.224, 0.225])
])

# Predecir
ruta = sys.argv[1]  # pasa la imagen por argumento
img = Image.open(ruta).convert('RGB')
tensor = transform(img).unsqueeze(0)

with torch.no_grad():
    salida = modelo(tensor)
    probabilidades = torch.softmax(salida, dim=1)[0]
    idx = probabilidades.argmax().item()

print(f"\nClasificación: {clases[idx].upper()}")
print(f"Confianza:     {probabilidades[idx]*100:.1f}%")
print("\nTodas las probabilidades:")
for i, c in enumerate(clases):
    print(f"  {c:12s}: {probabilidades[i]*100:.1f}%")