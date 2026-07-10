import sys   # untuk membaca argumen CLI (sys.argv)
import json  # untuk membungkus semua output sebagai JSON yang dibaca PHP (PythonRunner)
import os    # untuk operasi path file (cek file ada, gabung path, dsb)

def process_face(image_path):
    # image_path: path absolut ke file foto (JPEG) yang akan dikenali wajahnya
    try:
        import cv2  # import di dalam fungsi agar error-nya bisa ditangani & dilaporkan sebagai JSON, bukan traceback mentah
    except ImportError:
        # OpenCV (opencv-contrib-python) belum terpasang di environment Python ini
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: Modul opencv-contrib-python tidak terinstall. Jalankan: pip install opencv-contrib-python"
        }))
        return  # hentikan proses, tidak bisa lanjut tanpa OpenCV

    # Semua file pendukung (model & cascade) diasumsikan ada di folder yang sama dengan script ini
    path = os.path.dirname(os.path.abspath(__file__))
    model_path = os.path.join(path, 'trainer.yml')  # model hasil training (dibuat oleh train.py)
    cascade_path = os.path.join(path, 'haarcascade_frontalface_default.xml')  # detektor wajah Haar Cascade bawaan OpenCV

    if not os.path.exists(model_path):
        # Belum pernah training sama sekali -> tidak ada model untuk mengenali wajah
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: Model wajah (trainer.yml) belum dibuat. Silakan lakukan training terlebih dahulu di halaman admin."
        }))
        return

    if not os.path.exists(cascade_path):
        # File XML cascade hilang/tidak ter-deploy -> deteksi wajah tidak bisa dilakukan
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: File haarcascade_frontalface_default.xml tidak ditemukan."
        }))
        return

    # Buat recognizer LBPH (Local Binary Patterns Histograms) dan muat model yang sudah dilatih
    recognizer = cv2.face.LBPHFaceRecognizer_create()
    recognizer.read(model_path)
    # Siapkan detektor wajah berbasis Haar Cascade
    face_cascade = cv2.CascadeClassifier(cascade_path)

    # Baca file gambar dari path yang dikirim Laravel
    img = cv2.imread(image_path)
    if img is None:
        # Gagal dibaca -> kemungkinan file korup, format tidak didukung, atau path salah
        print(json.dumps({"success": False, "confidence": 0, "message": "Gambar tidak dapat dibaca."}))
        return

    # LBPH & Haar Cascade bekerja pada citra grayscale, bukan warna
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    # Deteksi semua wajah dalam gambar (bisa lebih dari satu)
    # scaleFactor: seberapa besar gambar diperkecil tiap skala pencarian
    # minNeighbors: makin tinggi makin ketat (mengurangi false positive)
    # minSize: ukuran minimum wajah yang dianggap valid (30x30 px)
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.2, minNeighbors=5, minSize=(30, 30))

    if len(faces) == 0:
        # Tidak ada wajah yang terdeteksi sama sekali di foto
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "Wajah tidak terdeteksi di gambar."
        }))
        return

    # Jika ada beberapa wajah terdeteksi, ambil yang area-nya (w * h) paling besar
    # asumsi: wajah utama/terdekat ke kamera adalah target yang ingin dikenali
    largest_face = max(faces, key=lambda f: f[2] * f[3])
    (x, y, w, h) = largest_face  # koordinat & ukuran bounding box wajah terpilih

    # Jalankan prediksi LBPH pada area wajah (crop grayscale)
    # id_siswa: ID user hasil prediksi (sesuai label saat training, lihat train.py)
    # confidence: sebenarnya adalah "distance" (jarak) - semakin KECIL semakin MIRIP
    id_siswa, confidence = recognizer.predict(gray[y:y+h, x:x+w])

    if confidence < 80:
        # Distance di bawah 80 dianggap cocok -> konversi ke skor persentase kemiripan
        # semakin kecil distance, semakin tinggi skor (100 - distance)
        match_confidence = round(100 - confidence, 2)
        # Batasi skor maksimum di 99.9% (tidak pernah 100% sempurna)
        final_score = min(round(match_confidence, 2), 99.9)

        print(json.dumps({
            "success": True,
            "confidence": final_score,
            "user_id": int(id_siswa),  # dikembalikan sebagai int agar mudah dicocokkan dengan users.id di Laravel
            "message": f"Wajah teridentifikasi sebagai User ID {id_siswa} (confidence: {final_score}%)."
        }))
    else:
        # Distance >= 80 -> wajah dianggap tidak cocok dengan data manapun yang tersimpan
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": f"Wajah tidak cocok dengan data yang tersimpan (distance: {round(confidence, 2)}). Coba lagi dengan pencahayaan lebih baik."
        }))


if __name__ == "__main__":
    # Script ini dipanggil sebagai proses CLI oleh PythonRunner (PHP), bukan di-import sebagai modul
    if len(sys.argv) > 1:
        # Argumen pertama (sys.argv[1]) adalah path gambar yang dikirim dari Laravel
        image_path = sys.argv[1]
        process_face(image_path)
    else:
        # Tidak ada argumen path gambar yang diberikan saat pemanggilan script
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "Path gambar tidak diberikan."
        }))
