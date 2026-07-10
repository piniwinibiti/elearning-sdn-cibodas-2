import cv2            # OpenCV (opencv-contrib-python) - deteksi wajah & LBPH face recognizer
import os              # operasi path/folder (baca isi folder dataset, cek file ada, dsb)
import numpy as np     # array numerik - dipakai untuk konversi gambar & daftar id label
from PIL import Image  # membuka file gambar dan mengonversinya ke grayscale ('L')

def train_model():
    # Folder tempat script ini berada, jadi semua path lain dihitung relatif dari sini
    path = os.path.dirname(os.path.abspath(__file__))
    dataset_path = os.path.join(path, 'dataset')  # folder berisi foto hasil registrasi wajah (User.ID.SAMPLE.jpg)

#algoritma lbph
    # Buat recognizer LBPH (Local Binary Patterns Histograms) - akan dilatih dari sample foto
    recognizer = cv2.face.LBPHFaceRecognizer_create()

    # File Haar Cascade untuk mendeteksi lokasi wajah dalam tiap foto dataset
    cascade_path = os.path.join(path, 'haarcascade_frontalface_default.xml')
    if not os.path.exists(cascade_path):
        # Tanpa file ini, wajah dalam foto dataset tidak bisa dideteksi sama sekali
        print("ERROR: haarcascade_frontalface_default.xml tidak ditemukan.")
        sys.exit(1)  # NOTE: 'sys' belum di-import di scope ini kecuali cv2 gagal di-import saat __main__ (lihat baris 69-73) -> baris ini akan NameError jika benar-benar dieksekusi

    detector = cv2.CascadeClassifier(cascade_path)  # detektor wajah siap dipakai

    if not os.path.exists(dataset_path):
        # Folder dataset belum ada sama sekali -> buat foldernya dan minta user isi foto dulu
        os.makedirs(dataset_path)
        print("Folder dataset dibuat. Harap masukkan foto siswa ke dalam folder tersebut sebelum training.")
        return False

    # Ambil semua path file foto (.jpg / .png) di dalam folder dataset
    imagePaths = [os.path.join(dataset_path, f) for f in os.listdir(dataset_path) if f.endswith('.jpg') or f.endswith('.png')]

    if len(imagePaths) == 0:
        # Folder dataset ada tapi kosong -> tidak ada yang bisa dilatih
        print("ERROR: Tidak ada gambar di folder dataset.")
        return False

    faceSamples = []  # kumpulan crop wajah (grayscale) dari semua foto dataset
    ids = []           # label user_id yang berpasangan 1:1 dengan tiap entri di faceSamples

    print("Memulai proses training wajah. Silakan tunggu...")

    for imagePath in imagePaths:

        # Buka foto & convert ke grayscale ('L'), lalu ubah jadi array numpy uint8
        # (LBPH & Haar Cascade sama-sama bekerja di grayscale)
        PIL_img = Image.open(imagePath).convert('L')
        img_numpy = np.array(PIL_img, 'uint8')

        try:
            # Nama file mengikuti format "User.[ID].[SAMPLE].jpg" -> ambil bagian ID (index [1] setelah split by ".")
            id = int(os.path.split(imagePath)[-1].split(".")[1])
            # Deteksi semua wajah di foto ini (idealnya cuma 1 wajah per foto dataset)
            faces = detector.detectMultiScale(img_numpy)

            for (x, y, w, h) in faces:
                # Crop area wajah saja, lalu simpan sebagai sample training beserta label id-nya
                faceSamples.append(img_numpy[y:y+h, x:x+w])
                ids.append(id)
        except Exception as e:
            # Nama file tidak sesuai format yang diharapkan -> lewati file ini, jangan hentikan proses training
            print(f"Melewati file {imagePath} karena format salah. Harap namakan file: User.[ID_SISWA].[Bebas].jpg")

    if len(faceSamples) > 0:
        # Mulai training
        # Latih model LBPH dari seluruh crop wajah beserta label id masing-masing
        recognizer.train(faceSamples, np.array(ids))

        # Simpan model
        # Tulis hasil model terlatih ke file trainer.yml (dipakai oleh recognize.py)
        recognizer.write(os.path.join(path, 'trainer.yml'))
        print(f"SUKSES: Model dilatih dengan {len(np.unique(ids))} id wajah unik.")
        return True
    else:
        # Ada file foto, tapi tidak ada satupun wajah yang berhasil dideteksi di semuanya
        print("ERROR: Tidak ada wajah yang dapat dideteksi dari dataset.")
        return False

if __name__ == "__main__":
    # Script ini dijalankan sebagai proses CLI (dipanggil oleh PythonRunner dari Laravel,
    # atau dijalankan manual lewat terminal untuk training/testing)
    try:
        import cv2  # cek ulang ketersediaan OpenCV sebelum training dimulai
    except ImportError:
        print("ERROR: Modul opencv-contrib-python tidak ditemukan! Harap jalankan: pip install opencv-contrib-python")
        import sys
        sys.exit(1)  # hentikan proses, tidak mungkin training tanpa OpenCV

    train_model()  # jalankan proses training yang sebenarnya
