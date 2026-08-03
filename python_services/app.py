import os
import cv2
import time
import shutil
import threading
from flask import Flask, Response, jsonify, request
from flask_cors import CORS
from train import train_model

app = Flask(__name__)
# Izinkan CORS agar frontend Laravel bisa fetch API ini dari port yang berbeda
CORS(app)

# Global status tracker untuk proses registrasi
# Format: { user_id: { 'status': 'idle'|'capturing'|'training'|'completed'|'failed', 'current': 0, 'total': 100, 'message': '' } }
captures_status = {}
camera_lock = threading.Lock()

@app.route('/')
def index():
    return jsonify({
        "status": "online",
        "message": "Python Face Recognition Service is running"
    })

def generate_face_samples(user_id, total_samples=100):
    global captures_status
    
    # Buat atau bersihkan folder dataset untuk user ini
    dataset_dir = os.path.join("dataset", str(user_id))
    if os.path.exists(dataset_dir):
        shutil.rmtree(dataset_dir)
    os.makedirs(dataset_dir)

    captures_status[user_id] = {
        'status': 'capturing',
        'current': 0,
        'total': total_samples,
        'message': 'Membuka kamera...'
    }

    # Buka webcam
    camera = cv2.VideoCapture(0, cv2.CAP_DSHOW) # Menggunakan CAP_DSHOW untuk Windows agar lebih cepat
    if not camera.isOpened():
        # Coba buka tanpa CAP_DSHOW jika gagal
        camera = cv2.VideoCapture(0)
        
    if not camera.isOpened():
        captures_status[user_id] = {
            'status': 'failed',
            'current': 0,
            'total': total_samples,
            'message': 'Gagal mengakses webcam.'
        }
        print(f"[ERROR] Gagal membuka kamera untuk user {user_id}")
        return

    # Set resolusi 640x480 untuk mengoptimalkan performa & CPU usage
    camera.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    camera.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    # Load Haar Cascade
    face_cascade_path = cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
    face_cascade = cv2.CascadeClassifier(face_cascade_path)
    
    count = 0
    last_capture_time = 0
    capture_interval = 0.08 # minimal delay 80ms antar capture agar dataset bervariasi

    try:
        while count < total_samples:
            # Check status jika direset atau dihentikan di tengah jalan
            if user_id in captures_status and captures_status[user_id]['status'] in ['failed', 'idle']:
                break
                
            success, frame = camera.read()
            if not success:
                break

            # Mirror frame agar natural bagi user
            frame = cv2.flip(frame, 1)
            
            # Deteksi wajah menggunakan grayscale
            gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
            faces = face_cascade.detectMultiScale(
                gray, 
                scaleFactor=1.3, 
                minNeighbors=5, 
                minSize=(120, 120)
            )

            # Sortir wajah berdasarkan ukuran terbesar (mengurangi noise latar belakang)
            faces = sorted(faces, key=lambda f: f[2] * f[3], reverse=True)

            current_time = time.time()
            face_detected = len(faces) > 0

            if face_detected:
                x, y, w, h = faces[0]
                
                # Gambar box hijau di wajah
                cv2.rectangle(frame, (x, y), (x+w, y+h), (16, 185, 129), 2) # Emerald Green (#10b981)
                
                # Ambil sampel wajah jika interval waktu terpenuhi
                if current_time - last_capture_time >= capture_interval:
                    count += 1
                    
                    # Pangkas 10% bagian atas wajah (area rambut/dahi)
                    # PENTING: harus sama dengan preprocessing saat recognition
                    hair_offset = int(h * 0.10)
                    y_cropped = min(y + hair_offset, y + h - 1)
                    h_cropped = h - hair_offset
                    
                    # Crop wajah (dengan hair offset)
                    face_crop = gray[y_cropped:y_cropped+h_cropped, x:x+w]
                    # Resize wajah agar konsisten 200x200
                    face_resized = cv2.resize(face_crop, (200, 200))
                    
                    # Simpan gambar
                    img_path = os.path.join(dataset_dir, f"user_{user_id}_{count}.jpg")
                    cv2.imwrite(img_path, face_resized)
                    
                    last_capture_time = current_time
                    captures_status[user_id]['current'] = count
                    captures_status[user_id]['message'] = f"Mengambil sampel wajah: {count}/{total_samples}"

                # Overlay status
                cv2.putText(
                    frame, 
                    f"Sampel: {count}/{total_samples}", 
                    (20, 40), 
                    cv2.FONT_HERSHEY_SIMPLEX, 
                    0.7, 
                    (16, 185, 129), 
                    2
                )
            else:
                # Tulis instruksi jika tidak ada wajah
                cv2.putText(
                    frame, 
                    "Posisikan wajah Anda di depan kamera", 
                    (20, 40), 
                    cv2.FONT_HERSHEY_SIMPLEX, 
                    0.6, 
                    (59, 130, 246), 
                    2
                )

            # Encode frame ke JPEG untuk streaming
            ret, buffer = cv2.imencode('.jpg', frame)
            frame_bytes = buffer.tobytes()
            
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
            
            # Sleep sedikit untuk mengurangi beban CPU (capping frame rate ke ~30 FPS)
            time.sleep(0.03)

        # Proses training setelah capture selesai
        if count >= total_samples:
            captures_status[user_id]['status'] = 'training'
            captures_status[user_id]['message'] = 'Melakukan training model wajah (LBPH)...'
            
            # Panggil fungsi training
            train_res = train_model()
            
            if train_res["success"]:
                captures_status[user_id]['status'] = 'completed'
                captures_status[user_id]['message'] = 'Registrasi wajah dan training selesai!'
            else:
                captures_status[user_id]['status'] = 'failed'
                captures_status[user_id]['message'] = f'Training gagal: {train_res["message"]}'
        else:
            if captures_status[user_id]['status'] != 'failed':
                captures_status[user_id]['status'] = 'failed'
                captures_status[user_id]['message'] = 'Proses pengambilan sampel terputus.'

    except Exception as e:
        print(f"[ERROR] Exception terjadi saat capture: {str(e)}")
        captures_status[user_id] = {
            'status': 'failed',
            'current': count,
            'total': total_samples,
            'message': f'Error: {str(e)}'
        }
    finally:
        camera.release()
        print(f"[INFO] Kamera dilepas untuk user {user_id}")

@app.route('/register-feed/<int:user_id>')
def register_feed(user_id):
    # Coba lock kamera agar tidak tabrakan
    acquired = camera_lock.acquire(blocking=False)
    if not acquired:
        return Response(
            "Kamera sedang digunakan oleh sesi lain. Harap tunggu.", 
            status=409, 
            mimetype='text/plain'
        )
        
    def stream_wrapper():
        try:
            yield from generate_face_samples(user_id)
        finally:
            camera_lock.release()
            
    return Response(
        stream_wrapper(),
        mimetype='multipart/x-mixed-replace; boundary=frame'
    )

@app.route('/status/<int:user_id>')
def get_status(user_id):
    status = captures_status.get(user_id, {
        'status': 'idle',
        'current': 0,
        'total': 100,
        'message': 'Belum memulai.'
    })
    return jsonify(status)

@app.route('/reset/<int:user_id>', methods=['POST'])
def reset_status(user_id):
    global captures_status
    if user_id in captures_status:
        captures_status[user_id] = {
            'status': 'idle',
            'current': 0,
            'total': 100,
            'message': 'State direset.'
        }
    return jsonify({"success": True, "message": f"Status untuk user {user_id} telah direset"})

@app.route('/train', methods=['POST'])
def manual_train():
    res = train_model()
    return jsonify(res)

# ==========================================
# ABSENSI SCAN WAJAH (RECOGNITION)
# ==========================================
last_attendance_recognition = {"user_id": None, "confidence": 0.0, "timestamp": 0}
recognition_locked = False  # Flag: True jika data sudah dibaca dan sedang diproses oleh frontend

# Multi-frame voting: kumpulkan beberapa frame sebelum trigger absensi
# Menghindari false positive dari 1 frame yang kebetulan cocok
recognition_vote = {"user_id": None, "count": 0, "confidences": []}
REQUIRED_VOTES = 7  # Butuh 7 frame berurutan dengan ID yang sama (lebih ketat)
MAX_AVG_CONFIDENCE = 40.0  # Rata-rata confidence semua vote harus di bawah ini

def generate_attendance_frames():
    global last_attendance_recognition, recognition_locked, recognition_vote
    
    # Buka webcam
    camera = cv2.VideoCapture(0, cv2.CAP_DSHOW)
    if not camera.isOpened():
        camera = cv2.VideoCapture(0)
        
    if not camera.isOpened():
        print("[ERROR] Gagal membuka kamera untuk absensi")
        return

    # Set resolusi 640x480 untuk mengoptimalkan performa & CPU usage
    camera.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
    camera.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)

    # Inisialisasi recognizer dan load model
    try:
        recognizer = cv2.face.LBPHFaceRecognizer_create()
    except AttributeError:
        print("[ERROR] cv2.face tidak ditemukan.")
        return
        
    trainer_path = os.path.join("trainer", "trainer.yml")
    has_model = False
    
    if os.path.exists(trainer_path):
        try:
            recognizer.read(trainer_path)
            has_model = True
            print("[INFO] Model trainer.yml berhasil dimuat.")
        except Exception as e:
            print(f"[ERROR] Gagal membaca model: {str(e)}")
    else:
        print("[WARNING] trainer.yml tidak ditemukan. Lakukan registrasi wajah terlebih dahulu.")

    # Load Haar Cascade
    face_cascade_path = cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
    face_cascade = cv2.CascadeClassifier(face_cascade_path)

    try:
        while True:
            success, frame = camera.read()
            if not success:
                break
                
            frame = cv2.flip(frame, 1)
            gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
            
            # Deteksi wajah (minSize lebih besar untuk mengurangi noise dari wajah kecil/jauh)
            faces = face_cascade.detectMultiScale(
                gray, 
                scaleFactor=1.3, 
                minNeighbors=5, 
                minSize=(150, 150)
            )
            
            # Sortir wajah berdasarkan ukuran terbesar
            faces = sorted(faces, key=lambda f: f[2] * f[3], reverse=True)
            
            if len(faces) > 0 and has_model:
                x, y, w, h = faces[0]
                
                # Pangkas 10% bagian atas wajah (area rambut/dahi)
                # agar model lebih fokus ke fitur wajah inti: mata, hidung, mulut
                hair_offset = int(h * 0.10)
                y_cropped = min(y + hair_offset, y + h - 1)
                h_cropped  = h - hair_offset
                
                # Crop dan resize area wajah (tanpa rambut)
                face_crop = gray[y_cropped:y_cropped+h_cropped, x:x+w]
                face_resized = cv2.resize(face_crop, (200, 200))
                
                # CLAHE: normalisasi kontras cahaya (HARUS sama dengan preprocessing training)
                # Membantu ketika pencahayaan ruangan berbeda dengan saat registrasi
                clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
                face_resized = clahe.apply(face_resized)
                
                # Lakukan prediksi
                label_id, confidence = recognizer.predict(face_resized)
                
                # Threshold diperketat: 45.0 (dari 55.0)
                # LBPH: semakin kecil nilai confidence = semakin mirip
                # 0-30 = sangat mirip, 30-40 = mirip, 40-45 = cukup mirip, >45 = bukan orang yg sama
                if confidence < 45.0:
                    # Gambar box hijau
                    cv2.rectangle(frame, (x, y), (x+w, y+h), (16, 185, 129), 2)
                    
                    # Multi-frame voting: pastikan ID yang sama muncul REQUIRED_VOTES kali berurutan
                    if not recognition_locked:
                        if recognition_vote["user_id"] == int(label_id):
                            recognition_vote["count"] += 1
                            recognition_vote["confidences"].append(float(confidence))
                        else:
                            # ID berbeda dari sebelumnya — reset voting
                            recognition_vote["user_id"] = int(label_id)
                            recognition_vote["count"] = 1
                            recognition_vote["confidences"] = [float(confidence)]
                        
                        if recognition_vote["count"] >= REQUIRED_VOTES:
                            # Cek rata-rata confidence dari semua vote
                            avg_conf = sum(recognition_vote["confidences"]) / len(recognition_vote["confidences"])
                            
                            if avg_conf < MAX_AVG_CONFIDENCE:
                                # Sudah terkonfirmasi N frame berurutan dengan confidence bagus — trigger absensi
                                last_attendance_recognition = {
                                    "user_id": int(label_id),
                                    "confidence": float(avg_conf),
                                    "timestamp": time.time()
                                }
                                recognition_locked = True  # Kunci sampai frontend selesai proses
                                recognition_vote = {"user_id": None, "count": 0, "confidences": []}  # Reset voting
                                print(f"[ABSENSI] User {label_id} terdeteksi, avg confidence: {avg_conf:.1f}")
                            else:
                                # Confidence rata-rata terlalu tinggi — kemungkinan bukan orang yang benar
                                print(f"[REJECT] User {label_id} ditolak, avg confidence: {avg_conf:.1f} > {MAX_AVG_CONFIDENCE}")
                                recognition_vote = {"user_id": None, "count": 0, "confidences": []}  # Reset voting
                    
                    # Tampilkan info di frame
                    avg_display = 0.0
                    if recognition_vote["confidences"]:
                        avg_display = sum(recognition_vote["confidences"]) / len(recognition_vote["confidences"])
                    label_txt = f"ID:{label_id} ({round(confidence,1)}) [{recognition_vote['count']}/{REQUIRED_VOTES}]"
                    cv2.putText(
                        frame, 
                        label_txt, 
                        (x, y - 10), 
                        cv2.FONT_HERSHEY_SIMPLEX, 
                        0.55, 
                        (16, 185, 129), 
                        2
                    )
                else:
                    # Gambar box merah untuk tidak dikenal
                    cv2.rectangle(frame, (x, y), (x+w, y+h), (239, 68, 68), 2)
                    cv2.putText(
                        frame, 
                        "Tidak Dikenal", 
                        (x, y - 10), 
                        cv2.FONT_HERSHEY_SIMPLEX, 
                        0.6, 
                        (239, 68, 68), 
                        2
                    )
            else:
                # Tidak ada wajah terdeteksi di frame ini — reset voting counter
                # Ini mencegah akumulasi vote dari frame terpisah yang tidak berurutan
                if recognition_vote["count"] > 0:
                    recognition_vote = {"user_id": None, "count": 0, "confidences": []}
            
            if len(faces) > 0 and not has_model:
                # Wajah ada tapi model tidak ada
                x, y, w, h = faces[0]
                cv2.rectangle(frame, (x, y), (x+w, y+h), (245, 158, 11), 2)
                cv2.putText(
                    frame, 
                    "Model Belum Ditraining", 
                    (x, y - 10), 
                    cv2.FONT_HERSHEY_SIMPLEX, 
                    0.5, 
                    (245, 158, 11), 
                    2
                )
            
            # Tambahkan status info model pada frame
            status_txt = "Model: OK" if has_model else "Model: MISSING"
            color = (16, 185, 129) if has_model else (239, 68, 68)
            cv2.putText(
                frame, 
                status_txt, 
                (20, 30), 
                cv2.FONT_HERSHEY_SIMPLEX, 
                0.5, 
                color, 
                2
            )

            # Encode frame
            ret, buffer = cv2.imencode('.jpg', frame)
            frame_bytes = buffer.tobytes()
            
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
                   
            time.sleep(0.03) # Sekitar 30fps

    except Exception as e:
        print(f"[ERROR] Exception saat scan absensi: {str(e)}")
    finally:
        camera.release()
        print("[INFO] Kamera absensi dilepas.")

@app.route('/attendance-feed')
def attendance_feed():
    acquired = camera_lock.acquire(blocking=False)
    if not acquired:
        return Response(
            "Kamera sedang digunakan oleh sesi lain. Harap tunggu.", 
            status=409, 
            mimetype='text/plain'
        )
        
    def stream_wrapper():
        try:
            yield from generate_attendance_frames()
        finally:
            camera_lock.release()
            
    return Response(
        stream_wrapper(),
        mimetype='multipart/x-mixed-replace; boundary=frame'
    )

@app.route('/attendance-status')
def get_attendance_status():
    global last_attendance_recognition
    now = time.time()
    if last_attendance_recognition["user_id"] is not None and (now - last_attendance_recognition["timestamp"] < 3.0):
        data = {
            "user_id": last_attendance_recognition["user_id"],
            "confidence": last_attendance_recognition["confidence"]
        }
        # Jangan reset di sini — reset dilakukan setelah frontend selesai proses (via /attendance-unlock)
        return jsonify(data)
        
    return jsonify({"user_id": None})

@app.route('/attendance-unlock', methods=['POST'])
def attendance_unlock():
    """Dipanggil oleh frontend setelah absensi selesai diproses.
    Mereset data pengenalan wajah dan membuka kunci agar wajah baru bisa dideteksi.
    """
    global last_attendance_recognition, recognition_locked, recognition_vote
    last_attendance_recognition = {"user_id": None, "confidence": 0.0, "timestamp": 0}
    recognition_locked = False
    recognition_vote = {"user_id": None, "count": 0, "confidences": []}  # Reset voting saat unlock
    return jsonify({"success": True, "message": "Recognition lock dilepas"})

if __name__ == '__main__':
    # Jalankan server Flask di port 5000
    app.run(host='0.0.0.0', port=5000, debug=True)
