import os
import cv2
import numpy as np
from PIL import Image

def train_model(dataset_dir="dataset", trainer_dir="trainer", model_name="trainer.yml"):
    print("[INFO] Memulai training model LBPH...")
    
    # Inisialisasi recognizer LBPH
    # Pastikan opencv-contrib-python terinstall untuk menggunakan cv2.face
    try:
        recognizer = cv2.face.LBPHFaceRecognizer_create()
    except AttributeError:
        print("[ERROR] cv2.face tidak ditemukan. Pastikan Anda menginstall 'opencv-contrib-python' bukan 'opencv-python' standar.")
        return {
            "success": False,
            "message": "cv2.face tidak ditemukan. Pastikan opencv-contrib-python sudah terinstall."
        }
        
    # Buat folder trainer jika belum ada
    if not os.path.exists(trainer_dir):
        os.makedirs(trainer_dir)
        
    if not os.path.exists(dataset_dir):
        print(f"[WARNING] Folder dataset '{dataset_dir}' tidak ditemukan. Membuat folder...")
        os.makedirs(dataset_dir)
        return {
            "success": False,
            "message": f"Folder dataset '{dataset_dir}' kosong atau baru dibuat."
        }

    face_samples = []
    ids = []
    
    # Baca folder dataset
    for item in os.listdir(dataset_dir):
        user_folder = os.path.join(dataset_dir, item)
        if os.path.isdir(user_folder):
            try:
                user_id = int(item)
            except ValueError:
                print(f"[WARNING] Nama folder '{item}' bukan integer (user_id). Dilewati.")
                continue
                
            for filename in os.listdir(user_folder):
                if filename.lower().endswith(('.jpg', '.jpeg', '.png')):
                    img_path = os.path.join(user_folder, filename)
                    try:
                        # Load image as grayscale menggunakan PIL
                        pil_image = Image.open(img_path).convert('L')
                        image_np = np.array(pil_image, 'uint8')
                        
                        # CLAHE: normalisasi kontras cahaya
                        # Membuat model lebih tahan terhadap perubahan pencahayaan
                        clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
                        image_np = clahe.apply(image_np)
                        
                        face_samples.append(image_np)
                        ids.append(user_id)
                    except Exception as e:
                        print(f"[ERROR] Gagal memuat gambar {img_path}: {str(e)}")
                        
    if len(face_samples) == 0:
        print("[WARNING] Tidak ada sampel wajah ditemukan di folder dataset.")
        return {
            "success": False,
            "message": "Tidak ada sampel wajah ditemukan di folder dataset."
        }
        
    # Proses training
    print(f"[INFO] Melatih model dengan {len(face_samples)} sampel wajah dari {len(set(ids))} user...")
    recognizer.train(face_samples, np.array(ids))
    
    # Simpan model
    model_path = os.path.join(trainer_dir, model_name)
    recognizer.write(model_path)
    print(f"[SUCCESS] Model disimpan ke {model_path}")
    
    return {
        "success": True,
        "message": f"Training sukses! {len(face_samples)} sampel wajah dilatih untuk {len(set(ids))} user.",
        "total_images": len(face_samples),
        "total_users": len(set(ids)),
        "trainer_path": model_path
    }

if __name__ == "__main__":
    # Menjalankan script secara standalone
    # Jalankan dari folder python_services
    result = train_model()
    print(result["message"])
