import http from 'k6/http';
import { check, sleep } from 'k6';

// Konfigurasi 4. Spike Test Lonjakan Beban Mendadak (Surge 50 VUs)
export const options = {
  stages: [
    { duration: '10s', target: 2 },  // Kondisi awal normal (2 VUs)
    { duration: '10s', target: 50 }, // LONJAKAN TIDA-TIBA (Spike 50 VUs dalam 10 detik)
    { duration: '1m',  target: 50 }, // Beban puncak lonjakan (1 menit)
    { duration: '10s', target: 2 },  // Pemulihan cepat kembali ke normal (2 VUs)
    { duration: '30s', target: 2 },  // Observasi pemulihan sistem (30 detik)
    { duration: '10s', target: 0 },  // Selesai (0 VUs)
  ],
  thresholds: {
    http_req_duration: ['p(95)<25000'], // Toleransi response time saat spike (< 25 detik)
    http_req_failed: ['rate<0.05'],     // Toleransi error rate maksimal 5%
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
