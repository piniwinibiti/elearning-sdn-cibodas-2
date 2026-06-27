import sys
import json
import os

def process_face(image_path):
    try:
        import cv2
    except ImportError:
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: Modul opencv-contrib-python tidak terinstall. Jalankan: pip install opencv-contrib-python"
        }))
        return

    path = os.path.dirname(os.path.abspath(__file__))
    model_path = os.path.join(path, 'trainer.yml')
    cascade_path = os.path.join(path, 'haarcascade_frontalface_default.xml')

    if not os.path.exists(model_path):
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: Model wajah (trainer.yml) belum dibuat. Silakan lakukan training terlebih dahulu di halaman admin."
        }))
        return

    if not os.path.exists(cascade_path):
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "ERROR: File haarcascade_frontalface_default.xml tidak ditemukan."
        }))
        return

    recognizer = cv2.face.LBPHFaceRecognizer_create()
    recognizer.read(model_path)
    face_cascade = cv2.CascadeClassifier(cascade_path)

    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"success": False, "confidence": 0, "message": "Gambar tidak dapat dibaca."}))
        return

    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.2, minNeighbors=5, minSize=(30, 30))

    if len(faces) == 0:
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "Wajah tidak terdeteksi di gambar."
        }))
        return

    largest_face = max(faces, key=lambda f: f[2] * f[3])
    (x, y, w, h) = largest_face

    id_siswa, confidence = recognizer.predict(gray[y:y+h, x:x+w])

    if confidence < 80:
        match_confidence = round(100 - confidence, 2)
        final_score = min(round(match_confidence, 2), 99.9)

        print(json.dumps({
            "success": True,
            "confidence": final_score,
            "user_id": int(id_siswa),
            "message": f"Wajah teridentifikasi sebagai User ID {id_siswa} (confidence: {final_score}%)."
        }))
    else:
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": f"Wajah tidak cocok dengan data yang tersimpan (distance: {round(confidence, 2)}). Coba lagi dengan pencahayaan lebih baik."
        }))


if __name__ == "__main__":
    if len(sys.argv) > 1:
        image_path = sys.argv[1]
        process_face(image_path)
    else:
        print(json.dumps({
            "success": False,
            "confidence": 0,
            "message": "Path gambar tidak diberikan."
        }))


