import cv2
import os
import numpy as np
from PIL import Image

def train_model():
    
    path = os.path.dirname(os.path.abspath(__file__))
    dataset_path = os.path.join(path, 'dataset')
    
#algoritma lbph
    recognizer = cv2.face.LBPHFaceRecognizer_create()
    
    
    cascade_path = os.path.join(path, 'haarcascade_frontalface_default.xml')
    if not os.path.exists(cascade_path):
        print("ERROR: haarcascade_frontalface_default.xml tidak ditemukan.")
        sys.exit(1)
        
    detector = cv2.CascadeClassifier(cascade_path)
    
    
    if not os.path.exists(dataset_path):
        os.makedirs(dataset_path)
        print("Folder dataset dibuat. Harap masukkan foto siswa ke dalam folder tersebut sebelum training.")
        return False
        
    
    imagePaths = [os.path.join(dataset_path, f) for f in os.listdir(dataset_path) if f.endswith('.jpg') or f.endswith('.png')]
    
    if len(imagePaths) == 0:
        print("ERROR: Tidak ada gambar di folder dataset.")
        return False

    faceSamples = []
    ids = []

    print("Memulai proses training wajah. Silakan tunggu...")
    
    for imagePath in imagePaths:
       
        PIL_img = Image.open(imagePath).convert('L')
        img_numpy = np.array(PIL_img, 'uint8')
       
        try:
            id = int(os.path.split(imagePath)[-1].split(".")[1])
            faces = detector.detectMultiScale(img_numpy)
            
            for (x, y, w, h) in faces:
                faceSamples.append(img_numpy[y:y+h, x:x+w])
                ids.append(id)
        except Exception as e:
            print(f"Melewati file {imagePath} karena format salah. Harap namakan file: User.[ID_SISWA].[Bebas].jpg")

    if len(faceSamples) > 0:
        # Mulai training
        recognizer.train(faceSamples, np.array(ids))
        
        # Simpan model
        recognizer.write(os.path.join(path, 'trainer.yml'))
        print(f"SUKSES: Model dilatih dengan {len(np.unique(ids))} id wajah unik.")
        return True
    else:
        print("ERROR: Tidak ada wajah yang dapat dideteksi dari dataset.")
        return False

if __name__ == "__main__":
    try:
        import cv2
    except ImportError:
        print("ERROR: Modul opencv-contrib-python tidak ditemukan! Harap jalankan: pip install opencv-contrib-python")
        import sys
        sys.exit(1)
        
    train_model()
