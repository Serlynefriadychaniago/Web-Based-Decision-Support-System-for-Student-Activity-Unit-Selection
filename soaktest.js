import http from 'k6/http';
import { check, sleep } from 'k6';

// Konfigurasi 5. Soak Test / Endurance Test (Beban Konstan 10 VUs Durasi Panjang)
export const options = {
  stages: [
    { duration: '30s', target: 10 }, // Ramp-up ke 10 VUs (30 detik)
    { duration: '4m',  target: 10 }, // Beban konstan 10 VUs (4 menit durasi panjang)
    { duration: '30s', target: 0 },  // Ramp-down ke 0 VUs (30 detik)
  ],
  thresholds: {
    http_req_duration: ['p(95)<5000'], // Threshold response time stabil (< 5 detik)
    http_req_failed: ['rate<0.01'],    // Error rate konsisten < 1%
  },
};

export default function () {
  const BASE_URL = 'http://127.0.0.1:8000';

  let res1 = http.get(`${BASE_URL}/`);
  check(res1, { 'Halaman Utama status 200': (r) => r.status === 200 });

  sleep(2);

  let res2 = http.get(`${BASE_URL}/mahasiswa/ukm`);
  check(res2, { 'Katalog UKM status 200': (r) => r.status === 200 });

  sleep(2);

  let res3 = http.get(`${BASE_URL}/mahasiswa/ukm/5`);
  check(res3, { 'Detail UKM status 200': (r) => r.status === 200 });

  sleep(2);
}
