import { apiFetch } from './client'

export function login(email, password) {
  return apiFetch('/login', { method: 'POST', body: { email, password } })
}

// role: 'user' (pemesan lapangan) | 'pemilik_lapangan'
export function register({ name, email, password, password_confirmation, kode_referral, role }) {
  return apiFetch('/register', {
    method: 'POST',
    body: { name, email, password, password_confirmation, kode_referral, role },
  })
}

export function me() {
  return apiFetch('/me', { auth: true })
}

export function logout() {
  return apiFetch('/logout', { method: 'POST', auth: true })
}