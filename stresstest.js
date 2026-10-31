import http from 'k6/http';
import { check, sleep } from 'k6';

// Konfigurasi 3. Stress Test Batas Beban Verifikasi (Hingga 50 VUs)
export const options = {
  stages: [
    { duration: '30s', target: 10 }, // Ramp-up ke 10 VUs (30 detik)
    { duration: '1m',  target: 30 }, // Naikkan beban ke 30 VUs (1 menit)
    { duration: '1m',  target: 50 }, // Stress peak load hingga 50 VUs (1 menit)
    { duration: '30s', target: 0 },  // Ramp-down ke 0 VUs (30 detik)
  ],
  thresholds: {
    http_req_duration: ['p(95)<25000'], // Toleransi batas response time stress test (25 detik)
    http_req_failed: ['rate<0.05'],     // Toleransi error rate maksimal 5% pada stress test
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
