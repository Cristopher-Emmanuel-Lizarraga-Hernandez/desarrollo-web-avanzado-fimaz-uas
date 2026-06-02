import torch
import torchvision
from torchvision import datasets, transforms, models
from torch import nn, optim
from torch.utils.data import DataLoader, random_split

# 1. Transformaciones de imagen
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.RandomHorizontalFlip(),
    transforms.ToTensor(),
    transforms.Normalize([0.485, 0.456, 0.406],
                         [0.229, 0.224, 0.225])
])

# 2. Cargar dataset
dataset = datasets.ImageFolder('dataset/', transform=transform)
clases = dataset.classes
print("Clases detectadas:", clases)

# 3. Dividir en entrenamiento y validación (80/20)
total = len(dataset)
train_size = int(0.8 * total)
val_size = total - train_size
train_data, val_data = random_split(dataset, [train_size, val_size])

train_loader = DataLoader(train_data, batch_size=32, shuffle=True)
val_loader   = DataLoader(val_data,   batch_size=32, shuffle=False)

# 4. Modelo: EfficientNet preentrenado (transfer learning)
modelo = models.efficientnet_b0(weights='IMAGENET1K_V1')
modelo.classifier[1] = nn.Linear(modelo.classifier[1].in_features, len(clases))

device = 'cuda' if torch.cuda.is_available() else 'cpu'
modelo = modelo.to(device)
print(f"Usando: {device}")

# 5. Entrenamiento
criterio = nn.CrossEntropyLoss()
optimizador = optim.Adam(modelo.parameters(), lr=0.001)

EPOCAS = 10
for epoca in range(EPOCAS):
    modelo.train()
    perdida_total = 0
    for imagenes, etiquetas in train_loader:
        imagenes, etiquetas = imagenes.to(device), etiquetas.to(device)
        optimizador.zero_grad()
        salidas = modelo(imagenes)
        perdida = criterio(salidas, etiquetas)
        perdida.backward()
        optimizador.step()
        perdida_total += perdida.item()

    # Validación
    modelo.eval()
    correctos = 0
    with torch.no_grad():
        for imagenes, etiquetas in val_loader:
            imagenes, etiquetas = imagenes.to(device), etiquetas.to(device)
            salidas = modelo(imagenes)
            _, predicciones = torch.max(salidas, 1)
            correctos += (predicciones == etiquetas).sum().item()

    precision = correctos / len(val_data) * 100
    print(f"Época {epoca+1}/{EPOCAS} | Pérdida: {perdida_total:.2f} | Precisión: {precision:.1f}%")

# 6. Guardar modelo
torch.save({'modelo': modelo.state_dict(), 'clases': clases}, 'modelo_basura.pth')
print("Modelo guardado en modelo_basura.pth")