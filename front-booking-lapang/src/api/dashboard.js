import { apiFetch } from './client'

// GET /api/admin/dashboard?dari=&sampai=  (khusus admin)
export function fetchDashboardAdmin(params = {}) {
  return apiFetch('/admin/dashboard', { auth: true, params })
}

// GET /api/pemilik/dashboard  (pemilik lapangan)
export function fetchDashboardPemilik() {
  return apiFetch('/pemilik/dashboard', { auth: true })
}