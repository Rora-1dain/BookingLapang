// ---------------------------------------------------------------------------
// Tipis wrapper di atas fetch() untuk bicara ke backend Laravel
// (routes/api.php). Base URL default '/api' supaya jalan lewat proxy Vite
// (lihat vite.config.js) saat development — tidak perlu utak-atik CORS di
// backend. Untuk production, set VITE_API_URL ke domain backend kamu, mis.
// https://api.bookinglapang.com/api
// ---------------------------------------------------------------------------

const BASE_URL = import.meta.env.VITE_API_URL || '/api'
const TOKEN_KEY = 'bookinglapang_token'

export function getToken() {
  return localStorage.getItem(TOKEN_KEY)
}

export function setToken(token) {
  if (token) {
    localStorage.setItem(TOKEN_KEY, token)
  } else {
    localStorage.removeItem(TOKEN_KEY)
  }
}

export class ApiError extends Error {
  constructor(message, status, payload) {
    super(message)
    this.status = status
    this.payload = payload
  }
}

export async function apiFetch(path, { method = 'GET', body, auth = false, params } = {}) {
  let url = `${BASE_URL}${path}`

  if (params) {
    const query = new URLSearchParams(
      Object.entries(params).filter(([, v]) => v !== undefined && v !== null && v !== '')
    ).toString()
    if (query) url += `?${query}`
  }

  const isFormData = body instanceof FormData

  const headers = {
    Accept: 'application/json',
  }
  // FormData: biarkan browser yang set Content-Type (butuh boundary multipart)
  if (body && !isFormData) headers['Content-Type'] = 'application/json'
  if (auth) {
    const token = getToken()
    if (token) headers.Authorization = `Bearer ${token}`
  }

  const res = await fetch(url, {
    method,
    headers,
    body: body ? (isFormData ? body : JSON.stringify(body)) : undefined,
  })

  const isJson = res.headers.get('content-type')?.includes('application/json')
  const data = isJson ? await res.json().catch(() => null) : null

  if (res.ok && !isJson) {
    throw new ApiError(
      'Server balas non-JSON. Cek VITE_API_URL (harus akhiran /api).',
      res.status,
      null
    )
  }

  if (!res.ok) {
    const message =
      data?.message ||
      (data?.errors && Object.values(data.errors).flat()[0]) ||
      `Permintaan gagal (${res.status})`
    throw new ApiError(message, res.status, data)
  }

  return data
}
