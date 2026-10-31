import http from 'k6/http';
import { check, sleep } from 'k6';

// Konfigurasi 2. Load Test Skalabilitas Verifikasi (10 VUs selama 3 Menit)
export const options = {
  stages: [
    { duration: '30s', target: 5 },  // Ramp-up 5 VUs (30 detik)
    { duration: '2m',  target: 10 }, // Peak Load 10 VUs (2 menit)
    { duration: '30s', target: 0 },  // Ramp-down (30 detik)
  ],
  thresholds: {
    http_req_duration: ['p(95)<5000'], // Threshold antrean server lokal (< 5 detik)
    http_req_failed: ['rate<0.01'],    // Error rate < 1%
  },
};

export default function () {
  const BASE_URL = 'http://127.0.0.1:8000';

  let res1 = http.get(`${BASE_URL}/`);
  check(res1, { 'Halaman Utama status 200': (r) => r.status === 200 });

  sleep(1);

  let res2 = http.get(`${BASE_URL}/mahasiswa/ukm`);
  check(res2, { 'Katalog UKM status 200': (r) => r.status === 200 });

  sleep(1);

  let res3 = http.get(`${BASE_URL}/mahasiswa/ukm/5`);
  check(res3, { 'Detail UKM status 200': (r) => r.status === 200 });

  sleep(1);
}
