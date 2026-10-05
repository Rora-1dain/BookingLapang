import { apiFetch } from './client'

// GET /api/admin/audit — audit log platform (baca saja, append-only)
export async function fetchAuditLog(params = {}) {
  const json = await apiFetch('/admin/audit', { auth: true, params })
  return {
    data: json.data ?? [],
    meta: json.meta ?? null,
    daftarAksi: json.daftar_aksi ?? [],
  }
}
